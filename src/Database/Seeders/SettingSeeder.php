<?php

declare(strict_types=1);

namespace Koneko\KonekoVuexyAdmin\Database\Seeders;

use Koneko\KonekoVuexyAdmin\Models\Setting;
use Koneko\KonekoVuexyAdmin\Support\Seeders\Base\AbstractDataSeeder;
use Koneko\KonekoVuexyAdmin\Support\Traits\Seeders\HandlesFileSeeders;

class SettingSeeder extends AbstractDataSeeder
{
    use HandlesFileSeeders;

    // Datos del Modelo
    protected string $model          = Setting::class;
    protected string|array $uniqueBy = 'key';

    // Ruta del archivo de datos
    protected string $targetFile = 'settings.json';
}
