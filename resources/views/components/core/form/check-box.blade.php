@props([
    'model' => null,
    'htmlId',
    'value' => null,
    'label' => '',
    'live' => false,
])

@php
    $wireModel = $model
        ? ($live
            ? "wire:model.live=\"{$model}\""
            : "wire:model=\"{$model}\"")
        : null;

    $isChecked = filled($model) ? 'true' : 'false';
@endphp

<div class="fragment" id="frg-{{ $htmlId }}">
    <input
        @if($wireModel) {!! $wireModel !!} @endif
        @if($model) wire:key="{{ $model }}" @endif
        type="checkbox"
        @if(!is_null($value)) value="{{ $value }}" @endif
        id="{{ $htmlId }}"
        role="checkbox"
        aria-checked="{{ $isChecked }}"
        {{ $attributes->merge(['class' => 'checkbox-input']) }}
    />
    <label for="{{ $htmlId }}" tabindex="0" @if($model) wire:target="{{ $model }}" @endif>
        {{ $label }}
    </label>
</div>
