
@if (!$forLandingPage)
<nav class="nav--phone" aria-labelledby="main-nav-phone">
    <h2 id="main-nav-phone" class="sr-only">
        Main navigation
    </h2>
    <ol class="nav__items">
        @auth

            <x-core.nav.link route="index" :label="__('pages.landing_page.name')" />
            <x-core.nav.link route="dashboard" :label="__('pages.dashboard.name')" />




            <livewire:core.notifications-button wire:key="nb-phone" />
            <livewire:core.nav.user-button wire:key="unb-phone" />

        @endauth
        @guest



            <x-core.nav.link route="index" :label="__('pages.landing_page.name')" />
            @if (isset($this->menus) && count($this->menus))
                @foreach ($this->menus as $menu)
                    @php
                        $dropdownInfo = $this->getRoutesOfMenu($menu);
                    @endphp
                    <livewire:core.nav-drop-down :data="$dropdownInfo['routes']" :dropdownName="$dropdownInfo['dropdownName']" :icon="$dropdownInfo['icon']" />
                @endforeach
            @endif

            <x-core.nav.link route="login" :label="__('pages.login.name')" />
            <x-core.nav.link route="register" :label="__('pages.register.name')" />
        @endguest

    </ol>
         <livewire:core.lang-menu wire:key="lmp" />
</nav>


@else
<nav class="nav--phone" aria-labelledby="main-nav-phone">
    <h2 id="main-nav-phone" class="sr-only">
        Main navigation
    </h2>
    <ol class="nav__items">
        @auth

            <x-core.nav.link route="#hero" :label="__('pages.landing_page.links.hero')" />
            <x-core.nav.link route="#aboutUs" :label="__('pages.landing_page.links.about_us')" />
            <x-core.nav.link route="#services" :label="__('pages.landing_page.links.services')" />
            <x-core.nav.link route="#contactUs" :label="__('pages.landing_page.links.contact_us')" />
            <x-core.nav.link route="dashboard" :label="__('pages.dashboard.name')" />

       <livewire:core.nav.user-button wire:key="unb-phone" />

        @endauth
        @guest

            <x-core.nav.link route="#hero" :label="__('pages.landing_page.links.hero')" />
            <x-core.nav.link route="#aboutUs" :label="__('pages.landing_page.links.about_us')" />
            <x-core.nav.link route="#services" :label="__('pages.landing_page.links.services')" />
            <x-core.nav.link route="#contactUs" :label="__('pages.landing_page.links.contact_us')" />

            @if (isset($this->menus) && count($this->menus))
                @foreach ($this->menus as $menu)
                    @php
                        $dropdownInfo = $this->getRoutesOfMenu($menu);
                    @endphp
                    <livewire:core.nav-drop-down :data="$dropdownInfo['routes']" :dropdownName="$dropdownInfo['dropdownName']" :icon="$dropdownInfo['icon']" />
                @endforeach
            @endif

            <x-core.nav.link route="login" :label="__('pages.login.name')" />
            <x-core.nav.link route="register" :label="__('pages.register.name')" />
        @endguest

    </ol>
         <livewire:core.lang-menu wire:key="lmp" />
</nav>


@endif
