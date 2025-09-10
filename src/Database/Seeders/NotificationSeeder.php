<?php

declare(strict_types=1);

namespace Koneko\KonekoVuexyAdmin\Database\Seeders;

use Koneko\KonekoVuexyAdmin\Models\Notification;
use Koneko\KonekoVuexyAdmin\Support\Seeders\Base\AbstractDataSeeder;

/**
 * 🌱 NotificationSeeder
 *
 * Seeder de notificaciones base del ecosistema Koneko Vuexy ERP.
 *
 * - Soporta archivos CSV/JSON.
 * - Permite generación Faker en modo demo o testing.
 *
 * @extends AbstractDataSeeder
 */
class NotificationSeeder extends AbstractDataSeeder
{
    // Datos del Modelo
    protected string $model          = Notification::class;
    protected string|array $uniqueBy = 'id';
}
