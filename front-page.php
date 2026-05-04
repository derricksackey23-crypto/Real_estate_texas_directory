<?php
/**
 * Front Page Template
 */
get_header();
?>

<main class="site-main">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <h1><?php echo esc_html(get_bloginfo('name')); ?></h1>
            <p>Find your perfect real estate property across Texas with our comprehensive directory.</p>
            <a href="#search" class="btn btn-secondary">Start Searching</a>
        </div>
    </section>

    <!-- Search Section -->
    <section id="search" class="search-section">
        <div class="container">
            <h3 class="section-title">Search <?php echo esc_html(RETEX_DIR_CAT); ?> by City</h3>
            <form class="search-form" role="search">
                <select id="first-level-taxonomy" aria-label="Select State">
                    <option value="">Select State</option>
                    <?php
                    $parent_terms = get_terms(array(
                        'taxonomy' => RETEX_DIR_TAX,
                        'parent' => 0,
                        'hide_empty' => true,
                        'orderby' => 'name'
                    ));
                    if ($parent_terms && !is_wp_error($parent_terms)) {
                        foreach ($parent_terms as $term) {
                            echo '<option value="' . esc_attr($term->term_id) . '">' . esc_html($term->name) . '</option>';
                        }
                    }
                    ?>
                </select>
                <select id="child-taxonomy" aria-label="Select City" disabled>
                    <option value="">Select City</option>
                </select>
            </form>
        </div>
    </section>

    <!-- Browse by State Section -->
    <section class="browse-section">
        <div class="container">
            <h3 class="section-title">Browse <?php echo esc_html(RETEX_DIR_CAT); ?> by State</h3>
            <div class="browse-grid">
                <?php
                $parent_terms = get_terms(array(
                    'taxonomy' => RETEX_DIR_TAX,
                    'parent' => 0,
                    'hide_empty' => true,
                    'orderby' => 'name',
                    'number' => 9
                ));
                
                if ($parent_terms && !is_wp_error($parent_terms)) {
                    foreach ($parent_terms as $term) {
                        $img = retex_get_taxonomy_image_fallback($term->term_id);
                        ?>
                        <article class="card">
                            <a href="<?php echo esc_url(get_term_link($term)); ?>" class="card-image">
                                <img src="<?php echo esc_url($img); ?>" 
                                     alt="<?php echo esc_attr($term->name); ?>"
                                     onerror="this.src='<?php echo retex_get_fallback_image($term->name); ?>'">
                            </a>
                            <div class="card-content">
                                <h4 class="card-title">
                                    <a href="<?php echo esc_url(get_term_link($term)); ?>">
                                        <?php echo esc_html($term->name); ?>
                                    </a>
                                </h4>
                                <?php if ($term->description): ?>
                                    <p class="card-excerpt"><?php echo esc_html(wp_trim_words($term->description, 20)); ?></p>
                                <?php endif; ?>
                                <div class="card-footer">
                                    <span class="card-meta">
                                        <?php echo intval($term->count); ?> listing<?php echo $term->count !== 1 ? 's' : ''; ?>
                                    </span>
                                    <a href="<?php echo esc_url(get_term_link($term)); ?>" class="btn">View</a>
                                </div>
                            </div>
                        </article>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Popular Listings Section -->
    <section class="popular-section">
        <div class="container">
            <h3 class="section-title">Popular <?php echo esc_html(RETEX_DIR_CAT); ?> around <?php echo esc_html(RETEX_DIR_COUNTRY); ?></h3>
            <div class="popular-grid">
                <?php
                $popular_posts = get_posts(array(
                    'post_type' => RETEX_DIR_CPT,
                    'posts_per_page' => 25,
                    'orderby' => 'rand',
                    'post_status' => 'publish'
                ));
                
                foreach ($popular_posts as $post) {
                    setup_postdata($post);
                    $address = get_field('address_info_city', $post->ID);
                    $region = get_field('address_info_region', $post->ID);
                    $rating_json = get_field('rating', $post->ID);
                    ?>
                    <article class="card">
                        <a href="<?php the_permalink(); ?>" class="card-image">
                            <?php if (has_post_thumbnail($post->ID)): ?>
                                <img src="<?php echo esc_url(get_the_post_thumbnail_url($post->ID, 'medium')); ?>" 
                                     alt="<?php the_title_attribute(); ?>">
                            <?php else: ?>
                                <img src="<?php echo retex_get_fallback_image(get_the_title($post->ID)); ?>" 
                                     alt="<?php the_title_attribute(); ?>">
                            <?php endif; ?>
                        </a>
                        <div class="card-content">
                            <h4 class="card-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h4>
                            <?php if ($address || $region): ?>
                                <p class="card-address">
                                    <?php echo esc_html($address); ?><?php echo $address && $region ? ', ' : ''; ?><?php echo esc_html($region); ?>
                                </p>
                            <?php endif; ?>
                            <?php if ($rating_json): echo retex_format_rating($rating_json); endif; ?>
                        </div>
                    </article>
                    <?php
                }
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </section>

    <!-- Latest Articles Section -->
    <section class="articles-section">
        <div class="container">
            <h3 class="section-title">Latest Articles</h3>
            <div class="articles-grid">
                <?php
                $blog_posts = get_posts(array(
                    'post_type' => 'post',
                    'posts_per_page' => 15,
                    'post_status' => 'publish',
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));
                
                foreach ($blog_posts as $post) {
                    setup_postdata($post);
                    ?>
                    <article class="card">
                        <a href="<?php the_permalink(); ?>" class="card-image">
                            <?php if (has_post_thumbnail($post->ID)): ?>
                                <img src="<?php echo esc_url(get_the_post_thumbnail_url($post->ID, 'medium')); ?>" 
                                     alt="<?php the_title_attribute(); ?>">
                            <?php else: ?>
                                <img src="<?php echo retex_get_fallback_image(get_the_title($post->ID)); ?>" 
                                     alt="<?php the_title_attribute(); ?>">
                            <?php endif; ?>
                        </a>
                        <div class="card-content">
                            <h4 class="card-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h4>
                            <p class="card-meta">
                                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                    <?php echo get_the_date('F j, Y'); ?>
                                </time>
                            </p>
                            <?php if (has_excerpt($post->ID)): ?>
                                <p class="card-excerpt"><?php echo get_the_excerpt(); ?></p>
                            <?php endif; ?>
                        </div>
                    </article>
                    <?php
                }
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
