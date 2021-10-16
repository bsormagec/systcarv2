<?php

namespace App\Models\tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable=['nombre','descripcion','slug','category_id'];

    public function category (){
        return $this->belongsTo(Category::class);
    }
}
