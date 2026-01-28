import { dispatchCustomEvent } from "../../utils/DespatchCustomEvent";
import { handleKeyEvents } from "../../utils/KeyEventHandlers";
import { THEME } from "./ThemeManager";

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

const applyUi = (container, selectedClass) => {
  const btn = container.querySelector(".color__picker__btn");
  const menu = container.querySelector(".color__picker__menu");
  if (!btn || !menu) return;

  const selected = themeColors.find(t => t.class === selectedClass) ?? themeColors[0];
  const remaining = themeColors.filter(t => t.class !== selected.class);

  btn.innerHTML = `
    <div class="color">
      <p>${selected.class}</p>
      <span style="background-color:${selected.color}"></span>
    </div>
  `;

  menu.innerHTML = remaining.map(t => `
    <li role="menuitem" class="color__picker__menu__item" tabindex="0" data-theme="${t.class}">
      <div class="color">
        <p>${t.class}</p>
        <span style="background-color:${t.color}"></span>
      </div>
    </li>
  `).join("");
};

const setAria = (btn, menu, isOpen) => {
  btn.setAttribute("aria-expanded", String(isOpen));
  menu.setAttribute("aria-hidden", String(!isOpen));
};

const toggleMenu = (btn, menu) => {
  const isOpen = menu.classList.toggle("open");
  setAria(btn, menu, isOpen);
  return isOpen;
};

const getItems = (menu) => Array.from(menu.querySelectorAll(".color__picker__menu__item"));

const initOne = (container) => {
  const btn = container.querySelector(".color__picker__btn");
  const menu = container.querySelector(".color__picker__menu");
  if (!btn || !menu) return;

  if (container.dataset.initialized === "1") return;
  container.dataset.initialized = "1";

  // show user override if exists; else show final theme
  const user = THEME.getUser();
  const initial = user || THEME.computeFinal();

  applyUi(container, initial);
  THEME.sync();

  btn.addEventListener("click", () => {
    toggleMenu(btn, menu);
    getItems(menu)[1]?.focus();
  });

  const onSelectIndex = (index) => {
    const items = getItems(menu);
    const selectedClass = THEME.sanitize(items[index]?.dataset?.theme) || THEME.DEFAULT;

    // user changes USER override
    THEME.setUser(selectedClass);

    applyUi(container, selectedClass);
    toggleMenu(btn, menu);
    btn.focus();

    dispatchCustomEvent("set-theme-color-class", { class: selectedClass });
  };

  const eventHandler = (event) => {
    const menuItem = event.target.closest(".color__picker__menu__item");
    if (!menuItem) return;

    const items = getItems(menu);
    const index = items.indexOf(menuItem);
    const onSelect = () => onSelectIndex(index);

    if (event.type === "keydown") {
      handleKeyEvents(event, index, onSelect, items);
      return;
    }
    onSelect();
  };

  container.addEventListener("keydown", eventHandler);
  container.addEventListener("click", eventHandler);

  // if final theme changes elsewhere, keep UI consistent when user has no override
  document.addEventListener("theme-final-changed", (e) => {
    const hasUserOverride = !!THEME.getUser();
    if (hasUserOverride) return;

    const t = THEME.sanitize(e?.detail?.themeColor) || THEME.DEFAULT;
    applyUi(container, t);
  });
};

export default function ColorPicker() {
  document.querySelectorAll(".color__picker__container").forEach(initOne);
}
