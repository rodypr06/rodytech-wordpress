</main>

<footer class="site-footer">
  <div class="footer-inner">
    <div class="footer-brand">
      <span class="footer-logo"><?php bloginfo('name'); ?></span>
      <p class="footer-tagline">Technology insights for Iowa businesses</p>
    </div>
    
    <div class="footer-newsletter">
      <h4>Stay Updated</h4>
      <p>Get AI insights delivered to your inbox weekly.</p>
      <form class="newsletter-form" style="display:flex;gap:8px;flex-wrap:wrap;">
        <input type="email" placeholder="Your email" required style="padding:8px 12px;border:1px solid #ddd;border-radius:4px;flex:1;min-width:150px;">
        <button type="submit" style="padding:8px 16px;background:#c45c3e;color:white;border:none;border-radius:4px;cursor:pointer;">Subscribe</button>
      </form>
    </div>
    
    <div class="footer-links">
      <div class="footer-col">
        <h4>Navigation</h4>
        <a href="<?php echo home_url(); ?>">Home</a>
        <a href="<?php echo home_url('/articles'); ?>">Articles</a>
        <a href="<?php echo home_url('/about'); ?>">About</a>
      </div>
      <div class="footer-col">
        <h4>Connect</h4>
        <a href="https://linkedin.com/in/roderick-rabelo-78ba9648/" target="_blank">LinkedIn</a>
        <a href="<?php echo home_url('/contact'); ?>">Contact</a>
        <a href="<?php echo home_url('/privacy-policy'); ?>">Privacy Policy</a>
      </div>
    </div>
  </div>
  
  <div class="footer-bottom">
    <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</p>
    <p>Powered by Helix AI</p>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
