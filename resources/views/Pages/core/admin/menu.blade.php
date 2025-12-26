@extends('layouts.core-layout')
@section('pageContent')
    <div class="container__header">


        <div class="container__header__top">
            <x-core.button variant="primary" icon="previous" route="menus_route" rounded=true hasTooltip=true
                :tooltip="__('toolTips.common.previous.page')" />
            <livewire:core.open-modal-button :text="__('modals.external_link.actions.add')" variant="primary" icon="add" :$modalTitle :$modalContent />


            <h2>@lang('pages.menu.titles.main', ['title' => $parameters['title']])</h2>

        </div>


    </div>


    <livewire:core.admin.external-links-table :menuId="$parameters['id']" />
@endsection
