<div class="form__container">
    <form class="form">

        {{-- General Information --}}
        <div class="row ">
            <x-core.form.input model="form.app_name" :label="__('forms.general_infos.app_name')" type="text" html_id="FL-appN" />
            <x-core.form.input model="form.acronym" :label="__('forms.general_infos.acronym')" type="text" html_id="FL-acronym" />
            <x-core.form.selector htmlId="yearGI"
                  model="form.inaugural_year" :label="__('forms.general_infos.inaugural_year')"
                            :data="$yearsOptions" :showError="true" />
        </div>

        <div class="row center">
            <x-core.form.input model="form.email" :label="__('forms.general_infos.email')" type="email" html_id="FL-email" />
            <x-core.form.input model="form.phone" :label="__('forms.general_infos.phone')" type="text" html_id="FL-phone" />
        </div>

        <div class="row center">
            <x-core.form.input model="form.landline" :label="__('forms.general_infos.landline')" type="text" html_id="FL-landline" />
            <x-core.form.input model="form.fax" :label="__('forms.general_infos.fax')" type="text" html_id="FL-fax" />
        </div>

        <div class="row">
            <x-core.form.input model="form.address_fr" :label="__('forms.general_infos.address_fr')" type="text" html_id="FL-addressFr" />
            <x-core.form.input model="form.address_en" :label="__('forms.general_infos.address_en')" type="text" html_id="FL-addressEn" />
            <x-core.form.input model="form.address_ar" :label="__('forms.general_infos.address_ar')" type="text" html_id="FL-addressAr" />
        </div>

        {{-- Map --}}
        <div class="column center">
            <x-core.form.textarea model="form.map" :label="__('forms.general_infos.map')" html_id="FL-map" />
        </div>

        {{-- Logo Upload --}}
        <div class="column center" x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true"
            x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-cancel="uploading = false"
            x-on:livewire-upload-error="uploading = false"
            x-on:livewire-upload-progress="progress = $event.detail.progress">

            <x-core.file-input
             model="form.logo"
            types="img" type="image"
             :fileUri="$temporaryImageUrl"
             :tooltip="__('forms.general_infos.logo')"
              />
            <div x-show="uploading" class="upload__progress">
                <progress max="100" x-bind:value="progress"></progress>
            </div>
        </div>


        {{-- Social Links --}}
        <div class="row">
            <x-core.form.input model="form.youtube" :label="__('forms.general_infos.youtube')" type="text" html_id="FL-youtube" />
            <x-core.form.input model="form.facebook" :label="__('forms.general_infos.facebook')" type="text" html_id="FL-facebook" />
            <x-core.form.input model="form.linkedin" :label="__('forms.general_infos.linkedin')" type="text" html_id="FL-linkedin" />
        </div>

        <div class="row">
            <x-core.form.input model="form.github" :label="__('forms.general_infos.github')" type="text" html_id="FL-github" />
            <x-core.form.input model="form.instagram" :label="__('forms.general_infos.instagram')" type="text" html_id="FL-instagram" />
            <x-core.form.input model="form.tiktok" :label="__('forms.general_infos.tiktok')" type="text" html_id="FL-tiktok" />
        </div>

        <div class="row">
            <x-core.form.input model="form.twitter" :label="__('forms.general_infos.twitter')" type="text" html_id="FL-x" />
            <x-core.form.input model="form.threads" :label="__('forms.general_infos.threads')" type="text" html_id="FL-threads" />
            <x-core.form.input model="form.snapchat" :label="__('forms.general_infos.snapchat')" type="text" html_id="FL-snapchat" />
        </div>

        <div class="row">
            <x-core.form.input model="form.pinterest" :label="__('forms.general_infos.pinterest')" type="text" html_id="FL-pinterest" />
            <x-core.form.input model="form.reddit" :label="__('forms.general_infos.reddit')" type="text" html_id="FL-reddit" />
            <x-core.form.input model="form.telegram" :label="__('forms.general_infos.telegram')" type="text" html_id="FL-telegram" />
        </div>

        <div class="row">
            <x-core.form.input model="form.whatsapp" :label="__('forms.general_infos.whatsapp')" type="text" html_id="FL-whatsapp" />
            <x-core.form.input model="form.discord" :label="__('forms.general_infos.discord')" type="text" html_id="FL-discord" />
            <x-core.form.input model="form.twitch" :label="__('forms.general_infos.twitch')" type="text" html_id="FL-twitch" />
        </div>



        {{-- Actions --}}
        <div class="form__actions">
            <x-core.button function="handleSubmit" :wireTargets="['handleSubmit']" prevent="true" variant="primary"
                :text="__('forms.common.actions.submit')" icon="confirm" expectLoading=true fullWidth=true />
        </div>

    </form>
</div>
