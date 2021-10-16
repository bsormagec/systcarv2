<?php

namespace App\Models\tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code','fecha','descriptions','observations','customer_id','start_at','finish_at',
        'duration','user_id','pet_id','medic_id','sucursal_id','service_id','status',
    ];

    protected $casts = [
        'fecha' => 'date',
        'start_at' => 'datetime',
        'finish_at' => 'datetime'
    ];
    
    public function customer(){
        return $this->belongsTo(Contact::class,'customer_id');
    }

    public function validate($data, $scenario)
    {
        switch ($scenario)
        {
            case 'create':
            case 'update':
                $rules = [
                    'title' => 'required',
                    'start' => 'required|date',
                    'end' => 'required|date|after_or_equal:start'
                ];

                break;
        }

        return Validator::make($data, $rules);
    }

    public function scopeFilter($query, $data)
    {
        if ( ! empty($data['start']))
        {
            $query->where('start_at', '>=', $data['start']);
        }

        if ( ! empty($data['end']))
        {
            $query->where('finish_at', '<=', $data['end']);
        }

        return $query;
    }
}
