@extends('layouts.core-layout')
@section('pageContent')
    <div class="container__header">

        <div class="container__header__top">
            <livewire:core.breadcrumb :$breadcrumbLinks />
        </div>
        <div class="container__header__bottom ">
            <div class="row ">


                <div class="column ">

                    <livewire:core.open-modal-button :text="__('modals.person.actions.add')" variant="primary" icon="add" :modalTitle="$modalTitle3"
                        :modalContent="$modalContent3" :modalTitleOptions="$modalTitleOptions3" />


                    <livewire:core.open-modal-button :text="__('modals.banking_info.actions.add')" variant="primary" icon="add" :modalTitle="$modalTitle2"
                        :modalContent="$modalContent2" />



                    <livewire:core.open-modal-button :text="__('modals.global_transfer.actions.add')" variant="primary" icon="add" :$modalTitle
                        :$modalContent />

                </div>

                <h2>@lang('pages.manage_social_works.name')</h2>
            </div>




        </div>

    </div>
    <div class="grid">
        <div class="grid__slot">
            <livewire:core.persons-table />
        </div>
        <div class="grid__divider" role="separator" aria-orientation="vertical" tabindex="0"></div>
        <div class="grid__slot">
            <livewire:app.social-admin.global-transfers-table />
        </div>
    @endsection
