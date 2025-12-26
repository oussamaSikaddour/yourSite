
<div class="select__group">
    <div>
        <label for="{{ $htmlId }}"></label>
        <div class="select">
            <select
                id="{{ $htmlId }}"
                wire:model="selectedValue"
                wire:change="selectedValueChanged"
            >
                @foreach ($data as $value => $option)
                    <option value="{{ $value }}">{{ $option }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
