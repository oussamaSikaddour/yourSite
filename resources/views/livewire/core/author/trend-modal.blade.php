      <div class="modal__body__content">
          <div class="form__container">
              <form class="form" wire:submit="handleSubmit">

                  <div class="row center">
                      <x-core.form.input model="{{ $form }}.title_fr" :label="__('forms.trend.title_fr')" type="text"
                          html_id="mTrend-aFr" />
                      <x-core.form.input model="{{ $form }}.title_ar" :label="__('forms.trend.title_ar')" type="text"
                          html_id="mTrend-aFr" />
                      <x-core.form.input model="{{ $form }}.title_en" :label="__('forms.trend.title_en')" type="text"
                          html_id="mTrend-aEn" />
                  </div>

                  <div class="column">

                      <p>@lang('forms.trend.content_fr') :</p>
                      <livewire:core.tiny-mce-text-area htmlId="MTrendCttfr" contentUpdatedEvent="set-content-fr"
                          wire:key="MaContentFr" :content="$contentFr" />
                      <p>@lang('forms.trend.content_ar') :</p>
                      <livewire:core.tiny-mce-text-area htmlId="MTrendCttAr" contentUpdatedEvent="set-content-ar"
                          wire:key="MaContentAr" :content="$contentAr" />
                      <p>@lang('forms.trend.content_en') :</p>
                      <livewire:core.tiny-mce-text-area htmlId="MTrendCttEn" contentUpdatedEvent="set-content-en"
                          wire:key="MaContentEn" :content="$contentEn" />
                  </div>

                  <div class="row center">
                      <x-core.form.input model="{{ $form }}.start_at" :label="__('forms.trend.start_at')" type="date"
                          html_id="mTrend-SAt" />
                      <x-core.form.input model="{{ $form }}.end_at" :label="__('forms.trend.end_at')" type="date"
                          html_id="mTrend-EAt" />
                  </div>
                  <div class="form__actions">
                      <x-core.button type="submit" variant="primary" :text="__('forms.common.actions.submit')" icon="confirm"
                          expectLoading=true fullWidth=true />
                  </div>
              </form>
          </div>
      </div>
