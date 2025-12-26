      <div class="modal__body__content">
          <!-- Form Section -->



          <form class="table__form">

              <div class="table__form__inputs">
                  <div class="row">
                      <x-core.form.input model="{{ $form }}.agency_fr" :label="__('forms.banking_information.agency_fr')" type="text"
                          html_id="BIM-agency-fr" />
                      <x-core.form.input model="{{ $form }}.agency_ar" :label="__('forms.banking_information.agency_ar')" type="text"
                          html_id="BIM-agency-ar" />
                      <x-core.form.input model="{{ $form }}.agency_en" :label="__('forms.banking_information.agency_en')" type="text"
                          html_id="BIM-agency-en" />
                  </div>
                  <div class="row">
                      <x-core.form.input model="{{ $form }}.agency_code" :label="__('forms.banking_information.agency_code')" type="text"
                          html_id="BIM-agency-code" />
                      <x-core.form.input model="{{ $form }}.account_number" :label="__('forms.banking_information.account_number')" type="text"
                          html_id="BIM-account-number" />

                  </div>
                  <div class="row">
                      <x-core.form.selector htmlId="BIM-bank-id" model="{{ $form }}.bank_id" :label="__('forms.banking_information.bank_id')"
                          :data="$banksOptions" :showError="true" />
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

          <!-- Form Buttons Container -->


          <!-- Table Section -->
          <div class="table__container" x-on:update-banking-information-table.window="$wire.$refresh()">
              <div class="table__header">
                  <h3>@lang('tables.banking_information.info_custom', ['name' => $bankableName])</h3>
              </div>

              @if (isset($this->bankingInformations) && $this->bankingInformations->isNotEmpty())
                  <div class="table__body">
                      <table class="table">
                          <thead>
                              <tr>
                                  <th></th>
                                  <th scope="column">
                                      <div>@lang('tables.common.actions')</div>
                                  </th>
                                  <x-core.table.sortable-th wire:key="UBKI-TH-1" model="bank_acronym" :label="__('tables.banking_information.bank_acronym')"
                                      :$sortDirection :$sortBy />
                                  <x-core.table.sortable-th wire:key="UBKI-TH-2" model="agency" :label="__('tables.banking_information.agency')"
                                      :$sortDirection :$sortBy />
                                  <x-core.table.sortable-th wire:key="UBKI-TH-3" model="agency_code" :label="__('tables.banking_information.agency_code')"
                                      :$sortDirection :$sortBy />
                                  <x-core.table.sortable-th wire:key="UBKI-TH-4" model="account_number"
                                      :label="__('tables.banking_information.account_number')" :$sortDirection :$sortBy />
                                  <x-core.table.sortable-th wire:key="UBKI-TH-6" model="is_active" :label="__('tables.banking_information.is_active')"
                                      :$sortDirection :$sortBy />

                                  <x-core.table.sortable-th wire:key="UBKI-TH-7" model="created_at" :label="__('tables.banking_information.created_at')"
                                      :$sortDirection :$sortBy />
                              </tr>
                          </thead>
                          <tbody>
                              @foreach ($this->bankingInformations as $bi)
                                  <tr wire:key="{{ 'bkm-' . $bi->id }}" scope="row">
                                      <!-- Radio Button -->
                                      <td>
                                          <x-core.form.radio-button model="selectedChoice"
                                              htmlId="{{ 'bkm-id' . $bi->id }}" value="{{ $bi->id }}"
                                              type="forTable" wire:key="{{ 'bkm-key-' . $bi->id }}" />
                                      </td>
                                      <!-- Actions -->
                                      <td>
                                          <x-core.button hasTooltip=true :tooltip="__('toolTips.banking_information.delete')" rounded=true icon="delete"
                                              function="openDeleteBankingInformationDialog" :parameters="[$bi]" />


                                      </td>
                                      <!-- Details -->
                                      <td>{{ $bi->bank_acronym }}</td>
                                      <td>{{ $bi->agency }}</td>
                                      <td>{{ $bi->agency_code }}</td>
                                      <td>{{ $bi->account_number }}</td>
                                      <!-- Active Status -->
                                      <td>
                                          <x-core.form.radio-button model="activeBankingInformationId"
                                              htmlId="{{ 'bnkI-id' . $bi->id }}" value="{{ $bi->id }}"
                                              type="forTable" wire:key="{{ 'bnkI-key-' . $bi->id }}" />
                                      </td>
                                      <!-- Created At -->
                                      <td>{{ $bi->created_at->format('Y-m-d') }}</td>
                                  </tr>
                              @endforeach
                          </tbody>
                      </table>
                  </div>
              @else
                  <div class="table__footer">
                      <h2>@lang('tables.banking_information.not_found')</h2>
                  </div>
              @endif
          </div>
      </div>
