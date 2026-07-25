<?php

namespace Exylia\ExyliaTheme;

use App\Contracts\Plugins\HasPluginSettings;
use App\Enums\ConsoleWidgetPosition;
use App\Filament\Server\Pages\Console;
use Exylia\ExyliaTheme\Concerns\BuildsExyliaPalette;
use Exylia\ExyliaTheme\Concerns\InteractsWithExyliaSettings;
use Exylia\ExyliaTheme\Widgets\QuickAccessWidget;
use Exylia\ExyliaTheme\Widgets\SystemPulseWidget;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsIconAlias;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\HtmlString;

class ExyliaThemePlugin implements HasPluginSettings, Plugin
{
    use BuildsExyliaPalette;
    use InteractsWithExyliaSettings;

    public function getId(): string
    {
        return 'exylia-theme';
    }

    public static function make(): static
    {
        return app(static::class);
    }

    /**
     * Runs while the panel is being configured.
     */
    public function register(Panel $panel): void
    {
        // Palette — Filament resolves these into full shade maps.
        $panel->colors($this->paletteForPanel());

        // Typography.
        if ($font = config('exylia-theme.font')) {
            $panel->font($font);
        }

        // Branding (optional overrides).
        $brand = config('exylia-theme.brand');
        if (! empty($brand['name'])) {
            $panel->brandName($brand['name']);
        }
        if (! empty($brand['logo'])) {
            $panel->brandLogo($brand['logo']);
            $panel->brandLogoHeight($brand['logo_height'] ?? '2rem');
        }

        // Compiled galactic stylesheet. The panel's vite.config.js globs
        // plugins/*/resources/css/**/*.css, so the Vite manifest key is the
        // full path below (build dir defaults to public/build).
        $panel->viteTheme('plugins/exylia-theme/resources/css/theme.css');

        // Living theme gallery — admin panel only.
        if ($panel->getId() === 'admin') {
            $panel->pages([
                \Exylia\ExyliaTheme\Pages\ThemePreview::class,
            ]);
        }
    }

    /**
     * Runs via middleware only when this panel is actually in use.
     */
    public function boot(Panel $panel): void
    {
        // Inject runtime CSS variables + body classes derived from settings.
        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_END,
            fn (): string => $this->renderHead(),
        );

        // Galactic atmosphere layer (glow + optional starfield) behind content.
        FilamentView::registerRenderHook(
            PanelsRenderHook::BODY_START,
            fn (): string => Blade::render('<x-exylia-theme::atmosphere />'),
        );

        // Structural sidebar/topbar/user-menu icon replacements — applies to
        // every panel this theme is active on.
        $this->registerIconAliases();

        // Sidebar footer with live status + regrouped, glanceable navigation
        // accents (badges, section separators) — cosmetic hooks only, the
        // navigation tree itself is still owned by each PanelProvider.
        $this->registerSidebarHooks();

        // Prepend our override view location once — resolves same-relative-path
        // blade files (console shell, server list cards) before the panel's own,
        // leaving every upstream view untouched on disk.
        View::prependLocation(dirname(__DIR__) . '/resources/views/overrides');

        if ($panel->getId() === 'server') {
            $this->registerServerConsoleOverrides();
        }
    }

    protected function registerIconAliases(): void
    {
        FilamentIcon::register([
            PanelsIconAlias::SIDEBAR_COLLAPSE_BUTTON => 'tabler-layout-sidebar-left-collapse',
            PanelsIconAlias::SIDEBAR_EXPAND_BUTTON => 'tabler-layout-sidebar-left-expand',
            PanelsIconAlias::SIDEBAR_GROUP_COLLAPSE_BUTTON => 'tabler-chevron-down',
            PanelsIconAlias::TOPBAR_OPEN_SIDEBAR_BUTTON => 'tabler-menu-2',
            PanelsIconAlias::TOPBAR_CLOSE_SIDEBAR_BUTTON => 'tabler-x',
            PanelsIconAlias::USER_MENU_PROFILE_ITEM => 'tabler-user-circle',
            PanelsIconAlias::USER_MENU_LOGOUT_BUTTON => 'tabler-logout-2',
            PanelsIconAlias::THEME_SWITCHER_LIGHT_BUTTON => 'tabler-sun-filled',
            PanelsIconAlias::THEME_SWITCHER_DARK_BUTTON => 'tabler-moon-stars',
            PanelsIconAlias::THEME_SWITCHER_SYSTEM_BUTTON => 'tabler-device-desktop',
            PanelsIconAlias::GLOBAL_SEARCH_FIELD => 'tabler-command',
        ]);
    }

    protected function registerSidebarHooks(): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::SIDEBAR_FOOTER,
            fn (): string => Blade::render('<x-exylia-theme::sidebar-status />'),
        );
    }

    /**
     * Registers Exylia's console widgets via the panel's own public extension
     * point. The matching view overrides are resolved via the prepended
     * location registered in boot().
     */
    protected function registerServerConsoleOverrides(): void
    {
        Console::registerCustomWidgets(ConsoleWidgetPosition::Top, [
            QuickAccessWidget::class,
        ]);

        Console::registerCustomWidgets(ConsoleWidgetPosition::BelowConsole, [
            SystemPulseWidget::class,
        ]);
    }

    protected function renderHead(): string
    {
        $vars = collect($this->cssVariables())
            ->map(fn ($v, $k) => "{$k}:{$v};")
            ->implode('');

        $bodyClasses = $this->bodyClasses();

        return new HtmlString(<<<HTML
        <style id="exylia-theme-vars">:root{{$vars}}</style>
        <script>document.documentElement.classList.add(..."{$bodyClasses}".split(" "));</script>
        HTML) . '';
    }
}
