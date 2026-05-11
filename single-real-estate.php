<?php
/**
 * Single Real Estate Listing Template
 */

get_header();

while (have_posts()) {
    the_post();
    
    $address_city = get_field('address_info_city');
    $address_region = get_field('address_info_region');
    $address_full = get_field('address');
    ?>

    <main class="site-main">
        <div class="single-listing-header">
            <div class="container">
                <?php
                // Breadcrumb
                echo '<div class="breadcrumb">';
                echo '<a href="' . esc_url(home_url('/')) . '"><i class="fas fa-home"></i> ' . __('Home', 'real-estate-texas') . '</a>';
                
                // Get taxonomy terms
                $terms = get_the_terms(get_the_ID(), RE_CUSTOM_TAXONOMY_KEY);
                if ($terms && !is_wp_error($terms)) {
                    foreach ($terms as $term) {
                        echo ' <i class="fas fa-chevron-right"></i> ';
                        echo '<a href="' . esc_url(get_term_link($term)) . '">' . esc_html($term->name) . '</a>';
                    }
                }
                
                echo '</div>';
                ?>

                <h1><?php printf(__('%s in %s, %s', 'real-estate-texas'), get_the_title(), esc_html($address_city), esc_html($address_region)); ?></h1>
            </div>
        </div>

        <div class="single-listing-content">
            <!-- Featured Image -->
            <div class="listing-main-image">
                <?php if (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail('full'); ?>
                <?php else : ?>
                    <img src="<?php echo esc_url(re_get_fallback_image(get_the_title())); ?>" alt="<?php the_title_attribute(); ?>">
                <?php endif; ?>
            </div>

            <div class="listing-details">
                <div class="listing-info">
                    <!-- Description -->
                    <?php
                    $description = get_field('description');
                    if ($description) :
                    ?>
                        <div class="info-section">
                            <h2><i class="fas fa-info-circle"></i> <?php _e('About', 'real-estate-texas'); ?></h2>
                            <p><?php echo wp_kses_post($description); ?></p>
                        </div>
                    <?php endif; ?>

                    <!-- Attributes -->
                    <?php
                    $attributes = get_field('attributes');
                    if ($attributes) :
                    ?>
                        <div class="info-section">
                            <h3><i class="fas fa-check-circle"></i> <?php _e('Features & Amenities', 'real-estate-texas'); ?></h3>
                            <?php echo re_format_attributes($attributes); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Work Time -->
                    <?php
                    $work_time = get_field('work_time');
                    if ($work_time) :
                    ?>
                        <div class="info-section">
                            <h3><i class="far fa-clock"></i> <?php _e('Business Hours', 'real-estate-texas'); ?></h3>
                            <?php echo re_format_work_time($work_time); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Address -->
                    <?php if ($address_full) : ?>
                        <div class="info-section">
                            <h3><i class="fas fa-map-marker-alt"></i> <?php _e('Address', 'real-estate-texas'); ?></h3>
                            <p><?php echo esc_html($address_full); ?></p>
                        </div>
                    <?php endif; ?>

                    <!-- Rating -->
                    <?php
                    $rating = get_field('rating');
                    if ($rating) :
                    ?>
                        <div class="info-section">
                            <h3><i class="fas fa-star"></i> <?php _e('Rating', 'real-estate-texas'); ?></h3>
                            <?php echo re_format_rating($rating); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Place Topics -->
                    <?php
                    $place_topics = get_field('place_topics');
                    if ($place_topics) :
                    ?>
                        <div class="info-section">
                            <h3><i class="fas fa-comments"></i> <?php _e('Review Topics', 'real-estate-texas'); ?></h3>
                            <?php echo re_format_place_topics($place_topics); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Review Comments -->
                    <?php
                    $review_comments = get_field('review_summary_comments');
                    if ($review_comments) :
                    ?>
                        <div class="reviews-section">
                            <h3><i class="fas fa-quote-left"></i> <?php _e('Customer Reviews', 'real-estate-texas'); ?></h3>
                            <div class="review-comment">
                                <p><?php echo wp_kses_post($review_comments); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="listing-sidebar">
                    <!-- Contact Info Box -->
                    <div class="sidebar-box">
                        <h3><i class="fas fa-address-card"></i> <?php _e('Contact Information', 'real-estate-texas'); ?></h3>
                        
                        <?php
                        $contact_info = get_field('contact_info');
                        if ($contact_info) {
                            echo re_format_contact_info($contact_info);
                        }
                        ?>

                        <!-- Social Links -->
                        <?php
                        $facebook = get_field('facebook');
                        $linkedin = get_field('linkedin');
                        $twitter = get_field('twitter');
                        $youtube = get_field('youtube');
                        $email = get_field('email');

                        if ($facebook || $linkedin || $twitter || $youtube || $email) :
                        ?>
                            <div class="social-links">
                                <?php if ($facebook && $facebook !== 'N/A') : ?>
                                    <a href="<?php echo esc_url($facebook); ?>" target="_blank" aria-label="Facebook">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($linkedin && $linkedin !== 'N/A') : ?>
                                    <a href="<?php echo esc_url($linkedin); ?>" target="_blank" aria-label="LinkedIn">
                                        <i class="fab fa-linkedin-in"></i>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($twitter && $twitter !== 'N/A') : ?>
                                    <a href="<?php echo esc_url($twitter); ?>" target="_blank" aria-label="Twitter">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($youtube && $youtube !== 'N/A') : ?>
                                    <a href="<?php echo esc_url($youtube); ?>" target="_blank" aria-label="YouTube">
                                        <i class="fab fa-youtube"></i>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($email && $email !== 'N/A') : ?>
                                    <a href="mailto:<?php echo esc_attr($email); ?>" aria-label="Email">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Website & Map Links -->
                    <div class="sidebar-box">
                        <h3><i class="fas fa-external-link-alt"></i> <?php _e('Useful Links', 'real-estate-texas'); ?></h3>
                        <ul class="contact-info-list">
                            <?php
                            $url = get_field('url');
                            if ($url) :
                            ?>
                                <li>
                                    <i class="fas fa-globe"></i>
                                    <a href="<?php echo esc_url($url); ?>" target="_blank">
                                        <?php _e('Visit Website', 'real-estate-texas'); ?>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php
                            $check_url = get_field('check_url');
                            if ($check_url) :
                            ?>
                                <li>
                                    <i class="fas fa-map-marked-alt"></i>
                                    <a href="<?php echo esc_url($check_url); ?>" target="_blank">
                                        <?php _e('View on Google Maps', 'real-estate-texas'); ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <!-- Additional Info -->
                    <?php
                    $phone = get_field('phone');
                    $category = get_field('category');
                    
                    if ($phone || $category) :
                    ?>
                        <div class="sidebar-box">
                            <h3><i class="fas fa-info-circle"></i> <?php _e('Additional Info', 'real-estate-texas'); ?></h3>
                            <ul class="contact-info-list">
                                <?php if ($phone) : ?>
                                    <li>
                                        <i class="fas fa-phone"></i>
                                        <a href="tel:<?php echo esc_attr($phone); ?>"><?php echo esc_html($phone); ?></a>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if ($category) : ?>
                                    <li>
                                        <i class="fas fa-tag"></i>
                                        <?php echo esc_html($category); ?>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Related Listings Section -->
        <section class="related-section">
            <div class="container">
                <h2 class="section-title">
                    <?php printf(__('Nearby %s in %s, %s', 'real-estate-texas'), esc_html(RE_DIRECTORY_GOOGLE_CAT), esc_html($address_city), esc_html($address_region)); ?>
                </h2>
                
                <div class="related-grid">
                    <?php
                    // Get terms for this post
                    $terms = get_the_terms(get_the_ID(), RE_CUSTOM_TAXONOMY_KEY);
                    
                    if ($terms && !is_wp_error($terms)) {
                        $term_ids = wp_list_pluck($terms, 'term_id');
                        
                        $related_posts = new WP_Query(array(
                            'post_type' => RE_CUSTOM_POST_KEY,
                            'posts_per_page' => 4,
                            'post__not_in' => array(get_the_ID()),
                            'tax_query' => array(
                                array(
                                    'taxonomy' => RE_CUSTOM_TAXONOMY_KEY,
                                    'field' => 'term_id',
                                    'terms' => $term_ids,
                                ),
                            ),
                        ));

                        if ($related_posts->have_posts()) {
                            while ($related_posts->have_posts()) {
                                $related_posts->the_post();
                                
                                $image_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                                if (empty($image_url)) {
                                    $image_url = re_get_fallback_image(get_the_title());
                                }
                                ?>
                                <div class="listing-card">
                                    <div class="listing-card-image">
                                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php the_title_attribute(); ?>">
                                    </div>
                                    <div class="listing-card-content">
                                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    </div>
                                </div>
                                <?php
                            }
                            wp_reset_postdata();
                        }
                    }
                    ?>
                </div>
            </div>
        </section>
    </main>

    <?php
}

get_footer();
