{{--
    Exylia override of livewire.server-entry (the grid-mode server card on the
    App dashboard). Resolved before the panel's own view (see
    ExyliaThemePlugin::boot() -> View::prependLocation()). The upstream file
    lives at resources/views/livewire/server-entry.blade.php and is left
    untouched on disk.

    All data wiring (actions, resource math, thresholds) is copied verbatim
    from the upstream component — only the markup/classes were changed to the
    Exylia card treatment. Do not rename any exposed variables here; the
    Livewire component ($component), $server, and optional $column are
    injected by App\Livewire\ServerEntry::render().
--}}
@php
    $actiongroup = \App\Filament\App\Resources\Servers\Pages\ListServers::getPowerActionGroup()->record($server);
    $backgroundImage = $server->icon ?? $server->egg->icon;

    $serverEntryColumn = $column ?? \App\Filament\Components\Tables\Columns\ServerEntryColumn::make('server_entry');
    $serverNodeStatistics = $server->node->statistics();
    $serverNodeSystemInfo = $server->node->systemInformation();

    $warningPercent = $serverEntryColumn->getWarningThresholdPercent() ?? 0.7;
    $dangerPercent = $serverEntryColumn->getDangerThresholdPercent() ?? 0.9;

    $condition = $server->condition;
    $conditionColor = $condition->getColor();
@endphp
<div wire:poll.15s
     class="exylia-server-card"
     data-tone="{{ $conditionColor }}"
     x-on:click="{{ $component->redirectUrl() }}"
     x-on:auxclick.prevent="if ($event.button === 1) {{ $component->redirectUrl(true) }}"
