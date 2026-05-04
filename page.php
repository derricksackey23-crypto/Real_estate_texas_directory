<?php
/**
 * Page Template
 */
get_header();
?>

<main class="site-main">
    <div class="container">
        <?php while (have_posts()): the_post(); ?>
            <article class="page-content">
                <?php retex_breadcrumbs(); ?>
                
                <header class="single-post-header">
                    <h1><?php the_title(); ?></h1>
                </header>
                
                <?php if (has_post_thumbnail()): ?>
                    <figure class="post-featured-image">
                        <img src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'large')); ?>" 
                             alt="<?php the_title_attribute(); ?>">
                    </figure>
                <?php endif; ?>
                
                <div class="post-section">
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
