<?php

namespace App\Models\tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable= [
        'nombre','descripcion_small','lote','imagen','stock','fecha_expir','dosis',
        'indicaciones','slug','unit_id','category_id','subcategory_id','prices'
        ];

    protected $appends = ['text'];

    protected $casts=[
        'fecha_expir'=> 'date',
       // 'prices' => 'array'
    ];
    
    public function getTextAttribute()
    {
        if ($this->attributes['lote']) {
        return $this->attributes['nombre'].' - '.$this->attributes['lote']; 
        }
        return $this->attributes['nombre'];
    }

    public function category (){
        return $this->belongsTo(Category::class);
    }

    public function subcategory (){
        return $this->belongsTo(SubCategory::class);
    }

    public function units() {
        return $this->belongsToMany(Unit::class)->withPivot('precio','cantidad_unidad');
    }
    
    public function depositos() {
        return $this->hasMany(ProductWarehouse::class);
    }

    public function warehouses() {
        return $this->belongsToMany(Warehouse::class,'product_warehouse')->withPivot('stock');
    }
    
    public function hasProductinWarehouse($product,$warehouse_id) {
        return $product->warehouses()
            ->where('product_id', $product->getKey())
            ->where('warehouse_id', $warehouse_id)
            ->exists();
    }
}
