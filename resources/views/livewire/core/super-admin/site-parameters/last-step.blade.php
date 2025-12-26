<form class="form " style="--form-position: 2">

    <div class="column center">

        <div class="radio__group">
            <div class="choices">

                <x-core.form.radio-button model="form.maintenance" value="1" :label="__('forms.site_parameters.steps.last.enable')" htmlId="m-m-rb-y" />
                <x-core.form.radio-button model="form.maintenance" value="0" :label="__('forms.site_parameters.steps.last.disable')" htmlId="m-m-rb-n" />
            </div>
            @error('form.maintenance')
                <div class="input__error">
                    {{ $message }}
                </div>
            @enderror

        </div>
    </div>


    <div class="form__actions">

        <div class="column">
            @if ($generalSettings?->maintenance)
                <x-core.button :text="__('forms.site_parameters.actions.download_db')" function="downloadDatabase" :wireTargets="['downloadDatabase']" prevent="true"
                    expectLoading=true fullWidth=true  icon="database"/>
            @endif
            <x-core.button :text="__('forms.common.actions.submit')" type="submit" variant="primary" function="handleSubmit" :wireTargets="['handleSubmit']"
                prevent="true" expectLoading=true fullWidth=true icon="permissions" />
        </div>


    </div>
</form>
