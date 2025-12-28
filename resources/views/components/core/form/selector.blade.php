<div class="select__input  {{ $tooltip ? 'hasTooltip' : '' }}">
    @if ($tooltip)
        <span class="tooltip__content">{{ $tooltip }}</span>
    @endif
    <div class="select__group">
        @if ($label)
            <label for="{{ $htmlId }}">{{ $label }}:</label>
        @endIf
        <div class="select">
            <select id="{{ $htmlId }}"
                wire:model{{ $type === 'filter' ? '.live' : '' }}="{{ $model }}">
                @foreach ($data as $value => $option)
                    <option value="{{ $value }}">{{ $option }}</option>
                @endforeach
            </select>
        </div>
    </div>

    @if ($showError)
        @error($model)
            ⚠   <div class="input__error">{{ $message }}</div>
        @enderror
    @endif
</div>
