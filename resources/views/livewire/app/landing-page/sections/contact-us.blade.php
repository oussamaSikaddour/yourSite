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
                      <form class="form" method="POST">
                          <div class="row">
                              <div class="input__item">
                                  <div class="input__group">
                                      <input type="text" class="input" placeholder="Your Name" name="name"
                                          id="inputNameId" required />
                                      <label for="inputNameId" class="input__label">Your Name</label>
                                  </div>
                              </div>
                              <div class="input__item">
                                  <div class="input__group">
                                      <input type="text" class="input" placeholder="Your email" name="name"
                                          id="inputEmailId" required />
                                      <label for="inputEmailId" class="input__label">Your email</label>
                                  </div>
                              </div>
                          </div>

                          <div class="textarea__group">
                              <textarea class="textarea" id="observations" name="observations" rows="4" placeholder=" " maxlength="200"></textarea>
                              <label for="observations" class="textarea__label">Your Message</label>
                          </div>
                          <div class="row">
                              <button class="button button--action" type="submit">
                                  Send Message

                                  <i class="fa-solid fa-arrow-right-long arrow"></i>
                                  <div class="loader xs">
                                      <div class="loader__circle"></div>
                                      <div class="loader__circle"></div>
                                  </div>
                              </button>
                              <ul class="socials">
                                  <li>
                                      <a href="@settings('facebook')"> <i class="fa-brands fa-facebook"></i></a>
                                  </li>
                                  <li>
                                      <a href="@setting('github')"> <i class="fa-brands fa-github"></i></a>
                                  </li>
                                  <li>
                                      <a href="@setting('twitter')"> <i class="fa-brands fa-twitter"></i></a>
                                  </li>
                              </ul>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
      </section>
