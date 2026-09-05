/* Resolve appearance before styles paint; storage is optional. */
(function () {
  'use strict';
  var key = 'rodytech-appearance';
  var system = window.matchMedia('(prefers-color-scheme: dark)');
  var preference = 'system';
  function valid(value) { return ['light', 'dark', 'system'].includes(value); }
  try { var saved = localStorage.getItem(key); if (valid(saved)) preference = saved; } catch (e) {}
  function apply() {
    document.documentElement.dataset.theme = preference === 'system' ? (system.matches ? 'dark' : 'light') : preference;
    var select = document.querySelector('.appearance-control select');
    if (select) select.value = preference;
  }
  apply();
  system.addEventListener('change', apply);
  window.addEventListener('storage', function (event) {
    if (event.key !== key && event.key !== null) return;
    preference = valid(event.newValue) ? event.newValue : 'system';
    apply();
  });
  document.addEventListener('DOMContentLoaded', function () {
    var control = document.querySelector('.appearance-control');
    if (!control) return;
    apply();
    control.hidden = false;
    control.querySelector('select').addEventListener('change', function (event) {
      preference = valid(event.target.value) ? event.target.value : 'system';
      try { localStorage.setItem(key, preference); } catch (e) {}
      apply();
    });
  });
}());
