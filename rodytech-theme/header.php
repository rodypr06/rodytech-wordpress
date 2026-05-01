<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

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
    ?>
    <div class="site-branding">
      <a href="<?php echo home_url(); ?>" class="site-logo">
        <span class="logo-text"><span class="rody">Rody</span><span class="tech">Tech</span></span>
        <span class="logo-sep"></span>
        <span class="logo-tagline">Blog</span>
      </a>
    </div>
    <nav class="main-nav">
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
      <a href="https://rodytech.ai" target="_blank" rel="noopener noreferrer" class="nav-cta">rodytech.ai ↗</a>
    </nav>
  </div>
</header>

<main class="main-content">
