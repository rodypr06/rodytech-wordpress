<?php
/**
 * Template for the About page (auto-matched to slug: about)
 */
get_header();

$avatar_url = get_user_meta(1, 'rodytech_avatar_url', true);
if (!$avatar_url) {
    $avatar_url = get_template_directory_uri() . '/avatar-rody.svg';
}
?>

<div class="about-page">

  <!-- ── Hero ── -->
  <section class="about-hero">
    <div class="about-hero-inner">
      <span class="about-eyebrow">About RodyTech Blog</span>
      <h1>Technology that works<br>for <span>real businesses</span></h1>
      <p>Practical AI and tech insights — no hype, no filler. Built by a builder, written for people who actually build things.</p>
    </div>
  </section>

  <!-- ── Who's Behind It ── -->
  <section class="about-bio-section">
    <div class="about-bio-card">
      <div class="about-bio-avatar-wrap">
        <img src="<?php echo esc_url($avatar_url); ?>" alt="Rody" class="about-bio-avatar">
        <div class="about-bio-avatar-glow"></div>
      </div>
      <div class="about-bio-content">
        <span class="about-bio-label">The Author</span>
        <h2 class="about-bio-name">Roderick <span>"Rody"</span> Rabelo</h2>
        <span class="about-bio-role">Founder &amp; CEO · RodyTech LLC</span>
        <p>Based in Iowa, building tools and systems that help businesses move faster with AI. I write about what I'm actually building, testing, and deploying — not theory. If it's on this blog, it's battle-tested.</p>
        <p>RodyTech started as a one-person consultancy and grew into a full product studio. We build AI agents, custom automation, and software for small and mid-sized businesses who want enterprise power without enterprise overhead.</p>
        <div class="about-bio-links">
          <a href="https://rodytech.net" target="_blank" rel="noopener" class="about-bio-btn primary">rodytech.net ↗</a>
          <a href="https://linkedin.com/in/roderick-rabelo-78ba9648/" target="_blank" rel="noopener" class="about-bio-btn secondary">LinkedIn</a>
          <a href="<?php echo home_url('/contact'); ?>" class="about-bio-btn secondary">Contact</a>
        </div>
      </div>
    </div>
  </section>

  <!-- ── What We Cover ── -->
  <section class="about-topics-section">
    <h2 class="about-section-title">What you'll find here</h2>
    <div class="about-topics-grid">

      <div class="about-topic-card">
        <div class="about-topic-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a10 10 0 1 0 10 10"/><path d="M12 6v6l4 2"/><path d="M22 12c0-2.76-1.12-5.26-2.93-7.07L16 8"/><path d="M22 5v7h-7"/></svg>
        </div>
        <h3>AI Tools &amp; Agents</h3>
        <p>Hands-on reviews, tutorials, and breakdowns of the AI tools that are actually useful for running a business — from LLMs to autonomous agents.</p>
      </div>

      <div class="about-topic-card">
        <div class="about-topic-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8m-4-4v4"/><path d="M7 8h10M7 12h6"/></svg>
        </div>
        <h3>Business Automation</h3>
        <p>Workflows, scripts, and systems that eliminate repetitive work. Built for Iowa businesses and beyond — practical automation that pays for itself.</p>
      </div>

      <div class="about-topic-card">
        <div class="about-topic-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
        </div>
        <h3>Developer Resources</h3>
        <p>Code snippets, architecture patterns, and build logs from real projects. If you're a developer, you'll find things you can actually use today.</p>
      </div>

      <div class="about-topic-card">
        <div class="about-topic-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </div>
        <h3>Iowa Tech Scene</h3>
        <p>Spotlights on local entrepreneurs, events, and the growing tech community across Iowa. Big things are being built here — we cover them.</p>
      </div>

      <div class="about-topic-card">
        <div class="about-topic-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07M8.46 8.46a5 5 0 0 0 0 7.07"/></svg>
        </div>
        <h3>Security &amp; Infrastructure</h3>
        <p>Practical guides on hardening your stack, infrastructure-as-code, and the security practices that actually matter for small teams.</p>
      </div>

      <div class="about-topic-card">
        <div class="about-topic-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
        </div>
        <h3>RodyTech Updates</h3>
        <p>Behind-the-scenes looks at what RodyTech LLC is building — new products, experiments, and lessons learned shipping real software.</p>
      </div>

    </div>
  </section>

  <!-- ── Mission ── -->
  <section class="about-mission-section">
    <div class="about-mission-card">
      <div class="about-mission-quote">"</div>
      <blockquote class="about-mission-text">Technology should work for everyone — not just the Fortune 500. My mission is to close that gap, one article, one tool, and one business at a time.</blockquote>
      <cite class="about-mission-cite">— Rody Rabelo, Founder RodyTech LLC</cite>
    </div>
  </section>

  <!-- ── CTA ── -->
  <section class="about-cta-section">
    <h2>Ready to explore?</h2>
    <p>Browse the latest articles or head to the main RodyTech site to see what we're building.</p>
    <div class="about-cta-buttons">
      <a href="<?php echo home_url(); ?>" class="about-cta-primary">Read the Blog</a>
      <a href="https://rodytech.net" target="_blank" rel="noopener" class="about-cta-ghost">Visit rodytech.net ↗</a>
    </div>
  </section>

</div>

<?php get_footer(); ?>
