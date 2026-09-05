<?php get_header(); ?>
<?php if (is_home() || is_front_page()) :
  global $wp_query;
  $paged = max(1, (int) get_query_var('paged'));
  $stories = $wp_query->posts;
  $featured = $paged === 1 ? array_slice($stories, 0, 3) : array();
  $latest = $paged === 1 ? array_slice($stories, 3) : $stories;
?>
<section class="editorial-hero publication-masthead">
  <div><span class="editorial-eyebrow">Ideas, experiments &amp; practical guides</span>
    <h1>RodyTech <em>Journal</em></h1>
    <p>AI, automation, and software. What’s useful, what works, and how to build it.</p>
  </div>
  <a class="publication-rss" href="<?php echo esc_url(get_feed_link()); ?>">Follow via RSS <span aria-hidden="true">↗</span></a>
</section>
<?php get_template_part('template-parts/topic-nav'); ?>
<?php if ($featured) : ?>
  <section class="publication-front" aria-label="Latest stories">
    <?php echo rodytech_render_publication_story($featured[0]->ID, 'lead'); ?>
    <div class="publication-briefs"><span class="section-label">More from the journal</span>
      <?php foreach (array_slice($featured, 1) as $story) echo rodytech_render_publication_story($story->ID, 'brief'); ?>
    </div>
  </section>
<?php endif; ?>
<section class="editorial-content-grid publication-feed" id="latest-stories">
  <div class="editorial-main-column">
    <div class="editorial-section-heading"><h2><?php echo $paged > 1 ? 'Older articles' : 'Latest articles'; ?></h2><span class="publication-order">Newest first<?php if ($paged > 1) echo ' · Page ' . esc_html($paged); ?></span></div>
    <div class="publication-list">
      <?php foreach ($latest as $story) echo rodytech_render_publication_story($story->ID, 'list'); ?>
    </div>
    <?php if (!$stories) : ?><div class="no-posts"><h2>No articles yet</h2><p>New writing will appear here.</p></div><?php endif; ?>
    <?php if ($wp_query->max_num_pages > 1) : ?>
      <nav class="pagination editorial-pagination" aria-label="Article pages">
        <?php echo paginate_links(array('current' => $paged, 'total' => $wp_query->max_num_pages, 'prev_text' => '← Newer', 'next_text' => 'Older →', 'mid_size' => 1)); ?>
      </nav>
    <?php endif; ?>
  </div>
  <?php get_template_part('template-parts/publication-sidebar'); ?>
</section>
<?php else : ?>
  <?php get_template_part('template-parts/editorial-archive'); ?>
<?php endif; ?>
<?php get_footer(); ?>
