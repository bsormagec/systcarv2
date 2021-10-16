<?php

namespace App\Models\tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sucursal_id', 'type', 'name','lastName','fullName','business_name','email', 'user_id', 
        'ci', 'phone', 'address', 'website', 'reference', 'enabled','speciality_id',
        'typedocument_id'
    ];
    
    protected static function boot() {
        parent::boot();
        if ( !app()->runningInConsole()) {
            self::saving(function ($table) {
                if ($table->name != '' && $table->lastName !='') {
                   $table->fullName = $table->name.' '.$table->lastName;
                }
                $table->enabled = true;
            });
        }
    }
    
    public function typedocument(){
        return $this->belongsTo(Type::class);
    }

    public function speciality(){
        return $this->belongsTo(Speciality::class);
    }

}
