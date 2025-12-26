<div class="table__container" x-on:update-persons-table.window="$wire.$refresh()">
    <div class="table__header">
        <h3>@lang('tables.persons.info')</h3>
        <div class="table__header__actions">
            @canany(['super-admin-access', 'admin-access'])
                <span wire:loading wire:target="excelFile">
                    <x-core.loading />
                </span>
                <x-core.file-input icon="upload" :tooltip="__('toolTips.person.manage.persons')" model="excelFile" types="excel" type="icon_only" />


                <x-core.button
                icon="export"
                function="generateEmptyPersonsExcel"
                rounded=true
                hasTooltip=true
                :tooltip="__('toolTips.person.excel.empty')" />


                <x-core.button
                variant="success"
                icon="export"
                function="generatePersonsExcel"
                rounded=true
                hasTooltip=true
                :tooltip="__('toolTips.person.excel.empty')" />
            @endcanany


            <x-core.button
            icon="filter"
            rounded=true
            hasTooltip=true
            :tooltip="__('toolTips.common.filters')"
             :extraClasses="['table__filters__btn']"
              />

            <x-core.form.selector htmlId="TP-upp" model="perPage" :data="$perPageOptions" type="filter" :tooltip="__('toolTips.common.per_page')" />
        </div>
    </div>

    <div class="table__filters" wire:ignore>
        <div class="form__container">
            <form class="form">
                <div class="row">
                    <x-core.form.input model="fullName" :label="__('tables.persons.name')" type="text" html_id="p-NameUT"
                        role="filter" />
                    <x-core.form.input model="email" :label="__('tables.persons.email')" type="text" html_id="p-EmailUT"
                        role="filter" />
                </div>
                <div class="row">
                    <x-core.form.input model="employeeNumber" :label="__('tables.persons.employee_number')" type="text" html_id="p-EmployeeUT"
                        role="filter" />
                </div>

                <div class="form__actions">



                    <x-core.button hasTooltip=true :tooltip="__('toolTips.common.resetFilters')" type="submit" variant="primary"
                        function="resetFilters" prevent=true rounded=true icon="refresh" />
                </div>
            </form>
        </div>
    </div>

    @if ($this->persons->isNotEmpty())
        <div class="table__body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="column">
                            <div>@lang('tables.common.actions')</div>
                        </th>
                        <x-core.table.sortable-th wire:key="P-TH-1" model="employee_number" :label="__('tables.persons.employee_number')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="P-TH-2" model="full_name_fr" :label="__('tables.persons.full_name_fr')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="P-TH-11" model="full_name_ar" :label="__('tables.persons.full_name_ar')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="P-TH-3" model="user_email" :label="__('tables.persons.email')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="P-TH-13" model="social_number" :label="__('tables.persons.social_number')"
                            :$sortDirection :$sortBy />

                        <x-core.table.sortable-th wire:key="P-TH-4" model="created_at" :label="__('tables.persons.registration_date')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="P-TH-5" model="phone" :label="__('tables.persons.phone')" :$sortDirection
                            :$sortBy />
                        <x-core.table.sortable-th wire:key="P-TH-6" model="card_number" :label="__('tables.persons.card_number')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="P-TH-7" model="birth_date" :label="__('tables.persons.birth_date')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="P-TH-8" model="birth_place_fr" :label="__('tables.persons.birth_place_fr')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="P-TH-9" model="birth_place_ar" :label="__('tables.persons.birth_place_ar')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="P-TH-10" model="birth_place_en" :label="__('tables.persons.birth_place_en')"
                            :$sortDirection :$sortBy />


                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->persons as $p)
                        <tr wire:key="person-{{ $p->id }}">
                            <td>


                                <x-core.button

                                variant="danger" icon="delete" function="openDeletePersonDialog"
                                    :parameters="[$p]" rounded=true hasTooltip=true :tooltip="__('toolTips.person.delete')" />

                                <livewire:core.open-modal-button
                                wire:key="edit-{{ $p->id }}" rounded=true
                                    hasTooltip=true :tooltip="__('toolTips.person.update')" icon="edit"
                                    modalTitle="modals.person.actions.update"
                                    :modalTitleOptions="['name' => $p->full_name]" :modalContent="[
                                        'name' => 'core.person-modal',
                                        'parameters' => ['id' => $p->id],
                                    ]" />



                                @canany(['super-admin-access', 'admin-access'])
                                    <livewire:core.open-modal-button wire:key="occ-{{ $p->id }}" rounded=true
                                        hasTooltip=true :tooltip="__('toolTips.person.manage.occupations')" icon="suitcase" modalTitle="modals.person.actions.manage.occupations"
                                        :modalTitleOptions="['name' => $p->full_name]" :modalContent="[
                                            'name' => 'core.admin.occupations-modal',
                                            'parameters' => ['person' => $p],
                                        ]" />
                                    <livewire:core.open-modal-button wire:key="bank-info-{{ $p->id }}" rounded=true
                                        hasTooltip=true :tooltip="__('toolTips.person.manage.banking_information')" icon="credit_card" modalTitle="modals.user.actions.manage.banking_information"
                                        :modalTitleOptions="['name' => $p->full_name]" :modalContent="[
                                            'name' => 'core.admin.banking-information-modal',
                                            'parameters' => ['bankable' => $p, 'bankableType' => 'person'],
                                        ]" />
                                    <livewire:core.open-modal-button wire:key="account-{{ $p->id }}" rounded=true
                                        hasTooltip=true :tooltip="__('toolTips.person.manage.account')" icon="profile" modalTitle="modals.person.actions.manage.account"
                                        :modalTitleOptions="['name' => $p->full_name]" :modalContent="[
                                            'name' => 'core.user-modal',
                                            'parameters' => ['id' => $p->user?->id, 'personId' => $p->id],
                                        ]" />
                                @endcanany


                            </td>
                            <td>{{ $p->employee_number }}</td>
                            <td>{{ $p->full_name_fr }}</td>
                            <td>{{ $p->full_name_ar }}</td>
                            <td>{{ $p->user_email }}</td>
                            <td>{{ $p->social_number }}</td>
                            <td>{{ $p->created_at->format('Y-m-d') }}</td>
                            <td>{{ $p->phone }}</td>
                            <td>{{ $p->card_number }}</td>
                            <td>{{ $p->birth_date }}</td>
                            <td>{{ $p->birth_place_fr }}</td>
                            <td>{{ $p->birth_place_ar }}</td>
                            <td>{{ $p->birth_place_en }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $this->persons->links('components.core.pagination') }}
    @else
        <div class="table__footer">
            <h2>@lang('tables.persons.not_found')</h2>
        </div>
    @endif
</div>
