import { dispatchCustomEvent } from "../utils/DespatchCustomEvent";
import { inView } from "../utils/IntersectionObserver";

const cache = {
  nav: null,
  navLinks: null,
  navPhone: null,
  navPhoneLinks: null,
};

const initCache = () => {
  if (cache.nav) return;

  cache.nav = document.querySelector(".nav");
  cache.navLinks = document.querySelectorAll(".nav a");

  cache.navPhone = document.querySelector(".nav--phone");
  cache.navPhoneLinks = document.querySelectorAll(".nav--phone a");
};

const toggleTransparentClass = (isInView) => {
  cache.nav?.classList.toggle("transparent", isInView);
};

function toggleInertContactFormContainer(isInView) {
  const formContainer = document.querySelector(".contact__us .form__container");

  if (isInView) {
    // If force is explicitly provided
    if (!isInView) {
      formContainer.setAttribute("inert", "");
      return true;
    } else {
      formContainer.removeAttribute("inert");
      return false;
    }
  }
}
const manageSectionOnScroll = (isInView, { id }) => {
  const section = document.getElementById(id);
  const navLink = document.querySelector(`.nav a[href="#${id}"]`);
  const navPhoneLink = document.querySelector(`.nav--phone a[href="#${id}"]`);

  if (!navLink || !navPhoneLink) return;

  if (isInView) {
    // Clear all active states
    cache.navLinks.forEach((link) =>
      link.parentElement.classList.remove("active")
    );
    cache.navPhoneLinks.forEach((link) =>
      link.parentElement.classList.remove("active")
    );

    // Activate correct link
    navLink.parentElement.classList.add("active");
    navPhoneLink.parentElement.classList.add("active");

    // Trigger animation once
    if (section && !section.classList.contains("animate")) {
      section.classList.add("animate");
      if (section.classList.contains("about__us")) {
        dispatchCustomEvent("about__us__is__animated");
      }
      if (section.classList.contains("services")) {
        dispatchCustomEvent("services__is__animated");
      }
    }

    cache.navPhone?.classList.remove("open");
  }
};

const LandingPageNavigation = () => {
  initCache();

  const sections = [
    {
      el: document.querySelector(".hero"),
      id: "hero",
      threshold: 70,
      extra: toggleTransparentClass, // transparency effect
    },
    { el: document.getElementById("aboutUs"), id: "aboutUs" },
    { el: document.getElementById("services"), id: "services" },
    {
      el: document.getElementById("contactUs"),
      id: "contactUs",
      threshold: 45,
    },
    {
      el: document.getElementById("contactUs"),
      id: "contactUs",
      threshold: 45,
      extra: toggleInertContactFormContainer,
    },
  ];

  sections.forEach(({ el, id, threshold = 75, extra }) => {
    if (!el) return;

    // Extra observer (only hero uses it)
    if (extra) inView(el, threshold, extra);

    // Main scroll observer (hero INCLUDED again)
    inView(el, threshold, manageSectionOnScroll, { id });
  });

  // Auto-active hero on refresh
  const HeroLinks = document.querySelectorAll(`a[href="#hero"]`);

  if (HeroLinks) {
    HeroLinks[0].click();
  }
};

export default LandingPageNavigation;
