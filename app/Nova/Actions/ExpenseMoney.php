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
use App\Models\tenant\Currency;
use Carbon\Carbon;

class ExpenseMoney extends Action
{
    use InteractsWithQueue, Queueable;
    public $name = 'Salida de dinero';
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
            if ($fields->cantidad > $model->opening_balance) {
                return Action::danger('La cantidad es mayor al saldo de la cuenta');
            }
            $currency = Currency::findOrFail($model->currency_id);
            $model->transacctions()->create([
                'sucursal_id' => session()->get('sucursal'),
                'type' => 'expense',
                'paid_at' => Carbon::now(),
                'currency_code' => $currency->code,
                'currency_rate' => $currency->rate,
                'amount' => $fields->cantidad,
                'contact_id' => 0,
                'description' => $fields->detalle,
                'category_id' => $fields->category_id,
                'user_id' => auth()->user()->id
            ]);
            $model->opening_balance -= $fields->cantidad;
            $model->save();
        }
        return Action::message('Entrada de dinero con exito');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Select::make('Tipo de Salida','category_id')
                    ->options(Category::where('type','expense')->pluck('nombre','id')),
            Number::make('Cantidad')->rules('required'),
            Textarea::make('Detalle')->rows(3)
        ];
    }
}
