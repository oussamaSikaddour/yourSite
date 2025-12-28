@extends('layouts.app-layout')
@section('pageContent')
    <div class="container__heading">
        <div class="heading">

            <livewire:core.breadcrumb :$breadcrumbLinks />
            <h3 class="heading__title">@lang('pages.news.title')</h3>
            {{-- <div class="heading__center">
            </div> --}}
            {{-- <div class="heading__bottom"></div> --}}
        </div>
    </div>

    <livewire:app.articles-pages-viewer />
@endsection
