/**
 * EDU Career India - Animation Controller
 * GSAP 3.12+ with ScrollTrigger & SplitType
 */

// Import GSAP (loaded via CDN in HTML)
// gsap, ScrollTrigger, SplitType available globally

class AnimationController {
  constructor() {
    this.init();
  }

  init() {
    // Register GSAP plugins
    gsap.registerPlugin(ScrollTrigger);

    // Initialize animations
    this.initTextAnimations();
    this.initScrollAnimations();
    this.initHeroAnimation();
    this.initStatsCounter();
    this.initServiceCards();

    // Recalculate on resize
    this.handleResize();
  }

  /**
   * Text Animations with SplitType - Word Breaking Prevention
   */
  initTextAnimations() {
    const textElements = document.querySelectorAll('.animate-text');

    textElements.forEach(element => {
      // Split text into words (NOT characters to prevent mid-word breaking)
      const splitText = new SplitType(element, {
        types: 'words',
        tagName: 'span'
      });

      // Prevent word wrapping issues
      if (splitText.words) {
        splitText.words.forEach(word => {
          word.style.display = 'inline-block';
          word.style.whiteSpace = 'nowrap'; // CRITICAL - prevents mid-word breaks
        });

        // Animate with blur effect
        gsap.fromTo(splitText.words,
          {
            opacity: 0,
            filter: 'blur(10px)',
            y: 20
          },
          {
            opacity: 1,
            filter: 'blur(0px)',
            y: 0,
            duration: 0.8,
            stagger: 0.05,
            ease: 'power2.out',
            scrollTrigger: {
              trigger: element,
              start: 'top 85%',
              once: true
            }
          }
        );
      }
    });
  }

  /**
   * Scroll-triggered animations
   */
  initScrollAnimations() {
    // Fade in elements
    gsap.utils.toArray('.fade-in').forEach(element => {
      gsap.fromTo(element,
        { opacity: 0 },
        {
          opacity: 1,
          duration: 1,
          ease: 'power2.out',
          scrollTrigger: {
            trigger: element,
            start: 'top 80%',
            once: true
          }
        }
      );
    });

    // Slide up elements
    gsap.utils.toArray('.slide-up').forEach(element => {
      gsap.fromTo(element,
        { opacity: 0, y: 50 },
        {
          opacity: 1,
          y: 0,
          duration: 1,
          ease: 'power2.out',
          scrollTrigger: {
            trigger: element,
            start: 'top 80%',
            once: true
          }
        }
      );
    });

    // Slide from left
    gsap.utils.toArray('.slide-left').forEach(element => {
      gsap.fromTo(element,
        { opacity: 0, x: -50 },
        {
          opacity: 1,
          x: 0,
          duration: 1,
          ease: 'power2.out',
          scrollTrigger: {
            trigger: element,
            start: 'top 80%',
            once: true
          }
        }
      );
    });

    // Slide from right
    gsap.utils.toArray('.slide-right').forEach(element => {
      gsap.fromTo(element,
        { opacity: 0, x: 50 },
        {
          opacity: 1,
          x: 0,
          duration: 1,
          ease: 'power2.out',
          scrollTrigger: {
            trigger: element,
            start: 'top 80%',
            once: true
          }
        }
      );
    });

    // Scale in elements
    gsap.utils.toArray('.scale-in').forEach(element => {
      gsap.fromTo(element,
        { opacity: 0, scale: 0.8 },
        {
          opacity: 1,
          scale: 1,
          duration: 1,
          ease: 'back.out(1.7)',
          scrollTrigger: {
            trigger: element,
            start: 'top 80%',
            once: true
          }
        }
      );
    });
  }

  /**
   * Hero section animation
   */
  initHeroAnimation() {
    const heroContent = document.querySelector('.hero-content');
    if (!heroContent) return;

    const timeline = gsap.timeline({ defaults: { ease: 'power3.out' } });

    timeline
      .fromTo('.hero-title',
        { opacity: 0, y: 50, filter: 'blur(10px)' },
        { opacity: 1, y: 0, filter: 'blur(0px)', duration: 1.2 }
      )
      .fromTo('.hero-subtitle',
        { opacity: 0, y: 30 },
        { opacity: 1, y: 0, duration: 1 },
        '-=0.8'
      )
      .fromTo('.hero-buttons .btn',
        { opacity: 0, y: 20 },
        { opacity: 1, y: 0, duration: 0.8, stagger: 0.2 },
        '-=0.6'
      );
  }

  /**
   * Animated statistics counter
   */
  initStatsCounter() {
    const statNumbers = document.querySelectorAll('.stat-number');

    statNumbers.forEach(stat => {
      const target = parseInt(stat.getAttribute('data-count') || stat.textContent);
      const hasPlus = stat.textContent.includes('+');
      const hasPercent = stat.textContent.includes('%');

      gsap.fromTo(stat,
        { textContent: 0 },
        {
          textContent: target,
          duration: 2.5,
          ease: 'power2.out',
          snap: { textContent: 1 },
          scrollTrigger: {
            trigger: stat,
            start: 'top 80%',
            once: true
          },
          onUpdate: function() {
            const current = Math.round(this.targets()[0].textContent);
            stat.textContent = current.toLocaleString() + (hasPlus ? '+' : '') + (hasPercent ? '%' : '');
          }
        }
      );
    });
  }

  /**
   * Service card hover animations
   */
  initServiceCards() {
    const cards = document.querySelectorAll('.service-card');

    cards.forEach(card => {
      const image = card.querySelector('.service-image img');

      card.addEventListener('mouseenter', () => {
        gsap.to(card, {
          y: -8,
          boxShadow: '0 16px 48px rgba(37, 99, 235, 0.2)',
          duration: 0.4,
          ease: 'power2.out'
        });

        if (image) {
          gsap.to(image, {
            scale: 1.1,
            duration: 0.6,
            ease: 'power2.out'
          });
        }
      });

      card.addEventListener('mouseleave', () => {
        gsap.to(card, {
          y: 0,
          boxShadow: '0 8px 32px rgba(37, 99, 235, 0.16)',
          duration: 0.4,
          ease: 'power2.out'
        });

        if (image) {
          gsap.to(image, {
            scale: 1,
            duration: 0.6,
            ease: 'power2.out'
          });
        }
      });
    });
  }

  /**
   * Handle window resize
   */
  handleResize() {
    let resizeTimer;
    window.addEventListener('resize', () => {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(() => {
        // Recalculate text splits
        const textElements = document.querySelectorAll('.animate-text');
        textElements.forEach(element => {
          SplitType.revert(element);
        });

        this.initTextAnimations();
        ScrollTrigger.refresh();
      }, 250);
    });
  }
}

// Initialize animations when DOM is ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    new AnimationController();
  });
} else {
  new AnimationController();
}
