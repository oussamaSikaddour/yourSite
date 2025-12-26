<section class="services" id="services">
    <div class="section__title">
        <h2>@lang('pages.landing_page.sections.services.title')</h2>
        <p>
            @lang('pages.landing_page.sections.services.sub_title')
        </p>
    </div>



    @if (isset($services) && $services->isNotEmpty())
        <div class="services__container">
            @foreach ($services as $service)
                <livewire:app.cards.service
                :service="$service"
                 wire:key="serviceCard-{{ $service->name }}"

                />
            @endforeach
        </div>
    @else
        <div class="services__container">
            <div class="service__card">
                <a href="service.html" class="service__link">
                    <i class="fa-solid fa-arrow-right"></i>
                </a>

                <div class="service__icon">
                    <img src="{{ asset('assets/app/icons/medical/gyenco.png') }}" alt="Gyneco" />
                </div>

                <div class="service__body">
                    <h2 class="service__title">Gynecology and Obstetrics</h2>
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorem
                        in, esse, nostrum obcaecati porro modi eveniet earum quidem
                        ipsam deserunt est accusamus alias, debitis ullam voluptatem
                        autem adipisci dolor quibusdam.
                    </p>
                </div>
                <div class="service__footer">
                    <strong class="counter">60</strong> Bed
                </div>
            </div>
            <div class="service__card">
                <a href="service.html" class="service__link">
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
                <div class="service__icon">
                    <img src="{{ asset('assets/app/icons/medical/churgie.png') }}" alt="Gyneco" />
                </div>

                <div class="service__body">
                    <h2 class="service__title">pediatric surgery</h2>
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorem
                        in, esse, nostrum obcaecati porro modi eveniet earum quidem
                        ipsam deserunt est accusamus alias, debitis ullam voluptatem
                        autem adipisci dolor quibusdam.
                    </p>
                </div>
                <div class="service__footer">
                    <strong class="counter">80</strong> Bed
                </div>
            </div>
            <div class="service__card">
                <a href="service.html" class="service__link">
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
                <div class="service__icon">
                    <img src="{{ asset('assets/app/icons/medical/pediatrie.png') }}" alt="Gyneco" />
                </div>

                <div class="service__body">
                    <h2 class="service__title">pediatrics</h2>
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorem
                        in, esse, nostrum obcaecati porro modi eveniet earum quidem
                        ipsam deserunt est accusamus alias, debitis ullam voluptatem
                        autem adipisci dolor quibusdam.
                    </p>
                </div>
                <div class="service__footer">
                    <strong class="counter">55</strong> Bed
                </div>
            </div>
            <div class="service__card">
                <a href="service.html" class="service__link">
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
                <div class="service__icon">
                    <img src="{{ asset('assets/app/icons/medical/neoNat.png') }}" alt="Gyneco" />
                </div>

                <div class="service__body">
                    <h2 class="service__title">Neonatology</h2>
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorem
                        in, esse, nostrum obcaecati porro modi eveniet earum quidem
                        ipsam deserunt est accusamus alias, debitis ullam voluptatem
                        autem adipisci dolor quibusdam.
                    </p>
                </div>
                <div class="service__footer">
                    <strong class="counter">52</strong> Bed
                </div>
            </div>
            <div class="service__card">
                <a href="service.html" class="service__link">
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
                <div class="service__icon">
                    <img src="{{ asset('assets/app/icons/medical/ria.png') }}" alt="Gyneco" />
                </div>

                <div class="service__body">
                    <h2 class="service__title">ANESTHESIA AND RESUSCITATION</h2>
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorem
                        in, esse, nostrum obcaecati porro modi eveniet earum quidem
                        ipsam deserunt est accusamus alias, debitis ullam voluptatem
                        autem adipisci dolor quibusdam.
                    </p>
                </div>
                <div class="service__footer">
                    <strong class="counter">52</strong> Bed
                </div>
            </div>
        </div>

    @endif

    <x-core.button variant="action" icon="forward" route="services_public"  :text="__('pages.services_public.name')" />
</section>
