@props(['href', 'label', 'span' => null])


<li class="nav__item " role="none">
    <a class="nav__link"
       href="{{ $href }}"
    >
        {{ $label }}
        @if($span)
            {!! $span !!}
        @endif
    </a>
</li>
