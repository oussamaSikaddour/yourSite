@extends('layouts.core-layout')
@section('pageContent')
    <div class="container__header">
        <div class="container__header__top">



            <h2>@lang('pages.login.titles.main')</h2>
        </div>
    </div>
    <div class="form__container small ">
        <livewire:core.guest.login />
    </div>
@endsection
