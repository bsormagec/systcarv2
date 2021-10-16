<?php

namespace Augusto\Purchase\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\tenant\Account;

class PurchaseCollection extends ResourceCollection
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
            'data' => $this->collection->transform(function ($purchase){
                return [
                    'id' => $purchase->id,
                    'fecha' => $purchase->date,
                    'nit' => $purchase->nit,
                    'nrofactura' => $purchase->invoice_number,
                    'total' => $purchase->sub_total,
                    'descuento' => $purchase->discount,
                    'proveedor' => $purchase->provider,
                    'estado' => $purchase->status,
                    'usuario' => $purchase->user
                ];
            }),
            'permiso'=> Account::getUserrunbox()
        ];
    }
}
