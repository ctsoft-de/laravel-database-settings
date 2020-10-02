<?php

namespace CTSoft\Laravel\DatabaseSettings\Facades;

use CTSoft\Laravel\DatabaseSettings\Contracts\Settings as SettingsContract;
use Illuminate\Support\Facades\Facade;

/**
 * @mixin SettingsContract
 * @noinspection PhpHierarchyChecksInspection
 */
class Settings extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return SettingsContract::class;
    }
}
