<?php
/**
 * Single Real Estate Listing Template
 */
get_header();

while (have_posts()): the_post();
    $address_city = get_field('address_info_city');
    $address_region = get_field('address_info_region');
    $address_full = get_field('address');
    $work_time = get_field('work_time');
    $attributes_json = get_field('attributes');
    $contact_json = get_field('contact_info');
    $url = get_field('url');
    $check_url = get_field('check_url');
    $description = get_field('description');
    $rating_json = get_field('rating');
    $topics_json = get_field('place_topics');
    
    // Get taxonomy for related posts
    $terms = get_the_terms(get_the_ID(), RETEX_DIR_TAX);
    $child_term = null;
    if ($terms && !is_wp_error($terms)) {
        foreach ($terms as $t) {
            if ($t->parent != 0) {
                $child_term = $t;
                break;
            }
        }
    }
    ?>
    
    <main class="site-main">
        <div class="container">
            <?php retex_breadcrumbs(); ?>
            
            <header class="single-post-header">
                <h1><?php the_title(); ?> in <?php echo esc_html($address_city); ?><?php echo $address_city && $address_region ? ', ' : ''; ?><?php echo esc_html($address_region); ?></h1>
                
                <?php if ($child_term): ?>
                    <p>
                        <a href="<?php echo esc_url(get_term_link($child_term)); ?>" class="btn btn-secondary">
                            ← Back to <?php echo esc_html($child_term->name); ?>
                        </a>
                    </p>
                <?php endif; ?>
            </header>
            
            <!-- Featured Image -->
            <?php if (has_post_thumbnail()): ?>
                <figure class="post-featured-image">
                    <img src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'large')); ?>" 
                         alt="<?php the_title_attribute(); ?>">
                </figure>
            <?php else: ?>
                <figure class="post-featured-image">
                    <img src="<?php echo retex_get_fallback_image(get_the_title()); ?>" 
                         alt="<?php the_title_attribute(); ?>">
                </figure>
            <?php endif; ?>
            
            <!-- Content Sections -->
            <article class="post-content">
                
                <!-- Attributes Section -->
                <?php if ($attributes_json): ?>
                    <section class="post-section">
                        <h3>Property Features</h3>
                        <div class="post-meta-grid">
                            <?php echo retex_format_attributes($attributes_json); ?>
                        </div>
                    </section>
                <?php endif; ?>
                
                <!-- Work Hours Section -->
                <?php if ($work_time): ?>
                    <section class="post-section">
                        <h3>Office Hours</h3>
                        <?php echo retex_format_work_hours($work_time); ?>
                    </section>
                <?php endif; ?>
                
                <!-- Address Section -->
                <?php if ($address_full): ?>
                    <section class="post-section">
                        <h3>Location</h3>
                        <div class="meta-row">
                            <span class="meta-label">Address:</span>
                            <span class="meta-value"><?php echo esc_html($address_full); ?></span>
                        </div>
                        <?php if ($address_city || $address_region): ?>
                            <div class="meta-row">
                                <span class="meta-label">City/Region:</span>
                                <span class="meta-value">
                                    <?php echo esc_html($address_city); ?><?php echo $address_city && $address_region ? ', ' : ''; ?><?php echo esc_html($address_region); ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </section>
                <?php endif; ?>
                
                <!-- Contact Section -->
                <?php if ($contact_json): ?>
                    <section class="post-section">
                        <h3>Contact Information</h3>
                        <?php echo retex_format_contact_info($contact_json); ?>
                    </section>
                <?php endif; ?>
                
                <!-- Links Section -->
                <section class="post-section">
                    <h3>Quick Links</h3>
                    <div class="post-meta-grid">
                        <?php if ($check_url): ?>
                            <div class="meta-row">
                                <span class="meta-label">View on Map:</span>
                                <span class="meta-value">
                                    <a href="<?php echo esc_url($check_url); ?>" target="_blank" rel="noopener">
                                        Open in Maps ↗
                                    </a>
                                </span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($url): ?>
                            <div class="meta-row">
                                <span class="meta-label">Website:</span>
                                <span class="meta-value">
                                    <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener">
                                        <?php echo esc_html(parse_url($url, PHP_URL_HOST)); ?> ↗
                                    </a>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>
                
                <!-- Description Section -->
                <?php if ($description): ?>
                    <section class="post-section">
                        <h3>Description</h3>
                        <div class="meta-value">
                            <?php echo wp_kses_post(wpautop($description)); ?>
                        </div>
                    </section>
                <?php endif; ?>
                
                <!-- Rating Section -->
                <?php if ($rating_json): ?>
                    <section class="post-section">
                        <h3>Rating & Reviews</h3>
                        <div class="meta-value">
                            <?php echo retex_format_rating($rating_json); ?>
                        </div>
                    </section>
                <?php endif; ?>
                
                <!-- Topics Section -->
                <?php if ($topics_json): ?>
                    <section class="post-section">
                        <h3>Review Topics</h3>
                        <div class="meta-value">
                            <?php echo retex_format_place_topics($topics_json); ?>
                        </div>
                    </section>
                <?php endif; ?>
                
            </article>
            
            <!-- Related Posts Section -->
            <?php if ($child_term): ?>
                <section class="related-section">
                    <h2 class="section-title">
                        Nearby <?php echo esc_html(RETEX_DIR_CAT); ?> in <?php echo esc_html($address_city); ?><?php echo $address_city && $address_region ? ', ' : ''; ?><?php echo esc_html($address_region); ?>
                    </h2>
                    <div class="related-grid">
                        <?php
                        $related_args = array(
                            'post_type' => RETEX_DIR_CPT,
                            'posts_per_page' => 4,
                            'post__not_in' => array(get_the_ID()),
                            'tax_query' => array(
                                array(
                                    'taxonomy' => RETEX_DIR_TAX,
                                    'field' => 'term_id',
                                    'terms' => $child_term->term_id
                                )
                            ),
                            'orderby' => 'rand'
                        );
                        
                        $related_query = new WP_Query($related_args);
                        
                        if ($related_query->have_posts()):
                            while ($related_query->have_posts()): $related_query->the_post();
                                ?>
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
                                    </div>
                                </article>
                                <?php
                            endwhile;
                            wp_reset_postdata();
                        else:
                            echo '<p>No nearby listings found.</p>';
                        endif;
                        ?>
                    </div>
                </section>
            <?php endif; ?>
            
        </div>
    </main>
    
<?php endwhile; ?>

<?php get_footer(); ?>
