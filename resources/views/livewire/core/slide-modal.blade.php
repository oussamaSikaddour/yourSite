<div class="modal__body__content">
    <div class="form__container">
        <form class="form" >
            <!-- Titles Section -->
            <div class="row center">
                <x-core.form.input model="{{ $form }}.title_fr" :label="__('forms.slide.title_fr')" type="text"
                    html_id="MSlide-aFr" />
                <x-core.form.input model="{{ $form }}.title_ar" :label="__('forms.slide.title_ar')" type="text"
                    html_id="MSlide-aFr" />
                <x-core.form.input model="{{ $form }}.title_en" :label="__('forms.slide.title_en')" type="text"
                    html_id="MSlide-aEn" />
            </div>

            <!-- Order Selector (Conditional) -->
            @if (count($this->orders()) > 0)
                <div class="row">
                    <x-core.form.selector htmlId="MsSlideOr" model="{{ $form }}.order" :label="__('forms.slide.order')"
                        :data="$orderOptions" :showError="true" type="filter" />
                </div>
            @endif


          <x-core.form.textarea
          model="{{ $form }}.content_fr"
          :label="__('forms.slide.content_fr')"
          html_id="SliderCFr" />
          <x-core.form.textarea
          model="{{ $form }}.content_ar"
          :label="__('forms.slide.content_ar')"
          html_id="SliderCAr" />
          <x-core.form.textarea
           model="{{ $form }}.content_en"
           :label="__('forms.slide.content_en')"
            html_id="SliderCEn" />


            <!-- File Upload Section -->
            <div class="column center" x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true"
                x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-cancel="uploading = false"
                x-on:livewire-upload-error="uploading = false"
                x-on:livewire-upload-progress="progress = $event.detail.progress">


                <x-core.file-input model="{{ $form }}.image" types="img" type="image" :fileUri="$temporaryImageUrl" />
                <!-- Upload Progress -->
                <div x-show="uploading">
                    <progress max="100" x-bind:value="progress"></progress>
                </div>
            </div>



            <!-- Form Actions -->
            <div class="form__actions">
        <x-core.button function="handleSubmit" :wireTargets="['handleSubmit']" prevent="true" variant="primary"
                          :text="__('forms.common.actions.submit')" icon="confirm" expectLoading=true fullWidth=true />
            </div>
        </form>
    </div>
</div>
