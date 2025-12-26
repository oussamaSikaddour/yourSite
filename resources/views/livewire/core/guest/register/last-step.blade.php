<form class="form " id="myForm" style="--form-position: 2">
    <h3>@lang('forms.register.instructions.code')</h3>
    <div class="column">
        <x-core.form.input model="form.email" :label="__('forms.register.email')" type="email" html_id="registerSFEmail" />
        <x-core.form.input model="form.code" :label="__('forms.register.code')" type="text" html_id="registerVerificationCode" />
    </div>

    <div class="form__actions">

        <div class="column">
            <x-core.button
            :text="__('forms.register.actions.get_new_code')"
             function="setNewValidationCode"
             :wireTargets="['setNewValidationCode']"
             prevent="true"
            expectLoading=true fullWidth=true
                 />
            <x-core.button
            :text="__('forms.register.actions.submit')"
            type="submit"
            variant="primary"
            function="handleSubmit"
            :wireTargets="['handleSubmit']"
             prevent="true"
             expectLoading=true
             fullWidth=true />
        </div>


    </div>
</form>
