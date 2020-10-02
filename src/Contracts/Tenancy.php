<?php

namespace CTSoft\Laravel\DatabaseSettings\Contracts;

use Illuminate\Database\Schema\Blueprint;

interface Tenancy
{
    /**
     * Boot the tenancy.
     *
     * @return void
     */
    public function boot(): void;

    /**
     * Migrate the settings table.
     *
     * @param Blueprint $table
     * @return void
     */
    public function migrate(Blueprint $table): void;

    /**
     * Get if the current context is global or tenant.
     *
     * @return bool
     */
    public function tenant(): bool;
}
