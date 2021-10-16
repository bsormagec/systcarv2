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

class ExpenseProduct extends Action
{
    use InteractsWithQueue, Queueable;
    public $name = 'Salida de productos';
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
            $typeincomeproduct = Category::find($fields->typeincome_id);
            $productwarehouse = ProductWarehouse::where('product_id',$model->id)
                                                ->where('warehouse_id',$fields->warehouse_id)
                                                ->first();
            if ($fields->cantidad > $productwarehouse->stock) {
                return Action::danger('La cantidad es mayor al stock del deposito');
            }
            $productwarehouse->stock -= $fields->cantidad;
            $productwarehouse->save();
            $typeincomeproduct->movementproducts()->create([
                'product_id' => $model->id,
                'cantidad' => $fields->cantidad,
                'unit_id' => 1,
                'detalle' => $fields->detalle,
                'warehouse_id' => $fields->warehouse_id,
                'user_id' => auth()->user()->id,
                'sucursal_id' => session()->get('sucursal'),
                'type' => 'expense'
            ]);
             $model->stock -= $fields->cantidad;
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
            Select::make('Tipo de Salida','typeexpense_id')
                  ->options(Category::where('type','expenseproducts')->pluck('nombre','id')),
            Select::make('Almacen','warehouse_id')
                  ->options(\App\Models\tenant\Warehouse::pluck('nombre','id')),
            Number::make('Cantidad')->rules('required'),
            Textarea::make('Detalle')->rows(3)
        ];
    }
}
