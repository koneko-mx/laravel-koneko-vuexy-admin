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

use Illuminate\Auth\Events\{Failed, Login, Logout};
use Koneko\KonekoVuexyAdmin\Application\Cache\Manager\KonekoCacheManager;
use Koneko\KonekoVuexyAdmin\Application\Config\Cast\VuexyLayoutCast;
use Koneko\KonekoVuexyAdmin\Application\Contracts\Loggers\{SecurityLoggerInterface, SystemLoggerInterface, UserInteractionLoggerInterface};
use Koneko\KonekoVuexyAdmin\Application\Settings\Contracts\SettingsRepositoryInterface;
use Koneko\KonekoVuexyAdmin\Application\Events\Settings\VuexyCustomizerSettingsUpdated;
use Koneko\KonekoVuexyAdmin\Application\Helpers\{VuexyHelper, VuexyNotifyHelper, VuexyToastrHelper};
use Koneko\KonekoVuexyAdmin\Application\Http\Middleware\{AdminTemplateMiddleware, LocaleMiddleware, TrackSessionActivity};
use Koneko\KonekoVuexyAdmin\Application\Jobs\Security\RotateVaultKeysJob;
use Koneko\KonekoVuexyAdmin\Application\Jobs\Users\ForceLogoutInactiveUsersJob;
use Koneko\KonekoVuexyAdmin\Application\Listeners\Authentication\{HandleFailedLogin, HandleUserLogin, HandleUserLogout};
use Koneko\KonekoVuexyAdmin\Application\Listeners\Settings\ApplyVuexyCustomizerSettings;
use Koneko\KonekoVuexyAdmin\Application\Loggers\{KonekoSecurityAuditLogger, KonekoUserInteractionLogger, KonekoSecurityLogger, KonekoSystemLogger};
use Koneko\KonekoVuexyAdmin\Application\Settings\Manager\KonekoSettingManager;
use Koneko\KonekoVuexyAdmin\Application\UI\Livewire\Audit\LaravelLogs\LaravelLogsTable;
use Koneko\KonekoVuexyAdmin\Application\UI\Livewire\Audit\SecurityEvents\SecurityEventsTable;
use Koneko\KonekoVuexyAdmin\Application\UI\Livewire\Audit\UsersAuthLogs\UsersAuthLogsTable;
use Koneko\KonekoVuexyAdmin\Application\UI\Livewire\KonekoVuexy\ModuleManagement\ModuleManagementIndex;
use Koneko\KonekoVuexyAdmin\Application\UI\Livewire\KonekoVuexy\Plugins\{PluginsIndex, VuexyQuicklinks};
use Koneko\KonekoVuexyAdmin\Application\UI\Livewire\Pages\Dashboards\MenuAccessCards;
use Koneko\KonekoVuexyAdmin\Application\UI\Livewire\Settings\EnvironmentVars\{EnvironmentVarsTable, EnvironmentVarsOffCanvasForm};
use Koneko\KonekoVuexyAdmin\Application\UI\Livewire\Settings\Rbac\Permissions\{PermissionsTable, PermissionOffCanvasForm};
use Koneko\KonekoVuexyAdmin\Application\UI\Livewire\Settings\Rbac\Roles\{RolesIndex, RoleCards};
use Koneko\KonekoVuexyAdmin\Application\UI\Livewire\Settings\Smtp\SmtpSettingsCard;
use Koneko\KonekoVuexyAdmin\Application\UI\Livewire\Settings\Users\{UsersTable, UsersCount, UserForm, UserOffCanvasForm};
use Koneko\KonekoVuexyAdmin\Application\UI\Livewire\Settings\VuexyInterface\VuexyInterfaceIndex;
use Koneko\KonekoVuexyAdmin\Application\UI\Livewire\Settings\WebInterface\{LogoOnLightBgCard, LogoOnDarkBgCard, AppDescriptionCard, AppFaviconCard};
use Koneko\KonekoVuexyAdmin\Application\UI\Livewire\Tools\Cache\{CacheFunctionsCard, CacheStatsCard, SessionStatsCard, MemcachedStatsCard, RedisStatsCard};
use Koneko\KonekoVuexyAdmin\Application\UI\Livewire\User\Profile\{UpdateProfileInformationForm, UpdatePasswordForm, TwoFactorAuthenticationForm, LogoutOtherBrowser, DeleteUserForm};
use Koneko\KonekoVuexyAdmin\Application\UI\Livewire\User\Viewer\UserDetailsViewerIndex;
use Koneko\KonekoVuexyAdmin\Console\Commands\Geolocationg\DownloadGeoIpDatabase;
use Koneko\KonekoVuexyAdmin\Console\Commands\Layout\VuexyMenuBuildCommand;
use Koneko\KonekoVuexyAdmin\Console\Commands\Layout\VuexyMenuListModulesCommand;
use Koneko\KonekoVuexyAdmin\Console\Commands\Notifications\VuexyDeviceTokenPruneCommand;
use Koneko\KonekoVuexyAdmin\Console\Commands\Orquestator\VuexySeedCommand;
use Koneko\KonekoVuexyAdmin\Console\Commands\RBAC\VuexyRbacCommand;
use Koneko\KonekoVuexyAdmin\Console\Commands\UI\VuexyAvatarInitialsCommand;
use Koneko\KonekoVuexyAdmin\Models\{Setting, User};
use Koneko\KonekoVuexyAdmin\Providers\FortifyServiceProvider;
use Spatie\Permission\PermissionServiceProvider;

