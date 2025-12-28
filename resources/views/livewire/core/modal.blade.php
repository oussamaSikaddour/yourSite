<div role="dialog" aria-labelledby="dialog_label"
    class="modal {{ $isOpen ? 'open' : '' }} {{ $transparent ? 'transparent' : '' }}" id="defaultModal">

    <div class="modal__content">

        {{-- Close Button --}}
        <button class="modal__closer" x-on:click="@this.closeModal()">
            <span></span>
            <span></span>
        </button>

        {{-- Header --}}
        <div class="modal__header">
            {{-- Accessible title --}}
            <h2 id="dialog_label" class="sr-only">Info Modal</h2>
            {{-- Visible title --}}
            <h2>@lang($title, $titleOptions)</h2>
        </div>
        <div class="modal__body">
            {{-- Livewire component slot --}}
            @if (!empty($component) && is_array($component))
                @livewire($component['name'], $component['parameters'] ?? [])
            @endif

        </div>

    </div>
</div>

@script
    <script>
        document.addEventListener('modal-will-be-close', function() {
            @this.closeModal();
            if (@this.containsTinyMce) {
                setTimeout(() => {
                    window.location.reload();
                }, 300); // Optional delay for smoother UX
            }
        });
    </script>
@endscript
