// ------------------------------------------------------
// Core Components
// ------------------------------------------------------
import Navigation from "./core/components/Header.js";
import Sidebar from "./core/components/Sidebar.js";
import Lang from "./core/components/Lang.js";

// ------------------------------------------------------
// UI Widgets / Inputs
// ------------------------------------------------------
import Accordion from "./core/components/Accordion.js";
import Combobox from "./core/components/Combobox.js";
import Tabs from "./core/components/Tabs.js";
import ToolTip from "./core/components/Tooltip.js";
import Table from "./core/components/Table.js";
import Modal from "./core/components/Modal.js";
import Dialog from "./core/components/Dialog.js";

// ------------------------------------------------------
// Form Helpers
// ------------------------------------------------------
import {
    clearMultiFormStepOnEvent,
    initSlideOneEvent,
    slideOnEvent,
} from "./core/components/Form.js";
import { manageCheckBoxes } from "./core/components/CheckBoxes.js";
import { manageRadioInputs } from "./core/components/RadioInput.js";
import { manageFileInputs } from "./core/components/FileInputs.js";

// ------------------------------------------------------
// Feedback & Notifications
// ------------------------------------------------------
import ErrorsNotifications from "./core/components/ErrorsNotifications.js";
import Toast from "./core/components/Toast.js";


// ------------------------------------------------------
// Loader
// ------------------------------------------------------
import { createLoader, showLoader, hideLoader } from "./core/components/Loader.js";
import { on } from "./utils/DespatchCustomEvent.js";




import LandingPageNavigation from "./app/LandingPageNavigation.js";
import Carousel from "./app/Carsoule.js";
import InfiniteScroll from "./app/InfiniteScroll.js";
import Counter from "./app/Counter.js";
// ------------------------------------------------------
// Safe Initializer Helper
// ------------------------------------------------------
const safeRun = (fn, parameters = []) => {
  try {
    // Check if parameters is an array and not null/undefined
    if (Array.isArray(parameters) && parameters.length > 0) {
      return fn(...parameters);
    } else {
      return fn();
    }
  } catch (e) {
    console.warn(`[init error] ${fn.name || "anonymous function"}:`, e);
    // Return undefined or handle the error as needed
    return undefined;
  }
};


// ------------------------------------------------------
// Boot on DOM Ready
// ------------------------------------------------------
document.addEventListener("DOMContentLoaded", async () => {
    // Loader START
    createLoader();
    showLoader();

    // Core
    safeRun(Navigation);
    safeRun(Sidebar);
    safeRun(Lang);

    // UI Widgets
    safeRun(Accordion);
    safeRun(Combobox);
    safeRun(Tabs);
    safeRun(Modal);
    safeRun(Dialog);
    safeRun(ToolTip);
    safeRun(Table);

    safeRun(manageCheckBoxes);
    safeRun(manageRadioInputs);
    safeRun(manageFileInputs);

    // Feedback
    safeRun(ErrorsNotifications);
    safeRun(Toast);


    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////////////           App
  ///////////////////////////////////////////////////////////////////////////////////////////////

  safeRun(LandingPageNavigation);
  safeRun(Carousel);


  // Loader END (UI ready)




    // Loader END (UI ready)
    hideLoader();
});

//register Multi Form
slideOnEvent("register-first-step-succeeded");
initSlideOneEvent("register-multi-form-init");
clearMultiFormStepOnEvent("register-multi-form-clear");

//Forget Password Multi Form
slideOnEvent("forget-password-first-step-succeeded");
initSlideOneEvent("forget-password-multi-form-init");
clearMultiFormStepOnEvent("forget-password-multi-form-clear");

//
slideOnEvent("site-params-first-step-succeeded");
initSlideOneEvent("site-params-multi-form-init");
clearMultiFormStepOnEvent("site-params-multi-form-clear");

//IinitON Event

on("init-tooltip", (event) => {

    safeRun(ToolTip)
});
on("init-accordion", (event) => {
    safeRun(Accordion)
});
on("init-table", (event) => {
    safeRun(Table)
});
on("init-radio", (event) => {
    safeRun(manageRadioInputs)
});
on("init-checkBox", (event) => {
    safeRun(manageCheckBoxes)
});
on("init-file-input", (event) => {
    safeRun(manageFileInputs)
});




    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////////////           App
  //////////////////////////////////////////////////////////////////////////////////////////////


on("about__us__is__animated", () => {
  safeRun(Counter,['.about__us']);
})

on("services__is__animated", () => {
  safeRun(Counter,['.services']);
});
