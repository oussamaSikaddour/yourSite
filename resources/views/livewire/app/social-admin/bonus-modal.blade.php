      <div class="modal__body__content">
    <div class="form__container">
        <form class="form" wire:submit="handleSubmit">

            <div class="row center">
                <x-core.form.input
                model="{{$form}}.titled_fr"
                :label="__('forms.bonus.titled_fr')"
                 type="text"
                  html_id="MBo-bAR" />
                <x-core.form.input
                model="{{$form}}.titled_ar"
                :label="__('forms.bonus.titled_ar')"
                 type="text"
                  html_id="MBo-bfr" />
                <x-core.form.input
                model="{{$form}}.titled_en"
                :label="__('forms.bonus.titled_en')"
                 type="text"
                  html_id="MBo-bAR" />

            </div>
            <div class="row center">
                <x-core.form.input model="{{$form}}.amount" :label="__('forms.bonus.amount')" type="money" html_id="MBo-bAR" />
            </div>
            <div class="form__actions">
                   <x-core.button type="submit" variant="primary" :text="__('forms.common.actions.submit')" icon="confirm"
                          expectLoading=true fullWidth=true />
            </div>
        </form>
    </div>
    </div>
