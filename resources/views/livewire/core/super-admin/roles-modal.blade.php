      <div class="modal__body__content">
          <div class="form__container">
              <form class="form" wire:submit.prevent="handleSubmit">
                  <div>
                      @if (isset($this->existingRoles) && $this->existingRoles->isNotEmpty())
                          <div class="checkbox__group">
                              <h2 id="checkbox-choices" class="sr-only">
                                  list Des Choix
                              </h2>
                              <div class="choices" role="groupe" aria-labelledby="checkbox-choices">
                                  @foreach ($this->existingRoles as $ER)
                                      <x-core.form.check-box model="form.roles" value="{{ $ER->id }}"
                                          label="{{ config('core.utils.ROLES')[app()->getLocale()][$ER->slug] }}"
                                          htmlId="role-m-${{ $ER->id }}" />
                                  @endforeach
                                  @error('form.roles')
                                      <div class="input__error">
                                          {{ $message }}
                                      </div>
                                  @enderror
                              </div>
                          </div>
                      @endif
                  </div>


                  <div class="form__actions">
                      <x-core.button type="submit" variant="primary" :text="__('forms.common.actions.submit')" icon="confirm"
                          expectLoading=true fullWidth=true />
                  </div>
              </form>

          </div>
      </div>
