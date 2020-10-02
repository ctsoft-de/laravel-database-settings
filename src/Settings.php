<?php

namespace CTSoft\Laravel\DatabaseSettings;

use CTSoft\Laravel\DatabaseSettings\Contracts\Settings as SettingsContract;
use CTSoft\Laravel\DatabaseSettings\Managers\TenancyManager;
use CTSoft\Laravel\DatabaseSettings\Models\Setting;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class Settings implements SettingsContract
{
    /**
     * The application configuration.
     *
     * @var Repository
     */
    protected $config;

    /**
     * The tenancy manager.
     *
     * @var TenancyManager
     */
    protected $tenancy;

    /**
     * Settings constructor.
     *
     * @param Repository     $config
     * @param TenancyManager $tenancy
     */
    public function __construct(Repository $config, TenancyManager $tenancy)
    {
        $this->config = $config;
        $this->tenancy = $tenancy;
    }

    /**
     * Set the specified setting value.
     *
     * @param string|array $key
     * @param mixed|null   $value
     * @return bool
     */
    public function set($key, $value = null): bool
    {
        $settings = is_array($key) ? $key : [$key => $value];
        $changed = false;

        Setting::unguarded(function () use ($settings, &$changed) {

            foreach ($settings as $key => $value) {
                $setting = Setting::query()->updateOrCreate(
                    ['key' => $key],
                    ['value' => $this->castValueForDatabase($key, $value)]
                );

                // TODO: Encrypted settings are always treated as changed
                if ($setting->wasRecentlyCreated || $setting->wasChanged()) {
                    $changed = true;
                }
            }

        });

        if ($changed) {
            Cache::forget('settings');
        }

        return $changed;
    }

    /**
     * Get the specified setting value.
     *
     * @param string     $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $settings = $this->all();

        if (!array_key_exists($key, $settings)) {
            return $default ?? $this->getDefaultValue($key);
        }

        return $this->castValueForApplication($key, $settings[$key]);
    }

    /**
     * Get all settings.
     *
     * @return string[]
     */
    protected function all(): array
    {
        return Cache::rememberForever('settings', function () {

            return Setting::all()->pluck('value', 'key')->all();

        });
    }

    /**
     * Cast a value for the database.
     *
     * @param string $key
     * @param mixed  $value
     * @return string
     */
    protected function castValueForDatabase(string $key, $value): string
    {
        if ($this->isEncrypted($key)) {
            return Crypt::encrypt($value);
        }

        return serialize($value);
    }

    /**
     * Cast a value for the application.
     *
     * @param string $key
     * @param string $value
     * @return mixed
     */
    protected function castValueForApplication(string $key, string $value)
    {
        if ($this->isEncrypted($key)) {
            return Crypt::decrypt($value);
        }

        return unserialize($value);
    }

    /**
     * Get the default value for the specified setting key.
     *
     * @param string $key
     * @return mixed|null
     */
    protected function getDefaultValue(string $key)
    {
        return $this->getConfig('default', $key);
    }

    /**
     * Get if the value of a setting should be encrypted.
     *
     * @param string $key
     * @return bool
     */
    protected function isEncrypted(string $key): bool
    {
        $base = Str::beforeLast($key, '.');

        if ($base == $key) {
            $encrypted = $this->getConfig('encrypted');
        } else {
            $encrypted = $this->getConfig('encrypted', $base);
        }

        if (is_null($encrypted)) {
            return false;
        }

        return in_array(Str::afterLast($key, '.'), $encrypted);
    }

    /**
     * Get a configuration value.
     *
     * @param string      $baseKey
     * @param string|null $subKey
     * @return mixed
     */
    protected function getConfig(string $baseKey, string $subKey = null)
    {
        $tenant = $this->tenancy->tenant() ? '_tenant' : '';
        $subKey = $subKey ? ".{$subKey}" : '';

        $configKey = "settings.{$baseKey}{$tenant}{$subKey}";

        return $this->config->get($configKey);
    }
}
