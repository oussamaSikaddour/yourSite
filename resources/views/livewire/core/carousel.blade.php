<section
class="carousel {{ $variant }}"
aria-roledescription="carousel"
aria-label="Highlighted television shows">

@if($showControls)
       <div class="controls">
              <button
                 type="button"
                  class="button rounded previous transparent"
                    aria-controls="myCarousel-items"
                     aria-label="Previous Slide">
                   <span><i class="fa-solid fa-arrow-left"></i></span>
                </button>
              <button
                   type="button"
                   class="button rounded rotation transparent"
                    aria-label="Stop automatic slide show">

               </button>
             <button
                    type="button"
                     class=" button rounded next transparent"
                       aria-controls="myCarousel-items"
                      aria-label="Next Slide">
               <span><i class="fa-solid fa-arrow-right"></i></span>
             </button>
          </div>
@endif
<div  class="carousel__items" aria-live="off">

    @if (is_array($slides) && count($slides) > 0)


    @foreach ($slides as $index => $slide)
        <div class="carousel__item " role="group" aria-roledescription="slide">
            <div class="carousel__item__image">
                <a href="#">
                    <img src="{{ $slide['image'] }}" alt="{{ $slide['title'] }}">
                </a>
            </div>

             @if($slide['title'] && $slide['caption'])
            <div class="carousel__item__caption">
                <h3>{{ $slide['title'] }}</h3>
                <div>
                    <p>{{ $slide['caption'] }}</p>
                </div>
            </div>
            @endIf
        </div>
    @endforeach
    @endif
</div>
</section>
