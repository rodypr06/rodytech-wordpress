<?php
/**
 * RodyTech Journal v7 - Brand Harmony
 * functions.php
 *
 * SEO / Open Graph: Yoast SEO on the live server is the source of truth.
 * This theme does not emit duplicate Open Graph, Twitter Card, or meta description tags.
 */


function rodytech_marketing_url() {
    return 'https://www.rodytech.ai';
}

// Theme setup
function rodytech_setup() {
    add_theme_support('title-tag');
    add_theme_support('automatic-feed-links');
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
        'footer_connect' => __('Footer Connect Menu', 'rodytech'),
    ));
}
add_action('after_setup_theme', 'rodytech_setup');

// Enqueue styles
function rodytech_scripts() {
    wp_enqueue_style('rodytech-fonts', 'https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@400;500;600;700;800&display=swap', array(), null);
    wp_enqueue_style('rodytech-style', get_stylesheet_uri(), array('rodytech-fonts'), '7.3');
    wp_enqueue_style('rodytech-brand-harmony', get_template_directory_uri() . '/brand-harmony.css', array('rodytech-style'), '7.3');
    wp_enqueue_style('rodytech-publication', get_template_directory_uri() . '/publication.css', array('rodytech-brand-harmony'), '7.3');
    wp_enqueue_style('rodytech-appearance', get_template_directory_uri() . '/appearance.css', array('rodytech-publication'), '1.0');
    wp_enqueue_script('rodytech-animations', get_template_directory_uri() . '/rodytech-animations.js', array(), '3.3', true);
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

function rodytech_clean_imported_content_markup($content) {
    if (stripos($content, 'content_html') === false) {
        return $content;
    }

    $cleaned = preg_replace(
        '/^\s*<p>\s*\{\s*<br\s*\/?>.*?content_html.*?<\/p>\s*/is',
        '',
        $content,
        1
    );

    if (!is_string($cleaned) || $cleaned === $content) {
        return $content;
    }

    $cleaned = preg_replace('/<p>\s*n+\s*<\/p>\s*/i', '', $cleaned);
    $cleaned = preg_replace('/p&gt;\s*n+/i', '', $cleaned);
    $cleaned = preg_replace('/<p>\s*(?:&#8221;|&rdquo;|"|”)?\s*\}\s*<\/p>\s*$/i', '', $cleaned);

    return $cleaned;
}
add_filter('the_content', 'rodytech_clean_imported_content_markup', 12);

function rodytech_should_noindex_archive() {
    if (is_search()) {
        return true;
    }

    if (is_tag()) {
        global $wp_query;
        $tag_count = isset($wp_query->found_posts) ? (int) $wp_query->found_posts : 0;
        return $tag_count < 2;
    }

    if (is_category()) {
        $category = get_queried_object();
        return $category instanceof WP_Term && (int) $category->count === 0;
    }

    return false;
}

function rodytech_archive_robots($robots) {
    if (!is_array($robots)) {
        $robots = array();
    }

    if (rodytech_should_noindex_archive()) {
        $robots['noindex'] = true;
        $robots['nofollow'] = false;
    }

    return $robots;
}
add_filter('wp_robots', 'rodytech_archive_robots');

function rodytech_author_slug_aliases() {
    return array(
        'rody'     => 'helix',
        'rodytech' => 'helix',
    );
}

function rodytech_register_author_alias_rewrites() {
    foreach (rodytech_author_slug_aliases() as $alias => $canonical_slug) {
        add_rewrite_rule(
            '^author/' . preg_quote($alias, '/') . '/?$',
            'index.php?author_name=' . rawurlencode($canonical_slug),
            'top'
        );
    }
}
add_action('init', 'rodytech_register_author_alias_rewrites');

function rodytech_flush_author_alias_rewrites() {
    $rewrite_version = '20260501-author-aliases';

    if (get_option('rodytech_rewrite_version') === $rewrite_version) {
        return;
    }

    rodytech_register_author_alias_rewrites();
    flush_rewrite_rules(false);
    update_option('rodytech_rewrite_version', $rewrite_version, false);
}
add_action('after_switch_theme', 'rodytech_flush_author_alias_rewrites');
add_action('init', 'rodytech_flush_author_alias_rewrites', 20);

function rodytech_canonical_author_slug($author_slug) {
    $aliases = rodytech_author_slug_aliases();

    return isset($aliases[$author_slug]) ? $aliases[$author_slug] : $author_slug;
}

// Normalize author archive requests so valid author slugs consistently resolve to the author template.
function rodytech_normalize_author_archive_request($query_vars) {
    if (is_admin()) {
        return $query_vars;
    }

    $author_slug = '';

    if (!empty($query_vars['author_name']) && is_string($query_vars['author_name'])) {
        $author_slug = sanitize_title_for_query(wp_unslash($query_vars['author_name']));
    } elseif (!empty($query_vars['author']) && is_numeric($query_vars['author'])) {
        $author = get_user_by('id', (int) $query_vars['author']);
        if ($author instanceof WP_User) {
            $author_slug = $author->user_nicename;
        }
    }

    if ($author_slug === '') {
        return $query_vars;
    }

    $author = get_user_by('slug', rodytech_canonical_author_slug($author_slug));
    if (!$author instanceof WP_User) {
        return $query_vars;
    }

    $query_vars['author'] = (int) $author->ID;
    $query_vars['author_name'] = $author->user_nicename;

    return $query_vars;
}
add_filter('request', 'rodytech_normalize_author_archive_request');

function rodytech_get_menu_links($location, $fallback_items = array()) {
    $links = array();

    if (has_nav_menu($location)) {
        $locations = get_nav_menu_locations();
        $menu_id = isset($locations[$location]) ? (int) $locations[$location] : 0;
        $menu_items = $menu_id ? wp_get_nav_menu_items($menu_id) : array();

        if (!empty($menu_items) && !is_wp_error($menu_items)) {
            foreach ($menu_items as $item) {
                $classes = is_array($item->classes) ? $item->classes : array();
                $is_current = array_intersect($classes, array(
                    'current-menu-item',
                    'current_page_item',
                    'current-menu-ancestor',
                    'current-page-ancestor',
                    'current-post-ancestor',
                    'current-menu-parent',
                    'current-page-parent',
                ));

                $target = !empty($item->target) ? $item->target : '';
                $rel_tokens = !empty($item->xfn) ? preg_split('/\s+/', trim($item->xfn)) : array();

                if ($target === '_blank') {
                    $rel_tokens[] = 'noopener';
                    $rel_tokens[] = 'noreferrer';
                }

                $rel_tokens = array_values(array_unique(array_filter($rel_tokens)));

                $links[] = array(
                    'label'  => $item->title,
                    'url'    => $item->url,
                    'class'  => $is_current ? 'nav-active' : '',
                    'target' => $target,
                    'rel'    => implode(' ', $rel_tokens),
                );
            }
        }
    }

    if (!empty($links)) {
        return $links;
    }

    return $fallback_items;
}

// Prevent reverse-tabnabbing for editor-managed menu links in every menu location.
function rodytech_secure_menu_link_attributes($atts) {
    if (($atts['target'] ?? '') !== '_blank') {
        return $atts;
    }

    $tokens = !empty($atts['rel']) ? preg_split('/\s+/', trim($atts['rel'])) : array();
    $tokens[] = 'noopener';
    $tokens[] = 'noreferrer';
    $atts['rel'] = implode(' ', array_values(array_unique(array_filter($tokens))));

    return $atts;
}
add_filter('nav_menu_link_attributes', 'rodytech_secure_menu_link_attributes');

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

function rodytech_normalize_editorial_excerpt_source($text) {
    $plain = trim(wp_strip_all_tags((string) $text));
    $plain = wp_specialchars_decode($plain, ENT_QUOTES);
    $plain = preg_replace('/\s+/', ' ', $plain);

    if ($plain === '' || strpos(ltrim($plain), '{') !== 0) {
        return $plain;
    }

    $candidate = ltrim($plain);
    if (preg_match('/["“](meta description|meta_description|description|excerpt)["”]\s*:\s*["“]([^"”]+)["”]/iu', $candidate, $matches)) {
        return trim(preg_replace('/\s+/', ' ', wp_strip_all_tags($matches[2])));
    }

    $max_scan = min(strlen($candidate), 5000);

    for ($index = 0; $index < $max_scan; $index++) {
        if ($candidate[$index] !== '}') {
            continue;
        }

        $metadata = json_decode(substr($candidate, 0, $index + 1), true);
        if (!is_array($metadata)) {
            continue;
        }

        foreach (array('meta description', 'meta_description', 'description', 'excerpt') as $key) {
            if (!empty($metadata[$key]) && is_string($metadata[$key])) {
                return trim(preg_replace('/\s+/', ' ', wp_strip_all_tags($metadata[$key])));
            }
        }

        return trim(substr($candidate, $index + 1));
    }

    return $plain;
}

function rodytech_get_editorial_excerpt($post_id, $length = 22) {
    $post = get_post($post_id);
    $excerpt = '';

    if ($post instanceof WP_Post) {
        $excerpt = has_excerpt($post_id) ? $post->post_excerpt : $post->post_content;
    }

    $excerpt = rodytech_normalize_editorial_excerpt_source($excerpt);
    if (has_excerpt($post_id) && strpos(ltrim($excerpt), '{') === 0 && $post instanceof WP_Post) {
        $excerpt = rodytech_normalize_editorial_excerpt_source($post->post_content);
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
          <img src="<?php echo esc_url($author_avatar); ?>" alt="" class="story-author-avatar" width="28" height="28" loading="lazy">
        <?php endif; ?>
        <span class="story-author-name"><?php echo esc_html($author_name); ?></span>
      </div>
      <div class="story-meta-stats">
        <time datetime="<?php echo esc_attr(get_the_date('c', $post_id)); ?>"><?php echo esc_html(get_the_date('M j, Y', $post_id)); ?></time>
        <span class="meta-separator">•</span>
        <span><?php echo esc_html(rodytech_reading_time($post_id)); ?></span>
        <?php if ($show_comments) : ?>
          <span class="meta-separator">•</span>
          <span class="comment-count"><?php echo esc_html(rodytech_comment_count($post_id)); ?></span>
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


// Keep the homepage's display and WordPress's pagination in the same query.
function rodytech_publication_query($query) {
    if (!is_admin() && $query->is_main_query() && $query->is_home()) {
        $query->set('posts_per_page', 9);
        $query->set('ignore_sticky_posts', true);
        $query->set('orderby', 'date');
        $query->set('order', 'DESC');
    }
}
add_action('pre_get_posts', 'rodytech_publication_query');

function rodytech_render_publication_story($post_id, $variant = 'list') {
    $story = get_post($post_id);
    if (!$story instanceof WP_Post) return '';
    $category = rodytech_get_primary_category($post_id);
    $author_id = (int) $story->post_author;
    $variant = in_array($variant, array('lead', 'brief', 'list'), true) ? $variant : 'list';
    ob_start();
    ?>
    <article class="publication-story publication-story-<?php echo esc_attr($variant); ?>" data-post-id="<?php echo esc_attr($post_id); ?>">
      <a class="publication-story-image" href="<?php echo esc_url(get_permalink($post_id)); ?>" tabindex="-1" aria-hidden="true">
        <?php if (has_post_thumbnail($post_id)) : ?>
          <?php echo get_the_post_thumbnail($post_id, $variant === 'lead' ? 'featured-large' : 'featured-medium', array('alt' => '', 'loading' => $variant === 'lead' ? 'eager' : 'lazy', 'fetchpriority' => $variant === 'lead' ? 'high' : 'auto')); ?>
        <?php else : ?><span class="publication-image-fallback" aria-hidden="true">R<span>FIELD NOTES</span></span><?php endif; ?>
      </a>
      <div class="publication-story-copy">
        <?php if ($category) : ?><a class="publication-category" href="<?php echo esc_url(get_category_link($category->term_id)); ?>"><?php echo esc_html($category->name); ?></a><?php endif; ?>
        <h2><a href="<?php echo esc_url(get_permalink($post_id)); ?>" class="story-card-link"><?php echo esc_html(get_the_title($post_id)); ?></a></h2>
        <p class="publication-excerpt"><?php echo esc_html(rodytech_get_editorial_excerpt($post_id, $variant === 'lead' ? 32 : 24)); ?></p>
        <div class="publication-meta">
          <a href="<?php echo esc_url(get_author_posts_url($author_id)); ?>"><?php echo esc_html(get_the_author_meta('display_name', $author_id)); ?></a>
          <span aria-hidden="true">·</span><time datetime="<?php echo esc_attr(get_the_date('c', $post_id)); ?>"><?php echo esc_html(get_the_date('M j, Y', $post_id)); ?></time>
          <span aria-hidden="true">·</span><span><?php echo esc_html(rodytech_reading_time($post_id)); ?></span>
        </div>
      </div>
    </article>
    <?php
    return ob_get_clean();
}
