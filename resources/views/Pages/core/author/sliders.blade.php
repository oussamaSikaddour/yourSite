@extends('layouts.core-layout')
@section('pageContent')
    <div class="container__header">

        <div class="container__header__top">


            <h2>@lang('pages.sliders.titles.main')</h2>



            <livewire:core.open-modal-button :text="__('modals.slider.actions.add', $modalTitleOptions)" variant="primary" icon="add" :$modalTitle
                :$modalTitleOptions :$modalContent />
        </div>
    </div>
    <livewire:core.sliders-table :$sliderableId :$sliderableType :$sliderableName />
@endsection
