      <div class="modal__body__content">
          <div class="form__container">
              <form class="form" wire:submit="handleSubmit">

                  <div class="row center">
                      <x-core.form.input model="{{ $form }}.amount" :label="__('forms.transfer.amount')" type="money"
                          html_id="Mtb-amount" />

                      @if ($form === 'addForm')
                          <x-core.form.selector htmlId="Mtb-employee" model="{{ $form }}.user_id"
                              :label="__('forms.transfer.user_id')" :data="$employeeOptions" :showError="true" />
                      @endif

                  </div>
                  <div class="form__actions">
                      <x-core.button type="submit" variant="primary" :text="__('forms.common.actions.submit')" icon="confirm"
                          expectLoading=true fullWidth=true />
                  </div>
              </form>
          </div>
      </div>
