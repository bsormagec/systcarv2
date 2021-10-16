<?php

namespace App\Models\tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RunBox extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha_inicio','import_inicio',
        'user_on_id','user_of_id','status',
        'fecha_fin','import_fin','account_id'
      ];
      
    protected $casts = [
      'fecha_inicio' => 'datetime:Y-m-d',
      'fecha_fin' => 'datetime'
    ];
    
    public function user () {
      return $this->belongsTo(User::class,'user_on_id');
    }

    public function userof () {
      return $this->belongsTo(User::class,'user_of_id');
    }
}
