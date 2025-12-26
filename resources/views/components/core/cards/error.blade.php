@props(['status'])

<div class="error__card">
    <h1>{{ $status }}</h1>

    <div>
         <p >@lang('cards.error.' . $status.'.title')</p>
        <p>
            @lang('cards.error.' . $status.'.text')
        </p>

        <a href="/">
            <i class="fa-solid fa-right-to-bracket"></i>
        </a>
    </div>
</div>
