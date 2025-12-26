<div class="table__container" x-on:update-slides-table.window="$wire.$refresh()">
    <div class="table__header">
        <h3>@lang('tables.slides.info')</h3>
        <div class="table__header__actions">


            <x-core.button icon="filter" rounded=true hasTooltip=true :tooltip="__('toolTips.common.filters')" :extraClasses="['table__filters__btn']" />

            <x-core.form.selector htmlId="TP-upp" model="perPage" :data="$perPageOptions" type="filter" :tooltip="__('toolTips.common.per_page')" />
        </div>
    </div>

    <div class="table__filters" wire:ignore.self>
        <div class="form__container">
            <form class="form">
                <div class="row">
                    <x-core.form.input model="title" :label="__('tables.slides.title')" type="text" html_id="TSlid-title"
                        role="filter" />
                </div>
                <div class="form__actions">



                    <x-core.button hasTooltip=true :tooltip="__('toolTips.common.resetFilters')" type="submit" variant="primary"
                        function="resetFilters" prevent=true rounded=true icon="refresh" />
                </div>
            </form>
        </div>
    </div>


    @if (isset($this->slides) && count($this->slides))
        <div class="table__body">
            <table class="table">
                <thead>
                    <tr scope="column">
                        <th>
                            <div>@lang('tables.common.actions')</div>
                        </th>
                        <x-core.table.sortable-th wire:key="slid-TH-1" model="title" :label="__('tables.slides.title')" :$sortDirection
                            :$sortBy :appLocal=true />

                        <x-core.table.sortable-th wire:key="slid-TH-2" model="order" :label="__('tables.slides.order')" :$sortDirection
                            :$sortBy />

                        <x-core.table.sortable-th wire:key="arT-TH-3" model="created_at" :label="__('tables.slides.created_at')"
                            :$sortDirection :$sortBy />

                        <th>
                            <div>@lang('tables.slides.image')</div>
                        </th>

                    </tr>
                </thead>

                <tbody>
                    @foreach ($this->slides as $slide)
                        <tr wire:key="{{ $slide->id }}-gt">
                            <td>
                                <x-core.button variant="danger" icon="delete" function="openDeleteDialog"
                                    :parameters="[$slide]" rounded=true hasTooltip=true :tooltip="__('toolTips.slide.delete')" />
                                <livewire:core.open-modal-button
                                     wire:key="edit-slide-{{ $slide->id }}"
                                     rounded=true
                                     hasTooltip=true
                                     :tooltip="__('toolTips.slide.update')"
                                     icon="edit"
                                     modalTitle="modals.slide.actions.update"
                                     :modalContent="[
                                        'name' => 'core.slide-modal',
                                        'parameters' => [
                                            'id' => $slide->id,
                                            'sliderId' => $slide->slider_id,
                                        ],
                                    ]"
                                    :containsTinyMce=true
                                    />
                            </td>
                            <td scope="row">{{ $slide->title }}</td>
                            <td>{{ $slide->order }}</td>
                            <td>{{ $slide->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div class="table__img__preview">
                                    <img  src="{{ $slide->image->url }}"
                                        alt="{{ $slide->image->use_case }}">
                                </div>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $this->slides->links('components.core.pagination') }}
    @else
        <div class="table__footer">
            <h2>@lang('tables.slides.not_found')</h2>
        </div>
    @endif
</div>
