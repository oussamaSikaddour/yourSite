@extends('layouts.core-layout')
@section('pageContent')
    <div class="container__header">

        <div class="container__header__top">

            @if (count($breadcrumbLinks) > 0)
                <livewire:core.breadcrumb :$breadcrumbLinks />
            @endif

        </div>

        <div class="container__header__bottom">


            <h2>@lang('pages.service.titles.main', $modalTitleOptions)</h2>



            <livewire:core.open-modal-button :text="__('modals.slider.actions.add', $modalTitleOptions)" variant="primary" icon="add" :modalTitle="$sliderModalTitle"
                :$modalTitleOptions :modalContent="$sliderModalContent" />

            <livewire:core.open-modal-button :text="__('modals.article.actions.add', $modalTitleOptions)" variant="primary" icon="add" :modalTitle="$articleModalTitle"
                :modalContent="$articleModalContent" :$modalTitleOptions :$containsTinyMce />
        </div>
    </div>

    <livewire:core.sliders-table :$sliderableId :$sliderableType />

    <livewire:core.author.articles-table :$articleableId :$articleableType />
@endsection
