<div class="modal__body">


    <form class="table__form">

        <div class="table__form__inputs">


            <!-- Image name -->
            <div class="row">
                <x-core.form.input model="{{ $form }}.display_name" :label="__('forms.image.display_name')" type="text"
                    html_id="IM-NM" />

                <!-- Use case -->
                <x-core.form.input model="{{ $form }}.use_case" :label="__('forms.image.use_case')" type="text" html_id="IM-UC" />

            </div>
            <!-- Upload -->
            <div class="column center" x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true"
                x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-cancel="uploading = false"
                x-on:livewire-upload-error="uploading = false"
                x-on:livewire-upload-progress="progress = $event.detail.progress">



                   <x-core.file-input  model="{{ $form }}.real_image"  types="img" type="image"   :fileUri="$temporaryImageUrl"/>
                <div x-show="uploading" class="progress__bar">
                    <progress max="100" x-bind:value="progress"></progress>
                </div>
            </div>



        </div>
        <!-- Form Buttons Container -->
        <div class="table__form__actions">

            <div wire:loading wire:target="handleSubmit">
                <x-core.loading />
            </div>

            {{-- Submit Button --}}
            <x-core.button hasTooltip=true :icon="$form === 'addForm' ? 'add' : 'edit'" :tooltip="$form === 'addForm' ? __('toolTips.common.add') : __('toolTips.common.update')" variant="primary" rounded=true
                function="handleSubmit" prevent=true />

            {{-- Reset Button --}}
            <x-core.button hasTooltip=true icon="refresh" :tooltip="__('toolTips.common.reset')" variant="warning" rounded=true
                function="resetForm" prevent=true />




        </div>
    </form>

    <!-- Table Section -->
    <div class="table__container" x-on:update-images-table.window="$wire.$refresh()">
        <div class="table__header">
            <h3>@lang('tables.images.info')</h3>
        </div>

        @if (isset($this->images) && $this->images->isNotEmpty())
            <div class="table__body">
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th scope="column">@lang('tables.common.actions')</th>
                            <x-core.table.sortable-th wire:key="IMG-TH-1" model="display_name" :label="__('tables.images.display_name')"
                                :$sortDirection :$sortBy />
                            <x-core.table.sortable-th wire:key="IMG-TH-2" model="use_case" :label="__('tables.images.use_case')"
                                :$sortDirection :$sortBy />
                            <x-core.table.sortable-th wire:key="IMG-TH-3" model="created_at" :label="__('tables.images.created_at')"
                                :$sortDirection :$sortBy />
                            <th scope="column">@lang('tables.images.preview')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($this->images as $img)
                            <tr wire:key="img-{{ $img->id }}" scope="row">
                                <!-- Delete -->


                                <!-- Select for edit -->
                                <td>
                                    <x-core.form.radio-button model="selectedChoice" htmlId="img-id{{ $img->id }}"
                                        value="{{ $img->id }}" type="forTable"
                                        wire:key="img-key{{ $img->id }}" />
                                </td>
                                <td>


                                    <x-core.button variant="danger" icon="delete" function="openDeleteImageDialog"
                                        :parameters="[$image]" rounded=true hasTooltip=true :tooltip="__('toolTips.image.delete')" />

                                </td>
                                <td>{{ $img->display_name }}</td>
                                <td>{{ $img->use_case }}</td>
                                <td>{{ $img->created_at->format('Y-m-d') }}</td>
                                <td>
                                    @if ($img->url)
                                        <img src="{{ $img->url }}" alt="{{ $img->name }}"
                                            class="table__img__preview">
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="table__footer">
                <h2>@lang('tables.images.not_found')</h2>
            </div>
        @endif
    </div>
</div>
