<?php

namespace App\Models\tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raza extends Model
{
    use HasFactory;

    protected $fillable = ['name','especie_id'];

    public function especie(){
        return $this->belongsTo(Especie::class);
    }
}
