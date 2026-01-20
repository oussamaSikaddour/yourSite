export function debounce(fn, wait = 150) {
  let t = null;
  let lastArgs;
  let lastThis;

  function debounced(...args) {
    lastArgs = args;
    lastThis = this;

    if (t) clearTimeout(t);
    t = setTimeout(() => {
      t = null;
      fn.apply(lastThis, lastArgs);
    }, wait);
  }

  debounced.cancel = () => {
    if (t) clearTimeout(t);
    t = null;
    lastArgs = lastThis = undefined;
  };

  // Immediately run if pending
  debounced.flush = () => {
    if (!t) return;
    clearTimeout(t);
    t = null;
    fn.apply(lastThis, lastArgs);
  };

  return debounced;
}


export function throttle(fn, wait = 1000) {
  let lastTime = 0;
  let t = null;
  let lastArgs;
  let lastThis;

  function invoke(time) {
    lastTime = time;
    t = null;
    fn.apply(lastThis, lastArgs);
    lastArgs = lastThis = undefined;
  }

  function throttled(...args) {
    const now = Date.now();
    lastArgs = args;
    lastThis = this;

    const remaining = wait - (now - lastTime);

    if (remaining <= 0) {
      // time window passed: run now
      if (t) {
        clearTimeout(t);
        t = null;
      }
      invoke(now);
    } else if (!t) {
      // schedule trailing call with latest args
      t = setTimeout(() => invoke(Date.now()), remaining);
    }
  }

  throttled.cancel = () => {
    if (t) clearTimeout(t);
    t = null;
    lastTime = 0;
    lastArgs = lastThis = undefined;
  };

  // Run trailing call immediately if pending
  throttled.flush = () => {
    if (!t) return;
    clearTimeout(t);
    invoke(Date.now());
  };

  return throttled;
}



// Retrieve the computed background color of a DOM element
export const getBackgroundColor = (element) => {
  const computedStyle = window.getComputedStyle(element); // Gets all computed styles
  return computedStyle.backgroundColor; // Returns just the background color
};

