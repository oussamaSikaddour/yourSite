<div class="table__container" x-on:update-menus-table.window="$wire.$refresh()">
    <div class="table__header">
        <h3>@lang('tables.menus.info')</h3>
        <div class="table__header__actions">


            <x-core.button icon="filter" rounded=true hasTooltip=true :tooltip="__('toolTips.common.filters')" :extraClasses="['table__filters__btn']" />

            <x-core.form.selector htmlId="TP-upp" model="perPage" :data="$perPageOptions" type="filter" :tooltip="__('toolTips.common.per_page')" />
        </div>
    </div>

    <div class="table__filters" wire:ignore>
        <div class="form__container">
            <form class="form">
                <div class="row">
                    <x-core.form.input model="title" :label="__('tables.menus.title')" type="text" html_id="TMt-tit"
                        role="filter" />



                    <x-core.form.selector htmlId="tmt-state" model="state" :label="__('tables.menus.state')" :data="$stateOptions"
                        type="filter" />

                </div>
                <div class="row">
                    <x-core.form.input model="employeeNumber" :label="__('tables.menus.employee_number')" type="text" html_id="p-EmployeeUT"
                        role="filter" />
                </div>

                <div class="form__actions">



                    <x-core.button hasTooltip=true :tooltip="__('toolTips.common.resetFilters')" type="submit" variant="primary"
                        function="resetFilters" prevent=true rounded=true icon="refresh" />
                </div>
            </form>
        </div>
    </div>


    @if (isset($this->menus) && $this->menus->isNotEmpty())
        <div class="table__body">
            <table>
                <thead>
                    <tr>
                        <th scope="column">
                            <div>@lang('tables.common.actions')</div>
                        </th>
                        <x-core.table.sortable-th wire:key="mt-TH-1" model="title" :label="__('tables.menus.title')" :$sortDirection
                            :$sortBy :appLocal=true />

                        <x-core.table.sortable-th wire:key="mt-TH-2" model="state" :label="__('tables.menus.state')" :$sortDirection
                            :$sortBy />


                        <x-core.table.sortable-th wire:key="gtT-TH-4" model="created_at" :label="__('tables.menus.created_at')"
                            :$sortDirection :$sortBy />


                    </tr>
                </thead>

                <tbody>
                    @foreach ($this->menus as $menu)
                        <tr wire:key="{{ $menu->id }}-gt">
                            <td>
                                <x-core.button variant="danger" icon="delete" function="openDeleteDialog"
                                    :parameters="[$menu]" rounded=true hasTooltip=true :tooltip="__('toolTips.menu.delete')" />


                                <livewire:core.open-modal-button wire:key="edit-Me-{{ $wilaya->id }}" rounded=true
                                    hasTooltip=true icon="edit" :tooltip="__('toolTips.menu.update')"
                                    modalTitle="modals.menu.actions.update" :modalContent="[
                                        'name' => 'core.admin.menu-modal',
                                        'parameters' => ['id' => $menu->id],
                                    ]" />

                                <x-core.button icon="view" variant="info" route="menu_route" :routeParameters="[
                                    'id' => $menu->id,
                                    'title' => $menu->localized_title,
                                ]"
                                    rounded=true hasTooltip=true :tooltip="__('toolTips.meun.manage')" />
                            </td>
                            <td scope="row">{{ $menu->localized_title }}</td>
                            <td>
                                <livewire:core.table-selector wire:key="st-P-{{ $menu->id }}" :data="$stateOptions"
                                    :selectedValue="$menu->state" :entity="$menu" lazy />
                            </td>
                            <td>{{ $menu->created_at->format('Y-m-d') }}</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $this->menus->links('components.core.pagination') }}
    @else
        <div class="table__footer">
            <h2>@lang('tables.menus.not_found')</h2>
        </div>
    @endif
</div>
