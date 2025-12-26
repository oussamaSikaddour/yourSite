const createCarousel = (container) => {
  // Cache DOM elements only once
  const items = Array.from(container.querySelectorAll('.carousel__item'));
  const buttons = Array.from(container.querySelectorAll('.controls button'));
  const rotationButton = container.querySelector('.rotation');

  let currentIndex = 0;
  let intervalId = null;

  const updateRotationButton = (isPlaying) => {
    if (!rotationButton) return;
    rotationButton.innerHTML = isPlaying
      ? '<span><i class="fa-solid fa-pause"></i></span>'
      : '<span><i class="fa-solid fa-play"></i></span>';
  };

  const updateCarousel = () => {
    items.forEach((item, i) => {
      item.classList.toggle("show", i === currentIndex);
    });

    items[currentIndex].setAttribute(
      'aria-label',
      `${currentIndex + 1} of ${items.length}`
    );
  };

  const goToSlide = (index) => {
    currentIndex = (index + items.length) % items.length;
    updateCarousel();
  };

  const goToNextSlide = () => goToSlide(currentIndex + 1);
  const goToPreviousSlide = () => goToSlide(currentIndex - 1);

  const startRotation = () => {
    if (intervalId) return; // already playing
    intervalId = setInterval(goToNextSlide, 3000);
    updateRotationButton(true);
  };

  const stopRotation = () => {
    if (!intervalId) return; // already stopped
    clearInterval(intervalId);
    intervalId = null;
    updateRotationButton(false);
  };

  const toggleRotation = () => {
    intervalId ? stopRotation() : startRotation();
  };

  const handleButtonClick = ({ currentTarget }) => {
    const isPrev = currentTarget.classList.contains('previous');
    const isNext = currentTarget.classList.contains('next');
    const isRotate = currentTarget.classList.contains('rotation');

    if (isPrev) {
      stopRotation();
      goToPreviousSlide();
      return;
    }

    if (isNext) {
      stopRotation();
      goToNextSlide();
      return;
    }

    if (isRotate) {
      toggleRotation();
    }
  };

  // Initialize event listeners once
  buttons.forEach((btn) => btn.addEventListener('click', handleButtonClick));

  // Initial state
  goToSlide(0);
  startRotation();
};

const Carousel = () => {
  document.querySelectorAll('.carousel').forEach(createCarousel);
};

export default Carousel;
