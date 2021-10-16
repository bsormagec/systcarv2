<?php

namespace App\Nova\Actions;

use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Textarea;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use App\Models\tenant\Category;
use App\Models\tenant\ProductWarehouse;

class IncomeProduct extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Entrada de productos';
    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {
             $typeincomeproduct = Category::findOrFail($fields->typeincome_id);
            if ($model->hasProductinWarehouse($model,$fields->warehouse_id)) {
                $productwarehouse = ProductWarehouse::where('product_id',$model->id)
                                                      ->where('warehouse_id',$fields->warehouse_id)
                                                      ->first();
                $productwarehouse->stock += $fields->cantidad;
                $productwarehouse->save();
            } else {
                $productwarehouse = new ProductWarehouse;
                $productwarehouse->product_id = $model->id;
                $productwarehouse->warehouse_id = $fields->warehouse_id;
                $productwarehouse->stock = $fields->cantidad;
                $productwarehouse->save();
            }
            $typeincomeproduct->movementproducts()->create([
                'product_id' => $model->id,
                'cantidad' => $fields->cantidad,
                'unit_id' => 1,
                'detalle' => $fields->detalle,
                'warehouse_id' => $fields->warehouse_id,
                'user_id' => auth()->user()->id,
                'sucursal_id' => session()->get('sucursal'),
                'type' => 'income'
            ]);
            $model->save();
        }
        return Action::message('Entrada de Producto con exito');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Select::make('Tipo de Ingreso','typeincome_id')
                  ->options(Category::where('type','incomeproducts')->pluck('nombre','id')),
            Select::make('Almacen','warehouse_id')
                  ->options(\App\Models\tenant\Warehouse::pluck('nombre','id')),
            Number::make('Cantidad')->rules('required'),
            Textarea::make('Detalle')->rows(3)
        ];
    }
}
