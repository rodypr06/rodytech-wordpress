</main>

<footer class="site-footer">
  <div class="footer-inner">
    <?php
      $footer_links = rodytech_get_menu_links('footer', array(
        array(
          'label'  => 'Home',
          'url'    => home_url('/'),
          'class'  => '',
          'target' => '',
          'rel'    => '',
        ),
        array(
          'label'  => 'Articles',
          'url'    => home_url('/articles'),
          'class'  => '',
          'target' => '',
          'rel'    => '',
        ),
        array(
          'label'  => 'About',
          'url'    => home_url('/about'),
          'class'  => '',
          'target' => '',
          'rel'    => '',
        ),
        array(
          'label'  => 'Visit RodyTech',
          'url'    => rodytech_marketing_url(),
          'class'  => '',
          'target' => '_blank',
          'rel'    => 'noopener noreferrer',
        ),
      ));

      $footer_connect_links = rodytech_get_menu_links('footer_connect', array(
        array(
          'label'  => 'LinkedIn',
          'url'    => 'https://linkedin.com/in/roderick-rabelo-78ba9648/',
          'class'  => '',
          'target' => '_blank',
          'rel'    => 'noopener noreferrer',
        ),
        array(
          'label'  => 'Contact',
          'url'    => home_url('/contact'),
          'class'  => '',
          'target' => '',
          'rel'    => '',
        ),
        array(
          'label'  => 'Privacy Policy',
          'url'    => home_url('/privacy-policy'),
          'class'  => '',
          'target' => '',
          'rel'    => '',
        ),
      ));
      $lockup_src = get_template_directory_uri() . '/assets/rodytech-lockup.svg';
    ?>

    <div class="footer-brand">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="footer-logo-link">
        <img src="<?php echo esc_url($lockup_src); ?>" alt="RodyTech" class="footer-logo-mark" width="145" height="90">
        <span class="footer-logo-blog">Blog</span>
      </a>
      <p class="footer-tagline">Notes from an AI consultant and systems builder. Industry is how a system is tailored, not a specialist identity.</p>
      <div class="footer-brand-actions">
        <a href="<?php echo esc_url(home_url('/articles')); ?>">Explore articles</a>
        <a href="<?php echo esc_url(rodytech_marketing_url()); ?>" target="_blank" rel="noopener noreferrer">Visit RodyTech</a>
      </div>

      <div class="footer-newsletter" aria-labelledby="footer-newsletter-title">
        <p id="footer-newsletter-title" class="footer-newsletter-label">Newsletter <span class="coming-soon-badge">Coming soon</span></p>
        <p class="footer-newsletter-note">Email updates are not live yet. No mailing list provider is connected.</p>
        <form class="footer-newsletter-form" action="#" method="post" onsubmit="return false;">
          <label class="screen-reader-text" for="newsletter_email">Email</label>
          <input id="newsletter_email" type="email" name="newsletter_email" placeholder="your@email.com" autocomplete="email" disabled>
          <button type="button" disabled>Coming soon</button>
        </form>
      </div>
    </div>

    <div class="footer-col-group">
      <h4>Navigate</h4>
      <div class="footer-col">
        <?php foreach ($footer_links as $link) : ?>
          <a
            href="<?php echo esc_url($link['url']); ?>"
            <?php if (!empty($link['target'])) : ?>target="<?php echo esc_attr($link['target']); ?>"<?php endif; ?>
            <?php if (!empty($link['rel'])) : ?>rel="<?php echo esc_attr($link['rel']); ?>"<?php endif; ?>
          >
            <?php echo esc_html($link['label']); ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="footer-col-group">
      <h4>Connect</h4>
      <div class="footer-col">
        <?php foreach ($footer_connect_links as $link) : ?>
          <a
            href="<?php echo esc_url($link['url']); ?>"
            <?php if (!empty($link['target'])) : ?>target="<?php echo esc_attr($link['target']); ?>"<?php endif; ?>
            <?php if (!empty($link['rel'])) : ?>rel="<?php echo esc_attr($link['rel']); ?>"<?php endif; ?>
          >
            <?php echo esc_html($link['label']); ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>

  </div>

  <div class="footer-bottom">
    <div class="footer-bottom-inner">
      <p>&copy; 2026 RodyTech</p>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
