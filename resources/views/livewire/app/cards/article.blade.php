<div class="article__container {{ $less ? '' : 'extend' }}">

    {{-- Header --}}
    <div class="article__header">
        @if ($article->images->isNotEmpty())
            <div class="article__carousel">
                <div class="carousel" aria-roledescription="carousel">
                    <div class="controls">
                        <button type="button" class="button transparent rounded previous">
                            <i class="fa-solid fa-arrow-left"></i>
                        </button>
                        <button type="button" class="button transparent rounded rotation"></button>
                        <button type="button" class="button transparent rounded next">
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>

                    <div class="carousel__items">
                        @foreach ($article->images as $image)
                            <div class="carousel__item">
                                <img src="{{ $image->url }}" alt="{{ $image->display_name }}">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Info --}}
    <div class="article__header__info">
        <h2>{{ $article->title }}</h2>
        <span>{{ $article->published_at }}</span>
    </div>

    {{-- Content --}}
    <div class="article">


       <livewire:core.tiny-mce-text-area htmlId="MAContentArticle"
            wire:key="MaContentAr" :content="$article->content" :viewOnly=true />
    </div>

    {{-- Footer --}}
    <div class="article__footer">
        <button
            class="button button--action"
            wire:click="toggleLess"
        >
            {{ $less ? __('cards.article.extend') : __('cards.article.less') }}
            <i class="fa-solid fa-arrow-down"></i>
        </button>
    </div>

</div>
