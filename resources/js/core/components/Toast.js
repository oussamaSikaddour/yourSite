import { setAriaAttributes } from "../../utils/Aria";
import { dispatchCustomEvent ,on} from "../../utils/DespatchCustomEvent";

// Global auto-close timer
let toastTimer = null;

// ---------------------------
// OPEN / CLOSE TOAST
// ---------------------------
const openToast = (toast, closeButton) => {
  // Prevent multiple timers stacking
  if (toastTimer) clearTimeout(toastTimer);

  toast.classList.add("open");
  setAriaAttributes(false, "0", toast);

  // Focus the closer button for accessibility
  closeButton.focus();

  // auto-close after 6s
  toastTimer = setTimeout(() => closeToast(toast), 6000);
};

const closeToast = (toast) => {
  // Clear auto-close timer
  if (toastTimer) {
    clearTimeout(toastTimer);
    toastTimer = null;
  }


  toast.classList.remove("open");
  setAriaAttributes(true, "-1", toast);
    dispatchCustomEvent("close-toast");
};

// ---------------------------
// ELEMENTS
// ---------------------------
const getElements = () => {
  const closeButton = document.querySelector(".toast__closer");

  const toast = document.querySelector(".toast__container");
  if ( !closeButton || !toast) return null;
  return {  closeButton, toast };
};

// ---------------------------
// MAIN SETUP
// ---------------------------
const Toast = () => {
  const els = getElements();
  if (!els) return;

  const {  closeButton, toast } = els;

  // manual close (button)
  closeButton.addEventListener("click", () => closeToast(toast));

  // click on the toast → close
  toast.addEventListener("click", () => closeToast(toast));

  // keyboard accessibility: Enter + Space
  toast.addEventListener("keydown", (e) => {
    if (e.key === "Enter" || e.key === " ") {
      closeToast(toast);
    }
  });


    on("set-toast-open", () => openToast(toast ,closeButton));
};

export default Toast;
