      <div class="modal__body__content">

          <form class="table__form">

              <div class="table__form__inputs">
                  <div class="row">
                      <x-core.form.selector htmlId="OM-FO" model="{{ $form }}.field_id" :label="__('forms.occupation.field_id')"
                          :data="$fieldsOptions" :showError="true" type="filter" />
                      <x-core.form.selector htmlId="OM-FGO" model="{{ $form }}.field_grade_id" :label="__('forms.occupation.field_grade_id')"
                          :data="$fieldGradesOptions" :showError="true" type="filter" />
                  </div>
                  <div class="row">
                      <x-core.form.input model="{{ $form }}.experience" :label="__('forms.occupation.experience')" type="number"
                          html_id="OM-EXP" />
                      <x-core.form.selector htmlId="OM-FSO" model="{{ $form }}.field_specialty_id"
                          :label="__('forms.occupation.field_specialty_id')" :data="$fieldSpecialtiesOptions" :showError="true" type="filter" />
                  </div>
                  <div class="row">
                      <x-core.form.textarea model="{{ $form }}.description_fr" :label="__('forms.occupation.description_fr')"
                          html_id="OM-DFR" />
                      <x-core.form.textarea model="{{ $form }}.description_ar" :label="__('forms.occupation.description_ar')"
                          html_id="OM-DAR" />
                  </div>
                  <div class="row">
                      <x-core.form.textarea model="{{ $form }}.description_en" :label="__('forms.occupation.description_en')"
                          html_id="OM-DEN" />
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
          <div class="table__container" x-on:update-occupations-table.window="$wire.$refresh()">
              <div class="table__header">
                  <h3>@lang('tables.occupations.info_custom', ['name' => $employeeName])</h3>
              </div>

              @if (isset($this->occupations) && $this->occupations->isNotEmpty())
                  <div class="table__body">
                      <table class="table">
                          <thead>
                              <tr>
                                  <th></th>
                                  <th scope="column">
                                      <div>@lang('tables.common.actions')</div>
                                  </th>
                                  <x-core.table.sortable-th wire:key="UO-TH-3" model="field" :label="__('tables.occupations.field')"
                                      :$sortDirection :$sortBy />
                                  <x-core.table.sortable-th wire:key="UO-TH-4" model="specialty" :label="__('tables.occupations.specialty')"
                                      :$sortDirection :$sortBy />
                                  <x-core.table.sortable-th wire:key="UO-TH-5" model="grade" :label="__('tables.occupations.grade')"
                                      :$sortDirection :$sortBy />
                                  <x-core.table.sortable-th wire:key="UO-TH-2" model="experience" :label="__('tables.occupations.experience')"
                                      :$sortDirection :$sortBy />
                                  <x-core.table.sortable-th wire:key="UO-TH-6" model="is_active" :label="__('tables.occupations.is_active')"
                                      :$sortDirection :$sortBy />
                                  <x-core.table.sortable-th wire:key="UO-TH-7" model="created_at" :label="__('tables.occupations.created_at')"
                                      :$sortDirection :$sortBy />

                              </tr>
                          </thead>
                          <tbody>
                              @foreach ($this->occupations as $o)
                                  <tr wire:key="{{ $o->id }}" scope="row">
                                      <td>
                                          <x-core.button variant="danger" hasTooltip=true rounded=true :tooltip="__('toolTips.occupation.delete')" icon="delete"
                                              function="openDeleteOccupationDialog" :parameters="[$o]" />
                                      </td>
                                      <td>
                                          <x-core.form.radio-button model="selectedChoice"
                                              htmlId="oc-id{{ $o->id }}" value="{{ $o->id }}"
                                              type="forTable" wire:key="oc-key{{ $o->id }}" />
                                      </td>
                                      <td>{{ $o->field }}</td>
                                      <td>{{ $o->specialty }}</td>
                                      <td>{{ $o->fieldGrade }}</td>
                                      <td>{{ $o->experience }}</td>
                                      <td>
                                          <x-core.form.radio-button model="activeOccupationId"
                                              htmlId="aoc-id{{ $o->id }}" value="{{ $o->id }}"
                                              type="forTable" wire:key="aoc-key{{ $o->id }}" />
                                      </td>
                                      <td>{{ $o->created_at->format('Y-m-d') }}</td>

                                  </tr>
                              @endforeach
                          </tbody>
                      </table>
                  </div>
              @else
                  <div class="table__footer">
                      <h2>@lang('tables.occupations.not_found')</h2>
                  </div>
              @endif
          </div>
      </div>
