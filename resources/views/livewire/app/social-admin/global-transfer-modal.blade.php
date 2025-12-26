      <div class="modal__body__content">
          <div class="form__container">
              <form class="form" wire:submit="handleSubmit">

                  <div class="row center">
                      <x-core.form.input model="{{ $form }}.number" :label="__('forms.global_transfer.number')" type="number"
                          html_id="MGBT-numb" />
                      <x-core.form.input model="{{ $form }}.date" :label="__('forms.global_transfer.date')" type="date"
                          html_id="MGBT-date" />
                  </div>

                  <div class="row center">
                      <x-core.form.input model="{{ $form }}.motive_fr" :label="__('forms.global_transfer.motive_fr')" type="text"
                          html_id="MGBT-mFr" />
                      <x-core.form.input model="{{ $form }}.motive_ar" :label="__('forms.global_transfer.motive_ar')" type="text"
                          html_id="MGBT-mAr" />
                      <x-core.form.input model="{{ $form }}.motive_en" :label="__('forms.global_transfer.motive_en')" type="text"
                          html_id="MGBT-mEn" />
                  </div>

                  <div class="form__actions">
                      <x-core.button type="submit" variant="primary" :text="__('forms.common.actions.submit')" icon="confirm"
                          expectLoading=true fullWidth=true />
                  </div>
              </form>
          </div>
      </div>
