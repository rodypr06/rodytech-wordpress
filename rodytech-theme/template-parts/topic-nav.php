<?php $topics = get_categories(array('hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC')); ?>
<nav class="publication-topics" aria-label="Topics">
  <a href="<?php echo esc_url(home_url('/articles/')); ?>" <?php if (is_page('articles')) echo 'aria-current="page"'; ?>>All articles</a>
  <?php foreach ($topics as $topic) : ?>
    <a href="<?php echo esc_url(get_category_link($topic->term_id)); ?>" <?php if (is_category($topic->term_id)) echo 'aria-current="page"'; ?>><?php echo esc_html($topic->name); ?></a>
  <?php endforeach; ?>
</nav>
