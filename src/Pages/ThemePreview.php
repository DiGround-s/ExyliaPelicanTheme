<?php

namespace Exylia\ExyliaTheme\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

/**
 * A living gallery of the Exylia theme: palette, buttons, inputs, badges,
 * cards and states — so an admin can verify the look in one place.
 *
 * Registered on the admin panel from ExyliaThemePlugin::register().
 */
class ThemePreview extends Page
{
    protected static ?string $navigationLabel = 'Theme Preview';

    protected static ?string $title = 'Exylia — Theme Preview';

    protected static string | \BackedEnum | null $navigationIcon = 'tabler-palette';

    protected static string | \UnitEnum | null $navigationGroup = 'Appearance';

    protected static ?int $navigationSort = 99;

    protected string $view = 'exylia-theme::pages.preview';

    public static function getSlug(?\Filament\Panel $panel = null): string
    {
        return 'exylia-theme/preview';
    }

    /**
     * Only authenticated panel users may open the preview.
     */
    public static function canAccess(): bool
    {
        return Auth::check();
    }

    /**
     * Data consumed by the blade gallery.
     *
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        return [
            'colors' => config('exylia-theme.colors'),
            'effects' => config('exylia-theme.effects'),
            'font' => config('exylia-theme.font'),
        ];
    }
}
