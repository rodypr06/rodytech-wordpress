<?php
/**
 * RodyTech Theme v5 - Dark Modern
 * functions.php
 */

// Theme setup
function rodytech_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    
    // Add featured image sizes
    add_image_size('featured-large', 1200, 675, true); // 16:9
    add_image_size('featured-medium', 800, 450, true); // 16:9
    add_image_size('author-avatar', 80, 80, true);
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'rodytech'),
        'footer' => __('Footer Menu', 'rodytech'),
    ));
}
add_action('after_setup_theme', 'rodytech_setup');

// Enqueue styles
function rodytech_scripts() {
    wp_enqueue_style('rodytech-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap', array(), null);
    wp_enqueue_style('rodytech-style', get_stylesheet_uri(), array(), '6.3');
    wp_enqueue_script('rodytech-animations', get_template_directory_uri() . '/rodytech-animations.js', array(), '2.1', true);
}
add_action('wp_enqueue_scripts', 'rodytech_scripts');

// Custom local avatar — overrides Gravatar for any user with rodytech_avatar_url meta
function rodytech_local_avatar($url, $id_or_email, $args) {
    $user_id = 0;
    if (is_numeric($id_or_email)) {
        $user_id = (int) $id_or_email;
    } elseif (is_object($id_or_email)) {
        if (!empty($id_or_email->user_id)) {
            $user_id = (int) $id_or_email->user_id;
        } elseif (isset($id_or_email->ID)) {
            $user_id = (int) $id_or_email->ID;
        }
    } elseif (is_string($id_or_email) && is_email($id_or_email)) {
        $user = get_user_by('email', $id_or_email);
        if ($user) $user_id = $user->ID;
    }
    if ($user_id) {
        $custom = get_user_meta($user_id, 'rodytech_avatar_url', true);
        if ($custom) return esc_url($custom);
    }
    return $url;
}
add_filter('get_avatar_url', 'rodytech_local_avatar', 10, 3);

// Calculate reading time
function rodytech_reading_time($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    $content = get_post_field('post_content', $post_id);
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // 200 words per minute
    return $reading_time . ' min read';
}

// Get comment count text
function rodytech_comment_count($post_id = null) {
    $count = get_comments_number($post_id);
    if ($count == 0) {
        return 'No comments';
    } elseif ($count == 1) {
        return '1 comment';
    } else {
        return $count . ' comments';
    }
}

// Newsletter subscription handler
function rodytech_handle_newsletter() {
    if (isset($_POST['newsletter_email']) && is_email($_POST['newsletter_email'])) {
        // Store subscription (in production, integrate with Mailchimp/etc)
        setcookie('rodytech_newsletter', sanitize_email($_POST['newsletter_email']), time() + 31536000, COOKIEPATH, COOKIE_DOMAIN);
        wp_redirect(add_query_arg('subscribed', '1', wp_get_referer()));
        exit;
    }
}
add_action('init', 'rodytech_handle_newsletter');

// Add custom author profile fields
function rodytech_author_meta($user_contact) {
    $user_contact['twitter'] = 'Twitter URL';
    $user_contact['linkedin'] = 'LinkedIn URL';
    $user_contact['github'] = 'GitHub URL';
    $user_contact['position'] = 'Job Title / Position';
    return $user_contact;
}
add_filter('user_contactmethods', 'rodytech_author_meta');

// Social share URLs
function rodytech_social_share($platform, $url = null, $title = null) {
    if (!$url) $url = get_permalink();
    if (!$title) $title = get_the_title();
    
    $title = urlencode($title);
    $url = urlencode($url);
    
    switch ($platform) {
        case 'twitter':
            return "https://twitter.com/intent/tweet?text=$title&url=$url";
        case 'linkedin':
            return "https://www.linkedin.com/sharing/share-offsite/?url=$url";
        case 'facebook':
            return "https://www.facebook.com/sharer/sharer.php?u=$url";
        default:
            return '';
    }
}

// Custom excerpt length
function rodytech_excerpt_length($length) {
    return 25;
}
add_filter('excerpt_length', 'rodytech_excerpt_length', 999);

