<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use App\Models\tenant\ProductWarehouse;
use App\Models\tenant\ProductUnit;
use Carbon\Carbon;
use App\Models\tenant\Account;
use App\Models\tenant\TypePayment;
use App\Models\tenant\Type;
use App\Models\tenant\Dosification;
use App\Models\tenant\Category;
use App\Models\tenant\AccountReceivable;
use App\Models\tenant\Setting;
/*
 * traits para definicr metodos para facilitar el registro de ventas 
 */
trait MovementSales
{
    public function createsale(){
        $default_to_credit = setting('default.type_payment_tocreditsales');
        $facturar = setting('invoices.invoice_the_sale');
        $form = [
            'contact_id' => null,
            'customer' => null,
            'customers' => [],
            'typepayments' => Type::where('tipo','payment')->select('id','name','description')->get(),
            'typepayment' => 1,
            'typedocuments'=> Type::where('tipo','document')->select('id','name')->get(),
            'defaultpaymet_for_creditsales' => $default_to_credit,
            'typedocument', 1,
            'document' => 0,
            'date' => date('Y-m-d'),
            'deadline' => null,
            'dui_number' => 0,
            'received' => 0,
            'turned' => 0,
            'exempt_amount' => 0,
            'discount' => 0,
            'subtotal' => 0,
            'amount' => 0,
            'amount_base' => 0,
            'debito_fiscal' => 0,
            'facturar' => intval($facturar),
            'observations' => '',
            'items' => [
                [
                    'product_id' => null,
                    'product' => null,
                    'description' => '',
                    'sale_price' => 0,
                    'quantity' => 1,
                    'quantity_unit' =>0,
                    'stock' => 0,
                    'almacen' =>null,
                    'unit_id' => null,
                    'units' => []
                ]
            ]
        ];
        return response()->json(['form' => $form]);
    }

    public function decrement_to_warehouse_and_create_movement_product($items,$request,$sale,$credit,$typepayment){
        $typeexpense = Category::where('nombre','=','VENTA')
                                ->where('type','expenseproducts')->first(); 
        $cliente = $request->customer['fullName'];
        $payment_to_cach = setting('default.type_payment_to_chash');
        $category = Category::where('nombre','VENTA')
                             ->where('type','income')->first();  
        $idventa = $sale->cuf?? $sale->id;
        if ($payment_to_cach == $typepayment || $request->received > 0) {
            //agregamos el dinero en caja
            $box = $this->getBoxcurrentUser();
            $box->opening_balance += $sale->subtotal;
            $box->update();
            //creamos la transaccion de dinero
            $box->transacctions()->create([
                'sucursal_id' => session()->get('sucursal'),
                'type' => 'income',
                'paid_at' => Carbon::now(),
                'currency_code' => $box->currency->code,
                'currency_rate' => $box->currency->rate,
                'amount' => $sale->subtotal,
                'contact_id' => 0,
                'description' => 'Venta a cliente '.$cliente.', CUF|ID nro:'.$idventa,
                'category_id' => $category->id,
                'user_id' => auth()->user()->id
            ]);
        }
        //crear las cuentas por cobrar si la venta es a credito
        if ($credit == $typepayment) {
            $this->create_account_receivable($sale,$cliente);
        }
        foreach ($items as $item) {
            $producto = ProductWarehouse::where('product_id',$item['product_id'])
                                        ->where('warehouse_id',$item['almacen'])
                                        ->first();
            $cantidad = $item['quantity'] * $item['quantity_unit'];
            $producto->decrement('stock',$cantidad);
            $producto->update();
            $typeexpense->movementproducts()->create([
                'product_id' => $item['product_id'],
                'cantidad' => $item['quantity'],
                'unit_id' => $item['unit_id'],
                'detalle' => 'Venta a cliente '.$cliente.', CUF|ID nro:'.$idventa,
                'user_id' => auth()->user()->id,
                'sucursal_id' => session()->get('sucursal'), 
                'warehouse_id' => $item['almacen'],
                'type' => 'expense'
            ]);
        }
    }

    public function get_dosificacion(){
        $fecha_actual = date('Y-m-d');
        return Dosification::where('fecha_limite', '>', $fecha_actual)->select('id', 'nro_autorizacion', 'numero_actual', 'llave_dosificacion', 'fecha_limite')->first();
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

    static function create_account_receivable($sale,$customer){
        $cuenta = new AccountReceivable;
        $idventa = $sale->cuf?? $sale->id;
        $cuenta->user_id = auth()->user()->id;
        $cuenta->quantity = $sale->subtotal;
        $cuenta->detail = 'Venta a cliente '.$customer.', CUF|ID nro:'.$idventa;
        $cuenta->deadline = $sale->deadline;
        $cuenta->contact_id = $sale->contact_id;
        $cuenta->sucursal_id = session()->get('sucursal');
        $cuenta->save();

        $cuenta->details()->create([
            'amount' => $sale->received,
            'detail' => 'Venta a cliente '.$customer.', CUF|ID nro:'.$idventa,
            'date' => Carbon::now(),
            'user_id'=> $sale->user_id
        ]);
    }

    public function cancel_sale($sale)
    {
        $idventa = $sale->cuf?? $sale->id;
        foreach ($sale->items as $item) {
            $producto = ProductWarehouse::where('product_id',$item->product_id)
                                        ->where('warehouse_id',$item->almacen)
                                        ->first();
            if (isset($item->unit_id)) {
                $productunit = ProductUnit::where('product_id',$item->product_id)
                                            ->where('unit_id',$item->unit_id)
                                            ->first();
            $cantidad = $item->quantity * $productunit->cantidad_unidad; 
            $producto->increment('stock',$cantidad);                               
            }
            $producto->update();

            //creamos movimientos de productos
            $typeincome = Category::where('nombre','ANULACION DE VENTA')
                                    ->where('type','incomeproducts')->first();
            $typeincome->movementproducts()->create([
                'product_id' => $item->product_id,
                'cantidad' => $cantidad,
                'unit_id' => $item->unit_id,
                'detalle' => 'Por anulacion de venta CUF|ID nro:'.$idventa,
                'user_id' => auth()->user()->id,
                'sucursal_id' => session()->get('sucursal'), 
                'warehouse_id' => $item->almacen,
                'type' => 'income'
            ]);
        }
        $payment_to_cach = setting('default.type_payment_to_chash');
        if ($sale->typepayment_id == $payment_to_cach) {
            //quitamos el dinero en caja
            $box = $this->getBoxcurrentUser();
            $box->opening_balance -= $sale->subtotal;
            $box->update();
            //creamos la transaccion de dinero
            $category = Category::where('nombre','ANULACION DE FACTURA')
                                  ->where('type','expense')->first();
           
            $box->transacctions()->create([
                'sucursal_id' => session()->get('sucursal'),
                'type' => 'expense',
                'paid_at' => Carbon::now(),
                'currency_code' => $box->currency->code,
                'currency_rate' => $box->currency->rate,
                'amount' => $sale->subtotal,
                'contact_id' => 0,
                'description' => 'Por anulacion de venta CUF|ID nro:'.$idventa,
                'category_id' => $category->id,
                'user_id' => auth()->user()->id
            ]);
        }
       
    }
}