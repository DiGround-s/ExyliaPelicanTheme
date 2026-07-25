<?php

/*
|--------------------------------------------------------------------------
| Exylia Theme configuration
|--------------------------------------------------------------------------
|
| Loaded automatically by the panel as config('exylia-theme.*').
| Values here are the built-in defaults. Anything the admin edits through
| Admin > Plugins > Exylia Theme > Settings is persisted to the .env file
| and overrides these defaults via env() below.
|
| Palette source: Exylia style guide.
|   Primary          #8a51c4
|   Secondary        #aa76de
|   Secondary light  #b48fd9
|
*/

return [

    // ---------------------------------------------------------------------
    // Branding
    // ---------------------------------------------------------------------
    'brand' => [
        'name' => env('EXYLIA_BRAND_NAME', null), // null = keep panel default
        'logo' => env('EXYLIA_BRAND_LOGO', null), // absolute URL or asset path
        'logo_height' => env('EXYLIA_BRAND_LOGO_HEIGHT', '2rem'),
    ],

    // ---------------------------------------------------------------------
    // Colors — Exylia galactic palette (hex, no leading #)
    // ---------------------------------------------------------------------
    'colors' => [
        'primary' => env('EXYLIA_COLOR_PRIMARY', '8a51c4'),
        'secondary' => env('EXYLIA_COLOR_SECONDARY', 'aa76de'),
        'secondary_light' => env('EXYLIA_COLOR_SECONDARY_LIGHT', 'b48fd9'),

        'success' => env('EXYLIA_COLOR_SUCCESS', '8fffc1'),
        'warning' => env('EXYLIA_COLOR_WARNING', 'ffc58f'),
        'danger' => env('EXYLIA_COLOR_DANGER', 'a33b53'),
        'info' => env('EXYLIA_COLOR_INFO', '59a4ff'),
    ],

    // ---------------------------------------------------------------------
    // Atmosphere & motion toggles
    // ---------------------------------------------------------------------
    'effects' => [
        // Galactic background glow behind the panel content.
        'glow' => (bool) env('EXYLIA_EFFECT_GLOW', true),
        // 0..100 — intensity of the glow / gradients.
        'glow_intensity' => (int) env('EXYLIA_EFFECT_GLOW_INTENSITY', 55),
        // Purposeful microinteractions & transitions.
        'animations' => (bool) env('EXYLIA_EFFECT_ANIMATIONS', true),
        // Custom galactic scrollbar.
        'custom_scrollbar' => (bool) env('EXYLIA_EFFECT_SCROLLBAR', true),
        // Subtle starfield in the background layer.
        'starfield' => (bool) env('EXYLIA_EFFECT_STARFIELD', true),
    ],

    // ---------------------------------------------------------------------
    // Typography
    // ---------------------------------------------------------------------
    'font' => env('EXYLIA_FONT', 'Inter'),
];
