<div class="table__container" x-on:update-articles-table.window="$wire.$refresh()">
    <div class="table__header">
        <h3>@lang('tables.articles.info')</h3>
        <div class="table__header__actions">


            <x-core.button icon="filter" rounded=true hasTooltip=true :tooltip="__('toolTips.common.filters')" :extraClasses="['table__filters__btn']" />

            <x-core.form.selector htmlId="TP-upp" model="perPage" :data="$perPageOptions" type="filter" :tooltip="__('toolTips.common.per_page')" />
        </div>
    </div>

    <div class="table__filters" wire:ignore>
        <div class="form__container">
            <form class="form">
                <div class="row">
                    <x-core.form.input model="author" :label="__('tables.articles.author')" type="text" html_id="TAr-author"
                        role="filter" />

                    <x-core.form.input model="title" :label="__('tables.articles.title')" type="text" html_id="TAr-title"
                        role="filter" />
                </div>

                <div class="form__actions">



                    <x-core.button hasTooltip=true :tooltip="__('toolTips.common.resetFilters')" type="submit" variant="primary"
                        function="resetFilters" prevent=true rounded=true icon="refresh" />
                </div>
            </form>
        </div>
    </div>



    @if (isset($this->articles) && count($this->articles))
        <div class="table__body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="column">
                            <div>@lang('tables.common.actions')</div>
                        </th>
                        <x-core.table.sortable-th wire:key="art-TH-1" model="title" :label="__('tables.articles.title')" :$sortDirection
                            :$sortBy :appLocal=true />

                        <x-core.table.sortable-th wire:key="Art-TH-2" model="author" :label="__('tables.articles.author')" :$sortDirection
                            :$sortBy />



                        <x-core.table.sortable-th wire:key="art-TH-4" model="state" :label="__('tables.articles.state')" :$sortDirection
                            :$sortBy />

                        <x-core.table.sortable-th wire:key="arT-TH-5" model="created_at" :label="__('tables.articles.created_at')"
                            :$sortDirection :$sortBy />


                    </tr>
                </thead>

                <tbody>
                    @foreach ($this->articles as $article)
                        <tr wire:key="{{ $article->id }}-gt">
                            <td>
                                    <x-core.button
                                    variant="danger"
                                    icon="delete"
                                    function="openDeleteDialog"
                                    :parameters="[$article]"
                                     rounded=true
                                     hasTooltip=true
                                     :tooltip="__('toolTips.article.delete')"
                                     />


                                <livewire:core.open-modal-button wire:key="edit-aR-{{ $article->id }}" rounded=true
                                    hasTooltip=true
                                    icon="edit"
                                    :tooltip="__('toolTips.article.update')"
                                     modalTitle="modals.article.actions.update"
                                     :modalTitleOptions="['title'=>$article->title]"
                                    :modalContent="[
                                        'name' => 'core.author.article-modal',
                                        'parameters' => ['id' => $article->id],
                                        ]"
                                     :containsTinyMce=true
                                     />

                            </td>
                            <td scope="row">{{ $article->title }}</td>
                            <td>{{ $article->author }}</td>
                            <td>
                                <livewire:core.table-selector wire:key="art-P-{{ $article->id }}" :data="$stateOptions"
                                    :selectedValue="$article->state" :entity="$article" lazy />
                            </td>
                            <td>{{ $article->created_at->format('Y-m-d') }}</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $this->articles->links('components.core.pagination') }}
    @else
        <div class="table__footer">
            <h2>@lang('tables.articles.not_found')</h2>
        </div>
    @endif
</div>
