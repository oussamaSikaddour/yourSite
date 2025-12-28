// -----------------------------------------------------------------------------
// Imports
// -----------------------------------------------------------------------------
import { handleKeyEvents } from "../../utils/KeyEventHandlers";
import { toggleInertForChildElement, toggleInertWhenState } from "../../utils/Inert";

// -----------------------------------------------------------------------------
// 1) ARIA + STATE MANAGEMENT
// -----------------------------------------------------------------------------

/** Handles ARIA for main sidebar toggle buttons */
const updateMenuButtonState = (btn) => {
  const isExpanded = btn.classList.contains("clicked");
  btn.setAttribute("aria-expanded", isExpanded);
  btn.setAttribute("aria-hidden", !isExpanded);
};

/** Ensures only 1 button remains clicked */
const resetOtherButtons = (sidebarButtons, activeBtn) => {
  sidebarButtons.forEach((btn) => {
    if (btn !== activeBtn) btn.classList.remove("clicked");
    updateMenuButtonState(btn);
  });
};

// -----------------------------------------------------------------------------
// 2) SIDEBAR DROPDOWN SUBMENU SRP FUNCTIONS
// -----------------------------------------------------------------------------

/** Update dropdown submenu ARIA + visibility */
const updateSidebarSubMenuState = (dropdownBtn) => {
  if (!dropdownBtn) return;

  const subMenu = dropdownBtn.nextElementSibling;

  // Safeguard
  if (!subMenu) return;

  const parent = dropdownBtn.parentElement;
  const isExpanded = parent.classList.contains("clicked");

  // Accessibility
  dropdownBtn.setAttribute("aria-expanded", isExpanded);

  // Hide/show submenu
  subMenu.toggleAttribute("hidden", !isExpanded);

  // Manage inert state for nested items
  toggleInertForChildElement(parent, subMenu, "clicked", true);
};

/** Toggle dropdown submenu on click/keyboard */
const toggleDropdown = (dropdownBtn) => {
  dropdownBtn.parentElement.classList.toggle("clicked");
  updateSidebarSubMenuState(dropdownBtn);
};

// -----------------------------------------------------------------------------
// 3) OUTSIDE CLICK HANDLER
// -----------------------------------------------------------------------------

const handleOutsideClick = (sidebar, buttons) => {
  const close = (event) => {
    const insideSidebar = sidebar.contains(event.target);
    const onButton = buttons.some((btn) => btn.contains(event.target));

    if (!insideSidebar && !onButton) {
      buttons.forEach((btn) => btn.classList.remove("clicked"));
      buttons.forEach(updateMenuButtonState);

      sidebar.classList.remove("open");
      toggleInertWhenState(sidebar, "open", true);

      document.removeEventListener("click", close);
    }
  };

  document.addEventListener("click", close);
};

// -----------------------------------------------------------------------------
// 4) MAIN SIDEBAR OPEN/CLOSE
// -----------------------------------------------------------------------------

const toggleMenuVisibility = (btn, sidebarButtons, sidebar) => {
  resetOtherButtons(sidebarButtons, btn);

  sidebar.classList.toggle("open");
  btn.classList.toggle("clicked");
  updateMenuButtonState(btn);

  toggleInertWhenState(sidebar, "open", true);

  if (btn.classList.contains("clicked")) {
    handleOutsideClick(sidebar, sidebarButtons);
  }
};

const closeSidebar = (index, sidebarButtons, sidebar) => {
  toggleMenuVisibility(sidebarButtons[index], sidebarButtons, sidebar);
  sidebarButtons[index]?.focus();
};

// -----------------------------------------------------------------------------
// 5) KEYBOARD NAVIGATION INSIDE SIDEBAR
// -----------------------------------------------------------------------------

const handleSidebarKeyboardNavigation = (index, sidebarButtons, sidebar) => {
  const menuItems = Array.from(sidebar.querySelectorAll("[role='menuitem']"));
  menuItems[0]?.focus();

  sidebar.addEventListener(
    "keydown",
    (event) => {
      const currentItem = event.target.closest("[role='menuitem']");
      const position = menuItems.indexOf(currentItem);

      handleKeyEvents(event, position, null, menuItems, () =>
        closeSidebar(index, sidebarButtons, sidebar)
      );
    },
    { once: true }
  );
};

// -----------------------------------------------------------------------------
// 6) ELEMENT QUERIES
// -----------------------------------------------------------------------------

const getSidebarElements = () => {
  const sidebar = document.querySelector(".sidebar");

  return {
    sidebar,
    buttons: Array.from(document.querySelectorAll(".sidebar__toggle__btn")),
    dropdownsBtn: Array.from(
      sidebar?.querySelectorAll(".sidebar__btn--dropdown") || []
    ),
  };
};

// -----------------------------------------------------------------------------
// 7) EVENT ATTACHERS
// -----------------------------------------------------------------------------

const attachButtonClick = (btn, buttons, sidebar) => {
  btn.addEventListener("click", () => {
    toggleMenuVisibility(btn, buttons, sidebar);
  });
};

const attachButtonKeyboard = (btn, index, buttons, sidebar) => {
  btn.addEventListener("keydown", (e) => {
    if (e.code === "Space" || e.code === "Enter") {
      e.preventDefault();
      toggleMenuVisibility(btn, buttons, sidebar);

      if (btn.classList.contains("clicked")) {
        handleSidebarKeyboardNavigation(index, buttons, sidebar);
      }
    }
  });
};

const initButtons = (buttons, sidebar) => {
  buttons.forEach((btn, index) => {
    attachButtonClick(btn, buttons, sidebar);
    attachButtonKeyboard(btn, index, buttons, sidebar);
  });
};

// -----------------------------------------------------------------------------
// 8) DROPDOWN INITIALIZER
// -----------------------------------------------------------------------------

const initSidebarDropdowns = (dropdownsBtn) => {
  dropdownsBtn.forEach((dropdownBtn) => {
    updateSidebarSubMenuState(dropdownBtn);

    dropdownBtn.addEventListener("click", () => toggleDropdown(dropdownBtn));

    dropdownBtn.addEventListener("keydown", (e) => {
      if (e.code === "Space" || e.code === "Enter") {
        e.preventDefault();
        toggleDropdown(dropdownBtn);
      }
    });
  });
};

// -----------------------------------------------------------------------------
// 9) SIDEBAR INITIALIZER
// -----------------------------------------------------------------------------

const Sidebar = () => {
  const { sidebar, buttons, dropdownsBtn } = getSidebarElements();
  if (!sidebar) return;

  toggleInertWhenState(sidebar, "open", true);

  initButtons(buttons, sidebar);
  initSidebarDropdowns(dropdownsBtn);
};

export default Sidebar;
