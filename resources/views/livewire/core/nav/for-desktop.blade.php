@if (!$forLandingPage)

    <header class="header">
        <nav class="nav" aria-labelledby="main-nav">
            <h2 id="main-nav" class="sr-only">
                Main navigation
            </h2>

            @auth
                <div class="nav__addons">
                    @canany(['admin-access', 'super-admin-access', 'social-admin-access'])
                        <x-core.side-bar.open-btn html_id="mainMenuDeskTopBtn" />
                    @endcanany
                    <livewire:core.nav-logo />
                </div>

                <ol class="nav__items">
                    <x-core.nav.link route="dashboard" :label="__('pages.dashboard.name')" />
                </ol>

                <ol class="nav__items">
                    <livewire:core.notifications-button wire:key="nb-desktop" />
                    <livewire:core.nav.user-button wire:key="unb-desktop" />
                </ol>
            @endauth

            @guest

                <div class="nav__addons">
                    <livewire:core.nav-logo />
                </div>
                <ol class="nav__items">
                    <x-core.nav.link route="LOGIN" :label="__('pages.login.name')" />
                    <x-core.nav.link route="REGISTER" :label="__('pages.register.name')" />

                    @if (isset($this->menus) && count($this->menus))
                        @foreach ($this->menus as $menu)
                            @php
                                $dropdownInfo = $this->getRoutesOfMenu($menu);
                            @endphp
                            <livewire:core.nav-drop-down :data="$dropdownInfo['routes']" :dropdownName="$dropdownInfo['dropdownName']" :icon="$dropdownInfo['icon']" />
                        @endforeach
                    @endif
                </ol>
            @endguest

            <livewire:core.lang-menu wire:key="lang-menu-desktop" />
        </nav>
    </header>
@else
    <header class="header">
        <nav class="nav" aria-labelledby="main-nav">
            <h2 id="main-nav" class="sr-only">
                Main navigation
            </h2>

            @auth
                <div class="nav__addons">
                    @canany(['admin-access', 'super-admin-access', 'social-admin-access'])
                        <x-core.side-bar.open-btn html_id="mainMenuDeskTopBtn" />
                    @endcanany
                    <livewire:core.nav-logo />
                </div>

                <ol class="nav__items">
                    <x-core.nav.link route="#hero" :label="__('pages.landing_page.links.hero')" />
                    <x-core.nav.link route="#aboutUs" :label="__('pages.landing_page.links.about_us')" />
                    <x-core.nav.link route="#services" :label="__('pages.landing_page.links.services')" />
                    <x-core.nav.link route="#contactUs" :label="__('pages.landing_page.links.contact_us')" />

                    <x-core.nav.link route="dashboard" :label="__('pages.dashboard.name')" />
                </ol>



                <ol class="nav__items">
                    <livewire:core.nav.user-button wire:key="unb-desktop" />
                </ol>
            @endauth

            @guest

                <div class="nav__addons">
                    <livewire:core.nav-logo />
                </div>
                <ol class="nav__items">
                    <x-core.nav.link route="#hero" :label="__('pages.landing_page.links.hero')" />
                    <x-core.nav.link route="#aboutUs" :label="__('pages.landing_page.links.about_us')" />
                    <x-core.nav.link route="#services" :label="__('pages.landing_page.links.services')" />
                    <x-core.nav.link route="#contactUs" :label="__('pages.landing_page.links.contact_us')" />
                </ol>
                <ol class="nav__items">
                    <x-core.nav.link route="LOGIN" :label="__('pages.login.name')" />
                    <x-core.nav.link route="REGISTER" :label="__('pages.register.name')" />

                    @if (isset($this->menus) && count($this->menus))
                        @foreach ($this->menus as $menu)
                            @php
                                $dropdownInfo = $this->getRoutesOfMenu($menu);
                            @endphp
                            <livewire:core.nav-drop-down :data="$dropdownInfo['routes']" :dropdownName="$dropdownInfo['dropdownName']" :icon="$dropdownInfo['icon']" />
                        @endforeach
                    @endif
                </ol>
            @endguest

            <livewire:core.lang-menu wire:key="lang-menu-desktop" />
        </nav>
    </header>


@endif
