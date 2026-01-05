/**
 * initGrid
 * Vanilla JS initializer for:
 * - Normal responsive grid
 * - Resizable split grid if .grid__divider exists inside .grid
 *
 * Only classes used:
 * - .grid
 * - .grid__slot
 * - .grid__divider
 *
 * Usage:
 * import initGrid from "./grid.js";
 * initGrid();
 */

export default function Grid(options = {}) {
  const {
    selector = ".grid",
    minLeft = 280,
    minRight = 280,
    dividerWidthVar = "--divider",
    leftWidthVar = "--left",
    draggingClass = "is-dragging",
    observe = false,
  } = options;

  const clamp = (val, min, max) => Math.min(Math.max(val, min), max);

  function setupGrid(grid) {
    // Avoid double init
    if (grid.__gridInitialized) return;
    grid.__gridInitialized = true;

    const divider = grid.querySelector(".grid__divider");
    if (!divider) return; // Not split grid → nothing to do

    // Make divider focusable for keyboard resizing
    if (!divider.hasAttribute("tabindex")) divider.setAttribute("tabindex", "0");
    divider.setAttribute("role", "separator");
    divider.setAttribute("aria-orientation", "vertical");

    let dragging = false;

    const getDividerWidth = () => {
      const cssVal = getComputedStyle(grid).getPropertyValue(dividerWidthVar).trim();
      const parsed = parseFloat(cssVal);
      if (!Number.isNaN(parsed)) return parsed;

      // fallback to element width
      return divider.offsetWidth || 10;
    };

    const getLeftPx = () => {
      const rect = grid.getBoundingClientRect();
      const current = getComputedStyle(grid).getPropertyValue(leftWidthVar).trim();

      // if % convert to px
      if (current.endsWith("%")) {
        const pct = parseFloat(current);
        return (rect.width * pct) / 100;
      }

      // else assume px
      const px = parseFloat(current);
      if (!Number.isNaN(px)) return px;

      // fallback to half
      return rect.width / 2;
    };

    const setLeftPx = (px) => {
      grid.style.setProperty(leftWidthVar, `${px}px`);
    };

    const onMove = (e) => {
      if (!dragging) return;

      const rect = grid.getBoundingClientRect();
      const x = e.clientX - rect.left;

      const dividerW = getDividerWidth();
      const maxLeft = rect.width - minRight - dividerW;

      const leftPx = clamp(x, minLeft, maxLeft);
      setLeftPx(leftPx);
    };

    const stop = () => {
      if (!dragging) return;
      dragging = false;
      grid.classList.remove(draggingClass);

      document.removeEventListener("pointermove", onMove);
      document.removeEventListener("pointerup", stop);
    };

    const start = (e) => {
      // Divider hidden on small screens → ignore dragging
      if (getComputedStyle(divider).display === "none") return;

      dragging = true;
      grid.classList.add(draggingClass);

      divider.setPointerCapture?.(e.pointerId);

      document.addEventListener("pointermove", onMove);
      document.addEventListener("pointerup", stop);
    };

    divider.addEventListener("pointerdown", start);

    // Keyboard resizing (ArrowLeft/ArrowRight)
    divider.addEventListener("keydown", (e) => {
      const rect = grid.getBoundingClientRect();
      const dividerW = getDividerWidth();
      const maxLeft = rect.width - minRight - dividerW;

      const currentPx = getLeftPx();
      const step = e.shiftKey ? 40 : 16;

      if (e.key === "ArrowLeft") {
        setLeftPx(clamp(currentPx - step, minLeft, maxLeft));
        e.preventDefault();
      }

      if (e.key === "ArrowRight") {
        setLeftPx(clamp(currentPx + step, minLeft, maxLeft));
        e.preventDefault();
      }
    });

    // store cleanup so you can destroy later if needed
    grid.__gridDestroy = () => {
      divider.removeEventListener("pointerdown", start);
      stop();
      grid.__gridInitialized = false;
    };
  }

  function initAll() {
    document.querySelectorAll(selector).forEach(setupGrid);
  }

  initAll();

  // Optional: auto-init grids added later dynamically
  if (observe) {
    const observer = new MutationObserver(() => initAll());
    observer.observe(document.body, { childList: true, subtree: true });

    return {
      destroy() {
        observer.disconnect();
        document.querySelectorAll(selector).forEach((grid) => grid.__gridDestroy?.());
      },
      refresh: initAll,
    };
  }

  return {
    destroy() {
      document.querySelectorAll(selector).forEach((grid) => grid.__gridDestroy?.());
    },
    refresh: initAll,
  };
}
