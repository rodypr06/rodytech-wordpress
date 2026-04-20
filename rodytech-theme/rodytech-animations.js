/**
 * RodyTech Blog — Animations & micro-interactions
 */
(function () {
  'use strict';

  /* ── Scroll-triggered fade-up ── */
  var observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.07, rootMargin: '0px 0px -36px 0px' });

  function initAnimations() {
    var targets = document.querySelectorAll(
      '.article-card:not(.featured), .related-card, .author-box, .author-profile-header, #comments.comments-area, .social-share'
    );
    targets.forEach(function (el, i) {
      el.style.opacity = '0';
      el.style.transform = 'translateY(22px)';
      el.style.transition =
        'opacity 0.55s cubic-bezier(0.22,1,0.36,1) ' + (i * 0.065) + 's, ' +
        'transform 0.55s cubic-bezier(0.22,1,0.36,1) ' + (i * 0.065) + 's';
      observer.observe(el);
    });

    /* Featured card fades in without translateY */
    var featured = document.querySelector('.article-card.featured');
    if (featured) {
      featured.style.opacity = '0';
      featured.style.transition = 'opacity 0.7s cubic-bezier(0.22,1,0.36,1) 0.1s';
      observer.observe(featured);
    }
  }

  /* ── Inject .is-visible styles ── */
  function injectStyles() {
    var s = document.createElement('style');
    s.textContent = '.is-visible { opacity: 1 !important; transform: translateY(0) !important; }';
    document.head.appendChild(s);
  }

  /* ── Sticky header scroll shadow ── */
  function initHeaderScroll() {
    var header = document.getElementById('site-header');
    if (!header) return;
    var ticking = false;
    window.addEventListener('scroll', function () {
      if (!ticking) {
        requestAnimationFrame(function () {
          if (window.scrollY > 40) {
            header.classList.add('scrolled');
          } else {
            header.classList.remove('scrolled');
          }
          ticking = false;
        });
        ticking = true;
      }
    }, { passive: true });
  }

  /* ── Active nav link highlight ── */
  function initActiveNav() {
    var links = document.querySelectorAll('.main-nav a:not(.nav-cta)');
    var path  = window.location.pathname.replace(/\/$/, '');
    links.forEach(function (link) {
      var href = link.getAttribute('href');
      if (!href) return;
      var linkPath = href.replace(/\/$/, '');
      if (linkPath === path || (path === '' && linkPath === window.location.origin)) {
        link.classList.add('nav-active');
      }
    });
  }

  /* ── Image lazy-load blur shimmer ── */
  function initImageShimmer() {
    var imgs = document.querySelectorAll('.card-image, .related-image, .hero-image');
    imgs.forEach(function (img) {
      if (!img.complete || img.naturalWidth === 0) {
        img.style.filter = 'blur(10px)';
        img.style.transition = 'filter 0.45s ease';
        img.addEventListener('load', function () {
          img.style.filter = 'blur(0)';
        });
      }
    });
  }

  /* ── Boot ── */
  document.addEventListener('DOMContentLoaded', function () {
    injectStyles();
    initAnimations();
    initHeaderScroll();
    initActiveNav();
    initImageShimmer();
  });

}());
