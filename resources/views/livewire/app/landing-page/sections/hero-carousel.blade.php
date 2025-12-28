      <section class="carousel hero__carousel" aria-roledescription="carousel" aria-label="Highlighted television shows">
          <div id="myCarousel-items" class="carousel__items" aria-live="off">

              @if (count($hero->images) >0)
                  @foreach ($hero->images as $image)
                      <div class="carousel__item" role="group" aria-roledescription="slide">
                          <div class="carousel__item__image">
                              <a href="#">
                                  <img src="{{ $image->url }}" alt="{{ $image->display_name }}" />
                              </a>
                          </div>
                      </div>
                  @endforeach
              @else
                  <div class="carousel__item" role="group" aria-roledescription="slide">
                      <div class="carousel__item__image">
                          <a href="#">
                              <img src="{{ asset('assets/app/images/Hero/1.jpg') }}" alt="boat" />
                          </a>
                      </div>
                  </div>
                  <div class="carousel__item" role="group" aria-roledescription="slide">
                      <div class="carousel__item__image">
                          <a href="#">
                              <img src="{{ asset('assets/app/images/Hero/2.jpg') }}" alt="fishing" />
                          </a>
                      </div>
                  </div>
                  <div class="carousel__item" role="group" aria-roledescription="slide">
                      <div class="carousel__item__image">
                          <a href="#">
                              <img src="{{ asset('assets/app/images/Hero/3.jpg') }}" alt="anchor" />
                          </a>
                      </div>
                  </div>
                  <div class="carousel__item" role="group" aria-roledescription="slide">
                      <div class="carousel__item__image">
                          <a href="#">
                              <img src="{{ asset('assets/app/images/Hero/4.jpg') }}" alt="anchor" />
                          </a>
                      </div>
                  </div>
                @endIf
          </div>
      </section>
