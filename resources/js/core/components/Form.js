// -----------------------------------------------------------------------------
// IMPORTS
// -----------------------------------------------------------------------------
import { toggleInertForChildElement } from '../../utils/Inert';
import { on } from '../../utils/DespatchCustomEvent';

// -----------------------------------------------------------------------------
// FORM UTILITIES
// -----------------------------------------------------------------------------
const isValidFormElement = (form) =>
  form && form.tagName?.toLowerCase() === 'form';

const isFocusableLabel = (el) =>
  el?.matches('label[tabindex="0"]');

const isInputVisible = (input) =>
  !input.matches('[style*="display: none"]') &&
  !input.hasAttribute('hidden');

const focusElement = (el) => el.focus();

const findFirstFocusableElement = (form) => {
  let el = form.querySelector('input');
  while (el) {
    if (isFocusableLabel(el.nextElementSibling)) return el.nextElementSibling;
    if (isInputVisible(el)) return el;
    el = el.nextElementSibling;
  }
  return null;
};

export const focusNonHiddenInput = (form) => {
  if (!isValidFormElement(form)) return;
  const el = findFirstFocusableElement(form);
  if (el) focusElement(el);
};

// -----------------------------------------------------------------------------
// CLEAR FORM ERRORS WHEN ANY INPUT FOCUSES
// -----------------------------------------------------------------------------
const getDefaultForm = () => document.querySelector('.form');
const getFormInputs = (form) => form.querySelectorAll('input, select, textarea');
const getFormErrors = (form) => form.querySelectorAll('.input__error');

export function clearErrorsOnFocus(myForm = null) {
  const form = myForm || getDefaultForm();
  if (!form) return;

  const handler = () =>
    getFormErrors(form).forEach((err) => (err.innerHTML = ''));

  getFormInputs(form).forEach((input) =>
    input.addEventListener('focus', handler)
  );
}

// -----------------------------------------------------------------------------
// STORAGE (SRP)
// -----------------------------------------------------------------------------
// -----------------------------------------------------------------------------
// STORAGE HELPERS
// -----------------------------------------------------------------------------
const getStoredStep = (key) => {
  const x = localStorage.getItem(key);
  return x ? parseInt(x) : null;
};

const storeStep = (key, step) => localStorage.setItem(key, step);

export const clearStep = (key) => localStorage.removeItem(key);



// -----------------------------------------------------------------------------
// MULTI-FORM ELEMENTS
// -----------------------------------------------------------------------------
const els = (multiFormSelector) => {
  const container = document.querySelector(`.${multiFormSelector}`);
  if (!container) return null;

  return {
    container,
    forms: container.querySelector('.forms'),
  };
};


// FIXED: no buttons anymore
const valid = (obj) =>
  obj && obj.container && obj.forms;






// -----------------------------------------------------------------------------
// ACCESSIBILITY SYNC
// -----------------------------------------------------------------------------
const syncInert = (forms, active) => {
  [...forms.children].forEach((form, i) => {
    toggleInertForChildElement(forms, form, 'slide', i + 1 !== active);
  });
};

export const syncFocus = (forms, active) => {
  [...forms.children].forEach((form, i) => {
    if (i + 1 === active) focusNonHiddenInput(form);
  });
};



// -----------------------------------------------------------------------------
// STEP HANDLING
// -----------------------------------------------------------------------------
const goTo = (container, forms, step, key) => {
  clearStep(key);
  container.style.setProperty('--form-position', step);
  syncInert(forms, step);
  syncFocus(forms, step);
  storeStep(key, step);
};

const resolveInitialStep = (key) => getStoredStep(key) || 1;





export const  initSlideOneEvent= (eventName)=>{

  on(eventName, (event) => {

    const key=event[0]
    const step=resolveInitialStep(key)
    const item = els(key);

    if (!valid(item)) return;

    const { container, forms } = item;

    const storageKey = key;
    goTo(container, forms,step, storageKey);
  });
}
// -----------------------------------------------------------------------------
// TRIGGER SLIDE BY CUSTOM EVENT
// -----------------------------------------------------------------------------
export const slideOnEvent = (eventName) => {
  on(eventName, (event) => {

    const key=event[0][0]
    const step=event[0][1]
    const item = els(key);

    if (!valid(item)) return;

    const { container, forms } = item;
    const storageKey = key;
    goTo(container, forms,step, storageKey);

  });
};




// -----------------------------------------------------------------------------
// CLEAR STEPS VIA CUSTOM EVENT (RESET TO STEP 1)
// -----------------------------------------------------------------------------
export const clearMultiFormStepOnEvent = (eventName) => {
  on(eventName, (event) => {


    const key=event[0]
    const item = els(key);
    if (!valid(item)) return;
    const { container, forms } = item;

    clearStep(key);
    goTo(container, forms, 1, key);
  });
};
