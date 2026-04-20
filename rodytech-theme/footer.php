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
          'label'  => 'Main Site ↗',
          'url'    => 'https://rodytech.ai',
          'class'  => '',
          'target' => '_blank',
          'rel'    => 'noopener',
        ),
      ));

      $footer_connect_links = rodytech_get_menu_links('footer_connect', array(
        array(
          'label'  => 'LinkedIn',
          'url'    => 'https://linkedin.com/in/roderick-rabelo-78ba9648/',
          'class'  => '',
          'target' => '_blank',
          'rel'    => 'noopener',
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
    ?>

    <div class="footer-brand">
      <a href="<?php echo home_url(); ?>" class="footer-logo-link">
        <span class="footer-logo"><span>Rody</span><span class="tech">Tech</span> Blog</span>
      </a>
      <p class="footer-tagline">Technology insights for Iowa businesses and the people building the future.</p>
      <p class="footer-newsletter-label">Stay updated</p>
      <form class="footer-newsletter-form" method="post" action="">
        <input type="email" name="newsletter_email" placeholder="your@email.com" required>
        <button type="submit">Subscribe</button>
      </form>
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
      <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</p>
      <p class="footer-powered">Powered by <span>Helix AI</span></p>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
