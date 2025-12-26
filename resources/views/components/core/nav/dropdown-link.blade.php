<li class="nav__item nav__item--dropDown">
    <div
        id="{{ $htmlId }}"
        tabindex="0"
        class="nav__btn nav__btn--dropdown"
        aria-expanded="false"
        aria-controls="subItems"
        aria-label="Show user menu"
        aria-labelledby="{{ $htmlId }}"
    >
        {!! $dropdownLink !!} <!-- Render the dropdown trigger HTML -->
    </div>
    <ul
        id="{{ $htmlId }}-subItems"
        class="nav__items--sub"
        role="menu"
    >
        {!! $renderedItems !!} <!-- Render the dropdown items HTML -->
        {{ $slot }} <!-- Render additional items passed via the slot -->
    </ul>
</li>
