@extends('layouts.core-layout')
@section('pageContent')
    <div class="container__header">

        <div class="container__header__top">
            <livewire:core.open-modal-button :text="__('modals.wilaya.actions.add')" variant="primary" icon="add" :$modalTitle :$modalContent />
            <h2>@lang('pages.wilayates.titles.main')</h2>

        </div>



    </div>


    <livewire:core.super-admin.wilayates-table />
@endsection
