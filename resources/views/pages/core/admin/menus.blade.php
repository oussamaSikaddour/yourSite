@extends('layouts.core-layout')
@section('pageContent')
    <div class="container__header">

        <div class="container__header__top">
            <livewire:core.open-modal-button :text="__('modals.menu.actions.add')" variant="primary" icon="add" :$modalTitle :$modalContent />

            <h2>@lang('pages.menus.titles.main')</h2>

        </div>


    </div>


    <livewire:core.admin.menus-table />
@endsection
