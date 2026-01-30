// ------------------------------------------------------
// Core Components
// ------------------------------------------------------
import Navigation from "./core/components/Header.js";
import Sidebar from "./core/components/Sidebar.js";
import Lang from "./core/components/Lang.js";
import AdminColorPicker from "./core/components/AdminColorPicker.js";
import ColorPicker from "./core/components/ColorPicker.js";

// ------------------------------------------------------
// UI Widgets / Inputs
// ------------------------------------------------------
import Accordion from "./core/components/Accordion.js";
import Combobox from "./core/components/Combobox.js";
import Tabs from "./core/components/Tabs.js";
import ToolTip from "./core/components/Tooltip.js";
import Modal from "./core/components/Modal.js";
import Table from "./core/components/Table.js";
import Dialog from "./core/components/Dialog.js";
import Slider from "./core/components/Slider.js";
import Carousel from "./core/components/Carousel.js";
import { THEME } from "./core/components/ThemeManager.js";

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
import Grid from "./core/components/Grid.js";

// ------------------------------------------------------
// Loader / Events
// ------------------------------------------------------
import PageLoader from "./core/components/PageLoader.js";
import { on } from "./utils/DespatchCustomEvent.js";

// ------------------------------------------------------
// App
// ------------------------------------------------------
import LandingPageNavigation from "./app/LandingPageNavigation.js";
import Counter from "./app/Counter.js";

// ------------------------------------------------------
// Safe Initializer Helper
// ------------------------------------------------------
const safeRun = (fn, parameters = []) => {
  try {
    if (Array.isArray(parameters) && parameters.length > 0) {
      return fn(...parameters);
    } else {
      return fn();
    }
  } catch (e) {
    console.warn(`[init error] ${fn.name || "anonymous function"}:`, e);
    return undefined;
  }
};

// ------------------------------------------------------
// Boot on DOM Ready (same logic / same order)
// ------------------------------------------------------
document.addEventListener("DOMContentLoaded", async () => {
  // Loader START
  safeRun(PageLoader);

  // Core
  safeRun(Navigation);
  safeRun(Sidebar);
  safeRun(Lang);

  // UI Widgets
  safeRun(Modal);
  safeRun(Accordion);
  safeRun(Combobox);
  safeRun(Tabs);

  safeRun(Dialog);
  safeRun(ToolTip);
  safeRun(Table);
  safeRun(Slider);

  safeRun(manageCheckBoxes);
  safeRun(manageRadioInputs);
  safeRun(manageFileInputs);

  // Feedback
  safeRun(ErrorsNotifications);
  safeRun(Toast);
  safeRun(Grid);

  // ------------------------------------------------------
  // App
  // ------------------------------------------------------
  safeRun(LandingPageNavigation);
  safeRun(Carousel);

  // Loader END (UI ready)
});

// ------------------------------------------------------
// Multi Form (same logic / same order)
// ------------------------------------------------------

// register Multi Form
slideOnEvent("register-first-step-succeeded");
initSlideOneEvent("register-multi-form-init");
clearMultiFormStepOnEvent("register-multi-form-clear");

// Forget Password Multi Form
slideOnEvent("forget-password-first-step-succeeded");
initSlideOneEvent("forget-password-multi-form-init");
clearMultiFormStepOnEvent("forget-password-multi-form-clear");

// site params
slideOnEvent("site-params-first-step-succeeded");
initSlideOneEvent("site-params-multi-form-init");
clearMultiFormStepOnEvent("site-params-multi-form-clear");

// ------------------------------------------------------
// Init ON Event (same logic / same order)
// ------------------------------------------------------

on("init-tooltip", (event) => {
  safeRun(ToolTip);
});

// Livewire 3: re-init after DOM morph (same logic)
document.addEventListener("livewire:init", () => {
  safeRun(ToolTip);

  Livewire.hook("morph.added", () => safeRun(ToolTip));
  Livewire.hook("morph.updated", () => safeRun(ToolTip));
});

on("init-accordion", (event) => {
  safeRun(Accordion);
});

on("init-table", (event) => {
  safeRun(Table);
});

on("init-radio", (event) => {
  safeRun(manageRadioInputs);
});

on("init-checkBox", (event) => {
  safeRun(manageCheckBoxes);
});

on("init-file-input", (event) => {
  safeRun(manageFileInputs);
});

// ------------------------------------------------------
// App Events (same logic / same order)
// ------------------------------------------------------

on("about__us__is__animated", () => {
  safeRun(Counter, [".about__us"]);
});

on("services__is__animated", () => {
  safeRun(Counter, [".services"]);
});

on("init_theme_color", (e) => {
  const themeFromDb = e?.detail?.themeColor ?? e?.[0] ?? "default";
  THEME.setGlobal(themeFromDb);

  safeRun(ColorPicker);
  safeRun(AdminColorPicker);
});
