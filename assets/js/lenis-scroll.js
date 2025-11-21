/**
 * EDU Career India - Lenis Smooth Scroll
 * Implements smooth scrolling using Lenis library
 */

(function() {
  'use strict';

  // ========================================
  // LENIS SMOOTH SCROLL CONTROLLER
  // ========================================
  class LenisSmoothScroll {
    constructor() {
      this.lenis = null;
      this.init();
    }

    init() {
      // Check if Lenis is loaded
      if (typeof Lenis === 'undefined') {
        console.warn('Lenis library not loaded');
        return;
      }

      // Initialize Lenis
      this.lenis = new Lenis({
        duration: 1.2,
        easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)), // easeOutExpo
        direction: 'vertical', // vertical, horizontal
        gestureDirection: 'vertical', // vertical, horizontal, both
        smooth: true,
        mouseMultiplier: 1,
        smoothTouch: false, // Don't enable smooth scroll on touch devices
        touchMultiplier: 2,
        infinite: false,
      });

      // Request animation frame loop
      this.setupRAF();

      // Integrate with GSAP ScrollTrigger if available
      this.integrateWithGSAP();

      // Add anchor link smooth scrolling
      this.setupAnchorLinks();

      // Expose lenis instance globally for other scripts
      window.lenis = this.lenis;

      console.log('✓ Lenis smooth scroll initialized');
    }

    setupRAF() {
      const raf = (time) => {
        this.lenis.raf(time);
        requestAnimationFrame(raf);
      };

      requestAnimationFrame(raf);
    }

    integrateWithGSAP() {
      // Check if GSAP and ScrollTrigger are available
      if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
        // Update ScrollTrigger on Lenis scroll
        this.lenis.on('scroll', ScrollTrigger.update);

        // Use Lenis's RAF for GSAP ticker
        gsap.ticker.add((time) => {
          this.lenis.raf(time * 1000);
        });

        // Disable GSAP's lag smoothing
        gsap.ticker.lagSmoothing(0);

        console.log('✓ Lenis integrated with GSAP ScrollTrigger');
      }
    }

    setupAnchorLinks() {
      // Find all anchor links
      const anchorLinks = document.querySelectorAll('a[href^="#"]');

      anchorLinks.forEach(link => {
        link.addEventListener('click', (e) => {
          const href = link.getAttribute('href');

          // Skip empty hash links
          if (href === '#') {
            e.preventDefault();
            return;
          }

          // Get target element
          const targetId = href.substring(1);
          const targetElement = document.getElementById(targetId);

          if (targetElement) {
            e.preventDefault();

            // Scroll to target with Lenis
            this.lenis.scrollTo(targetElement, {
              offset: -100, // Account for fixed header
              duration: 1.5,
              easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
            });
          }
        });
      });
    }

    // Public methods
    stop() {
      this.lenis.stop();
    }

    start() {
      this.lenis.start();
    }

    scrollTo(target, options = {}) {
      this.lenis.scrollTo(target, options);
    }

    destroy() {
      this.lenis.destroy();
    }
  }

  // ========================================
  // INITIALIZE ON DOM READY
  // ========================================
  function init() {
    new LenisSmoothScroll();
  }

  // Wait for DOM to be ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
