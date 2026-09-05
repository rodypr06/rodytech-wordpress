<?php if (get_post_field('post_name', get_the_ID()) === 'a-resilient-next-js-16-devtools-mcp-debugging-pipeline') : ?>
<figure class="reader-workflow">
  <figcaption><span class="reader-kicker">The workflow</span><strong>From a symptom to a verified fix</strong><span>A model proposes explanations; evidence and tests decide what holds up.</span></figcaption>
  <ol>
    <li><span>01</span><strong>Capture</strong><p>Route, revision, timestamp, request, expected vs. observed behavior.</p></li>
    <li><span>02</span><strong>Redact & reproduce</strong><p>Remove sensitive data. Recreate the trigger in an isolated environment.</p></li>
    <li><span>03</span><strong>Challenge</strong><p>Compare hypotheses. Run the smallest test that could disprove each.</p></li>
    <li><span>04</span><strong>Verify</strong><p>Patch narrowly, replay the trigger, run checks, and request human review.</p></li>
  </ol>
</figure>
<aside class="reader-callout"><strong>Key insight</strong><p>More telemetry is not automatically better evidence. The useful unit is a correlated incident that lets you test and reject plausible explanations.</p></aside>
<aside class="reader-callout reader-warning"><strong>Watch out</strong><p>Unified logs do not prove every experimental inspection feature is available. Check the installed release and its official documentation before relying on a capability.</p></aside>
<?php endif; ?>
