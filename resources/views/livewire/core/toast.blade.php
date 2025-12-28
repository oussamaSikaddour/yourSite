<div tabindex="0"
     role="alert"
     aria-labelledby="toast_label"
     class="toast__container {{ $isOpen ? 'open' : '' }} {{ $variant }}"
>
    <h2 id="toast_label" class="sr-only">Toast Message</h2>

    <button class="toast__closer"
            aria-label="Fermer la notification"
            wire:click="$dispatch('close-toast')">
        <span></span><span></span>
    </button>

    <div class="toast">
        {{ $message }}
    </div>
</div>
