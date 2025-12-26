<div class="table__container" x-on:update-messages-table.window="$wire.$refresh()">
    <div class="table__header">
        <h3>@lang('tables.messages.info')</h3>
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
                    <x-core.form.input model="name" :label="__('tables.messages.name')" type="text" html_id="tM-n" role="filter" />
                    <x-core.form.input model="email" :label="__('tables.messages.email')" type="email" html_id="tM-email"
                        role="filter" />

                </div>
                <div class="form__actions">



                    <x-core.button hasTooltip=true :tooltip="__('toolTips.common.resetFilters')" type="submit" variant="primary"
                        function="resetFilters" prevent=true rounded=true icon="refresh" />
                </div>
            </form>
        </div>
    </div>



    @if (isset($this->messages) && $this->messages->isNotEmpty())
        <div class="table__body">
            <table>
                <thead>
                    <tr>
                        <th scope="column">
                            <div>@lang('tables.common.actions')</div>
                        </th>
                        <x-core.table.sortable-th wire:key="tm-TH-1" model="name" :label="__('tables.messages.name')" :$sortDirection
                            :$sortBy />
                        <x-core.table.sortable-th wire:key="tm-TH-2" model="email" :label="__('tables.messages.email')" :$sortDirection
                            :$sortBy />
                        <x-core.table.sortable-th wire:key="tm-TH-3" model="created_at" :label="__('tables.messages.created_at')"
                            :$sortDirection :$sortBy />

                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->messages as $m)
                        <tr wire:key="m-t-{{ $m->id }}" scope="row">
                            <td>
                             <x-core.button
                                variant="danger"
                                icon="delete"
                                function="openDeleteDialog"
                                :parameters="[$message]"
                                rounded=true hasTooltip=true
                                :tooltip="__('toolTips.message.delete')"
                                />
                                <livewire:core.open-modal-button

                                     wire:key="reply-{{ $message->id }}"
                                     rounded=true
                                     hasTooltip=true icon="edit"
                                     :tooltip="__('toolTips.message.reply')"
                                      modalTitle="modals.message.actions.reply"
                                      :modalContent="[
                                        'name' => 'core.super-admin.reply-modal',
                                        'parameters' => ['message' => $m],
                                      ]"
                                      containsTinyMce=true

                                    />
                            </td>
                            <td>{{ $m->name }}</td>
                            <td>{{ $m->email }}</td>
                            <td>{{ $m->created_at->format('Y-m-d') }}</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $this->messages->links('components.core.pagination') }}
    @else
        <div class="table__footer">
            <h2>@lang('tables.messages.not_found')</h2>
        </div>
    @endif
</div>
