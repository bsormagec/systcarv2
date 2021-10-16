<?php

namespace App\Models\tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\HasManyRelation;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory, HasManyRelation,SoftDeletes;

    protected $with = [
        'customer:id,fullName,address,ci,phone',
        'user:id,name,email'
    ];

    protected $fillable = [
        'user_id','date','document','cuf','control_code','status','invoice_number',
        'authorization_number','deadline','amount','amount_ice','amount_exento',
        'zero_rate','subtotal','discount','amount_base','debito_fiscal','electronic_bill',
        'cash','autorizacion_id','received','turned','observations','typepayment_id',
        'typedocument_id','contact_id','sucursal_id'
    ];
    
    public function customer(){
        return $this->belongsTo(Contact::class,'contact_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function items() {
        return $this->hasMany(DetailSale::class);
    }

    
}
