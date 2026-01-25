// resources/js/components/admin/AdminColorPicker.js

import { dispatchCustomEvent } from "../../utils/DespatchCustomEvent";

const STORAGE_KEY = "theme-color-class";
const DEFAULT_THEME = "default";

const ALLOWED_THEMES = [
  "default", "emerald", "gold", "lime", "ocean",
  "rose", "sky", "slate", "sunset", "violet",
];

const sanitizeTheme = (theme) => {
  theme = (theme ?? "").toString().toLowerCase().trim();
  return ALLOWED_THEMES.includes(theme) ? theme : "";
};

const getThemeColorFromStorage = () => {
  return sanitizeTheme(localStorage.getItem(STORAGE_KEY));
};

const saveThemeColorToStorage = (theme) => {
  localStorage.setItem(STORAGE_KEY, theme);
};

const setThemeClassOnRoot = (theme) => {
  const root = document.documentElement;
  const safe = ALLOWED_THEMES.includes(theme) ? theme : DEFAULT_THEME;

  // remove all theme classes first
  root.classList.remove(...ALLOWED_THEMES);

  // ✅ only add class if NOT default
  if (safe !== DEFAULT_THEME) {
    root.classList.add(safe);
  }
};
const setActiveButton = (picker, theme) => {


  const safe = theme && ALLOWED_THEMES.includes(theme) ? theme : DEFAULT_THEME;
  picker.querySelectorAll(".admin__color__btn").forEach((b) => {
    const btnTheme = (b.dataset.theme || "").toLowerCase().trim();
    b.classList.toggle("active", btnTheme === safe);
  });
};

export default function AdminColorPicker() {
  const picker = document.querySelector(".admin__color__picker");
  if (!picker) return;

  // DB raw value (empty string means "not set")
  const dbThemeRaw = sanitizeTheme(picker.dataset.dbTheme); // returns "" if invalid/empty
  const storedTheme = getThemeColorFromStorage();           // "" if missing/invalid

  // ✅ Priority: DB (if set) > localStorage (if set) > default
  const finalTheme =
    (dbThemeRaw !== "" ? dbThemeRaw : (storedTheme !== "" ? storedTheme : DEFAULT_THEME));

  setThemeClassOnRoot(finalTheme);
  setActiveButton(picker, finalTheme);

  // sync Livewire on init
  dispatchCustomEvent("admin-color-init", { themeColor: finalTheme });

  const handleClick = (e) => {
    const btn = e.target.closest(".admin__color__btn");
    if (!btn || !picker.contains(btn)) return;

    const theme = (btn.dataset.theme || "").toLowerCase().trim();
    const safe = ALLOWED_THEMES.includes(theme) ? theme : DEFAULT_THEME;

    setThemeClassOnRoot(safe);
    setActiveButton(picker, safe);

    saveThemeColorToStorage(safe);
    dispatchCustomEvent("admin-color-change", { themeColor: safe });
  };

  picker.addEventListener("click", handleClick);
  return () => picker.removeEventListener("click", handleClick);
}
