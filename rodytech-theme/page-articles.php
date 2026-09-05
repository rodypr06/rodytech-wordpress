<?php
/** Template Name: Articles Page */
get_header();
$paged = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
$archive_url = get_permalink();
$articles_query = new WP_Query(array('post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 12, 'paged' => $paged, 'orderby' => 'date', 'order' => 'DESC', 'ignore_sticky_posts' => true));
?>
<section class="editorial-hero editorial-hero-archive publication-archive-heading">
  <div><span class="editorial-eyebrow">RodyTech Journal</span><h1>All articles</h1>
    <p><?php echo esc_html(number_format_i18n($articles_query->found_posts)); ?> articles on AI, automation, and building useful software.</p>
  </div>
  <form role="search" method="get" class="archive-search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label class="screen-reader-text" for="archive-search">Search articles</label>
    <input id="archive-search" type="search" name="s" placeholder="Find an article…"><button type="submit">Search</button>
  </form>
</section>
<?php get_template_part('template-parts/topic-nav'); ?>
<section class="editorial-content-grid publication-feed">
  <div class="editorial-main-column">
    <div class="editorial-section-heading"><h2><?php echo $paged > 1 ? 'Older articles' : 'Latest articles'; ?></h2><span class="publication-order">Newest first</span></div>
    <div class="publication-list">
      <?php foreach ($articles_query->posts as $story) echo rodytech_render_publication_story($story->ID, 'list'); ?>
    </div>
    <?php if (!$articles_query->have_posts()) : ?><div class="no-posts"><h2>No articles yet</h2><p>New writing will appear here.</p></div><?php endif; ?>
    <?php if ($articles_query->max_num_pages > 1) : ?>
      <nav class="pagination editorial-pagination" aria-label="Article pages">
        <?php echo paginate_links(array('base' => trailingslashit($archive_url) . user_trailingslashit('page/%#%/', 'paged'), 'format' => '', 'current' => $paged, 'total' => $articles_query->max_num_pages, 'prev_text' => '← Newer', 'next_text' => 'Older →', 'mid_size' => 1)); ?>
      </nav>
    <?php endif; ?>
  </div>
  <?php get_template_part('template-parts/publication-sidebar'); ?>
</section>
<?php get_footer(); ?>
