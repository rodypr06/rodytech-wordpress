<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<!-- Newsletter Bar -->
<div class="newsletter-bar">
  <div class="newsletter-bar-inner">
    <span class="newsletter-label">Get new articles in your inbox</span>
    <form class="newsletter-form" method="post" action="">
      <?php if (isset($_GET['subscribed'])) : ?>
        <span class="newsletter-success">✓ You're subscribed!</span>
      <?php else : ?>
        <input type="email" name="newsletter_email" placeholder="your@email.com" required class="newsletter-input">
        <button type="submit" class="newsletter-btn">Subscribe</button>
      <?php endif; ?>
    </form>
  </div>
</div>

<header class="site-header" id="site-header">
  <div class="header-inner">
    <?php
      $home_active = (is_home() || is_front_page()) ? 'nav-active' : '';
      $articles_active = (is_page('articles') || is_single() || is_archive() || is_search()) ? 'nav-active' : '';
      $about_active = is_page('about') ? 'nav-active' : '';
    ?>
    <div class="site-branding">
      <a href="<?php echo home_url(); ?>" class="site-logo">
        <span class="logo-text"><span class="rody">Rody</span><span class="tech">Tech</span></span>
        <span class="logo-sep"></span>
        <span class="logo-tagline">Blog</span>
      </a>
    </div>
    <nav class="main-nav">
      <a href="<?php echo home_url(); ?>" class="<?php echo esc_attr($home_active); ?>">Home</a>
      <a href="<?php echo home_url('/articles'); ?>" class="<?php echo esc_attr($articles_active); ?>">Articles</a>
      <a href="<?php echo home_url('/about'); ?>" class="<?php echo esc_attr($about_active); ?>">About</a>
      <a href="https://rodytech.net" target="_blank" class="nav-cta">rodytech.net ↗</a>
    </nav>
  </div>
</header>

<main class="main-content">
