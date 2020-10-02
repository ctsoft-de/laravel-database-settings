<?php

namespace CTSoft\Laravel\DatabaseSettings\Managers;

use CTSoft\Laravel\DatabaseSettings\Contracts\Tenancy;
use CTSoft\Laravel\DatabaseSettings\Tenancy\StanclMultiDatabase;
use CTSoft\Laravel\DatabaseSettings\Tenancy\StanclSingleDatabase;
use Illuminate\Support\Manager;
use Illuminate\Support\Optional;

/**
 * @mixin Tenancy
 * @noinspection PhpHierarchyChecksInspection
 */
class TenancyManager extends Manager
{
    /**
     * Get the default driver.
     *
     * @return string
     */
    public function getDefaultDriver(): string
    {
        return $this->config->get('settings.tenancy') ?: 'none';
    }

    /**
     * Create a driver for none tenancy.
     *
     * @return Optional
     */
    protected function createNoneDriver(): Optional
    {
        return new Optional(null);
    }

    /**
     * Create a Stancl multi-database tenancy driver.
     *
     * @return StanclMultiDatabase
     */
    protected function createStanclMultiDatabaseDriver(): StanclMultiDatabase
    {
        return $this->container->make(StanclMultiDatabase::class);
    }

    /**
     * Create a Stancl single-database tenancy driver.
     *
     * @return StanclSingleDatabase
     */
    protected function createStanclSingleDatabaseDriver(): StanclSingleDatabase
    {
        return $this->container->make(StanclSingleDatabase::class);
    }
}
