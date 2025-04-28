import './bootstrap';
import Lenis from 'lenis';

// Initialize Lenis on DOM load
document.addEventListener('DOMContentLoaded', () => {
    // Initialize Lenis smooth scrolling
    const lenis = new Lenis({
        duration: 2.5,
        easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
        orientation: 'vertical',
        smoothWheel: true,
        wheelMultiplier: 2,
        smoothTouch: true,
        touchMultiplier: 2,
        infinite: false,
    });

    // RAF setup for smooth animation
    function raf(time) {
        lenis.raf(time);
        requestAnimationFrame(raf);
    }

    // Start the animation loop
    requestAnimationFrame(raf);

    // Handle smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', (e) => {
            e.preventDefault();
            const target = document.querySelector(anchor.getAttribute('href'));
            if (target) {
                lenis.scrollTo(target);
            }
        });
    });
});
