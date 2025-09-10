<?php

declare(strict_types=1);

namespace Koneko\KonekoVuexyAdmin\Application\UI\Livewire\Settings\EnvironmentVars;

use Koneko\KonekoVuexyAdmin\Application\UX\ConfigBuilders\System\EnvironmentVarsTableConfigBuilder;
use Koneko\KonekoVuexyAdmin\Support\Livewire\Components\Table\AbstractTableComponent;

class EnvironmentVarsTable extends AbstractTableComponent
{
    protected function configBuilderClass(): ?string
    {
        return EnvironmentVarsTableConfigBuilder::class;
    }

    /**
     * Vista Blade que debe renderizar este componente.
     */
    protected function viewPath(): string
    {
       return 'vuexy-admin::livewire.settings.environment-vars.table-index';
    }
}
