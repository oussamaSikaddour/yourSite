<form class="form " wire:submit.prevent="handleSubmit" style="--form-position: 2">
    <h3> @lang('forms.forgot_password.instructions.code')</h3>

    <div class="column">
        <x-core.form.input model="form.email" :label="__('forms.forgot_password.email')" type="email" html_id="FPEmail" />
        <x-core.form.input model="form.code" :label="__('forms.forgot_password.code')" type="text" html_id="FPPassword" />
    </div>
    <div class="column">
        <x-core.form.password-input :label="__('forms.forgot_password.password')" model="form.password" html_id="FPNPassword" />
    </div>
    <div class="form__actions">


        <x-core.button type="submit" variant="primary" :text="__('forms.common.actions.submit')" expectLoading=true  icon="forward"/>
    </div>
</form>
