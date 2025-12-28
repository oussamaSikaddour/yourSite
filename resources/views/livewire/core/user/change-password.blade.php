<form class="form" wire:submit="handelSubmit" x-data="{ redirecting: false }"
    x-on:redirect-page.window="redirecting = true; setTimeout(() => { window.location.href = '{{ $this->logoutRoute() }}' }, 4500)">
    <h3>@lang('forms.change_password.infos.redirect')</h3>
    <div class="column">
        <x-core.form.password-input :label="__('forms.change_password.old_pwd')" model="form.password" html_id="CPPassword" />
        <x-core.form.password-input :label="__('forms.change_password.pwd')" model="form.newPassword" html_id="newPassword" />
    </div>
    <div class="form__actions">

        <div class="form__actions">
            <x-core.button type="submit" variant="primary" :text="__('forms.common.actions.submit')" icon="confirm" expectLoading=true
                fullWidth=true />
        </div>
</form>
