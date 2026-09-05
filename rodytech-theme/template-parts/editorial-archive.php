<?php
/**
 * Shared editorial archive / search / category feed.
 */
?>

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
      if ($tag_count === 0) {
        $archive_description = 'No published articles currently use this tag.';
      } else {
        $archive_description = sprintf(
          '%s article%s filed under this tag across the archive.',
          number_format_i18n($tag_count),
          $tag_count === 1 ? '' : 's'
        );
      }
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
        $archive_description = 'Articles on AI, automation, and software.';
      }
    }

  ?>

  <section class="editorial-hero editorial-hero-archive publication-archive-heading">
    <div class="editorial-hero-copy">
      <span class="editorial-eyebrow"><?php echo esc_html($archive_eyebrow); ?></span>
      <h1><?php echo esc_html($archive_title); ?></h1>
      <?php if ($archive_description) : ?>
        <p><?php echo esc_html($archive_description); ?></p>
      <?php else : ?>
        <p>Articles on AI, automation, and software.</p>
      <?php endif; ?>
      <?php if (is_search()) : ?>
        <form role="search" method="get" class="archive-search-form" action="<?php echo esc_url(home_url('/')); ?>">
          <label class="screen-reader-text" for="archive-search">Search articles</label>
          <input id="archive-search" type="search" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="Search articles">
          <button type="submit">Search</button>
        </form>
      <?php endif; ?>
    </div>
  </section>

  <?php get_template_part('template-parts/topic-nav'); ?>
  <section class="editorial-content-grid publication-feed">
    <div class="editorial-main-column">
      <?php if (have_posts()) : ?>
        <div class="publication-list">
          <?php while (have_posts()) : the_post(); ?>
            <?php echo rodytech_render_publication_story(get_the_ID(), 'list'); ?>
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
          <h2>
            <?php
              if (is_tag()) {
                echo 'No published articles use this tag yet';
              } elseif (is_category()) {
                echo 'No published articles are in this category yet';
              } elseif (is_search()) {
                echo 'No matching articles';
              } else {
                echo 'No published articles in this archive yet';
              }
            ?>
          </h2>
          <p>
            <?php
              if (is_tag()) {
                echo 'Use the broader category paths or the full archive to keep exploring.';
              } elseif (is_category()) {
                echo 'Browse the full archive while this category is still empty.';
              } elseif (is_search()) {
                echo 'Try a different query or browse the main archive.';
              } else {
                echo 'Browse the main archive for active writing lanes.';
              }
            ?>
          </p>
          <p class="no-posts-actions">
            <a href="<?php echo esc_url(home_url('/articles')); ?>" class="inline-action-link">Open the full archive</a>
          </p>
        </div>
      <?php endif; ?>
    </div>

    <?php get_template_part('template-parts/publication-sidebar'); ?>
  </section>
