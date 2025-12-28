<div class="modal__body__content">
    <div class="form__container">
        <form class="form" wire:submit="handleSubmit">

            <div class="row center">
                <x-core.form.input model="{{ $form }}.name" :label="__('forms.slider.name')" type="text" html_id="MSlideN" />
                <x-core.form.input model="{{ $form }}.position" :label="__('forms.slider.position')" type="number" min="1"
                    html_id="MSlideN" />
            </div>
            <div class="form__actions">

                <x-core.button type="submit" variant="primary" :text="__('forms.common.actions.submit')" icon="confirm" expectLoading=true
                    fullWidth=true />
            </div>
        </form>
    </div>
</div>
