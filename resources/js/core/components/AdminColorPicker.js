import { dispatchCustomEvent } from "../../utils/DespatchCustomEvent";
import { THEME } from "./ThemeManager";

const LEGACY_KEY = "theme-color-class"; // 👈 your old key

const setActiveButton = (picker, theme) => {


  const safe = THEME.ALLOWED.includes(theme) ? theme : THEME.DEFAULT;

  picker.querySelectorAll(".admin__color__btn").forEach((b) => {
    const btnTheme = (b.dataset.theme || "").toLowerCase().trim();
    b.classList.toggle("active", btnTheme === safe);
  });
};

const getLegacyTheme = () => {
  const legacy = THEME.sanitize(localStorage.getItem(LEGACY_KEY));
  return legacy || "";
};

export default function AdminColorPicker() {
  const picker = document.querySelector(".admin__color__picker");
  if (!picker) return;

  // avoid double binding after Livewire morph
  if (picker.dataset.initialized === "1") return;
  picker.dataset.initialized = "1";

  // 1) DB theme from blade
  const dbTheme = THEME.sanitize(picker.dataset.dbTheme);

  // 2) Global from new storage
  const storedGlobal = THEME.getGlobal();

  // 3) Legacy from old storage
  const legacy = getLegacyTheme();

  // ✅ ACTIVE should reflect GLOBAL source (DB > storedGlobal > legacy > default)
const globalTheme = storedGlobal || dbTheme || legacy || THEME.DEFAULT;


  // ✅ Cache DB as global if present (keeps storage consistent)
  if (dbTheme) THEME.setGlobal(dbTheme);

  // Ensure DOM is ready (especially in modals)
  requestAnimationFrame(() => {
    setActiveButton(picker, globalTheme);
  });

  // Still sync the actual site theme (user override may win)
  THEME.sync();

  const handleClick = (e) => {

    localStorage.removeItem('user-theme-color-class')
    const btn = e.target.closest(".admin__color__btn");
    if (!btn || !picker.contains(btn)) return;

    const theme = THEME.sanitize(btn.dataset.theme) || THEME.DEFAULT;

    // admin changes GLOBAL
    THEME.setGlobal(theme);

    // ✅ ACTIVE = GLOBAL immediately
    setActiveButton(picker, theme);

    // notify Livewire form (save to DB)
    dispatchCustomEvent("admin-color-change", { themeColor: theme });
  };

  picker.addEventListener("click", handleClick);
  return () => picker.removeEventListener("click", handleClick);
}
