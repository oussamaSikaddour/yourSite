@props([
    'model', // Wire model name
    'label', // Label text for the input

    'type' => 'text', // Input type (default: text)
    'html_id', // Unique HTML ID for the input
    'role' => '', // Role of the input (e.g., 'filter')
    'min' => null, // Minimum value (for number/date inputs)
    'max' => null, // Maximum value (for number/date inputs)
    'placeHolder' => null,
    'default' => true,
])


@if ($default)
    <div class="input__item">
        <div class="input__group">
            <input
            name="{{ $model }}"
            type="{{ $type }}"
                class="input {{ $errors->has($model) ? 'input--error' : '' }}"

                placeholder="{{ $label }}"
                id="{{ $html_id }}"
                wire:model{{ $role === 'filter' ? '.live.debounce.300ms' : '' }}="{{ $model }}"
                @if ($min) min="{{ $min }}" @endif
                @if ($max) max="{{ $max }}" @endif
                @if ($type === 'money') x-data
            x-on:blur="$event.target.value = parseFloat($event.target.value || 0).toFixed(2)" @endif
                aria-describedby="{{ $html_id }}-error" {{ $attributes }}

                />
            <label for="{{ $html_id }}" class="input__label">{{ $label }}</label>
        </div>
        @error($model)
            <div id="{{ $html_id }}-error" class="input__error">
                ⚠   {{ $message }}
            </div>
        @enderror
    </div>
@else
    <div class="form-group">
        <div class="form-field">
            <input
               name="{{ $model }}"
               type="{{ $type }}"
                class="form-control"
                placeholder="{{ $placeHolder }}"
                id="{{ $html_id }}"
                wire:model{{ $role === 'filter' ? '.live.debounce.300ms' : '' }}="{{ $model }}"
                @if ($min) min="{{ $min }}" @endif
                @if ($max) max="{{ $max }}" @endif
                @if ($type === 'money') x-data
            x-on:blur="$event.target.value = parseFloat($event.target.value || 0).toFixed(2)" @endif
                aria-describedby="{{ $html_id }}-error" {{ $attributes }}
            >
        </div>

        @error($model)
            <div id="{{ $html_id }}-error" class="error-message">
                  ⚠  {{ $message }}

            </div>
        @enderror
    </div>
@endif
