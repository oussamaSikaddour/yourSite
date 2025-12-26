      <div class="modal__body__content">
          <!-- Form Section -->


          <form class="table__form">

              <div class="table__form__inputs">
                  <div class="row">
                      <x-core.form.input model="{{ $form }}.designation_fr" :label="__('forms.commune.designation_fr')" type="text"
                          html_id="MCM-bFR" />
                      <x-core.form.input model="{{ $form }}.designation_ar" :label="__('forms.commune.designation_ar')" type="text"
                          html_id="MCM-bAR" />

                      <x-core.form.input model="{{ $form }}.designation_en" :label="__('forms.commune.designation_en')" type="text"
                          html_id="MCM-bEN" />
                  </div>
                  <div class="row">
                      <x-core.form.input model="{{ $form }}.code" :label="__('forms.commune.code')" type="text"
                          html_id="MCM-code" />
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
          <div class="table__container" x-on:update-communes-table.window="$wire.$refresh()">
              <div class="table__header">
                  <h3>@lang('tables.communes.info', ['code' => $dairaCode])</h3>


                  <div class="table__header__actions">

                      <span wire:loading wire:target="excelFile">
                          <x-core.loading />
                      </span>
                      <x-core.file-input icon="upload" :tooltip="__('toolTips.commune.excel.upload')" model="excelFile" types="excel"
                          type="icon_only" />


                      <x-core.button icon="export" function="generateEmptyCommunesExcel" rounded=true hasTooltip=true
                          :tooltip="__('toolTips.commune.excel.empty')" />


                      <x-core.button variant="success" icon="export" function="generateCommunesExcel" rounded=true
                          hasTooltip=true :tooltip="__('toolTips.commune.excel.empty')" />



                      <x-core.button icon="filter" rounded=true hasTooltip=true :tooltip="__('toolTips.common.filters')"
                          :extraClasses="['table__filters__btn']" />

                      <x-core.form.selector htmlId="TP-upp" model="perPage" :data="$perPageOptions" type="filter"
                          :tooltip="__('toolTips.common.per_page')" />
                  </div>
              </div>


              <div class="table__filters" wire:ignore>
                  <div class="form__container">
                      <form class="form">
                          <div class="row">
                              <x-core.form.input model="designation" :label="__('tables.communes.designation')" type="text"
                                  html_id="CommuneDesignationFilter" role="filter" />
                              <x-core.form.input model="code" :label="__('tables.communes.code')" type="text"
                                  html_id="CommuneCodeFilter" role="filter" />
                          </div>
                          <div class="row">



                              <x-core.button hasTooltip=true :tooltip="__('toolTips.common.resetFilters')" type="submit" variant="primary"
                                  function="resetFilters" prevent=true rounded=true icon="refresh" />
                          </div>
                      </form>
                  </div>
              </div>

              @if (isset($this->communes) && $this->communes->isNotEmpty())
                  <div class="table__body">
                      <table class="table">
                          <thead>
                              <tr>
                                  <th></th>
                                  <th>
                                      <div>@lang('tables.common.actions')</div>
                                  </th>
                                  <x-core.table.sortable-th model="code" :label="__('tables.communes.code')" :$sortDirection
                                      :$sortBy />
                                  <x-core.table.sortable-th model="designation_fr" :label="__('tables.communes.designation_fr')" :$sortDirection
                                      :$sortBy />
                                  <x-core.table.sortable-th model="designation_en" :label="__('tables.communes.designation_en')" :$sortDirection
                                      :$sortBy />
                                  <x-core.table.sortable-th model="designation_ar" :label="__('tables.communes.designation_ar')" :$sortDirection
                                      :$sortBy />
                                  <x-core.table.sortable-th model="created_at" :label="__('tables.communes.registration_date')" :$sortDirection
                                      :$sortBy />

                              </tr>
                          </thead>
                          <tbody>
                              @foreach ($this->communes as $commune)
                                  <tr wire:key="{{ $commune->id }}">
                                      <td>
                                          <x-core.form.radio-button model="selectedChoice"
                                              htmlId="communeM-id{{ $commune->id }}" value="{{ $commune->id }}"
                                              type="forTable" />
                                      </td>
                                      <td>

                                          <x-core.button variant="danger" icon="delete" function="openDeleteDialog"
                                              :parameters="[$commune]" rounded=true hasTooltip=true :tooltip="__('toolTips.commune.delete')" />

                                      </td>
                                      <td>{{ $commune->code }}</td>
                                      <td>{{ $commune->designation_fr }}</td>
                                      <td>{{ $commune->designation_en }}</td>
                                      <td>{{ $commune->designation_ar }}</td>
                                      <td>{{ $commune->created_at->format('Y-m-d') }}</td>

                                  </tr>
                              @endforeach
                          </tbody>
                      </table>
                  </div>
                  {{ $this->communes->links('components.core.pagination') }}
              @else
                  <div class="table__footer">
                      <h2>@lang('tables.communes.not_found')</h2>
                  </div>
              @endif
          </div>
      </div>
