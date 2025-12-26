import { toggleInertForAllExceptOpenedElement } from "../../utils/Inert";
import { setAriaAttributes } from "../../utils/Aria";
import { dispatchCustomEvent, on } from "../../utils/DespatchCustomEvent";

// ---- OPEN ----
const openDialog = (dialog, closeBtn) => {
    if (dialog.classList.contains("open")) return;

    dialog.classList.add("open");
    setAriaAttributes(false, "0", dialog);
    toggleInertForAllExceptOpenedElement(dialog, "open");

    if (closeBtn) closeBtn.focus();
};

// ---- CLOSE ----
const closeDialog = (dialog) => {
    if (!dialog.classList.contains("open")) return;

    // Move focus outside BEFORE hiding dialog
    document.body.focus(); // this prevents the aria-hidden/focus error

    dialog.classList.remove("open");
    setAriaAttributes(true, "-1", dialog);
    toggleInertForAllExceptOpenedElement(dialog, "open");

    dispatchCustomEvent("close-dialog");
};

// ---- INITIALIZE ----
const Dialog = () => {
    const dialog = document.querySelector(".dialog");
    const closeButton = document.querySelector(".dialog__closer");

    if (!dialog || !closeButton) return;

    // Open dialog via custom event
    on("add-open-to-dialog", () => openDialog(dialog, closeButton));

    // Close button
    closeButton.addEventListener("click", () => closeDialog(dialog));

    on("dialog-will-be-close",()=>closeDialog(dialog))
    // Optional: ESC key closes dialog
    document.addEventListener("keydown", e => {
        if (e.key === "Escape" && dialog.classList.contains("open")) {
            closeDialog(dialog);
        }
    });

    // Optional: click outside closes dialog
    document.addEventListener("click", e => {
        if (!dialog.contains(e.target) && dialog.classList.contains("open")) {
            closeDialog(dialog);
        }
    });
};

export default Dialog;
