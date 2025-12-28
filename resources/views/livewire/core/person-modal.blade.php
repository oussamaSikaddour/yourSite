      <div class="modal__body__content">
          <div class="form__container">
              <form class="form" wire:submit="handleSubmit">
                  <div class="row">
                      <div class="column">
                          <!-- Names -->
                          <div class="row">
                              <x-core.form.input model="{{ $form }}.person.first_name_fr" :label="__('forms.person.first_name_fr')"
                                  type="text" html_id="PM-FNfr" />
                              <x-core.form.input model="{{ $form }}.person.last_name_fr" :label="__('forms.person.last_name_fr')"
                                  type="text" html_id="PM-LNfr" />
                          </div>
                          <div class="row">
                              <x-core.form.input model="{{ $form }}.person.first_name_ar" :label="__('forms.person.first_name_ar')"
                                  type="text" html_id="PM-FNar" />
                              <x-core.form.input model="{{ $form }}.person.last_name_ar" :label="__('forms.person.last_name_ar')"
                                  type="text" html_id="PM-LNar" />
                          </div>

                          <!-- Employee / Social Number -->
                          <div class="row">
                              <x-core.form.input model="{{ $form }}.person.employee_number" :label="__('forms.person.employee_number')"
                                  type="text" html_id="PM-EmN" />
                              <x-core.form.input model="{{ $form }}.person.social_number" :label="__('forms.person.social_number')"
                                  type="text" html_id="PM-SOCnUM" />
                          </div>

                          <!-- Email -->


                          @if ($form == 'addForm')
                              <div class="row">
                                  <x-core.form.input model="{{ $form }}.default.email" :label="__('forms.person.email')"
                                      type="email" html_id="PM-E" />
                              </div>
                          @endif
                      </div>


                        <x-core.file-input   model="{{ $form }}.image"  types="img" type="avatar"  type="avatar" :fileUri="$temporaryImageUrl"/>
                  </div>

                  <!-- Contact & Card Info -->
                  <div class="row">
                      <x-core.form.input model="{{ $form }}.person.phone" :label="__('forms.person.tel')" type="text"
                          html_id="PM-T" />
                      <x-core.form.input model="{{ $form }}.person.card_number" :label="__('forms.person.card_number')"
                          type="text" html_id="PM-CN" />
                      <x-core.form.input model="{{ $form }}.person.birth_date" :label="__('forms.person.birth_date')"
                          type="date" html_id="PM-BD" />
                  </div>

                  <!-- Birth Places -->
                  <div class="row">
                      <x-core.form.input model="{{ $form }}.person.birth_place_fr" :label="__('forms.person.birth_place_fr')"
                          type="text" html_id="PM-BP-fr" />
                      <x-core.form.input model="{{ $form }}.person.birth_place_ar" :label="__('forms.person.birth_place_ar')"
                          type="text" html_id="PM-BP-ar" />
                      <x-core.form.input model="{{ $form }}.person.birth_place_en" :label="__('forms.person.birth_place_en')"
                          type="text" html_id="PM-BP-en" />
                  </div>

                  <!-- Addresses -->
                  <div class="row">
                      <x-core.form.input model="{{ $form }}.person.address_fr" :label="__('forms.person.address_fr')"
                          type="text" html_id="PM-AD-fr" />
                      <x-core.form.input model="{{ $form }}.person.address_ar" :label="__('forms.person.address_ar')"
                          type="text" html_id="PM-AD-ar" />
                      <x-core.form.input model="{{ $form }}.person.address_en" :label="__('forms.person.address_en')"
                          type="text" html_id="PM-AD-en" />
                  </div>


                  <div class="row center">

                      @if ($form == 'updateForm')
                          <div class="checkbox__group">
                              <div class="choices" role="group" aria-labelledby="checkbox-choices">
                                  <x-core.form.check-box model="isPaidCheckBoxValue"
                                      value="{{ !$isPaidCheckBoxValue }}" :label="__('forms.person.is_paid')" htmlId="PM-iSEM"
                                      :live=true />
                              </div>
                          </div>

                          @can('super-admin-access')
                              <div class="checkbox__group">
                                  <div class="choices" role="group" aria-labelledby="checkbox-choices">
                                      <x-core.form.check-box model="isActiveCheckBoxValue"
                                          value="{{ !$isActiveCheckBoxValue }}" :label="__('forms.person.is_active')" htmlId="PM-isActive"
                                          :live=true />
                                  </div>
                              </div>
                          @endcan
                      @endif
                  </div>

                  <!-- Form Actions -->
                  <div class="form__actions">

                      <x-core.button type="submit" variant="primary" :text="__('forms.common.actions.submit')" icon="confirm"
                          expectLoading=true fullWidth=true />

                  </div>
              </form>
          </div>
      </div>
