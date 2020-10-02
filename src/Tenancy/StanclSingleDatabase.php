<?php

namespace CTSoft\Laravel\DatabaseSettings\Tenancy;

use CTSoft\Laravel\DatabaseSettings\Contracts\Tenancy;
use CTSoft\Laravel\DatabaseSettings\Models\Setting;
use Exception;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use Stancl\Tenancy\Tenancy as StanclTenancy;

class StanclSingleDatabase implements Tenancy
{
    /**
     * The Stancl tenancy.
     *
     * @var StanclTenancy
     */
    protected $tenancy;

    /**
     * The foreign column.
     *
     * @var string
     */
    protected $column;

    /**
     * StanclSingleDatabase constructor.
     *
     * @param StanclTenancy $tenancy
     */
    public function __construct(StanclTenancy $tenancy)
    {
        $this->tenancy = $tenancy;
        $this->column = BelongsToTenant::$tenantIdColumn;
    }

    /**
     * Boot the tenancy.
     *
     * @return void
     */
    public function boot(): void
    {
        Setting::creating(function (Setting $setting) {
            $setting->{$this->column} = $this->getTenantKey();
        });

        Setting::addGlobalScope(function (Builder $query) {
            $query->where($this->column, $this->getTenantKey());
        });
    }

    /**
     * Migrate the settings table.
     *
     * @param Blueprint $table
     * @return void
     * @throws Exception
     */
    public function migrate(Blueprint $table): void
    {
        $tenantTable = $this->tenancy->model()->getTable();
        $keyColumn = $this->tenancy->model()->getTenantKeyName();

        /** @var Connection $connection */
        $connection = DB::connection();

        $typeSql = $connection->table($tenantTable)->select($keyColumn)->limit(0)->toSql();
        $meta = $connection->getPdo()->query($typeSql)->getColumnMeta(0);

        switch ($meta['native_type']) {
            case 'LONG':
                $columnType = 'unsignedInteger';
                break;

            case 'LONGLONG':
                $columnType = 'unsignedBigInteger';
                break;

            case 'VAR_STRING':
                $columnType = 'string';
                break;

            default:
                throw new Exception("Can't autodetect database type for the tenant key column. " .
                    'Please publish the settings migration and adjust it to your needs.');
        }

        $table->{$columnType}($this->column)->nullable();
        $table->foreign($this->column)->references($keyColumn)->on($tenantTable)->onUpdate('restrict')->onDelete('restrict');
        $table->index([$this->column, 'key']);
    }

    /**
     * Get if the current context is global or tenant.
     *
     * @return bool
     */
    public function tenant(): bool
    {
        return !is_null($this->getTenantKey());
    }

    /**
     * Get the current tenant key.
     *
     * @return mixed|null
     */
    protected function getTenantKey()
    {
        /** @noinspection PhpExpressionAlwaysNullInspection */
        return $this->tenancy->initialized ? $this->tenancy->tenant->getTenantKey() : null;
    }
}
