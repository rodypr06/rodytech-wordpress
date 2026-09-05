<aside class="editorial-sidebar publication-sidebar" aria-label="About the journal and topics">
  <section class="sidebar-card">
    <span class="sidebar-card-label">Behind the journal</span>
    <h2>Technology, put to work.</h2>
    <p>Field notes, technical guides, and perspectives from RodyTech on building useful systems.</p>
    <a href="<?php echo esc_url(home_url('/about/')); ?>" class="inline-action-link">About RodyTech Journal →</a>
  </section>
  <section class="sidebar-card">
    <h2 class="sidebar-topics-title">Explore topics</h2>
    <ul class="sidebar-category-list">
      <?php foreach (get_categories(array('hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC')) as $topic) : ?>
        <li><a href="<?php echo esc_url(get_category_link($topic->term_id)); ?>"><span><?php echo esc_html($topic->name); ?></span><strong><?php echo esc_html(number_format_i18n($topic->count)); ?></strong></a></li>
      <?php endforeach; ?>
    </ul>
  </section>
  <section class="sidebar-card publication-follow">
    <span class="sidebar-card-label">Keep up with new writing</span>
    <h2>Your next useful idea.</h2>
    <p>Add the journal to your RSS reader for new articles as they’re published.</p>
    <a href="<?php echo esc_url(get_feed_link()); ?>" class="inline-action-link">Get the RSS feed ↗</a>
  </section>
</aside>
