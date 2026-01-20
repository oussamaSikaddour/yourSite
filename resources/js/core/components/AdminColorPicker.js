// AdminColorPicker.js
export default function AdminColorPicker() {
  const picker = document.querySelector(".admin__color__picker");
  if (!picker) return;

  // Optional: set first button active if none is active
  if (!picker.querySelector(".admin__color__btn.active")) {
    picker.querySelector(".admin__color__btn")?.classList.add("active");
  }

  const handleClick = (e) => {
    const btn = e.target.closest(".admin__color__btn");
    if (!btn || !picker.contains(btn)) return;

    picker
      .querySelectorAll(".admin__color__btn.active")
      .forEach((b) => b.classList.remove("active"));

    btn.classList.add("active");
  };

  picker.addEventListener("click", handleClick);

  // Optional: return a cleanup function (useful if you re-init)
  return () => picker.removeEventListener("click", handleClick);
}
