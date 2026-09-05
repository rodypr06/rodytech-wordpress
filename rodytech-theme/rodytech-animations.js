/**
 * RodyTech Blog — Animations & micro-interactions
 */
(function () {
  'use strict';

  var reducedMotionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
  function prefersReducedMotion() {
    return reducedMotionQuery.matches;
  }

  /* ── Scroll-triggered fade-up ── */
  var observer = ('IntersectionObserver' in window && !prefersReducedMotion())
    ? new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.07, rootMargin: '0px 0px -36px 0px' })
    : null;

  function initAnimations() {
    var targets = document.querySelectorAll(
      '.publication-story-list, .publication-story-brief, .article-card:not(.featured), .story-card:not(.story-card-featured), .collection-card, .editorial-note-card, .sidebar-card, .related-card, .author-box, .author-profile-header, #comments.comments-area, .social-share'
    );

    if (!observer) {
      targets.forEach(function (el) {
        el.classList.add('is-visible');
        el.style.opacity = '';
        el.style.transform = '';
        el.style.transition = '';
      });
      return;
    }

    targets.forEach(function (el, i) {
      el.style.opacity = '0';
      el.style.transform = 'translateY(32px)';
      el.style.transition =
        'opacity 0.55s cubic-bezier(0.22,1,0.36,1) ' + ((i % 4) * 0.065) + 's, ' +
        'transform 0.55s cubic-bezier(0.22,1,0.36,1) ' + ((i % 4) * 0.065) + 's';
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

  /* ── Accessible mobile navigation ── */
  function initMobileNav() {
    var toggle = document.querySelector('.nav-toggle');
    var nav = document.getElementById('primary-navigation');
    if (!toggle || !nav) return;

    document.documentElement.classList.add('nav-enhanced');

    function closeNav(returnFocus) {
      toggle.setAttribute('aria-expanded', 'false');
      nav.classList.remove('nav-open');
      document.body.classList.remove('mobile-nav-open');
      if (returnFocus) toggle.focus();
    }

    function openNav() {
      toggle.setAttribute('aria-expanded', 'true');
      nav.classList.add('nav-open');
      document.body.classList.add('mobile-nav-open');
      var firstTarget = nav.querySelector('a, input, button');
      if (firstTarget) firstTarget.focus();
    }

    toggle.addEventListener('click', function () {
      if (toggle.getAttribute('aria-expanded') === 'true') {
        closeNav(false);
      } else {
        openNav();
      }
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
        closeNav(true);
      }
    });

    document.addEventListener('click', function (event) {
      if (toggle.getAttribute('aria-expanded') === 'true' && !nav.contains(event.target) && !toggle.contains(event.target)) {
        closeNav(false);
      }
    });

    nav.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () { closeNav(false); });
    });

    window.addEventListener('resize', function () {
      if (window.innerWidth > 768) closeNav(false);
    });
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

  /* Local light and notebook depth: event-driven, with no ambient render loop. */
  function initReactiveLight() {
    var fine = window.matchMedia('(hover: hover) and (pointer: fine)');
    var targets = document.querySelectorAll('.story-card, .collection-card, .editorial-hero-copy, .journal-object, .publication-story, .publication-masthead');
    targets.forEach(function (element) {
      var frame = 0;
      function reset() {
        cancelAnimationFrame(frame);
        frame = 0;
        element.style.removeProperty('--pointer-x');
        element.style.removeProperty('--pointer-y');
        element.style.removeProperty('--light-x');
        element.style.removeProperty('--light-y');
        element.style.removeProperty('--depth-x');
        element.style.removeProperty('--depth-y');
      }
      element.addEventListener('pointermove', function (event) {
        if (prefersReducedMotion() || !fine.matches || element.classList.contains('journal-paused')) return;
        var x = event.clientX;
        var y = event.clientY;
        cancelAnimationFrame(frame);
        frame = requestAnimationFrame(function () {
          var bounds = element.getBoundingClientRect();
          var px = Math.max(0, Math.min(1, (x - bounds.left) / bounds.width));
          var py = Math.max(0, Math.min(1, (y - bounds.top) / bounds.height));
          element.style.setProperty('--light-x', (px * 100) + '%');
          element.style.setProperty('--light-y', (py * 100) + '%');
          var masthead = element.classList.contains('publication-masthead');
          element.style.setProperty('--depth-x', ((0.5 - py) * (masthead ? 30 : 10)) + 'deg');
          element.style.setProperty('--depth-y', ((px - 0.5) * (masthead ? 48 : 14)) + 'deg');
          element.style.setProperty('--pointer-x', ((px - 0.5) * (masthead ? 28 : 10)) + 'px');
          element.style.setProperty('--pointer-y', ((py - 0.5) * (masthead ? 22 : 7)) + 'px');
          frame = 0;
        });
      }, { passive: true });
      element.addEventListener('pointerleave', reset);
      reducedMotionQuery.addEventListener('change', reset);
      fine.addEventListener('change', reset);
      document.addEventListener('visibilitychange', function () { if (document.hidden) reset(); });
    });
    reducedMotionQuery.addEventListener('change', function () {
      if (!prefersReducedMotion()) return;
      if (observer) observer.disconnect();
      document.querySelectorAll('.publication-story, .story-card, .article-card, .collection-card, .editorial-note-card, .sidebar-card, .related-card, .author-box, .author-profile-header, #comments.comments-area, .social-share').forEach(function (element) {
        element.classList.add('is-visible');
        element.style.opacity = '';
        element.style.transform = '';
        element.style.transition = '';
      });
    });
  }

  function initReadingProgress() {
    var content = document.querySelector('.single-article .article-content');
    var bar = document.querySelector('.reading-progress span');
    if (!content || !bar) return;
    var frame = 0;
    function update() {
      var bounds = content.getBoundingClientRect();
      var available = Math.max(1, bounds.height - (window.innerHeight - 100));
      var progress = Math.max(0, Math.min(1, (100 - bounds.top) / available));
      bar.style.transform = 'scaleX(' + progress + ')';
      frame = 0;
    }
    function schedule() { if (!frame) frame = requestAnimationFrame(update); }
    window.addEventListener('scroll', schedule, { passive: true });
    window.addEventListener('resize', schedule, { passive: true });
    if ('ResizeObserver' in window) new ResizeObserver(schedule).observe(content);
    update();
  }

  /* Progressive in-page navigation; article content and existing anchors remain intact. */
  function initArticleContents() {
    var content = document.querySelector('.article-content');
    var toc = document.querySelector('.article-toc');
    if (!content || !toc) return;
    var headings = Array.from(content.querySelectorAll('h2')).filter(function (heading) { return heading.textContent.trim(); });
    if (headings.length < 3) return;
    var list = toc.querySelector('ol');
    headings.forEach(function (heading, index) {
      if (!heading.id) {
        var id = 'journal-section-' + (index + 1);
        while (document.getElementById(id)) id += '-section';
        heading.id = id;
      }
      var item = document.createElement('li');
      var link = document.createElement('a');
      link.href = '#' + encodeURIComponent(heading.id);
      link.textContent = heading.textContent.trim();
      item.appendChild(link);
      list.appendChild(item);
    });
    toc.hidden = false;
    toc.open = window.matchMedia('(min-width: 769px)').matches;
    var links = Array.from(list.querySelectorAll('a'));
    var frame = 0;
    function updateCurrentSection() {
      var current = 0;
      headings.forEach(function (heading, index) {
        if (heading.getBoundingClientRect().top <= 145) current = index;
      });
      links.forEach(function (link, index) {
        if (index === current) link.setAttribute('aria-current', 'location');
        else link.removeAttribute('aria-current');
      });
      frame = 0;
    }
    function schedule() { if (!frame) frame = requestAnimationFrame(updateCurrentSection); }
    window.addEventListener('scroll', schedule, { passive: true });
    window.addEventListener('resize', schedule, { passive: true });
    if ('ResizeObserver' in window) new ResizeObserver(schedule).observe(content);
    updateCurrentSection();

  }


  function initTopicIndicator() {
    var nav = document.querySelector('.publication-topics');
    if (!nav) return;
    var links = Array.from(nav.querySelectorAll('a'));
    if (!links.length) return;
    var marker = document.createElement('span');
    marker.className = 'topic-indicator';
    marker.setAttribute('aria-hidden', 'true');
    nav.appendChild(marker);
    var current = nav.querySelector('[aria-current="page"]') || links[0];
    var active = current;
    function move(link) {
      active = link;
      var bounds = link.getBoundingClientRect();
      var parent = nav.getBoundingClientRect();
      marker.style.width = bounds.width + 'px';
      marker.style.transform = 'translateX(' + (bounds.left - parent.left + nav.scrollLeft) + 'px)';
    }
    function reset() {
      move(links.includes(document.activeElement) ? document.activeElement : current);
    }
    links.forEach(function (link) {
      link.addEventListener('pointerenter', function (event) { if (event.pointerType !== 'touch') move(link); });
      link.addEventListener('focus', function () { move(link); });
    });
    nav.addEventListener('pointerleave', reset);
    nav.addEventListener('focusout', function () { requestAnimationFrame(reset); });
    if ('ResizeObserver' in window) new ResizeObserver(function () { move(active); }).observe(nav);
    window.addEventListener('resize', function () { move(active); }, { passive: true });
    if (document.fonts) document.fonts.ready.then(function () { move(active); });
    move(current);
  }

  function initJournalMasthead() {
    var masthead = document.querySelector('.publication-masthead');
    if (!masthead) return;
    var replay = masthead.querySelector('.journal-replay');
    var pause = masthead.querySelector('.journal-pause');
    var inView = true;
    function visibilityChanged() {
      masthead.classList.toggle('journal-offscreen', !inView || document.hidden);
    }
    function setPaused(value) {
      masthead.classList.toggle('journal-paused', value);
      if (value) masthead.classList.remove('journal-enter');
      pause.setAttribute('aria-pressed', String(value));
      pause.textContent = value ? 'Resume motion' : 'Pause motion';
    }
    function play() {
      masthead.classList.remove('journal-enter');
      if (prefersReducedMotion()) return;
      setPaused(false);
      // Restart the finite entrance without an idle animation loop.
      void masthead.offsetWidth;
      masthead.classList.add('journal-enter');
    }
    function preferenceChanged() {
      replay.hidden = prefersReducedMotion();
      pause.hidden = prefersReducedMotion();
      if (prefersReducedMotion()) masthead.classList.remove('journal-enter');
    }
    replay.addEventListener('click', play);
    pause.addEventListener('click', function () { setPaused(!masthead.classList.contains('journal-paused')); });
    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function (entries) {
        inView = entries[0].isIntersecting;
        visibilityChanged();
      }).observe(masthead);
    }
    document.addEventListener('visibilitychange', visibilityChanged);
    masthead.classList.add('journal-motion');
    reducedMotionQuery.addEventListener('change', preferenceChanged);
    preferenceChanged();
    play();
  }

  /* ── Boot ── */
  document.addEventListener('DOMContentLoaded', function () {
    injectStyles();
    initAnimations();
    initHeaderScroll();
    initMobileNav();
    initActiveNav();
    initImageShimmer();
    initReactiveLight();
    initReadingProgress();
    initArticleContents();
    initTopicIndicator();
    initJournalMasthead();
  });

}());
