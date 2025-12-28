      <div class="service__details__container">

          @if (count($slides) > 0)
              <div class="service__details__carousel">
                  <livewire:core.carousel :$slides />
              </div>
          @endif
          <div class="service__details">
              <h4 class="service__details__title">@lang('cards.service_details.head_of_service')</h4>

              <div class="service__head">

                  <div class="service__head__img">
                      <img src="{{ $headService->user->avatar->url ?? 'assets/core/images/persons/person-1.png' }}"
                          alt="Head of service" />
                  </div>


                  <div class="service__head__info">
                      <h4 class="service__head__name">{{ $headService->full_name }}</h4>
                      <p class="service__head__role">
                          {{ $headService->occupations[0]?->grade?->localized_designation ?? 'Head Service' }}</p>
                  </div>
              </div>
              <h4 class="service__details__title">@lang('cards.service_details.overview')</h4>
              <ol class="service__details__stats">
                  <li class="service__details__stat">
                      <span class="service__details__label">@lang('cards.service_details.beds')</span>
                      <span class="service__details__value counter">{{ $service->beds_number }}</span>
                  </li>

                  <li class="service__details__stat">
                      <span class="service__details__label">@lang('cards.service_details.specialists')</span>
                      <span class="service__details__value counter">{{ $service->specialists_number }}</span>
                  </li>

                  <li class="service__details__stat">
                      <span class="service__details__label">@lang('cards.service_details.physicians')</span>
                      <span class="service__details__value counter">{{ $service->physicians_number }}</span>
                  </li>

                  <li class="service__details__stat">
                      <span class="service__details__label">@lang('cards.service_details.paramedics')</span>
                      <span class="service__details__value counter">{{ $service->paramedics_number }}</span>
                  </li>
                  <li class="service__details__stat">
                      <span class="service__details__label">
                          <i class="fa-solid fa-phone"></i></span>
                      <span class="service__details__value counter">{{ $service->landline }}</span>
                  </li>
                  <li class="service__details__stat">
                      <span class="service__details__label">
                          <i class="fa-solid fa-fax"></i>
                      </span>
                      <span class="service__details__value counter">{{ $service->fax }}</span>
                  </li>
                  <li class="service__details__stat">
                      <span class="service__details__label">
                          <i class="fa-regular fa-envelope"></i>
                      </span>
                      <span class="service__details__value counter">{{ $service->email }}</span>
                  </li>
              </ol>


          </div>
      </div>
