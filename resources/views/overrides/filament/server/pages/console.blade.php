{{--
    Exylia override of filament.server.pages.console.
    Resolved before the panel's own view because Exylia\ExyliaTheme\ExyliaThemePlugin
    prepends this plugin's overrides/ directory to the view finder paths for
    the server panel. The upstream file lives at
    resources/views/filament/server/pages/console.blade.php in the panel and
    is left untouched on disk.

    Structural changes vs upstream:
    - Wraps everything in .exylia-console-shell for a dedicated grid/background layer.
    - Adds a context header (server name + live condition chip) above the
      widgets, which the stock page does not have.
--}}
<x-filament-panels::page class="fi-console-page exylia-console-page">
    @php
        $server = \Filament\Facades\Filament::getTenant();
    @endphp

    <div class="exylia-console-shell">
        @if ($server)
            <div class="exylia-console-context">
                <div class="exylia-console-context__identity">
                    <span class="exylia-console-context__eyebrow">Server</span>
                    <h2 class="exylia-console-context__name">{{ $server->name }}</h2>
                </div>

                <div
                    class="exylia-console-context__status"
                    data-color="{{ $status->getColor() }}"
                >
                    <x-filament::icon :icon="$status->getIcon()" class="exylia-console-context__status-icon" />
                    <span>{{ $status->getLabel() }}</span>
                </div>
            </div>
        @endif

        <x-filament-widgets::widgets
            :columns="$this->getColumns()"
            :data="$this->getWidgetData()"
            :widgets="$this->getVisibleWidgets()"
        />
    </div>

    <x-filament-actions::modals />
</x-filament-panels::page>
