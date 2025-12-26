<div class="table__container" x-on:update-dairates-table.window="$wire.$refresh()">
    <div class="table__header">
        <h3>@lang('tables.dairas.info')</h3>
        <div class="table__header__actions">
            @canany(['super-admin-access', 'admin-access'])
                <span wire:loading wire:target="excelFile">
                    <x-core.loading />
                </span>
                <x-core.file-input icon="upload" :tooltip="__('toolTips.daira.manage.dairates')" model="excelFile" types="excel" type="icon_only" />


                <x-core.button icon="export" function="generateEmptyDairatesExcel" rounded=true hasTooltip=true
                    :tooltip="__('toolTips.daira.excel.empty')" />


                <x-core.button variant="success" icon="export" function="generateDairatesExcel" rounded=true
                    hasTooltip=true :tooltip="__('toolTips.daira.excel.empty')" />
            @endcanany


            <x-core.button icon="filter" rounded=true hasTooltip=true :tooltip="__('toolTips.common.filters')" :extraClasses="['table__filters__btn']" />

            <x-core.form.selector htmlId="TP-upp" model="perPage" :data="$perPageOptions" type="filter" :tooltip="__('toolTips.common.per_page')" />
        </div>
    </div>

    <div class="table__filters" wire:ignore>
        <div class="form__container">
            <form class="form">
                <div class="row">
                    <x-core.form.input model="designation" :label="__('tables.dairates.designation')" type="text" html_id="FTDesignation"
                        role="filter" />
                    <x-core.form.input model="code" :label="__('tables.dairates.code')" type="text" html_id="FTCode"
                        role="filter" />
                </div>

                <div class="form__actions">



                    <x-core.button hasTooltip=true :tooltip="__('toolTips.common.resetFilters')" type="submit" variant="primary"
                        function="resetFilters" prevent=true rounded=true icon="refresh" />
                </div>
            </form>
        </div>
    </div>
    @if (isset($this->dairates) && $this->dairates->isNotEmpty())
        <div class="table__body">
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            <div>@lang('tables.common.actions')</div>
                        </th>
                        <x-core.table.sortable-th wire:key="DT-Th-1" model="code" :label="__('tables.dairates.code')" :$sortDirection
                            :$sortBy />
                        <x-core.table.sortable-th wire:key="DT-Th-2" model="designation_fr" :label="__('tables.dairates.designation_fr')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="DT-Th-3" model="designation_en" :label="__('tables.dairates.designation_en')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="DT-Th-4" model="designation_ar" :label="__('tables.dairates.designation_ar')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="DT-Th-5" model="created_at" :label="__('tables.dairates.registration_date')"
                            :$sortDirection :$sortBy />

                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->dairates as $daira)
                        <tr wire:key="daira-{{ $daira->id }}">

                            <td>
                                <x-core.button
                                variant="danger"
                                 icon="delete"
                                 function="openDeleteDialog"
                                    :parameters="[$daira]"
                                     rounded=true
                                      hasTooltip=true
                                     :tooltip="__('toolTips.daira.delete')" />
                                <livewire:core.open-modal-button wire:key="edit-da-{{ $daira->id }}" rounded=true
                                    hasTooltip=true icon="edit" :tooltip="__('toolTips.daira.update')"
                                    modalTitle="modals.daira.actions.update" :modalTitleOptions="['code' => $daira->code]" :modalContent="[
                                        'name' => 'core.super-admin.daira-modal',
                                        'parameters' => ['id' => $daira->id],
                                    ]" />
                                <livewire:core.open-modal-button wire:key="m-communes-{{ $daira->id }}" rounded=true
                                    hasTooltip=true icon="commune" :tooltip="__('toolTips.daira.manage.communes')"
                                    modalTitle="modals.daira.actions.manage.communes" :modalTitleOptions="['code' => $daira->code]"
                                    :modalContent="[
                                        'name' => 'core.super-admin.commune-modal',
                                        'parameters' => ['daira' => $daira],
                                    ]" />
                            </td>
                            <td>{{ $daira->code }}</td>
                            <td>{{ $daira->designation_fr }}</td>
                            <td>{{ $daira->designation_en }}</td>
                            <td>{{ $daira->designation_ar }}</td>
                            <td>{{ $daira->created_at->format('Y-m-d') }}</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $this->dairates->links('components.core.pagination') }}
    @else
        <div class="table__footer">
            <h2>@lang('tables.dairates.not_found')</h2>
        </div>
    @endif
</div>
