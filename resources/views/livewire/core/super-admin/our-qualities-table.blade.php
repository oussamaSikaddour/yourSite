<div class="table__container" x-on:update-our-qualities-table.window="$wire.$refresh()">
    <div class="table__header">
        <h3>@lang('tables.our_qualities.info')</h3>
        <div class="table__header__actions">

            <x-core.button icon="filter" rounded=true hasTooltip=true :tooltip="__('toolTips.common.filters')" :extraClasses="['table__filters__btn']" />

            <x-core.form.selector htmlId="TP-upp" model="perPage" :data="$perPageOptions" type="filter"
                :tooltip="__('toolTips.common.per_page')" />
        </div>
    </div>

    <div class="table__filters" wire:ignore>
        <div class="form__container">
            <form class="form">
                <div class="row">
                    <x-core.form.input model="name" :label="__('tables.our_qualities.name')" type="text" html_id="tName"
                        role="filter" />
                </div> <!-- FIXED missing closing .row -->

                <div class="form__actions">
                    <x-core.button hasTooltip=true :tooltip="__('toolTips.common.resetFilters')" type="submit"
                        variant="primary" function="resetFilters" prevent=true rounded=true icon="refresh" />
                </div>
            </form>
        </div> <!-- FIXED missing closing .form__container -->
    </div>

    @if (isset($this->ourQualities) && $this->ourQualities->isNotEmpty())

        <div class="table__body">
            <table>
                <thead>
                    <tr>

                        <x-core.table.sortable-th wire:key="oqT-TH-2" model="name_fr"
                            :label="__('tables.our_qualities.name')" :$sortDirection :$sortBy />

                        <x-core.table.sortable-th wire:key="crt-TH-5" model="is_active"
                            :label="__('tables.our_qualities.status')" :$sortDirection :$sortBy />

                        <x-core.table.sortable-th wire:key="crt-TH-6" model="created_at"
                            :label="__('tables.our_qualities.created_at')" :$sortDirection :$sortBy />

                        <th scope="column">
                            <div>@lang('tables.common.actions')</div>
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($this->ourQualities as $oQ)
                        <tr wire:key="{{ $oQ->id }}-pr">
                            <td scope="row">{{ $oQ->localized_name }}</td>

                            <td>
                                <livewire:core.table-selector wire:key="st-P-{{ $oQ->id }}" :data="$statusOptions"
                                    :selectedValue="$oQ->is_active" :entity="$oQ" lazy />
                            </td>

                            <td>{{ $oQ->created_at->format('Y-m-d') }}</td>

                            <td>
                                <x-core.button
                                    variant="danger"
                                    icon="delete"
                                    function="openDeleteDialog"
                                    :parameters="[$oQ]"
                                    rounded=true
                                    hasTooltip=true
                                    :tooltip="__('toolTips.our_quality.delete')"
                                />

                                <livewire:core.open-modal-button wire:key="edit-oQ-{{ $oQ->id }}" rounded=true
                                    hasTooltip=true icon="edit" :tooltip="__('toolTips.our_quality.update')"
                                    modalTitle="modals.our_quality.actions.update"
                                    :modalContent="[
                                        'name' => 'core.super-admin.our-quality-modal',
                                        'parameters' => ['id' => $oQ->id],
                                    ]"
                                />
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        {{ $this->ourQualities->links('components.core.pagination') }}

    @else

        <div class="table__footer">
            <h2>
                @lang('tables.our_qualities.not_found')
            </h2>
        </div>

    @endif

</div>
