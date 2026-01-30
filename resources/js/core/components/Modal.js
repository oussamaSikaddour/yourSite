import { on } from "../../utils/DespatchCustomEvent";
import { toggleInertForAllExceptOpenedElement } from "../../utils/Inert";

const Modal = () => {
  const modal = document.querySelector("#defaultModal");
  if (!modal) return;

  const applyInertNow = () => {
    toggleInertForAllExceptOpenedElement(modal, "open");

    const isOpen = modal.classList.contains("open");
    const bodyChildren = Array.from(document.body.children);

    bodyChildren.forEach((el) => {
      if (el === modal) return;
      if (isOpen) el.setAttribute("aria-hidden", "true");
      else el.removeAttribute("aria-hidden");
    });

    if (isOpen) modal.querySelector(".modal__closer")?.focus();
  };

  const applyInertAfterDomSettles = () => {
    queueMicrotask(() => requestAnimationFrame(applyInertNow));
  };

  on("modal-sync-inert", applyInertAfterDomSettles);
  applyInertAfterDomSettles();
};

export default Modal;
