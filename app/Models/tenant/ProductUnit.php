<?php

namespace App\Models\tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductUnit extends Model
{
    protected $table= 'product_unit';
    protected $fillable = ['product_id','unit_id','precio','cantidad_unidad'];
}
