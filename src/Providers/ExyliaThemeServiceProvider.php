<?php

namespace Exylia\ExyliaTheme\Providers;

use Illuminate\Support\ServiceProvider;

class ExyliaThemeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Merge defaults so config('exylia-theme.*') is always populated even
        // if the panel's PluginService loads this provider before the config.
        $this->mergeConfigFrom(
            dirname(__DIR__, 2) . '/config/exylia-theme.php',
            'exylia-theme',
        );
    }

    public function boot(): void
    {
        // The panel already registers resources/views under the "exylia-theme::"
        // namespace, which makes <x-exylia-theme::atmosphere /> resolvable from
        // resources/views/components/atmosphere.blade.php.
        //
        // Nothing else is required here; kept for future runtime bindings.
    }
}
