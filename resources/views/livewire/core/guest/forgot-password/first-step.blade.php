<form class="form " wire:submit.prevent="handleSubmit"   style="--form-position: 1">
    <h3>
        @lang("forms.forgot_password.instructions.email")
    </h3>
    <div class="column">
        <x-core.form.input
       model="form.email"
        :label="__('forms.forgot_password.email')"
          type="email"
           html_id="FFPEmail" />
    </div>
    <div class="form__actions">

          <x-core.button type="submit" variant="primary" :text="__('forms.forgot_password.actions.get_code')"   expectLoading=true/>
    </div>
</form>
