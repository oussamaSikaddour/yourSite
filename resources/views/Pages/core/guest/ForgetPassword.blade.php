@extends('layouts.core-layout')
@section('pageContent')
    <div class="container__header">

        <div class="container__header__top">



            <h2>@lang('pages.forgot_password.titles.main')</h2>

        </div>
    </div>
    <div class="form__container small forMultiForm forget-password-multi-form-step">
        <div class="forms fpForms">
            <livewire:core.guest.forgot-password.first-step wire:key="fp-f-s" />
            <livewire:core.guest.forgot-password.last-step wire:key="fp-l-s" />
        </div>
    </div>
@endsection
