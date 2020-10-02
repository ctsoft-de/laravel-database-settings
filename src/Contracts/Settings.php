<?php

namespace CTSoft\Laravel\DatabaseSettings\Contracts;

interface Settings
{
    /**
     * Set the specified setting value.
     *
     * @param string|array $key
     * @param mixed|null   $value
     * @return bool
     */
    public function set($key, $value = null): bool;

    /**
     * Get the specified setting value.
     *
     * @param string     $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $key, $default = null);
}
