<?php

namespace Exylia\ExyliaTheme\Widgets;

use App\Models\Server;
use Filament\Widgets\Widget;

/**
 * Exylia "system pulse" strip, registered below the console via
 * App\Filament\Server\Pages\Console::registerCustomWidgets(BelowConsole, ...).
 *
 * Complements Filament\Server\Widgets\ServerOverview (which already shows
 * live CPU/memory/disk above the console) with context that doesn't change
 * second-to-second: egg, node and allocation info.
 */
class SystemPulseWidget extends Widget
{
    protected string $view = 'exylia-theme::widgets.system-pulse';

    protected int|string|array $columnSpan = 'full';

    public ?Server $server = null;

    /**
     * @return array<int, array{label: string, value: string, icon: string}>
     */
    public function getFacts(): array
    {
        $server = $this->server;

        if (! $server) {
            return [];
        }

        return [
            [
                'label' => 'Egg',
                'value' => $server->egg->name ?? '—',
                'icon' => 'tabler-egg',
            ],
            [
                'label' => 'Node',
                'value' => $server->node->name ?? '—',
                'icon' => 'tabler-server-2',
            ],
            [
                'label' => 'Allocation',
                'value' => $server->allocation->address ?? '—',
                'icon' => 'tabler-plug-connected',
            ],
            [
                'label' => 'Image',
                'value' => str($server->image ?? '—')->afterLast('/')->toString(),
                'icon' => 'tabler-brand-docker',
            ],
        ];
    }
}
