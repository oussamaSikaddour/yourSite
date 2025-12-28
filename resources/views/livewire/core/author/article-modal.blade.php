      <div class="modal__body__content">
          <div class="form__container">
              <form class="form">

                  <div class="row center">
                      <x-core.form.input model="{{ $form }}.title_fr" :label="__('forms.article.title_fr')" type="text"
                          html_id="MArticle-aFr" />
                      <x-core.form.input model="{{ $form }}.title_ar" :label="__('forms.article.title_ar')" type="text"
                          html_id="MArticle-aFr" />
                      <x-core.form.input model="{{ $form }}.title_en" :label="__('forms.article.title_en')" type="text"
                          html_id="MArticle-aEn" />
                  </div>

                  <div class="column">

                      <p>@lang('forms.article.content_fr') :</p>
                      <livewire:core.tiny-mce-text-area htmlId="MAContentfr" contentUpdatedEvent="set-content-fr"
                          wire:key="MaContentFr" :content="$contentFr" />
                      <p>@lang('forms.article.content_ar') :</p>
                      <livewire:core.tiny-mce-text-area htmlId="MAContentAr" contentUpdatedEvent="set-content-ar"
                          wire:key="MaContentAr" :content="$contentAr" />
                      <p>@lang('forms.article.content_en') :</p>
                      <livewire:core.tiny-mce-text-area htmlId="MAContentEn" contentUpdatedEvent="set-content-en"
                          wire:key="MaContentEn" :content="$contentEn" />
                  </div>

                  <div class="column center" x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true"
                      x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-cancel="uploading = false"
                      x-on:livewire-upload-error="uploading = false"
                      x-on:livewire-upload-progress="progress = $event.detail.progress">

                      <x-core.file-input model="{{ $form }}.images" types="img" multiple=true />

                      <div x-show="uploading">
                          <progress max="100" x-bind:value="progress"></progress>
                      </div>

                  </div>
                  @if (is_array($temporaryImageUrls) && !empty($temporaryImageUrls))
                      <div class="imgs__container">
                          <div class="imgs">
                              @foreach ($temporaryImageUrls as $url)
                                  <img class="img" src="{{ $url }}"
                                      alt="{{ __('forms.manage_hero.images') }}" />
                              @endforeach
                          </div>
                      </div>
                  @endif

                  <div class="row center">
                      <x-core.form.input model="{{ $form }}.published_at" :label="__('forms.article.published_at')" type="date"
                          html_id="MaPat" />

                  </div>

                  <div class="form__actions">
                      <x-core.button function="handleSubmit" :wireTargets="['handleSubmit']" prevent="true" variant="primary"
                          :text="__('forms.common.actions.submit')" icon="confirm" expectLoading=true fullWidth=true />
                  </div>
              </form>
          </div>
      </div>
