<?php

namespace App\Models\tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Account extends Model
{
    use HasFactory;
    
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sucursal_id', 'name','currency_id','number', 'opening_balance', 
        'bank_name', 'bank_phone', 'bank_address', 'enabled','type','status',
        'user_id','user_runbox_id'
    ];
    
    public function runbox (){
        return $this->hasMany(RunBox::class); 
    }
    
    public function transacctions (){
        return $this->hasMany(Transacction::class); 
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function currency() {
        return $this->belongsTo(Currency::class);
    }

    public function getUsuarioAttribute()
    {
        $usuariorunbox = DB::table('accounts')->select('u.id','u.name')
              ->join('run_boxes as rb', 'rb.account_id', 'accounts.id')
              ->join('users as u', 'u.id', 'rb.user_on_id')
              ->where('accounts.status','=','iniciada')
              ->whereDate('rb.created_at','=',Carbon::now())
              ->where('accounts.id','=',$this->id)
              ->first();
        $retVal = $usuariorunbox ?? '--' ;
        return $retVal;
    }
    public static function getUserrunbox()
    {
        return $usuariorunbox = DB::table('accounts')->select('accounts.id','u.name')
              ->join('run_boxes as rb', 'rb.account_id', 'accounts.id')
              ->join('users as u', 'u.id', 'rb.user_on_id')
              ->where('accounts.status','=','iniciada')
              ->whereDate('rb.created_at','=',Carbon::now())
              ->where('rb.user_on_id', auth()->user()->id)
              ->first();
    }
    public static function permissionToCloseBox($id){
        return $usuariodownnbox = DB::table('accounts')->select('u.id','u.name')
              ->join('run_boxes as rb', 'rb.account_id', 'accounts.id')
              ->join('users as u', 'u.id', 'rb.user_on_id')
              ->where('accounts.status','=','iniciada')
              ->whereDate('rb.created_at','=',Carbon::now())
              ->where('rb.status',true)
              ->where('accounts.id','=',$id)
              ->where('rb.user_on_id', auth()->user()->id)
              ->first();
    }
}
