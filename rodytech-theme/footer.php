</main>

<footer class="site-footer">
  <div class="footer-inner">

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
        <a href="<?php echo home_url(); ?>">Home</a>
        <a href="<?php echo home_url('/articles'); ?>">Articles</a>
        <a href="<?php echo home_url('/about'); ?>">About</a>
        <a href="https://rodytech.ai" target="_blank">Main Site ↗</a>
      </div>
    </div>

    <div class="footer-col-group">
      <h4>Connect</h4>
      <div class="footer-col">
        <a href="https://linkedin.com/in/roderick-rabelo-78ba9648/" target="_blank">LinkedIn</a>
        <a href="<?php echo home_url('/contact'); ?>">Contact</a>
        <a href="<?php echo home_url('/privacy-policy'); ?>">Privacy Policy</a>
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
