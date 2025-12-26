    <footer class="footer">
        <div class="footer__top">
            <div class="footer__top__right">
                <h4>@lang('pages.landing_page.sections.services.title')</h4>
                <div class="footer__links">
                    @foreach ($this->services as $column)
                        <div class="column">
                            @foreach ($column as $service)
                                <a href="{{ route('servicePublic', ['id' => $service->id]) }}">
                                    <span>&#8250;</span> {{ $service->name }}
                                </a>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                <x-core.button variant="action" icon="forward" route="services_public" :text="__('pages.services_public.name')" />
            </div>
            <div class="footer__top__left">
                <h4>@lang('pages.landing_page.sections.contact_us.title')</h4>
                <div class="footer__coordinates">
                    <div class="footer__coordinate">
                        <div class="footer__coordinate__icon">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <p>@settings('address')</p>
                    </div>
                    <div class="footer__coordinate">
                        <div class="footer__coordinate__icon">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <p>@settings('email')</p>
                    </div>
                    <div class="footer__coordinate">
                        <div class="footer__coordinate__icon">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <p>@settings('phone')</p>
                    </div>
                </div>
                <ul class="socials">
                    <li>
                        <a href="@settings('facebook'))"> <i class="fa-brands fa-facebook"></i></a>
                    </li>
                    <li>
                        <a href="@setting('github')"> <i class="fa-brands fa-github"></i></a>
                    </li>
                    <li>
                        <a href="@setting('twitter')"> <i class="fa-brands fa-twitter"></i></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="footer__bottom">
            <div class="footer__bottom__right">
                <a href="index.html"><img class="logo" src="assets/app/images/logo.png" /></a>
                <p class="text-light">
                    &#169;@lang('pages.landing_page.sections.footer.copyright') @settings('app_name') @lang('pages.landing_page.sections.footer.reservation')
                </p>
            </div>
            <div class="footer__bottom__left">
                <div class="row">
                    <a href="#"> @lang('pages.landing_page.sections.footer.links.privacy_policy')</a>
                    <a href="#">@lang('pages.landing_page.sections.footer.links.terms_of_service')</a>
                    <a href="#">@lang('pages.landing_page.sections.footer.links.cookie_policy')</a>
                </div>
                <h4>@lang('pages.landing_page.sections.footer.designed_by') <a href="#">Oussama Si kaddour</a></h4>
            </div>
        </div>
    </footer>
