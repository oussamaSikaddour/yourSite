import { createCounterAnimation } from "../utils/app/CreateCounterAnimation";

const Counter = (parentSelector)=>{

        document.querySelectorAll(`${parentSelector} .counter`).forEach(counter => {
        const target = Number(counter.textContent.trim());
        if (!Number.isFinite(target) || target <= 0) return;

        counter.textContent = "0";

        const animation = createCounterAnimation(counter, target);
        animation.start();
    });
}

export default Counter
