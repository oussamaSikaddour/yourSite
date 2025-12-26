@props(['html_id', 'model', 'label'])

<div class="input__item">
    <div class="input__group" x-data="{ showPassword: false }">
        <!-- Password Input -->
        <input
            x-bind:type="showPassword ? 'text' : 'password'"
            class="input"
            placeholder="{{ $label }}"
            wire:model="{{ $model}}"
            id="{{ $html_id }}"
            {{ $attributes->merge(['class' => 'input']) }}
            aria-describedby="{{ $html_id }}-error"
        />

        <!-- Toggle Password Visibility -->
        <span
            role="button"
            tabindex="0"
            aria-label="Toggle password visibility"
            x-on:click="showPassword = !showPassword"
            x-bind:aria-pressed="showPassword.toString()"
            class="input__span"
        >
            <i x-bind:class="showPassword ? 'form__icon fa-solid fa-eye fa-xl' : 'form__icon fa-solid fa-eye-slash fa-xl'"></i>
        </span>

        <!-- Label -->
        <label for="{{ $html_id }}" class="input__label">{{ $label }}</label>
    </div>

    <!-- Validation Error Message -->
    @error($model)
        <div id="{{ $html_id }}-error" class="input__error">
            ⚠   {{ $message }}
        </div>
    @enderror
</div>
