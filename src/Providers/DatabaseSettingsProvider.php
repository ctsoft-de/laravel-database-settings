<?php

namespace CTSoft\Laravel\DatabaseSettings\Providers;

use CTSoft\Laravel\DatabaseSettings\Contracts\Settings as SettingsContract;
use CTSoft\Laravel\DatabaseSettings\Managers\TenancyManager;
use CTSoft\Laravel\DatabaseSettings\Settings;
use Illuminate\Support\ServiceProvider;

class DatabaseSettingsProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/settings.php', 'settings');

        $this->app->singleton(SettingsContract::class, Settings::class);
        $this->app->singleton(TenancyManager::class);
    }

    /**
     * Bootstrap services.
     *
     * @param TenancyManager $tenancy
     * @return void
     */
    public function boot(TenancyManager $tenancy): void
    {
        $tenancy->boot();

        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        $this->publishes([
            __DIR__ . '/../../config/settings.php' => $this->app->configPath('settings.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../../database/migrations' => $this->app->databasePath('migrations'),
        ], 'migrations');
    }
}
