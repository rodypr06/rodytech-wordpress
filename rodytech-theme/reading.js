(function () {
  'use strict';
  document.addEventListener('DOMContentLoaded', function () {
    var body = document.querySelector('.article-body');
    var rail = document.querySelector('.article-reading-rail');
    var toc = document.querySelector('.article-toc');
    var wide = matchMedia('(min-width: 1100px)');
    if (body && rail && toc) {
      function placeContents() {
        if (wide.matches && !toc.hidden) { rail.appendChild(toc); toc.open = true; }
        else { body.insertBefore(toc, body.firstChild); toc.open = false; }
      }
      // The existing contents builder runs before this script's listener.
      placeContents();
      wide.addEventListener('change', placeContents);
    }
    document.querySelectorAll('.article-content pre').forEach(function (pre) {
      var code = pre.querySelector('code') || pre;
      var language = (code.className || '').match(/(?:language|lang)-([\w+#-]+)/);
      var toolbar = document.createElement('div');
      toolbar.className = 'code-toolbar';
      var label = document.createElement('span');
      label.textContent = language ? language[1].toUpperCase() : 'CODE';
      var button = document.createElement('button');
      button.type = 'button'; button.className = 'code-copy'; button.textContent = 'Copy code';
      var status = document.createElement('p');
      status.className = 'code-copy-status'; status.setAttribute('role', 'status');
      toolbar.append(label, button);
      pre.before(toolbar); pre.after(status);
      button.addEventListener('click', async function () {
        try {
          if (!navigator.clipboard) throw new Error('Clipboard unavailable');
          await navigator.clipboard.writeText(code.textContent);
          status.textContent = 'Copied to clipboard.';
        } catch (e) {
          status.textContent = 'Could not access the clipboard. Select the code and copy it manually.';
        }
      });
    });
  });
}());
