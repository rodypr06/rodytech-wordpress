<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<!-- Newsletter Subscription Bar -->
<div class="newsletter-bar">
  <div class="newsletter-bar-inner">
    <span class="newsletter-label">Subscribe to notifications of new posts:</span>
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

<header class="site-header">
  <div class="header-inner">
    <div class="site-branding">
      <a href="<?php echo home_url(); ?>" class="site-logo">
        <span class="logo-text"><span style="color:#ffffff">Rody</span><span style="color:#f97316">Tech</span></span>
        <span class="logo-tagline">Blog</span>
      </a>
    </div>
    <nav class="main-nav">
      <a href="<?php echo home_url(); ?>">Home</a>
      <a href="<?php echo home_url('/articles'); ?>">Articles</a>
      <a href="<?php echo home_url('/about'); ?>">About</a>
      <a href="https://rodytech.net" target="_blank">Main Site</a>
    </nav>
  </div>
</header>

<main class="main-content">
