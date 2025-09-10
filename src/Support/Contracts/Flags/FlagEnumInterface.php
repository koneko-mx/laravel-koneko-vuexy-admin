<?php

declare(strict_types=1);

namespace Koneko\KonekoVuexyAdmin\Application\Contracts\Flags;

interface FlagEnumInterface
{
    public function flagName(): string;
    public function description(): string;
    public static function modelClass(): string;
}
