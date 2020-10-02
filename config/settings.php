<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default values
    |--------------------------------------------------------------------------
    |
    | Here you can define the default values which should be used
    | if a setting does not exists.
    |
    */

    'default' => [],

    /*
    |--------------------------------------------------------------------------
    | Encrypted settings
    |--------------------------------------------------------------------------
    |
    | Here you can define the settings which should be encrypted.
    |
    */

    'encrypted' => [],

    /*
    |--------------------------------------------------------------------------
    | Tenancy
    |--------------------------------------------------------------------------
    |
    | This package supports multi-tenancy. Currently the following packages
    | are supported out of the box. Feel free to define your own.
    |
    | "stancl_multi_database" (https://github.com/stancl/tenancy)
    | "stancl_single_database" (https://github.com/stancl/tenancy)
    |
    */

    'tenancy' => null,

    /*
    |--------------------------------------------------------------------------
    | Tenant default values
    |--------------------------------------------------------------------------
    |
    | Same as "Default values" but for tenant settings.
    |
    */

    'default_tenant' => [],

    /*
    |--------------------------------------------------------------------------
    | Tenant encrypted settings
    |--------------------------------------------------------------------------
    |
    | Same as "Encrypted settings" but for tenant settings.
    |
    */

    'encrypted_tenant' => [],

];
