      <div class="modal__body__content">
          <div class="form__container">
              <form class="form" wire:submit="handleSubmit">

                  <div class="row center">
                      <x-core.form.input model="{{ $form }}.title_fr" :label="__('forms.menu.title_fr')" type="text"
                          html_id="MT-tFr" />
                      <x-core.form.input model="{{ $form }}.title_ar" :label="__('forms.menu.title_ar')" type="text"
                          html_id="MT-tAr" />
                      <x-core.form.input model="{{ $form }}.title_en" :label="__('forms.menu.title_en')" type="text"
                          html_id="MT-tEn" />


                  </div>

                  <div class="form__actions">
                      <x-core.button type="submit" variant="primary" :text="__('forms.common.actions.submit')" icon="confirm"
                          expectLoading=true fullWidth=true />
                  </div>
              </form>
          </div>
      </div>
