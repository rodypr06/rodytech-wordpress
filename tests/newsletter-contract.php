<?php
// Run with PHP CLI. Exercise configuration and the actual template without external mail or WordPress writes.
function add_action($name, $callback) {}
function get_theme_mod($name, $fallback = '') { return $GLOBALS['mods'][$name] ?? $fallback; }
function esc_url_raw($url, $protocols = array()) { return filter_var($url, FILTER_VALIDATE_URL) ? $url : ''; }
function wp_parse_url($url) { return parse_url($url); }
function esc_url($url) { return htmlspecialchars($url, ENT_QUOTES); }
function esc_attr($value) { return htmlspecialchars($value, ENT_QUOTES); }
function home_url($path) { return 'https://journal.example' . $path; }
function get_feed_link() { return 'https://journal.example/feed/'; }
function get_template_directory_uri() { return 'https://journal.example/theme'; }
require __DIR__ . '/../rodytech-theme/journal-growth.php';
function check($condition, $message) { if (!$condition) throw new Exception($message); }
foreach (array('', 'http://example.com', 'javascript:alert(1)', '//example.com', 'https://user:pass@example.com', array('url')) as $bad) check(rodytech_newsletter_url($bad) === '', 'Unsafe URL accepted');
$GLOBALS['mods'] = array(); check(!rodytech_newsletter_ready(), 'Empty configuration activated');
$GLOBALS['mods'] = array('rodytech_newsletter_url' => 'https://newsletter.example/join');
check(!rodytech_newsletter_ready(), 'Unverified configuration activated');
$GLOBALS['mods']['rodytech_newsletter_verified'] = true;
check(rodytech_newsletter_ready(), 'Verified configuration inactive');
$args = array('placement' => 'article'); ob_start(); require __DIR__ . '/../rodytech-theme/template-parts/newsletter.php'; $html = ob_get_clean();
check(strpos($html, 'href="https://newsletter.example/join"') !== false, 'Provider URL missing');
check(strpos($html, 'Get the Journal by email') !== false, 'Active copy missing');
check(strpos($html, 'type="email"') === false, 'Theme unexpectedly collects email');
$GLOBALS['mods']['rodytech_newsletter_verified'] = false;
ob_start(); require __DIR__ . '/../rodytech-theme/template-parts/newsletter.php'; $html = ob_get_clean();
check(strpos($html, 'Get the Journal by email') === false, 'Inactive form makes signup promise');
check(strpos($html, '/resources/start-here.html') !== false, 'Resource fallback missing');
echo "newsletter_contract_ok\n";
