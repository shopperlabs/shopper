<?php

namespace Shopper\Framework;

class Shopper
{
    /**
     * Get the current Shopper version.
     *
     * @return string
     */
    public static function version()
    {
        return '1.0.0';
    }

    /**
     * Get the URI path prefix utilized by Shopper.
     *
     * @return string
     */
    public static function prefix()
    {
        return config('shopper.prefix');
    }
}
