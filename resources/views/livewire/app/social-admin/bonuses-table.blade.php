@if (!$simplisticView)
    <div class="table__container" x-on:update-bonuses-table.window="$wire.$refresh()">
        <div class="table__header">
            <h3>@lang('tables.bonuses.info')</h3>
            <div class="table__header__actions">
                <x-core.button icon="filter" rounded=true hasTooltip=true :tooltip="__('toolTips.common.filters')" :extraClasses="['table__filters__btn']" />
                <x-core.form.selector htmlId="TP-upp" model="perPage" :data="$perPageOptions" type="filter" :tooltip="__('toolTips.common.per_page')" />
            </div>
        </div>

        <div class="table__filters" wire:ignore>
            <div class="form__container">
                <form class="form">
                    <div class="row">
                        <x-core.form.input model="titled" :label="__('tables.bonuses.titled')" type="text" html_id="boTitled"
                            role="filter" />

                        <x-core.form.input model="amount" :label="__('tables.bonuses.amount')" type="money" html_id="boAmount"
                            role="filter" />

                    </div>
                    <div class="row">
                        <x-core.form.input model="designation" :label="__('tables.bonuses.designation')" type="text" html_id="bDesignation"
                            role="filter" />
                    </div>
                    <div class="form__actions">



                        <x-core.button hasTooltip=true :tooltip="__('toolTips.common.resetFilters')" type="submit" variant="primary"
                            function="resetFilters" prevent=true rounded=true icon="refresh" />
                    </div>
                </form>
            </div>
        </div>


        @if (isset($this->bonuses) && $this->bonuses->isNotEmpty())
            <div class="table__body">
                <table class="table">
                    <thead>
                        <tr>

                            <th scope="column">
                                <div>@lang('tables.common.actions')</div>
                            </th>
                            <x-core.table.sortable-th wire:key="boT-TH-1" model="titled" :label="__('tables.bonuses.titled')"
                                :$sortDirection :$sortBy :appLocal=true />

                            <x-core.table.sortable-th wire:key="boT-TH-2" model="amount" :label="__('tables.bonuses.amount')"
                                :$sortDirection :$sortBy />

                            <x-core.table.sortable-th wire:key="bt-TH-3" model="created_at" :label="__('tables.bonuses.created_at')"
                                :$sortDirection :$sortBy />

                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($this->bonuses as $bonus)
                            <tr wire:key="{{ $bonus->id }}-pr">
                                <td>

                                    <x-core.button variant="danger" icon="delete" function="openDeleteDialog"
                                        :parameters="[$buns]" rounded=true hasTooltip=true :tooltip="__('toolTips.bonus.delete')" />

                                    <livewire:core.open-modal-button wire:key="edit-bo-{{ $bonus->id }}" rounded=true
                                        hasTooltip=true :tooltip="__('toolTips.bonus.update')" icon="edit"
                                        modalTitle="modals.bonus.actions.update" :modalContent="[
                                            'name' => 'app.admin.bonus-modal',
                                            'parameters' => ['id' => $bonus->id],
                                        ]" />
                                </td>
                                <td scope="row">{{ $bonus->titled }}</td>
                                <td>{{ $bonus->amount }}</td>
                                <td>{{ $bonus->created_at->format('Y-m-d') }}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $this->bonuses->links('components.default.pagination') }}
        @else
            <!-- Empty State -->
            <div class="table__footer">
                <h2>@lang('tables.bonuses.not_found')</h2>
            </div>
        @endif
    </div>
@else
    <!-- Simplified Bonus Table View -->
    <div class="table__container" wire:key="bonust2">
        @if (isset($this->bonuses) && $this->bonuses->isNotEmpty())
            <div class="table__body">
                <table class="table">
                    <thead>
                        <tr>

                            <th></th>
                            <x-core.table.sortable-th wire:key="boT-TH-1" model="titled" :label="__('tables.bonuses.titled')"
                                :$sortDirection :$sortBy :appLocal=true />

                            <x-core.table.sortable-th wire:key="boT-TH-2" model="amount" :label="__('tables.bonuses.amount')"
                                :$sortDirection :$sortBy />



                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($this->bonuses as $bonus)
                            <tr wire:key="{{ $bonus->id }}-pr">
                                <td scope="row">
                                    <x-core.form.check-box model="selectedBonuses" value="{{ $bonus->amount }}"
                                        :live="true" label="" htmlId="s-bl-{{ $bonus->id }}" />
                                </td>
                                <td scope="row">{{ $bonus->titled }}</td>
                                <td>{{ $bonus->amount }}</td>


                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $this->bonuses->links('components.default.pagination') }}
        @endif
    </div>
@endif
