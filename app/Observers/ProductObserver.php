<?php

namespace App\Observers;

use App\Models\tenant\Product;
use App\Models\tenant\Warehouse;
use App\Models\tenant\ProductUnit;
use Illuminate\Support\Facades\DB;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function created(Product $product)
    {
        $prices= collect(json_decode($product->prices));
        //crear los precios en su relacion con producto_unidad
        foreach ($prices as $value) {
            $prod = new ProductUnit;
            $prod->product_id = $product->id;
            $prod->unit_id = $value->unit_id;
            $prod->precio = $value->precio;
            $prod->cantidad_unidad = $value->cantidad_unidad; 
            $prod->save();
        }
        
    }

    /**
     * Handle the Product "updated" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function updated(Product $product)
    {
        $preciosunits = array_column(json_decode($product->prices, true),'unit_id');
        $product->units()->detach($preciosunits);
        $prices= collect(json_decode($product->prices));
        foreach ($prices as $value) {
            $prod = new ProductUnit;
            $prod->product_id = $product->id;
            $prod->unit_id = $value->unit_id;
            $prod->precio = $value->precio;
            $prod->cantidad_unidad = $value->cantidad_unidad; 
            $prod->save();
        }
       
    }
    // public function saving(Product $product)
    // {
    //     $precios= json_decode($product->prices);
    //     $product->units()->sync($precios);
    // }

    /**
     * Handle the Product "deleted" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function deleted(Product $product)
    {
        $product->units()->delete();
    }

    /**
     * Handle the Product "restored" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function restored(Product $product)
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function forceDeleted(Product $product)
    {
        //
    }
}
