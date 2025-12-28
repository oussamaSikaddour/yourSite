                      <form class="form" wire:submit="handleSubmit">
                          <div class="row">
                              <x-core.form.input model="form.name" :label="__('forms.guest.message.name-placeholder')" type="text" html_id="fGMN" />
                              <x-core.form.input model="form.email" :label="__('forms.guest.message.email-placeholder')" type="email" html_id="fGME" />

                          </div>


                          <x-core.form.textarea model="form.message" :label="__('forms.guest.message.message-placeholder')" html_id="FGMM" />
                          <div class="row">
                              <x-core.button type="submit" variant="action" :text="__('forms.guest.message.sent')" icon="forward"
                                  expectLoading=true />
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
