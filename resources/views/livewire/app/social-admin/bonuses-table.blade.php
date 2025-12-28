@if (!$simplisticView)
    {{-- FULL VIEW --}}
    <div class="table__container" x-on:update-bonuses-table.window="$wire.$refresh()">

        {{-- header --}}
        <div class="table__header">
            <h3>@lang('tables.bonuses.info')</h3>

            <div class="table__header__actions">
                <x-core.button icon="filter" rounded hasTooltip :tooltip="__('toolTips.common.filters')" :extraClasses="['table__filters__btn']" />

                <x-core.form.selector htmlId="TP-upp" model="perPage" :data="$perPageOptions" type="filter" :tooltip="__('toolTips.common.per_page')" />
            </div>
        </div>

        {{-- filters --}}
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
                        <x-core.button hasTooltip :tooltip="__('toolTips.common.resetFilters')" type="submit" variant="primary"
                            function="resetFilters" prevent rounded icon="refresh" />
                    </div>
                </form>
            </div>
        </div>
    @else
        {{-- SIMPLIFIED VIEW --}}
        <div class="table__container" wire:key="bonust2">
@endif


{{-- shared table body for both modes --}}
@unless ($this->bonuses->isEmpty())

    <div class="table__body">
        <table class="table">

            <thead>
                <tr>

                    @if ($simplisticView)
                        <th></th>
                    @else
                        <th scope="column">
                            <div>@lang('tables.common.actions')</div>
                        </th>
                    @endif

                    <x-core.table.sortable-th wire:key="boT-TH-1" model="titled" :label="__('tables.bonuses.titled')" :$sortDirection :$sortBy
                        :appLocal="true" />

                    <x-core.table.sortable-th wire:key="boT-TH-2" model="amount" :label="__('tables.bonuses.amount')" :$sortDirection
                        :$sortBy />

                    @unless ($simplisticView)
                        <x-core.table.sortable-th wire:key="bt-TH-3" model="created_at" :label="__('tables.bonuses.created_at')" :$sortDirection
                            :$sortBy />
                    @endunless

                </tr>
            </thead>

            <tbody>

                @foreach ($this->bonuses as $bonus)
                    <tr wire:key="bonus-{{ $bonus->id }}">

                        {{-- simplified checkbox --}}
                        @if ($simplisticView)
                            <td>
                                <x-core.form.check-box model="selectedBonuses" value="{{ $bonus->amount }}"
                                    :live="true" htmlId="s-bl-{{ $bonus->id }}" />
                            </td>

                            {{-- full view actions --}}
                        @else
                            <td>
                                <x-core.button variant="danger" icon="delete" function="openDeleteDialog"
                                    :parameters="[$bonus]" rounded hasTooltip :tooltip="__('toolTips.bonus.delete')" />

                                <livewire:core.open-modal-button wire:key="edit-bo-{{ $bonus->id }}" rounded hasTooltip
                                    :tooltip="__('toolTips.bonus.update')" icon="edit" modalTitle="modals.bonus.actions.update"
                                    :modalContent="[
                                        'name' => 'app.social-admin.bonus-modal',
                                        'parameters' => ['id' => $bonus->id],
                                    ]" />
                            </td>
                        @endif

                        <td>{{ $bonus->titled }}</td>
                        <td>{{ $bonus->amount }}</td>

                        @unless ($simplisticView)
                            <td>{{ $bonus->created_at->format('Y-m-d') }}</td>
                        @endunless

                    </tr>
                @endforeach

            </tbody>

        </table>
    </div>

    {{-- pagination --}}
    {{ $this->bonuses->links('components.core.pagination') }}
@else
    {{-- empty state --}}
    <div class="table__footer">
        <h2>@lang('tables.bonuses.not_found')</h2>
    </div>
@endunless

</div>
