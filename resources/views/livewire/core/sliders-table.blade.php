<div class="table__container" x-on:update-sliders-table.window="$wire.$refresh()">
    <div class="table__header">
        <h3>@lang('tables.sliders.info')</h3>
        <div class="table__header__actions">


            <x-core.button icon="filter" rounded=true hasTooltip=true :tooltip="__('toolTips.common.filters')" :extraClasses="['table__filters__btn']" />

            <x-core.form.selector htmlId="TP-upp" model="perPage" :data="$perPageOptions" type="filter" :tooltip="__('toolTips.common.per_page')" />
        </div>
    </div>

    <div class="table__filters" wire:ignore.self>
        <div class="form__container">
            <form class="form">
                <div class="row">
                    <x-core.form.input model="creator" :label="__('tables.sliders.creator')" type="text" html_id="TSlid-creator"
                        role="filter" />

                    <x-core.form.input model="name" :label="__('tables.sliders.name')" type="text" html_id="TSlid-name"
                        role="filter" />
                </div>
                <div class="form__actions">
                    <x-core.button hasTooltip=true :tooltip="__('toolTips.common.resetFilters')" type="submit" variant="primary"
                        function="resetFilters" prevent=true rounded=true icon="refresh" />
                </div>
            </form>
        </div>
    </div>


    @if (isset($this->sliders) && count($this->sliders))
        <div class="table__body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="column">
                            <div>@lang('tables.common.actions')</div>
                        </th>
                        <x-core.table.sortable-th wire:key="slid-TH-1" model="name" :label="__('tables.sliders.name')" :$sortDirection
                            :$sortBy />

                        <x-core.table.sortable-th wire:key="slid-TH-2" model="creator" :label="__('tables.sliders.creator')" :$sortDirection
                            :$sortBy />

                        <x-core.table.sortable-th wire:key="slid-TH-3" model="position" :label="__('tables.sliders.position')"
                            :$sortDirection :$sortBy />

                        <x-core.table.sortable-th wire:key="slid-TH-4" model="state" :label="__('tables.sliders.state')" :$sortDirection
                            :$sortBy />

                        <x-core.table.sortable-th wire:key="arT-TH-5" model="created_at" :label="__('tables.sliders.created_at')"
                            :$sortDirection :$sortBy />

                    </tr>
                </thead>

                <tbody>
                    @foreach ($this->sliders as $slider)
                        <tr wire:key="{{ $slider->id }}-gt">
                            <td>

                                <x-core.button variant="danger" icon="delete" function="openDeleteDialog"
                                    :parameters="[$slider]" rounded=true hasTooltip=true :tooltip="__('toolTips.slider.delete')" />

                                <livewire:core.open-modal-button wire:key="edit-slider-{{ $slider->id }}" rounded=true
                                    hasTooltip=true :tooltip="__('toolTips.slider.update')" icon="edit"
                                    modalTitle="modals.slider.actions.update" :modalTitleOptions="['name' => $slider->name]" :modalContent="[
                                        'name' => 'core.slider-modal',
                                        'parameters' => ['id' => $slider->id],
                                    ]" />

                                <x-core.button icon="view" variant="info" route="slider_route" :routeParameters="[
                                    'id' => $slider->id,
                                    'name' => $slider->name,
                                    'sliderableId' => $sliderableId,
                                    'sliderableType' => $sliderableType,
                                    'sliderableName' => $sliderableName,
                                ]"
                                    rounded=true hasTooltip=true :tooltip="__('toolTips.slider.manage')" />
                            </td>
                            <td scope="row">{{ $slider->name }}</td>
                            <td>{{ $slider->creator }}</td>
                            <td>{{ $slider->position }}</td>
                            <td>
                                <livewire:core.table-selector wire:key="slid-P-{{ $slider->id }}" :data="$stateOptions"
                                    :selectedValue="$slider->state" :entity="$slider" lazy />
                            </td>
                            <td>{{ $slider->created_at->format('Y-m-d') }}</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $this->sliders->links('components.core.pagination') }}
    @else
        <div class="table__footer">
            <h2>@lang('tables.sliders.not_found')</h2>
        </div>
    @endif
</div>
