      <div class="pages__container">
          <div class="page">
              @if (isset($this->services) && count($this->services))

                  @foreach ($this->services as $service)
                      <livewire:app.cards.service
                      :$formServicesPublic
                      :$service wire:key="serviceCard-{{ $service->name }}"
                       />
                  @endforeach
              @endIf
          </div>
          <div class="pages__container__bottom">
              {{ $this->services->links('components.core.pagination') }}
          </div>
      </div>
