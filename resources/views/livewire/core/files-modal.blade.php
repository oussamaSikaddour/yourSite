      <div class="modal__body__content">



          <form class="table__form">



              <div class="table__form__inputs">

                  <!-- File name -->
                  <div class="row">
                      <x-core.form.input model="{{ $form }}.display_name" :label="__('forms.file.display_name')" type="text"
                          html_id="FL-NM" />

                      <!-- Use case -->
                      <x-core.form.input model="{{ $form }}.use_case" :label="__('forms.file.use_case')" type="text"
                          html_id="FL-UC" />

                  </div>
                  <!-- Upload -->
                  <div class="column center" x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true"
                      x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-cancel="uploading = false"
                      x-on:livewire-upload-error="uploading = false"
                      x-on:livewire-upload-progress="progress = $event.detail.progress">


                           <x-core.file-input  model="{{ $form }}.real_file"  types="pdf" type="default"   :fileUri="$temporaryFileName"/>

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
          <div class="table__container" x-on:update-files-table.window="$wire.$refresh()">
              <div class="table__header">
                  <h3>@lang('tables.files.info')</h3>
              </div>

              @if (isset($this->files) && $this->files->isNotEmpty())
                  <div class="table__body">
                      <table class="table">
                          <thead>
                              <tr>
                                  <th></th>
                                  <th scope="column">@lang('tables.common.actions')</th>
                                  <x-core.table.sortable-th wire:key="FL-TH-1" model="display_name" :label="__('tables.files.display_name')"
                                      :$sortDirection :$sortBy />
                                  <x-core.table.sortable-th wire:key="FL-TH-2" model="use_case" :label="__('tables.files.use_case')"
                                      :$sortDirection :$sortBy />
                                  <x-core.table.sortable-th wire:key="FL-TH-3" model="created_at" :label="__('tables.files.created_at')"
                                      :$sortDirection :$sortBy />
                                  <th scope="column">@lang('tables.files.preview')</th>
                              </tr>
                          </thead>
                          <tbody>
                              @foreach ($this->files as $file)
                                  <tr wire:key="file-{{ $file->id }}" scope="row">
                                      <!-- Select for edit -->
                                      <td>
                                          <x-core.form.radio-button model="selectedChoice"
                                              htmlId="file-id{{ $file->id }}" value="{{ $file->id }}"
                                              type="forTable" wire:key="file-key{{ $file->id }}" />
                                      </td>

                                      <!-- Delete -->
                                      <td>

                                          <x-core.button variant="danger" icon="delete" function="openDeleteFileDialog"
                                              :parameters="[$file]" rounded=true hasTooltip=true :tooltip="__('toolTips.file.delete')" />

                                      </td>

                                      <!-- File data -->
                                      <td>{{ $file->display_name }}</td>
                                      <td>{{ $file->use_case }}</td>
                                      <td>{{ $file->created_at->format('Y-m-d') }}</td>

                                      <!-- Preview (link) -->
                                      <td>
                                          @if ($file->url)
                                              <a href="{{ $file->url }}" target="_blank"
                                                  class="button button--primary">
                                                  <i class="fa-solid fa-download"></i>
                                                  @lang('tables.files.download')
                                              </a>
                                          @else
                                              <span class="text-muted">@lang('tables.files.no_file')</span>
                                          @endif
                                      </td>
                                  </tr>
                              @endforeach
                          </tbody>
                      </table>
                  </div>
              @else
                  <div class="table__footer">
                      <h2>@lang('tables.files.not_found')</h2>
                  </div>
              @endif
          </div>
      </div>
