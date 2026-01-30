import { on } from "../../utils/DespatchCustomEvent";

let timer = null;

const Toast = () => {
  const toast = document.querySelector("#coreToast");
  if (!toast) return;

  const closeBtn = toast.querySelector(".toast__closer");

  const clear = () => {
    if (timer) {
      clearTimeout(timer);
      timer = null;
    }
  };

  const scheduleClose = () => {
    clear();

    // focus close button for a11y (optional)
    if (closeBtn) closeBtn.focus();

    timer = setTimeout(() => {
      // Tell Livewire to close
      window.dispatchEvent(new CustomEvent("close-toast"));
    }, 6000);
  };

  // Watch class changes from Livewire (open/close)
  const observer = new MutationObserver(() => {
    const isOpen = toast.classList.contains("open");
    if (isOpen) scheduleClose();
    else clear();
  });

  observer.observe(toast, { attributes: true, attributeFilter: ["class"] });

  // initial state
  if (toast.classList.contains("open")) scheduleClose();

  // Hook Livewire close-toast event → component listens already
  on("close-toast", () => {
    clear();
  });
};

export default Toast;
