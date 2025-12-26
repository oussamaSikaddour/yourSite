<div role="dialog" aria-labelledby="dialog_box" class="dialog {{ $isOpen ?'open':'' }}"
    id="box">
    <h3 id="dialog_box" class="sr-only">@lang('Help about the box')</h3>

    <div class="dialog__header">
        <h3>{{ __($question) }}</h3>
    </div>
    <div class="dialog__body">
        {{ $questionDetails }}
    </div>

    <div class="dialog__footer">

        <div class="dialog__actions">
            <div wire:loading wire:target="confirmAction">
                <x-core.loading />
            </div>



            <button class="button dialog__closer ">
                @lang('forms.common.actions.cancel')
            </button>
            <x-core.button :text="__('forms.common.actions.confirm')" :tooltip="__('toolTips.common.confirm')" variant="primary" function="confirmAction" />
        </div>

    </div>
</div>

@script
    <script>
        document.addEventListener('close-dialog', () => {
            @this.closeDialog();
        });

        $wire.on("user-chose-yes", () => {
            @this.closeDialog();

            // Dispatch a custom event for external logic
            const closeDialogBoxEvent = new CustomEvent('dialog-will-be-close');
            document.dispatchEvent(closeDialogBoxEvent);
        });
    </script>
@endscript
