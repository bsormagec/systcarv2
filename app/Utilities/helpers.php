<?php

if (!function_exists('cache_prefix')) {
    /**
     * Cache system added company_id prefix.
     *
     * @return string
     */
    function cache_prefix() {
        return session('sucursal_id') . '_';
    }
}
