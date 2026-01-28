import { dispatchCustomEvent } from "../../utils/DespatchCustomEvent";

export const THEME = {
  DEFAULT: "default",
  ALLOWED: [
    "default", "emerald", "gold", "lime", "ocean",
    "rose", "sky", "slate", "sunset", "violet",
  ],

  STORAGE: {
    GLOBAL: "global-theme-color-class", // admin/db cached (optional)
    USER: "user-theme-color-class",     // user override
  },

  sanitize(value) {
    const v = (value ?? "").toString().toLowerCase().trim();
    return this.ALLOWED.includes(v) ? v : "";
  },

  applyToRoot(theme) {
    const root = document.documentElement;
    const safe = this.ALLOWED.includes(theme) ? theme : this.DEFAULT;

    root.classList.remove(...this.ALLOWED);
    if (safe !== this.DEFAULT) root.classList.add(safe);
  },

  setGlobal(theme) {
    const safe = this.sanitize(theme);
    localStorage.setItem(this.STORAGE.GLOBAL, safe);
    // do NOT apply immediately if user override exists; recompute
    this.sync();
  },

  setUser(theme) {
    const safe = this.sanitize(theme) ;

    // if user selects "default" we treat it as "remove override"

      localStorage.setItem(this.STORAGE.USER, safe);


    this.sync();
  },

  getGlobal() {
    return this.sanitize(localStorage.getItem(this.STORAGE.GLOBAL));
  },

  getUser() {
    return this.sanitize(localStorage.getItem(this.STORAGE.USER));
  },

  computeFinal() {
    const user = this.getUser();
    if (user) return user;

    const global = this.getGlobal();
    if (global) return global;

    return this.DEFAULT;
  },

  sync() {
    const finalTheme = this.computeFinal();
    this.applyToRoot(finalTheme);

    // Tell Livewire/UI to reflect final state
    dispatchCustomEvent("theme-final-changed", { themeColor: finalTheme });

    return finalTheme;
  },
};
