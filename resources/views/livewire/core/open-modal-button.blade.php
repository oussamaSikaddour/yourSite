
@php
    // ------------------------
    // CSS Classes
    // ------------------------
    $classes = 'button modal__opener' . ($variant ? " button--{$variant}" : '');
    $classes .= $rounded ? ' rounded' : '';
    $classes .= $fullWidth ? ' w-full' : '';
    $classes .= $hasTooltip ? ' hasTooltip' : '';

    // Add extra classes (array or string)
    if (!empty($extraClasses)) {
        $classes .= ' ' . (is_array($extraClasses) ? implode(' ', $extraClasses) : $extraClasses);
    }


@endphp

{{-- ============================= --}}
{{-- CASE 1: <a href="...">         --}}
{{-- ============================= --}}

    <button id="{{ $htmlId }}"  class="{{ $classes }}"

    wire:click="fillModal"
    >

        @if ($rounded)
            {!! $iconHtml !!}
        @else
            {{ $text }} {!! $iconHtml !!}
        @endif

        @if ($hasTooltip)
            <span class="tooltip__content">{{ $tooltip }}</span>
        @endif
    </button>
