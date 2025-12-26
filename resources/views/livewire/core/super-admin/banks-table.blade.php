<div class="table__container" x-on:update-banks-table.window="$wire.$refresh()">
    <div class="table__header">
        <h3>@lang('tables.banks.info')</h3>
        <div class="table__header__actions">


            <x-core.button icon="filter" rounded=true hasTooltip=true :tooltip="__('toolTips.common.filters')" :extraClasses="['table__filters__btn']" />

            <x-core.form.selector htmlId="TP-upp" model="perPage" :data="$perPageOptions" type="filter" :tooltip="__('toolTips.common.per_page')" />
        </div>
    </div>

    <div class="table__filters" wire:ignore>
        <div class="form__container">
            <form class="form">
                <div class="row">
                    <x-core.form.input model="acronym" :label="__('tables.banks.acronym')" type="text" html_id="bAcro"
                        role="filter" />
                    <x-core.form.input model="code" :label="__('tables.banks.code')" type="text" html_id="bCode" role="filter" />
                </div>
                <div class="row">
                    <x-core.form.input model="designation" :label="__('tables.banks.designation')" type="text" html_id="bDesignation"
                        role="filter" />
                </div>
                <div class="form__actions">



                    <x-core.button hasTooltip=true :tooltip="__('toolTips.common.resetFilters')" type="submit" variant="primary"
                        function="resetFilters" prevent=true rounded=true icon="refresh" />
                </div>
            </form>
        </div>
    </div>


    @if (isset($this->banks) && $this->banks->isNotEmpty())
        <div class="table__body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="column">
                            <div>@lang('tables.common.actions')</div>
                        </th>
                        <x-core.table.sortable-th wire:key="bT-TH-1" model="acronym" :label="__('tables.banks.acronym')" :$sortDirection
                            :$sortBy />
                        <x-core.table.sortable-th wire:key="bT-TH-2" model="code" :label="__('tables.banks.code')" :$sortDirection
                            :$sortBy />
                        <x-core.table.sortable-th wire:key="bT-TH-3" model="designation_fr" :label="__('tables.banks.designation_fr')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="bT-TH-4" model="designation_ar" :label="__('tables.banks.designation_ar')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="bT-TH-5" model="designation_en" :label="__('tables.banks.designation_en')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="bt-TH-6" model="created_at" :label="__('tables.banks.created_at')"
                            :$sortDirection :$sortBy />

                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->banks as $bank)
                        <tr wire:key="{{ $bank->id }}-pr">
                            <td>
                                <x-core.button
                                variant="danger"
                                icon="delete"
                                function="openDeleteDialog"
                                :parameters="[$bank]"
                                rounded=true hasTooltip=true
                                :tooltip="__('toolTips.bank.delete')"
                                />


                                <livewire:core.open-modal-button wire:key="edit-Me-{{ $bank->id }}" rounded=true
                                    hasTooltip=true icon="edit" :tooltip="__('toolTips.bank.update')"
                                    modalTitle="modals.bank.actions.update"
                                     :modalContent="[
                                        'name' => 'core.super-admin.bank-modal',
                                        'parameters' => ['id' => $bank->id],
                                    ]"
                                    />

                            </td>
                            <td scope="row">{{ $bank->acronym }}</td>
                            <td>{{ $bank->code }}</td>
                            <td>{{ $bank->designation_fr }}</td>
                            <td>{{ $bank->designation_ar }}</td>
                            <td>{{ $bank->designation_en }}</td>
                            <td>{{ $bank->created_at->format('Y-m-d') }}</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $this->banks->links('components.core.pagination') }}
    @else
        <div class="table__footer">
            <h2>@lang('tables.banks.not_found')</h2>
        </div>
    @endif
</div>
