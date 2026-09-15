<?php
// Run only in a disposable WordPress installation with the theme active.
if (!defined('ABSPATH') || !defined('RODYTECH_DISPOSABLE_TEST') || !RODYTECH_DISPOSABLE_TEST) {
    throw new Exception('A disposable WordPress test environment is required.');
}
function journal_assert($condition, $message) { if (!$condition) throw new Exception($message); }
set_theme_mod('rodytech_newsletter_url', 'https://newsletter.example/original');
set_theme_mod('rodytech_newsletter_verified', true);
journal_assert(rodytech_newsletter_ready(), 'Initial verified destination inactive');
set_theme_mod('unrelated_setting', 'preserve-me');
journal_assert(rodytech_newsletter_ready(), 'Unrelated save cleared verification');
set_theme_mod('rodytech_newsletter_url', 'https://newsletter.example/original');
journal_assert(rodytech_newsletter_ready(), 'Unchanged URL cleared verification');
set_theme_mod('rodytech_newsletter_url', 'https://newsletter.example/replacement');
journal_assert(!rodytech_newsletter_ready(), 'set_theme_mod retained old verification');
journal_assert(get_theme_mod('unrelated_setting') === 'preserve-me', 'Unrelated setting lost');
ob_start(); get_template_part('template-parts/newsletter', null, array('placement' => 'article')); $html = ob_get_clean();
journal_assert(strpos($html, 'Get the Journal by email') === false, 'Unverified replacement published');
journal_assert(strpos($html, '/resources/start-here.html') !== false, 'Resource fallback missing');
set_theme_mod('rodytech_newsletter_verified', true);
journal_assert(rodytech_newsletter_ready(), 'Explicit re-verification failed');
$mods = get_theme_mods();
$mods['rodytech_newsletter_url'] = 'https://newsletter.example/bulk-replacement';
$mods['rodytech_newsletter_verified'] = true;
update_option('theme_mods_' . get_stylesheet(), $mods);
journal_assert(!rodytech_newsletter_ready(), 'Bulk option save inherited verification');
set_theme_mod('rodytech_newsletter_verified', true);
set_theme_mod('rodytech_newsletter_url', '');
journal_assert(!get_theme_mod('rodytech_newsletter_verified'), 'URL removal retained verification');
set_theme_mod('rodytech_newsletter_url', 'https://newsletter.example/original');
journal_assert(!rodytech_newsletter_ready(), 'Restored URL inherited verification');
echo "newsletter_wordpress_ok\n";
