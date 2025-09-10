<?php

/**
 * Controlador de Usuarios del ERP Koneko Vuexy Admin
 *
 * @package   Koneko\KonekoVuexyAdmin
 * @author    Arturo Corro Pacheco <opensource@koneko.mx>
 * @copyright 2025 Koneko Soluciones Tecnológicas
 * @license   Business Source License 1.1 (custom) - See LICENSE or https://github.com/koneko-mx/laravel-koneko-vuexy-admin/blob/main/LICENSE
 */

 declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Koneko\KonekoVuexyAdmin\Application\Bootstrap\Extenders\Catalog\CatalogModuleRegistry;
use Koneko\KonekoVuexyAdmin\Application\Cache\Contracts\CacheRepositoryInterface;
use Koneko\KonekoVuexyAdmin\Application\Cache\Manager\KonekoCacheManager;
use Koneko\KonekoVuexyAdmin\Application\Config\Contracts\ConfigRepositoryInterface;
use Koneko\KonekoVuexyAdmin\Application\Config\Manager\KonekoConfigManager;
use Koneko\KonekoVuexyAdmin\Application\Settings\Contracts\SettingsRepositoryInterface;
use Koneko\KonekoVuexyAdmin\Application\Settings\Manager\KonekoSettingManager;
use Koneko\KonekoVuexyAdmin\Application\Helpers\VuexyHelper;
use Koneko\KonekoVuexyAdmin\Application\Loggers\{KonekoSecurityLogger, KonekoSystemLogger, KonekoUserInteractionLogger};
use Koneko\KonekoVuexyAdmin\Application\UX\Notifications\Manager\KonekoNotifyManager;
use Koneko\KonekoVuexyAdmin\Models\SystemLog;
use Koneko\KonekoVuexyAdmin\Models\UserInteraction;
use Koneko\KonekoVuexyAdmin\Support\Enums\SystemLog\LogLevel;
use Koneko\KonekoVuexyAdmin\Support\Enums\UserInteractions\InteractionSecurityLevel;

// =================== HELPERS ===================

if (!function_exists('Helper')) {
    function Helper(): mixed
    {
        return app(VuexyHelper::class)->appClasses();
    }
}

// =================== CONFIG ===================

if (!function_exists('config_m')) {
    function config_m(?string $component = null): ConfigRepositoryInterface
    {
        $config_m = KonekoConfigManager::make();

        if ($component) {
            $config_m->component($component);
        }

        return $config_m;
    }
}


// =================== SETTINGS ===================

if (!function_exists('settings')) {
    /**
     * Devuelve una instancia de SettingsManager con contexto aplicado automáticamente.
     *
     * @param  string|Model|null  $component
     * - string: asume solo componente.
     * - Model: se intenta extraer scope con `withScopeFromModel()`.
     *
     * @return SettingsRepositoryInterface
     */
    function settings(?string $component = null): SettingsRepositoryInterface
    {
        $settings = KonekoSettingManager::make();

        if ($component) {
            $settings->component($component);
        }

        return $settings;
    }
}


// =================== CACHE ===================

if (!function_exists('cache_m')) {
    /**
     * Crea un gestor de caché con contexto aplicado.
     *
     * Ejemplos:
     * - `cache_m('site')`
     * - `cache_m(['component' => 'site', 'group' => 'seo', 'key_name' => 'enabled'])`
     * - `cache_m($empresaModel)`
     *
     * @param string|Model|null $component
     * @return CacheRepositoryInterface
     */
    function cache_m(?string $component = null): CacheRepositoryInterface
    {
        $cache_m = KonekoCacheManager::make();

        if ($component) {
            $cache_m->component($component);
        }

        return $cache_m;
    }
}


if (!function_exists('media_url')) {
    function media_url(string $path, ?string $disk=null): string {
        $disk = $disk ?? config('koneko.media.default_disk', 'public');
        return Storage::disk($disk)->url($path);
    }
}

if (!function_exists('vendor_or_url')) {
    function vendor_or_url(string $value): string {
        return str_starts_with($value, 'http') ? $value : asset($value);
    }
}



// =================== LOGGERS ===================

if (!function_exists('log_system')) {
    function log_system(
        string|LogLevel $level,
        string $message,
        array $context = [],
        ?\Illuminate\Database\Eloquent\Model $related = null
    ): SystemLog {
        return app(KonekoSystemLogger::class)
            ->log($level, $message, $context, $related);
    }
}

if (!function_exists('log_security')) {
    function log_security(
        string $type,
        ?\Illuminate\Http\Request $request = null,
        ?int $userId = null,
        array $payload = [],
        bool $isProxy = false
    ): void {
        app(KonekoSecurityLogger::class)
            ->logEvent($type, $request, $userId, $payload, $isProxy);
    }
}

if (!function_exists('log_interaction')) {
    function log_interaction(
        string $action,
        array $context = [],
        InteractionSecurityLevel|string $security = 'normal',
        ?string $livewireComponent = null
    ): ?UserInteraction {
        return app(KonekoUserInteractionLogger::class)
            ->record($action, $context, $security, $livewireComponent);
    }
}


// =================== NOTIFICATIONS ===================

if (!function_exists('notify')) {
    function notify(): KonekoNotifyManager
    {
        return app(KonekoNotifyManager::class);
    }
}

/*
if (!function_exists('vuexy_notify')) {
    function vuexy_notify(
        string $message,
        string $type = 'info',
        string $target = 'body',
        int $delay = 5000
    ): void {
        VuexyNotifyHelper::flash(
            $message,
            $type,
            $target,
            $delay
        );
    }
}

if (!function_exists('vuexy_toastr')) {
    function vuexy_toastr(
        string $message,
        string $type = 'info',
        int $delay = 5000
    ): void {
        VuexyToastrHelper::flash(
            $message,
            $type,
            $delay
        );
    }
}
*/

// =================== CATALOGS ===================

if (!function_exists('catalog')) {
    /**
     * Accede a un servicio de catálogos registrado por componente.
     *
     * @param string $component
     * @return \Koneko\KonekoVuexyAdmin\Application\Services\Contracts\CatalogServiceInterface|null
     */
    /*
    function catalog(string $component)
    {
        return CatalogModuleRegistry::get($component);
    }
    */
}



// =================== KEY VAULT ===================
/*
if (!function_exists('vault_value_key')) {
    function vault_value_key(): string
    {
        static $cachedKey = null;

        if ($cachedKey) {
            return $cachedKey;
        }

        $path = env('VAULT_VALUE_KEY_PATH');

        if (!$path || !file_exists($path)) {
            throw new \RuntimeException("Vault Value Key file not found at {$path}");
        }

        $key = trim(file_get_contents($path));

        if (Str::startsWith($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }

        if (empty($key)) {
            throw new \RuntimeException("Vault Value Key is invalid or empty.");
        }

        return $cachedKey = $key;
    }
}
*/
