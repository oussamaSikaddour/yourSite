import { toggleInertForAllExceptOpenedElement } from "../../utils/Inert";
import { on } from "../../utils/DespatchCustomEvent";
const ErrorsNotifications = () => {
  const container = document.querySelector("#coreErrors");
  if (!container) return;

  const applyInertNow = () => {
    toggleInertForAllExceptOpenedElement(container, "open");

    const isOpen = container.classList.contains("open");
    const bodyChildren = Array.from(document.body.children);

    bodyChildren.forEach((el) => {
      if (el === container) return;

      if (isOpen) el.setAttribute("aria-hidden", "true");
      else el.removeAttribute("aria-hidden");
    });

    if (isOpen) {
      const closeBtn = container.querySelector(".errors__closer");
      if (closeBtn) closeBtn.focus();
    }
  };

  const applyInertAfterDomSettles = () => {
    queueMicrotask(() => requestAnimationFrame(applyInertNow));
  };

 on("errors-sync-inert", applyInertAfterDomSettles);

  // safety on first load
  applyInertAfterDomSettles();
};

export default ErrorsNotifications;
