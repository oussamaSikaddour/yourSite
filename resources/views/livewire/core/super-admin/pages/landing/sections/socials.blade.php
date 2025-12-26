
<div class="form__container small">
    <form class="form" wire:submit="handleSubmit">


        <div class="column ">
            <x-core.form.input model="form.youtube" :label="__('forms.socials.youtube')" type="text" html_id="FS-Y" />
            <x-core.form.input model="form.facebook" :label="__('forms.socials.facebook')" type="text" html_id="FS-f" />
            <x-core.form.input model="form.instagram" :label="__('forms.socials.instagram')" type="text" html_id="FS-i" />
            <x-core.form.input model="form.linkedin" :label="__('forms.socials.linkedin')" type="text" html_id="FS-l" />
            <x-core.form.input model="form.github" :label="__('forms.socials.github')" type="text" html_id="FS-g" />
            <x-core.form.input model="form.tiktok" :label="__('forms.socials.tiktok')" type="text" html_id="FS-t" />

        </div>
        <div class="form__actions">
                      <x-core.button type="submit" variant="primary" :text="__('forms.common.actions.submit')" icon="confirm"
                          expectLoading=true fullWidth=true />
        </div>
    </form>
</div>
