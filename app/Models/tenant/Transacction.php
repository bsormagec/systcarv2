<?php

namespace App\Models\tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transacction extends Model
{
    use HasFactory;

    protected $fillable = [
        'sucursal_id', 'type', 'account_id', 'paid_at', 'amount','currency_code', 
        'currency_rate','sale_id', 'contact_id', 'description', 'category_id', 
        'payment_method', 'reference', 'parent_id','user_id','reconciled'
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'amount' => 'double',
        'currency_rate' => 'double'
    ];

}
