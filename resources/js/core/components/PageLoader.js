// Import helper to focus the first non-hidden input in a form
import { focusNonHiddenInput } from "./Form.js";

let loaderEl = null;

const getLoader = () => {
  if (loaderEl && document.contains(loaderEl)) return loaderEl;
  loaderEl = document.getElementById("pageLoader");
  return loaderEl;
};

export const showPageLoader = () => {
  const el = getLoader();
  if (!el) return;

  el.classList.add("show");
  el.setAttribute("aria-hidden", "false");
};

export const hidePageLoader = () => {
  const el = getLoader();
  if (!el) return;

  el.classList.remove("show");
  el.setAttribute("aria-hidden", "true");

  // after loader hides, focus first usable form input
  const form = document.querySelector(".form");
  if (form) focusNonHiddenInput(form);
};

export const bootPageLoader = () => {
  // cache element early
  getLoader();

  // Hide when the full page (images/fonts/css) finished loading
  window.addEventListener("load", hidePageLoader, { once: true });

  // Livewire v3 navigate (safe even if not used)
  document.addEventListener("livewire:navigating", showPageLoader);
  document.addEventListener("livewire:navigated", hidePageLoader);
};

export default function PageLoader() {
  bootPageLoader();
}
