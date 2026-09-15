(() => {
  'use strict';
  const names = new Set(['newsletter_signup_open', 'reader_resource_open', 'rss_open']);
  const placements = new Set(['article', 'footer']);
  document.addEventListener('click', (event) => {
    const link = event.target.closest?.('a[data-journal-event]');
    if (!link || !names.has(link.dataset.journalEvent) || !placements.has(link.dataset.journalPlacement)) return;
    // A local hook, not an analytics collector. A consent-aware adapter may subscribe.
    // Never include email, destination URLs, query strings, referrers or free text.
    window.dispatchEvent(new CustomEvent('rodytech:reader-action', { detail: {
      event: link.dataset.journalEvent, placement: link.dataset.journalPlacement,
    } }));
  });
})();
