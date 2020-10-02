# Laravel Database Settings

This package allows you to save settings in the database. Multi-Tenancy is supported out of the box.

### Installation

```bash
composer require ctsoft/laravel-database-settings
```

### Usage

The usage is the same as ```config``` in the Laravel framework.

```php
// Facade
Settings::set('foo', 'bar');
Settings::set(['foo' => 'bar', 'myarr.key1' => true, 'myarr.key2' => 5.87]);
Settings::get('foo');
Settings::get('myarr.key1');

// Helper
setting(['foo' => 'bar']);
setting('foo');
```

### Publish configuration

If you want to change the configuration of the package publish the configuration file and see the next sections.

```bash
php artisan vendor:publish --provider="CTSoft\Laravel\DatabaseSettings\Providers\DatabaseSettingsProvider" --tag=config
```

### Default values

In the package configuration file you can define default values which should be used if a setting does not exist.

```php
[
    'foo'   => 'myval',
    'myarr' => [
        'key1' => false,
    ],
]
```

### Encryption

In the package configuration file you can define which settings should be encrypted in the database.

```php
[
    'bar',
    'myarr' => [
        'key2',
    ],
]
```

### Multi-Tenancy

This package supports the following multi-tenancy packages:

- Stancl Multi Database
- Stancl Single Database

To use tenancy support you must define it in the package configuration file.
If you use any other multi-tenancy package please study the source code of this package. It is easy to add your own tenancy support.

### Publish database migrations

If you want to adjust the database table publish the migration files.

```bash
php artisan vendor:publish --provider="CTSoft\Laravel\DatabaseSettings\Providers\DatabaseSettingsProvider" --tag=migrations
```

## Security

If you discover any security-related issues, please email security@ctsoft.de instead of using the issue tracker.

## License

Laravel Database Settings is open-sourced software licensed under the [MIT license](LICENSE.md).
