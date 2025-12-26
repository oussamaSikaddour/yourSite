@extends('layouts.core-layout')
@section('pageContent')
    <div class="container__header">

        <div class="container__header__top">
            <livewire:core.breadcrumb :$breadcrumbLinks />
        </div>
        <div class="container__header__bottom">
            <h2>@lang('pages.manage_about_us.titles.main')</h2>
            <livewire:core.open-modal-button :text="__('modals.our_quality.actions.new')" variant="primary" icon="add" :$modalTitle :$modalContent />

        </div>

    </div>

    <livewire:core.super-admin.our-qualities-table />
@endsection
