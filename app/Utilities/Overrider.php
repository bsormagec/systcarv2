<?php

namespace App\Utilities;

class Overrider
{
    public static $sucursal_id;

    public static function load($type)
    {
        // Overrides apply per company
        $sucursal_id = session('sucursal_id');
        if (empty($sucursal_id)) {
            return;
        }

        static::$sucursal_id = $sucursal_id;

        $method = 'load' . ucfirst($type);

        static::$method();
    }

    protected static function loadSettings()
    {
        // Set the active company settings
        setting()->setExtraColumns(['sucursal_id' => static::$sucursal_id]);
        setting()->forgetAll();
        setting()->load(true);

        // Timezone
        config(['app.timezone' => setting('localisation.timezone', 'America/La_paz')]);
        date_default_timezone_set(config('app.timezone'));

    }
}
