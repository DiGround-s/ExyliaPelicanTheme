<?php

namespace Exylia\ExyliaTheme;

use App\Contracts\Plugins\HasPluginSettings;
use Exylia\ExyliaTheme\Concerns\BuildsExyliaPalette;
use Exylia\ExyliaTheme\Concerns\InteractsWithExyliaSettings;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
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
