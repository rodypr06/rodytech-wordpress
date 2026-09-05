<?php
/**
 * 404 template.
 */
get_header();
?>

<section class="error-404 editorial-hero editorial-hero-archive">
  <div class="editorial-hero-copy">
    <span class="editorial-eyebrow">404</span>
    <p class="error-code" aria-hidden="true">404</p>
    <h1>This page is not in the archive.</h1>
    <p>The URL may have moved, or it never existed. Search the blog or visit the main RodyTech site.</p>
    <form role="search" method="get" class="archive-search-form" action="<?php echo esc_url(home_url('/')); ?>">
      <label class="screen-reader-text" for="404-search">Search articles</label>
      <input id="404-search" type="search" name="s" placeholder="Search articles" value="">
      <button type="submit">Search</button>
    </form>
    <div class="error-404-actions">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-home">Back to the blog</a>
      <a href="<?php echo esc_url(home_url('/articles')); ?>" class="editorial-btn editorial-btn-secondary">Browse archive</a>
      <a href="<?php echo esc_url(rodytech_marketing_url()); ?>" class="editorial-btn editorial-btn-secondary" target="_blank" rel="noopener noreferrer">Visit RodyTech</a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
