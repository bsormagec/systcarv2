<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use App\Models\tenant\Category;
use App\Models\tenant\ProductUnit;
use App\Models\tenant\ProductWarehouse;
use Carbon\Carbon;
use App\Models\tenant\Account;
/*
 * traits para registrar entrada a caja en las compras 
 */
trait MovementPurchases
{
  public function expense_money_purchase($provider,$purchase_id,$cantidad,$box){
    $category = Category::where('descripcion','=','PAGO A PROVEEDORES')
                          ->where('type','expense')->first();
    $proveedor = $provider['business_name'];
    //creamos la transaccion
    $box->transacctions()->create([
        'sucursal_id' => session()->get('sucursal'),
        'type' => 'expense',
        'paid_at' => Carbon::now(),
        'currency_code' => $box->currency->code,
        'currency_rate' => $box->currency->rate,
        'amount' => $cantidad,
        'contact_id' => 0,
        'description' => "Pago a proveedor $proveedor, codigo de compra nro:$purchase_id",
        'category_id' => $category->id,
        'user_id' => auth()->user()->id
    ]);
  }

  public static function getUserrunbox()
  {
    return $usuariorunbox = Account::with('currency:id,name,code,rate')
                                    ->join('run_boxes as rb', 'rb.account_id', 'accounts.id')
                                    ->join('users as u', 'u.id', 'rb.user_on_id')
                                    ->select('accounts.id','accounts.opening_balance','accounts.name','accounts.currency_id')
                                    ->where('accounts.status','iniciada')
                                    ->where('rb.status',1)
                                    ->whereDate('rb.created_at','=',Carbon::now())
                                    ->where('rb.user_on_id', auth()->user()->id)
                                    ->first();
  }

  public function create_or_update_warehouse($items,$provider,$purchase_id){
    $proveedor = $provider['business_name'];
    foreach ($items as $item) {
        $producto = ProductWarehouse::where('product_id',$item['product_id'])
                                    ->where('warehouse_id',$item['almacen'])
                                    ->first();
        $cantidad = $item['quantity'] * $item['quantity_unit'];
        if (is_null($producto)) {
            $newproducto = new ProductWarehouse;
            $newproducto->product_id = $item['product_id'];
            $newproducto->warehouse_id = $item['almacen'];
            $newproducto->stock = $cantidad;
            $newproducto->save(); 
        }else{
            $producto->increment('stock',$cantidad);
            $producto->update();
        }
        //creamos el movimiento de entrada de producto
        $category = Category::where('nombre','=','COMPRA')
                          ->where('type','incomeproducts')->first();
        $category->movementproducts()->create([
            'product_id' => $item['product_id'],
            'cantidad' => $item['quantity'],
            'unit_id' => $item['unit_id'],
            'detalle' => 'Compra a proveedor '.$proveedor.', NRO:'.$purchase_id,
            'user_id' => auth()->user()->id,
            'sucursal_id' => session()->get('sucursal'), 
            'warehouse_id' => $item['almacen'],
            'type' => 'income'
        ]);
    }
  }

  public function cancel_purchase($purchase)
  {
      foreach ($purchase->items as $item) {
          $producto = ProductWarehouse::where('product_id',$item->product_id)
                                      ->where('warehouse_id',$item->almacen)
                                      ->first();
          if (isset($item->unit_id)) {
                $productunit = ProductUnit::where('product_id',$item->product_id)
                                            ->where('unit_id',$item->unit_id)
                                            ->first();
                $cantidad = $item->quantity * $productunit->cantidad_unidad; 
                $producto->decrement('stock',$cantidad);   
                $producto->update();                           
          }
          
          //creamos movimientos de productos
          $typeincome = Category::where('nombre','ANULACION DE COMPRA')
                                  ->where('type','expense')->first();
          $typeincome->movementproducts()->create([
              'product_id' => $item->product_id,
              'cantidad' => $item->quantity,
              'unit_id' => $item->unit_id,
              'detalle' => 'Por anulacion de compra ID nro:'.$item->purchase->id,
              'user_id' => auth()->user()->id,
              'sucursal_id' => session()->get('sucursal'), 
              'warehouse_id' => $item->almacen,
              'type' => 'expense'
          ]);
      }
      if ($purchase->expense_money_box) { 
          //quitamos el dinero en caja
          $box = $this->getBoxcurrentUser(); 
          $box->opening_balance += $purchase->sub_total;
          $box->update();
          //creamos la transaccion de dinero
          $category = Category::where('nombre','ANULACION DE COMPRA')
                                ->where('type','expense')->first();
          
          $box->transacctions()->create([
              'sucursal_id' => session()->get('sucursal'),
              'type' => 'income',
              'paid_at' => Carbon::now(),
              'currency_code' => $box->currency->code,
              'currency_rate' => $box->currency->rate,
              'amount' => $purchase->sub_total,
              'contact_id' => 0,
              'description' => 'Por anulacion de compra ID nro:'.$purchase->id,
              'category_id' => $category->id,
              'user_id' => auth()->user()->id
          ]);
      }
      
  }
  static function getBoxcurrentUser()
  {
      return $usuariorunbox = Account::with('currency:id,name,code,rate')
                                  ->join('run_boxes as rb', 'rb.account_id', 'accounts.id')
                                  ->join('users as u', 'u.id', 'rb.user_on_id')
                                  ->select('accounts.id','accounts.opening_balance','accounts.name','accounts.currency_id')
                                  ->where('accounts.status','iniciada')
                                  ->where('rb.status',1)
                                  ->whereDate('rb.created_at','=',Carbon::now())
                                  ->where('rb.user_on_id', auth()->user()->id)
                                  ->first();
  }
}