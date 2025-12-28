@extends('layouts.core-layout')
@section('pageContent')
    <div class="container__header">

        <div class="container__header__top">
            <livewire:core.breadcrumb :$breadcrumbLinks />
        </div>
        <div class="container__header__bottom">
            <h2>@lang('pages.general_infos.titles.main')</h2>
        </div>

    </div>

    <livewire:core.super-admin.general-infos />
@endsection