// Excerpt more
function rodytech_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'rodytech_excerpt_more');

// Primary category helper
function rodytech_get_primary_category($post_id = null) {
    $post_id = $post_id ? (int) $post_id : get_the_ID();
    $categories = get_the_category($post_id);

    if (!empty($categories) && !is_wp_error($categories)) {
        return $categories[0];
    }

    return null;
}

// Compact editorial category set used across the homepage and archive layouts
function rodytech_get_editorial_categories($limit = 5) {
    $uncategorized_id = get_cat_ID('Uncategorized');

    $args = array(
        'hide_empty' => true,
        'orderby'    => 'count',
        'order'      => 'DESC',
        'number'     => $limit,
    );

    if ($uncategorized_id) {
        $args['exclude'] = array($uncategorized_id);
    }

    return get_categories($args);
}

// Fallback summaries keep category collection cards useful even without term descriptions
function rodytech_get_category_summary($category) {
    if (!$category instanceof WP_Term) {
        return '';
    }

    $description = trim(strip_tags(term_description($category, 'category')));
    if ($description !== '') {
        return wp_trim_words($description, 18, '...');
    }

    $fallbacks = array(
        'Artificial Intelligence'   => 'Applied AI systems, agent workflows, model operations, and automation that small teams can ship.',
        'Cloud & Infrastructure'    => 'Practical infrastructure guides covering homelabs, proxies, deployment patterns, and resilient ops.',
        'Developer Tools'           => 'Frameworks, workflow upgrades, and build tooling that make engineering teams faster in practice.',
        'Security'                  => 'Security posture, safer defaults, and field-tested approaches to protecting modern internal platforms.',
        'OpenClaw'                  => 'Notes from building OpenClaw, Helix, and the internal systems behind agent-driven product work.',
        'Business & Strategy'       => 'Operational lessons on growing technical systems that still stay useful for real businesses.',
    );

    if (isset($fallbacks[$category->name])) {
        return $fallbacks[$category->name];
    }

    return 'Recent writing and field notes from the RodyTech editorial archive.';
}

// Blog-level stats used in hero and sidebar callouts
function rodytech_get_blog_stats() {
    $post_counts = wp_count_posts('post');
    $published_posts = isset($post_counts->publish) ? (int) $post_counts->publish : 0;
    $categories = rodytech_get_editorial_categories(1);
    $top_category = !empty($categories) ? $categories[0] : null;

    $recent_query = new WP_Query(array(
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'posts_per_page'      => 1,
        'ignore_sticky_posts' => true,
        'date_query'          => array(
            array(
                'after' => '30 days ago',
            ),
        ),
    ));

    $fresh_posts = (int) $recent_query->found_posts;
    wp_reset_postdata();

    return array(
        'published_posts' => $published_posts,
        'fresh_posts'     => $fresh_posts,
        'top_category'    => $top_category,
        'category_count'  => count(get_categories(array('hide_empty' => true))),
    );
}

function rodytech_get_editorial_excerpt($post_id, $length = 22) {
    $excerpt = get_the_excerpt($post_id);

    if ($excerpt === '') {
        $post = get_post($post_id);
        $excerpt = $post ? wp_strip_all_tags($post->post_content) : '';
    }

    return wp_trim_words($excerpt, $length, '...');
}

function rodytech_render_story_meta($post_id, $show_comments = true) {
    $author_id = (int) get_post_field('post_author', $post_id);
    $author_name = get_the_author_meta('display_name', $author_id);
    $author_avatar = get_avatar_url($author_id, array('size' => 80));

    ob_start();
    ?>
    <div class="story-meta">
      <div class="story-author">
        <?php if ($author_avatar) : ?>
          <img src="<?php echo esc_url($author_avatar); ?>" alt="<?php echo esc_attr($author_name); ?>" class="story-author-avatar">
        <?php endif; ?>
        <span class="story-author-name"><?php echo esc_html($author_name); ?></span>
      </div>
      <div class="story-meta-stats">
        <time datetime="<?php echo esc_attr(get_the_date('c', $post_id)); ?>"><?php echo esc_html(get_the_date('M j, Y', $post_id)); ?></time>
        <span class="meta-separator">•</span>
        <span><?php echo esc_html(rodytech_reading_time($post_id)); ?></span>
        <?php if ($show_comments) : ?>
          <span class="meta-separator">•</span>
          <a href="<?php echo esc_url(get_permalink($post_id) . '#comments'); ?>" class="comment-link"><?php echo esc_html(rodytech_comment_count($post_id)); ?></a>
        <?php endif; ?>
      </div>
    </div>
    <?php
    return ob_get_clean();
}

