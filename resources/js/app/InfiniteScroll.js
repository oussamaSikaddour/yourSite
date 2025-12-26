// --- Public API: setScroller content before initialization ---
export const setScroller = (scroller, html, reverse = false) => {
  if (!scroller) return;

  if (reverse) scroller.classList.add("reverse");

  const inner = scroller.querySelector(".scroller__inner");
  if (inner) inner.innerHTML = html;
};

// --- Duplicate original items for infinite scroll effect ---
const duplicateChildren = (inner) => {
  const children = Array.from(inner.children);
  children.forEach((item) => {
    const clone = item.cloneNode(true);
    clone.setAttribute("aria-hidden", "true");
    inner.appendChild(clone);
  });
};

// --- Initialize a scroller ---
const launchScroller = (scroller) => {
  const inner = scroller.querySelector(".scroller__inner");
  if (!inner || !inner.children.length) return;

  duplicateChildren(inner);
  addScrollerEvents(scroller);
};

// --- Stop/Start scrolling ---
const stopScrolling = (scroller) => scroller.classList.add("stop");
const startScrolling = (scroller) => scroller.classList.remove("stop");

// --- Scroll one element left or right ---
const scrollOneChild = (scroller, reverse = false) => {
  stopScrolling(scroller);

  const inner = scroller.querySelector(".scroller__inner");
  if (!inner) return;

  const first = inner.children[0];
  const width = first.offsetWidth + 10;

  if (reverse) {
    inner.insertBefore(inner.lastElementChild, first);
    scroller.scrollLeft += width;
  }

  scroller.scrollBy({
    left: reverse ? -width : width,
    behavior: "smooth",
  });

  setTimeout(() => {
    if (!reverse) {
      inner.appendChild(inner.firstElementChild);
      scroller.scrollLeft -= width;
    }
    startScrolling(scroller);
  }, 500);
};

// --- Hover / Touch events ---
const addScrollerEvents = (scroller) => {
  const pause = () => stopScrolling(scroller);
  const resume = () => startScrolling(scroller);

  scroller.addEventListener("mouseenter", pause);
  scroller.addEventListener("mouseleave", resume);
  scroller.addEventListener("touchstart", pause);
  scroller.addEventListener("touchend", resume);
};

// --- HTML templates for easy reuse ---
const PRODUCTS_HTML = `
<div class="product">
<h1 class="product__brand">Hp</h1>
<div class="product__cover"><img src="img/lenovo-v14/1.png" alt="" /></div>
<div class="product__content">
  <h3>Wirless Headphones</h3>
  <h2 class="product__heading">DA 4900.<small>99</small></h2>
  <a href="product.html" class="button rounded transparent"><span><i class="fa-regular fa-eye"></i></span></a>
</div>
</div>

<div class="product">
<h1 class="product__brand">Dell</h1>
<div class="product__cover"><img src="img/lenovo-v15/1.png" alt="" /></div>
<div class="product__content">
  <h3>dell 52644x</h3>
  <h2 class="product__heading">DA 9000.<small>99</small></h2>
  <a href="product.html" class="button rounded transparent"><span><i class="fa-regular fa-eye"></i></span></a>
</div>
</div>

<div class="product">
<h1 class="product__brand">Dell</h1>
<div class="product__cover"><img src="img/canon/1.png" alt="" /></div>
<div class="product__content">
  <h3>dell 52644x</h3>
  <h2 class="product__heading">DA 9000.<small>99</small></h2>
  <a href="product.html" class="button rounded transparent"><span><i class="fa-regular fa-eye"></i></span></a>
</div>
</div>

<div class="product">
<h1 class="product__brand">Dell</h1>
<div class="product__cover"><img src="img/hp-printer/1.png" alt="" /></div>
<div class="product__content">
  <h3>dell 52644x</h3>
  <h2 class="product__heading">DA 9000.<small>99</small></h2>
  <a href="product.html" class="button rounded transparent"><span><i class="fa-regular fa-eye"></i></span></a>
</div>
</div>
`;

// --- Initialize all scrollers automatically ---
export const Scroller = () => {
  const scrollers = document.querySelectorAll(".scroller");
  if (!scrollers.length) return;

  scrollers.forEach((scroller) => {
    const leftBtn = scroller.parentElement.querySelector(
      ".scroller__btn--left"
    );
    const rightBtn = scroller.parentElement.querySelector(
      ".scroller__btn--right"
    );

    if (rightBtn)
      rightBtn.addEventListener("click", () => scrollOneChild(scroller));
    if (leftBtn)
      leftBtn.addEventListener("click", () => scrollOneChild(scroller, true));

    launchScroller(scroller);
  });
};

// --- Optional: auto-inject your specific scrollers when the module is imported ---
// --- Optional: auto-inject your specific scrollers when the module is imported ---
export const InfiniteScroll = (scrollerElement, scrollerElementInnerHtml) => {
  // Validate scrollerElement is a DOM element
  if (!(scrollerElement instanceof HTMLElement)) {
    return;
  }

  // Set up the scroller
  setScroller(scrollerElement, scrollerElementInnerHtml, true);

  // Return the scroller instance or start it
  return Scroller();
};

export default InfiniteScroll;
