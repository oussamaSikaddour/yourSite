import { dispatchCustomEvent } from "../../utils/DespatchCustomEvent";
import { handleKeyEvents } from "../../utils/KeyEventHandlers";

// --------------------
// Data
// --------------------
const STORAGE_KEY = "theme-color-class";

const themeColors = [
  { color: "#087EA4", class: "default" },
  { color: "#34d399", class: "emerald" },
  { color: "#f1d77a", class: "gold" },
  { color: "#a3e635", class: "lime" },
  { color: "#1aa3c8", class: "ocean" },
  { color: "#fb7185", class: "rose" },
  { color: "#38bdf8", class: "sky" },
  { color: "#64748b", class: "slate" },
  { color: "#fb923c", class: "sunset" },
  { color: "#a78bfa", class: "violet" },
];

// --------------------
// Storage
// --------------------
const saveThemeColorToStorage = (themeClass) => {
  localStorage.setItem(STORAGE_KEY, themeClass);
};

const getThemeColorFormStorage = () => {
  return localStorage.getItem(STORAGE_KEY) || "default";
};

// --------------------
// DOM utilities
// --------------------
const applyThemeColorClass = (selectedClass, themeColors) => {
  const root = document.documentElement;

  // Remove any theme class currently applied
  root.classList.remove(...themeColors.map((t) => t.class));

  // Add selected (but NOT default)
  if (selectedClass && selectedClass !== "default") {
    root.classList.add(selectedClass);
  }
};


const setAriaAttributes = (btn, menu, isOpen) => {
  btn.setAttribute("aria-expanded", String(isOpen));
  menu.setAttribute("aria-hidden", String(!isOpen));
};

const toggleMenuVisibility = (btn, menu) => {
  const isOpen = menu.classList.toggle("open");
  setAriaAttributes(btn, menu, isOpen);
  return isOpen;
};

const getMenuItems = (menu) =>
  Array.from(menu.querySelectorAll(".color__picker__menu__item"));

// --------------------
// Theme data helpers
// --------------------
const findThemeColorIndex = (themeClass, themeColors) =>
  themeColors.findIndex((t) => t.class === themeClass);

const getSelectedThemeColor = (themeClass, themeColors) => {
  const idx = findThemeColorIndex(themeClass, themeColors);
  return idx >= 0 ? themeColors[idx] : themeColors[0]; // fallback
};

const getRemainingThemeColors = (selectedClass, themeColors) =>
  themeColors.filter((t) => t.class !== selectedClass);

// --------------------
// Rendering
// --------------------
const createThemeColorButtonHtml = (themeColor) => `
  <div class="color">
    <p>${themeColor.class}</p>
    <span style="background-color:${themeColor.color}"></span>
  </div>
`;

const createMenuItemHTML = (themeColor) => `
  <li role="menuitem" class="color__picker__menu__item" tabindex="0">
    <div class="color">
      <p>${themeColor.class}</p>
      <span style="background-color:${themeColor.color}"></span>
    </div>
  </li>
`;

const updateThemeColorButtons = (selectedThemeColor, btns) => {
  const html = createThemeColorButtonHtml(selectedThemeColor);
  btns.forEach((btn) => (btn.innerHTML = html));
};

const updateThemeColorMenus = (remainingThemeColors, menus) => {
  const html = remainingThemeColors.map(createMenuItemHTML).join("");
  menus.forEach((menu) => (menu.innerHTML = html));
};

// --------------------
// Preference
// --------------------
const setThemColorPreference = (selectedClass) => {
  saveThemeColorToStorage(selectedClass);
  applyThemeColorClass(selectedClass, themeColors);
};

const populateColorPickerMenu = (themeColors, selectedClass, refs) => {
  const selectedThemeColor = getSelectedThemeColor(selectedClass, themeColors);
  const remainingThemeColors = getRemainingThemeColors(selectedThemeColor.class, themeColors);

  updateThemeColorButtons(selectedThemeColor, refs.colorPickerBtns);
  updateThemeColorMenus(remainingThemeColors, refs.colorPickerMenus);

  setThemColorPreference(selectedThemeColor.class);
};

// --------------------
// Events
// --------------------
const handleColorPickerBtnClick = (btn, menu) => {
  toggleMenuVisibility(btn, menu);
  const items = getMenuItems(menu);
  items[1]?.focus(); // keep your original behavior
};

const getSelectedThemeClassFromMenuItem = (menuItem) =>
  menuItem?.querySelector("p")?.textContent;

const selectThemeColorClass = (index, btn, menu, themeColors, refs) => {
  const items = getMenuItems(menu);
  const selectedClass = getSelectedThemeClassFromMenuItem(items[index]);
  if (!selectedClass) return;

  populateColorPickerMenu(themeColors, selectedClass, refs);
  toggleMenuVisibility(btn, menu);
  btn.focus();

  dispatchCustomEvent("set-theme-color-class", { class: selectedClass });
};

const handleMenuItemInteraction = (event, btn, menu, themeColors, refs) => {
  const menuItem = event.target.closest(".color__picker__menu__item");
  if (!menuItem) return;

  const items = getMenuItems(menu);
  const index = items.indexOf(menuItem);

  const onSelect = () => selectThemeColorClass(index, btn, menu, themeColors, refs);

  if (event.type === "keydown") {
    handleKeyEvents(event, index, onSelect, items);
    return;
  }

  onSelect();
};

// --------------------
// Init
// --------------------
const initializeThemeColorMenu = (container, themeColors, refs) => {
  const menu = container.querySelector(".color__picker__menu");
  const btn = container.querySelector(".color__picker__btn");
  if (!btn || !menu) return;

  const selectedClass = getThemeColorFormStorage();
  populateColorPickerMenu(themeColors, selectedClass, refs);

  btn.addEventListener("click", () => handleColorPickerBtnClick(btn, menu));

  const eventHandler = (event) =>
    handleMenuItemInteraction(event, btn, menu, themeColors, refs);

  container.addEventListener("keydown", eventHandler);
  container.addEventListener("click", eventHandler);
};

// Main
const ColorPicker = () => {
  const refs = {
    colorPickerMenus: Array.from(document.querySelectorAll(".color__picker__menu")),
    colorPickerBtns: Array.from(document.querySelectorAll(".color__picker__btn")),
  };

  document
    .querySelectorAll(".color__picker__container")
    .forEach((container) => initializeThemeColorMenu(container, themeColors, refs));
};

export default ColorPicker;
