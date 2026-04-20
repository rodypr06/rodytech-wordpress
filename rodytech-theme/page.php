<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<article class="page-content">
  <header class="page-hero">
    <h1 class="page-title"><?php the_title(); ?></h1>
  </header>
  <div class="article-body">
    <div class="article-content">
      <?php the_content(); ?>
    </div>
  </div>
</article>

<?php endwhile; endif; ?>

<?php get_footer(); ?>
