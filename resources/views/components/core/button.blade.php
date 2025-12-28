@php
    // ------------------------
    // CSS Classes
    // ------------------------
    $classes = 'button' . ($variant ? " button--{$variant}" : '');
    $classes .= $rounded ? ' rounded' : '';
    $classes .= $fullWidth ? ' w-full' : '';
    $classes .= $hasTooltip ? ' hasTooltip' : '';
    $classes .= $disabled ? ' disabled' : '';

    // Add extra classes (array or string)
    if (!empty($extraClasses)) {
        $classes .= ' ' . (is_array($extraClasses) ? implode(' ', $extraClasses) : $extraClasses);
    }

    // wire:target
    $wireTarget = !empty($wireTargets) ? 'wire:target=' . implode(',', $wireTargets) : '';

    // Modes
    $isLinkHref = !empty($href);
    $isLinkRoute = !empty($routeUrl);
@endphp

{{-- ============================= --}}
{{-- CASE 1: <a href="...">         --}}
{{-- ============================= --}}
@if ($isLinkHref)
    <a id="{{ $htmlId }}" href="{{ $href }}" class="{{ $classes }}" {{ $attributes }}
        @if ($newTab) target="_blank" rel="noopener" @endif
        @if ($disabled) aria-disabled="true" tabindex="-1" @endif>

        @if ($expectLoading)
            <span wire:loading {{ $wireTarget }}>
                <x-core.loading variant="xs" />
            </span>
        @endif

        @if ($rounded)
            {!! $iconHtml !!}
        @else
            {{ $text }} {!! $iconHtml !!}
        @endif

        @if ($hasTooltip)
            <span class="tooltip__content">{{ $tooltip }}</span>
        @endif
    </a>

    {{-- ============================= --}}
    {{-- CASE 2: <a route="...">        --}}
    {{-- ============================= --}}
@elseif($isLinkRoute)
    <a id="{{ $htmlId }}" href="{{ $routeUrl }}" class="{{ $classes }}" {{ $attributes }}
        @if ($newTab) target="_blank" rel="noopener" @endif
        @if ($disabled) aria-disabled="true" tabindex="-1" @endif>

        @if ($expectLoading)
            <span wire:loading {{ $wireTarget }}>
                <x-core.loading variant="xs" />
            </span>
        @endif

        @if ($rounded)
            {!! $iconHtml !!}
        @else
            {{ $text }} {!! $iconHtml !!}
        @endif

        @if ($hasTooltip)
            <span class="tooltip__content">{{ $tooltip }}</span>
        @endif
    </a>

    {{-- ============================= --}}
    {{-- CASE 3: <button>               --}}
    {{-- ============================= --}}
@else
    <button id="{{ $htmlId }}" type="{{ $type }}" class="{{ $classes }}" {{ $attributes }}
        @if ($function) @if ($prevent)
            wire:click.prevent="{{ $function }}(
                {{ implode(',', $parameters) }}
            )"
        @else
            wire:click="{{ $function }}(
                {{ implode(',', $parameters) }}
            )" @endif
        @endif

        @if ($disabled) disabled @endif
        @if ($expectLoading) wire:loading.attr="disabled" @endif
        >

        @if ($expectLoading)
            <span wire:loading {{ $wireTarget }}>
                <x-core.loading variant="xs" />
            </span>
        @endif

        @if ($rounded)
            {!! $iconHtml !!}
        @else
            {{ $text }} {!! $iconHtml !!}
        @endif

        @if ($hasTooltip)
            <span class="tooltip__content">{{ $tooltip }}</span>
        @endif
    </button>
@endif
