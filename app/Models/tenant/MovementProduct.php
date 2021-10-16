<?php

namespace App\Models\tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovementProduct extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'cantidad','detalle','warehouse_id','product_id',
        'category_id','user_id','type','sucursal_id','unit_id'
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function warehouse() {
        return $this->belongsTo(Warehous::class);
    }

    public function sucursal() {
        return $this->belongsTo(Sucursal::class);
    }
}
