<?php

namespace Koneko\KonekoVuexyAdmin\Support\Enums\SystemNotifications;

enum SystemNotificationScope: string
{
    case Admin    = 'admin';
    case Frontend = 'frontend';
    case Both     = 'both';
}