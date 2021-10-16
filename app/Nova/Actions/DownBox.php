<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use App\Models\tenant\RunBox;
use Carbon\Carbon;

class DownBox extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Cerrar Caja';

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        if ($models->count() > 1) {
            return Action::danger('Seleccine Solo una Caja');
        }

        foreach ($models as $model) {
            if ($model->status == "cerrada") {
                return Action::danger('Esta caja ya esta cerrada');
            }
            if($model->permissionToCloseBox($model->id)){
                $model->status = "cerrada";
                $detallecaja = RunBox::where('account_id',$model->id)
                                        ->whereDate('fecha_inicio','=',Carbon::now())
                                        ->where('status', true)
                                        ->first();
                $detallecaja->fecha_fin = Carbon::now();
                $detallecaja->user_of_id = auth()->user()->id;
                $detallecaja->import_fin = $model->opening_balance;
                $detallecaja->status = false;
                $detallecaja->update();
                $model->save();
            }else{
                return Action::danger('No Puedes cerrar esta caja');
            }
           
        }
        return Action::message('Caja Cerrada');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }
}
