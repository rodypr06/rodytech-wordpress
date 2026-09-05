<?php
/** Reader resources and a provider-hosted newsletter journey. No subscriber data is stored by the theme. */

function rodytech_newsletter_url($value = null) {
    if ($value === null) $value = get_theme_mod('rodytech_newsletter_url', '');
    if (!is_string($value)) return '';
    $url = esc_url_raw(trim($value), array('https'));
    $parts = wp_parse_url($url);
    if (!$parts || empty($parts['host']) || ($parts['scheme'] ?? '') !== 'https' || !empty($parts['user']) || !empty($parts['pass'])) return '';
    return $url;
}

function rodytech_newsletter_ready() {
    return (bool) get_theme_mod('rodytech_newsletter_verified', false) && rodytech_newsletter_url() !== '';
}

add_action('customize_register', function ($customizer) {
    $customizer->add_section('rodytech_newsletter', array('title' => 'Journal newsletter', 'priority' => 140));
    $customizer->add_setting('rodytech_newsletter_url', array('default' => '', 'sanitize_callback' => 'rodytech_newsletter_url'));
    $customizer->add_control('rodytech_newsletter_url', array(
        'section' => 'rodytech_newsletter', 'type' => 'url', 'label' => 'Hosted signup page (HTTPS)',
        'description' => 'Use the public signup page from your email provider. Never enter an API key. Subscribers enter their email on that page.',
    ));
    $customizer->add_setting('rodytech_newsletter_verified', array('default' => false, 'sanitize_callback' => function ($value) { return (bool) $value; }));
    $customizer->add_control('rodytech_newsletter_verified', array(
        'section' => 'rodytech_newsletter', 'type' => 'checkbox', 'label' => 'Enable verified newsletter signup',
        'description' => 'Enable only after confirmation, resource delivery, preferences, privacy information and unsubscribe have been tested. Until then, readers see RSS and free resources.',
    ));
});

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('rodytech-growth', get_template_directory_uri() . '/journal-growth.css', array('rodytech-appearance'), '1.0');
    wp_enqueue_script('rodytech-growth', get_template_directory_uri() . '/journal-growth.js', array(), '1.0', true);
});

function rodytech_reader_resource_url() {
    return get_template_directory_uri() . '/resources/start-here.html';
}
