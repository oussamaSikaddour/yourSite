import { toggleInertWhenState } from "../../utils/Inert";

// ----------------------------
// Utility helpers
// ----------------------------
const updateAriaAttributes = (element, expanded, hidden) => {
  element?.setAttribute("aria-expanded", expanded);
  element?.setAttribute("aria-hidden", hidden);
};

const focusFirstInteractive = (container) => {
  const firstFocusable = container?.querySelector("input, select, textarea, button");
  if (firstFocusable) firstFocusable.focus();
};

const toggleElementVisibility = (element, isVisible) => {
  if (!element) return;
  element.classList.toggle("open", isVisible);
  toggleInertWhenState(element, "open", true);
};

const openPanel = (btn, panel) => {
  toggleElementVisibility(panel, true);
  updateAriaAttributes(btn, true, false);
  focusFirstInteractive(panel);
};

const closePanel = (btn, panel) => {
  toggleElementVisibility(panel, false);
  updateAriaAttributes(btn, false, true);
};

const closeFilters = (filterBtn, filters) => {
  closePanel(filterBtn, filters);
};

// ----------------------------
// Table container logic
// ----------------------------
const setupTableContainer = (tc) => {
  const filterBtn = tc.querySelector(".table__filters__btn");
  const filtersContainer = tc.querySelector(".table__filters");

  if (!filterBtn || !filtersContainer) return;

  // Initialize inert
  toggleInertWhenState(filtersContainer, "open", true);

  tc.addEventListener("click", (event) => {
    const clickedFilterBtn = event.target.closest(".table__filters__btn");
    const isInsideFilters = filtersContainer.contains(event.target);

    // ---- Filter button ----
    if (clickedFilterBtn) {
      const isFilterOpen = filtersContainer.classList.contains("open");

      if (!isFilterOpen) openPanel(filterBtn, filtersContainer);
      else closePanel(filterBtn, filtersContainer);

      return;
    }

    // ---- Click inside filters → do nothing ----
    if (isInsideFilters) return;

    // ---- Everything else closes filters ----
    closeFilters(filterBtn, filtersContainer);
  });

  // ---- Click outside table closes filters ----
  document.addEventListener("click", (event) => {
    if (!tc.contains(event.target)) {
      closeFilters(filterBtn, filtersContainer);
    }
  });
};

// ----------------------------
// Module entry point
// ----------------------------
const Table = () => {
  const tableContainers = document.querySelectorAll(".table__container");
  if (!tableContainers.length) return;

  tableContainers.forEach(setupTableContainer);
};

export default Table;
