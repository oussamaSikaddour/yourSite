<form class="form " wire:submit="handleSubmit" style="--form-position: 1">

    <div class="column">
        <x-core.form.input model="form.email" :label="__('forms.site_parameters.steps.first.email')" type="email" html_id="spEmail" />
        <x-core.form.password-input model="form.password" :label="__('forms.site_parameters.steps.first.password')" html_id="spPassword" />
    </div>

    <div class="form__actions">



        <x-core.button type="submit" variant="primary" :text="__('forms.common.actions.submit')" expectLoading=true fullWidth=true  icon="forward"/>
    </div>
</form>
