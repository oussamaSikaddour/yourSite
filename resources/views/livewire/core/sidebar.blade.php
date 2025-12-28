<aside class="sidebar">
    <ul id="mainMenu" role="menu" aria-labelledby="menubutton" class="sidebar__items">

        @can('super-admin-access')
            <x-core.side-bar.dropdown :items="$superAdminDropdownItems" :dropdownLink="$superAdminDropdownLink" />
        @endcan
        @canany(['admin-access', 'author-access'])
            <x-core.side-bar.dropdown :items="$adminDropdownItems" :dropdownLink="$adminDropdownLink" />

            @if (isset($this->services) && count($this->services))
                @php
                    $dropdownInfo = $this->getRoutesOfServiceForAuthor();
                @endphp
                <x-core.side-bar.dropdown :items="$dropdownInfo['routes']" :dropdownLink="$dropdownInfo['dropdownName']" />
            @endif
        @endcanany
    </ul>
</aside>
