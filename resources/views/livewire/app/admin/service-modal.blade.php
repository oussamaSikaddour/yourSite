      <div class="modal__body__content">
          <div class="form__container">
              <form class="form">

                  <div class="row center">
                      <x-core.form.input model="{{ $form }}.name_fr" :label="__('forms.service.name_fr')" type="text"
                          html_id="MSer-nfr" />
                      <x-core.form.input model="{{ $form }}.name_en" :label="__('forms.service.name_en')" type="text"
                          html_id="MSer-nEn" />
                      <x-core.form.input model="{{ $form }}.name_ar" :label="__('forms.service.name_ar')" type="text"
                          html_id="MSer-nAr" />

                  </div>
                  <div class="row center">
                      <x-core.form.selector htmlId="MSer-Sep" model="{{ $form }}.specialty_id" :label="__('forms.service.specialty')"
                          :data="$serviceSpecialtiesOptions" :showError="true" />

                      <x-core.form.selector htmlId="MSer-ui" model="{{ $form }}.head_of_service_id"
                          :label="__('forms.service.head_of_service_id')" :data="$headOfServiceOptions" :showError="true" />
                  </div>
                  <div class="row ">
                      <x-core.form.input model="{{ $form }}.email" :label="__('forms.service.email')" type="email"
                          html_id="MService-Email" />
                      <x-core.form.input model="{{ $form }}.tel" :label="__('forms.service.tel')" type="text"
                          html_id="MService-Ph" />
                      <x-core.form.input model="{{ $form }}.fax" :label="__('forms.service.fax')" type="text"
                          html_id="MEService-fax" />

                  </div>
                  <div class="row center ">
                      <x-core.form.input model="{{ $form }}.specialists_number" :label="__('forms.service.specialists_number')"
                          type="number" min="1" html_id="MService-SN" />
                      <x-core.form.input model="{{ $form }}.physicians_number" :label="__('forms.service.physicians_number')" type="text"
                          min="1" html_id="MService-PN" />
                  </div>
                  <div class="row center ">
                      <x-core.form.input model="{{ $form }}.paramedics_number" :label="__('forms.service.paramedics_number')"
                          type="number" min="1" html_id="MService-Email" />
                      <x-core.form.input model="{{ $form }}.beds_number" :label="__('forms.service.beds_number')" type="number"
                          min="1" html_id="MService-BN" />
                  </div>


                  <x-core.form.textarea model="{{ $form }}.introduction_fr" :label="__('forms.service.introduction_fr')"
                      html_id="FSIFr" />
                  <x-core.form.textarea model="{{ $form }}.introduction_ar" :label="__('forms.service.introduction_ar')"
                      html_id="FSIAr" />
                  <x-core.form.textarea model="{{ $form }}.introduction_en" :label="__('forms.service.introduction_en')"
                      html_id="FSIEn" />


                  <div class="column center" x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true"
                      x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-cancel="uploading = false"
                      x-on:livewire-upload-error="uploading = false"
                      x-on:livewire-upload-progress="progress = $event.detail.progress">

                      <x-core.file-input
                      model="{{ $form }}.icon"
                      types="img"
                      type="image"
                        :fileUri="$temporaryImageUrl"
                          :tooltip="__('forms.service.icon')" />
                      <div x-show="uploading" class="upload__progress">
                          <progress max="100" x-bind:value="progress"></progress>
                      </div>
                  </div>
                  <div class="form__actions">
                      <x-core.button
                      type="submit"
                       variant="primary"
                        :text="__('forms.common.actions.submit')"
                        icon="confirm"
                          expectLoading=true
                          fullWidth=true
                          function="handleSubmit"
                          :wireTargets="['handleSubmit']"
                           prevent="true" />
                  </div>
              </form>
          </div>
      </div>
