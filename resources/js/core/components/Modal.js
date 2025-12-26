import { dispatchCustomEvent } from "../../utils/DespatchCustomEvent";
import { toggleInertForAllExceptOpenedElement } from "../../utils/Inert";
import { setAriaAttributes } from "../../utils/Aria";

// ======================
// OPEN MODAL
// ======================
const openModal = (modal, closeButton) => {
  if (modal.classList.contains("open")) return; // prevent flickering

  modal.classList.add("open");
  setAriaAttributes(false, "0", modal);

  toggleInertForAllExceptOpenedElement(modal, "open");

  if (closeButton) closeButton.focus();
dispatchCustomEvent('open-modal')

};

// ======================
// CLOSE MODAL
// ======================
const closeModal = (modal, closeButton) => {
  if (!modal.classList.contains("open")) return;

  modal.classList.remove("open");
  setAriaAttributes(true, "-1", modal);

  toggleInertForAllExceptOpenedElement(modal, "open");


  dispatchCustomEvent("modal-will-be-close");
};

// ======================
// EVENT BINDING
// ======================
const bindModalEvents = (openButtons, closeButton, modal) => {

  // OPENERS ALWAYS OPEN
  openButtons.forEach((btn) => {
    btn.addEventListener("click", () => openModal(modal, closeButton));
  });

  // CLOSER ALWAYS CLOSE
  closeButton.addEventListener("click", () => closeModal(modal, closeButton));
};

// ======================
// MAIN INITIALIZER
// ======================
const Modal = () => {
  const openButtons = document.querySelectorAll(".modal__opener");
  const closeButton = document.querySelector(".modal__closer");
  const modal = document.querySelector(".modal");

  if (!openButtons.length || !closeButton || !modal) return;

  bindModalEvents(openButtons, closeButton, modal);
};

export default Modal;
