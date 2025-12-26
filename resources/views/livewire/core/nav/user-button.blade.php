
 <x-core.nav.dropdown-link
        x-on:update-nav-user-btn.window="$wire.refreshUserInfo()"
        :items="$routes"
        :dropdownLink="$userDropdownLink"
    />
