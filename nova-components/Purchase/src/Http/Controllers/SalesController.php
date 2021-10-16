<?php

namespace Augusto\Purchase\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;
use App\Models\tenant\Product;
use App\Models\tenant\ProductWarehouse;
use Luecano\NumeroALetras\NumeroALetras;
use App\Models\tenant\Sale;
use App\Models\tenant\Contact;
use App\Models\tenant\Warehouse;
use App\Models\tenant\Dosification;
use App\Models\tenant\Setting;
use App\Traits\MovementSales;
use App\Traits\Cuf;
use App\Traits\CodigoControl;
use DB;
use App\Utilities\Overrider;
use Augusto\Purchase\Http\Resources\SaleCollection;

class SalesController extends Controller {
  
   use MovementSales,Cuf,CodigoControl;

   public function filtersales($tipoventa='normal'){  
    $tipo = setting('default.type_payment_tocreditsales');  
    switch ($tipoventa) {
        case 'normal':
            return new SaleCollection(Sale::where('typepayment_id','!=',$tipo)
                                            ->where('electronic_bill',false)
                                            ->orderBy('id','DESC')->paginate(10));
            break;
        case 'credit':
            return new SaleCollection(Sale::where('typepayment_id','=',$tipo)
                                            ->where('electronic_bill',false)
                                            ->orderBy('id','DESC')->paginate(10));
            break;
        case 'electronic':
            return new SaleCollection(Sale::where('electronic_bill',true)
                                            ->orderBy('id','DESC')->paginate(10));
            break;
        default:
            break;
    }
   }

   public function search($field,$type,$search)
    {
        $tipo = setting('default.type_payment_tocreditsales');
        switch ($type) {
            case 'normal':
                if ($field == "cliente") {
                    return new SaleCollection(
                                            Sale::where('typepayment_id','!=',$tipo)
                                                ->where('electronic_bill',false)
                                                ->whereHas('customer', function ($query) use ($search) {
                                                 $query->where('fullName', 'like', "%{$search}%")
                                                       ->orWhere('ci','like',"%{$search}%")
                                                       ->orWhere('phone','like',"%{$search}%");
                                             })
                                             ->paginate(10)
                                             );
                 }
                 return new SaleCollection(Sale::where('typepayment_id','!=',$tipo)
                                                ->where('electronic_bill',false)
                                                 ->where($field,'LIKE',"%$search%")
                                                 ->latest()
                                                 ->paginate(10)
                                             );
                break;
            case 'credit':
                if ($field == "cliente") {
                    return new SaleCollection(Sale::where('typepayment_id','=',$tipo)
                                                    ->where('electronic_bill',false)
                                                    ->whereHas('customer', function ($query) use ($search) {
                                                    $query->where('fullName', 'like', "%{$search}%")
                                                        ->orWhere('ci','like',"%{$search}%")
                                                        ->orWhere('phone','like',"%{$search}%");
                                                })
                                             ->paginate(10)
                                             );
                 }
                 return new SaleCollection(Sale::where('typepayment_id','=',$tipo)
                                                ->where('electronic_bill',false)
                                                ->where($field,'LIKE',"%$search%")
                                                 ->latest()
                                                 ->paginate(10)
                                             );
                break;
            case 'electronic':
                if ($field == "cliente") {
                    return new SaleCollection(Sale::where('electronic_bill',true)
                                                ->whereHas('customer', function ($query) use ($search) {
                                                    $query->where('fullName', 'like', "%{$search}%")
                                                        ->orWhere('ci','like',"%{$search}%")
                                                        ->orWhere('phone','like',"%{$search}%");
                                                })
                                             ->paginate(10)
                                             );
                 }
                 return new SaleCollection(Sale::where('electronic_bill',true)
                                                ->where($field,'LIKE',"%$search%")
                                                 ->latest()
                                                 ->paginate(10)
                                             );
                break;
            default:
                # code...
                break;
        }
    }
   
   public function create() {
    return $this->createsale();
   }
   
