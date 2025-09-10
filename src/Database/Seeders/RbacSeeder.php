<?php

declare(strict_types=1);

namespace Koneko\KonekoVuexyAdmin\Database\Seeders;

use Illuminate\Database\Seeder;
use Koneko\KonekoVuexyAdmin\Application\RBAC\Sync\KonekoRbacSyncManager;

class RbacSeeder extends Seeder
{
    /**
     * Ejecuta el seeder completo para RBAC.
     */
    public function run(): void
    {
        $this->command?->info(" 🔐 Iniciando carga de Roles y Permisos desde módulos...");

        // Sincroniza permisos y roles de todos los módulos
        KonekoRbacSyncManager::importAll();

        $this->command?->info(" Roles y permisos cargados exitosamente.");
    }
}
