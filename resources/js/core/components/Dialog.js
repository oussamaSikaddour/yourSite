import { toggleInertForAllExceptOpenedElement } from "../../utils/Inert";
import { on } from "../../utils/DespatchCustomEvent";

const Dialog = () => {
  const dialog = document.querySelector("#box");
  if (!dialog) return;

  const applyInertNow = () => {
    toggleInertForAllExceptOpenedElement(dialog, "open");

    const isOpen = dialog.classList.contains("open");
    const bodyChildren = Array.from(document.body.children);

    bodyChildren.forEach((el) => {
      if (el === dialog) return;

      if (isOpen) el.setAttribute("aria-hidden", "true");
      else el.removeAttribute("aria-hidden");
    });

    if (isOpen) {
      const btn = dialog.querySelector(".dialog__closer");
      if (btn) btn.focus();
    }
  };

  const applyInertAfterDomSettles = () => {
    queueMicrotask(() => requestAnimationFrame(applyInertNow));
  };

  // Livewire browser event
  on("dialog-sync-inert", applyInertAfterDomSettles);

  // Safety on page load
  applyInertAfterDomSettles();
};

export default Dialog;
