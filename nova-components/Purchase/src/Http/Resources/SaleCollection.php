<?php

namespace Augusto\Purchase\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\tenant\Account;

class SaleCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($sale){
                return [
                    'id' => $sale->id,
                    'fecha' => $sale->date,
                    'cliente' => $sale->customer,
                    'documento' => $sale->document,
                    'codigocontrol' => $sale->control_code,
                    'pagada' => $sale->paid,
                    'estado' => $sale->status,
                    'total' => $sale->subtotal,
                    'usuario' => $sale->user
                ];
            }),
            'permiso'=> Account::getUserrunbox(),
            'plan' => tenant('plan')
        ];
    }
}
