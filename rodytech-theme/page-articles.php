<?php
/**
 * Template Name: Articles Page
 */
get_header();

$paged = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
$stats = rodytech_get_blog_stats();
$categories = get_categories(array('hide_empty' => true));
$articles_query = new WP_Query(array(
  'post_type'           => 'post',
  'post_status'         => 'publish',
  'posts_per_page'      => 12,
  'paged'               => $paged,
  'orderby'             => 'date',
  'order'               => 'DESC',
  'ignore_sticky_posts' => true,
));
?>

<section class="editorial-hero editorial-hero-archive">
  <div class="editorial-hero-copy">
    <span class="editorial-eyebrow">Editorial archive</span>
    <h1>All <span>Articles</span></h1>
    <p>Browse every published article through a cleaner archive view built around category discovery and readable scanning.</p>
  </div>

  <aside class="editorial-hero-sidebar">
    <div class="editorial-note-card">
      <h2>Archive scale</h2>
      <div class="editorial-note-stats">
        <div>
          <span>Published</span>
          <strong><?php echo esc_html($stats['published_posts']); ?></strong>
        </div>
        <div>
          <span>Categories</span>
          <strong><?php echo esc_html($stats['category_count']); ?></strong>
        </div>
      </div>
    </div>
  </aside>
</section>

<?php if (!empty($categories)) : ?>
  <div class="editorial-topic-row editorial-topic-row-archive" aria-label="Archive categories">
    <a href="<?php echo esc_url(get_permalink()); ?>" class="editorial-topic-pill editorial-topic-pill-static">
      <span>All</span>
      <strong><?php echo esc_html($stats['published_posts']); ?></strong>
    </a>
    <?php foreach ($categories as $category) : ?>
      <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="editorial-topic-pill">
        <span><?php echo esc_html($category->name); ?></span>
        <strong><?php echo esc_html($category->count); ?></strong>
      </a>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<section class="editorial-content-grid editorial-content-grid-archive">
  <div class="editorial-main-column">
    <div class="editorial-section-heading">
      <div>
        <span class="section-label">Archive list</span>
        <h2>Every article, in chronological order</h2>
      </div>
    </div>

    <?php if ($articles_query->have_posts()) : ?>
      <div class="story-grid">
        <?php while ($articles_query->have_posts()) : $articles_query->the_post(); ?>
          <?php echo rodytech_render_story_card(get_the_ID(), 'standard'); ?>
        <?php endwhile; ?>
      </div>

      <div class="pagination editorial-pagination">
        <?php
          echo paginate_links(array(
            'base'      => trailingslashit(get_permalink()) . user_trailingslashit('page/%#%/', 'paged'),
            'format'    => '',
            'current'   => $paged,
            'total'     => $articles_query->max_num_pages,
            'prev_text' => '← Newer',
            'next_text' => 'Older →',
            'mid_size'  => 2,
          ));
        ?>
      </div>
    <?php else : ?>
      <div class="no-posts">
        <h2>No articles yet</h2>
        <p>Check back soon — new writing lands regularly.</p>
      </div>
    <?php endif; wp_reset_postdata(); ?>
  </div>

  <aside class="editorial-sidebar">
    <div class="sidebar-card">
      <span class="sidebar-card-label">How to use this archive</span>
      <h3>Start with a category, then follow the article trail.</h3>
      <p>The archive is organized for scanning: jump into a category or work top-down from the latest posts.</p>
    </div>

    <?php if (!empty($categories)) : ?>
      <div class="sidebar-card">
        <span class="sidebar-card-label">Category map</span>
        <ul class="sidebar-category-list">
          <?php foreach (array_slice($categories, 0, 8) as $category) : ?>
            <li>
              <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>">
                <span><?php echo esc_html($category->name); ?></span>
                <strong><?php echo esc_html($category->count); ?></strong>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
  </aside>
</section>

<?php get_footer(); ?>
