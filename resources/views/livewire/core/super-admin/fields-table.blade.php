<div class="table__container" x-on:update-fields-table.window="$wire.$refresh()">
    <div class="table__header">
        <h3>@lang('tables.fields.info')</h3>
        <div class="table__header__actions">

            <span wire:loading wire:target="excelFile">
                <x-core.loading />
            </span>
            <x-core.file-input icon="upload" :tooltip="__('toolTips.field.manage.fields')" model="excelFile" types="excel" type="icon_only" />


            <x-core.button icon="export" function="generateEmptyFieldsExcel" rounded=true hasTooltip=true
                :tooltip="__('toolTips.field.excel.empty')" />


            <x-core.button variant="success" icon="export" function="generateFieldsExcel" rounded=true hasTooltip=true
                :tooltip="__('toolTips.field.excel.empty')" />



            <x-core.button icon="filter" rounded=true hasTooltip=true :tooltip="__('toolTips.common.filters')" :extraClasses="['table__filters__btn']" />

            <x-core.form.selector htmlId="TP-upp" model="perPage" :data="$perPageOptions" type="filter" :tooltip="__('toolTips.common.per_page')" />
        </div>
    </div>

    <div class="table__filters" wire:ignore>
        <div class="form__container">
            <form class="form">
                <div class="row">
                    <x-core.form.input model="designation" :label="__('tables.fields.designation')" type="text" html_id="FTDesignation"
                        role="filter" />
                    <x-core.form.input model="acronym" :label="__('tables.fields.acronym')" type="text" html_id="FTAcronym"
                        role="filter" />
                </div>

                <div class="form__actions">



                    <x-core.button hasTooltip=true :tooltip="__('toolTips.common.resetFilters')" type="submit" variant="primary"
                        function="resetFilters" prevent=true rounded=true icon="refresh" />
                </div>
            </form>
        </div>
    </div>
    @if (isset($this->fields) && $this->fields->isNotEmpty())
        <div class="table__body">
            <table>
                <thead>
                    <tr>
                        <th>
                            <div>@lang('tables.common.actions')</div>
                        </th>
                        <x-core.table.sortable-th wire:key="FT-Th-1" model="acronym" :label="__('tables.fields.acronym')" :$sortDirection
                            :$sortBy />
                        <x-core.table.sortable-th wire:key="FT-Th-2" model="designation_fr" :label="__('tables.fields.designation_fr')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="FT-Th-3" model="designation_en" :label="__('tables.fields.designation_en')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="FT-Th-4" model="designation_ar" :label="__('tables.fields.designation_ar')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="FT-Th-5" model="created_at" :label="__('tables.fields.registration_date')"
                            :$sortDirection :$sortBy />

                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->fields as $field)
                        <tr wire:key="field-{{ $field->id }}">
                            <td>

                                <x-core.button variant="danger" icon="delete" function="openDeleteDialog"
                                    :parameters="[$field]" rounded=true hasTooltip=true :tooltip="__('toolTips.field.delete')" />


                                <livewire:core.open-modal-button wire:key="edit-F-{{ $field->id }}" rounded=true
                                    hasTooltip=true icon="edit" :tooltip="__('toolTips.field.update')"
                                    modalTitle="modals.field.actions.update" :modalTitleOptions="['acronym' => $field->acronym]" :modalContent="[
                                        'name' => 'core.super-admin.field-modal',
                                        'parameters' => ['id' => $field->id],
                                    ]" />
                                <livewire:core.open-modal-button wire:key="manage-G-{{ $field->id }}" rounded=true
                                    hasTooltip=true icon="education" :tooltip="__('toolTips.field.manage.grades')"
                                    modalTitle="modals.field.actions.manage.grades" :modalTitleOptions="['acronym' => $field->acronym]"
                                    :modalContent="[
                                        'name' => 'core.super-admin.field-grade-modal',
                                        'parameters' => ['field' => $field],
                                    ]" />
                                <livewire:core.open-modal-button wire:key="manage-Spe-{{ $field->id }}" rounded=true
                                    hasTooltip=true icon="exam" :tooltip="__('toolTips.field.manage.specialties')"
                                    modalTitle="modals.field.actions.manage.specialties" :modalTitleOptions="['acronym' => $field->acronym]"
                                    :modalContent="[
                                        'name' => 'core.super-admin.field-specialty-modal',
                                        'parameters' => ['field' => $field],
                                    ]" />
                            </td>
                            <td>{{ $field->acronym }}</td>
                            <td>{{ $field->designation_fr }}</td>
                            <td>{{ $field->designation_en }}</td>
                            <td>{{ $field->designation_ar }}</td>
                            <td>{{ $field->created_at->format('Y-m-d') }}</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $this->fields->links('components.core.pagination') }}
    @else
        <div class="table__footer">
            <h2>@lang('tables.fields.not_found')</h2>
        </div>
    @endif
</div>
