<?php
/**
 * Main Index Template (Fallback)
 */
get_header();
?>

<main class="site-main">
    <div class="container">
        <?php if (have_posts()): ?>
            <h1 class="section-title"><?php single_post_title(); ?></h1>
            <div class="articles-grid">
                <?php while (have_posts()): the_post(); ?>
                    <article class="card">
                        <a href="<?php the_permalink(); ?>" class="card-image">
                            <?php if (has_post_thumbnail()): ?>
                                <img src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'medium')); ?>" 
                                     alt="<?php the_title_attribute(); ?>">
                            <?php else: ?>
                                <img src="<?php echo retex_get_fallback_image(get_the_title()); ?>" 
                                     alt="<?php the_title_attribute(); ?>">
                            <?php endif; ?>
                        </a>
                        <div class="card-content">
                            <h3 class="card-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            <p class="card-meta">
                                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                    <?php echo get_the_date('F j, Y'); ?>
                                </time>
                            </p>
                            <?php if (has_excerpt()): ?>
                                <p class="card-excerpt"><?php echo get_the_excerpt(); ?></p>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            
            <div class="text-center mt-40">
                <?php echo paginate_links(); ?>
            </div>
        <?php else: ?>
            <p class="text-center">No content found.</p>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
