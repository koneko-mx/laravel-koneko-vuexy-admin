<?php

namespace Koneko\KonekoVuexyAdmin\Application\Cache\Manager\Concerns;

use Koneko\KonekoVuexyAdmin\Application\Traits\System\Context\HasBaseContext;
use Koneko\KonekoVuexyAdmin\Application\Traits\System\Context\HasCacheContextValidation;

trait ___HasCacheContext
{
    use HasBaseContext;
    use HasCacheContextValidation;

    // ======================= HELPERS =========================

    public function reset(): static
    {


        return $this;
    }
}
