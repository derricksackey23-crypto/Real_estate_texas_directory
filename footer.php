<?php
/**
 * Footer Template
 */
?>
<footer class="site-footer">
    <div class="container">
        <div class="footer-inner">
            <div class="footer-brand">
                <div class="footer-logo">
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/logo.png" 
                         alt="<?php bloginfo('name'); ?>"
                         onerror="this.style.display='none'">
                    <span class="footer-logo-text"><?php bloginfo('name'); ?></span>
                </div>
                <p><?php bloginfo('description'); ?></p>
            </div>
            
            <div class="footer-nav">
                <h4>Quick Links</h4>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer',
                    'menu_class' => '',
                    'container' => false,
                    'fallback_cb' => function() {
                        echo '<ul><li><a href="' . esc_url(home_url('/')) . '">Home</a></li></ul>';
                    }
                ));
                ?>
            </div>
            
            <div class="footer-contact">
                <h4>Contact</h4>
                <p>
                    <strong>Real estate in texas</strong><br>
                    United State of America
                </p>
            </div>
        </div>
        
        <div class="copyright">
            <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
