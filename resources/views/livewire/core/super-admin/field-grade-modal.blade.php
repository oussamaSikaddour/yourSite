<div class="modal__body__content">
    <!-- Form Section -->



    <form class="table__form">

        <div class="table__form__inputs">
            <div class="row">
                <x-core.form.input model="{{ $form }}.designation_fr" :label="__('forms.field_grade.designation_fr')" type="text"
                    html_id="MFG-bfr" />
                <x-core.form.input model="{{ $form }}.designation_ar" :label="__('forms.field_grade.designation_ar')" type="text"
                    html_id="MFG-bAr" />
                <x-core.form.input model="{{ $form }}.designation_en" :label="__('forms.field_grade.designation_en')" type="text"
                    html_id="MFG-bEN" />
            </div>
            <div class="row">
                <x-core.form.input model="{{ $form }}.acronym" :label="__('forms.field_grade.acronym')" type="text"
                    html_id="MFG-ac" />
            </div>
        </div>
        <!-- Form Buttons Container -->
        <div class="table__form__actions">

            <div wire:loading wire:target="handleSubmit">
                <x-core.loading />
            </div>

            {{-- Submit Button --}}
            <x-core.button hasTooltip=true :icon="$form === 'addForm' ? 'add' : 'edit'" :tooltip="$form === 'addForm' ? __('toolTips.common.add') : __('toolTips.common.update')" variant="primary" rounded=true
                function="handleSubmit" prevent=true />

            {{-- Reset Button --}}
            <x-core.button hasTooltip=true icon="refresh" :tooltip="__('toolTips.common.reset')" variant="warning" rounded=true
                function="resetForm" prevent=true />




        </div>
    </form>

    <!-- Form Buttons Container -->


    <!-- Table Section -->
    <div class="table__container" x-on:update-field-grades-table.window="$wire.$refresh()">
        <div class="table__header">
            <h3>@lang('tables.field_grades.info', ['acronym' => "$fieldAcronym"])</h3>
            <div class="table__header__actions">

                <span wire:loading wire:target="excelFile">
                    <x-core.loading />
                </span>
                <x-core.file-input icon="upload" :tooltip="__('toolTips.field_grade.manage.upload')" model="excelFile" types="excel" type="icon_only" />


                <x-core.button icon="export" function="generateEmptyFieldGradesExcel" rounded=true hasTooltip=true
                    :tooltip="__('toolTips.field_grade.excel.empty')" />


                <x-core.button variant="success" icon="export" function="generateFieldGradesExcel" rounded=true
                    hasTooltip=true :tooltip="__('toolTips.field_grade.excel.empty')" />



            </div>
        </div>

        @if (isset($this->fieldGrades) && $this->fieldGrades->isNotEmpty())
            <div class="table__body">
                <table class="table">
                    <thead>
                        <tr>
                            <th></th>
                            <th scope="column">
                                <div>@lang('tables.common.actions')</div>
                            </th>
                            <x-core.table.sortable-th wire:key="FTG-Th-1" model="acronym" :label="__('tables.field_grades.acronym')"
                                :$sortDirection :$sortBy />
                            <x-core.table.sortable-th wire:key="FTG-Th-2" model="designation_fr" :label="__('tables.field_grades.designation_fr')"
                                :$sortDirection :$sortBy />
                            <x-core.table.sortable-th wire:key="FTG-Th-3" model="designation_en" :label="__('tables.field_grades.designation_en')"
                                :$sortDirection :$sortBy />
                            <x-core.table.sortable-th wire:key="FTG-Th-4" model="designation_ar" :label="__('tables.field_grades.designation_ar')"
                                :$sortDirection :$sortBy />
                            <x-core.table.sortable-th wire:key="FTG-Th-4" model="created_at" :label="__('tables.field_grades.registration_date')"
                                :$sortDirection :$sortBy />



                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($this->fieldGrades as $fieldG)
                            <tr wire:key="{{ $fieldG->id }}" scope="row">

                                <td>
                                    <x-core.form.radio-button model="selectedChoice"
                                        htmlId="{{ 'fgM-id' . $fieldG->id }}" value="{{ $fieldG->id }}"
                                        type="forTable" wire:key="{{ 'fgM-key-' . $fieldG->id }}" />
                                </td>
                                <td>

                                    <x-core.button variant="danger" icon="delete" function="openDeleteDialog"
                                        :parameters="[$fieldG]" rounded=true hasTooltip=true :tooltip="__('toolTips.field_grade.delete')" />

                                </td>
                                <td>{{ $fieldG->acronym }}</td>
                                <td>{{ $fieldG->designation_fr }}</td>
                                <td>{{ $fieldG->designation_en }}</td>
                                <td>{{ $fieldG->designation_ar }}</td>

                                <td>{{ $fieldG->created_at->format('Y-m-d') }}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $this->fieldGrades->links('components.core.pagination') }}
        @else
            <div class="table__footer">
                <h2>
                    @lang('tables.field_grades.not_found')
                </h2>
            </div>
        @endif
    </div>
</div>
