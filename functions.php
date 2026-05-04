<?php
/**
 * Real Estate Texas Directory Theme Functions
 */

// Configuration Variables
$folderName = 'Real_estate_texas_directory';
$siteTitle = 'Real estate in texas';
$customPostKey = 'real_estate';
$customTaxonomyKey = 'state';
$themeName = 'Real_estate_texas_directory';
$directoryGoogleCat = 'Real estate';
$directoryCountry = 'United State of America';

// Make config available globally
if (!defined('RETEX_DIR_CPT')) define('RETEX_DIR_CPT', $customPostKey);
if (!defined('RETEX_DIR_TAX')) define('RETEX_DIR_TAX', $customTaxonomyKey);
if (!defined('RETEX_DIR_CAT')) define('RETEX_DIR_CAT', $directoryGoogleCat);
if (!defined('RETEX_DIR_COUNTRY')) define('RETEX_DIR_COUNTRY', $directoryCountry);

// Include ACF Fields Configuration
require_once get_template_directory() . '/acf-fields.php';

/**
 * Theme Setup
 */
function retex_directory_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
    ));
    add_theme_support('responsive-embeds');
    
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'real-estate-texas-directory'),
        'footer' => __('Footer Menu', 'real-estate-texas-directory')
    ));
    
    set_post_thumbnail_size(600, 400, true);
}
add_action('after_setup_theme', 'retex_directory_setup');

/**
 * Register Custom Post Type: Real Estate
 */
function retex_register_cpt_real_estate() {
    $labels = array(
        'name' => _x('Real Estate Listings', 'Post Type General Name', 'real-estate-texas-directory'),
        'singular_name' => _x('Real Estate Listing', 'Post Type Singular Name', 'real-estate-texas-directory'),
        'menu_name' => __('Real Estate', 'real-estate-texas-directory'),
        'add_new' => __('Add New', 'real-estate-texas-directory'),
        'add_new_item' => __('Add New Listing', 'real-estate-texas-directory'),
        'edit_item' => __('Edit Listing', 'real-estate-texas-directory'),
        'view_item' => __('View Listing', 'real-estate-texas-directory'),
        'search_items' => __('Search Listings', 'real-estate-texas-directory'),
        'not_found' => __('No listings found', 'real-estate-texas-directory'),
        'not_found_in_trash' => __('No listings found in Trash', 'real-estate-texas-directory'),
    );
    
    $args = array(
        'label' => __('Real Estate Listing', 'real-estate-texas-directory'),
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'real-estate', 'with_front' => false),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-building',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest' => true,
    );
    
    register_post_type(RETEX_DIR_CPT, $args);
}
add_action('init', 'retex_register_cpt_real_estate');

/**
 * Register Hierarchical Custom Taxonomy: State
 */
function retex_register_taxonomy_state() {
    $labels = array(
        'name' => _x('States & Cities', 'Taxonomy General Name', 'real-estate-texas-directory'),
        'singular_name' => _x('State/City', 'Taxonomy Singular Name', 'real-estate-texas-directory'),
        'menu_name' => __('Locations', 'real-estate-texas-directory'),
        'all_items' => __('All Locations', 'real-estate-texas-directory'),
        'parent_item' => __('Parent State', 'real-estate-texas-directory'),
        'parent_item_colon' => __('Parent State:', 'real-estate-texas-directory'),
        'new_item_name' => __('New Location Name', 'real-estate-texas-directory'),
        'add_new_item' => __('Add New Location', 'real-estate-texas-directory'),
        'edit_item' => __('Edit Location', 'real-estate-texas-directory'),
        'update_item' => __('Update Location', 'real-estate-texas-directory'),
        'search_items' => __('Search Locations', 'real-estate-texas-directory'),
    );
    
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'location', 'with_front' => false),
        'show_in_rest' => true,
    );
    
    register_taxonomy(RETEX_DIR_TAX, array(RETEX_DIR_CPT), $args);
}
add_action('init', 'retex_register_taxonomy_state');

/**
 * Enqueue Styles and Scripts
 */