   public function store(Request $request) {
    $request->validate([
        'contact_id' => 'required|integer|exists:contacts,id',
        'date' => 'required|date_format:Y-m-d',
        'typepayment' => 'required',
        'typedocument' => 'required',
        'document' => 'required',
        'received' => 'required|min:1',
        'turned' => 'required',
        'observation' => 'nullable|max:2000',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|integer|exists:products,id',
        'items.*.sale_price' => 'required|numeric|min:0',
        'items.*.quantity' => 'required|integer|min:1'
    ]);
    $sale = new Sale;
    $sale->fill($request->except('items'));
    $sale->status = "V";
    $sale->user_id = auth()->user()->id;
    $sale->typepayment_id = $request->typepayment;
    $sale->typedocument_id = $request->typedocument;
    $sale->sucursal_id = session()->get('sucursal');
    $dosif = null;
    $nit = null;
    if ($request->facturar) {
        //validacion si la venta es a credito
        if ($request->defaultpaymet_for_creditsales == $request->typepayment) {
            return response()
                ->json(['mensage' => 'Las ventas a creditos no son permitidas en la modalidad de facturacion electronica']);
        }
         $dosif = $this->get_dosificacion();
         $nit = setting('company.nit');
         if (!$dosif || !$nit) {
            return response()
                ->json(['mensage' => 'Agrege Llave de Dosificacion, Numero de Autorizacion o revise su Nit porfavor']);
        }
    }
    
    $sale = DB::transaction(function() use ($sale, $request,$dosif,$nit) {
        if ($request->facturar) {
            $cuf = $this->obtenerCUF($nit, date('Ymd', strtotime($request->date)), session()->get('sucursal'), 1, 1, 1, 0, 10, 0);
            $sale->cuf = $cuf;
            if ($dosif) {
                $sale->electronic_bill = true;
                $dosif->numero_actual += 1;
                $codigo_control = $this->generar($dosif->nro_autorizacion, $dosif->numero_actual, $nit, date('Ymd', strtotime($request->date)), $request->subtotal, $dosif->llave_dosificacion);
                $res=trim(str_replace(array('-','.'), '', $codigo_control));
                $code = $cuf.$res;
                $sale->invoice_number = $dosif->numero_actual;
                $sale->cuf = $code;
                $sale->authorization_number = $dosif->nro_autorizacion;
                $sale->control_code = $codigo_control;
                $dosif->update();
            }
        }
        
        // custom method from app/Helper/HasManyRelation
        $sale->storeHasMany([
            'items' => $request->items
        ]);
        $detalle = collect($request->items);
        $credito = $request->defaultpaymet_for_creditsales;
        $tipopago = $request->typepayment;
        $this->decrement_to_warehouse_and_create_movement_product($detalle,$request,$sale,$credito,$tipopago);
        return $sale;
    });

    return response()
        ->json(['saved' => true, 'id' => $sale->id]);

   }

    public function show($id)
    {
        $model = Sale::with(['customer:id,fullName',
                            'items.product:id,nombre,lote',
                            'items.unit:id,abreviacion'
                            ])
                        ->select('id','cuf','contact_id','date','status','subtotal','observations','user_id')
                        ->first($id);
                        
        return response()
            ->json(['model' => $model]);
    }

   public function selectcustomer() {
    $results = Contact::orderBy('id')
                        ->when(request('q'), function($query) {
                            $query->where('fullName', 'like', '%'.request('q').'%')
                            ->orWhere('ci', 'like', '%'.request('q').'%')
                            ->orWhere('phone', 'like', '%'.request('q').'%');
                        })
                        ->where('type','customer')
                        ->limit(6)
                        ->get();

      return response()
            ->json(['results' => $results]);
   }
   
   public function selectproduct() {
    $results = Product::with('units:id,nombre,abreviacion')
                        ->join('product_warehouse as pw','pw.product_id','products.id')
                        ->join('warehouses as alm','alm.id','pw.warehouse_id')
                        ->select('products.id','products.nombre','products.lote','alm.id as idalmacen',
                                'pw.stock','alm.nombre as almacen')
                        ->orderBy('products.id','desc')
                        ->when(request('q'), function($query) {
                            $query->where('products.nombre', 'like', '%'.request('q').'%')
                            ->orWhere('products.lote', 'like', '%'.request('q').'%');
                        })
                        ->having('pw.stock', '>', 3)
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
        $sale = Sale::with('items')
                      ->select('id','subtotal','contact_id','user_id','typepayment_id','cuf')
                      ->where('id',$id)->first();
        //devolver productos a su almacen correspondiente
        DB::transaction(function () use($sale) {
            $this->cancel_sale($sale);
            $sale->delete();
        });
        return response()
            ->json([
                'model' => $sale,
                'deleted' => true
                ]);
    }

    public function printInvoice($id)
    {
        $invoice = Sale::with([
            'customer:id,fullName,address,ci,email',
            'items.unit:id,abreviacion',
            'items.product:id,nombre,lote,descripcion_small'
            ])
            ->select('id','invoice_number','cuf','document','contact_id','date','electronic_bill',
            'status','subtotal','observations','amount','discount','amount_base','user_id')
            ->first($id);
        
        //$company = Setting::where('sucursal_id',session()->get('sucursal'))->get();
        $monto_literal = (new NumeroALetras())->toInvoice($invoice->subtotal, 2, 'BOLIVIANOS', 'CENTAVOS');
        $view = view('tenant.sales.invoice.print_modern',compact('invoice','monto_literal'));

        return mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');
    }
}