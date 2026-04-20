<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post();
  $category        = get_the_category();
  $cat_name        = !empty($category) ? esc_html($category[0]->name) : 'Article';
  $primary_category = function_exists('rodytech_get_primary_category') ? rodytech_get_primary_category(get_the_ID()) : null;
  $category_url     = $primary_category ? get_category_link($primary_category->term_id) : home_url('/articles/');
  $author_id       = get_the_author_meta('ID');
  $author_name     = esc_html(get_the_author());
  $author_bio      = get_the_author_meta('description');
  $author_position = esc_html(get_the_author_meta('position'));
  $twitter         = get_the_author_meta('twitter');
  $linkedin        = get_the_author_meta('linkedin');
  $github          = get_the_author_meta('github');
  $next_post       = get_next_post();
  $previous_post   = get_previous_post();
?>

<article class="single-article">

  <!-- Hero -->
  <?php if (has_post_thumbnail()) : ?>
    <header class="article-hero">
      <div class="hero-image-wrapper">
        <?php the_post_thumbnail('featured-large', array('class' => 'hero-image')); ?>
        <span class="hero-category"><?php echo $cat_name; ?></span>
        <div class="hero-overlay">
          <h1 class="article-title"><?php the_title(); ?></h1>
          <div class="article-meta-header">
            <div class="meta-authors">
              <?php echo get_avatar($author_id, 40, '', '', array('class' => 'author-avatar-large')); ?>
              <div class="meta-author-info">
                <a href="<?php echo get_author_posts_url($author_id); ?>" class="author-name-link"><?php echo $author_name; ?></a>
                <?php if ($author_position) : ?>
                  <span class="author-position"><?php echo $author_position; ?></span>
                <?php endif; ?>
              </div>
            </div>
            <div class="meta-stats">
              <time><?php echo get_the_date('M j, Y'); ?></time>
              <span class="meta-separator">•</span>
              <span><?php echo rodytech_reading_time(); ?></span>
              <span class="meta-separator">•</span>
              <a href="#comments"><?php echo rodytech_comment_count(); ?></a>
            </div>
          </div>
        </div>
      </div>
    </header>
  <?php else : ?>
    <header class="hero-no-image">
      <span class="hero-category"><?php echo $cat_name; ?></span>
      <h1 class="article-title"><?php the_title(); ?></h1>
      <div class="article-meta-header">
        <div class="meta-authors">
          <?php echo get_avatar($author_id, 40, '', '', array('class' => 'author-avatar-large')); ?>
          <div class="meta-author-info">
            <a href="<?php echo get_author_posts_url($author_id); ?>" class="author-name-link"><?php echo $author_name; ?></a>
            <?php if ($author_position) : ?>
              <span class="author-position"><?php echo $author_position; ?></span>
            <?php endif; ?>
          </div>
        </div>
        <div class="meta-stats">
          <time><?php echo get_the_date('M j, Y'); ?></time>
          <span class="meta-separator">•</span>
          <span><?php echo rodytech_reading_time(); ?></span>
          <span class="meta-separator">•</span>
          <a href="#comments"><?php echo rodytech_comment_count(); ?></a>
        </div>
      </div>
    </header>
  <?php endif; ?>

  <!-- Body -->
  <div class="article-body">
    <div class="article-content">
      <?php the_content(); ?>
    </div>

    <section class="article-inline-cta" aria-labelledby="article-inline-cta-title">
      <div class="article-inline-cta-copy">
        <span class="article-inline-cta-kicker">Stay in the loop</span>
        <h2 id="article-inline-cta-title">Get the next deep dive before it hits search.</h2>
        <p>RodyTech publishes practical writing on AI systems, infrastructure, and software that teams can actually ship. Subscribe for new posts without waiting for an algorithm to surface them.</p>
        <ul class="article-inline-cta-points" aria-label="Newsletter benefits">
          <li>One useful email when a new article is worth your time</li>
          <li>Hands-on notes from real builds, deployments, and ops work</li>
          <li>No generic growth funnel copy, just the writing</li>
        </ul>
      </div>
      <div class="article-inline-cta-form-wrap">
        <?php if (isset($_GET['subscribed'])) : ?>
          <div class="article-inline-cta-success" role="status" aria-live="polite">
            <strong>Subscribed.</strong>
            <span>You’ll get the next article in your inbox.</span>
          </div>
        <?php else : ?>
          <form class="article-inline-cta-form" method="post" action="">
            <label class="screen-reader-text" for="article-newsletter-email">Email address</label>
            <input id="article-newsletter-email" type="email" name="newsletter_email" placeholder="your@email.com" required>
            <button type="submit">Subscribe</button>
          </form>
          <div class="article-inline-cta-actions">
            <a href="<?php echo esc_url(home_url('/articles/')); ?>" class="article-inline-link">Browse all articles</a>
            <?php if ($primary_category) : ?>
              <a href="<?php echo esc_url($category_url); ?>" class="article-inline-link">More in <?php echo esc_html($primary_category->name); ?></a>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <!-- Share -->
    <div class="social-share">
      <span class="share-label">Share</span>
      <div class="share-buttons">
        <a href="<?php echo esc_url(rodytech_social_share('twitter')); ?>" target="_blank" rel="noopener" class="share-btn twitter" aria-label="Share on X / Twitter">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
          X / Twitter
        </a>
        <a href="<?php echo esc_url(rodytech_social_share('linkedin')); ?>" target="_blank" rel="noopener" class="share-btn linkedin" aria-label="Share on LinkedIn">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
          LinkedIn
        </a>
        <a href="<?php echo esc_url(rodytech_social_share('facebook')); ?>" target="_blank" rel="noopener" class="share-btn facebook" aria-label="Share on Facebook">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
          Facebook
        </a>
      </div>
    </div>
  </div>

  <!-- Author Box -->
  <div class="author-box">
    <div class="author-box-avatar">
      <?php echo get_avatar($author_id, 80, '', '', array('class' => 'author-avatar-large')); ?>
    </div>
    <div class="author-box-content">
      <h3 class="author-box-name">
        <a href="<?php echo get_author_posts_url($author_id); ?>"><?php echo $author_name; ?></a>
      </h3>
      <?php if ($author_position) : ?>
        <span class="author-box-position"><?php echo $author_position; ?></span>
      <?php endif; ?>
      <p class="author-box-bio"><?php echo esc_html($author_bio ? $author_bio : 'Author at RodyTech Blog.'); ?></p>
      <div class="author-box-social">
        <?php if ($twitter) : ?>
          <a href="<?php echo esc_url($twitter); ?>" target="_blank" rel="noopener" class="social-link">Twitter</a>
        <?php endif; ?>
        <?php if ($linkedin) : ?>
          <a href="<?php echo esc_url($linkedin); ?>" target="_blank" rel="noopener" class="social-link">LinkedIn</a>
        <?php endif; ?>
        <?php if ($github) : ?>
          <a href="<?php echo esc_url($github); ?>" target="_blank" rel="noopener" class="social-link">GitHub</a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <section class="post-conversion-band" aria-labelledby="post-conversion-title">
    <div class="post-conversion-card post-conversion-card-primary">
      <span class="post-conversion-kicker">Next step</span>
      <h3 id="post-conversion-title">Turn one article into a working reading loop.</h3>
      <p>Keep the context warm: subscribe for new writing, revisit the archive, or stay inside the same topic while the thread is still fresh.</p>
      <div class="post-conversion-actions">
        <a href="<?php echo esc_url(home_url('/articles/')); ?>" class="post-conversion-btn post-conversion-btn-primary">Explore the archive</a>
        <?php if ($primary_category) : ?>
          <a href="<?php echo esc_url($category_url); ?>" class="post-conversion-btn post-conversion-btn-secondary">More <?php echo esc_html($primary_category->name); ?></a>
        <?php endif; ?>
      </div>
    </div>
    <div class="post-conversion-card post-conversion-card-secondary">
      <span class="post-conversion-kicker">Keep reading</span>
      <div class="post-conversion-links">
        <?php if ($next_post) : ?>
          <a class="post-conversion-story" href="<?php echo esc_url(get_permalink($next_post->ID)); ?>">
            <span class="post-conversion-story-label">Next article</span>
            <strong><?php echo esc_html(get_the_title($next_post->ID)); ?></strong>
          </a>
        <?php endif; ?>
        <?php if ($previous_post) : ?>
          <a class="post-conversion-story" href="<?php echo esc_url(get_permalink($previous_post->ID)); ?>">
            <span class="post-conversion-story-label">Previous article</span>
            <strong><?php echo esc_html(get_the_title($previous_post->ID)); ?></strong>
          </a>
        <?php endif; ?>
        <?php if (!$next_post && !$previous_post) : ?>
          <div class="post-conversion-empty">
            <span class="post-conversion-story-label">Reading path</span>
            <strong>Use the archive and related articles below to continue.</strong>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- Related Posts -->
  <?php
    $related = new WP_Query(array(
      'category__in'   => wp_get_post_categories(get_the_ID()),
      'posts_per_page' => 3,
      'post__not_in'   => array(get_the_ID()),
    ));
    if ($related->have_posts()) :
  ?>
  <div class="related-posts">
    <h3 class="related-title">Related Articles</h3>
    <div class="related-grid">
      <?php while ($related->have_posts()) : $related->the_post();
        $rel_cat      = get_the_category();
        $rel_cat_name = !empty($rel_cat) ? esc_html($rel_cat[0]->name) : 'Article';
      ?>
        <article class="related-card">
          <a href="<?php the_permalink(); ?>">
            <?php if (has_post_thumbnail()) : ?>
              <?php the_post_thumbnail('featured-medium', array('class' => 'related-image')); ?>
            <?php else : ?>
              <div class="related-image-placeholder"><span><?php echo $rel_cat_name; ?></span></div>
            <?php endif; ?>
            <span class="related-category"><?php echo $rel_cat_name; ?></span>
            <h4 class="related-card-title"><?php the_title(); ?></h4>
          </a>
        </article>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
  <?php endif; ?>

  <!-- Comments -->
  <div id="comments-wrapper" class="comments-section">
    <?php
      if (comments_open() || get_comments_number()) {
        comments_template();
      }
    ?>
  </div>

</article>

<?php endwhile; endif; ?>

<?php get_footer(); ?>
