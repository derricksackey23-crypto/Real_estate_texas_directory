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

// Define constants for use throughout theme
define('RE_CUSTOM_POST_KEY', 'real_estate');
define('RE_CUSTOM_TAXONOMY_KEY', 'state');
define('RE_DIRECTORY_GOOGLE_CAT', 'Real estate');
define('RE_DIRECTORY_COUNTRY', 'United State of America');
define('RE_SITE_TITLE', 'Real estate in texas');

// Include ACF Fields Configuration
require_once get_template_directory() . '/acf-fields.php';

/**
 * Theme Setup
 */
function re_theme_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(600, 400, true);

    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'real-estate-texas'),
        'footer' => __('Footer Menu', 'real-estate-texas'),
    ));

    // Add theme support for selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for custom logo
    add_theme_support('custom-logo', array(
        'height' => 50,
        'width' => 200,
        'flex-height' => true,
        'flex-width' => true,
    ));

    // Add support for HTML5 markup
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
}
add_action('after_setup_theme', 're_theme_setup');

/**
 * Enqueue scripts and styles
 */
function re_enqueue_scripts() {
    // Main stylesheet
    wp_enqueue_style('re-style', get_stylesheet_uri(), array(), '1.0.0');

    // Google Fonts
    wp_enqueue_style('re-google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap', array(), null);

    // Font Awesome for icons
    wp_enqueue_style('re-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0');

    // jQuery
    wp_enqueue_script('jquery');

    // Custom JavaScript
    wp_enqueue_script('re-scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'), '1.0.0', true);

    // Localize script for AJAX
    wp_localize_script('re-scripts', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('re_nonce')
    ));
}
add_action('wp_enqueue_scripts', 're_enqueue_scripts');

/**
 * Register Custom Post Type
 */
function re_register_custom_post_type() {
    $labels = array(
        'name' => __('Real Estate Listings', 'real-estate-texas'),
        'singular_name' => __('Real Estate', 'real-estate-texas'),
        'menu_name' => __('Real Estate', 'real-estate-texas'),
        'add_new' => __('Add New', 'real-estate-texas'),
        'add_new_item' => __('Add New Listing', 'real-estate-texas'),
        'edit_item' => __('Edit Listing', 'real-estate-texas'),
        'new_item' => __('New Listing', 'real-estate-texas'),
        'view_item' => __('View Listing', 'real-estate-texas'),
        'search_items' => __('Search Listings', 'real-estate-texas'),
        'not_found' => __('No listings found', 'real-estate-texas'),
        'not_found_in_trash' => __('No listings found in trash', 'real-estate-texas'),
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'listing'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-building',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'show_in_rest' => true,
    );

    register_post_type(RE_CUSTOM_POST_KEY, $args);
}
add_action('init', 're_register_custom_post_type');

/**
 * Register Custom Taxonomy
 */
function re_register_custom_taxonomy() {
    $labels = array(
        'name' => __('States', 'real-estate-texas'),
        'singular_name' => __('State', 'real-estate-texas'),
        'search_items' => __('Search States', 'real-estate-texas'),
        'all_items' => __('All States', 'real-estate-texas'),
        'parent_item' => __('Parent State', 'real-estate-texas'),
        'parent_item_colon' => __('Parent State:', 'real-estate-texas'),
        'edit_item' => __('Edit State', 'real-estate-texas'),
        'update_item' => __('Update State', 'real-estate-texas'),
        'add_new_item' => __('Add New State', 'real-estate-texas'),
        'new_item_name' => __('New State Name', 'real-estate-texas'),
        'menu_name' => __('States', 'real-estate-texas'),
    );

    $args = array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'state'),
        'show_in_rest' => true,
    );

    register_taxonomy(RE_CUSTOM_TAXONOMY_KEY, array(RE_CUSTOM_POST_KEY), $args);
}
add_action('init', 're_register_custom_taxonomy');

/**
 * AJAX Handler for Child Taxonomies
 */
function re_get_child_taxonomies() {
    check_ajax_referer('re_nonce', 'nonce');

    $parent_id = intval($_POST['parent_id']);
    $taxonomy = sanitize_text_field($_POST['taxonomy']);

    if (!$parent_id) {
        wp_send_json_error();
    }

    $terms = get_terms(array(
        'taxonomy' => $taxonomy,
        'parent' => $parent_id,
        'hide_empty' => true,
    ));

    if (is_wp_error($terms) || empty($terms)) {
        wp_send_json(array());
    }

    $result = array();
    foreach ($terms as $term) {
        $result[] = array(
            'id' => $term->term_id,
            'name' => $term->name,
            'link' => get_term_link($term),
        );
    }

    wp_send_json($result);
}
add_action('wp_ajax_get_child_taxonomies', 're_get_child_taxonomies');
add_action('wp_ajax_nopriv_get_child_taxonomies', 're_get_child_taxonomies');

