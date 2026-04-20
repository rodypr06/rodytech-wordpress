<?php get_header(); ?>

<?php
$paged = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
?>

<?php if (is_home() || is_front_page()) : ?>
  <?php
    $stats = rodytech_get_blog_stats();
    $top_categories = rodytech_get_editorial_categories(5);
    $featured_count = min(3, (int) $stats['published_posts']);
    $featured_posts = ($paged === 1) ? get_posts(array(
      'post_type'           => 'post',
      'post_status'         => 'publish',
      'numberposts'         => $featured_count,
      'orderby'             => 'date',
      'order'               => 'DESC',
      'ignore_sticky_posts' => true,
    )) : array();
    $featured_ids = wp_list_pluck($featured_posts, 'ID');

    $river_per_page = 6;
    $river_offset = $featured_count + (($paged - 1) * $river_per_page);
    $river_query = new WP_Query(array(
      'post_type'           => 'post',
      'post_status'         => 'publish',
      'posts_per_page'      => $river_per_page,
      'offset'              => $river_offset,
      'orderby'             => 'date',
      'order'               => 'DESC',
      'ignore_sticky_posts' => true,
    ));

    $total_posts = (int) $stats['published_posts'];
    $remaining_posts = max(0, $total_posts - $featured_count);
    $total_pages = max(1, (int) ceil($remaining_posts / $river_per_page));
    $collection_categories = rodytech_get_editorial_categories(3);
  ?>

  <section class="editorial-hero">
    <div class="editorial-hero-copy">
      <span class="editorial-eyebrow">RodyTech Journal</span>
      <h1><span class="editorial-gradient">Technical writing</span> on AI, infrastructure, and software systems.</h1>
      <p>Clear technical writing for builders and operators working across automation, cloud platforms, developer tools, and the systems that keep production moving.</p>
      <div class="editorial-hero-actions">
        <a href="#latest-stories" class="editorial-btn editorial-btn-primary">Explore latest stories</a>
        <a href="<?php echo esc_url(home_url('/articles')); ?>" class="editorial-btn editorial-btn-secondary">Browse archive</a>
      </div>
      <?php if (!empty($top_categories)) : ?>
        <div class="editorial-topic-row" aria-label="Top categories">
          <?php foreach ($top_categories as $category) : ?>
            <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="editorial-topic-pill">
              <span><?php echo esc_html($category->name); ?></span>
              <strong><?php echo esc_html($category->count); ?></strong>
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <aside class="editorial-hero-sidebar">
      <div class="editorial-note-card">
        <h2>Archive snapshot</h2>
        <p>Fresh writing centered on infrastructure, applied AI, and the build systems behind modern internal products.</p>
        <div class="editorial-note-stats">
          <div>
            <span>Published</span>
            <strong><?php echo esc_html($stats['published_posts']); ?></strong>
          </div>
          <div>
            <span>Fresh this month</span>
            <strong><?php echo esc_html($stats['fresh_posts']); ?></strong>
          </div>
        </div>
      </div>

      <div class="editorial-note-card">
        <h2>Leading topic</h2>
        <?php if (!empty($stats['top_category'])) : ?>
          <p><?php echo esc_html($stats['top_category']->name); ?> currently anchors the archive and sets the pace for the most active writing lane.</p>
          <a href="<?php echo esc_url(get_category_link($stats['top_category']->term_id)); ?>" class="inline-action-link">Open category</a>
        <?php else : ?>
          <p>The archive is ready for its first category cluster.</p>
        <?php endif; ?>
      </div>
    </aside>
  </section>

  <?php if (!empty($featured_posts)) : ?>
    <section class="editorial-feature-grid">
      <div class="editorial-feature-main">
        <?php echo rodytech_render_story_card($featured_posts[0]->ID, 'featured'); ?>
      </div>

      <div class="editorial-feature-stack">
        <?php foreach (array_slice($featured_posts, 1) as $featured_post) : ?>
          <?php echo rodytech_render_story_card($featured_post->ID, 'compact'); ?>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>

  <section class="editorial-content-grid" id="latest-stories">
    <div class="editorial-main-column">
      <div class="editorial-section-heading">
        <div>
          <span class="section-label">Recent stories</span>
          <h2><?php echo ($paged > 1) ? 'More from the archive' : 'New writing from the main feed'; ?></h2>
        </div>
        <a href="<?php echo esc_url(home_url('/articles')); ?>" class="inline-action-link">View all articles</a>
      </div>

      <?php if ($river_query->have_posts()) : ?>
        <div class="story-grid">
          <?php while ($river_query->have_posts()) : $river_query->the_post(); ?>
            <?php echo rodytech_render_story_card(get_the_ID(), 'standard'); ?>
          <?php endwhile; ?>
        </div>

        <?php if ($total_pages > 1) : ?>
          <div class="pagination editorial-pagination">
            <?php
              echo paginate_links(array(
                'base'      => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                'format'    => '?paged=%#%',
                'current'   => $paged,
                'total'     => $total_pages,
                'prev_text' => '← Newer',
                'next_text' => 'Older →',
                'mid_size'  => 2,
              ));
            ?>
          </div>
        <?php endif; ?>
      <?php else : ?>
        <div class="no-posts">
          <h2>No articles yet</h2>
          <p>Check back soon for new content.</p>
        </div>
      <?php endif; wp_reset_postdata(); ?>
    </div>

    <aside class="editorial-sidebar">
      <div class="sidebar-card">
        <span class="sidebar-card-label">About this blog</span>
        <h3>Built around practical technical systems.</h3>
        <p>RodyTech covers applied AI, cloud infrastructure, and software delivery with a bias toward concrete implementation details.</p>
      </div>

      <?php if (!empty($top_categories)) : ?>
        <div class="sidebar-card">
          <span class="sidebar-card-label">Top categories</span>
          <ul class="sidebar-category-list">
            <?php foreach ($top_categories as $category) : ?>
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

      <div class="sidebar-card sidebar-card-accent">
        <span class="sidebar-card-label">Stay updated</span>
        <h3>Subscribe from the header or footer.</h3>
        <p>The newsletter CTA stays visible across the site, so the homepage can prioritize reading flow instead of repeating the same form block.</p>
      </div>
    </aside>
  </section>

  <?php if (!empty($collection_categories)) : ?>
    <section class="editorial-collections">
      <div class="editorial-section-heading">
        <div>
          <span class="section-label">Collections</span>
          <h2>Category-led entry points into the archive</h2>
        </div>
      </div>

      <div class="collection-grid">
        <?php foreach ($collection_categories as $category) : ?>
          <?php
            $collection_posts = get_posts(array(
              'post_type'           => 'post',
              'post_status'         => 'publish',
              'numberposts'         => 2,
              'orderby'             => 'date',
              'order'               => 'DESC',
              'ignore_sticky_posts' => true,
              'category'            => $category->term_id,
            ));
          ?>
          <article class="collection-card">
            <div class="collection-card-head">
              <span class="collection-dot"></span>
              <span class="collection-count"><?php echo esc_html($category->count); ?> posts</span>
            </div>
            <h3><?php echo esc_html($category->name); ?></h3>
            <p><?php echo esc_html(rodytech_get_category_summary($category)); ?></p>
            <?php if (!empty($collection_posts)) : ?>
              <ul class="collection-post-list">
                <?php foreach ($collection_posts as $collection_post) : ?>
                  <li>
                    <a href="<?php echo esc_url(get_permalink($collection_post->ID)); ?>"><?php echo esc_html(get_the_title($collection_post->ID)); ?></a>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>
            <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="collection-link">Explore collection</a>
          </article>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>

