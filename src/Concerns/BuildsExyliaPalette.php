<?php

namespace Exylia\ExyliaTheme\Concerns;

use Filament\Support\Colors\Color;

/**
 * Turns the configured hex values into the shade maps Filament expects and
 * exposes the raw hex values for use inside CSS custom properties.
 */
trait BuildsExyliaPalette
{
    /**
     * @return array<string, array<int, string>|string>
     */
    protected function paletteForPanel(): array
    {
        $c = config('exylia-theme.colors');

        return [
            'primary' => Color::hex('#' . ltrim($c['primary'], '#')),
            'secondary' => Color::hex('#' . ltrim($c['secondary'], '#')),
            'success' => Color::hex('#' . ltrim($c['success'], '#')),
            'warning' => Color::hex('#' . ltrim($c['warning'], '#')),
            'danger' => Color::hex('#' . ltrim($c['danger'], '#')),
            'info' => Color::hex('#' . ltrim($c['info'], '#')),
            'gray' => Color::Zinc,
        ];
    }

    /**
     * CSS custom properties injected on <html> so the compiled theme.css and
     * the render-hook background layer can react to admin settings at runtime.
     *
     * @return array<string, string>
     */
    protected function cssVariables(): array
    {
        $c = config('exylia-theme.colors');
        $e = config('exylia-theme.effects');

        $intensity = max(0, min(100, (int) ($e['glow_intensity'] ?? 55)));

        return [
            '--exylia-primary' => '#' . ltrim($c['primary'], '#'),
            '--exylia-secondary' => '#' . ltrim($c['secondary'], '#'),
            '--exylia-secondary-light' => '#' . ltrim($c['secondary_light'], '#'),
            '--exylia-success' => '#' . ltrim($c['success'], '#'),
            '--exylia-warning' => '#' . ltrim($c['warning'], '#'),
            '--exylia-danger' => '#' . ltrim($c['danger'], '#'),
            '--exylia-info' => '#' . ltrim($c['info'], '#'),
            '--exylia-glow' => ($e['glow'] ?? true) ? (string) ($intensity / 100) : '0',
            '--exylia-glow-intensity' => (string) $intensity,
        ];
    }

    protected function bodyClasses(): string
    {
        $e = config('exylia-theme.effects');

        $classes = ['exylia-theme'];

        if (! ($e['animations'] ?? true)) {
            $classes[] = 'exylia-no-motion';
        }
        if ($e['glow'] ?? true) {
            $classes[] = 'exylia-glow';
        }
        if ($e['custom_scrollbar'] ?? true) {
            $classes[] = 'exylia-scrollbar';
        }
        if ($e['starfield'] ?? true) {
            $classes[] = 'exylia-starfield';
        }

        return implode(' ', $classes);
    }
}
