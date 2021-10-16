<?php

namespace App\Models\tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    
    protected $fillable=[
        'nombre','descripcion','type','slug','imagen','enabled','color'
    ];

    public function movementproducts(){
        return $this->hasMany(MovementProduct::class);
    }
}
