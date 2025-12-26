<!-- resources/views/livewire/registration-form.blade.php -->

<form class="form" wire:submit.prevent="handleSubmit" style="--form-position: 1" >
    <h3>@lang("forms.register.instructions.email")</h3>

    <div class="column">
        <x-core.form.input
        model="form.email"
       :label="__('forms.register.email')"
        type="email"
        html_id="registEmail" />
         <x-core.form.password-input
         model="form.password"
         :label="__('forms.register.password')"
          html_id="registPassword"/>
    </div>
    <div class="row">

    </div>
    <div class="form__actions">


         <x-core.button  href="{{ route($this->loginRoute) }}" :text="__('pages.register.links.login')" />
         <x-core.button type="submit" variant="primary" :text="__('forms.register.actions.get_code')"   expectLoading=true/>
    </div>
</form>


