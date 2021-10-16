<?php

namespace App\Models\tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $guarded = ['id'];
    
    public static function getByKey($key,$default = null){
        $setting = self::where('key',$key)->first();
        if (isset($setting)) {
            return $setting->value;
        }else{
            return $default;
        }
    }
    /**
     * Get all the settings
     *
     * @return mixed
     */
    public static function getAllSettings()
    { 
        return self::all();
    }
    /**
     * Get all the settings in array
     *
     * @return mixed
     */
    public static function getSettingsArray()
    {
        return self::getAllSettings()->pluck('value', 'key')->toArray();
    }

    public static function has($key)
    {
        return (boolean)self::getAllSettings()->whereStrict('key', $key)->count();
    }
     /**
     * Get a settings value
     *
     * @param $key
     * @param null $default
     * @return bool|int|mixed
     */
    public static function get($key, $default = null)
    {
        if (self::has($key)) {
            $setting = self::getAllSettings()->where('key', $key)->first();
            return $setting->value;
        }
        return $default;
    }
    /**
     * Add a settings value
     *
     * @param $key
     * @param $value
     * @return bool
     */
    public static function add($key, $value)
    {
        if (self::has($key)) {
            return self::set($key, $value);
        }
        return self::create(['key' => $key, 'value' => $value]) ? $value : false;
    }

    /**
     * Set a value for setting
     *
     * @param $key
     * @param $value
     * @return bool
     */
    public static function set($key, $value)
    {
        if ($setting = self::getAllSettings()->where('key', $key)->first()) {
            return $setting->update([
                'key' => $key,
                'value' => $value]) ? $value : false;
        }
        return self::add($key, $value);
    }

    /**
     * Update Settings
     *
     * @param $data
     * @return void
     */
    public static function updateSettings($data)
    {
        foreach ($data as $key => $value) {
            self::set($key, $value);
        }
    }
    /**
     * Scope to only include by prefix.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $prefix
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePrefix($query, $prefix = 'sucursal')
    {
        return $query->where('key', 'like', $prefix . '.%');
    }

    /**
     * Scope to only include sucursal data.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $sucursal_id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSucursalId($query, $sucursal_id)
    {
        return $query->where($this->table . '.sucursal_id', '=', $sucursal_id);
    }
    /**
     * Remove a setting
     *
     * @param $key
     * @return bool
     */
    public static function remove($key)
    {
        if (self::has($key)) {
            return self::whereKey($key)->delete();
        }

        return false;
    }
   
}
