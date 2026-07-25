<?php

namespace Exylia\ExyliaTheme\Widgets;

use App\Models\Server;
use Filament\Facades\Filament;
use Filament\Widgets\Widget;

/**
 * Exylia quick-access strip, registered above the console widgets via
 * App\Filament\Server\Pages\Console::registerCustomWidgets(Top, ...).
 *
 * Surfaces the links an admin/owner reaches for constantly (files, databases,
 * schedules, settings) as a single glanceable row, instead of requiring a
 * sidebar navigation round-trip.
 */
class QuickAccessWidget extends Widget
{
    protected string $view = 'exylia-theme::widgets.quick-access';

    protected int|string|array $columnSpan = 'full';

    public ?Server $server = null;

    /**
     * @return array<int, array{label: string, icon: string, url: string|null}>
     */
    public function getLinks(): array
    {
        $panel = Filament::getCurrentPanel();

        return [
            [
                'label' => 'Files',
                'icon' => 'tabler-folder',
                'url' => class_exists(\App\Filament\Server\Resources\Files\FileResource::class)
                    ? \App\Filament\Server\Resources\Files\FileResource::getUrl(panel: $panel?->getId())
                    : null,
            ],
            [
                'label' => 'Databases',
                'icon' => 'tabler-database',
                'url' => class_exists(\App\Filament\Server\Resources\Databases\DatabaseResource::class)
                    ? \App\Filament\Server\Resources\Databases\DatabaseResource::getUrl(panel: $panel?->getId())
                    : null,
            ],
            [
                'label' => 'Schedules',
                'icon' => 'tabler-clock',
                'url' => class_exists(\App\Filament\Server\Resources\Schedules\ScheduleResource::class)
                    ? \App\Filament\Server\Resources\Schedules\ScheduleResource::getUrl(panel: $panel?->getId())
                    : null,
            ],
            [
                'label' => 'Backups',
                'icon' => 'tabler-cloud-upload',
                'url' => class_exists(\App\Filament\Server\Resources\Backups\BackupResource::class)
                    ? \App\Filament\Server\Resources\Backups\BackupResource::getUrl(panel: $panel?->getId())
                    : null,
            ],
            [
                'label' => 'Settings',
                'icon' => 'tabler-adjustments',
                'url' => class_exists(\App\Filament\Server\Pages\Settings::class)
                    ? \App\Filament\Server\Pages\Settings::getUrl(panel: $panel?->getId())
                    : null,
            ],
        ];
    }
}
