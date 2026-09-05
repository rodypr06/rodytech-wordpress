<?php
// Authored reading guidance, editable without changing the original article body.
add_action('add_meta_boxes', function () {
    add_meta_box('rodytech-reader-guide', 'Reader guide', function ($post) {
        wp_nonce_field('rodytech_reader_guide', 'rodytech_reader_nonce');
        $guide = get_post_meta($post->ID, 'rodytech_reader_guide', true);
        $guide = is_array($guide) ? $guide : array();
        echo '<p>Write article-specific guidance. Empty fields stay hidden; the example Next.js article has a built-in editorial guide.</p>';
        $fields = array('audience' => 'Who is this for?', 'prerequisites' => 'Prerequisites', 'takeaways' => 'Takeaways (one per line)', 'next_step' => 'Practical next step');
        foreach ($fields as $key => $label) {
            $value = $key === 'next_step' ? get_post_meta($post->ID, 'rodytech_next_step', true) : ($guide[$key] ?? '');
            if (is_array($value)) $value = implode("\n", array_filter($value, 'is_string'));
            if (!is_string($value)) $value = '';
            echo '<p><label for="rt-reader-' . esc_attr($key) . '"><strong>' . esc_html($label) . '</strong></label><textarea class="widefat" rows="3" id="rt-reader-' . esc_attr($key) . '" name="rt_reader[' . esc_attr($key) . ']">' . esc_textarea($value) . '</textarea></p>';
        }
        $service = get_post_meta($post->ID, 'rodytech_reader_service_path', true);
        echo '<p><label for="rt-reader-service">Relevant service link (optional)</label><select id="rt-reader-service" name="rt_reader[service]">';
        foreach (array('' => 'None', '/services' => 'RodyTech services', '/#pricing' => 'RodyTech pricing') as $path => $label) echo '<option value="' . esc_attr($path) . '" ' . selected($service, $path, false) . '>' . esc_html($label) . '</option>';
        echo '</select></p>';
    }, 'post', 'normal');
});
add_action('save_post_post', function ($id) {
    if (wp_is_post_autosave($id) || wp_is_post_revision($id) || !current_user_can('edit_post', $id)) return;
    if (empty($_POST['rodytech_reader_nonce']) || !is_string($_POST['rodytech_reader_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['rodytech_reader_nonce'])), 'rodytech_reader_guide')) return;
    $input = isset($_POST['rt_reader']) && is_array($_POST['rt_reader']) ? wp_unslash($_POST['rt_reader']) : array();
    $clean = array();
    foreach (array('audience', 'prerequisites', 'takeaways', 'next_step', 'service') as $key) $clean[$key] = isset($input[$key]) && is_string($input[$key]) ? sanitize_textarea_field($input[$key]) : '';
    $takeaways = array_values(array_filter(array_map('trim', explode("\n", $clean['takeaways']))));
    update_post_meta($id, 'rodytech_reader_guide', $takeaways ? array('audience' => $clean['audience'], 'prerequisites' => $clean['prerequisites'], 'takeaways' => $takeaways) : array());
    update_post_meta($id, 'rodytech_next_step', $clean['next_step']);
    update_post_meta($id, 'rodytech_reader_service_path', in_array($clean['service'], array('/services', '/#pricing'), true) ? $clean['service'] : '');
});
