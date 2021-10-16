<?php

namespace App\Models\tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountDetail extends Model
{
    use HasFactory;
    
    protected $table = 'account_details';
    protected $fillable =[
        'amount','detail','date','account_id','user_id'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function account(){
        return $this->belongsTo(AccountReceivable::class,'account_id');
    }   
}
