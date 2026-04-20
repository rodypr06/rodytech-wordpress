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
      '.article-card:not(.featured), .story-card:not(.story-card-featured), .collection-card, .editorial-note-card, .sidebar-card, .related-card, .author-box, .author-profile-header, #comments.comments-area, .social-share'
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
    var featured = document.querySelector('.article-card.featured, .story-card-featured');
    if (featured) {
      featured.style.opacity = '0';
      featured.style.transition = 'opacity 0.7s cubic-bezier(0.22,1,0.36,1) 0.1s';
      observer.observe(featured);
    }
  }

  /* ── Interactive background network ── */
  function initNetworkField() {
    var canvas = document.getElementById('network');
    if (!canvas) return;

    var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var context = canvas.getContext('2d');
    if (!context) return;

    var pointer = { x: window.innerWidth / 2, y: window.innerHeight / 2 };
    var state = { nodes: [], ratio: window.devicePixelRatio || 1, raf: null };
    var config = {
      density: 7,
      maxConnections: 2,
      drift: 22,
      speed: 0.00034,
      attraction: 0.06,
      attractionRange: 220
    };

    function distance(a, b) {
      var dx = a.x - b.x;
      var dy = a.y - b.y;
      return Math.sqrt(dx * dx + dy * dy);
    }

    function drawHex(x, y, radius, alpha) {
      var i;
      context.beginPath();
      for (i = 0; i < 6; i += 1) {
        var angle = (Math.PI / 3) * i + Math.PI / 6;
        var px = x + radius * Math.cos(angle);
        var py = y + radius * Math.sin(angle);
        if (i === 0) {
          context.moveTo(px, py);
        } else {
          context.lineTo(px, py);
        }
      }
      context.closePath();
      context.fillStyle = 'rgba(255,255,255,' + alpha + ')';
      context.fill();
    }

    function buildField() {
      var ratio = window.devicePixelRatio || 1;
      var step = Math.max(140, 1000 / config.density);
      var x;
      var y;

      state.ratio = ratio;
      canvas.width = Math.floor(window.innerWidth * ratio);
      canvas.height = Math.floor(window.innerHeight * ratio);
      canvas.style.width = window.innerWidth + 'px';
      canvas.style.height = window.innerHeight + 'px';
      context.setTransform(ratio, 0, 0, ratio, 0, 0);

      state.nodes = [];
      for (x = -80; x < window.innerWidth + 80; x += step) {
        for (y = -80; y < window.innerHeight + 80; y += step) {
          state.nodes.push({
            originX: x + Math.random() * step,
            originY: y + Math.random() * step,
            x: x,
            y: y,
            radius: 9 + Math.random() * 3,
            phase: Math.random() * Math.PI * 2,
            alpha: 0.12 + Math.random() * 0.16,
            close: []
          });
        }
      }

      state.nodes.forEach(function (node) {
        node.close = state.nodes
          .filter(function (other) { return other !== node; })
          .sort(function (a, b) { return distance(node, a) - distance(node, b); })
          .slice(0, config.maxConnections);
      });
    }

    function renderFrame(time) {
      context.clearRect(0, 0, window.innerWidth, window.innerHeight);

      state.nodes.forEach(function (node) {
        var attractionDistance = distance(node, pointer);
        var pull = attractionDistance < config.attractionRange
          ? ((config.attractionRange - attractionDistance) / config.attractionRange) * config.attraction
          : 0;

        node.x = node.originX + Math.sin(time * config.speed + node.phase) * config.drift - (node.originX - pointer.x) * pull;
        node.y = node.originY + Math.cos(time * config.speed + node.phase) * config.drift - (node.originY - pointer.y) * pull;
      });

      state.nodes.forEach(function (node) {
        node.close.forEach(function (other) {
          context.beginPath();
          context.moveTo(node.x, node.y);
          context.lineTo(other.x, other.y);
          context.strokeStyle = 'rgba(255,255,255,0.18)';
          context.lineWidth = 1;
          context.stroke();
        });
      });

      state.nodes.forEach(function (node) {
        drawHex(node.x, node.y, node.radius, node.alpha);
      });

      if (!prefersReducedMotion) {
        state.raf = window.requestAnimationFrame(renderFrame);
      }
    }

    window.addEventListener('mousemove', function (event) {
      pointer.x = event.clientX;
      pointer.y = event.clientY;
    }, { passive: true });

    window.addEventListener('resize', buildField);
    buildField();
    renderFrame(window.performance.now());
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
    initNetworkField();
  });

}());
