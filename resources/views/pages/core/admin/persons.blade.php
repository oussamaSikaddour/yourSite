@extends('layouts.core-layout')
@section('pageContent')
    <div class="container__header">

        <div class="container__header__top">
            <livewire:core.breadcrumb :$breadcrumbLinks />
        </div>
        <div class="container__header__bottom">
            <h2>@lang('pages.manage_persons.titles.main')</h2>
            <livewire:core.open-modal-button :text="__('modals.person.actions.add')" variant="primary" icon="add" :$modalTitle :$modalContent />
        </div>

    </div>




    <livewire:core.persons-table />
@endsection
