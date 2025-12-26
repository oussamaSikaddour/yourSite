// Debounce utility to limit how often a function can be executed
// Useful for performance optimization (e.g., input or scroll events)
export const debounce = (func, wait = 150) => {
  let timeout;

  return function (...args) {
    const context = this; // preserve caller context
    clearTimeout(timeout);
    timeout = setTimeout(() => func.apply(context, args), wait);
  };
};


// Retrieve the computed background color of a DOM element
export const getBackgroundColor = (element) => {
  const computedStyle = window.getComputedStyle(element); // Gets all computed styles
  return computedStyle.backgroundColor; // Returns just the background color
};

