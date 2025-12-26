<div class="table__container" x-on:update-trends-table.window="$wire.$refresh()">
    <div class="table__header">
        <h3>@lang('tables.trends.info')</h3>
        <div class="table__header__actions">


            <x-core.button icon="filter" rounded=true hasTooltip=true :tooltip="__('toolTips.common.filters')" :extraClasses="['table__filters__btn']" />

            <x-core.form.selector htmlId="TP-upp" model="perPage" :data="$perPageOptions" type="filter" :tooltip="__('toolTips.common.per_page')" />
        </div>
    </div>

    <div class="table__filters" wire:ignore>
        <div class="form__container">
            <form class="form">
                <div class="row">
                    <x-core.form.input model="title" :label="__('tables.trends.title')" type="text" html_id="TTs-tit"
                        role="filter" />

                    <x-core.form.selector htmlId="TTs-state" model="state" :label="__('tables.trends.state')" :data="$stateOptions"
                        type="filter" />

                </div>

                <div class="form__actions">



                    <x-core.button hasTooltip=true :tooltip="__('toolTips.common.resetFilters')" type="submit" variant="primary"
                        function="resetFilters" prevent=true rounded=true icon="refresh" />
                </div>
            </form>
        </div>
    </div>


    @if (isset($this->trends) && $this->trends->isNotEmpty())
        <div class="table__body">
            <table>
                <thead>
                    <tr>
                        <th scope="column">
                            <div>@lang('tables.common.actions')</div>
                        </th>
                        <x-core.table.sortable-th wire:key="mtt-TH-1" model="title" :label="__('tables.trends.title')" :$sortDirection
                            :$sortBy :appLocal=true />

                        <x-core.table.sortable-th wire:key="mtt-TH-2" model="state" :label="__('tables.trends.state')" :$sortDirection
                            :$sortBy />

                        <x-core.table.sortable-th wire:key="mtt-TH-4" model="created_at" :label="__('tables.trends.created_at')"
                            :$sortDirection :$sortBy />


                    </tr>
                </thead>

                <tbody>
                    @foreach ($this->trends as $trend)
                        <tr wire:key="{{ $trend->id }}-gt">
                            <td>



                                <x-core.button variant="danger" icon="delete" function="openDeleteDialog"
                                    :parameters="[$trend]" rounded=true hasTooltip=true :tooltip="__('toolTips.trend.delete')" />


                                <livewire:core.open-modal-button wire:key="edit-Me-{{ $wilaya->id }}" rounded=true
                                    hasTooltip=true icon="edit" :tooltip="__('toolTips.trend.update')"
                                    modalTitle="modals.trend.actions.update" :modalContent="[
                                        'name' => 'core.author.trend-modal',
                                        'parameters' => ['id' => $trend->id],
                                    ]" />

                            </td>
                            <td scope="row">{{ $trend->localized_title }}</td>
                            <td>
                                <livewire:core.table-selector wire:key="st-P-{{ $trend->id }}" :data="$stateOptions"
                                    :selectedValue="$trend->state" :entity="$trend" lazy />
                            </td>
                            <td>{{ $trend->created_at->format('Y-m-d') }}</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $this->trends->links('components.core.pagination') }}
    @else
        <div class="table__footer">
            <h2>@lang('tables.trends.not_found')</h2>
        </div>
    @endif
</div>
