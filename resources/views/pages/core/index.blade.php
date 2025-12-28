@extends('layouts.app-layout')
@section('pageContent')
    <livewire:app.landing-page.sections.hero-carousel :$hero />
    <livewire:app.landing-page.sections.hero :$hero />
    <livewire:app.landing-page.sections.about-us />
    <livewire:app.landing-page.sections.services :$services />
    <livewire:app.landing-page.sections.contact-us />
@endsection
