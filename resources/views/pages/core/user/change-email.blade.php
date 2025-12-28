@extends('layouts.core-layout')
@section('pageContent')
    <div class="container__header">
        <div class="container__header__top">


            <h2>@lang('pages.change_email.titles.main')</h2>
        </div>

    </div>
    <div class="form__container small">
        <livewire:core.user.change-mail />
    </div>
@endsection