return [
    // 🌐 Identidad del Módulo
    'name' => 'Vuexy Admin',
    'description' => 'Laravel Vuexy Admin, núcleo del ERP optimizado para México.',
    'type' => 'core',
    'tags' => ['koneko-official', 'core', 'admin', 'rbac', 'erp'],

    // ⚙️ Namespace de configuraciones Koneko Vuexy Admin
    'componentNamespace' => 'core',

    // 🗒 Metadatos visuales para UI del gestor
    'ui' => [
        'image'  => 'resources/img/module-cover.png',
        'readme' => 'README.md',
    ],

    // ⚙️ Archivos de configuración del módulo
    'configs' => [
        'auth'    => 'config/auth.php',
        'fortify' => 'config/fortify.php',
        'image'   => 'config/image.php',
        'koneko'                => 'config/koneko.php',
        'koneko.core.layout'    => 'config/koneko_layout.php',
        'koneko.core.ui'        => 'config/koneko_ui.php',
        'koneko.core.logging'   => 'config/koneko_logging.php',
        'koneko.core.security'  => 'config/koneko_security.php',
        'koneko.core.key_vault' => 'config/koneko_key_vault.php',
        'koneko.media'          => 'config/koneko_media.php',
        'database.connections.vault' => 'config/koneko_key_vault_db.php',
    ],
    // 📦 Configuraciones de bloques
    'configBlocks' => [
        'koneko.core.layout.vuexy' => [
            'component' => 'core',
            'group'     => 'layout',
            'section'   => 'vuexy',
            'sub_group' => 'customizer',
            'key_name'  => 'vuexy-layout',
            'cast'      => VuexyLayoutCast::class,
        ],
    ],

    // 🏭 Proveedores de servicio, Middleware y Aliases (runtime)
    'providers' => [
        FortifyServiceProvider::class,
        PermissionServiceProvider::class,
    ],
    'middleware' => [
        'admin'           => AdminTemplateMiddleware::class,
        'sessionActivity' => TrackSessionActivity::class,
        'lang'            => LocaleMiddleware::class,
    ],
    'aliases' => [
        'VuexyNotify' => VuexyNotifyHelper::class,
        'VuexyToastr' => VuexyToastrHelper::class,
        'Helper'      => VuexyHelper::class,
    ],

    // 🔩 Singletons
    'Singletons' => [
        KonekoCacheManager::class,
        KonekoSecurityAuditLogger::class,
        //KonekoAdminVarsBuilder::class,
    ],

    // 🔗 Bindings de interfaces a servicios
    'bindings' => [
        SettingsRepositoryInterface::class    => KonekoSettingManager::class,
        SystemLoggerInterface::class          => KonekoSystemLogger::class,
        SecurityLoggerInterface::class        => KonekoSecurityLogger::class,
        UserInteractionLoggerInterface::class => KonekoUserInteractionLogger::class,
    ],

    // 📜 Macros
    'macros' => [
        //
    ],

    // 🔊 Eventos
    'listeners' => [
        //SettingChanged::class => SettingCacheListener::class,
        //VuexyCustomizerSettingsUpdated::class => ApplyVuexyCustomizerSettings::class,
        Login::class  => HandleUserLogin::class,
        Logout::class => HandleUserLogout::class,
        Failed::class => HandleFailedLogin::class,
    ],

    // 🧪 Modelos auditables
    'auditable' => [
        User::class,
    ],

    // 📦 migraciones
    'migrations' => [
        'database/migrations',
    ],

    // 🗺️ Rutas
    'routes' => [
        [
            'middleware' => ['web', 'auth', 'admin', 'lang'],
            'paths' => [
                'routes/admin.php',
                'routes/user.php',
                'routes/users-rbac.php',
                'routes/koneko.php',
            ],
        ],
        [
            'middleware' => ['web', 'auth', 'lang'],
            'paths' => [
                'routes/system.php',
            ],
        ],
        [
            'middleware' => ['web', 'lang'],
            'paths' => [
                'routes/pages.php',
                'routes/language.php',
            ],
        ]
    ],

    // 🗂️ Vistas, traducciones
    'views' => [
        'vuexy-admin' => 'resources/views',
    ],
    'translations' => [
        'lang' => 'resources/lang',
    ],

    // 🧩 Componentes Blade y Livewire
    /*
    'bladeComponents' => [
        'vuexy-admin' => 'KonekoVuexyAdmin\\View\\Components',
    ],
    */
    'livewire' => [
        'vuexy-admin' => [
            // Usuarios
            'users-table'         => UsersTable::class,
            'users-count'         => UsersCount::class,
            'user-form'           => UserForm::class,
            'user-offcanvas-form' => UserOffCanvasForm::class,

            // Roles y permisos
            'roles-index'               => RolesIndex::class,
            'role-cards'                => RoleCards::class,
            'permissions-table'         => PermissionsTable::class,
            'permission-offcanvas-form' => PermissionOffCanvasForm::class,

            // Interfaz Web
            'app-description-card'  => AppDescriptionCard::class,
            'app-favicon-card'      => AppFaviconCard::class,
            'logo-on-light-bg-card' => LogoOnLightBgCard::class,
            'logo-on-dark-bg-card'  => LogoOnDarkBgCard::class,

            // Interfaz Vuexy
            'vuexy-interface-index' => VuexyInterfaceIndex::class,

            // Configuraciones SMTP
            'smtp-settings-card' => SmtpSettingsCard::class,

            // Variables de entorno
            'environment-vars-table'          => EnvironmentVarsTable::class,
            'environment-vars-offcanvas-form' => EnvironmentVarsOffCanvasForm::class,

            // Cache
            'cache-stats-card'     => CacheStatsCard::class,
            'session-stats-card'   => SessionStatsCard::class,
            'redis-stats-card'     => RedisStatsCard::class,
            'memcached-stats-card' => MemcachedStatsCard::class,
            'cache-functions-card' => CacheFunctionsCard::class,

            // Koneko Vuexy
            'module-management-index' => ModuleManagementIndex::class,
            'plugins-index'           => PluginsIndex::class,

            // Auditoría
            'auth-users-logs-table' => UsersAuthLogsTable::class,
            'laravel-logs-table'    => LaravelLogsTable::class,
            'security-events-table' => SecurityEventsTable::class,

            // Perfil
            'update-profile-information-form' => UpdateProfileInformationForm::class,
            'update-password-form'            => UpdatePasswordForm::class,
            'two-factor-authentication-form'  => TwoFactorAuthenticationForm::class,
            'logout-other-browser'            => LogoutOtherBrowser::class,
            'delete-user-form'                => DeleteUserForm::class,

            // Visor de usuario
            'user-details-viewer-index' => UserDetailsViewerIndex::class,

            // Accesos rápidos a carpetas
            'menu-access-cards' => MenuAccessCards::class,

            // Navbar
            'vuexy-quicklinks' => VuexyQuicklinks::class,
        ]
    ],

    // 📁 Publicar archivos
    'publishedFiles' => [
        'assets'              => ['resources/public'   => public_path('vendor/koneko/vuexy-admin/')],
        'auth-config'         => ['config/image.php'   => config_path('image.php')],
        'fortify-config'      => ['config/fortify.php' => config_path('fortify.php')],
        'image-config'        => ['config/image.php'   => config_path('image.php')],
        'koneko_media-config' => ['config/koneko_media.php' => config_path('koneko_media.php')],
        'seeder-samples'      => ['database/data/seeder_samples' => base_path('database/data/koneko-vuexy-admin/seeder_samples/')],
    ],

    // 🛠 Comandos Artisan
    'commands' => [
        DownloadGeoIpDatabase::class,
        VuexyAvatarInitialsCommand::class,
        VuexyDeviceTokenPruneCommand::class,
        VuexyMenuBuildCommand::class,
        VuexyMenuListModulesCommand::class,
        VuexyRbacCommand::class,
        VuexySeedCommand::class,
    ],

    // 📦 Scope Models
    'scopeModels' => [
        'user' => User::class,
    ],

    // Trabajos programados
    'schedules' => [
        [
            'job'    => ForceLogoutInactiveUsersJob::class,
            'method' => 'cron',
            'params' => ['*/10 * * * *'],
            'chain'  => ['withoutOverlapping'],
        ],
        [
            'job'    => RotateVaultKeysJob::class,
            'method' => 'cron',
            'params' => ['*/10 * * * *'],
            'chain'  => ['withoutOverlapping'],
        ],
    ],

    // 🛡️ Configuración de roles y permisos (RBAC)
    'rbac' => [
        'permissions_path' => 'database/rbac/permissions.json',
        'roles_path'       => 'database/rbac/roles.json',
    ],
];
