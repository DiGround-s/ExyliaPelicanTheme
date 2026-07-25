# Exylia Theme

A premium, minimalist **galactic** theme for [Pelican Panel](https://pelican.dev).
Controlled violet gradients, soft glow, subtle starfield, custom scrollbar,
purposeful motion, and full **dark + light** modes — all configurable from
`Admin > Plugins > Exylia Theme > Settings` with no rebuild.

## Palette

| Role | Hex |
|---|---|
| Primary | `#8a51c4` |
| Secondary | `#aa76de` |
| Secondary light | `#b48fd9` |

Success `#8fffc1` · Warning `#ffc58f` · Danger `#a33b53` · Info `#59a4ff`.

## Install

> **Important:** this plugin ships with `meta.status: "not_installed"`.
> Importing it (by file or by URL) only extracts the files — it does **not**
> compile assets or activate the theme. You must run the install step below
> before the theme is enabled. Enabling a theme plugin without first running
> `p:plugin:install` will crash the panel with a "Unable to locate file in
> Vite manifest" 500 error, because the CSS/JS entries won't exist in the
> panel's Vite manifest yet.

**1. Import** (any of these):
- Admin UI: **Plugins > Import > URL**, paste the release asset link:
  `https://github.com/DiGround-s/ExyliaPelicanTheme/releases/download/v1.0.0/exylia-theme.zip`
- Or copy this folder into the panel's persistent `plugins/` volume as
  `plugins/exylia-theme/`.

**2. Install (compiles assets + enables the theme):**
```bash
php artisan p:plugin:install exylia-theme
```
Or from the Admin UI: on the Plugins list, the newly imported theme shows a
**"Install"** action (visible while its status is "Not Installed") — click it.

This triggers the panel's `yarn install && yarn build`. The panel's own
`vite.config.js` globs `plugins/*/resources/css/**/*.css`, so
`resources/css/theme.css` (and `resources/js/theme.js`) are picked up and
compiled into the panel's Vite manifest — no build tooling ships in this
plugin.

When importing by ZIP file directly, the archive filename must match the
plugin id (`exylia-theme.zip`), because the importer locates
`exylia-theme/plugin.json` inside it. Do **not** use GitHub's "Source code
(zip)" link — use the release asset link above.

## What it does

- **Colors / branding / font** applied via the Filament Plugin API on `register()`.
- **Runtime CSS variables + body classes** injected via `PanelsRenderHook::HEAD_END`
  so admin settings (palette, glow intensity, motion, starfield, scrollbar)
  change the look instantly.
- **Atmosphere layer** injected via `PanelsRenderHook::BODY_START`
  (`resources/views/components/atmosphere.blade.php`).
- **Accessibility:** on-brand focus rings, `prefers-reduced-motion` respected,
  and an explicit "disable motion" toggle.

## Theme Preview page

Installs a **Theme Preview** page on the **admin** panel
(`Appearance > Theme Preview`, slug `admin/exylia-theme/preview`). It's a living
gallery — palette swatches, buttons, badges, inputs, stat cards and feedback
states — so you can verify the look at a glance without touring the whole admin.

## Settings

| Setting | Env | Default |
|---|---|---|
| Brand name | `EXYLIA_BRAND_NAME` | panel default |
| Brand logo | `EXYLIA_BRAND_LOGO` | panel default |
| Primary / Secondary / Secondary light | `EXYLIA_COLOR_*` | Exylia palette |
| Background glow | `EXYLIA_EFFECT_GLOW` | `true` |
| Glow intensity | `EXYLIA_EFFECT_GLOW_INTENSITY` | `55` |
| Motion | `EXYLIA_EFFECT_ANIMATIONS` | `true` |
| Starfield | `EXYLIA_EFFECT_STARFIELD` | `true` |
| Custom scrollbar | `EXYLIA_EFFECT_SCROLLBAR` | `true` |

## Structure

```
plugins/exylia-theme/
├── plugin.json
├── config/exylia-theme.php
├── src/
│   ├── ExyliaThemePlugin.php
│   ├── Pages/ThemePreview.php
│   ├── Concerns/{BuildsExyliaPalette,InteractsWithExyliaSettings}.php
│   └── Providers/ExyliaThemeServiceProvider.php
├── resources/
│   ├── css/theme.css
│   ├── js/theme.js
│   └── views/
│       ├── components/atmosphere.blade.php
│       └── pages/preview.blade.php
└── lang/en/theme.php
```
