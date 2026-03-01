<?php get_header(); ?>

<?php if (is_home() || is_front_page()) : ?>
  <!-- Homepage with Grid Layout -->
  <div class="homepage-content">
    <?php if (have_posts()) : 
      $post_count = 0;
    ?>
      <div class="articles-grid">
        <?php while (have_posts()) : the_post(); 
          $post_count++;
          $is_featured = ($post_count === 1);
          $category = get_the_category();
          $category_name = !empty($category) ? $category[0]->name : 'Article';
          
          // Get author info
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
      
      <!-- Pagination / Load More -->
      <div class="pagination">
        <?php 
          echo paginate_links(array(
            'prev_text' => '← Newer',
            'next_text' => 'Older →',
            'mid_size' => 2,
          ));
        ?>
      </div>
      
    <?php else : ?>
      <div class="no-posts">
        <h2>No articles yet</h2>
        <p>Check back soon for new content!</p>
      </div>
    <?php endif; ?>
  </div>

<?php else : ?>
  <!-- Single Article Page (fallback) -->
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <?php get_template_part('content', 'single'); ?>
  <?php endwhile; endif; ?>
<?php endif; ?>

<?php get_footer(); ?>
