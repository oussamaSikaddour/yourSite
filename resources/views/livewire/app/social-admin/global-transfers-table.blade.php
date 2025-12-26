<div class="table__container" x-on:update-global-transfers-table.window="$wire.$refresh()">
    <div class="table__header">
        <h3>@lang('tables.global_transfers.info')</h3>
        <div class="table__header__actions">


            <x-core.button icon="filter" rounded=true hasTooltip=true :tooltip="__('toolTips.common.filters')" :extraClasses="['table__filters__btn']" />

            <x-core.form.selector htmlId="TP-upp" model="perPage" :data="$perPageOptions" type="filter" :tooltip="__('toolTips.common.per_page')" />
        </div>
    </div>

    <div class="table__filters" wire:ignore>
        <div class="form__container">
            <form class="form">

                <div class="row">
                    <x-core.form.input model="date_min" :label="__('tables.global_transfers.date_min')" type="date" html_id="TGTDateM"
                        role="filter" />
                    <x-core.form.input model="date_max" :label="__('tables.global_transfers.date_max')" type="date" html_id="TGTDateMa"
                        role="filter" />
                </div>
                <div class="row">
                    <x-core.form.input model="number" :label="__('tables.global_transfers.number')" type="number" html_id="TGTNumber"
                        role="filter" />
                    <x-core.form.input model="motive" :label="__('tables.global_transfers.motive')" type="text" html_id="TGTMotive"
                        role="filter" />
                    <x-core.form.input model="total_amount" :label="__('tables.global_transfers.total_amount')" type="money" html_id="TGTNumber"
                        role="filter" />

                </div>
                <div class="form__actions">



                    <x-core.button hasTooltip=true :tooltip="__('toolTips.common.resetFilters')" type="submit" variant="primary"
                        function="resetFilters" prevent=true rounded=true icon="refresh" />
                </div>
            </form>
        </div>
    </div>


    @if (isset($this->globalTransfers) && $this->globalTransfers->isNotEmpty())


        <div class="table__body">
            <table>
                <thead>
                    <tr>
                        <th scope="column">
                            <div>@lang('tables.common.actions')</div>
                        </th>

                        <x-core.table.sortable-th wire:key="gtT-TH-1" model="number" :label="__('tables.global_transfers.number')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="gtT-TH-2" model="number" :label="__('tables.global_transfers.date')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="gtT-TH-3" model="reference" :label="__('tables.global_transfers.reference')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="gtT-TH-4" model="total_amount" :label="__('tables.global_transfers.total_amount')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="gtT-TH-5" model="motive" :label="__('tables.global_transfers.motive')"
                            :$sortDirection :$sortBy :appLocal=true />
                        <x-core.table.sortable-th wire:key="gtT-TH-5" model="initiatorColumn" :label="__('tables.global_transfers.initiator')"
                            :$sortDirection :$sortBy :appLocal=true />


                        <x-core.table.sortable-th wire:key="gtT-TH-7" model="created_at" :label="__('tables.global_transfers.created_at')"
                            :$sortDirection :$sortBy />


                    </tr>
                </thead>

                <tbody>
                    @foreach ($this->globalTransfers as $gb)
                        <tr wire:key="{{ $gb->id }}-gt">
                            <td>



                                <x-core.button variant="danger" icon="delete" function="openDeleteDialog"
                                    :parameters="[$gb]" rounded=true hasTooltip=true :tooltip="__('toolTips.global_transfer.delete')" />
                                <livewire:core.open-modal-button wire:key="edit-GB-{{ $global_transfer->id }}"
                                    rounded=true hasTooltip=true icon="edit" :tooltip="__('toolTips.global_transfer.update')"
                                    modalTitle="modals.global_transfer.actions.update" :modalContent="[
                                        'name' => 'app.social-admin.global-transfer-modal',
                                        'parameters' => ['id' => $gb->id],
                                    ]" />

                                <x-core.button icon="view" variant="info" route="global_transfers_details_route"
                                    :routeParameters="[
                                        'id' => $gb->id,
                                        'motive' => $gb->motive,
                                    ]" rounded=true hasTooltip=true :tooltip="__('toolTips.slider.manage')" />

                            </td>
                            <td scope="row">{{ $gb->number }}</td>
                            <td>{{ $gb->date }}</td>
                            <td>{{ $gb->reference }}</td>
                            <td>{{ $gb->total_amount }}</td>
                            <td>{{ $gb->localized_motive }}</td>
                            <td>{{ $gb->initiator }}</td>
                            <td>{{ $gb->created_at->format('Y-m-d') }}</td>

                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
        {{ $this->globalTransfers->links('components.default.pagination') }}
    @else
        <div class="table__footer">
            <h2>
                @lang('tables.global_transfers.not_found')
            </h2>
        </div>
    @endif
</div>


