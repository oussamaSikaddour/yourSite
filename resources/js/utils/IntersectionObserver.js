export const inView = (element, visibilityPercentage, callback, props = {}) => {
  if (!element) return;
  const observer = new IntersectionObserver((entries) => {
    callback(entries[0].isIntersecting, props);
  }, {
    threshold: visibilityPercentage / 100,
  });

  observer.observe(element);

  // Optional: return cleanup
  return () => observer.disconnect();
};





