<?php
// Deliberately authored guidance for the example article; no invented summaries.
$guide = get_post_meta(get_the_ID(), 'rodytech_reader_guide', true);
$example = get_post_field('post_name', get_the_ID()) === 'a-resilient-next-js-16-devtools-mcp-debugging-pipeline';
if (!$guide && $example) $guide = array(
  'audience' => 'Developers investigating Next.js failures with AI-assisted tools.',
  'prerequisites' => 'Access to the matching application revision, an isolated development or staging environment, and non-sensitive reproduction data.',
  'takeaways' => array('Build a small incident envelope that connects the route, revision, request, and observed failure.', 'Separate documented tooling capabilities from experimental claims.', 'Test competing explanations before applying a narrow patch and replaying the original failure.'),
);
if (!is_array($guide) || empty($guide['takeaways']) || !is_array($guide['takeaways'])) return;
?>
<section class="reader-callout reader-summary" aria-labelledby="reader-summary-title">
  <span class="reader-kicker">Before you begin</span>
  <h2 id="reader-summary-title">What you’ll learn</h2>
  <ul><?php foreach ($guide['takeaways'] as $takeaway) : if (!is_string($takeaway)) continue; ?><li><?php echo esc_html($takeaway); ?></li><?php endforeach; ?></ul>
  <?php if (!empty($guide['audience']) && is_string($guide['audience'])) : ?><p><strong>For:</strong> <?php echo esc_html($guide['audience']); ?></p><?php endif; ?>
  <?php if (!empty($guide['prerequisites']) && is_string($guide['prerequisites'])) : ?><p><strong>Bring:</strong> <?php echo esc_html($guide['prerequisites']); ?></p><?php endif; ?>
</section>
