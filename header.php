<?php
/**
 * Header Template
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?php echo esc_url(get_template_directory_uri()); ?>/assets/logo.png" type="image/png">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
    <div class="container header-inner">
        <div class="site-logo">
            <a href="<?php echo esc_url(home_url('/')); ?>">
                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/logo.png" 
                     alt="<?php bloginfo('name'); ?>" 
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                <span class="site-logo-text" style="display:none"><?php bloginfo('name'); ?></span>
            </a>
        </div>
        
        <button class="menu-toggle" aria-label="Toggle navigation" aria-expanded="false">
            ☰
        </button>
        
        <nav class="main-navigation" aria-label="Primary Menu">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'menu_class' => '',
                'container' => false,
                'fallback_cb' => false
            ));
            ?>
        </nav>
    </div>
</header>
