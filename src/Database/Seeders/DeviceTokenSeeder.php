<?php

declare(strict_types=1);

namespace Koneko\KonekoVuexyAdmin\Database\Seeders;

use Koneko\KonekoVuexyAdmin\Models\DeviceToken;
use Koneko\KonekoVuexyAdmin\Support\Seeders\Base\AbstractDataSeeder;

/**
 * 🌱 DeviceTokenSeeder
 *
 * Seeder de tokens de dispositivos móviles para Push Notifications.
 *
 * - Soporta archivos CSV/JSON.
 * - Compatible con generación Faker para testing de dispositivos.
 *
 * @extends AbstractDataSeeder
 */
class DeviceTokenSeeder extends AbstractDataSeeder
{
    // Datos del Modelo
    protected string $model          = DeviceToken::class;
    protected string|array $uniqueBy = 'token';
}
