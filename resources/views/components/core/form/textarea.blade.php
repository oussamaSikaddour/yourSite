@props([
    'model',    // Wire model name
    'label',    // Label text for the textarea
    'html_id',  // Unique HTML ID for the textarea
    'rows' => 4,    // Default number of rows
    'cols' => 100,  // Default number of columns
    'maxlength' => 3000, // Default maximum character length
    'placeHolder'=>null,
    'type'=>"default",
])


@if($type==="default")
<div class="textarea__group">
    <textarea
        id="{{ $html_id }}"
        class="textarea"
        wire:model="{{ $model }}"
        rows="{{ $rows }}"
        cols="{{ $cols }}"
        maxlength="{{ $maxlength }}"
        placeholder="{{ $label }}"
        {{ $attributes }}
    ></textarea>
    <label for="{{ $html_id }}" class="textarea__label">{{ $label }}</label>
    @error($model)
        <div class="input__error">
            {{ $message }}
        </div>
    @enderror
</div>

@else

 <div class="form-group">
         <div class="form-field message-field">

            <textarea
            class="form-control message-input"
             id="{{ $html_id }}"
              wire:model="{{ $model }}"
              rows="{{ $rows }}"
               cols="{{ $cols }}"
               maxlength="{{ $maxlength }}"
              placeholder="{{ $placeHolder }}"
             >
            </textarea>
            </div>
        @error($model)
            <div id="{{ $html_id }}-error" class="error-message">
                ⚠ {{ $message }}

            </div>
        @enderror

 </div>


@endif
