      <div class="modal__body__content">
          <div class="form__container">
              <form class="form">

                  <div class="row center">
                      <x-core.form.input model="{{ $form }}.name_fr" :label="__('forms.our_quality.name_fr')" type="text"
                          html_id="Moq-nfr" />
                      <x-core.form.input model="{{ $form }}.name_ar" :label="__('forms.our_quality.name_ar')" type="text"
                          html_id="Moq-nAr" />
                      <x-core.form.input model="{{ $form }}.name_en" :label="__('forms.our_quality.name_en')" type="text"
                          html_id="Moq-nEN" />
                  </div>

                  <div class="column center" x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true"
                      x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-cancel="uploading = false"
                      x-on:livewire-upload-error="uploading = false"
                      x-on:livewire-upload-progress="progress = $event.detail.progress">


                      <x-core.file-input model="{{ $form }}.image" types="img" type="image"
                          :fileUri="$temporaryImageUrl" />
                      <div x-show="uploading">
                          <progress max="100" x-bind:value="progress"></progress>


                      </div>

                  </div>


                  <div class="form__actions">
                      <x-core.button function="handleSubmit" :wireTargets="['handleSubmit']" prevent="true" variant="primary"
                          :text="__('forms.common.actions.submit')" icon="confirm" expectLoading=true fullWidth=true />
                  </div>
              </form>
          </div>
      </div>
