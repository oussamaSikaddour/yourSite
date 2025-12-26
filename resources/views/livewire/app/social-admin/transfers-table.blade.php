<div class="table__container" x-on:update-transfers-table.window="$wire.$refresh()">
    <div class="table__header">
        <h3>@lang('tables.transfers.info', ['motive' => $motive])</h3>
        <div class="table__header__actions">

            <span wire:loading wire:target="excelFile">
                <x-core.loading />
            </span>
            <x-core.file-input icon="upload" :tooltip="__('toolTips.transfer.manage.transfers')" model="excelFile" types="excel" type="icon_only" />


            <x-core.button hasTooltip=true :tooltip="__('toolTips.transfer.generate')" icon="edi" function="generateEDI" />




            <x-core.button hasTooltip=true :tooltip="__('toolTips.transfer.transfers')" icon="transfer" function="openAddTransfersDialog" />



            <x-core.button icon="filter" rounded=true hasTooltip=true :tooltip="__('toolTips.common.filters')" :extraClasses="['table__filters__btn']" />

            <x-core.form.selector htmlId="TP-upp" model="perPage" :data="$perPageOptions" type="filter" :tooltip="__('toolTips.common.per_page')" />
        </div>
    </div>

    <div class="table__filters" wire:ignore>
        <div class="form__container">
            <form class="form">
                <div class="row">
                    <x-core.form.input model="fullName" :label="__('tables.transfers.fullName')" type="text" html_id="TTfullN"
                        role="filter" />
                    <x-core.form.input model="account" :label="__('tables.transfers.account')" type="text" html_id="TTAccunt"
                        role="filter" />
                    <x-core.form.selector htmlId="TTEb" model="bank" :label="__('tables.transfers.bank')" :data="$banksOptions"
                        type="filter" />
                </div>

                <div class="form__actions">



                    <x-core.button hasTooltip=true :tooltip="__('toolTips.common.resetFilters')" type="submit" variant="primary"
                        function="resetFilters" prevent=true rounded=true icon="refresh" />
                </div>
            </form>
        </div>
    </div>
    @if (isset($this->transfers) && $this->transfers->isNotEmpty())


        <div class="table__body">
            <table>
                <thead>
                    <tr>

                        <th></th>

                        <th scope="column">
                            <div>@lang('tables.common.actions')</div>
                        </th>
                        <x-core.table.sortable-th wire:key="trant-TH-1" model="beneficiary" :label="__('tables.transfers.beneficiary')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="trant-TH-2" model="bank" :label="__('tables.transfers.bank')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="trant-TH-3" model="account" :label="__('tables.transfers.account')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="trant-TH-4" model="amount" :label="__('tables.transfers.amount')"
                            :$sortDirection :$sortBy />

                    </tr>
                </thead>

                <tbody>
                    @foreach ($this->transfers as $index => $tb)
                        <tr wire:key="{{ $tb->id }}-gt">
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <x-core.button variant="danger" icon="delete" function="openDeleteDialog"
                                    :parameters="[$tb]" rounded=true hasTooltip=true :tooltip="__('toolTips.transfer.delete')" />
                                <livewire:core.open-modal-button
                                   wire:key="edit-transfer-{{ $transfer->id }}"
                                    rounded=true
                                    hasTooltip=true
                                    :tooltip="__('toolTips.transfer.update')"
                                     icon="edit"
                                    modalTitle="modals.transfer.actions.update"
                                    :modalContent="[
                                        'name' => 'app.social-admin.transfer-modal',
                                        'parameters' => ['id' => $tb->id],
                                    ]"
                                    />

                            </td>
                            <td scope="row">{{ $tb->beneficiary }}</td>
                            <td>{{ $tb->bank }}</td>
                            <td>{{ $tb->account }}</td>
                            <td>{{ $tb->amount }}</td>

                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
        {{ $this->transfers->links('components.default.pagination') }}
    @else
        <div class="table__footer">
            <h2>
                @lang('tables.transfers.not_found')
            </h2>
        </div>
    @endif
</div>
