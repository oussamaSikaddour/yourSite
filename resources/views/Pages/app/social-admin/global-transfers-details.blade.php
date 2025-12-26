@extends("layouts.core-layout")
@section("pageContent")

   <div class="container__header">
<h2>@lang("pages.global_transfer_details.titles.main",["motive"=>$parameters['motive']])</h2>



    <livewire:core.open-modal-button
     :text="__('modals.transfer.actions.add')"
     variant="primary"
     icon="add"
     :$modalTitle
     :$modalContent
 />

   </div>

 <livewire:app.social-admin.bonuses-table
 :simplisticView="true"
 />
 <livewire:app.social-admin.transfers-table
 :globalTransferId="$parameters['id']"
 :motive="$parameters['motive']"
 />
@endsection
