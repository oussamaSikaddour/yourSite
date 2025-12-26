@extends("layouts.core-layout")
@section("pageContent")

<div class="container__header">
<h2>@lang('pages.site_parameters.titles.main')</h2>
</div>
<div class="form__container small forMultiForm site-params-multi-form-step">
 <div class="forms spForms">
   <livewire:core.super-admin.site-parameters.first-step wire:key="sp-f-s"/>
   <livewire:core.super-admin.site-parameters.last-step wire:key="sp-l-s" />
</div>
</div>
@endsection
