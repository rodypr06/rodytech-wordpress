<?php get_header(); ?>

<?php if (is_home() || is_front_page()) : ?>

  <div class="blog-header">
    <h1>Latest <span>Articles</span></h1>
    <p>Insights on AI, technology, and business — written from Iowa, built for everywhere.</p>
  </div>

  <?php if (have_posts()) :
    $post_count = 0;
  ?>
    <div class="articles-grid">
      <?php while (have_posts()) : the_post();
        $post_count++;
        $is_featured = ($post_count === 1);
        $category    = get_the_category();
        $cat_name    = !empty($category) ? esc_html($category[0]->name) : 'Article';
        $author_id   = get_the_author_meta('ID');
        $author_name = esc_html(get_the_author());
        $author_avatar = esc_url(get_avatar_url($author_id, array('size' => 80)));
      ?>

        <?php if ($is_featured) : ?>

          <article class="article-card featured">
            <a href="<?php the_permalink(); ?>" class="card-link">

              <div class="card-image-wrapper">
                <?php if (has_post_thumbnail()) : ?>
                  <?php the_post_thumbnail('featured-large', array('class' => 'card-image')); ?>
                <?php else : ?>
                  <div class="card-image-placeholder"><span><?php echo $cat_name; ?></span></div>
                <?php endif; ?>
              </div>

              <div class="featured-overlay">
                <span class="card-category"><?php echo $cat_name; ?></span>
                <div class="card-content">
                  <h2 class="card-title"><?php the_title(); ?></h2>
                  <p class="card-excerpt"><?php echo get_the_excerpt(); ?></p>
                  <div class="card-meta">
                    <div class="card-authors">
                      <img src="<?php echo $author_avatar; ?>" alt="<?php echo $author_name; ?>" class="author-avatar-small">
                      <span class="author-name"><?php echo $author_name; ?></span>
                    </div>
                    <div class="card-stats">
                      <time><?php echo get_the_date('M j, Y'); ?></time>
                      <span class="meta-separator">•</span>
                      <span><?php echo rodytech_reading_time(); ?></span>
                    </div>
                    <span class="read-article-hint">Read Article →</span>
                  </div>
                </div>
              </div>

            </a>
          </article>

        <?php else : ?>

          <article class="article-card">
            <a href="<?php the_permalink(); ?>" class="card-link">
              <div class="card-image-wrapper">
                <?php if (has_post_thumbnail()) : ?>
                  <?php the_post_thumbnail('featured-medium', array('class' => 'card-image')); ?>
                <?php else : ?>
                  <div class="card-image-placeholder"><span><?php echo $cat_name; ?></span></div>
                <?php endif; ?>
                <span class="card-category"><?php echo $cat_name; ?></span>
              </div>
              <div class="card-content">
                <h2 class="card-title"><?php the_title(); ?></h2>
                <p class="card-excerpt"><?php echo get_the_excerpt(); ?></p>
                <div class="card-meta">
                  <div class="card-authors">
                    <img src="<?php echo $author_avatar; ?>" alt="<?php echo $author_name; ?>" class="author-avatar-small">
                    <span class="author-name"><?php echo $author_name; ?></span>
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

        <?php endif; ?>

      <?php endwhile; ?>
    </div>

    <div class="pagination">
      <?php echo paginate_links(array(
        'prev_text' => '← Newer',
        'next_text' => 'Older →',
        'mid_size'  => 2,
      )); ?>
    </div>

  <?php else : ?>
    <div class="no-posts">
      <h2>No articles yet</h2>
      <p>Check back soon for new content!</p>
    </div>
  <?php endif; ?>

<?php else : ?>
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <?php get_template_part('content', 'single'); ?>
  <?php endwhile; endif; ?>
<?php endif; ?>

<?php get_footer(); ?>
