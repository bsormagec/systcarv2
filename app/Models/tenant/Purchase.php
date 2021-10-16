<?php

namespace App\Models\tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\HasManyRelation;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory, HasManyRelation,SoftDeletes;
    
    protected $fillable= [
        'date','nit','invoice_number','dui_number','authorization_number','exempt_amount','sub_total',
        'discount','status','amount_base_cf','credito_fiscal','type_purchase','expense_money_box',
        'codigo_control','observation','contact_id','user_id','sucursal_id'
    ];
    
    protected $casts = [
        'expense_money_box' => 'boolean'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function provider(){
        return $this->belongsTo(Contact::class,'contact_id');
    }

    public function items()
    {
        return $this->hasMany(DetailPurchase::class);
    }
}
