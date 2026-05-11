<?php
/**
 * Page Template
 */

get_header();
?>

<main class="site-main">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="page-header">
                <h1><?php the_title(); ?></h1>
            </div>

            <div class="page-content container">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php
get_footer();
