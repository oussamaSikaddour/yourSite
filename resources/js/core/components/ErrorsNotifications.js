import { toggleInertForAllExceptOpenedElement } from '../../utils/Inert';
import { dispatchCustomEvent } from '../../utils/DespatchCustomEvent';

// --- ELEMENTS ---
const getElements = () => {

  const errorsContainer = document.querySelector(".errors__container");
  const closeButton = errorsContainer?.querySelector(".errors__closer");

  if ( !errorsContainer || !closeButton) return null;
  return { errorsContainer, closeButton };
};

// --- OPEN / CLOSE ---
const openErrors = (container, closeButton) => {
  container.classList.add("open");
  toggleInertForAllExceptOpenedElement(container, "open");
  closeButton.focus();
};

const closeErrors = (container) => {
  container.classList.remove("open");
  toggleInertForAllExceptOpenedElement(container, "open");
};

// --- MAIN SETUP ---
const ErrorsNotifications = () => {
  const els = getElements();
  if (!els) return;

  const { errorsContainer, closeButton } = els;

  // Close: button click → close container
  closeButton.addEventListener("click", () => {
    closeErrors(errorsContainer);
  });

  // Custom event → open container
  document.addEventListener("errors-notifications", (e) => {
    e.preventDefault();
    openErrors(errorsContainer, closeButton);
  });
};

export default ErrorsNotifications;
