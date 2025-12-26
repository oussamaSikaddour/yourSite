<div
    role="alert"
    aria-labelledby="errors_label"
    aria-describedby="errors_list"
    aria-live="polite"
    class="errors__container {{ $isOpen ?'open':'' }}"
    id="coreErrors"

>
    <h2 id="errors_label" class="sr-only">Errors</h2>
    <button
        class="errors__closer"
        wire:click="closeErrors"
        aria-label="Close errors">
        <span></span>
        <span></span>
    </button>
    <ul id="errors_list" class="errors" tabindex="0">
        @foreach ($errors as $error)
            <li class="error">{{ $error }}</li>
        @endforeach
    </ul>
</div>


