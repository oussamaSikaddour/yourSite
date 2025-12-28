@extends('layouts.core-layout')
@section('pageContent')
    <div class="container__header">

        <div class="container__header__top">
            <livewire:core.breadcrumb :$breadcrumbLinks />
        </div>

        <div class="container__header__bottom">
            <livewire:core.open-modal-button :text="__('modals.external_link.actions.add')" variant="primary" icon="add" :$modalTitle :$modalContent />

            <h2>@lang('pages.menu.titles.main', ['title' => $parameters['title']])</h2>

        </div>


    </div>


    <livewire:core.admin.external-links-table :menuId="$parameters['id']" />
@endsection
