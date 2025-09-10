<?php

declare(strict_types=1);

namespace Koneko\KonekoVuexyAdmin\Application\UI\Livewire\Audit\SecurityEvents;

use Koneko\KonekoVuexyAdmin\Application\UX\ConfigBuilders\System\SecurityEventsTableConfigBuilder;
use Koneko\KonekoVuexyAdmin\Support\Livewire\Components\Table\AbstractTableComponent;

class SecurityEventsTable extends AbstractTableComponent
{
    protected function configBuilderClass(): ?string
    {
        return SecurityEventsTableConfigBuilder::class;
    }

    /**
     * Vista Blade que debe renderizar este componente.
     */
    protected function viewPath(): string
    {
       return 'vuexy-admin::livewire.audit.security-events.table-index';
    }
}
