          <div class="service__card">
              <a href="{{ route('servicePublic', ['id' => $service->id, 'formServicesPublic' => $formServicesPublic]) }}"
                  class="service__link">
                  <i class="fa-solid fa-arrow-right"></i>
              </a>


              <div class="service__icon">
                  <img src="{{ $service->icon->url ?? asset('assets/app/icons/medical/gyenco.png') }}"
                      alt="{{ $service->icon->display_name ?? 'Image' }}" />
              </div>

              <div class="service__body">
                  <h2 class="service__title">{{ $service->name ?? 'Service Title' }}</h2>
                  <p>


                      {{ $service->introduction ?: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Eos voluptatum explicabo facilis alias architecto necessitatibus enim tempore veniam obcaecati. Libero aperiam quaerat ipsa quisquam beatae autem vitae, inventore atque iusto' }}

                  </p>
              </div>
              <div class="service__footer">
                  <strong class="counter">{{ $service->beds_number }}</strong> @lang('pages.landing_page.sections.about_us.beds')
              </div>
          </div>
