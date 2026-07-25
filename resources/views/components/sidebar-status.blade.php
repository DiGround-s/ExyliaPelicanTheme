@php
    $tenant = \Filament\Facades\Filament::getTenant();
    $server = $tenant instanceof \App\Models\Server ? $tenant : null;
    $version = app(\App\Services\Helpers\SoftwareVersionService::class)->currentPanelVersion();
@endphp

<div class="exylia-sidebar-status">
    @if ($server)
        @php $condition = $server->condition; @endphp
        <div class="exylia-sidebar-status__row">
            <span
                class="exylia-sidebar-status__dot"
                data-color="{{ $condition->getColor() }}"
            ></span>
            <span class="exylia-sidebar-status__label">{{ $condition->getLabel() }}</span>
        </div>
    @endif

    <div class="exylia-sidebar-status__version">
        <x-filament::icon icon="tabler-sparkles" class="exylia-sidebar-status__version-icon" />
        <span>Exylia · v{{ $version }}</span>
    </div>
</div>
