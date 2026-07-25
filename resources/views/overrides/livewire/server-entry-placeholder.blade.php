{{--
    Exylia override of livewire.server-entry-placeholder — the Livewire lazy-load
    skeleton shown while App\Livewire\ServerEntry mounts (see
    App\Livewire\ServerEntry::placeholder()). Resolved before the panel's own
    view via ExyliaThemePlugin::boot() -> View::prependLocation(). The upstream
    file lives at resources/views/livewire/server-entry-placeholder.blade.php
    and is left untouched on disk.

    Structure intentionally mirrors the exylia-server-card markup in our
    livewire/server-entry.blade.php override so the real card doesn't "pop" in
    with a different layout once Livewire finishes loading — bars/labels are
    swapped for shimmer blocks instead of live data.
--}}
@php
    $backgroundImage = $server->icon ?? $server->egg->icon;
@endphp
<div
     class="exylia-server-card exylia-server-card--loading"
     data-tone="warning"
     x-on:click="{{ $component->redirectUrl() }}"
     x-on:auxclick.prevent="if ($event.button === 1) {{ $component->redirectUrl(true) }}"
>
    <span class="exylia-server-card__rail"></span>

    @if($backgroundImage)
        <div class="exylia-server-card__art" style="background-image: url('{{ $backgroundImage }}');"></div>
    @endif

    <div class="exylia-server-card__body">
        <div class="exylia-server-card__head">
            <span class="exylia-server-card__status" data-tone="warning">
                <x-filament::loading-indicator class="exylia-server-card__status-icon" />
            </span>

            <div class="exylia-server-card__title-wrap">
                <h2 class="exylia-server-card__title">
                    {{ $server->name }}
                    <span class="exylia-server-card__uptime">{{ trans('server/dashboard.loading') }}</span>
                </h2>

                @if ($server->description)
                    <p class="exylia-server-card__description">
                        {{ Str::limit($server->description, 48, preserveWords: true) }}
                    </p>
                @endif
            </div>
        </div>

        <div class="exylia-server-card__stats">
            <div class="exylia-server-card__stat">
                <span class="exylia-server-card__stat-label">
                    <x-filament::icon icon="tabler-cpu" class="exylia-server-card__stat-icon" />
                    {{ trans('server/dashboard.cpu') }}
                </span>
                <span class="exylia-server-card__skeleton-bar"></span>
            </div>

            <div class="exylia-server-card__stat">
                <span class="exylia-server-card__stat-label">
                    <x-filament::icon icon="tabler-brain" class="exylia-server-card__stat-icon" />
                    {{ trans('server/dashboard.memory') }}
                </span>
                <span class="exylia-server-card__skeleton-bar"></span>
            </div>

            <div class="exylia-server-card__stat">
                <span class="exylia-server-card__stat-label">
                    <x-filament::icon icon="tabler-device-floppy" class="exylia-server-card__stat-icon" />
                    {{ trans('server/dashboard.disk') }}
                </span>
                <span class="exylia-server-card__skeleton-bar"></span>
            </div>

            <div class="exylia-server-card__network">
                <span class="exylia-server-card__stat-label">
                    <x-filament::icon icon="tabler-plug" class="exylia-server-card__stat-icon" />
                    {{ trans('server/dashboard.network') }}
                </span>
                <span class="exylia-server-card__skeleton-bar exylia-server-card__skeleton-bar--short"></span>
            </div>
        </div>
    </div>
</div>
