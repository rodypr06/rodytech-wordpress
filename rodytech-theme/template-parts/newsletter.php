<?php
$placement = isset($args['placement']) && $args['placement'] === 'article' ? 'article' : 'footer';
$ready = rodytech_newsletter_ready();
$heading_id = 'journal-join-' . $placement;
?>
<section class="journal-join journal-join--<?php echo esc_attr($placement); ?>" aria-labelledby="<?php echo esc_attr($heading_id); ?>">
  <div class="journal-join-copy">
    <span class="journal-join-kicker">Keep the useful part</span>
    <h2 id="<?php echo esc_attr($heading_id); ?>"><?php echo $ready ? 'A little less noise. A more useful inbox.' : 'Good ideas deserve a next step.'; ?></h2>
    <p><?php echo $ready ? 'Practical AI and automation notes, every two weeks. Useful decisions for operators. Clear implementation notes for builders.' : 'Start with a free workflow scorecard or incident checklist. Follow the Journal through RSS for new articles.'; ?></p>
  </div>
  <div class="journal-join-actions">
    <?php if ($ready) : ?>
    <a class="journal-join-primary" href="<?php echo esc_url(rodytech_newsletter_url()); ?>" data-journal-event="newsletter_signup_open" data-journal-placement="<?php echo esc_attr($placement); ?>">Get the Journal by email <span aria-hidden="true">↗</span></a>
    <small>Choose your interests on our signup page. Unsubscribe anytime. <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">Privacy</a></small>
    <?php else : ?>
    <a class="journal-join-primary" href="<?php echo esc_url(rodytech_reader_resource_url()); ?>" data-journal-event="reader_resource_open" data-journal-placement="<?php echo esc_attr($placement); ?>">Open the free field kit <span aria-hidden="true">↗</span></a>
    <a class="journal-join-secondary" href="<?php echo esc_url(get_feed_link()); ?>" data-journal-event="rss_open" data-journal-placement="<?php echo esc_attr($placement); ?>">Follow via RSS →</a>
    <?php endif; ?>
  </div>
</section>
