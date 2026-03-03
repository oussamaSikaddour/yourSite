import { toggleInertWhenState } from "../../utils/Inert";

// ----------------------------
// Utility helpers
// ----------------------------
const updateAriaAttributes = (element, expanded, hidden) => {
  element?.setAttribute("aria-expanded", String(expanded));
  element?.setAttribute("aria-hidden", String(hidden));
};

const focusFirstInteractive = (container) => {
  const firstFocusable = container?.querySelector(
    "input, select, textarea, button, [tabindex]:not([tabindex='-1'])"
  );
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
// Bulk selection (select_all / select_one)
// ----------------------------
const getRowCheckboxes = (scopeEl) =>
  Array.from(scopeEl.querySelectorAll('input[type="checkbox"].select_one'));

const getSelectAll = (scopeEl) =>
  scopeEl.querySelector('input[type="checkbox"].select_all');

const syncSelectAllState = (scopeEl) => {
  const selectAll = getSelectAll(scopeEl);
  if (!selectAll) return;

  const rows = getRowCheckboxes(scopeEl).filter((cb) => !cb.disabled);
  if (!rows.length) {
    selectAll.checked = false;
    selectAll.indeterminate = false;
    selectAll.disabled = true;
    return;
  }

  selectAll.disabled = false;

  const checkedCount = rows.reduce((n, cb) => n + (cb.checked ? 1 : 0), 0);

  if (checkedCount === 0) {
    selectAll.checked = false;
    selectAll.indeterminate = false;
  } else if (checkedCount === rows.length) {
    selectAll.checked = true;
    selectAll.indeterminate = false;
  } else {
    selectAll.checked = false;
    selectAll.indeterminate = true;
  }
};

const setAllRowsChecked = (scopeEl, checked) => {
  const rows = getRowCheckboxes(scopeEl);
  rows.forEach((cb) => {
    if (cb.disabled) return;
    cb.checked = checked;
    // If you rely on listeners elsewhere (Livewire/Alpine/etc), keep this:
    cb.dispatchEvent(new Event("change", { bubbles: true }));
  });
  syncSelectAllState(scopeEl);
};

const setupBulkSelection = (tc) => {
  // Initial sync
  syncSelectAllState(tc);

  // One delegated handler for both select_all and select_one
  tc.addEventListener("change", (event) => {
    const target = event.target;

    if (!(target instanceof HTMLInputElement)) return;
    if (target.type !== "checkbox") return;

    // select_all clicked -> toggle all select_one
    if (target.classList.contains("select_all")) {
      // if it was indeterminate and user clicks, browsers usually set checked=true.
      // we just trust target.checked
      setAllRowsChecked(tc, target.checked);
      return;
    }

    // select_one changed -> sync select_all state
    if (target.classList.contains("select_one")) {
      syncSelectAllState(tc);
    }
  });
};

// ----------------------------
// Table container logic
// ----------------------------
const setupTableContainer = (tc) => {
  const filterBtn = tc.querySelector(".table__filters__btn");
  const filtersContainer = tc.querySelector(".table__filters");

  // Bulk selection (works even if filters don't exist)
  setupBulkSelection(tc);

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