function rodytech_render_story_card($post_id, $variant = 'standard') {
    $post = get_post($post_id);

    if (!$post instanceof WP_Post) {
        return '';
    }

    $category = rodytech_get_primary_category($post_id);
    $category_name = $category ? $category->name : 'Article';
    $permalink = get_permalink($post_id);
    $title = get_the_title($post_id);
    $show_comments = ($variant === 'standard');
    $excerpt_length = ($variant === 'featured') ? 28 : (($variant === 'compact') ? 18 : 22);
    $image_size = ($variant === 'featured') ? 'featured-large' : 'featured-medium';
    $image_class = ($variant === 'compact') ? 'story-card-thumb-image' : 'story-card-image';

    ob_start();
    ?>
    <article class="story-card story-card-<?php echo esc_attr($variant); ?>">
      <a href="<?php echo esc_url($permalink); ?>" class="story-card-link">
        <?php if ($variant === 'featured') : ?>
          <div class="story-card-media story-card-media-featured">
            <?php if (has_post_thumbnail($post_id)) : ?>
              <?php echo get_the_post_thumbnail($post_id, $image_size, array('class' => 'story-card-image')); ?>
            <?php else : ?>
              <div class="story-card-placeholder"><span><?php echo esc_html($category_name); ?></span></div>
            <?php endif; ?>
          </div>
          <div class="story-card-surface">
            <span class="story-card-category"><?php echo esc_html($category_name); ?></span>
            <div class="story-card-body">
              <h2 class="story-card-title"><?php echo esc_html($title); ?></h2>
              <p class="story-card-excerpt"><?php echo esc_html(rodytech_get_editorial_excerpt($post_id, $excerpt_length)); ?></p>
              <?php echo rodytech_render_story_meta($post_id, false); ?>
            </div>
          </div>
        <?php elseif ($variant === 'compact') : ?>
          <div class="story-card-thumb">
            <?php if (has_post_thumbnail($post_id)) : ?>
              <?php echo get_the_post_thumbnail($post_id, $image_size, array('class' => $image_class)); ?>
            <?php else : ?>
              <div class="story-card-placeholder"><span><?php echo esc_html($category_name); ?></span></div>
            <?php endif; ?>
          </div>
          <div class="story-card-body">
            <span class="story-card-kicker"><?php echo esc_html($category_name); ?></span>
            <h3 class="story-card-title"><?php echo esc_html($title); ?></h3>
            <p class="story-card-excerpt"><?php echo esc_html(rodytech_get_editorial_excerpt($post_id, $excerpt_length)); ?></p>
            <?php echo rodytech_render_story_meta($post_id, false); ?>
          </div>
        <?php else : ?>
          <div class="story-card-media">
            <?php if (has_post_thumbnail($post_id)) : ?>
              <?php echo get_the_post_thumbnail($post_id, $image_size, array('class' => 'story-card-image')); ?>
            <?php else : ?>
              <div class="story-card-placeholder"><span><?php echo esc_html($category_name); ?></span></div>
            <?php endif; ?>
            <span class="story-card-category"><?php echo esc_html($category_name); ?></span>
          </div>
          <div class="story-card-body">
            <h3 class="story-card-title"><?php echo esc_html($title); ?></h3>
            <p class="story-card-excerpt"><?php echo esc_html(rodytech_get_editorial_excerpt($post_id, $excerpt_length)); ?></p>
            <?php echo rodytech_render_story_meta($post_id, $show_comments); ?>
          </div>
        <?php endif; ?>
      </a>
    </article>
    <?php
    return ob_get_clean();
}
