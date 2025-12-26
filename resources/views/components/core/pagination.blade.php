<div class="pagination__container">
    @if ($paginator->hasPages())
      <nav role="navigation" aria-label="Pagination Navigation" class="pagination">
        <div class="pagination__content">
          <div class="pagination__info">
            <p>
                {!! __('pagination.info',
                ['first'=>$paginator->firstItem() ,
                'last'=> $paginator->lastItem(),
                'total'=>$paginator->total()] )!!}
            </p>
          </div>

          <div class="pagination__links">

              {{-- First Page Button --}}
              <button type="button"
                      wire:click="gotoPage(1, '{{ $paginator->getPageName() }}')"
                      wire:loading.attr="disabled"
                      class="button"
                      aria-label="{{ __('pagination.first') }}"
                      @if ($paginator->onFirstPage())
                        disabled
                      @endif
              >
              <i class="fa-solid fa-angles-left"></i>
              </button>

              {{-- Previous Page Link --}}
              <button type="button"
                      wire:click="previousPage('{{ $paginator->getPageName() }}')"
                      wire:loading.attr="disabled"
                      class="button"
                      aria-label="{{ __('pagination.previous') }}"
                      @if ($paginator->onFirstPage())
                        disabled
                      @endif
              >
              <i class="fa-solid fa-angle-left"></i>
              </button>

              {{-- Next Page Link --}}
              <button type="button"
                      wire:click="nextPage('{{ $paginator->getPageName() }}')"
                      wire:loading.attr="disabled"
                      class="button"
                      aria-label="{{ __('pagination.next') }}"
                      @if (!$paginator->hasMorePages())
                        disabled
                      @endif
              >
              <i class="fa-solid fa-angle-right"></i>
              </button>

              {{-- Last Page Button --}}
              <button type="button"
                      wire:click="gotoPage({{ $paginator->lastPage() }}, '{{ $paginator->getPageName() }}')"
                      wire:loading.attr="disabled"
                      class="button"
                      aria-label="{{ __('pagination.last') }}"
                      @if (!$paginator->hasMorePages())
                        disabled
                      @endif
              >
              <i class="fa-solid fa-angles-right"></i>
              </button>

          </div>
        </div>
      </nav>
    @endif
  </div>
