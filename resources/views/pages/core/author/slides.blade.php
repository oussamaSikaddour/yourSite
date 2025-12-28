@extends('layouts.core-layout')
@section('pageContent')
    <div class="container__header">

        <div class="container__header__top">


            <livewire:core.breadcrumb :$breadcrumbLinks />


        </div>
        <div class="container__header__bottom">
            <livewire:core.open-modal-button :text="__('modals.slide.actions.add')" variant="primary" icon="add" :$modalTitle :$modalContent
                :$containsTinyMce />
            <h2>@lang('pages.slider.titles.main', ['name' => $sliderName])</h2>
        </div>
    </div>
    <livewire:core.slides-table :$sliderId :$sliderableId :$sliderableType />
@endsection
