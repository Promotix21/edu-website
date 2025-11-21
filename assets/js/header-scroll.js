/**
 * EDU Career India - Header Scroll Animation
 * Makes header transparent on homepage, then opaque on scroll
 */

(function() {
  'use strict';

  // Check if we're on the homepage
  const isHomepage = document.body.classList.contains('home') ||
                     window.location.pathname === '/' ||
                     window.location.pathname === '/index.php';

  if (!isHomepage) {
    return; // Only run on homepage
  }

  const header = document.querySelector('.header');
  if (!header) return;

  // Make header transparent initially on homepage
  header.classList.add('transparent');

  let lastScrollTop = 0;
  const scrollThreshold = 100; // When to trigger the transition

  function handleScroll() {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

    if (scrollTop > scrollThreshold) {
      // Scrolled down - make header opaque
      header.classList.remove('transparent');
    } else {
      // At top - make header transparent
      header.classList.add('transparent');
    }

    lastScrollTop = scrollTop;
  }

  // Listen for scroll events with throttling for performance
  let ticking = false;
  window.addEventListener('scroll', function() {
    if (!ticking) {
      window.requestAnimationFrame(function() {
        handleScroll();
        ticking = false;
      });
      ticking = true;
    }
  });

  // Initial check
  handleScroll();

  console.log('✓ Header scroll animation initialized');
})();
