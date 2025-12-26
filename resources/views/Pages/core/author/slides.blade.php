@extends('layouts.core-layout')
@section('pageContent')
    <div class="container__header">

        <div class="container__header__top">


            <x-core.button variant="primary" icon="back" route="sliders_route" :routeParameters="[
                'sliderableId' => $sliderableId,
                'sliderableType' => $sliderableType,
                'sliderableName' => $sliderableName,
            ]" rounded=true
                hasTooltip=true :tooltip="__('toolTips.common.previous.page')" />

            <livewire:core.open-modal-button :text="__('modals.slide.actions.add')" variant="primary" icon="add" :$modalTitle :$modalContent
                :$containsTinyMce />
            <h2>@lang('pages.slider.titles.main', ['name' => $sliderName])</h2>
        </div>
    </div>
    <livewire:core.slides-table :$sliderId :$sliderableId :$sliderableType />
@endsection
