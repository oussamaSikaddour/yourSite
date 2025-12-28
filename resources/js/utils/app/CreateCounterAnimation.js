export function createCounterAnimation(element, target) {
  const config = {
    totalDuration: 3000, // total animation time
    fastPercentage: 0.9, // 90% fast
    slowPercentage: 0.1, // 10% slow
  };
  const fastEnd = Math.floor(target * config.fastPercentage); // where fast stops
  const fastDuration = config.totalDuration * config.fastPercentage;
  const slowDuration = config.totalDuration * config.slowPercentage;

  // fallback: ensure no division by zero
  const safeFastDuration = Math.max(fastDuration, 16);
  const safeSlowDuration = Math.max(slowDuration, 16);

  let current = 0;

  const update = (value) => {
    element.textContent = value.toString();
  };

  const animatePhase = (start, end, duration, onComplete) => {
    let startTime = null;

    const frame = (timestamp) => {
      if (!startTime) startTime = timestamp;

      const progress = Math.min((timestamp - startTime) / duration, 1);
      const value = start + Math.floor(progress * (end - start));

      current = value;
      update(value);

      if (progress < 1) {
        requestAnimationFrame(frame);
      } else if (typeof onComplete === "function") {
        onComplete();
      }
    };

    requestAnimationFrame(frame);
  };

  const start = () => {
    animatePhase(0, fastEnd, safeFastDuration, () => {
      animatePhase(fastEnd, target, safeSlowDuration);
    });
  };

  return { start };
}
