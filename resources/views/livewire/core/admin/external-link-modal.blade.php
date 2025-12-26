      <div class="modal__body__content">
          <div class="form__container">
              <form class="form" wire:submit="handleSubmit">

                  <div class="row center">
                      <x-core.form.input model="{{ $form }}.name_fr" :label="__('forms.menu.name_fr')" type="text"
                          html_id="ELM-NaFr" />
                      <x-core.form.input model="{{ $form }}.name_ar" :label="__('forms.menu.name_ar')" type="text"
                          html_id="ELM-NaAr" />
                      <x-core.form.input model="{{ $form }}.name_en" :label="__('forms.menu.name_en')" type="text"
                          html_id="ELM-NaEn" />
                  </div>
                  <div class="row center">
                      <x-core.form.input model="{{ $form }}.url" :label="__('forms.menu.url')" type="text"
                          html_id="ELM-Url" />
                  </div>
                  <div class="form__actions">
                      <x-core.button type="submit" variant="primary" :text="__('forms.common.actions.submit')" icon="confirm"
                          expectLoading=true fullWidth=true />
                  </div>
              </form>
          </div>
      </div>
