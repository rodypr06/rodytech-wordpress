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
        'footer_connect' => __('Footer Connect Menu', 'rodytech'),
    ));
}
add_action('after_setup_theme', 'rodytech_setup');

// Enqueue styles
function rodytech_scripts() {
    wp_enqueue_style('rodytech-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap', array(), null);
    wp_enqueue_style('rodytech-style', get_stylesheet_uri(), array(), '6.6');
    wp_enqueue_script('rodytech-animations', get_template_directory_uri() . '/rodytech-animations.js', array(), '2.2', true);
    wp_add_inline_style(
        'rodytech-style',
        '.newsletter-notice{width:100%;margin:0 0 .75rem;padding:.7rem .9rem;border-radius:999px;font-size:.9rem;line-height:1.4}.newsletter-notice-success{background:rgba(24,184,112,.16);border:1px solid rgba(24,184,112,.28);color:#dff8ea}.newsletter-notice-error{background:rgba(255,126,95,.14);border:1px solid rgba(255,126,95,.28);color:#ffe3d9}.newsletter-honeypot{position:absolute !important;left:-9999px !important;opacity:0 !important;pointer-events:none !important;}'
    );
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

// Newsletter storage and submission flow.
function rodytech_newsletter_option_name() {
    return 'rodytech_newsletter_subscribers';
}

function rodytech_get_newsletter_subscribers() {
    $subscribers = get_option(rodytech_newsletter_option_name(), array());
    return is_array($subscribers) ? $subscribers : array();
}

function rodytech_save_newsletter_subscribers($subscribers) {
    if (!is_array($subscribers)) {
        return false;
    }

    return update_option(rodytech_newsletter_option_name(), $subscribers, false);
}

function rodytech_current_request_url() {
    $host = isset($_SERVER['HTTP_HOST']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_HOST'])) : wp_parse_url(home_url('/'), PHP_URL_HOST);
    $uri = isset($_SERVER['REQUEST_URI']) ? wp_unslash($_SERVER['REQUEST_URI']) : '/';
    $scheme = is_ssl() ? 'https' : 'http';

    return esc_url_raw($scheme . '://' . $host . $uri);
}

function rodytech_newsletter_redirect_url() {
    $url = remove_query_arg(
        array('newsletter_status', 'newsletter_context', 'newsletter_message', 'subscribed'),
        rodytech_current_request_url()
    );

    return wp_validate_redirect($url, home_url('/'));
}

function rodytech_get_newsletter_notice_config($status) {
    $messages = array(
        'success'  => array(
            'type'    => 'success',
            'message' => 'Subscription saved. New articles will go to your inbox.',
        ),
        'duplicate' => array(
            'type'    => 'error',
            'message' => 'That email address is already subscribed.',
        ),
        'invalid'  => array(
            'type'    => 'error',
            'message' => 'Enter a valid email address and try again.',
        ),
        'security' => array(
            'type'    => 'error',
            'message' => 'The form expired. Refresh the page and try again.',
        ),
        'error'    => array(
            'type'    => 'error',
            'message' => 'Subscription could not be saved. Please try again.',
        ),
    );

    return isset($messages[$status]) ? $messages[$status] : null;
}

function rodytech_newsletter_notice_markup($context) {
    $status = isset($_GET['newsletter_status']) ? sanitize_key(wp_unslash($_GET['newsletter_status'])) : '';
    $notice = rodytech_get_newsletter_notice_config($status);

    if (!$notice) {
        return '';
    }

    $requested_context = isset($_GET['newsletter_context']) ? sanitize_key(wp_unslash($_GET['newsletter_context'])) : '';

    if ($requested_context && $requested_context !== $context) {
        return '';
    }

    return sprintf(
        '<div class="newsletter-notice newsletter-notice-%1$s" role="%2$s" aria-live="polite">%3$s</div>',
        esc_attr($notice['type']),
        $notice['type'] === 'error' ? 'alert' : 'status',
        esc_html($notice['message'])
    );
}

function rodytech_newsletter_hidden_fields($context) {
    return implode(
        '',
        array(
            '<input type="hidden" name="action" value="rodytech_newsletter_subscribe">',
            '<input type="hidden" name="newsletter_context" value="' . esc_attr($context) . '">',
            '<input type="hidden" name="newsletter_redirect" value="' . esc_url(rodytech_newsletter_redirect_url()) . '">',
            wp_nonce_field('rodytech_newsletter_subscribe', 'newsletter_nonce', true, false),
            '<input type="text" name="newsletter_company" class="newsletter-honeypot" tabindex="-1" autocomplete="off" aria-hidden="true">',
        )
    );
}

function rodytech_inject_newsletter_form($matches) {
    $context = strpos($matches[1], 'footer-newsletter-form') !== false ? 'footer' : 'header';
    $tag = preg_replace('/\saction="[^"]*"/i', '', $matches[0]);
    $tag = rtrim(substr($tag, 0, -1)) . ' action="' . esc_url(admin_url('admin-post.php')) . '">';

    return $tag . rodytech_newsletter_notice_markup($context) . rodytech_newsletter_hidden_fields($context);
}

function rodytech_newsletter_buffer_callback($html) {
    if (stripos($html, 'newsletter-form') === false && stripos($html, 'footer-newsletter-form') === false) {
        return $html;
    }

    return preg_replace_callback(
        '/<form\b([^>]class="[^"]*(?:newsletter-form|footer-newsletter-form)[^"]*"[^>]*)>/i',
        'rodytech_inject_newsletter_form',
        $html
    );
}

function rodytech_start_newsletter_buffer() {
    if (is_admin() || wp_doing_ajax() || is_feed()) {
        return;
    }

    if (defined('REST_REQUEST') && REST_REQUEST) {
        return;
    }

    ob_start('rodytech_newsletter_buffer_callback');
}
add_action('template_redirect', 'rodytech_start_newsletter_buffer', 0);

function rodytech_store_newsletter_subscriber($email, $context = 'site', $source_url = '') {
    $email = sanitize_email($email);

    if (!is_email($email)) {
        return new WP_Error('invalid_email', 'Invalid email address.');
    }

    $subscribers = rodytech_get_newsletter_subscribers();
    $key = md5(strtolower($email));

    if (isset($subscribers[$key])) {
        return new WP_Error('duplicate', 'Subscriber already exists.');
    }

    $subscribers[$key] = array(
        'email'          => $email,
        'context'        => sanitize_key($context),
        'subscribed_at'  => current_time('mysql'),
        'subscribed_gmt' => current_time('mysql', true),
        'source_url'     => $source_url ? esc_url_raw($source_url) : rodytech_newsletter_redirect_url(),
        'ip_hash'        => isset($_SERVER['REMOTE_ADDR']) ? wp_hash(wp_unslash($_SERVER['REMOTE_ADDR'])) : '',
    );

    $saved = rodytech_save_newsletter_subscribers($subscribers);

    if (!$saved && get_option(rodytech_newsletter_option_name(), null) === null) {
        return new WP_Error('save_failed', 'Unable to save subscriber.');
    }

    return $subscribers[$key];
}

function rodytech_newsletter_redirect_with_status($redirect, $status, $context = 'header') {
    $url = add_query_arg(
        array(
            'newsletter_status'  => sanitize_key($status),
            'newsletter_context' => sanitize_key($context),
        ),
        $redirect
    );

    wp_safe_redirect($url);
    exit;
}

function rodytech_handle_newsletter_submission() {
    $redirect = isset($_POST['newsletter_redirect']) ? wp_unslash($_POST['newsletter_redirect']) : home_url('/');
    $redirect = wp_validate_redirect($redirect, home_url('/'));
    $context = isset($_POST['newsletter_context']) ? sanitize_key(wp_unslash($_POST['newsletter_context'])) : 'header';

    if (!isset($_POST['newsletter_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['newsletter_nonce'])), 'rodytech_newsletter_subscribe')) {
        rodytech_newsletter_redirect_with_status($redirect, 'security', $context);
    }

    if (!empty($_POST['newsletter_company'])) {
        rodytech_newsletter_redirect_with_status($redirect, 'success', $context);
    }

    $email = isset($_POST['newsletter_email']) ? sanitize_email(wp_unslash($_POST['newsletter_email'])) : '';

    if (!is_email($email)) {
        rodytech_newsletter_redirect_with_status($redirect, 'invalid', $context);
    }

    $result = rodytech_store_newsletter_subscriber($email, $context, $redirect);

    if (is_wp_error($result)) {
        $status = $result->get_error_code() === 'duplicate' ? 'duplicate' : 'error';
        rodytech_newsletter_redirect_with_status($redirect, $status, $context);
    }

    rodytech_newsletter_redirect_with_status($redirect, 'success', $context);
}
add_action('admin_post_nopriv_rodytech_newsletter_subscribe', 'rodytech_handle_newsletter_submission');
add_action('admin_post_rodytech_newsletter_subscribe', 'rodytech_handle_newsletter_submission');

function rodytech_newsletter_admin_page() {
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('You do not have permission to access this page.', 'rodytech'));
    }

    $subscribers = array_values(rodytech_get_newsletter_subscribers());
    usort(
        $subscribers,
        function ($left, $right) {
            return strcmp($right['subscribed_gmt'], $left['subscribed_gmt']);
        }
    );

    $export_url = wp_nonce_url(
        admin_url('admin-post.php?action=rodytech_newsletter_export'),
        'rodytech_newsletter_export'
    );
    ?>
    <div class="wrap">
      <h1><?php esc_html_e('Newsletter Subscribers', 'rodytech'); ?></h1>
      <p><?php echo esc_html(sprintf('%d subscriber%s captured through the theme signup forms.', count($subscribers), count($subscribers) === 1 ? '' : 's')); ?></p>
      <p><a href="<?php echo esc_url($export_url); ?>" class="button button-primary"><?php esc_html_e('Export CSV', 'rodytech'); ?></a></p>
      <table class="widefat striped">
        <thead>
          <tr>
            <th><?php esc_html_e('Email', 'rodytech'); ?></th>
            <th><?php esc_html_e('Source', 'rodytech'); ?></th>
            <th><?php esc_html_e('Subscribed At', 'rodytech'); ?></th>
            <th><?php esc_html_e('Page', 'rodytech'); ?></th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($subscribers)) : ?>
            <tr>
              <td colspan="4"><?php esc_html_e('No subscribers captured yet.', 'rodytech'); ?></td>
            </tr>
          <?php else : ?>
            <?php foreach ($subscribers as $subscriber) : ?>
              <tr>
                <td><?php echo esc_html($subscriber['email']); ?></td>
                <td><?php echo esc_html(ucfirst($subscriber['context'])); ?></td>
                <td><?php echo esc_html(mysql2date('Y-m-d H:i:s', $subscriber['subscribed_at'])); ?></td>
                <td><a href="<?php echo esc_url($subscriber['source_url']); ?>" target="_blank" rel="noreferrer"><?php echo esc_html($subscriber['source_url']); ?></a></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    <?php
}

function rodytech_register_newsletter_admin_page() {
    add_management_page(
        __('Newsletter Subscribers', 'rodytech'),
        __('Newsletter Subscribers', 'rodytech'),
        'manage_options',
        'rodytech-newsletter-subscribers',
        'rodytech_newsletter_admin_page'
    );
}
add_action('admin_menu', 'rodytech_register_newsletter_admin_page');

function rodytech_export_newsletter_csv() {
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('You do not have permission to export subscribers.', 'rodytech'));
    }

    check_admin_referer('rodytech_newsletter_export');

    $subscribers = array_values(rodytech_get_newsletter_subscribers());
    usort(
        $subscribers,
        function ($left, $right) {
            return strcmp($right['subscribed_gmt'], $left['subscribed_gmt']);
        }
    );

    nocache_headers();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=rodytech-newsletter-subscribers-' . gmdate('Ymd-His') . '.csv');

    $output = fopen('php://output', 'w');
    fputcsv($output, array('email', 'source', 'subscribed_at', 'source_url'));

    foreach ($subscribers as $subscriber) {
        fputcsv(
            $output,
            array(
                $subscriber['email'],
                $subscriber['context'],
                $subscriber['subscribed_gmt'],
                $subscriber['source_url'],
            )
        );
    }

    fclose($output);
    exit;
}
add_action('admin_post_rodytech_newsletter_export', 'rodytech_export_newsletter_csv');

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
                if ((int) $item->menu_item_parent !== 0) {
                    continue;
                }

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

                $links[] = array(
                    'label'  => $item->title,
                    'url'    => $item->url,
                    'class'  => $is_current ? 'nav-active' : '',
                    'target' => !empty($item->target) ? $item->target : '',
                    'rel'    => !empty($item->xfn) ? $item->xfn : '',
                );
            }
        }
    }

    if (!empty($links)) {
        return $links;
    }

    return $fallback_items;
}

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
