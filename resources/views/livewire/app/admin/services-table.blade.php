<div class="table__container" x-on:update-services-table.window="$wire.$refresh()">
    <div class="table__header">
        <h3>@lang('tables.services.info')</h3>
        <div class="table__header__actions">

            <span wire:loading wire:target="excelFile">
                <x-core.loading />
            </span>
            <x-core.file-input icon="upload" :tooltip="__('toolTips.service.manage.services')" model="excelFile" types="excel" type="icon_only" />


            <x-core.button icon="export" function="generateEmptyServicesExcel" rounded=true hasTooltip=true
                :tooltip="__('toolTips.service.excel.empty')" />


            <x-core.button variant="success" icon="export" function="generateServicesExcel" rounded=true
                hasTooltip=true :tooltip="__('toolTips.service.excel.empty')" />



            <x-core.button icon="filter" rounded=true hasTooltip=true :tooltip="__('toolTips.common.filters')" :extraClasses="['table__filters__btn']" />

            <x-core.form.selector htmlId="TP-upp" model="perPage" :data="$perPageOptions" type="filter" :tooltip="__('toolTips.common.per_page')" />
        </div>
    </div>

    <div class="table__filters" wire:ignore>
        <div class="form__container">
            <form class="form">

                <div class="row">
                    <x-core.form.input model="name" :label="__('tables.services.name')" type="text" html_id="TsSName"
                        role="filter" />
                    <x-core.form.input model="headOfService" :label="__('tables.services.head_service')" type="text" html_id="TSResponsible"
                        role="filter" />
                </div>
                <div class="row">
                    <x-core.form.selector htmlId="TsTEb" model="type" :label="__('tables.services.type')" :data="$serviceTypesOptions"
                        type="filter" />
                    <x-core.form.selector htmlId="TTEsb" model="specialtyId" :label="__('tables.services.specialty')" :data="$serviceSpecialtiesOptions"
                        type="filter" />
                </div>
                <div class="form__actions">



                    <x-core.button hasTooltip=true :tooltip="__('toolTips.common.resetFilters')" type="submit" variant="primary"
                        function="resetFilters" prevent=true rounded=true icon="refresh" />
                </div>
            </form>
        </div>
    </div>
    @if (isset($this->services) && count($this->services))
        <div class="table__body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="column">
                            <div>@lang('tables.common.actions')</div>
                        </th>
                        <x-core.table.sortable-th wire:key="service-th-1" model="name_fr" :label="__('tables.services.name_fr')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="service-th-9" model="name_ar" :label="__('tables.services.name_ar')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="service-th-10" model="name_en" :label="__('tables.services.name_en')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="service-th-2" model="head_of_service" :label="__('tables.services.head_service')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="service-th-4" model="specialty" :label="__('tables.services.specialty')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="service-th-8" model="email" :label="__('tables.services.email')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="service-th-5" model="tel" :label="__('tables.services.tel')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="service-th-6" model="fax" :label="__('tables.services.fax')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="service-th-11" model="specialists_number" :label="__('tables.services.specialists_number')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="service-th-12" model="physicians_number" :label="__('tables.services.physicians_number')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="service-th-13" model="paramedics_number" :label="__('tables.services.paramedics_number')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="service-th-14" model="beds_number" :label="__('tables.services.beds_number')"
                            :$sortDirection :$sortBy />
                        <x-core.table.sortable-th wire:key="service-th-7" model="created_at" :label="__('tables.services.created_at')"
                            :$sortDirection :$sortBy />

                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->services as $service)
                        <tr wire:key="{{ $service->id }}-gt">
                            <td>
                                <x-core.button variant="danger" icon="delete" function="openDeleteDialog"
                                    :parameters="[$service]" rounded=true hasTooltip=true :tooltip="__('toolTips.service.delete')" />

                                <livewire:core.open-modal-button wire:key="edit-{{ $service->id }}" rounded=true
                                    hasTooltip=true :tooltip="__('toolTips.service.update')" icon="edit"
                                    modalTitle="modals.service.actions.update" :modalTitleOptions="['name' => $service->name]" :modalContent="[
                                        'name' => 'app.admin.service-modal',
                                        'parameters' => [
                                            'id' => $service->id,
                                        ],
                                    ]"
                                    :containsTinyMce=true />


                                <x-core.button icon="view" variant="info" route="service_route" :routeParameters="[
                                    'id' => $service->id,
                                    'showBreadcrumb'=>true

                                ]"
                                    rounded=true hasTooltip=true :tooltip="__('toolTips.service.manage.view')" />
                            </td>
                            <td scope="row">{{ $service->name_fr }}</td>
                            <td>{{ $service->name_ar }}</td>
                            <td>{{ $service->name_en }}</td>
                            <td>{{ $service->head_service }}</td>
                            <td>{{ $service->specialty }}</td>
                            <td>{{ $service->email }}</td>
                            <td>{{ $service->tel }}</td>
                            <td>{{ $service->fax }}</td>
                            <td>{{ $service->specialists_number }}</td>
                            <td>{{ $service->physicians_number }}</td>
                            <td>{{ $service->paramedics_number }}</td>
                            <td>{{ $service->beds_number }}</td>
                            <td>{{ $service->created_at->format('d-m-Y') }}</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $this->services->links('components.core.pagination') }}
    @else
        <div class="table__footer">
            <h2>@lang('tables.services.not_found')</h2>
        </div>
    @endif
</div>
