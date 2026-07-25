@php
    $links = collect($this->getLinks())->filter(fn ($link) => filled($link['url']));
@endphp

@if ($links->isNotEmpty())
    <div class="exylia-quick-access">
        @foreach ($links as $link)
            <a href="{{ $link['url'] }}" wire:navigate class="exylia-quick-access__item">
                <x-filament::icon :icon="$link['icon']" class="exylia-quick-access__icon" />
                <span>{{ $link['label'] }}</span>
            </a>
        @endforeach
    </div>
@endif
