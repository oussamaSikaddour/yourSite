<div class="form__container">
    <form class="form">


        <div class="row center ">
            <x-core.form.input model="form.title_en" :label="__('forms.manage_hero.title_en')" type="text" html_id="FH-te" />
            <x-core.form.input model="form.title_fr" :label="__('forms.manage_hero.title_fr')" type="text" html_id="FH-tf" />
            <x-core.form.input model="form.title_ar" :label="__('forms.manage_hero.title_ar')" type="text" html_id="FH-ta" />

        </div>
        <div class="row  center">
            <x-core.form.input model="form.sub_title_en" :label="__('forms.manage_hero.sub_title_en')" type="text" html_id="FH-ste" />
            <x-core.form.input model="form.sub_title_fr" :label="__('forms.manage_hero.sub_title_fr')" type="text" html_id="FH-stf" />
            <x-core.form.input model="form.sub_title_ar" :label="__('forms.manage_hero.sub_title_ar')" type="text" html_id="FH-sta" />

        </div>
        <div class="row  center">
            <x-core.form.input model="form.primary_call_to_action_fr" :label="__('forms.manage_hero.primary_call_to_action_fr')" type="text"
                html_id="FH-PCtaFr" />
            <x-core.form.input model="form.primary_call_to_action_ar" :label="__('forms.manage_hero.primary_call_to_action_ar')" type="text"
                html_id="FH-PCtaAr" />
            <x-core.form.input model="form.primary_call_to_action_en" :label="__('forms.manage_hero.primary_call_to_action_en')" type="text"
                html_id="FH-PCtaEn" />
        </div>
        <div class="row  center">
            <x-core.form.input model="form.secondary_call_to_action_fr" :label="__('forms.manage_hero.secondary_call_to_action_fr')" type="text"
                html_id="FH-SCtaFr" />
            <x-core.form.input model="form.secondary_call_to_action_ar" :label="__('forms.manage_hero.secondary_call_to_action_ar')" type="text"
                html_id="FH-SCtaAr" />
            <x-core.form.input model="form.secondary_call_to_action_en" :label="__('forms.manage_hero.secondary_call_to_action_en')" type="text"
                html_id="FH-SCta" />
        </div>
        <div class="column">
            <x-core.form.textarea model="form.introduction_fr" :label="__('forms.hero.introduction_fr')" html_id="mh-IntoFr" />
            <x-core.form.textarea model="form.introduction_en" :label="__('forms.hero.introduction_en')" html_id="mh-IntoEn" />
            <x-core.form.textarea model="form.introduction_ar" :label="__('forms.hero.introduction_ar')" html_id="mh-IntoAr" />
        </div>


        <div class="column center" x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true"
            x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-cancel="uploading = false"
            x-on:livewire-upload-error="uploading = false"
            x-on:livewire-upload-progress="progress = $event.detail.progress">

            <x-core.file-input model="form.images" types="img"  multiple=true />

            <div x-show="uploading">
                <progress max="100" x-bind:value="progress"></progress>
            </div>

        </div>
        @if (is_array($temporaryImageUrls) && !empty($temporaryImageUrls))
            <div class="imgs__container">
                <div class="imgs">
                    @foreach ($temporaryImageUrls as $url)
                        <img class="img" src="{{ $url }}" alt="{{ __('forms.manage_hero.images') }}" />
                    @endforeach
                </div>
            </div>
        @endif

        <div class="form__actions">
            <x-core.button function="handleSubmit" :wireTargets="['handleSubmit']" prevent="true" variant="primary"
                :text="__('forms.common.actions.submit')" icon="confirm" expectLoading=true fullWidth=true />
        </div>
    </form>
</div>
