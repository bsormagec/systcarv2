<?php

namespace Augusto\Purchase\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;
use App\Models\tenant\Product;
use App\Models\tenant\ProductWarehouse;
use App\Models\tenant\Purchase;
use App\Models\tenant\Account;
use App\Models\tenant\Contact;
use App\Models\tenant\Warehouse;
use App\Traits\MovementPurchases;
use DB;
use Augusto\Purchase\Http\Resources\PurchaseCollection;

class PurchaseController extends Controller {
  
   use MovementPurchases;

   public function index (Request $request) {
    return new PurchaseCollection(Purchase::orderBy('id','DESC')->paginate(10));
   }
   
   public function search($field,$search)
    {
        if ($field == "provider") {
           return new PurchaseCollection(Purchase::whereHas('provider', function ($query) use ($search) {
                                        $query->where('type','provider')
                                              ->where('business_name', 'like', "%{$search}%")
                                              ->orWhere('ci','like',"%{$search}%")
                                              ->orWhere('phone','like',"%{$search}%")
                                              ->orWhere('email','like',"%{$search}%");
                                    })
                                    ->paginate(10)
                                    );
        }
        return 
            new PurchaseCollection(Purchase::where($field,'LIKE',"%$search%")
                                        ->latest()
                                        ->paginate(10)
                                    );
    }

   public function create() {
    $warehouses = Warehouse::select('nombre','id')->get();
    $form = [
        'provider_id' => null,
        'provider' => null,
        'providers' => [],
        'warehouses' => $warehouses,
        'warehouse' => 1,
        'nit' => 0,
        'date' => date('Y-m-d'),
        'dui_number' => 0,
        'authorization_number' => 0,
        'invoice_number' => 0,
        'exempt_amount' => 0,
        'discount' => 0,
        'sub_total' => 0,
        'total_purchase'=> 0,
        'amount_base_cf' => 0,
        'credito_fiscal' => 0,
        'codigo_control' => 0,
        'type_purchase' => '',
        'observation' => 'Ninguna...',
        'reg_compra_caja' => false,
        'with_invoice' => false,
        'items' => [
            [
                'product_id' => null,
                'product' => null,
                'description' => '',
                'purchase_price' => 0,
                'quantity' => 1,
                'almacen' => 1,
                'quantity_unit' => 0,
                'unit_id' => 1,
                'units' => []
            ]
        ]
    ];

    return response()
        ->json(['form' => $form]);
   }
   
   public function store(Request $request) {
    $request->validate([
        'contact_id' => 'required|integer|exists:contacts,id',
        'date' => 'required|date_format:Y-m-d',
        //'invoice_number' => 'required|numeric',
        //'warehouse' => 'required|integer|exists:warehouses,id',
        'observation' => 'nullable|max:2000',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|integer|exists:products,id',
        'items.*.purchase_price' => 'required|numeric|min:0',
        'items.*.quantity' => 'required|integer|min:1'
    ]);
    $compra = new Purchase;
    $compra->fill($request->except('items'));
    $compra->status = "V";
    $compra->user_id = auth()->user()->id;
    $compra->sucursal_id = session()->get('sucursal');
    $box = $this->getUserrunbox();
    if (is_bool($request->reg_compra_caja) === true) {
        $compra->expense_money_box = true;
        if ($box->opening_balance < $request->sub_total) {
            return response()
                    ->json(['mensage' => 'La cantidad en caja es menor a la que desea sacar']);
        }
    }
    $compra = DB::transaction(function() use ($compra, $request,$box) {
        
        // custom method from app/Helper/HasManyRelation
        $compra->storeHasMany([
            'items' => $request->items
        ]);
        $detalle = collect($request->items);
        //$almacen = intval($request->warehouse);
        
        $this->create_or_update_warehouse($detalle,$request->provider,$compra->id);

        if (is_bool($request->reg_compra_caja) === true) {
            $this->expense_money_purchase($request->provider,$compra->id,$request->sub_total,$box);
            $box->opening_balance -= $request->sub_total;
            $box->update();
        }
        return $compra;
    });

    return response()
        ->json(['saved' => true, 'id' => $compra->id]);

   }

