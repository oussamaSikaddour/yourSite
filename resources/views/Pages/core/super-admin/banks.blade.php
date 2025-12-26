@extends('layouts.core-layout')
@section('pageContent')
    <div class="container__header">



        <div class="container__header__top">

            <livewire:core.open-modal-button :text="__('modals.bank.actions.add')" variant="primary" icon="add" :$modalTitle :$modalContent />
            <h2>@lang('pages.banks.titles.main')</h2>

        </div>

    </div>

    <livewire:core.super-admin.banks-table />
@endsection
