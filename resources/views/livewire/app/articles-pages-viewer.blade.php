      <div class="pages__container">
          <div class="page">
              @if (isset($this->articles) && count($this->articles))

                  @foreach ($this->articles as $article)
                      <livewire:app.cards.article :$article wire:key="articleCard-{{ $article->title }}" />
                  @endforeach
              @endIf
          </div>
          <div class="pages__container__bottom">
              {{ $this->articles->links('components.core.pagination') }}
          </div>
      </div>
