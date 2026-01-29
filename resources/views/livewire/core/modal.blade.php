<div
    id="defaultModal"
    class="modal {{ $isOpen ? 'open' : '' }} {{ $transparent ? 'transparent' : '' }}"
    role="dialog"
    aria-modal="true"
    aria-hidden="{{ $isOpen ? 'false' : 'true' }}"
    tabindex="-1"
    style="{{ $isOpen ? '' : 'pointer-events:none;' }}"
>
    <div class="modal__backdrop" style="position:absolute; inset:0;"></div>

    <div class="modal__content" style="position:relative;">
        <button class="modal__closer" type="button" wire:click="closeModal">
            <span></span><span></span>
        </button>

        <div class="modal__header">
            <h2 id="dialog_label" class="sr-only">Info Modal</h2>
            <h2>@lang($title, $titleOptions)</h2>
        </div>

        <div class="modal__body">
            @if (!empty($component) && !empty($component['name']))
                @livewire($component['name'], $component['parameters'] ?? [], key('modal-'.$modalInstance))
            @endif
        </div>
    </div>
</div>
