<?php

namespace Koneko\KonekoVuexyAdmin\Application\Contracts\Loggers;

use Koneko\KonekoVuexyAdmin\Application\Enums\UserInteractions\InteractionSecurityLevel;
use Koneko\KonekoVuexyAdmin\Models\UserInteraction;

interface UserInteractionLoggerInterface
{
    public function record(
        string $action,
        array $context = [],
        InteractionSecurityLevel|string $security = 'normal',
        ?string $livewireComponent = null
    ): ?UserInteraction;
}
