<?php

namespace CTSoft\Laravel\DatabaseSettings\Tenancy;

use CTSoft\Laravel\DatabaseSettings\Contracts\Tenancy;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\Schema\Blueprint;
use Stancl\Tenancy\Tenancy as StanclTenancy;

class StanclMultiDatabase implements Tenancy
{
    /**
     * The application config.
     *
     * @var Repository
     */
    protected $config;

    /**
     * The Stancl tenancy.
     *
     * @var StanclTenancy
     */
    protected $tenancy;

    /**
     * StanclMultiDatabase constructor.
     *
     * @param Repository    $config
     * @param StanclTenancy $tenancy
     */
    public function __construct(Repository $config, StanclTenancy $tenancy)
    {
        $this->config = $config;
        $this->tenancy = $tenancy;
    }

    /**
     * Boot the tenancy.
     *
     * @return void
     */
    public function boot(): void
    {
        $configKey = 'tenancy.migration_parameters.--path';
        $migrationsPath = __DIR__ . '/../../database/migrations';

        $this->config->push($configKey, $migrationsPath);
    }

    /**
     * Migrate the settings table.
     *
     * @param Blueprint $table
     * @return void
     */
    public function migrate(Blueprint $table): void
    {
        //
    }

    /**
     * Get if the current context is global or tenant.
     *
     * @return bool
     */
    public function tenant(): bool
    {
        return $this->tenancy->initialized;
    }
}
