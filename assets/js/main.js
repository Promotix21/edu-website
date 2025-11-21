/**
 * EDU Career India - Main JavaScript
 * Handles navigation, animations, form validation, and interactive elements
 */

(function() {
  'use strict';

  // ==========================================
  // MOBILE NAVIGATION TOGGLE
  // ==========================================
  const mobileToggle = document.querySelector('.mobile-toggle');
  const navMenu = document.querySelector('.nav-menu');

  if (mobileToggle && navMenu) {
    mobileToggle.addEventListener('click', function() {
      navMenu.classList.toggle('active');
      this.classList.toggle('active');
    });

    // Close menu when clicking on nav links
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
      link.addEventListener('click', () => {
        navMenu.classList.remove('active');
        mobileToggle.classList.remove('active');
      });
    });

    // Close menu when clicking outside
    document.addEventListener('click', function(event) {
      if (!event.target.closest('.navbar')) {
        navMenu.classList.remove('active');
        mobileToggle.classList.remove('active');
      }
    });
  }

  // ==========================================
  // STICKY HEADER ON SCROLL
  // ==========================================
  const header = document.querySelector('.header');
  let lastScroll = 0;

  window.addEventListener('scroll', function() {
    const currentScroll = window.pageYOffset;

    if (currentScroll > 100) {
      header.classList.add('scrolled');
    } else {
      header.classList.remove('scrolled');
    }

    lastScroll = currentScroll;
  });

  // ==========================================
  // SMOOTH SCROLL FOR ANCHOR LINKS
  // ==========================================
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const href = this.getAttribute('href');

      // Skip if href is just "#"
      if (href === '#') return;

      e.preventDefault();
      const target = document.querySelector(href);

      if (target) {
        const offsetTop = target.offsetTop - 80; // Account for fixed header
        window.scrollTo({
          top: offsetTop,
          behavior: 'smooth'
        });
      }
    });
  });

  // ==========================================
  // ANIMATE ON SCROLL
  // ==========================================
  const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
  };

  const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('animated');
        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  // Observe all elements with animate-on-scroll class
  document.querySelectorAll('.animate-on-scroll').forEach(el => {
    observer.observe(el);
  });

  // ==========================================
  // STATS COUNTER ANIMATION
  // ==========================================
  function animateCounter(element, target, duration = 2000) {
    const start = 0;
    const increment = target / (duration / 16); // 60 FPS
    let current = start;

    const timer = setInterval(() => {
      current += increment;
      if (current >= target) {
        element.textContent = formatNumber(target);
        clearInterval(timer);
      } else {
        element.textContent = formatNumber(Math.floor(current));
      }
    }, 16);
  }

  function formatNumber(num) {
    if (num >= 1000) {
      return (num / 1000).toFixed(1).replace(/\.0$/, '') + 'K';
    }
    return num.toString();
  }

  // Trigger counter animation when stats section is visible
  const statsSection = document.querySelector('.stats');
  if (statsSection) {
    const statsObserver = new IntersectionObserver(function(entries) {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const statNumbers = entry.target.querySelectorAll('.stat-number');
          statNumbers.forEach(stat => {
            const text = stat.textContent.replace(/[^0-9]/g, '');
            const target = parseInt(text);
            if (target) {
              stat.textContent = '0';
              animateCounter(stat, target);
            }
          });
          statsObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.3 });

    statsObserver.observe(statsSection);
  }

  // ==========================================
  // CONTACT FORM VALIDATION
  // ==========================================
  const contactForm = document.getElementById('contactForm');

  if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
      e.preventDefault();

      // Get form values
      const name = document.getElementById('name').value.trim();
      const email = document.getElementById('email').value.trim();
      const phone = document.getElementById('phone').value.trim();
      const course = document.getElementById('course').value;
      const message = document.getElementById('message').value.trim();

      // Basic validation
      if (!name || !email || !phone || !course) {
        alert('Please fill in all required fields.');
        return false;
      }

      // Email validation
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
        alert('Please enter a valid email address.');
        return false;
      }

      // Phone validation (basic)
      const phoneRegex = /^[0-9+\s-]{10,15}$/;
      if (!phoneRegex.test(phone)) {
        alert('Please enter a valid phone number.');
        return false;
      }

      // If validation passes, submit the form
      // In production, this would submit via AJAX
      alert('Thank you for your inquiry! We will contact you within 24 hours.');
      contactForm.reset();

      // Uncomment below for actual form submission
      // this.submit();
    });
  }

  // ==========================================
  // FAQ ACCORDION
  // ==========================================
  const faqItems = document.querySelectorAll('.faq-item');

  faqItems.forEach(item => {
    const question = item.querySelector('.faq-question');
    const answer = item.querySelector('.faq-answer');

    if (question && answer) {
      // Hide answer initially
      answer.style.display = 'none';

      question.addEventListener('click', function() {
        const isOpen = answer.style.display === 'block';

        // Close all other FAQs
        faqItems.forEach(otherItem => {
          const otherAnswer = otherItem.querySelector('.faq-answer');
          const otherIcon = otherItem.querySelector('.faq-question span');
          if (otherAnswer && otherAnswer !== answer) {
            otherAnswer.style.display = 'none';
            if (otherIcon) otherIcon.textContent = '+';
          }
        });

        // Toggle current FAQ
        if (isOpen) {
          answer.style.display = 'none';
          question.querySelector('span').textContent = '+';
        } else {
          answer.style.display = 'block';
          question.querySelector('span').textContent = '−';
        }
      });
    }
  });

  // ==========================================
  // LAZY LOADING IMAGES
  // ==========================================
  if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const img = entry.target;
          if (img.dataset.src) {
            img.src = img.dataset.src;
            img.removeAttribute('data-src');
          }
          imageObserver.unobserve(img);
        }
      });
    });

    document.querySelectorAll('img[data-src]').forEach(img => {
      imageObserver.observe(img);
    });
  }

  // ==========================================
  // COURSE NAVIGATION HIGHLIGHT
  // ==========================================
  const courseNavLinks = document.querySelectorAll('a[href^="#"]');

  if (courseNavLinks.length > 0) {
    window.addEventListener('scroll', function() {
      let current = '';
      const sections = document.querySelectorAll('.course-section');

      sections.forEach(section => {
        const sectionTop = section.offsetTop - 150;
        const sectionHeight = section.clientHeight;

        if (window.pageYOffset >= sectionTop && window.pageYOffset < sectionTop + sectionHeight) {
          current = section.getAttribute('id');
        }
      });

      courseNavLinks.forEach(link => {
        link.style.backgroundColor = '';
        link.style.color = 'var(--primary-color)';

        if (link.getAttribute('href') === '#' + current) {
          link.style.backgroundColor = 'var(--primary-color)';
          link.style.color = 'var(--white)';
        }
      });
    });
  }

  // ==========================================
  // PREVENT FORM RESUBMISSION ON REFRESH
  // ==========================================
  if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
  }

  // ==========================================
  // CONSOLE MESSAGE
  // ==========================================
  console.log('%cEDU Career India', 'font-size: 24px; color: #1e40af; font-weight: bold;');
  console.log('%cYour Dream, Our Mission', 'font-size: 14px; color: #f59e0b;');

})();
