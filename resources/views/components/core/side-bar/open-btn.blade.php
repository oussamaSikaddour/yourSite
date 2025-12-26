@props(['htmlId'])
<button
    id="{{$htmlId}}"
    {{ $attributes->merge(['class'=>"button rounded sidebar__toggle__btn"]) }}
    aria-haspopup="true"
    aria-expanded="false"
    aria-controls="mainMenu"
>
    <span>
        <i class="fa-solid fa-gear"></i>
    </span>
</button>
