(() => {
  const checks = [...document.querySelectorAll('input[type="checkbox"]')];
  const progress = document.querySelector('.progress');
  function update() { if (progress) progress.textContent = `${checks.filter(c => c.checked).length} of ${checks.length} questions reviewed. This is a planning aid, not a readiness certification.`; }
  checks.forEach(c => c.addEventListener('change', update));
  if (checks.length) update();
  document.querySelectorAll('[data-print]').forEach(b => { b.hidden = false; b.addEventListener('click', () => window.print()); });
  document.querySelectorAll('[data-copy]').forEach(b => {
    b.hidden = false;
    b.addEventListener('click', async () => {
      const target = document.getElementById(b.dataset.copy);
      const status = document.querySelector('.copy-status');
      if (!target || !status) return;
      try { await navigator.clipboard.writeText(target.textContent); status.textContent = 'Worksheet copied. Paste it into your own notes.'; }
      catch { status.textContent = 'Clipboard access is unavailable. Select the worksheet text below and copy it manually.'; }
    });
  });
})();
