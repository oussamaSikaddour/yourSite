import { throttle } from "../../utils/General";

/**
 * Perfect infinite loop slider (DOM push/pop)
 * - Uses transform-based animation: center at translateX(-100%)
 * - On move: animate to 0% (prev) or -200% (next)
 * - On transitionend: reorder DOM (prepend/append) then snap back to -100% w/o animation
 * - On resize: recompute itemsPerScreen (CSS var), re-prime loop, rebuild progress bar
 */

/* -------------------- Helpers -------------------- */

function getItemsPerScreen(slides) {
  const style = getComputedStyle(slides);
  const n = parseInt(style.getPropertyValue("--item-per-slide"), 10);
  return Number.isFinite(n) && n > 0 ? n : 1;
}

function setTransform(slides, value, animate) {
  if (!animate) {
    slides.style.transition = "none";
    slides.style.transform = value;
    // force reflow so the next transition is reliable
    // eslint-disable-next-line no-unused-expressions
    slides.offsetHeight;
    slides.style.transition = "";
  } else {
    slides.style.transform = value;
  }
}

function updateActive(progressBar, prevIndex, nextIndex) {
  const prev = progressBar.children[prevIndex];
  const next = progressBar.children[nextIndex];
  if (prev) prev.classList.remove("active");
  if (next) next.classList.add("active");
}

function buildProgressBar(progressBar, count, activeIndex) {
  const frag = document.createDocumentFragment();
  for (let i = 0; i < count; i++) {
    const item = document.createElement("div");
    item.className =
      "slider__progress__item" + (i === activeIndex ? " active" : "");
    item.dataset.index = String(i);
    frag.appendChild(item);
  }
  progressBar.replaceChildren(frag);
}

/**
 * With a true loop (DOM reorder), there isn't a "real start/end",
 * but progress dots still represent pages in the original set order.
 * We'll maintain a virtual page index: sliderContainer._pageIndex
 */
function calculateProgress(sliderContainer) {
  const slides = sliderContainer._slides;
  const progressBar = sliderContainer._progressBar;

  const totalItems = slides.children.length;
  const itemsPerScreen = sliderContainer._step || 1;

  const barCount = Math.max(1, Math.ceil(totalItems / itemsPerScreen));

  // clamp virtual index
  sliderContainer._pageIndex = Math.min(
    Math.max(0, sliderContainer._pageIndex || 0),
    barCount - 1
  );

  if (progressBar.children.length !== barCount) {
    buildProgressBar(progressBar, barCount, sliderContainer._pageIndex);
  } else {
    const prevActive = progressBar.querySelector(".active");
    const shouldBeActive = progressBar.children[sliderContainer._pageIndex];
    if (prevActive !== shouldBeActive) {
      if (prevActive) prevActive.classList.remove("active");
      if (shouldBeActive) shouldBeActive.classList.add("active");
    }
  }

  sliderContainer._barCount = barCount;
  return barCount;
}

/**
 * Prime the loop so there is always a buffer "page" on the left.
 * We do that by moving last `step` items to the front, then setting translateX(-100%).
 */
function primeLoop(slides, step) {
  const len = slides.children.length;
  if (len === 0) return;

  const k = Math.min(step, len);

  // Move last k items to the front
  for (let i = 0; i < k; i++) {
    slides.insertBefore(slides.lastElementChild, slides.firstElementChild);
  }

  // Center view (buffer on left + current page in middle)
  setTransform(slides, "translateX(-100%)", false);
}

/* -------------------- Move (push/pop DOM) -------------------- */

function moveLoop(sliderContainer, dir) {
  if (sliderContainer._isAnimating) return;

  const slides = sliderContainer._slides;
  const progressBar = sliderContainer._progressBar;
  const step = sliderContainer._step || 1;

  const barCount =
    sliderContainer._barCount || progressBar.children.length || 1;

  sliderContainer._isAnimating = true;

  // animate from center (-100%) to next (-200%) or prev (0%)
  const target = dir > 0 ? "translateX(-200%)" : "translateX(0%)";
  setTransform(slides, target, true);

  let fallbackId = null;

  const unlockAndReorder = (e) => {
    // Guard against bubbled transitionend from children
    if (e && e.target !== slides) return;

    slides.removeEventListener("transitionend", unlockAndReorder);

    // Reorder DOM after animation completes
    const len = slides.children.length;
    const k = Math.min(step, len);

    if (dir > 0) {
      // Next: move first k to end
      for (let i = 0; i < k; i++) {
        slides.appendChild(slides.firstElementChild);
      }
      // virtual index next
      const prev = sliderContainer._pageIndex;
      sliderContainer._pageIndex = (prev + 1 + barCount) % barCount;
      updateActive(progressBar, prev, sliderContainer._pageIndex);
    } else {
      // Prev: move last k to start
      for (let i = 0; i < k; i++) {
        slides.insertBefore(slides.lastElementChild, slides.firstElementChild);
      }
      // virtual index prev
      const prev = sliderContainer._pageIndex;
      sliderContainer._pageIndex = (prev - 1 + barCount) % barCount;
      updateActive(progressBar, prev, sliderContainer._pageIndex);
    }

    // Snap back to center without animation
    setTransform(slides, "translateX(-100%)", false);

    sliderContainer._isAnimating = false;

    if (fallbackId) window.clearTimeout(fallbackId);
  };

  slides.addEventListener("transitionend", unlockAndReorder, { once: false });

  // Fallback unlock in case transitionend doesn't fire (display:none, etc.)
  fallbackId = window.setTimeout(
    () => unlockAndReorder(),
    sliderContainer._transitionMs + 60
  );
}