    public function show($id)
    {
        $model = Purchase::with(['provider:id,business_name,address',
                                 'items.product:id,nombre,lote','items.unit:id,abreviacion'])
            ->findOrFail($id);

        return response()
            ->json(['model' => $model]);
    }

    public function edit($id)
    {
        $form = Purchase::with(['provider', 'items.product'])
                        ->findOrFail($id);
        return response()
            ->json(['form' => $form]);
    }

    public function update($id, Request $request)
    {
        $compra = Purchase::findOrFail($id);

        $request->validate([
            'contact_id' => 'required|integer|exists:contacts,id',
            'fecha' => 'required|date_format:Y-m-d',
            'nro_factura' => 'required|numeric',
            'observacion' => 'nullable|max:2000',
            'items' => 'required|array|min:1',
            'items.*.id' => 'sometimes|required|integer|exists:detalles_compras,id,compra_id,'.$compra->id,
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.purchase_price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        $compra->fill($request->except('items'));
        $compra->user_id = auth()->user()->id;
        $compra->company_id = auth()->user()->company->id;
        $compra->total_compra = collect($request->items)->sum(function($item) {
            return $item['quantity'] * $item['purchase_price'];
        });

        $compra = DB::transaction(function() use ($compra, $request) {
            // custom method from app/Helper/HasManyRelation
            $compra->updateHasMany([
                'items' => $request->items
            ]);

            return $compra;
        });

        return response()
            ->json(['saved' => true, 'id' => $compra->id]);
    }

   public function selectprovider() {
    $results = Contact::orderBy('id')
                        ->when(request('q'), function($query) {
                            $query->where('business_name', 'like', '%'.request('q').'%')
                            ->orWhere('phone', 'like', '%'.request('q').'%')
                            ->orWhere('email', 'like', '%'.request('q').'%');
                        })
                        ->where('type','provider')
                        ->select('id','business_name','ci')
                        ->limit(6)
                        ->get();

      return response()
            ->json(['results' => $results]);
   }

   public function selectproduct() {
    // $results = Product::orderBy('id','desc')
    //                     ->when(request('q'), function($query) {
    //                         $query->where('nombre', 'like', '%'.request('q').'%')
    //                         ->orWhere('lote', 'like', '%'.request('q').'%');
    //                     })
    //                     ->limit(6)
    //                     ->get();

    //   return response()
    //         ->json(['results' => $results]);
    $results = Product::with('units:id,nombre,abreviacion')
                        ->select('id','nombre','lote')
                        ->orderBy('id','desc')
                        ->when(request('q'), function($query) {
                            $query->where('nombre', 'like', '%'.request('q').'%')
                            ->orWhere('lote', 'like', '%'.request('q').'%');
                        })
                        ->limit(6)
                        ->get();
    $map = $results->map(
        function($items){
                $data['COD'] = $items->id;
                $data['nombre'] = $items->nombre;
                $data['lote'] = $items->lote;
                $data['idalmacen'] = $items->idalmacen;
                $data['stock'] = $items->stock;
                $data['text'] = $items->text;
                $data['almacen'] = $items->almacen;
                $data['units'] = $items->units;
                return $data;
            }
        );
      return response()
            ->json(['results' => $map]);
   }

   public function destroy($id)
    {
        $purchase = Purchase::with('items')
                      ->select('id','sub_total','contact_id','user_id','expense_money_box')
                      ->where('id',$id)->first();
       
        //sacamos productos del almacen correspondiente
        DB::transaction(function () use($purchase) {
            $this->cancel_purchase($purchase);
            $purchase->delete();
        });
        return response()
            ->json([
                'model' => $purchase,
                'deleted' => true
                ]);
    }
}