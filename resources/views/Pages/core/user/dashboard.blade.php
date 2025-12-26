@extends('layouts.core-layout')
@section('pageContent')
    <div class="container__header">

        <div class="container__header__top">

            <h2>@lang('pages.dashboard.titles.main', ['name' => $userName])</h2>
        </div>

    </div>



    <section class="dashboard">
        @can('super-admin-access')
            <x-core.dashboard-link route="general_infos" img="general-info" :label="__('pages.general_infos.name')" />
            <x-core.dashboard-link route="manage_hero" img="manage-hero" :label="__('pages.manage_hero.name')" />
            <x-core.dashboard-link route="manage_about_us" img="manage-about-us" :label="__('pages.manage_about_us.name')" />
            <x-core.dashboard-link route="manage_our_qualities" img="manage-our-qualities" :label="__('pages.manage_our_qualities.name')" />
        @endcan

        @canany(['admin-access', 'super-admin-access'])
            <x-core.dashboard-link route="users_route" img="manage-users" :label="__('pages.manage_users.name')" />
        @endcanany
        @can('admin-access')
            <x-core.dashboard-link route="persons_route" img="manage-persons" :label="__('pages.manage_persons.name')" />
        @endcan

        @canany(['admin-access', 'author-access'])
            <x-core.dashboard-link route="services_route" app="true" img="manage-services" :label="__('pages.services.name')" />
        @endcanany
        @can('social-admin-access')
            <x-core.dashboard-link route="bonuses_route" app="true" img="manage-bonuses" :label="__('pages.bonuses.name')" />
            <x-core.dashboard-link route="social_works_route" app="true" img="manage-social-works" :label="__('pages.manage_social_works.name')" />
        @endcan

    </section>
@endsection
