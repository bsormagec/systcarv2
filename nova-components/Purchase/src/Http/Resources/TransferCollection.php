<?php

namespace Augusto\Purchase\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\tenant\Transfer;

class TransferCollection extends ResourceCollection
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
            'data' => $this->collection->transform(function ($transfer){
                return [
                    'id' => $transfer->id,
                    'fecha' => $transfer->expense_transaction_id,
                    'nit' => $transfer->nit,
                    'nrofactura' => $transfer->invoice_number,
                    'total' => $transfer->sub_total,
                    'descuento' => $transfer->discount,
                    'proveedor' => $transfer->provider,
                    'estado' => $transfer->status,
                    'usuario' => $transfer->user
                ];
            }),
            'permiso'=> Account::getUserrunbox()
        ];
    }
}
