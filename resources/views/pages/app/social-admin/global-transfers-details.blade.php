@extends('layouts.core-layout')
@section('pageContent')
    <div class="container__header">


        <div class="container__header__top">
            <livewire:core.breadcrumb :$breadcrumbLinks />
        </div>

        <div class="container__header__bottom">
            <h2>@lang('pages.global_transfer_details.titles.main', ['motive' => $parameters['motive']])</h2>



            <livewire:core.open-modal-button :text="__('modals.transfer.actions.add')" variant="primary" icon="add" :$modalTitle :$modalContent />



        </div>

    </div>
    <div class="grid">
        <div class="grid__slot">
            <livewire:app.social-admin.bonuses-table :simplisticView="true" />
        </div>
        <div class="grid__divider" role="separator" aria-orientation="vertical" tabindex="0"></div>
        <div class="grid__slot">
            <livewire:app.social-admin.transfers-table :globalTransferId="$parameters['id']" :motive="$parameters['motive']" />
        </div>
    </div>
@endsection
