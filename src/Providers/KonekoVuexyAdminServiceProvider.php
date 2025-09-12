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

namespace Koneko\KonekoVuexyAdmin\Providers;

use Illuminate\Support\ServiceProvider;
use Koneko\KonekoVuexyAdmin\Application\Bootstrap\Manager\KonekoModuleBootManager;
use Koneko\KonekoVuexyAdmin\Providers\Concerns\{EnforcesHttps, RegistersTrustedProxies};
use Koneko\KonekoVuexyAdmin\Support\Traits\Modules\KonekoModuleBoots;

class KonekoVuexyAdminServiceProvider extends ServiceProvider
{
    use KonekoModuleBoots;
    use EnforcesHttps,
        RegistersTrustedProxies;

    public function register(): void
    {
        $this->registerKonekoModule(dirname(__DIR__));
    }

    public function boot(): void
    {
        $this->enforceHttps();
        $this->registerTrustedProxies();

        $this->registerAllKonekoModules();

        KonekoModuleBootManager::bootAll();
    }
}
