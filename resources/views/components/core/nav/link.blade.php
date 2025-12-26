<li class="nav__item {{ $active }}">
    <a
        class="nav__link"
        href="{{ $routeUrl }}"
        @if ($active) aria-current="page" @endif
    >
        {{ $label }}

        @if ($span)
            <span>{!! $span !!}</span>
        @endif
    </a>
</li>
