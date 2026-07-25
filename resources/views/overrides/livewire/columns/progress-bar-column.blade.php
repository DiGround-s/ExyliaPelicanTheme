{{--
    Exylia override of livewire.columns.progress-bar-column, used by both the
    classic table's ProgressBarColumn and our exylia-server-card grid include
    (see livewire/server-entry.blade.php). Resolved before the panel's own
    view via ExyliaThemePlugin::boot() -> View::prependLocation(). The
    upstream file lives at resources/views/livewire/columns/progress-bar-column.blade.php
    and is left untouched on disk.

    All color/threshold resolution below is copied verbatim from upstream —
    only the track/fill markup and classes were swapped for the Exylia glow
    treatment. Do not rename any of the injected $get* closures; they're
    passed in by the calling column/include.
--}}
@php
    $currentValue = $getState();
    $maxValue = $getMaxValue();
    $status = $getProgressStatus();
    $percentage = $getProgressPercentage();
    $label = $getProgressLabel();
    $color = $getProgressColor();
    $resolved = \App\Filament\Components\Tables\Columns\ProgressBarColumn::resolveColor($color);
    $color = $resolved ?? (is_string($color) ? $color : 'gray');
    $colorStr = is_string($color) ? $color : 'gray';
    $isRgb = str_starts_with($colorStr, 'rgb(');

    if ($isRgb) {
        $lightBackgroundColor = str_replace('rgb(', 'rgba(', rtrim($colorStr, ')') . ', 0.15)');
    } else {
        $lightBackgroundColor = "color-mix(in srgb, {$colorStr} 15%, transparent)";
    }

    $isDanger = $status === 'danger';

    $lighterColor = $colorStr;
    $animClass = null;

    if ($isDanger) {
        $lighterColor = "color-mix(in srgb, {$colorStr} 50%, #ffffff)";
        $animClass = 'exylia-danger-pulse-' . substr(md5($colorStr), 0, 8);
    }
@endphp

<div @class(['exylia-progress'])>
    @if($isDanger && $animClass)
        <style>
            @keyframes {{ $animClass }} {
                0% { color: {{ $colorStr }}; }
                50% { color: {{ $lighterColor }}; }
                100% { color: {{ $colorStr }}; }
            }

            .{{ $animClass }} {
                animation: {{ $animClass }} 1s ease-in-out infinite;
            }
        </style>
    @endif

    <div class="exylia-progress__track"
         style="background-color: {{ $lightBackgroundColor }};"
         role="progressbar"
         aria-valuenow="{{ $currentValue }}"
         aria-valuemin="0"
         aria-valuemax="{{ $maxValue ?? 100 }}"
         aria-label="{{ $label }}"
    >
        <div class="exylia-progress__fill"
             data-status="{{ $status }}"
             style="width: {{ $percentage }}%; background-color: {{ $colorStr }};"
        ></div>
    </div>

    <span
        @class([
            'exylia-progress__label',
            $animClass => $isDanger && $animClass,
        ])
        @if($isDanger)
            role="status"
            aria-live="assertive"
            style="color: {{ $colorStr }};"
        @else
            style="color: unset;"
        @endif
    >
        {{ $label }}
    </span>
</div>
