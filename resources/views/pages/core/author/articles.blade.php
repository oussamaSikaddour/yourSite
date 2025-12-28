@extends('layouts.core-layout')
@section('pageContent')
    <div class="container__header">

        <div class="container__header__top">
            <h2>@lang('pages.articles.titles.main')</h2>



            <livewire:core.open-modal-button :text="__('modals.article.actions.add', $modalTitleOptions)" variant="primary" icon="add" :$modalTitle :$modalContent
                :$modalTitleOptions :$containsTinyMce />
        </div>
    </div>
    <livewire:core.author.articles-table :$articleableId :$articleableType />
@endsection
