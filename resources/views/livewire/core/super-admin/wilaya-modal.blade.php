      <div class="modal__body__content">
          <div class="form__container">
              <form class="form" wire:submit="handleSubmit">

                  <div class="row center">
                      <x-core.form.input model="{{ $form }}.designation_fr" :label="__('forms.wilaya.designation_fr')" type="text"
                          html_id="MW-bfr" />
                      <x-core.form.input model="{{ $form }}.designation_ar" :label="__('forms.wilaya.designation_ar')" type="text"
                          html_id="MW-bAr" />
                      <x-core.form.input model="{{ $form }}.designation_en" :label="__('forms.wilaya.designation_en')" type="text"
                          html_id="MW-bEN" />
                  </div>
                  <div class="row center">
                      <x-core.form.input model="{{ $form }}.code" :label="__('forms.wilaya.code')" type="text"
                          html_id="MW-ac" />
                  </div>


                  <div class="form__actions">
                      <x-core.button type="submit" variant="primary" :text="__('forms.common.actions.submit')" icon="confirm"
                          expectLoading=true fullWidth=true />
                  </div>
              </form>
          </div>
      </div>
