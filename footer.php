<?php
/**
 * Footer Template
 */
?>
<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-content">
            <div class="footer-section">
                <div class="footer-logo">
                    <?php if (has_custom_logo()) : ?>
                        <?php the_custom_logo(); ?>
                    <?php else : ?>
                        <h3><?php bloginfo('name'); ?></h3>
                    <?php endif; ?>
                </div>
                <p><?php bloginfo('description'); ?></p>
            </div>

            <div class="footer-section">
                <h4><?php _e('Quick Links', 'real-estate-texas'); ?></h4>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer',
                    'menu_class' => 'footer-menu',
                    'container' => false,
                    'fallback_cb' => function() {
                        echo '<ul><li><a href="' . esc_url(home_url('/')) . '">Home</a></li></ul>';
                    },
                ));
                ?>
            </div>

            <div class="footer-section">
                <h4><?php _e('Browse', 'real-estate-texas'); ?></h4>
                <ul>
                    <li><a href="<?php echo esc_url(get_post_type_archive_link('real_estate')); ?>">
                        <i class="fas fa-building"></i> <?php _e('All Listings', 'real-estate-texas'); ?>
                    </a></li>
                    <li><a href="<?php echo esc_url(get_taxonomy_link('state')); ?>">
                        <i class="fas fa-map-marked-alt"></i> <?php _e('By State', 'real-estate-texas'); ?>
                    </a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h4><?php _e('Contact', 'real-estate-texas'); ?></h4>
                <ul>
                    <li><i class="fas fa-envelope"></i> <a href="mailto:<?php echo esc_attr(get_bloginfo('admin_email')); ?>">
                        <?php echo esc_html(get_bloginfo('admin_email')); ?>
                    </a></li>
                    <li><i class="fas fa-phone"></i> <?php _e('Contact Us', 'real-estate-texas'); ?></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php _e('All rights reserved.', 'real-estate-texas'); ?></p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
