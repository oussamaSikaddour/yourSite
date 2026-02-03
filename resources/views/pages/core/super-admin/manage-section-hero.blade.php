@extends('layouts.core-layout')
@section('pageContent')
    <div class="container__header">

        <div class="container__header__top">
            <livewire:core.breadcrumb :$breadcrumbLinks />
        </div>
        <div class="container__header__bottom">

            <h2>@lang('pages.manage_hero.titles.main')</h2>



        </div>

    </div>

    <div class="row center">
    <livewire:core.super-admin.pages.landing.sections.hero />
    </div>
@endsection