>
    <span class="exylia-server-card__rail"></span>

    @if($backgroundImage)
        <div class="exylia-server-card__art" style="background-image: url('{{ $backgroundImage }}');"></div>
    @endif

    <div class="exylia-server-card__body">
        <div class="exylia-server-card__head">
            <span class="exylia-server-card__status" data-tone="{{ $conditionColor }}">
                <x-filament::icon
                    :icon="$condition->getIcon()"
                    class="exylia-server-card__status-icon"
                />
            </span>

            <div class="exylia-server-card__title-wrap">
                <h2 class="exylia-server-card__title">
                    {{ $server->name }}
                    <span class="exylia-server-card__uptime">
                        {{ $server->formatResource(\App\Enums\ServerResourceType::Uptime) }}
                    </span>
                </h2>

                @if ($server->description)
                    <p class="exylia-server-card__description">
                        {{ Str::limit($server->description, 48, preserveWords: true) }}
                    </p>
                @endif
            </div>

            @if ($actiongroup->isVisible())
                <div class="exylia-server-card__actions" x-on:click.stop>
                    {{ $actiongroup }}
                </div>
            @endif
        </div>

        <div class="exylia-server-card__stats">
            <div class="exylia-server-card__stat">
                @php
                    $cpuCurrent = \App\Enums\ServerResourceType::CPU->getResourceAmount($server);
                    $cpuMax = \App\Enums\ServerResourceType::CPULimit->getResourceAmount($server) === 0 ? (($serverNodeSystemInfo['cpu_count'] ?? 0) * 100) : \App\Enums\ServerResourceType::CPULimit->getResourceAmount($server);
                    $getState = fn() => $cpuCurrent;
                    $getMaxValue = fn() => $cpuMax;
                    $getProgressPercentage = fn() => $cpuMax > 0 ? ($cpuCurrent / $cpuMax) * 100 : 0;
                    $getProgressLabel = fn () => $server->formatResource(\App\Enums\ServerResourceType::CPU, 0) . ' / ' . $server->formatResource(\App\Enums\ServerResourceType::CPULimit, 0);
                    $getProgressStatus = fn() => ($cpuMax > 0 && ($cpuCurrent / $cpuMax) * 100 >= ($dangerPercent * 100)) ? 'danger' : (( $cpuMax > 0 && ($cpuCurrent / $cpuMax) * 100 >= ($warningPercent * 100)) ? 'warning' : 'success');
                    $getProgressColor = fn() => $serverEntryColumn->getProgressColorForStatus($getProgressStatus());
                @endphp

                <span class="exylia-server-card__stat-label">
                    <x-filament::icon icon="tabler-cpu" class="exylia-server-card__stat-icon" />
                    {{ trans('server/dashboard.cpu') }}
                </span>

                @include('livewire.columns.progress-bar-column', [
                    'getState' => $getState,
                    'getMaxValue' => $getMaxValue,
                    'getProgressPercentage' => $getProgressPercentage,
                    'getProgressLabel' => $getProgressLabel,
                    'getProgressStatus' => $getProgressStatus,
                    'getProgressColor' => $getProgressColor,
                ])
            </div>

            <div class="exylia-server-card__stat">
                @php
                    $memCurrent = \App\Enums\ServerResourceType::Memory->getResourceAmount($server);
                    $memMax = \App\Enums\ServerResourceType::MemoryLimit->getResourceAmount($server) === 0 ? $serverNodeStatistics['memory_total'] : \App\Enums\ServerResourceType::MemoryLimit->getResourceAmount($server);
                    $getState = fn() => $memCurrent;
                    $getMaxValue = fn() => $memMax > 0 ? $memMax : null;
                    $getProgressPercentage = fn() => ($memMax > 0) ? ($memCurrent / $memMax) * 100 : 0;
                    $getProgressLabel = fn() => $server->formatResource(\App\Enums\ServerResourceType::Memory) . ' / ' . $server->formatResource(\App\Enums\ServerResourceType::MemoryLimit);
                    $getProgressStatus = fn() => ($memMax > 0 && ($memCurrent / $memMax) * 100 >= ($dangerPercent * 100)) ? 'danger' : (( $memMax > 0 && ($memCurrent / $memMax) * 100 >= ($warningPercent * 100)) ? 'warning' : 'success');
                    $getProgressColor = fn() => $serverEntryColumn->getProgressColorForStatus($getProgressStatus());
                @endphp

                <span class="exylia-server-card__stat-label">
                    <x-filament::icon icon="tabler-brain" class="exylia-server-card__stat-icon" />
                    {{ trans('server/dashboard.memory') }}
                </span>

                @include('livewire.columns.progress-bar-column', [
                    'getState' => $getState,
                    'getMaxValue' => $getMaxValue,
                    'getProgressPercentage' => $getProgressPercentage,
                    'getProgressLabel' => $getProgressLabel,
                    'getProgressStatus' => $getProgressStatus,
                    'getProgressColor' => $getProgressColor,
                ])
            </div>

            <div class="exylia-server-card__stat">
                @php
                    $diskCurrent = \App\Enums\ServerResourceType::Disk->getResourceAmount($server);
                    $diskMax = \App\Enums\ServerResourceType::DiskLimit->getResourceAmount($server) === 0 ? $serverNodeStatistics['disk_total'] : \App\Enums\ServerResourceType::DiskLimit->getResourceAmount($server);
                    $getState = fn() => $diskCurrent;
                    $getMaxValue = fn() => $diskMax > 0 ? $diskMax : null;
                    $getProgressPercentage = fn() => ($diskMax > 0) ? ($diskCurrent / $diskMax) * 100 : 0;
                    $getProgressLabel = fn() => $server->formatResource(\App\Enums\ServerResourceType::Disk) . ' / ' . $server->formatResource(\App\Enums\ServerResourceType::DiskLimit);
                    $getProgressStatus = fn() => ($diskMax > 0 && ($diskCurrent / $diskMax) * 100 >= ($dangerPercent * 100)) ? 'danger' : (( $diskMax > 0 && ($diskCurrent / $diskMax) * 100 >= ($warningPercent * 100)) ? 'warning' : 'success');
                    $getProgressColor = fn() => $serverEntryColumn->getProgressColorForStatus($getProgressStatus());
                @endphp

                <span class="exylia-server-card__stat-label">
                    <x-filament::icon icon="tabler-device-floppy" class="exylia-server-card__stat-icon" />
                    {{ trans('server/dashboard.disk') }}
                </span>

                @include('livewire.columns.progress-bar-column', [
                    'getState' => $getState,
                    'getMaxValue' => $getMaxValue,
                    'getProgressPercentage' => $getProgressPercentage,
                    'getProgressLabel' => $getProgressLabel,
                    'getProgressStatus' => $getProgressStatus,
                    'getProgressColor' => $getProgressColor,
                ])
            </div>

            <div class="exylia-server-card__network">
                <span class="exylia-server-card__stat-label">
                    <x-filament::icon icon="tabler-plug" class="exylia-server-card__stat-icon" />
                    {{ trans('server/dashboard.network') }}
                </span>
                <span class="exylia-server-card__network-address">
                    {{ $server->allocation?->address ?? trans('server/dashboard.none') }}
                </span>
            </div>
        </div>
    </div>
</div>
