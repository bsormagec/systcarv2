<?php

namespace App\Models\tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;
    
    protected $casts = [
        'fecha_nacim' => 'date',
    ];

    public function customer(){
        return $this->belongsTo(Contact::class,'contact_id');
    }

    public function especie(){
        return $this->belongsTo(Especie::class);
    }

    public function raza(){
        return $this->belongsTo(Raza::class);
    }
}
