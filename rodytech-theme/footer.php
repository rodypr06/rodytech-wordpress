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
      $lockup_src = get_template_directory_uri() . '/assets/rodytech-mark.webp';
    ?>

    <div class="footer-brand">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="footer-logo-link">
        <img src="<?php echo esc_url($lockup_src); ?>" alt="" class="footer-logo-mark" width="84" height="36"><span class="brand-wordmark">RODYTECH</span>
        <span class="footer-logo-blog">Journal</span>
      </a>
      <p class="footer-tagline">Practical AI. Thoughtfully applied. Field notes from the people building a better way to work.</p>
      <div class="footer-brand-actions">
        <a href="<?php echo esc_url(home_url('/articles')); ?>">Explore articles</a>
        <a href="<?php echo esc_url(rodytech_marketing_url()); ?>" target="_blank" rel="noopener noreferrer">Visit RodyTech</a>
      </div>

      <?php get_template_part('template-parts/newsletter', null, array('placement' => 'footer')); ?>
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
