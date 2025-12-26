<form class="form" wire:submit="handelSubmit" x-data="{ redirecting: false }"
    x-on:redirect-page.window="redirecting = true; setTimeout(() => { window.location.href = '{{ $this->logoutRoute() }}' }, 4500)">
    <h3>@lang('forms.change_mail.infos.redirect')</h3>
    <div class="column">
        <x-core.form.input model="form.oldEmail" :label="__('forms.change_mail.mail')" type="email" html_id="FCPEmail" />
        <x-core.form.password-input :label="__('forms.change_mail.pwd')" model="form.password" html_id="CEPassword" />
    </div>
    <div class="column">
        <x-core.form.input model="form.newEmail" :label="__('forms.change_mail.new_mail')" type="email" html_id="FCPNewEmail" />
    </div>
    <div class="form__actions">

        <div class="form__actions">
            <x-core.button type="submit" variant="primary" :text="__('forms.common.actions.submit')" icon="confirm" expectLoading=true
                fullWidth=true />
        </div>
</form>
