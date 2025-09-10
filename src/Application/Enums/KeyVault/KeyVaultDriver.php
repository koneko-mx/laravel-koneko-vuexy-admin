<?php

namespace Koneko\KonekoVuexyAdmin\Application\Enums\KeyVault;

enum KeyVaultDriver: string {
    case LARAVEL = 'laravel';
    case DATABASE = 'database';
    case SERVICE = 'service';
}