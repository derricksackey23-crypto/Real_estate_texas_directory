<?php
/**
 * Main Index Template
 */

get_header();
?>

<main class="site-main">
    <div class="container">
        <?php if (have_posts()) : ?>
            <div class="posts-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="post-content">
                            <h2>
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            
                            <div class="post-meta">
                                <time datetime="<?php echo get_the_date('c'); ?>">
                                    <i class="far fa-calendar"></i> <?php echo get_the_date(); ?>
                                </time>
                            </div>
                            
                            <div class="post-excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                            
                            <a href="<?php the_permalink(); ?>" class="btn btn-read-more">
                                <?php _e('Read More', 'real-estate-texas'); ?>
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <div class="pagination">
                <?php
                the_posts_pagination(array(
                    'mid_size' => 2,
                    'prev_text' => __('Previous', 'real-estate-texas'),
                    'next_text' => __('Next', 'real-estate-texas'),
                ));
                ?>
            </div>
        <?php else : ?>
            <p><?php _e('No posts found.', 'real-estate-texas'); ?></p>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();
