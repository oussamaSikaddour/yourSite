<li role="presentation" class="sidebar__item {{ $activeClass }}">
    <a role="menuitem" href="{{ $routeUrl }}" tabindex="0">
        <!-- Route display name -->
        {{ $routeName }}

        <!-- Icon display -->
        @if ($iconHtml)
            <span class="sidebar__icon">
                {!! $iconHtml !!}
            </span>
        @endif

        <!-- Badge display (if provided) -->
        @if ($badge)
            <x-core.badge :badge="$badge" class="{{ $badgeClass }}" />
        @endif
    </a>
</li>
