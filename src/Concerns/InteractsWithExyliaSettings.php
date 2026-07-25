<?php

namespace Exylia\ExyliaTheme\Concerns;

use App\Traits\EnvironmentWriterTrait;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;

/**
 * Implements the App\Contracts\Plugins\HasPluginSettings contract so a
 * "Settings" button appears in Admin > Plugins for this theme.
 */
trait InteractsWithExyliaSettings
{
    use EnvironmentWriterTrait;

    public function getSettingsFormData(): array
    {
        $c = config('exylia-theme.colors');
        $e = config('exylia-theme.effects');
        $b = config('exylia-theme.brand');

        return [
            'brand_name' => $b['name'],
            'brand_logo' => $b['logo'],

            'color_primary' => '#' . ltrim($c['primary'], '#'),
            'color_secondary' => '#' . ltrim($c['secondary'], '#'),
            'color_secondary_light' => '#' . ltrim($c['secondary_light'], '#'),

            'glow' => (bool) ($e['glow'] ?? true),
            'glow_intensity' => (int) ($e['glow_intensity'] ?? 55),
            'animations' => (bool) ($e['animations'] ?? true),
            'custom_scrollbar' => (bool) ($e['custom_scrollbar'] ?? true),
            'starfield' => (bool) ($e['starfield'] ?? true),
        ];
    }

    /**
     * @return \Filament\Schemas\Components\Component[]
     */
    public function getSettingsForm(): array
    {
        return [
            Section::make('Branding')
                ->description('Override the panel brand shown in the sidebar and login.')
                ->columns(2)
                ->schema([
                    TextInput::make('brand_name')
                        ->label('Brand name')
                        ->placeholder('Keep panel default')
                        ->maxLength(60),
                    TextInput::make('brand_logo')
                        ->label('Brand logo URL')
                        ->placeholder('https://.../logo.svg')
                        ->url()
                        ->maxLength(255),
                ]),

            Section::make('Galactic palette')
                ->description('Exylia derives all gradients and glow from these three violets.')
                ->columns(3)
                ->schema([
                    ColorPicker::make('color_primary')->label('Primary')->required(),
                    ColorPicker::make('color_secondary')->label('Secondary')->required(),
                    ColorPicker::make('color_secondary_light')->label('Secondary light')->required(),
                ]),

            Section::make('Atmosphere & motion')
                ->description('Depth and movement should reinforce hierarchy, never hide it.')
                ->columns(2)
                ->schema([
                    Toggle::make('glow')->label('Background glow')->live(),
                    TextInput::make('glow_intensity')
                        ->label('Glow intensity')
                        ->numeric()->minValue(0)->maxValue(100)->suffix('%')
                        ->disabled(fn (Get $get) => ! $get('glow')),
                    Toggle::make('animations')
                        ->label('Motion & microinteractions')
                        ->helperText('Respects prefers-reduced-motion regardless of this toggle.'),
                    Toggle::make('starfield')->label('Subtle starfield'),
                    Toggle::make('custom_scrollbar')->label('Custom scrollbar'),
                ]),
        ];
    }

    public function saveSettings(array $data): void
    {
        $this->writeToEnvironment([
            'EXYLIA_BRAND_NAME' => $data['brand_name'] ?? '',
            'EXYLIA_BRAND_LOGO' => $data['brand_logo'] ?? '',

            'EXYLIA_COLOR_PRIMARY' => ltrim((string) $data['color_primary'], '#'),
            'EXYLIA_COLOR_SECONDARY' => ltrim((string) $data['color_secondary'], '#'),
            'EXYLIA_COLOR_SECONDARY_LIGHT' => ltrim((string) $data['color_secondary_light'], '#'),

            'EXYLIA_EFFECT_GLOW' => ! empty($data['glow']) ? 'true' : 'false',
            'EXYLIA_EFFECT_GLOW_INTENSITY' => (string) (int) ($data['glow_intensity'] ?? 55),
            'EXYLIA_EFFECT_ANIMATIONS' => ! empty($data['animations']) ? 'true' : 'false',
            'EXYLIA_EFFECT_SCROLLBAR' => ! empty($data['custom_scrollbar']) ? 'true' : 'false',
            'EXYLIA_EFFECT_STARFIELD' => ! empty($data['starfield']) ? 'true' : 'false',
        ]);
    }
}
