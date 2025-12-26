      <section class="hero" id="hero">
          <div class="hero__content">
              <h2>{{ $hero->title }}</h2>
              <p>
                  {{ $hero->introduction }}
              </p>


              <x-core.button variant="action"  href="#aboutUs"  :text="$hero->primary_call_to_action" />
          </div>

          <a href="#aboutUs" class="hero__arrow">
              <img src="assets/app/images/triangle.png" alt="triangle" />
          </a>
      </section>
