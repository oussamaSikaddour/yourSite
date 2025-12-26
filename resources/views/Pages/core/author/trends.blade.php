@extends('layouts.core-layout')
@section('pageContent')
    <div class="container__header">
        <div class="container__header__top">


            <livewire:core.open-modal-button :text="__('modals.trend.actions.add')" variant="primary" icon="add" :$modalTitle :$modalContent
                :$containsTinyMce />
            <h2>@lang('pages.trends.titles.main')</h2>
        </div>
    </div>
    <livewire:core.author.trends-table />
@endsection
