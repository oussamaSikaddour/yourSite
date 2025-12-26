@extends('layouts.core-layout')
@section('pageContent')
    <div class="container__header">

        <div class="container__header__top">

            <h2>@lang('pages.register.titles.main')</h2>
        </div>

    </div>

    <div class="form__container forMultiForm  small register-multi-form-step">
        <div class="forms" style="--forms-count: 2">
            <livewire:core.guest.register.first-step wire:key="r-f-s" />
            <livewire:core.guest.register.last-step wire:key="r-l-s" />
        </div>
    </div>
@endsection
