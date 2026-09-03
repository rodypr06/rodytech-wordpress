<?php 
/**
 * Author Profile Template
 */
get_header(); 

$author = get_queried_object();

if (!$author instanceof WP_User) {
  $author_name_query_var = get_query_var('author_name');
  $author_id_query_var = get_query_var('author');

  if (is_string($author_name_query_var) && $author_name_query_var !== '') {
    $author = get_user_by('slug', sanitize_title_for_query(wp_unslash($author_name_query_var)));
  }

  if (!$author instanceof WP_User && is_numeric($author_id_query_var)) {
    $author = get_user_by('id', (int) $author_id_query_var);
  }
}

if (!$author instanceof WP_User) {
  global $wp_query;
  $wp_query->set_404();
  status_header(404);
  nocache_headers();
  ?>
  <main class="main-content">
    <section class="editorial-hero editorial-hero-archive">
      <div class="editorial-hero-copy">
        <span class="editorial-eyebrow">Author</span>
        <h1>Author not found</h1>
        <p>The requested author archive could not be resolved.</p>
      </div>
    </section>
  </main>
  <?php
  get_footer();
  return;
}

$author_id = (int) $author->ID;
$author_name = $author->display_name;
$author_bio = $author->description;
$author_avatar = get_avatar($author_id, 160, '', '', array('class' => 'author-profile-avatar'));
$author_position = get_the_author_meta('position', $author_id);
$twitter = get_the_author_meta('twitter', $author_id);
$linkedin = get_the_author_meta('linkedin', $author_id);
$github = get_the_author_meta('github', $author_id);
?>

<div class="author-profile">
  <div class="author-profile-header">
    <div class="author-profile-avatar-wrap">
      <?php echo $author_avatar; ?>
    </div>
    <div class="author-profile-info">
      <span class="editorial-eyebrow">Author</span>
      <h1 class="author-profile-name"><?php echo esc_html($author_name); ?></h1>
      <?php if ($author_position) : ?>
        <span class="author-profile-position"><?php echo esc_html($author_position); ?></span>
      <?php endif; ?>
      
      <?php if ($author_bio) : ?>
        <p class="author-profile-bio"><?php echo esc_html($author_bio); ?></p>
      <?php else : ?>
        <p class="author-profile-bio">Writing on AI, infrastructure, and software systems from the RodyTech archive.</p>
      <?php endif; ?>
      
      <div class="author-profile-social">
        <?php if ($twitter) : ?>
          <a href="<?php echo esc_url($twitter); ?>" target="_blank" rel="noopener noreferrer" class="profile-social-link">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            Twitter
          </a>
        <?php endif; ?>
        <?php if ($linkedin) : ?>
          <a href="<?php echo esc_url($linkedin); ?>" target="_blank" rel="noopener noreferrer" class="profile-social-link">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
            LinkedIn
          </a>
        <?php endif; ?>
        <?php if ($github) : ?>
          <a href="<?php echo esc_url($github); ?>" target="_blank" rel="noopener noreferrer" class="profile-social-link">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
            GitHub
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
  
  <div class="author-articles">
    <h2 class="author-articles-title">Articles by <?php echo esc_html($author_name); ?></h2>
    
    <?php if (have_posts()) : ?>
      <div class="articles-grid">
        <?php while (have_posts()) : the_post(); 
          $category = get_the_category();
          $category_name = !empty($category) ? $category[0]->name : 'Article';
        ?>
          <article class="article-card">
            <a href="<?php the_permalink(); ?>" class="card-link">
              <div class="card-image-wrapper">
                <?php if (has_post_thumbnail()) : ?>
                  <?php the_post_thumbnail('featured-medium', array('class' => 'card-image')); ?>
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
                  <div class="card-stats">
                    <time><?php echo get_the_date('M j, Y'); ?></time>
                    <span class="meta-separator">•</span>
                    <span><?php echo rodytech_reading_time(); ?></span>
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
            'prev_text' => '← Newer',
            'next_text' => 'Older →',
            'mid_size' => 2,
          ));
        ?>
      </div>
    <?php else : ?>
      <p class="no-articles">No articles yet.</p>
    <?php endif; ?>
  </div>
</div>

<?php get_footer(); ?>
