<?php
/**
 * Taxonomy Template: State/City
 * Handles both parent (state) and child (city) terms
 */
get_header();
$term = get_queried_object();
?>

<main class="site-main">
    <div class="container">
        <?php retex_breadcrumbs(); ?>
        
        <?php if ($term->parent == 0): ?>
            <!-- Parent Taxonomy (State) -->
            <h1><?php echo esc_html(RETEX_DIR_CAT); ?> in <?php echo esc_html($term->name); ?></h1>
            
            <div class="browse-grid" style="margin-top: 30px;">
                <?php
                $children = get_terms(array(
                    'taxonomy' => RETEX_DIR_TAX,
                    'parent' => $term->term_id,
                    'hide_empty' => true,
                    'orderby' => 'name'
                ));
                
                if ($children && !is_wp_error($children)):
                    foreach ($children as $child):
                        $img = retex_get_taxonomy_image_fallback($child->term_id);
                        ?>
                        <article class="card">
                            <a href="<?php echo esc_url(get_term_link($child)); ?>" class="card-image">
                                <img src="<?php echo esc_url($img); ?>" 
                                     alt="<?php echo esc_attr($child->name); ?>"
                                     onerror="this.src='<?php echo retex_get_fallback_image($child->name); ?>'">
                            </a>
                            <div class="card-content">
                                <h4 class="card-title">
                                    <a href="<?php echo esc_url(get_term_link($child)); ?>">
                                        <?php echo esc_html($child->name); ?>
                                    </a>
                                </h4>
                                <?php if ($child->description): ?>
                                    <p class="card-excerpt"><?php echo esc_html(wp_trim_words($child->description, 25)); ?></p>
                                <?php endif; ?>
                                <div class="card-footer">
                                    <span class="card-meta">
                                        <?php echo intval($child->count); ?> property<?php echo $child->count !== 1 ? 's' : ''; ?>
                                    </span>
                                    <a href="<?php echo esc_url(get_term_link($child)); ?>" class="btn">Explore</a>
                                </div>
                            </div>
                        </article>
                        <?php
                    endforeach;
                else:
                    echo '<p class="text-center">No cities found in this state.</p>';
                endif;
                ?>
            </div>
            
            <div class="text-center mt-40">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-secondary">← Back to Home</a>
            </div>
            
        <?php else: ?>
            <!-- Child Taxonomy (City) -->
            <?php
            $parent = get_term($term->parent, RETEX_DIR_TAX);
            $parent_name = $parent && !is_wp_error($parent) ? $parent->name : '';
            ?>
            <h1><?php echo esc_html(RETEX_DIR_CAT); ?> near <?php echo esc_html($term->name); ?><?php echo $parent_name ? ', ' . esc_html($parent_name) : ''; ?></h1>
            
            <div class="popular-grid" style="margin-top: 30px;">
                <?php
                $args = array(
                    'post_type' => RETEX_DIR_CPT,
                    'posts_per_page' => 12,
                    'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
                    'tax_query' => array(
                        array(
                            'taxonomy' => RETEX_DIR_TAX,
                            'field' => 'term_id',
                            'terms' => $term->term_id
                        )
                    )
                );
                
                $query = new WP_Query($args);
                
                if ($query->have_posts()):
                    while ($query->have_posts()): $query->the_post();
                        $address = get_field('address_info_city');
                        $region = get_field('address_info_region');
                        $work_time = get_field('work_time');
                        $url = get_field('url');
                        $rating_json = get_field('rating');
                        $topics_json = get_field('place_topics');
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
                                <h4 class="card-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h4>
                                
                                <?php if ($work_time): ?>
                                    <div class="card-meta">
                                        <strong>Hours:</strong> 
                                        <?php
                                        $hours = retex_format_work_hours($work_time);
                                        if ($hours) {
                                            // Extract first day's hours for preview
                                            preg_match('/<td>([^<]+)<\/td>/', $hours, $matches);
                                            echo isset($matches[1]) ? esc_html($matches[1]) : 'See details';
                                        }
                                        ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($address || $region): ?>
                                    <p class="card-address">
                                        <?php echo esc_html($address); ?><?php echo $address && $region ? ', ' : ''; ?><?php echo esc_html($region); ?>
                                    </p>
                                <?php endif; ?>
                                
                                <?php if ($url): ?>
                                    <p class="card-meta">
                                        <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener">
                                            Visit Website ↗
                                        </a>
                                    </p>
                                <?php endif; ?>
                                
                                <?php if ($rating_json): echo retex_format_rating($rating_json); endif; ?>
                                
                                <?php if ($topics_json): ?>
                                    <div style="margin-top:10px">
                                        <small style="color:var(--color-text-light)">Review Topics:</small>
                                        <?php echo retex_format_place_topics($topics_json); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </article>
                        <?php
                    endwhile;
                    
                    // Pagination
                    echo '<div class="text-center mt-40">';
                    echo paginate_links(array(
                        'total' => $query->max_num_pages,
                        'current' => max(1, get_query_var('paged')),
                        'prev_text' => '← Previous',
                        'next_text' => 'Next →'
                    ));
                    echo '</div>';
                    
                    wp_reset_postdata();
                else:
                    echo '<p class="text-center">No listings found in this location.</p>';
                endif;
                ?>
            </div>
            
            <?php if ($parent && !is_wp_error($parent)): ?>
                <div class="text-center mt-40">
                    <a href="<?php echo esc_url(get_term_link($parent)); ?>" class="btn btn-secondary">
                        ← Back to <?php echo esc_html($parent_name); ?>
                    </a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
