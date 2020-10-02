<?php

use CTSoft\Laravel\DatabaseSettings\Contracts\Settings;
use Illuminate\Container\Container;

if (!function_exists('setting')) {
    /**
     * Get or set the specified setting value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param string|array $key
     * @param mixed|null   $default
     * @return mixed|null
     */
    function setting($key, $default = null)
    {
        /** @var Settings $settings */
        $settings = Container::getInstance()->make(Settings::class);

        if (is_array($key)) {
            return $settings->set($key);
        }

        return $settings->get($key, $default);
    }
}
