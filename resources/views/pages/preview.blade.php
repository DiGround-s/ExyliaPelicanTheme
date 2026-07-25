<x-filament-panels::page>
    {{-- Exylia theme preview: a living gallery to verify the look at a glance. --}}

    @php
        $swatches = [
            'Primary' => '#' . ltrim($colors['primary'], '#'),
            'Secondary' => '#' . ltrim($colors['secondary'], '#'),
            'Secondary light' => '#' . ltrim($colors['secondary_light'], '#'),
            'Success' => '#' . ltrim($colors['success'], '#'),
            'Warning' => '#' . ltrim($colors['warning'], '#'),
            'Danger' => '#' . ltrim($colors['danger'], '#'),
            'Info' => '#' . ltrim($colors['info'], '#'),
        ];
    @endphp

    {{-- Hero --}}
    <section class="exylia-preview-hero">
        <div class="exylia-preview-hero__glow"></div>
        <div class="exylia-preview-hero__content">
            <p class="exylia-preview-eyebrow">Exylia Theme</p>
            <h2 class="exylia-preview-title">Galactic. Minimalist. Premium.</h2>
            <p class="exylia-preview-lead">
                Controlled violet gradients, soft glow and purposeful motion.
                Font in use: <strong>{{ $font }}</strong>.
            </p>
        </div>
    </section>

    {{-- Palette --}}
    <x-filament::section>
        <x-slot name="heading">Palette</x-slot>
        <x-slot name="description">Semantic roles derived from the Exylia guide.</x-slot>

        <div class="exylia-swatches">
            @foreach ($swatches as $name => $hex)
                <div class="exylia-swatch">
                    <span class="exylia-swatch__chip" style="background: {{ $hex }}"></span>
                    <span class="exylia-swatch__name">{{ $name }}</span>
                    <span class="exylia-swatch__hex">{{ $hex }}</span>
                </div>
            @endforeach
        </div>
    </x-filament::section>

    {{-- Buttons --}}
    <x-filament::section>
        <x-slot name="heading">Buttons</x-slot>
        <x-slot name="description">Brand gradient, glow and pressed states.</x-slot>

        <div class="exylia-row">
            <x-filament::button>Primary</x-filament::button>
            <x-filament::button color="gray">Neutral</x-filament::button>
            <x-filament::button color="success">Success</x-filament::button>
            <x-filament::button color="danger">Danger</x-filament::button>
            <x-filament::button icon="tabler-rocket">With icon</x-filament::button>
            <x-filament::button :disabled="true">Disabled</x-filament::button>
        </div>
        <div class="exylia-row" style="margin-top:.75rem">
            <x-filament::button outlined>Outlined</x-filament::button>
            <x-filament::button size="sm">Small</x-filament::button>
            <x-filament::button size="lg">Large</x-filament::button>
        </div>
    </x-filament::section>

    {{-- Badges --}}
    <x-filament::section>
        <x-slot name="heading">Badges</x-slot>

        <div class="exylia-row">
            <x-filament::badge>Default</x-filament::badge>
            <x-filament::badge color="success">Online</x-filament::badge>
            <x-filament::badge color="warning">Suspended</x-filament::badge>
            <x-filament::badge color="danger">Offline</x-filament::badge>
            <x-filament::badge color="info">Info</x-filament::badge>
        </div>
    </x-filament::section>

    {{-- Inputs --}}
    <x-filament::section>
        <x-slot name="heading">Inputs</x-slot>
        <x-slot name="description">Focus rings and hover states are on-brand.</x-slot>

        <div class="exylia-grid-2">
            <x-filament::input.wrapper>
                <x-filament::input type="text" placeholder="Server name" />
            </x-filament::input.wrapper>

            <x-filament::input.wrapper>
                <x-filament::input type="email" placeholder="you@exylia.dev" />
            </x-filament::input.wrapper>

            <x-filament::input.wrapper prefix="https://">
                <x-filament::input type="text" placeholder="panel.example.com" />
            </x-filament::input.wrapper>

            <x-filament::input.wrapper>
                <x-filament::input.select>
                    <option>Choose a region</option>
                    <option>EU — Frankfurt</option>
                    <option>US — Dallas</option>
                    <option>Asia — Singapore</option>
                </x-filament::input.select>
            </x-filament::input.wrapper>
        </div>
    </x-filament::section>

    {{-- Cards / stats --}}
    <div class="exylia-grid-3">
        <x-filament::section>
            <x-slot name="heading">CPU</x-slot>
            <p class="exylia-stat">42<span>%</span></p>
            <p class="exylia-stat-sub">2 vCores · nominal</p>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Memory</x-slot>
            <p class="exylia-stat">3.1<span>GB</span></p>
            <p class="exylia-stat-sub">of 8 GB allocated</p>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Uptime</x-slot>
            <p class="exylia-stat">99.9<span>%</span></p>
            <p class="exylia-stat-sub">last 30 days</p>
        </x-filament::section>
    </div>

    {{-- Feedback states --}}
    <x-filament::section>
        <x-slot name="heading">Feedback</x-slot>

        <div class="exylia-callouts">
            <div class="exylia-callout exylia-callout--success">Server started successfully.</div>
            <div class="exylia-callout exylia-callout--warning">Backup is running low on space.</div>
            <div class="exylia-callout exylia-callout--danger">Failed to connect to the daemon.</div>
            <div class="exylia-callout exylia-callout--info">A new panel version is available.</div>
        </div>
    </x-filament::section>
</x-filament-panels::page>
