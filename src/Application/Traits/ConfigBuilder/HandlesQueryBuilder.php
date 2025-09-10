<?php

declare(strict_types=1);

namespace Koneko\KonekoVuexyAdmin\Application\Traits\ConfigBuilder;

use Illuminate\Http\Request;
use Koneko\KonekoVuexyAdmin\Application\Queries\BootstrapTableQueryBuilder;

trait HandlesQueryBuilder
{
    public function getQueryBuilder(Request $request): BootstrapTableQueryBuilder
    {
        /** @var \Koneko\KonekoVuexyAdmin\Support\Builders\Table\AbstractTableConfigBuilder $self */
        $self = $this;

        return new BootstrapTableQueryBuilder(
            $request,
            $self->getIndexBaseQuery(),
            $self->buildIndexConfig()
        );
    }
}
