@php $facts = $this->getFacts(); @endphp

@if (!empty($facts))
    <div class="exylia-pulse">
        @foreach ($facts as $fact)
            <div class="exylia-pulse__item">
                <x-filament::icon :icon="$fact['icon']" class="exylia-pulse__icon" />
                <div class="exylia-pulse__text">
                    <span class="exylia-pulse__label">{{ $fact['label'] }}</span>
                    <span class="exylia-pulse__value" title="{{ $fact['value'] }}">{{ $fact['value'] }}</span>
                </div>
            </div>
        @endforeach
    </div>
@endif
