<div class="form__container ">
    <form class="form">

        <div class="row center">
            <x-core.form.input model="form.sub_title_fr" :label="__('forms.manage_about_us.sub_title_fr')" type="text" html_id="FAU-tf" />
            <x-core.form.input model="form.sub_title_ar" :label="__('forms.manage_about_us.sub_title_ar')" type="text" html_id="FAU-ta" />
            <x-core.form.input model="form.sub_title_en" :label="__('forms.manage_about_us.sub_title_en')" type="text" html_id="FAU-te" />
        </div>


        <div class="column">
            <x-core.form.textarea model="form.first_paragraph_fr" :label="__('forms.manage_about_us.first_paragraph_fr')" html_id="FPFr" />
            <x-core.form.textarea model="form.first_paragraph_ar" :label="__('forms.manage_about_us.first_paragraph_ar')" html_id="FPAr" />
            <x-core.form.textarea model="form.first_paragraph_en" :label="__('forms.manage_about_us.first_paragraph_en')" html_id="FPEn" />
        </div>
        <div class="column">
            <x-core.form.textarea model="form.second_paragraph_fr" :label="__('forms.manage_about_us.second_paragraph_fr')" html_id="SPFr" />
            <x-core.form.textarea model="form.second_paragraph_ar" :label="__('forms.manage_about_us.second_paragraph_ar')" html_id="SPAr" />
            <x-core.form.textarea model="form.second_paragraph_en" :label="__('forms.manage_about_us.second_paragraph_en')" html_id="SPEn" />
        </div>
        <div class="column">
            <x-core.form.textarea model="form.third_paragraph_fr" :label="__('forms.manage_about_us.third_paragraph_fr')" html_id="TPFr" />
            <x-core.form.textarea model="form.third_paragraph_ar" :label="__('forms.manage_about_us.third_paragraph_ar')" html_id="TPAr" />
            <x-core.form.textarea model="form.third_paragraph_en" :label="__('forms.manage_about_us.third_paragraph_en')" html_id="tPEn" />
        </div>

        <div class="column center" x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true"
            x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-cancel="uploading = false"
            x-on:livewire-upload-error="uploading = false"
            x-on:livewire-upload-progress="progress = $event.detail.progress">

            <x-core.file-input model="form.image" types="img" type="image" :fileUri="$temporaryImageUrl" />
            <div x-show="uploading">
                <progress max="100" x-bind:value="progress"></progress>
            </div>
        </div>

        <div class="form__actions">
            <x-core.button function="handleSubmit" :wireTargets="['handleSubmit']" prevent="true" variant="primary" :text="__('forms.common.actions.submit')"
                icon="confirm" expectLoading=true fullWidth=true />
        </div>
    </form>
</div>
