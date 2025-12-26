<div class="table__container" x-on:update-wilayates-table.window="$wire.$refresh()">
    <div class="table__header">
        <h3>@lang('tables.wilayas.info')</h3>
        <div class="table__header__actions">
            @canany(['super-admin-access', 'admin-access'])
                <span wire:loading wire:target="excelFile">
                    <x-core.loading />
                </span>
                <x-core.file-input icon="upload" :tooltip="__('toolTips.wilaya.manage.wilayas')" model="excelFile" types="excel" type="icon_only" />


                <x-core.button icon="export" function="generateEmptyWilayasExcel" rounded=true hasTooltip=true
                    :tooltip="__('toolTips.wilaya.excel.empty')" />


                <x-core.button variant="success" icon="export" function="generateWilayasExcel" rounded=true hasTooltip=true
                    :tooltip="__('toolTips.wilaya.excel.empty')" />
            @endcanany


            <x-core.button icon="filter" rounded=true hasTooltip=true :tooltip="__('toolTips.common.filters')" :extraClasses="['table__filters__btn']" />

            <x-core.form.selector htmlId="TP-upp" model="perPage" :data="$perPageOptions" type="filter" :tooltip="__('toolTips.common.per_page')" />
        </div>
    </div>

    <div class="table__filters" wire:ignore>
        <div class="form__container">
            <form class="form">

                <div class="row">
                            <x-core.form.input
            model="designation"
            :label="__('tables.wilayates.designation')"
            type="text"
            html_id="FTDesignation"
            role="filter"
        />

        <x-core.form.input
            model="code"
            :label="__('tables.wilayates.code')"
            type="text"
            html_id="FTCode"
            role="filter"
        />
                </div>

                <div class="form__actions">



                    <x-core.button hasTooltip=true :tooltip="__('toolTips.common.resetFilters')" type="submit" variant="primary"
                        function="resetFilters" prevent=true rounded=true icon="refresh" />
                </div>
            </form>
        </div>
    </div>


    @if (isset($this->wilayates) && $this->wilayates->isNotEmpty())
        <div class="table__body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="column">
                            <div>@lang('tables.common.actions')</div>
                        </th>
                        <x-core.table.sortable-th wire:key="WT-Th-1" model="code" :label="__('tables.wilayates.code')" :$sortDirection
                            :$sortBy />
                        <x-core.table.sortable-th wire:key="WT-Th-2" model="designation_fr" :label="__('tables.wilayates.designation_fr')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="WT-Th-3" model="designation_en" :label="__('tables.wilayates.designation_en')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="WT-Th-4" model="designation_ar" :label="__('tables.wilayates.designation_ar')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="WT-Th-5" model="created_at" :label="__('tables.wilayates.registration_date')"
                            :$sortDirection :$sortBy />

                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->wilayates as $wilaya)
                        <tr wire:key="{{ $wilaya->id }}" scope="row">
                            <td>
                                <x-core.button variant="danger" icon="delete" function="openDeleteDialog"
                                    :parameters="[$wilaya]" rounded=true hasTooltip=true :tooltip="__('toolTips.wilaya.delete')" />


                                <livewire:core.open-modal-button wire:key="edit-w-{{ $wilaya->id }}" rounded=true
                                    hasTooltip=true :tooltip="__('toolTips.wilaya.update')" icon="edit"
                                    modalTitle="modals.wilaya.actions.update" :modalTitleOptions="['code' => $wilaya->code]"
                                    :modalContent="[
                                        'name' => 'core.super-admin.wilaya-modal',
                                        'parameters' => ['id' => $wilaya->id],
                                    ]" />

                                <x-core.button
                                icon="view"
                                 variant="info"
                                  route="wilaya"
                                  :routeParameters="['id' => $wilaya->id, 'code' => $wilaya->code]"
                                    rounded=true hasTooltip=true :tooltip="__('toolTips.wilaya.manage.view')" />

                            </td>
                            <td>{{ $wilaya->code }}</td>
                            <td>{{ $wilaya->designation_fr }}</td>
                            <td>{{ $wilaya->designation_en }}</td>
                            <td>{{ $wilaya->designation_ar }}</td>
                            <td>{{ $wilaya->created_at->format('Y-m-d') }}</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $this->wilayates->links('components.core.pagination') }}
    @else
        <div class="table__footer">
            <h2>@lang('tables.wilayas.not_found')</h2>
        </div>
    @endif
</div>



