@extends('layouts.core-layout')
@section('pageContent')
    <div class="container__header">

        <div class="container__header__top">
            <h2>@lang('pages.messages.titles.main')</h2>
        </div>

    </div>

    <livewire:core.super-admin.visitors-messages-table />
@endsection