/* -------------------- Autoplay -------------------- */

function stopAutoplay(sliderContainer) {
  if (sliderContainer._autoplayTimer) {
    clearTimeout(sliderContainer._autoplayTimer);
    sliderContainer._autoplayTimer = null;
  }
}

function scheduleNext(sliderContainer) {
  stopAutoplay(sliderContainer);

  sliderContainer._autoplayTimer = window.setTimeout(() => {
    if (!sliderContainer._paused) {
      moveLoop(sliderContainer, +1);
    }
    scheduleNext(sliderContainer);
  }, sliderContainer._autoplayMs);
}

function pause(sliderContainer) {
  sliderContainer._paused = true;
}

function resume(sliderContainer) {
  sliderContainer._paused = false;
}

/* -------------------- Resize handling -------------------- */

const sliders = new Set();

function refreshOnResize(sliderContainer) {
  if (!sliderContainer.isConnected) {
    sliders.delete(sliderContainer);
    return;
  }

  const slides = sliderContainer._slides;
  const newStep = getItemsPerScreen(slides);

  // If step changed, re-prime
  sliderContainer._step = newStep;

  // Reset transform and prime (keeps perfect loop after resize)
  setTransform(slides, "translateX(-100%)", false);
  primeLoop(slides, newStep);

  // Recalc progress bar with current virtual index
  calculateProgress(sliderContainer);
}

const onResize = throttle(() => {
  sliders.forEach((sc) => refreshOnResize(sc));
}, 200);

/* -------------------- Init -------------------- */

function initSlider(sliderContainer) {
  // avoid double init (SPA/hot reload)
  if (sliderContainer._loopInitialized) return;

  const slides = sliderContainer.querySelector(".slides");
  const progressBar = sliderContainer.querySelector(".slider__progress");
  if (!slides || !progressBar) return;

  sliderContainer._loopInitialized = true;

  sliderContainer._slides = slides;
  sliderContainer._progressBar = progressBar;

  // Tune to match CSS
  sliderContainer._autoplayMs = 2600;
  sliderContainer._transitionMs = 450; // MUST match CSS transition duration on .slides

  sliderContainer._paused = false;
  sliderContainer._isAnimating = false;
  sliderContainer._autoplayTimer = null;

  sliderContainer._step = getItemsPerScreen(slides);
  sliderContainer._pageIndex = 0; // virtual index for progress dots
  sliderContainer._barCount = 0;

  // Set initial state: buffer + center
  primeLoop(slides, sliderContainer._step);

  // Build progress bar
  calculateProgress(sliderContainer);

  // Handles (left/right)
  sliderContainer.addEventListener("click", (e) => {
    const handle = e.target.closest(".slider__handle");
    if (!handle) return;

    pause(sliderContainer);
    moveLoop(
      sliderContainer,
      handle.classList.contains("slider__handle--left") ? -1 : +1
    );
  });

  // Optional: click dots to jump (approximate: repeated moves)
  progressBar.addEventListener("click", (e) => {
    const dot = e.target.closest(".slider__progress__item");
    if (!dot) return;

    const target = parseInt(dot.dataset.index, 10);
    if (!Number.isFinite(target)) return;

    const barCount = sliderContainer._barCount || 1;
    const current = sliderContainer._pageIndex;

    if (target === current) return;

    pause(sliderContainer);

    // choose shortest direction
    const forward = (target - current + barCount) % barCount;
    const backward = (current - target + barCount) % barCount;

    const dir = forward <= backward ? +1 : -1;
    const steps = Math.min(forward, backward);

    // perform steps sequentially to keep DOM reorder consistent
    let remaining = steps;

    const chain = () => {
      if (remaining <= 0) return;
      if (sliderContainer._isAnimating) {
        window.setTimeout(chain, 30);
        return;
      }
      moveLoop(sliderContainer, dir);
      remaining -= 1;
      window.setTimeout(chain, sliderContainer._transitionMs + 30);
    };

    chain();
  });

  // Pause on hover / touch
  sliderContainer.addEventListener("mouseenter", () => pause(sliderContainer));
  sliderContainer.addEventListener("mouseleave", () => resume(sliderContainer));

  sliderContainer.addEventListener("touchstart", () => pause(sliderContainer), {
    passive: true,
  });
  sliderContainer.addEventListener("touchend", () => resume(sliderContainer), {
    passive: true,
  });
  sliderContainer.addEventListener(
    "touchcancel",
    () => resume(sliderContainer),
    { passive: true }
  );

  // Track for resize updates
  sliders.add(sliderContainer);

  scheduleNext(sliderContainer);
}

/* -------------------- Export -------------------- */

export default function Slider() {
  const sliderContainers = document.querySelectorAll(".slider__container");
  if (!sliderContainers.length) return;

  sliderContainers.forEach(initSlider);

  window.removeEventListener("resize", onResize);
  window.addEventListener("resize", onResize);
}
