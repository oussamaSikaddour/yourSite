@extends('layouts.core-layout')
@section('pageContent')
    <div class="container__header">

        <div class="container__header__top">
            <livewire:core.breadcrumb :$breadcrumbLinks />
        </div>
        <div class="container__header__bottom">
            <livewire:core.open-modal-button :text="__('modals.daira.actions.add')" variant="primary" icon="add" :$modalTitle :$modalContent />
            <h2>@lang('pages.wilaya.titles.main', ['name' => $parameters['name']])</h2>

        </div>

    </div>


    <livewire:core.super-admin.dairates-table :wilayaId="$parameters['id']" :wilayaName="$parameters['name']" />
@endsection
