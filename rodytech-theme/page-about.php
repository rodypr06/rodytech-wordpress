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

  <section class="about-hero">
    <div class="about-hero-inner">
      <span class="about-eyebrow">Start Here</span>
      <h1>AI consulting and systems<br>for <span>operators who build</span></h1>
      <p>RodyTech Journal is a field notebook about practical AI, automation, and software. For operators, it explains the decisions behind a useful workflow. For builders, it explores how to implement, test, and maintain one.</p>
    </div>
  </section>

  <section class="about-bio-section">
    <div class="about-bio-card">
      <div class="about-bio-avatar-wrap">
        <img src="<?php echo esc_url($avatar_url); ?>" alt="Roderick Rabelo" class="about-bio-avatar">
        <div class="about-bio-avatar-glow"></div>
      </div>
      <div class="about-bio-content">
        <span class="about-bio-label">The Author</span>
        <h2 class="about-bio-name">Roderick <span>"Rody"</span> Rabelo</h2>
        <span class="about-bio-role">AI consultant &amp; systems builder · RodyTech LLC</span>
        <p>Based in Iowa. I design and ship practical systems: automation, applied AI, and software that helps operators move with more judgment and less waste. Vertical is a tailor, never a brand claim.</p>
        <p>The archive brings together guides, experiments, and implementation notes. Start with the problem you are trying to solve; use the examples and sources to decide what applies to your own system.</p>
        <div class="about-bio-links">
          <a href="<?php echo esc_url(rodytech_marketing_url()); ?>" target="_blank" rel="noopener noreferrer" class="about-bio-btn primary">Visit RodyTech</a>
          <a href="https://linkedin.com/in/roderick-rabelo-78ba9648/" target="_blank" rel="noopener noreferrer" class="about-bio-btn secondary">LinkedIn</a>
          <a href="<?php echo esc_url(home_url('/contact')); ?>" class="about-bio-btn secondary">Contact</a>
        </div>
      </div>
    </div>
  </section>

  <section class="about-topics-section">
    <h2 class="about-section-title">What you'll find here</h2>
    <div class="about-topics-grid">

      <div class="about-topic-card">
        <div class="about-topic-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a10 10 0 1 0 10 10"/><path d="M12 6v6l4 2"/><path d="M22 12c0-2.76-1.12-5.26-2.93-7.07L16 8"/><path d="M22 5v7h-7"/></svg>
        </div>
        <h3>AI Tools &amp; Agents</h3>
        <p>Hands-on notes on AI tools, agents, and automation patterns that can survive real operating constraints.</p>
      </div>

      <div class="about-topic-card">
        <div class="about-topic-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8m-4-4v4"/><path d="M7 8h10M7 12h6"/></svg>
        </div>
        <h3>Business Automation</h3>
        <p>Workflows, scripts, and systems that reduce repetitive work without creating a brittle mess nobody can maintain.</p>
      </div>

      <div class="about-topic-card">
        <div class="about-topic-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
        </div>
        <h3>Developer Resources</h3>
        <p>Code snippets, architecture patterns, and build notes for developers who care about shipping maintainable systems.</p>
      </div>

      <div class="about-topic-card">
        <div class="about-topic-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </div>
        <h3>Iowa Tech Scene</h3>
        <p>Practical reads on Iowa companies, workforce signals, infrastructure, and where durable technical work appears to be forming.</p>
      </div>

      <div class="about-topic-card">
        <div class="about-topic-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07M8.46 8.46a5 5 0 0 0 0 7.07"/></svg>
        </div>
        <h3>Security &amp; Infrastructure</h3>
        <p>Guides on hardening systems, managing infrastructure, and making security decisions small teams can actually execute.</p>
      </div>

      <div class="about-topic-card">
        <div class="about-topic-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
        </div>
        <h3>RodyTech Updates</h3>
        <p>Occasional behind-the-scenes notes from RodyTech LLC: products, experiments, and lessons from building real software.</p>
      </div>

    </div>
  </section>

  <section class="about-mission-section">
    <div class="about-mission-card">
      <div class="about-mission-quote">"</div>
      <blockquote class="about-mission-text">Good technology writing should help you make a better decision before you buy a tool, change a system, or chase a trend.</blockquote>
      <cite class="about-mission-cite">— Rody Rabelo, Founder RodyTech LLC</cite>
    </div>
  </section>

  <section class="about-cta-section">
    <h2>Start with the curated articles</h2>
    <p>The current public set is intentionally small while the broader archive is reviewed and rewritten to a higher standard.</p>
    <div class="about-cta-buttons">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="about-cta-primary">Read the Blog</a>
      <a href="<?php echo esc_url(rodytech_marketing_url()); ?>" target="_blank" rel="noopener noreferrer" class="about-cta-ghost">Visit RodyTech</a>
    </div>
  </section>

</div>

<?php get_footer(); ?>