/**
 * Get Parent Taxonomy Link
 */
function re_get_parent_taxonomy_link($term_id, $taxonomy) {
    $term = get_term($term_id, $taxonomy);
    
    if (!$term || is_wp_error($term)) {
        return '';
    }

    if ($term->parent == 0) {
        return get_term_link($term);
    }

    $parent_term = get_term($term->parent, $taxonomy);
    return $parent_term ? get_term_link($parent_term) : '';
}

/**
 * Get Taxonomy Image URL
 */
if (!function_exists('z_taxonomy_image_url')) {
    function z_taxonomy_image_url($term_id = null) {
        if (!$term_id) {
            if (is_category()) {
                $term_id = get_queried_object_id();
            } elseif (is_tax()) {
                $term = get_queried_object();
                $term_id = $term ? $term->term_id : null;
            }
        }

        if (!$term_id) {
            return '';
        }

        $image_id = get_term_meta($term_id, 'taxonomy_image_id', true);
        
        if ($image_id) {
            $image_url = wp_get_attachment_url($image_id);
            return $image_url ? $image_url : '';
        }

        return '';
    }
}

/**
 * Helper function to get first post featured image for taxonomy
 */
function re_get_taxonomy_fallback_image($term_id, $taxonomy) {
    $posts = get_posts(array(
        'post_type' => RE_CUSTOM_POST_KEY,
        'posts_per_page' => 1,
        'tax_query' => array(
            array(
                'taxonomy' => $taxonomy,
                'field' => 'term_id',
                'terms' => $term_id,
            ),
        ),
        'fields' => 'ids',
    ));

    if (!empty($posts)) {
        $featured_image = get_the_post_thumbnail_url($posts[0], 'full');
        return $featured_image ? $featured_image : '';
    }

    return '';
}

/**
 * Parse and format work time JSON
 */
function re_format_work_time($json_data) {
    if (empty($json_data)) {
        return '';
    }

    $data = is_array($json_data) ? $json_data : json_decode($json_data, true);
    
    if (!$data || !isset($data['timetable'])) {
        return '';
    }

    $days = array(
        'monday' => 'Monday',
        'tuesday' => 'Tuesday',
        'wednesday' => 'Wednesday',
        'thursday' => 'Thursday',
        'friday' => 'Friday',
        'saturday' => 'Saturday',
        'sunday' => 'Sunday',
    );

    $output = '<ul class="work-hours-list">';
    
    foreach ($days as $day_key => $day_name) {
        $hours = isset($data['timetable'][$day_key]) ? $data['timetable'][$day_key] : null;
        
        if (empty($hours)) {
            $output .= '<li><span class="day">' . $day_name . '</span>: <span class="hours">Closed</span></li>';
        } else {
            $time_slots = array();
            foreach ($hours as $slot) {
                if (isset($slot['open']) && isset($slot['close'])) {
                    $open = sprintf('%02d:%02d', $slot['open']['hour'], $slot['open']['minute']);
                    $close = sprintf('%02d:%02d', $slot['close']['hour'], $slot['close']['minute']);
                    $time_slots[] = $open . ' - ' . $close;
                }
            }
            $output .= '<li><span class="day">' . $day_name . '</span>: <span class="hours">' . implode(', ', $time_slots) . '</span></li>';
        }
    }
    
    $output .= '</ul>';
    
    return $output;
}

/**
 * Parse and format rating JSON
 */
function re_format_rating($json_data) {
    if (empty($json_data)) {
        return '';
    }

    $data = is_array($json_data) ? $json_data : json_decode($json_data, true);
    
    if (!$data || !isset($data['value'])) {
        return '';
    }

    $value = $data['value'];
    $max = isset($data['rating_max']) ? $data['rating_max'] : 5;
    $votes = isset($data['votes_count']) ? $data['votes_count'] : 0;

    $output = '<div class="rating-display">';
    $output .= '<div class="rating-stars">';
    for ($i = 1; $i <= 5; $i++) {
        $full_stars = floor($value);
        $has_half = ($value - $full_stars) >= 0.5;
        
        if ($i <= $full_stars) {
            $output .= '<i class="fas fa-star"></i>';
        } elseif ($i == $full_stars + 1 && $has_half) {
            $output .= '<i class="fas fa-star-half-alt"></i>';
        } else {
            $output .= '<i class="far fa-star"></i>';
        }
    }
    $output .= '</div>';
    $output .= '<span class="rating-value">' . number_format($value, 1) . '/' . $max . '</span>';
    $output .= '<span class="rating-votes">(' . $votes . ' reviews)</span>';
    $output .= '</div>';

    return $output;
}

