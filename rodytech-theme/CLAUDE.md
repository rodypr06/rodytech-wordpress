# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What This Is

A custom WordPress theme named **RodyTech Theme v5 - Dark Modern** for a blog at rodytech.net. It uses no build tools — all files are plain PHP, CSS, and vanilla JS deployed directly to WordPress.

## Installation / Deployment

This theme has no build step. To activate:
1. Copy or symlink the theme directory into your WordPress installation at `wp-content/themes/rodytech-theme/`
2. Activate via **WordPress Admin → Appearance → Themes**

All changes to `.php`, `.css`, or `.js` files take effect immediately after saving.

## File Structure

| File | Purpose |
|------|---------|
| `style.css` | All theme styles + theme metadata header (required by WordPress) |
| `functions.php` | Theme setup, asset enqueuing, helper functions |
| `header.php` | Newsletter bar, sticky nav, opens `<main>` |
| `footer.php` | Footer links/brand, closes `<main>`, `wp_footer()` |
| `index.php` | Homepage — 3-column article grid; first post gets `.featured` (full-width, 2-col layout) |
| `single.php` | Single post — hero image, article body, social share, author box, related posts, comments |
| `page-articles.php` | Custom page template (`Template Name: Articles Page`) — full archive with category filter tabs and pagination |
| `author.php` | Author archive — profile header + article grid |
| `comments.php` | Comment list + reply form; defines `rodytech_comment_callback()` |
| `rodytech-animations.js` | Scroll-triggered fade-in via IntersectionObserver, lazy-load blur shimmer, active nav highlight |

## Design System

All tokens are CSS custom properties defined in `:root` in `style.css`:

- `--bg` `#0a0a0a` — page background
- `--bg-card` `#0f172a` / `--bg-card-hover` `#1e293b` — card surfaces
- `--bg-surface` `#111827` — muted surface (footer, code blocks, etc.)
- `--accent` `#f97316` (orange) — primary accent; hover is `--accent-hover` `#fb923c`
- `--accent-dim` `rgba(249,115,22,0.15)` — badge backgrounds
- `--text` `#ffffff` / `--text-secondary` `#cbd5e1` / `--text-muted` `#94a3b8`
- `--border` `rgba(255,255,255,0.08)` / `--border-hover` `rgba(249,115,22,0.35)`
- `--radius` `12px`, `--ease` `cubic-bezier(0.22,1,0.36,1)` — shared animation curve
- Font: **Inter** (Google Fonts), weights 300–900

Breakpoints: `1024px` (2-col grid), `768px` (1-col, stacked nav), `480px` (newsletter stacked).

## Key Patterns

### Image Sizes
Three custom sizes registered in `functions.php`:
- `featured-large` — 1200×675 (used for hero and featured card)
- `featured-medium` — 800×450 (used for regular cards and related posts)
- `author-avatar` — 80×80

### Helper Functions (functions.php)
- `rodytech_reading_time($post_id)` — calculates reading time at 200 wpm
- `rodytech_comment_count()` — returns formatted comment count string
- `rodytech_social_share($platform, $url, $title)` — generates share URLs for `twitter`, `linkedin`, `facebook`

### Author Custom Meta Fields
Registered via `user_contactmethods` filter: `twitter`, `linkedin`, `github`, `position`. Used in `single.php` and `author.php`.

### Newsletter Subscription
Handled server-side in `functions.php` via `init` action hook — reads `$_POST['newsletter_email']`, sets a 1-year cookie, and redirects with `?subscribed=1`. **No external service integrated yet** — the cookie storage is a placeholder for Mailchimp/ConvertKit integration.

### Featured Post Logic
In both `index.php` and `page-articles.php`, `$post_count === 1` (and on page 1 for the articles template) triggers `class="article-card featured"`, which spans the full grid width with a side-by-side image+content layout via CSS Grid.

### WordPress Menus
Two nav menus registered: `primary` and `footer`. The `header.php` nav is currently hardcoded HTML — swap to `wp_nav_menu()` if dynamic menu management is needed.
