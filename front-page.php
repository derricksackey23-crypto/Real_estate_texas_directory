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
            <h1><?php echo esc_html(RE_SITE_TITLE); ?></h1>
            <p><?php _e('Find your perfect property in Texas with our comprehensive directory of real estate professionals and listings.', 'real-estate-texas'); ?></p>
        </div>
    </section>

    <!-- Search Section -->
    <section class="search-section">
        <div class="container">
            <h3><?php printf(__('Search %s by City', 'real-estate-texas'), esc_html(RE_DIRECTORY_GOOGLE_CAT)); ?></h3>
            
            <form class="search-form" id="taxonomy-search-form">
                <div class="form-group">
                    <label for="first-level-taxonomy"><?php _e('Select State', 'real-estate-texas'); ?></label>
                    <select id="first-level-taxonomy" name="parent_taxonomy">
                        <option value=""><?php _e('Choose State', 'real-estate-texas'); ?></option>
                        <?php
                        $parent_terms = get_terms(array(
                            'taxonomy' => RE_CUSTOM_TAXONOMY_KEY,
                            'parent' => 0,
                            'hide_empty' => true,
                        ));

                        if (!is_wp_error($parent_terms) && !empty($parent_terms)) {
                            foreach ($parent_terms as $term) {
                                echo '<option value="' . esc_attr($term->term_id) . '">' . esc_html($term->name) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="child-taxonomy"><?php _e('Select City', 'real-estate-texas'); ?></label>
                    <select id="child-taxonomy" name="child_taxonomy" disabled>
                        <option value=""><?php _e('Select City', 'real-estate-texas'); ?></option>
                    </select>
                </div>

                <button type="submit" class="btn">
                    <i class="fas fa-search"></i> <?php _e('Search', 'real-estate-texas'); ?>
                </button>
            </form>
        </div>
    </section>

    <!-- Browse by State Section -->
    <section class="browse-section">
        <div class="container">
            <h3 class="section-title"><?php printf(__('Browse %s by State', 'real-estate-texas'), esc_html(RE_DIRECTORY_GOOGLE_CAT)); ?></h3>
            
            <div class="taxonomy-grid">
                <?php
                $parent_terms = get_terms(array(
                    'taxonomy' => RE_CUSTOM_TAXONOMY_KEY,
                    'parent' => 0,
                    'hide_empty' => true,
                ));

                if (!is_wp_error($parent_terms) && !empty($parent_terms)) {
                    foreach ($parent_terms as $term) {
                        // Get taxonomy image
                        $image_url = '';
                        if (function_exists('z_taxonomy_image_url')) {
                            $image_url = z_taxonomy_image_url($term->term_id);
                        }
                        
                        // Fallback to first post featured image
                        if (empty($image_url)) {
                            $image_url = re_get_taxonomy_fallback_image($term->term_id, RE_CUSTOM_TAXONOMY_KEY);
                        }
                        
                        // Final fallback
                        if (empty($image_url)) {
                            $image_url = re_get_fallback_image($term->name);
                        }

                        echo '<div class="taxonomy-card">';
                        echo '<div class="taxonomy-card-image">';
                        echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($term->name) . '">';
                        echo '</div>';
                        echo '<div class="taxonomy-card-content">';
                        echo '<h4><a href="' . esc_url(get_term_link($term)) . '">' . esc_html($term->name) . '</a></h4>';
                        if (!empty($term->description)) {
                            echo '<p>' . esc_html($term->description) . '</p>';
                        }
                        echo '</div>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Popular Listings Section -->
    <section class="popular-section">
        <div class="container">
            <h3 class="section-title"><?php printf(__('Popular %s around %s', 'real-estate-texas'), esc_html(RE_DIRECTORY_GOOGLE_CAT), esc_html(RE_DIRECTORY_COUNTRY)); ?></h3>
            
            <div class="listings-grid">
                <?php
                $popular_posts = new WP_Query(array(
                    'post_type' => RE_CUSTOM_POST_KEY,
                    'posts_per_page' => 25,
                    'orderby' => 'rand',
                ));

                if ($popular_posts->have_posts()) {
                    while ($popular_posts->have_posts()) {
                        $popular_posts->the_post();
                        
                        $address = get_field('address_info_city');
                        $region = get_field('address_info_region');
                        $rating_data = get_field('rating');
                        $rating_value = '';
                        
                        if ($rating_data) {
                            $rating_json = is_array($rating_data) ? $rating_data : json_decode($rating_data, true);
                            if ($rating_json && isset($rating_json['value'])) {
                                $rating_value = number_format($rating_json['value'], 1);
                            }
                        }

                        $image_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                        if (empty($image_url)) {
                            $image_url = re_get_fallback_image(get_the_title());
                        }
                        ?>
                        <div class="listing-card">
                            <div class="listing-card-image">
                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php the_title_attribute(); ?>">
                                <?php if (!empty($rating_value)) : ?>
                                    <span class="listing-rating">
                                        <i class="fas fa-star"></i> <?php echo esc_html($rating_value); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="listing-card-content">
                                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <?php if ($address || $region) : ?>
                                    <p class="listing-address">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <?php echo esc_html($address . ($region ? ', ' . $region : '')); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php
                    }
                    wp_reset_postdata();
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Latest Articles Section -->
    <section class="articles-section">
        <div class="container">
            <h3 class="section-title"><?php _e('Latest Articles', 'real-estate-texas'); ?></h3>
            
            <div class="articles-grid">
                <?php
                $latest_posts = new WP_Query(array(
                    'post_type' => 'post',
                    'posts_per_page' => 15,
                    'orderby' => 'date',
                    'order' => 'DESC',
                ));

                if ($latest_posts->have_posts()) {
                    while ($latest_posts->have_posts()) {
                        $latest_posts->the_post();
                        ?>
                        <div class="article-card">
                            <div class="article-card-image">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('large'); ?>
                                <?php else : ?>
                                    <img src="<?php echo esc_url(re_get_fallback_image(get_the_title())); ?>" alt="<?php the_title_attribute(); ?>">
                                <?php endif; ?>
                            </div>
                            <div class="article-card-content">
                                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <p class="article-date">
                                    <i class="far fa-calendar"></i> <?php echo get_the_date(); ?>
                                </p>
                            </div>
                        </div>
                        <?php
                    }
                    wp_reset_postdata();
                }
                ?>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();
