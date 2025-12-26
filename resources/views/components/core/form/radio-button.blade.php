@props([
    'model',     // Wire model binding
    'htmlId',    // Unique ID for the input
    'value',     // Value of the radio button
    'label' => '', // Label for the radio button
    'type' => '',  // Type to conditionally apply attributes
])

<div class="radio__button" {{ $attributes->merge(['class' => 'radio']) }}>
    <input
        type="radio"
        id="{{ $htmlId }}"
        wire:model{{ $type === 'forTable' ? '.live' : '' }}="{{ $model }}"
        value="{{ $value }}"
        aria-checked="false"
    />
    <label for="{{ $htmlId }}" role="radio" tabindex="0">
        {{ $label }}
    </label>
</div>
