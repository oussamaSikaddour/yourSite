      <div class="modal__body__content">
          <div class="form__container">
              <form class="form" wire:submit="handleSubmit">

                  <div class="row center">
                      <x-core.form.input model="{{ $form }}.designation_fr" :label="__('forms.field.designation_fr')" type="text"
                          html_id="MF-bfr" />
                      <x-core.form.input model="{{ $form }}.designation_ar" :label="__('forms.field.designation_ar')" type="text"
                          html_id="MF-bAr" />
                      <x-core.form.input model="{{ $form }}.designation_en" :label="__('forms.field.designation_en')" type="text"
                          html_id="MF-bEN" />
                  </div>
                  <div class="row center">
                      <x-core.form.input model="{{ $form }}.acronym" :label="__('forms.field.acronym')" type="text"
                          html_id="MF-ac" />
                  </div>
                  <div class="form__actions">
                      <x-core.button type="submit" variant="primary" :text="__('forms.common.actions.submit')" icon="confirm"
                          expectLoading=true fullWidth=true />
                  </div>
              </form>
          </div>
      </div>