function retex_enqueue_assets() {
    // Main stylesheet
    wp_enqueue_style(
        'retex-main',
        get_stylesheet_uri(),
        array(),
        wp_get_theme()->get('Version')
    );
    
    // Main JavaScript
    wp_enqueue_script(
        'retex-main',
        get_template_directory_uri() . '/assets/js/main.js',
        array('jquery'),
        wp_get_theme()->get('Version'),
        true
    );
    
    // Localize AJAX object
    wp_localize_script('retex-main', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('retex_ajax_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'retex_enqueue_assets');

/**
 * AJAX Handler: Get Child Taxonomies
 */
function retex_ajax_get_child_taxonomies() {
    check_ajax_referer('retex_ajax_nonce', 'nonce', false);
    
    $parent_id = isset($_POST['parent_id']) ? intval($_POST['parent_id']) : 0;
    $taxonomy = isset($_POST['taxonomy']) ? sanitize_text_field($_POST['taxonomy']) : RETEX_DIR_TAX;
    
    if (!$parent_id) {
        wp_send_json_error(array('message' => 'Parent ID required'));
        return;
    }
    
    $children = get_terms(array(
        'taxonomy' => $taxonomy,
        'parent' => $parent_id,
        'hide_empty' => true,
        'orderby' => 'name',
        'order' => 'ASC'
    ));
    
    if (is_wp_error($children) || empty($children)) {
        wp_send_json_success(array());
        return;
    }
    
    $results = array();
    foreach ($children as $child) {
        $results[] = array(
            'term_id' => $child->term_id,
            'name' => $child->name,
            'slug' => $child->slug,
            'link' => get_term_link($child)
        );
    }
    
    wp_send_json_success($results);
}
add_action('wp_ajax_get_child_taxonomies', 'retex_ajax_get_child_taxonomies');
add_action('wp_ajax_nopriv_get_child_taxonomies', 'retex_ajax_get_child_taxonomies');

/**
 * Helper: Get Fallback Image URL
 */
function retex_get_fallback_image($text = null) {
    $site_title = defined('RETEX_DIR_CAT') ? RETEX_DIR_CAT : 'Real Estate';
    $text = $text ? rawurlencode($text) : rawurlencode($site_title);
    return 'https://placehold.co/600x400?text=' . $text;
}

/**
 * Helper: Parse Work Hours JSON to Human Readable
 */
function retex_format_work_hours($json_string) {
    if (empty($json_string)) return '';
    
    $data = json_decode($json_string, true);
    if (json_last_error() !== JSON_ERROR_NONE || empty($data)) return '';
    
    if (!isset($data['work_hours']['timetable'])) return '';
    
    $timetable = $data['work_hours']['timetable'];
    $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
    $output = '<table class="work-hours-table"><tbody>';
    
    foreach ($days as $day) {
        $day_name = ucfirst($day);
        if (isset($timetable[$day]) && is_array($timetable[$day]) && !empty($timetable[$day])) {
            $slots = array();
            foreach ($timetable[$day] as $slot) {
                if (isset($slot['open'], $slot['close'])) {
                    $open = sprintf('%02d:%02d', $slot['open']['hour'], $slot['open']['minute']);
                    $close = sprintf('%02d:%02d', $slot['close']['hour'], $slot['close']['minute']);
                    $slots[] = $open . ' – ' . $close;
                }
            }
            $hours = implode(', ', $slots);
        } else {
            $hours = 'Closed';
        }
        $output .= sprintf('<tr><th>%s</th><td>%s</td></tr>', esc_html($day_name), esc_html($hours));
    }
    
    $output .= '</tbody></table>';
    return $output;
}

/**
 * Helper: Parse Rating JSON to Human Readable
 */
function retex_format_rating($json_string) {
    if (empty($json_string)) return '';
    
    $data = json_decode($json_string, true);
    if (json_last_error() !== JSON_ERROR_NONE || empty($data)) return '';
    
    $value = isset($data['value']) ? floatval($data['value']) : 0;
    $max = isset($data['rating_max']) && $data['rating_max'] ? floatval($data['rating_max']) : 5;
    $votes = isset($data['votes_count']) ? intval($data['votes_count']) : 0;
    
    if ($value == 0) return '';
    
    $stars = '';
    $full = floor($value);
    $half = ($value - $full) >= 0.5 ? 1 : 0;
    $empty = 5 - $full - $half;
    
    $stars .= str_repeat('★', $full);
    if ($half) $stars .= '½';
    $stars .= str_repeat('☆', $empty);
    
    return sprintf(
        '<span class="card-rating">%s <span class="count">(%d review%s)</span></span>',
        esc_html($stars),
        $votes,
        $votes === 1 ? '' : 's'
    );
}

/**
 * Helper: Parse Place Topics JSON to Human Readable
 */
function retex_format_place_topics($json_string) {
    if (empty($json_string)) return '';
    
    $data = json_decode($json_string, true);
    if (json_last_error() !== JSON_ERROR_NONE || empty($data)) return '';
    
    $output = '<div class="topics-list">';
    foreach ($data as $topic => $count) {
        if ($count > 0) {
            $topic_label = ucwords(str_replace('_', ' ', $topic));
            $output .= sprintf(
                '<span class="topic-tag">%s <span class="count">%d</span></span>',
                esc_html($topic_label),
                intval($count)
            );
        }
    }
    $output .= '</div>';
    
    return $output;
}

/**
 * Helper: Parse Attributes JSON to Human Readable
 */
function retex_format_attributes($json_string) {
    if (empty($json_string)) return '';
    
    $data = json_decode($json_string, true);
    if (json_last_error() !== JSON_ERROR_NONE || empty($data)) return '';
    
    $output = '<ul style="list-style:none;padding:0;margin:0;">';
    
    if (!empty($data['available_attributes'])) {
        foreach ($data['available_attributes'] as $category => $items) {
            if (is_array($items)) {
                foreach ($items as $attr) {
                    $label = ucwords(str_replace('_', ' ', str_replace('has_', '', $attr)));
                    $output .= sprintf('<li>✓ %s</li>', esc_html($label));
                }
            }
        }
    }
    
    if (!empty($data['unavailable_attributes']) && is_array($data['unavailable_attributes'])) {
        foreach ($data['unavailable_attributes'] as $attr) {
            $label = ucwords(str_replace('_', ' ', str_replace('has_', '', $attr)));
            $output .= sprintf('<li style="opacity:0.6">✗ %s</li>', esc_html($label));
        }
    }
    
    $output .= '</ul>';
    return $output;
}

/**
 * Helper: Parse Contact Info JSON
 */
function retex_format_contact_info($json_string) {
    if (empty($json_string)) return '';
    
    $data = json_decode($json_string, true);
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) return '';
    
    $output = '<div class="contact-info">';
    foreach ($data as $item) {
        $type = isset($item['type']) ? $item['type'] : '';
        $value = isset($item['value']) ? $item['value'] : '';
        
        if ($type === 'telephone' && !empty($value)) {
            $output .= sprintf(
                '<div><strong>Phone:</strong> <a href="tel:%s">%s</a></div>',
                esc_attr($value),
                esc_html($value)
            );
        }
    }
    $output .= '</div>';
    
    return $output;
}

/**
 * Breadcrumb Generator
 */
function retex_breadcrumbs() {
    echo '<nav class="breadcrumbs" aria-label="Breadcrumb">';
    echo '<a href="' . esc_url(home_url('/')) . '">Home</a>';
    
    if (is_tax(RETEX_DIR_TAX)) {
        $term = get_queried_object();
        if ($term->parent != 0) {
            $parent = get_term($term->parent, RETEX_DIR_TAX);
            if ($parent && !is_wp_error($parent)) {
                echo ' <span>›</span> <a href="' . esc_url(get_term_link($parent)) . '">' . esc_html($parent->name) . '</a>';
            }
        }
        echo ' <span>›</span> <span>' . esc_html($term->name) . '</span>';
    } elseif (is_singular(RETEX_DIR_CPT)) {
        echo ' <span>›</span> <a href="' . esc_url(get_post_type_archive_link(RETEX_DIR_CPT)) . '">' . RETEX_DIR_CAT . '</a>';
        
        $terms = get_the_terms(get_the_ID(), RETEX_DIR_TAX);
        if ($terms && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                if ($term->parent != 0) {
                    $parent = get_term($term->parent, RETEX_DIR_TAX);
                    if ($parent && !is_wp_error($parent)) {
                        echo ' <span>›</span> <a href="' . esc_url(get_term_link($parent)) . '">' . esc_html($parent->name) . '</a>';
                    }
                }
                echo ' <span>›</span> <a href="' . esc_url(get_term_link($term)) . '">' . esc_html($term->name) . '</a>';
            }
        }
        echo ' <span>›</span> <span>' . get_the_title() . '</span>';
    }
    
    echo '</nav>';
}

/**
 * Get First Post Featured Image for Taxonomy Fallback
 */
function retex_get_taxonomy_image_fallback($term_id) {
    if (function_exists('z_taxonomy_image_url')) {
        $img = z_taxonomy_image_url($term_id);
        if ($img && !is_wp_error($img) && !empty($img)) {
            return $img;
        }
    }
    
    // Fallback: Get first post in this taxonomy
    $posts = get_posts(array(
        'post_type' => RETEX_DIR_CPT,
        'tax_query' => array(array(
            'taxonomy' => RETEX_DIR_TAX,
            'field' => 'term_id',
            'terms' => $term_id
        )),
        'posts_per_page' => 1,
        'fields' => 'ids'
    ));
    
    if (!empty($posts) && has_post_thumbnail($posts[0])) {
        return get_the_post_thumbnail_url($posts[0], 'medium');
    }
    
    return retex_get_fallback_image();
}
