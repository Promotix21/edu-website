/**
 * EDU Career India - Sliders (Hero Banner + Testimonials)
 * Handles hero banner slider and testimonials carousel
 */

(function() {
  'use strict';

  // ========================================
  // HERO BANNER SLIDER
  // ========================================
  class HeroSlider {
    constructor() {
      this.slider = document.querySelector('.hero-slider');
      if (!this.slider) return;

      this.slides = Array.from(this.slider.querySelectorAll('.hero-slide'));
      this.currentSlide = 0;
      this.totalSlides = this.slides.length;
      this.autoplayInterval = null;
      this.autoplayDelay = 6000; // 6 seconds per slide

      this.init();
    }

    init() {
      // Create slider dots
      this.createDots();

      // Setup controls
      this.setupControls();

      // Start autoplay
      this.startAutoplay();

      // Pause autoplay on hover
      this.slider.addEventListener('mouseenter', () => this.stopAutoplay());
      this.slider.addEventListener('mouseleave', () => this.startAutoplay());

      // Touch events for mobile swiping
      this.setupTouchEvents();

      // Keyboard navigation
      document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') this.prevSlide();
        if (e.key === 'ArrowRight') this.nextSlide();
      });
    }

    createDots() {
      const dotsContainer = document.querySelector('.slider-dots');
      if (!dotsContainer) return;

      this.slides.forEach((_, index) => {
        const dot = document.createElement('div');
        dot.classList.add('slider-dot');
        if (index === 0) dot.classList.add('active');
        dot.addEventListener('click', () => this.goToSlide(index));
        dotsContainer.appendChild(dot);
      });

      this.dots = Array.from(dotsContainer.querySelectorAll('.slider-dot'));
    }

    setupControls() {
      const prevBtn = document.querySelector('.hero-slider-controls .slider-btn.prev');
      const nextBtn = document.querySelector('.hero-slider-controls .slider-btn.next');

      if (prevBtn) prevBtn.addEventListener('click', () => this.prevSlide());
      if (nextBtn) nextBtn.addEventListener('click', () => this.nextSlide());
    }

    goToSlide(index) {
      // Remove active class from current slide and dot
      this.slides[this.currentSlide].classList.remove('active');
      if (this.dots) this.dots[this.currentSlide].classList.remove('active');

      // Update current slide
      this.currentSlide = index;

      // Add active class to new slide and dot
      this.slides[this.currentSlide].classList.add('active');
      if (this.dots) this.dots[this.currentSlide].classList.add('active');

      // Reset autoplay
      this.stopAutoplay();
      this.startAutoplay();
    }

    nextSlide() {
      const nextIndex = (this.currentSlide + 1) % this.totalSlides;
      this.goToSlide(nextIndex);
    }

    prevSlide() {
      const prevIndex = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
      this.goToSlide(prevIndex);
    }

    startAutoplay() {
      this.autoplayInterval = setInterval(() => {
        this.nextSlide();
      }, this.autoplayDelay);
    }

    stopAutoplay() {
      if (this.autoplayInterval) {
        clearInterval(this.autoplayInterval);
        this.autoplayInterval = null;
      }
    }

    setupTouchEvents() {
      let touchStartX = 0;
      let touchEndX = 0;

      this.slider.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
      });

      this.slider.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        this.handleSwipe(touchStartX, touchEndX);
      });
    }

    handleSwipe(startX, endX) {
      const swipeThreshold = 50;
      const diff = startX - endX;

      if (Math.abs(diff) > swipeThreshold) {
        if (diff > 0) {
          // Swiped left - next slide
          this.nextSlide();
        } else {
          // Swiped right - previous slide
          this.prevSlide();
        }
      }
    }
  }

  // ========================================
  // TESTIMONIALS CAROUSEL
  // ========================================
  class TestimonialsCarousel {
    constructor() {
      this.carousel = document.querySelector('.testimonials-carousel');
      if (!this.carousel) return;

      this.track = this.carousel.querySelector('.testimonial-track');
      this.cards = Array.from(this.track.querySelectorAll('.testimonial-card'));
      this.currentIndex = 0;
      this.cardWidth = 0;
      this.cardsPerView = 1;
      this.autoplayInterval = null;
      this.autoplayDelay = 5000; // 5 seconds

      this.init();
    }

    init() {
      // Calculate cards per view based on screen size
      this.calculateCardsPerView();

      // Clone first and last cards for infinite loop effect
      this.setupInfiniteLoop();

      // Setup controls
      this.setupControls();

      // Start autoplay
      this.startAutoplay();

      // Pause autoplay on hover
      this.carousel.addEventListener('mouseenter', () => this.stopAutoplay());
      this.carousel.addEventListener('mouseleave', () => this.startAutoplay());

      // Handle window resize
      let resizeTimer;
      window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
          this.calculateCardsPerView();
          this.updateCarousel(false);
        }, 250);
      });

      // Touch events for mobile swiping
      this.setupTouchEvents();

      // Initial position
      this.updateCarousel(false);
    }

    calculateCardsPerView() {
      const width = window.innerWidth;

      if (width >= 1200) {
        this.cardsPerView = 3;
      } else if (width >= 768) {
        this.cardsPerView = 2;
      } else {
        this.cardsPerView = 1;
      }

      // Get actual card width including gap (2rem = 32px)
      const card = this.cards[0];
      if (card) {
        this.cardWidth = card.offsetWidth + 32; // card width + gap
      } else {
        this.cardWidth = 412; // fallback: 380px + 32px gap
      }
    }

    setupInfiniteLoop() {
      // Clone first few cards and append to end
      const clones = [];
      for (let i = 0; i < this.cardsPerView; i++) {
        const clone = this.cards[i].cloneNode(true);
        clone.classList.add('clone');
        clones.push(clone);
      }
      clones.forEach(clone => this.track.appendChild(clone));
    }

    setupControls() {
      const prevBtn = this.carousel.querySelector('.carousel-btn.prev');
      const nextBtn = this.carousel.querySelector('.carousel-btn.next');

      if (prevBtn) prevBtn.addEventListener('click', () => this.prev());
      if (nextBtn) nextBtn.addEventListener('click', () => this.next());
    }

    updateCarousel(animate = true) {
      // Calculate offset to center the visible cards
      const containerWidth = this.carousel.offsetWidth;
      const visibleCardsWidth = this.cardsPerView * this.cardWidth - 32; // Subtract one gap
      const centerOffset = (containerWidth - visibleCardsWidth) / 2;

      // Calculate translation with centering
      const translateX = centerOffset - (this.currentIndex * this.cardWidth);

      if (animate) {
        this.track.style.transition = 'transform 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
      } else {
        this.track.style.transition = 'none';
      }

      this.track.style.transform = `translateX(${translateX}px)`;
      // Keep cards centered - don't override CSS justify-content
    }

    next() {
      this.currentIndex++;

      this.updateCarousel(true);

      // Check if we've reached the cloned cards
      if (this.currentIndex >= this.cards.length) {
        setTimeout(() => {
          this.currentIndex = 0;
          this.updateCarousel(false);
        }, 500);
      }

      this.resetAutoplay();
    }

    prev() {
      this.currentIndex--;

      if (this.currentIndex < 0) {
        this.currentIndex = this.cards.length - 1;
        this.updateCarousel(false);

        // Force a reflow
        this.track.offsetHeight;

        setTimeout(() => {
          this.currentIndex--;
          this.updateCarousel(true);
        }, 10);
      } else {
        this.updateCarousel(true);
      }

      this.resetAutoplay();
    }

    startAutoplay() {
      this.autoplayInterval = setInterval(() => {
        this.next();
      }, this.autoplayDelay);
    }

    stopAutoplay() {
      if (this.autoplayInterval) {
        clearInterval(this.autoplayInterval);
        this.autoplayInterval = null;
      }
    }

    resetAutoplay() {
      this.stopAutoplay();
      this.startAutoplay();
    }

    setupTouchEvents() {
      let touchStartX = 0;
      let touchEndX = 0;

      this.track.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
        this.stopAutoplay();
      });

      this.track.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        this.handleSwipe(touchStartX, touchEndX);
        this.startAutoplay();
      });
    }

    handleSwipe(startX, endX) {
      const swipeThreshold = 50;
      const diff = startX - endX;

      if (Math.abs(diff) > swipeThreshold) {
        if (diff > 0) {
          // Swiped left - next
          this.next();
        } else {
          // Swiped right - previous
          this.prev();
        }
      }
    }
  }

  // ========================================
  // INITIALIZE ON DOM READY
  // ========================================
  function init() {
    // Initialize hero slider
    new HeroSlider();

    // Initialize testimonials carousel
    new TestimonialsCarousel();

    console.log('✓ Sliders initialized');
  }

  // Wait for DOM to be ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
