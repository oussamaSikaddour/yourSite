<div class="table__container" x-on:update-users-table.window="$wire.$refresh()">
    <div class="table__header">
        <h3>@lang('tables.users.info')</h3>
        <div class="table__header__actions">
            @canany(['super-admin-access', 'admin-access'])
                <span wire:loading wire:target="excelFile">
                    <x-core.loading />
                </span>
                <x-core.file-input icon="upload" :tooltip="__('toolTips.user.manage.users')" model="excelFile" types="excel" type="icon_only" />


                <x-core.button icon="export" function="generateEmptyUsersExcel" rounded=true hasTooltip=true
                    :tooltip="__('toolTips.user.excel.empty')" />


                <x-core.button variant="success" icon="export" function="generateUsersExcel" rounded=true hasTooltip=true
                    :tooltip="__('toolTips.user.excel.empty')" />
            @endcanany


            <x-core.button icon="filter" rounded=true hasTooltip=true :tooltip="__('toolTips.common.filters')" :extraClasses="['table__filters__btn']" />

            <x-core.form.selector htmlId="TP-upp" model="perPage" :data="$perPageOptions" type="filter" :tooltip="__('toolTips.common.per_page')" />
        </div>
    </div>

    <div class="table__filters" wire:ignore>
        <div class="form__container">
            <form class="form">
                <div class="row">
                    <x-core.form.input model="name" :label="__('tables.users.name')" type="text" html_id="u-NameUT"
                        role="filter" />
                    <x-core.form.input model="email" :label="__('tables.users.email')" type="text" html_id="u-EmailUT"
                        role="filter" />
                </div>

                <div class="form__actions">



                    <x-core.button hasTooltip=true :tooltip="__('toolTips.common.resetFilters')" type="submit" variant="primary"
                        function="resetFilters" prevent=true rounded=true icon="refresh" />
                </div>
            </form>
        </div>
    </div>
    @if ($this->users->isNotEmpty())
        <div class="table__body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="column">
                            <div>@lang('tables.common.actions')</div>
                        </th>
                        <x-core.table.sortable-th wire:key="U-TH-2" model="name" :label="__('tables.users.name')" :$sortDirection
                            :$sortBy />
                        <x-core.table.sortable-th wire:key="U-TH-3" model="email" :label="__('tables.users.email')" :$sortDirection
                            :$sortBy />
                        <x-core.table.sortable-th wire:key="U-TH-4" model="created_at" :label="__('tables.users.registration_date')"
                            :$sortDirection :$sortBy />
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->users as $u)
                        <tr wire:key="user-{{ $u->id }}">
                            <td>
                                <x-core.button variant="danger" icon="delete" function="openDeleteUserDialog"
                                    :parameters="[$u]" rounded=true hasTooltip=true :tooltip="__('toolTips.user.delete')" />

                                <livewire:core.open-modal-button wire:key="edit-{{ $u->id }}" rounded=true
                                    hasTooltip=true :tooltip="__('toolTips.user.update')" icon="edit"
                                    modalTitle="modals.user.actions.update" :modalTitleOptions="['name' => $u->name]" :modalContent="[
                                        'name' => 'core.user-modal',
                                        'parameters' => [
                                            'id' => $u->id,
                                        ],
                                    ]" />




                                @canany(['super-admin-access', 'admin-access'])
                                    <livewire:core.open-modal-button wire:key="mRoles-{{ $u->id }}" rounded=true
                                        hasTooltip=true :tooltip="__('toolTips.user.manage.roles')" icon="permissions"
                                        modalTitle="modals.user.actions.manage.roles" :modalTitleOptions="['name' => $u->name]"
                                        :modalContent="[
                                            'name' => 'core.super-admin.roles-modal',
                                            'parameters' => ['id' => $u->id],
                                        ]" />
                                @endcanany
                            </td>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>{{ $u->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $this->users->links('components.core.pagination') }}
    @else
        <div class="table__footer">
            <h2>@lang('tables.users.not_found')</h2>
        </div>
    @endif
</div>
