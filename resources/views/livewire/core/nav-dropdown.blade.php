
    <x-core.nav.dropdown-link
        x-on:update-nav-dropdown-btn.window="$wire.refreshDropDownInfo()"
        :items="$routes"
        :$dropdownLink
    />
