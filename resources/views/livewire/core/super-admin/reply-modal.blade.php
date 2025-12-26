      <div class="modal__body__content">
          <div class="form__container">
              <form class="form" wire:submit="handleSubmit">
                  <div class="row">
                      <p>{{ $message['message'] }}</p>
                  </div>

                  <div class="column">


                      <livewire:core.tiny-mce-text-area htmlId="rM-m" contentUpdatedEvent="set-message-content"
                          wire:key="rM-m" :content="$messageContent" />
                  </div>
                  <div class="form__actions">

                      <div class="form__actions">
                          <x-core.button type="submit" variant="primary" :text="__('forms.common.actions.submit')" icon="confirm"
                              expectLoading=true fullWidth=true />
                      </div>
              </form>
          </div>
