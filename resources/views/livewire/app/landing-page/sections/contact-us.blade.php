      <section class="contact__us" id="contactUs">
          <div class="contact__us__content">
              <div class="contact__us__map">
                  @settings('map')
                      {{-- This will render the setting if it exists --}}

              </div>


              <div class="contact__us__card">
                  <div class="column">
                      <div class="row">
                          <div class="contact__us__coordinate">
                              <div class="contact__us__coordinate__icon">
                                  <i class="fa-solid fa-phone"></i>
                              </div>
                              <div class="contact__us__coordinate__texts">
                                  <h4>@lang('pages.landing_page.sections.contact_us.phone')</h4>
                                  <p>@settings('landline')  </p>
                              </div>
                          </div>
                          <div class="contact__us__coordinate">
                              <div class="contact__us__coordinate__icon">
                                  <i class="fa-solid fa-fax"></i>
                              </div>
                              <div class="contact__us__coordinate__texts">
                                  <h4>@lang('pages.landing_page.sections.contact_us.fax')</h4>
                                  <p>@settings('fax')</p>
                              </div>
                          </div>
                      </div>
                      <div class="row">
                          <div class="contact__us__coordinate">
                              <div class="contact__us__coordinate__icon">
                                  <i class="fa-regular fa-envelope"></i>
                              </div>
                              <div class="contact__us__coordinate__texts">
                                  <h4>@lang('pages.landing_page.sections.contact_us.email')</h4>
                                  <p>@settings('email')</p>
                              </div>
                          </div>
                          <div class="contact__us__coordinate">
                              <div class="contact__us__coordinate__icon">
                                  <i class="fa-solid fa-location-dot"></i>
                              </div>
                              <div class="contact__us__coordinate__texts">
                                  <h4>@lang('pages.landing_page.sections.contact_us.location')</h4>
                                  <p>@settings('address')</p>
                              </div>
                          </div>
                      </div>
                  </div>

                  <div class="contact__us__instruction">
                      <h3>@lang('pages.landing_page.sections.contact_us.title')</h3>
                      <p>
                      @lang('pages.landing_page.sections.contact_us.sub_title')
                      </p>
                  </div>
                  <div class="form__container" inert>
                    <livewire:app.guest.message-modal />
                  </div>
              </div>
          </div>
      </section>
