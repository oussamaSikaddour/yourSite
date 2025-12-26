@extends('layouts.app-layout')
@section('pageContent')
    <div class="container__heading">
        <div class="heading">


            <livewire:core.breadcrumb :$breadcrumbLinks />


            <h3 class="heading__title">@lang('pages.service_details_public.title', ['name' => $service->name])</h3>
            {{-- <div class="heading__center">
            </div> --}}
            {{-- <div class="heading__bottom"></div> --}}
        </div>
    </div>


    <livewire:app.cards.service-details :$service />
    <livewire:app.articles-pages-viewer :$articleableId :$articleableType />
@endsection
