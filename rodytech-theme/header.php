<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
  <!-- Open Graph / SEO metadata is owned by Yoast SEO on the live server. This theme does not duplicate Yoast output. -->
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main-content">Skip to content</a>

<canvas id="network" aria-hidden="true"></canvas>

<header class="site-header" id="site-header">
  <div class="header-inner">
    <?php
      $primary_links = rodytech_get_menu_links('primary', array(
        array(
          'label'  => 'Home',
          'url'    => home_url('/'),
          'class'  => (is_home() || is_front_page()) ? 'nav-active' : '',
          'target' => '',
          'rel'    => '',
        ),
        array(
          'label'  => 'Articles',
          'url'    => home_url('/articles'),
          'class'  => (is_page('articles') || is_single() || is_archive() || is_search()) ? 'nav-active' : '',
          'target' => '',
          'rel'    => '',
        ),
        array(
          'label'  => 'About',
          'url'    => home_url('/about'),
          'class'  => is_page('about') ? 'nav-active' : '',
          'target' => '',
          'rel'    => '',
        ),
      ));
      $lockup_src = get_template_directory_uri() . '/assets/rodytech-lockup.svg';
    ?>
    <div class="site-branding">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo" aria-label="RodyTech Blog home">
        <img src="<?php echo esc_url($lockup_src); ?>" alt="RodyTech" class="site-logo-mark" width="145" height="90">
        <span class="logo-sep"></span>
        <span class="logo-tagline">Blog</span>
      </a>
    </div>
    <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="primary-navigation">
      <span class="screen-reader-text">Toggle navigation</span>
      <span class="nav-toggle-lines" aria-hidden="true"><span></span><span></span><span></span></span>
    </button>
    <nav class="main-nav" id="primary-navigation" aria-label="Primary">
      <?php foreach ($primary_links as $link) : ?>
        <a
          href="<?php echo esc_url($link['url']); ?>"
          class="<?php echo esc_attr($link['class']); ?>"
          <?php if (!empty($link['target'])) : ?>target="<?php echo esc_attr($link['target']); ?>"<?php endif; ?>
          <?php if (!empty($link['rel'])) : ?>rel="<?php echo esc_attr($link['rel']); ?>"<?php endif; ?>
        >
          <?php echo esc_html($link['label']); ?>
        </a>
      <?php endforeach; ?>
      <form class="nav-search" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
        <label class="screen-reader-text" for="header-search-field">Search articles</label>
        <input id="header-search-field" type="search" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="Search articles">
        <button type="submit">Search</button>
      </form>
      <a href="<?php echo esc_url(rodytech_marketing_url()); ?>" target="_blank" rel="noopener noreferrer" class="nav-cta">Visit RodyTech</a>
    </nav>
  </div>
</header>

<noscript><style>@media (max-width:768px){.nav-toggle{display:none!important}.main-nav{display:flex!important;position:static!important;flex-wrap:wrap!important}}</style></noscript>

<main class="main-content" id="main-content">
