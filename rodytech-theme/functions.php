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
    wp_enqueue_style('rodytech-style', get_stylesheet_uri(), array(), '6.0');
    wp_enqueue_script('rodytech-animations', get_template_directory_uri() . '/rodytech-animations.js', array(), '2.0', true);
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
function rodytech_comment_count() {
    $count = get_comments_number();
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
