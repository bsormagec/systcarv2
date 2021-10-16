<?php

namespace App\Models\tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountReceivable extends Model
{
    use HasFactory;
    
    protected $table = 'account_receivables';

    protected $fillable =[
        'quantity','detail','deadline','contact_id',
        'sucursal_id','user_id','status'
    ];
    
    protected $casts = [
        'deadline' => 'date'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function sucursal(){
        return $this->belongsTo(Sucursal::class);
    }   
    
    public function customer(){
        return $this->belongsTo(Contact::class,'contact_id');
    }

    public function details(){
        return $this->hasMany(AccountDetail::class,'account_id');
    }
}
