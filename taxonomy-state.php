<?php
/**
 * Taxonomy: State Template
 */

get_header();

$term = get_queried_object();
$taxonomy = RE_CUSTOM_TAXONOMY_KEY;
?>

<main class="site-main">
    <div class="taxonomy-header">
        <div class="container">
            <?php
            // Breadcrumb
            echo '<div class="breadcrumb">';
            echo '<a href="' . esc_url(home_url('/')) . '"><i class="fas fa-home"></i> ' . __('Home', 'real-estate-texas') . '</a>';
            echo ' <i class="fas fa-chevron-right"></i> ';
            
            if ($term->parent != 0) {
                $parent_term = get_term($term->parent, $taxonomy);
                if ($parent_term && !is_wp_error($parent_term)) {
                    echo '<a href="' . esc_url(get_term_link($parent_term)) . '">' . esc_html($parent_term->name) . '</a>';
                    echo ' <i class="fas fa-chevron-right"></i> ';
                }
            }
            
            echo '<span>' . esc_html($term->name) . '</span>';
            echo '</div>';
            ?>

            <?php if ($term->parent == 0) : ?>
                <!-- Parent Taxonomy -->
                <h1><?php printf(__('%s in %s', 'real-estate-texas'), esc_html(RE_DIRECTORY_GOOGLE_CAT), esc_html($term->name)); ?></h1>
            <?php else : ?>
                <!-- Child Taxonomy -->
                <?php
                $parent_term = get_term($term->parent, $taxonomy);
                $parent_name = $parent_term ? $parent_term->name : '';
                ?>
                <h1><?php printf(__('%s near %s, %s', 'real-estate-texas'), esc_html(RE_DIRECTORY_GOOGLE_CAT), esc_html($term->name), esc_html($parent_name)); ?></h1>
            <?php endif; ?>
        </div>
    </div>

    <div class="container">
        <?php if ($term->parent == 0) : ?>
            <!-- Parent Taxonomy: Show Child Taxonomies -->
            <div class="taxonomy-grid">
                <?php
                $child_terms = get_terms(array(
                    'taxonomy' => $taxonomy,
                    'parent' => $term->term_id,
                    'hide_empty' => true,
                ));

                if (!is_wp_error($child_terms) && !empty($child_terms)) {
                    foreach ($child_terms as $child_term) {
                        $image_url = '';
                        if (function_exists('z_taxonomy_image_url')) {
                            $image_url = z_taxonomy_image_url($child_term->term_id);
                        }
                        
                        if (empty($image_url)) {
                            $image_url = re_get_taxonomy_fallback_image($child_term->term_id, $taxonomy);
                        }
                        
                        if (empty($image_url)) {
                            $image_url = re_get_fallback_image($child_term->name);
                        }

                        $post_count = $child_term->count;
                        ?>
                        <div class="taxonomy-card">
                            <div class="taxonomy-card-image">
                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($child_term->name); ?>">
                            </div>
                            <div class="taxonomy-card-content">
                                <h4>
                                    <a href="<?php echo esc_url(get_term_link($child_term)); ?>">
                                        <?php echo esc_html($child_term->name); ?>
                                    </a>
                                </h4>
                                <p><?php printf(_n('%s listing', '%s listings', $post_count, 'real-estate-texas'), number_format_i18n($post_count)); ?></p>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<p>' . __('No cities found in this state.', 'real-estate-texas') . '</p>';
                }
                ?>
            </div>
        <?php else : ?>
            <!-- Child Taxonomy: Show Posts -->
            <div class="taxonomy-posts-grid">
                <?php
                if (have_posts()) {
                    while (have_posts()) {
                        the_post();
                        
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
                                
                                <?php
                                // Display work time
                                $work_time = get_field('work_time');
                                if ($work_time) {
                                    echo '<div class="work-time-preview">';
                                    echo re_format_work_time($work_time);
                                    echo '</div>';
                                }
                                ?>
                                
                                <?php
                                // Display address
                                $full_address = get_field('address');
                                if ($full_address) {
                                    echo '<p class="listing-full-address"><i class="fas fa-location-arrow"></i> ' . esc_html($full_address) . '</p>';
                                }
                                ?>
                                
                                <?php
                                // Display website URL
                                $url = get_field('url');
                                if ($url) {
                                    echo '<p class="listing-website"><i class="fas fa-globe"></i> <a href="' . esc_url($url) . '" target="_blank">' . __('Visit Website', 'real-estate-texas') . '</a></p>';
                                }
                                ?>
                                
                                <?php
                                // Display rating
                                if ($rating_data) {
                                    echo '<div class="listing-rating-full">';
                                    echo re_format_rating($rating_data);
                                    echo '</div>';
                                }
                                ?>
                                
                                <?php
                                // Display place topics
                                $place_topics = get_field('place_topics');
                                if ($place_topics) {
                                    echo '<div class="listing-topics">';
                                    echo '<strong>' . __('Review Topics:', 'real-estate-texas') . '</strong> ';
                                    echo re_format_place_topics($place_topics);
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<p>' . __('No listings found in this city.', 'real-estate-texas') . '</p>';
                }
                ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();
