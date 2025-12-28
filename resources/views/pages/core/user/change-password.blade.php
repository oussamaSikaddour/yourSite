@extends('layouts.core-layout')
@section('pageContent')
    <div class="container__header">
        <div class="container__header__top">

            <h2>@lang('pages.change_password.titles.main')</h2>

        </div>

    </div>
    <div class="form__container small">
        <livewire:core.user.change-password />
    </div>
@endsection
