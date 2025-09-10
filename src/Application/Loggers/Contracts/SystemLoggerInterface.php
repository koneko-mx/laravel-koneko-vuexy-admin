<?php

namespace Koneko\KonekoVuexyAdmin\Application\Contracts\Loggers;

use Illuminate\Database\Eloquent\Model;
use Koneko\KonekoVuexyAdmin\Application\Enums\SystemLog\LogTriggerType;
use Koneko\KonekoVuexyAdmin\Application\Enums\SystemLog\LogLevel;
use Koneko\KonekoVuexyAdmin\Models\SystemLog;

interface SystemLoggerInterface
{
    public function log(
        string|LogLevel $level,
        string $message,
        array $context = [],
        ?Model $relatedModel = null,
        LogTriggerType $triggerType = LogTriggerType::System,
        ?int $triggerId = null
    ): SystemLog;
}
