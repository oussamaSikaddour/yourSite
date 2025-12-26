@props(['model', 'htmlId', 'value', 'label' => '', 'live' => false])

@php
    $wireModel = $live ? "wire:model.live=\"{$model}\"" : "wire:model=\"{$model}\"";
@endphp

<div class="fragment" id="frg-{{ $htmlId }}">
    <input
        {!! $wireModel !!}
        wire:key="{{ $model }}"
        type="checkbox"
        value="{{ $value }}"
        id="{{ $htmlId }}"
        role="checkbox"
        aria-checked="{{ $model ? 'true' : 'false' }}"
        {{ $attributes->merge(['class' => 'checkbox-input']) }}
    />
    <label for="{{ $htmlId }}" tabindex="0" wire:target="{{ $model }}">
        {{ $label }}
    </label>
</div>
