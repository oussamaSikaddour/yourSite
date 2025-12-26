<li class="sidebar__item sidebar__item--dropdown">
    <div id="{{ $htmlId }}"
         tabindex="0"
         class="sidebar__btn sidebar__btn--dropdown"
         aria-expanded="false"
         aria-controls="{{ $subItemsId }}"
         aria-label="Show user menu">

            {!! $dropdownLink !!}

            @if ($shouldShowMainBadge())
                <span class="{{ $mainBadgeClass() }}">{{ $badge }}</span>
            @endif

            <span class="caret">
                <i class="fa-solid fa-caret-down"></i>
            </span>

    </div>

    <ul id="{{ $subItemsId }}" class="sidebar__items--sub" role="menu">
        {!! $renderedItems !!}
        {{ $slot }}
    </ul>
</li>
