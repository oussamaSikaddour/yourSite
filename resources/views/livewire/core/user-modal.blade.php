<div class="modal__body__content">
    <div class="form__container">

        <form class="form" wire:submit="handleSubmit">
            <div class="row">

                <!-- Column 1 -->
                <div class="column">
                    <x-core.form.input model="{{ $form }}.name" :label="__('forms.user.name')" type="text"
                        html_id="UM-Name" />

                    <x-core.form.input model="{{ $form }}.email" :label="__('forms.user.email')" type="text"
                        html_id="UM-Email" />

                    <x-core.form.password-input model="{{ $form }}.password" :label="__('forms.user.password')"
                        html_id="UserPassword" />

                    @if ($form === 'addForm' && !isset($personId))
                        <x-core.form.selector htmlId="" model="addForm.person_id" :label="__('forms.user.person_id')"
                            :data="$personsOptions" :showError="true" />
                    @endif

                    @canany(['super-admin-access', 'admin-access'])
                        <div class="checkbox__group">
                            <div class="choices" role="group" aria-labelledby="checkbox-choices">
                                <x-core.form.check-box model="isActiveCheckBoxValue" value="{{ !$isActiveCheckBoxValue }}"
                                    :label="__('forms.user.is_active')" htmlId="UM-isActive" :live="true" />
                            </div>
                        </div>
                    @endcanany
                </div>

                <!-- Column 2 -->
                <div class="column">
                    <x-core.file-input   model="{{ $form }}.avatar"  types="img" type="avatar"   :fileUri="$temporaryImageUrl"/>
                </div>

            </div> <!-- end .row -->

            <div class="form__actions">
                <x-core.button type="submit" variant="primary" :text="__('forms.common.actions.submit')" icon="confirm" expectLoading="true"
                    fullWidth="true" />
            </div>

        </form>

    </div> <!-- end .form__container -->
</div> <!-- end .modal__body__content -->
