<form class="form" wire:submit="handelSubmit">
    <div class="column">
        <x-core.form.input model="form.email" :label="__('forms.login.email')" type="email" html_id="loginEmail" />
        <x-core.form.password-input model="form.password" :label="__('forms.login.password')" html_id="loginPassword" />
    </div>



    <div class="form__actions">
        <div class="column">
            <x-core.button href="{{ route($this->forgetPasswordRoute) }}" :text="__('pages.login.links.forgot_password')" />






            <x-core.button type="submit" variant="primary" :text="__('forms.login.actions.submit')" icon="login" expectLoading=true
                fullWidth=true />
            <x-core.button href="{{ route($this->registerPageRoute) }}" :text="__('pages.login.links.register')" />

            < </div>
        </div>
</form>
