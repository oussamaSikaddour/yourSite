<div class="table__container" x-on:update-transfers-table.window="$wire.$refresh()">
    <div class="table__header">
        <span wire:loading wire:target="selectedValues,selectAll">
            <x-core.loading />
        </span>

        <h3>@lang('tables.transfers.info', ['motive' => $motive])</h3>

        <div class="table__header__actions">

            @if (!empty($selectedValues))
                <x-core.button variant="danger" hasTooltip=true :tooltip="__('toolTips.transfer.delete_bulk')" icon="delete"
                    function="openDeleteBulkDialog" rounded="true" :disabled="empty($selectedValues)" />

                <x-core.button variant="danger" hasTooltip=true :tooltip="__('toolTips.transfer.empty_amount_bulk')" icon="zero"
                    function="openEmptyAmountBulkDialog" rounded="true" :disabled="empty($selectedValues)" />

                <x-core.button hasTooltip=true :tooltip="__('toolTips.transfer.bonuses')" icon="bonus" function="openAddBonusesDialog"
                    rounded="true" :disabled="empty($selectedValues)" />
            @endif


            <x-core.button hasTooltip=true :tooltip="__('toolTips.transfer.generate')" icon="wallet" function="generateEDI" rounded='true' />

            <span wire:loading wire:target="excelFile">
                <x-core.loading />
            </span>

            <x-core.file-input icon="upload" :tooltip="__('toolTips.transfer.excel.upload')" model="excelFile" types="excel" type="icon_only" />

            <x-core.button icon="filter" rounded=true hasTooltip=true :tooltip="__('toolTips.common.filters')" :extraClasses="['table__filters__btn']" />

            <x-core.form.selector htmlId="TP-upp" model="perPage" :data="$perPageOptions" type="filter"
                :tooltip="__('toolTips.common.per_page')" />
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
        @php
            // Option A: current page only
            $pageIds = $this->transfers->getCollection()->pluck('id')->all();
            $selectedValues = $selectedValues ?? [];
            $selectedOnPage = array_values(array_intersect($selectedValues, $pageIds));

            $allSelectedOnPage = count($pageIds) > 0 && count($selectedOnPage) === count($pageIds);
            $someSelectedOnPage = count($selectedOnPage) > 0 && count($selectedOnPage) < count($pageIds);
        @endphp

        <div class="table__body">
            <table class="table">
                <thead>
                    <tr>
                        <th></th>

                        {{-- Select All (current page only) --}}
                        <th x-data="{
                            setIndeterminate() {
                                const input = this.$el.querySelector('input');
                                if (!input) return;
                                input.indeterminate = @js($someSelectedOnPage);
                            }
                        }" x-init="setIndeterminate()" x-effect="setIndeterminate()">
                            {{-- boolean checkbox: use value=1 (avoid value=0 weirdness) --}}
                            <x-core.form.check-box model="selectAll" htmlId="tbSAll" value="1"
                                :live="true" />
                        </th>

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

                            {{-- Per-row checkbox (multi-select) --}}
                            <td>
                                <x-core.form.check-box model="selectedValues" htmlId="{{ 'tbkey' . $tb->id }}"
                                    value="{{ $tb->id }}" :live="true" />
                            </td>

                            <td>
                                <x-core.button variant="danger" icon="delete" function="openDeleteDialog"
                                    :parameters="[$tb]" rounded=true hasTooltip=true :tooltip="__('toolTips.transfer.delete')" />

                                <livewire:core.open-modal-button wire:key="edit-transfer-{{ $tb->id }}"
                                    rounded=true hasTooltip=true :tooltip="__('toolTips.transfer.update')" icon="edit"
                                    modalTitle="modals.transfer.actions.update" :modalTitleOptions="['name' => $tb->beneficiary]" :modalContent="[
                                        'name' => 'app.social-admin.transfer-modal',
                                        'parameters' => ['id' => $tb->id],
                                    ]" />
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

        {{ $this->transfers->links('components.core.pagination') }}
    @else
        <div class="table__footer">
            <h2>@lang('tables.transfers.not_found')</h2>
        </div>
    @endif
</div>
