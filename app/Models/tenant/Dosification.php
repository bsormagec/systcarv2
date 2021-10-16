<?php

namespace App\Models\tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosification extends Model
{
    use HasFactory;

    protected $fillable = ['nro_autorizacion','numero_actual','llave_dosificacion','fecha_limite'];

    public function sucursal() {
        return $this->belongsTo(Sucursal::class);
    }
}
