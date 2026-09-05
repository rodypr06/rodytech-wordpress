<?php
$next_step = get_post_meta(get_the_ID(), 'rodytech_next_step', true);
$example = get_post_field('post_name', get_the_ID()) === 'a-resilient-next-js-16-devtools-mcp-debugging-pipeline';
if (!$next_step && $example) $next_step = 'Choose one reproducible failure. Record the route, revision, request identifier, expected result, and observed result. Remove sensitive data, write two competing explanations, and define one disconfirming test for each before changing code.';
if (!is_string($next_step) || !$next_step) return;
?>
<section class="reader-callout reader-next-step"><span class="reader-kicker">Put it into practice</span><h2>Your next step</h2><p><?php echo esc_html($next_step); ?></p>
<?php if ($example) : ?><details><summary>Try this: incident-envelope checklist</summary><pre><code class="language-text">Route and deployment revision:
Timestamp and request identifier:
Expected vs. observed result:
Non-sensitive reproduction fixture:
Leading explanation and disconfirming test:
Competing explanation and disconfirming test:
Trigger replay, required checks, and human reviewer:</code></pre></details><?php endif; ?>
<?php $service = get_post_meta(get_the_ID(), 'rodytech_reader_service_path', true);
// Only explicitly selected, known marketing destinations are eligible.
if (is_string($service) && in_array($service, array('/services','/#pricing'), true)) : ?><p><a href="<?php echo esc_url(rtrim(rodytech_marketing_url(), '/') . $service); ?>">Explore relevant RodyTech support →</a></p><?php endif; ?>
</section>
