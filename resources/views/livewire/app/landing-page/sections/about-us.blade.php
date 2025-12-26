      <section class="about__us" id="aboutUs">
        <div class="section__title">
          <h2> @lang('pages.landing_page.sections.about_us.title')</h2>
          <p>
           {{ $aboutUs->sub_title }}
          </p>
        </div>

        <div class="about__us__content">
          <div class="about__us__img__container">
            <div class="about__us__info">
              <h2 class="counter">{{ $years }}</h2>
              <p>@lang('pages.landing_page.sections.about_us.years')</p>
            </div>
            <div class="about__us__info">
              <h2 class="counter">{{ $this->services->total_services ?? 10 }}</h2>
               <p>@lang('pages.landing_page.sections.about_us.services')</p>
            </div>
            <div class="about__us__info">
              <h2 class="counter">{{ $this->services->total_beds ?? 250 }}</h2>
               <p>@lang('pages.landing_page.sections.about_us.beds')</p>
            </div>

            <div class="about__us__img">
              <img
              src="{{asset('assets/app/images/AboutUs/ehs.jpg') }}"
              alt="aboutUs" />
            </div>
          </div>
          <div class="about__us__body">
            <p>
             {{ $aboutUs->first_paragraph }}
            </p>
            <p>
             {{ $aboutUs->second_paragraph }}
            </p>
            <p>
             {{ $aboutUs->third_paragraph }}
            </p>
          </div>
        </div>
      </section>
