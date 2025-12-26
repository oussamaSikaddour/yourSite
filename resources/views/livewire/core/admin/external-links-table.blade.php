<div class="table__container" x-on:update-external-links-table.window="$wire.$refresh()">
    <div class="table__header">
        <h3>@lang('tables.external_links.info')</h3>
        <div class="table__header__actions">


            <x-core.button icon="filter" rounded=true hasTooltip=true :tooltip="__('toolTips.common.filters')" :extraClasses="['table__filters__btn']" />

            <x-core.form.selector htmlId="TP-upp" model="perPage" :data="$perPageOptions" type="filter" :tooltip="__('toolTips.common.per_page')" />
        </div>
    </div>

    <div class="table__filters" wire:ignore>
        <div class="form__container">
            <form class="form">
                <div class="row">
                    <x-core.form.input model="name" :label="__('tables.external_links.name')" type="text" html_id="tExName"
                        role="filter" />

                    <x-core.form.input model="url" :label="__('tables.external_links.url')" type="text" html_id="tExUrl"
                        role="filter" />
                </div>
                <div class="row">
                    <x-core.form.input model="employeeNumber" :label="__('tables.external_links.employee_number')" type="text" html_id="p-EmployeeUT"
                        role="filter" />
                </div>

                <div class="form__actions">



                    <x-core.button hasTooltip=true :tooltip="__('toolTips.common.resetFilters')" type="submit" variant="primary"
                        function="resetFilters" prevent=true rounded=true icon="refresh" />
                </div>
            </form>
        </div>
    </div>


    @if (isset($this->externalLinks) && $this->externalLinks->isNotEmpty())
        <div class="table__body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="column">
                            <div>@lang('tables.common.actions')</div>
                        </th>
                        <x-core.table.sortable-th wire:key="textL-TH-1" model="name" :label="__('tables.external_links.name')" :$sortDirection
                            :$sortBy :appLocal=true />

                        <x-core.table.sortable-th wire:key="textL-TH-2" model="url" :label="__('tables.external_links.url')" :$sortDirection
                            :$sortBy />

                        <x-core.table.sortable-th wire:key="textL-TH-3" model="created_at" :label="__('tables.external_links.created_at')"
                            :$sortDirection :$sortBy />


                    </tr>
                </thead>

                <tbody>
                    @foreach ($this->externalLinks as $externalLink)
                        <tr wire:key="{{ $externalLink->id }}-extl">
                            <td>



                                <x-core.button variant="danger" icon="delete" function="openDeleteDialog"
                                    :parameters="[$externalLink]" rounded=true hasTooltip=true :tooltip="__('toolTips.external_link.delete')" />

                                <livewire:core.open-modal-button wire:key="edit-EL-{{ $p->id }}" rounded=true
                                    hasTooltip=true :tooltip="__('toolTips.external_link.update')" icon="edit"
                                    modalTitle="modals.external_link.actions.update" :modalContent="[
                                        'name' => 'core.admin.external-link-modal',
                                        'parameters' => ['id' => $externalLink->id],
                                    ]" />
                                />


                            </td>
                            <td scope="row">{{ $externalLink->localized_name }}</td>
                            <td>{{ $externalLink->url }}</td>
                            <td>{{ $externalLink->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $this->externalLinks->links('components.core.pagination') }}
    @else
        <div class="table__footer">
            <h2>@lang('tables.external_links.not_found')</h2>
        </div>
    @endif
</div>
