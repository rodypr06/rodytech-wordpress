<?php
/**
 * Template Name: Articles Page
 * Template for displaying all articles with categories
 */
get_header(); ?>

<div class="homepage-content">

  <?php
    // Get all categories that have posts
    $categories = get_categories(array('hide_empty' => true));
  ?>

  <?php if (!empty($categories)) : ?>
    <div class="category-filter">
      <a href="<?php echo get_permalink(); ?>" class="cat-btn active">All</a>
      <?php foreach ($categories as $cat) : ?>
        <a href="<?php echo get_category_link($cat->term_id); ?>" class="cat-btn">
          <?php echo esc_html($cat->name); ?>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $articles_query = new WP_Query(array(
      'post_type'      => 'post',
      'post_status'    => 'publish',
      'posts_per_page' => 12,
      'paged'          => $paged,
      'orderby'        => 'date',
      'order'          => 'DESC',
    ));
  ?>

  <?php if ($articles_query->have_posts()) : ?>
    <div class="articles-grid">
      <?php
        $post_count = 0;
        while ($articles_query->have_posts()) : $articles_query->the_post();
          $post_count++;
          $is_featured = ($post_count === 1 && $paged === 1);
          $category = get_the_category();
          $category_name = !empty($category) ? $category[0]->name : 'Technology';
          $author_id = get_the_author_meta('ID');
          $author_name = get_the_author();
          $author_avatar = get_avatar_url($author_id, array('size' => 80));
      ?>
        <article class="article-card <?php echo $is_featured ? 'featured' : ''; ?>">
          <a href="<?php the_permalink(); ?>" class="card-link">
            <div class="card-image-wrapper">
              <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail($is_featured ? 'featured-large' : 'featured-medium', array('class' => 'card-image')); ?>
              <?php else : ?>
                <div class="card-image-placeholder">
                  <span><?php echo esc_html($category_name); ?></span>
                </div>
              <?php endif; ?>
              <span class="card-category"><?php echo esc_html($category_name); ?></span>
            </div>

            <div class="card-content">
              <h2 class="card-title"><?php the_title(); ?></h2>
              <p class="card-excerpt"><?php echo get_the_excerpt(); ?></p>

              <div class="card-meta">
                <div class="card-authors">
                  <img src="<?php echo esc_url($author_avatar); ?>" alt="<?php echo esc_attr($author_name); ?>" class="author-avatar-small">
                  <span class="author-name"><?php echo esc_html($author_name); ?></span>
                </div>
                <div class="card-stats">
                  <time><?php echo get_the_date('M j, Y'); ?></time>
                  <span class="meta-separator">•</span>
                  <span><?php echo rodytech_reading_time(); ?></span>
                  <span class="meta-separator">•</span>
                  <a href="<?php the_permalink(); ?>#comments" class="comment-link"><?php echo rodytech_comment_count(); ?></a>
                </div>
              </div>
            </div>
          </a>
        </article>
      <?php endwhile; ?>
    </div>

    <div class="pagination">
      <?php
        echo paginate_links(array(
          'base'      => get_permalink() . '%_%',
          'format'    => 'page/%#%/',
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
      <p>Check back soon — new content drops regularly.</p>
    </div>
  <?php endif;
  wp_reset_postdata(); ?>

</div>

<?php get_footer(); ?>
