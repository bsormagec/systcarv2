<?php

namespace App\Models\tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Akaunting\Money\Currency as MoneyCurrency;

class Currency extends Model
{
    use HasFactory;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sucursal_id', 'name', 'code', 'rate', 'enabled', 'precision', 
        'symbol', 'symbol_first', 'decimal_mark', 'thousands_separator'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'rate' => 'double',
        'enabled' => 'boolean',
    ];
    
    /**
     * Scope to only include active models.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    public static function precios(){
        // Get current currencies
        $current = self::pluck('code')->toArray();

        // Prepare codes
        $codes = [];
        $currencies = MoneyCurrency::getCurrencies();

        foreach ($currencies as $key => $item) {
            // Don't show if already available
            if (in_array($key, $current)) {
                continue;
            }

            $codes[$key] = $key;
        }
        return $codes;
    }
}