<?php else : ?>
  <?php
    global $wp_query;
    $archive_eyebrow = 'Archive';

    if (is_category()) {
      $queried_category = get_queried_object();
      $archive_eyebrow = 'Category';
      $archive_title = single_cat_title('', false);
      $archive_description = trim(wp_strip_all_tags(get_the_archive_description()));
      if ($archive_description === '' && $queried_category instanceof WP_Term) {
        $archive_description = rodytech_get_category_summary($queried_category);
      }
    } elseif (is_tag()) {
      $tag_name = single_tag_title('', false);
      $archive_eyebrow = 'Tag';
      $archive_title = $tag_name;
      $tag_count = isset($wp_query->found_posts) ? (int) $wp_query->found_posts : 0;
      $archive_description = sprintf(
        '%s article%s filed under this tag across the archive.',
        number_format_i18n($tag_count),
        $tag_count === 1 ? '' : 's'
      );
    } elseif (is_author()) {
      $author_object = get_queried_object();
      $archive_eyebrow = 'Author';
      $archive_title = get_the_author();
      $archive_description = $author_object && !empty($author_object->description)
        ? wp_strip_all_tags($author_object->description)
        : 'Writing collected by author across AI, infrastructure, and software systems.';
    } elseif (is_search()) {
      $query = get_search_query();
      $result_count = isset($wp_query->found_posts) ? (int) $wp_query->found_posts : 0;
      $archive_eyebrow = 'Search results';
      $archive_title = sprintf('Results for "%s"', $query);
      $archive_description = sprintf(
        '%s article%s matched your search.',
        number_format_i18n($result_count),
        $result_count === 1 ? '' : 's'
      );
    } elseif (is_day()) {
      $archive_eyebrow = 'Daily archive';
      $archive_title = get_the_date('F j, Y');
      $archive_description = 'Stories published on this day.';
    } elseif (is_month()) {
      $archive_eyebrow = 'Monthly archive';
      $archive_title = get_the_date('F Y');
      $archive_description = 'Stories published during this month.';
    } elseif (is_year()) {
      $archive_eyebrow = 'Yearly archive';
      $archive_title = get_the_date('Y');
      $archive_description = 'Stories published during this year.';
    } else {
      $archive_title = wp_strip_all_tags(get_the_archive_title());
      $archive_description = wp_strip_all_tags(get_the_archive_description());
      if ($archive_description === '') {
        $archive_description = 'Browse the archive through the same editorial layout used on the homepage.';
      }
    }

    $sidebar_categories = rodytech_get_editorial_categories(6);
  ?>

  <section class="editorial-hero editorial-hero-archive">
    <div class="editorial-hero-copy">
      <span class="editorial-eyebrow"><?php echo esc_html($archive_eyebrow); ?></span>
      <h1><?php echo esc_html($archive_title); ?></h1>
      <?php if ($archive_description) : ?>
        <p><?php echo esc_html($archive_description); ?></p>
      <?php else : ?>
        <p>Browse the archive through the same editorial layout used on the homepage.</p>
      <?php endif; ?>
    </div>
  </section>

  <section class="editorial-content-grid editorial-content-grid-archive">
    <div class="editorial-main-column">
      <?php if (have_posts()) : ?>
        <div class="story-grid">
          <?php while (have_posts()) : the_post(); ?>
            <?php echo rodytech_render_story_card(get_the_ID(), 'standard'); ?>
          <?php endwhile; ?>
        </div>

        <div class="pagination editorial-pagination">
          <?php
            echo paginate_links(array(
              'prev_text' => '← Newer',
              'next_text' => 'Older →',
              'mid_size'  => 2,
            ));
          ?>
        </div>
      <?php else : ?>
        <div class="no-posts">
          <h2>No matching articles</h2>
          <p>Try a different query or browse the main archive.</p>
        </div>
      <?php endif; ?>
    </div>

    <aside class="editorial-sidebar">
      <?php if (!empty($sidebar_categories)) : ?>
        <div class="sidebar-card">
          <span class="sidebar-card-label">Popular paths</span>
          <ul class="sidebar-category-list">
            <?php foreach ($sidebar_categories as $category) : ?>
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
<?php endif; ?>

<?php get_footer(); ?>
