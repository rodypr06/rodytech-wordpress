/**
 * RodyTech Blog - Scroll animations & micro-interactions
 */
(function() {
  'use strict';

  // Intersection Observer for scroll-triggered animations
  const observer = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });

  // Observe article cards and sections
  function initAnimations() {
    var cards = document.querySelectorAll('.article-card, .related-card, .author-box, .comments-section');
    cards.forEach(function(el, i) {
      el.style.opacity = '0';
      el.style.transform = 'translateY(20px)';
      el.style.transition = 'opacity 0.55s cubic-bezier(0.22,1,0.36,1) ' + (i * 0.07) + 's, transform 0.55s cubic-bezier(0.22,1,0.36,1) ' + (i * 0.07) + 's';
      observer.observe(el);
    });
  }

  // When element enters view
  document.addEventListener('DOMContentLoaded', function() {
    initAnimations();

    // Add visible styles via JS
    var style = document.createElement('style');
    style.textContent = '.is-visible { opacity: 1 !important; transform: translateY(0) !important; }';
    document.head.appendChild(style);

    // Smooth active nav link highlight
    var navLinks = document.querySelectorAll('.main-nav a');
    var currentPath = window.location.pathname;
    navLinks.forEach(function(link) {
      if (link.getAttribute('href') === currentPath || link.href === window.location.href) {
        link.style.color = 'var(--accent)';
        link.style.background = 'var(--accent-light)';
      }
    });

    // Image lazy load shimmer
    var images = document.querySelectorAll('.card-image, .related-image');
    images.forEach(function(img) {
      if (!img.complete) {
        img.style.filter = 'blur(8px)';
        img.addEventListener('load', function() {
          img.style.transition = 'filter 0.4s ease';
          img.style.filter = 'blur(0)';
        });
      }
    });
  });
})();