/**
 * Parse and format place topics JSON
 */
function re_format_place_topics($json_data) {
    if (empty($json_data)) {
        return '';
    }

    $data = is_array($json_data) ? $json_data : json_decode($json_data, true);
    
    if (!$data || empty($data)) {
        return '';
    }

    $output = '<div class="place-topics">';
    foreach ($data as $topic => $count) {
        $topic_label = ucwords(str_replace('_', ' ', $topic));
        $output .= '<span class="topic-tag">' . esc_html($topic_label) . ' (' . intval($count) . ')</span>';
    }
    $output .= '</div>';

    return $output;
}

/**
 * Parse and format attributes JSON
 */
function re_format_attributes($json_data) {
    if (empty($json_data)) {
        return '';
    }

    $data = is_array($json_data) ? $json_data : json_decode($json_data, true);
    
    if (!$data) {
        return '';
    }

    $output = '<ul class="attributes-list">';
    
    if (isset($data['available_attributes']) && is_array($data['available_attributes'])) {
        foreach ($data['available_attributes'] as $category => $attributes) {
            if (is_array($attributes)) {
                foreach ($attributes as $attr) {
                    $label = re_format_attribute_name($attr);
                    $output .= '<li><i class="fas fa-check-circle"></i> ' . esc_html($label) . '</li>';
                }
            }
        }
    }
    
    $output .= '</ul>';

    return $output;
}

/**
 * Format attribute name from snake_case to readable format
 */
function re_format_attribute_name($name) {
    $name = str_replace('_', ' ', $name);
    $name = str_replace('has ', '', $name);
    return ucwords($name);
}

/**
 * Parse and format contact info JSON
 */
function re_format_contact_info($json_data) {
    if (empty($json_data)) {
        return '';
    }

    $data = is_array($json_data) ? $json_data : json_decode($json_data, true);
    
    if (!$data || !is_array($data)) {
        return '';
    }

    $output = '<ul class="contact-info-list">';
    
    foreach ($data as $contact) {
        if (!isset($contact['type']) || !isset($contact['value'])) {
            continue;
        }

        $type = $contact['type'];
        $value = $contact['value'];
        $icon = '';
        $label = '';

        switch ($type) {
            case 'telephone':
                $icon = 'fa-phone';
                $label = 'Phone';
                $value = '<a href="tel:' . esc_attr($value) . '">' . esc_html($value) . '</a>';
                break;
            case 'website':
                $icon = 'fa-globe';
                $label = 'Website';
                $value = '<a href="' . esc_url($value) . '" target="_blank">' . esc_html($value) . '</a>';
                break;
            case 'email':
                $icon = 'fa-envelope';
                $label = 'Email';
                $value = '<a href="mailto:' . esc_attr($value) . '">' . esc_html($value) . '</a>';
                break;
            case 'social':
                $icon = 'fa-share-alt';
                $label = 'Social';
                $value = '<a href="' . esc_url($value) . '" target="_blank">View Profile</a>';
                break;
            default:
                continue;
        }

        $output .= '<li><i class="fas ' . $icon . '"></i> <strong>' . $label . ':</strong> ' . $value . '</li>';
    }
    
    $output .= '</ul>';

    return $output;
}

/**
 * Get fallback image URL
 */
function re_get_fallback_image($title = null) {
    $site_title = RE_SITE_TITLE;
    if ($title) {
        return 'https://placehold.co/600x400?text=' . urlencode($title);
    }
    return 'https://placehold.co/600x400?text=' . urlencode($site_title);
}

/**
 * Custom excerpt length
 */
function re_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 're_excerpt_length', 999);

/**
 * Add taxonomy image support
 */
function re_taxonomy_image_init() {
    register_meta('term', 'taxonomy_image_id', array(
        'type' => 'integer',
        'description' => 'Taxonomy image attachment ID',
        'single' => true,
        'sanitize_callback' => 'absint',
    ));
}
add_action('init', 're_taxonomy_image_init');
