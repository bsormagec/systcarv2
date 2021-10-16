<?php

namespace App\Transformers\Setting;

use App\Models\tenant\Setting as Model;
use League\Fractal\TransformerAbstract;

class Setting extends TransformerAbstract
{
    /**
     * @param Model $model
     * @return array
     */
    public function transform(Model $model)
    {
        return [
            'id' => $model->id,
            'sucursal_id' => $model->sucursal_id,
            'key' => $model->key,
            'value' => $model->value,
        ];
    }
}
