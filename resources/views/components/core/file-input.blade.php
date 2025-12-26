@php
    // Icon fallback
    $defaultIcon = '<i class="fa-solid fa-file-import"></i>';
    $iconToUse = $iconHtml ?? $defaultIcon;

    // Determine visual layout
    $layout = match ($type) {
        'icon_only' => 'icon_only',
        'image' => 'image',
        'avatar' => 'avatar',
        default => 'default',
    };
@endphp


@switch($layout)

    {{-- =========================================================
     DEFAULT (FULL WIDTH)
========================================================= --}}
    @case('default')
        <div class="file__input__item {{ $fileUri ? 'show-uri' : '' }}" role="input">

            <div class="file__input__group">
                <button class="button button--info">
                    {{ $text }}
                    <span>{!! $iconToUse !!}</span>
                </button>

                <input id="{{ $htmlId }}" type="file" wire:model="{{ $model }}"
                    @if ($multiple) multiple @endif
                    @if ($typesToUpload) accept="{{ $typesToUpload }}" @endif />
            </div>

            @if ($fileUri)
                <div class="file__input__uri">
                    <p>{{ $fileUri }}</p>
                </div>
            @endif

            @error($model)
                <div id="{{ $htmlId }}-error" class="input__error">⚠ {{ $message }}</div>
            @enderror

        </div>
    @break

    {{-- =========================================================
     ICON ONLY
========================================================= --}}
    @case('icon_only')
        <div class="file__input__group icon_only {{ $tooltip ? 'hasTooltip' : '' }}">

            @if ($tooltip)
                <span class="tooltip__content">{{ $tooltip }}</span>
            @endif

            <button class="button button--info rounded">
                <span>{!! $iconToUse !!}</span>
            </button>

            <input id="{{ $htmlId }}" type="file" wire:model="{{ $model }}"
                @if ($multiple) multiple @endif
                @if ($typesToUpload) accept="{{ $typesToUpload }}" @endif />
        </div>

        @error($model)
            <div id="{{ $htmlId }}-error" class="input__error">⚠ {{ $message }}</div>
        @enderror
    @break

    {{-- =========================================================
     IMAGE INPUT
========================================================= --}}
    @case('image')
        <div class="file__input__item {{ $tooltip ? 'hasTooltip' : '' }}" role="input">


            @if ($tooltip)
                <span class="tooltip__content">{{ $tooltip }}</span>
            @endif
            <div class="image__input__group">
                <button class="button">
                    <img src="{{ $fileUri ?? asset('assets/core/images/utils/pictures.png') }}"
                        class="image__input__placeholder" alt="defaultImg" />

                    <img src="{{ asset('assets/core/images/utils/camera.png') }}" class="image__input__button" />
                </button>

                <input id="{{ $htmlId }}" type="file" wire:model="{{ $model }}"
                    @if ($typesToUpload) accept="{{ $typesToUpload }}" @endif />
            </div>

            @error($model)
                <div id="{{ $htmlId }}-error" class="input__error">⚠ {{ $message }}</div>
            @enderror

        </div>
    @break

    {{-- =========================================================
     AVATAR INPUT
========================================================= --}}
    @case('avatar')
        <div class="file__input__item" role="input">

            <div class="image__input__group profile-image">
                <button class="button">
                    <img src="{{ $fileUri ?? asset('assets/core/images/utils/avatar.png') }}" class="image__input__placeholder"
                        alt="defaultImg" />

                    <img src="{{ asset('assets/core/images/utils/camera.png') }}" class="image__input__button" />
                </button>

                <input id="{{ $htmlId }}" type="file" wire:model="{{ $model }}"
                    @if ($typesToUpload) accept="{{ $typesToUpload }}" @endif />
            </div>

            @error($model)
                <div id="{{ $htmlId }}-error" class="input__error">⚠ {{ $message }}</div>
            @enderror

        </div>
    @break

@endswitch
