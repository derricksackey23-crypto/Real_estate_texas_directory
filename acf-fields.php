<?php
/**
 * ACF Fields Configuration for Real Estate Directory
 * Registers fields via PHP using acf_add_local_field_group()
 */

if (!defined('ABSPATH')) exit;

add_action('acf/init', 'retex_acf_init');

function retex_acf_init() {
    if (!function_exists('acf_add_local_field_group')) return;
    
    $customPostKey = defined('RETEX_DIR_CPT') ? RETEX_DIR_CPT : 'real_estate';
    
    acf_add_local_field_group(array(
        'key' => 'group_' . $customPostKey . '_fields',
        'title' => 'Real Estate Listing Details',
        'fields' => array(
            array(
                'key' => 'field_fbd_title',
                'label' => 'Title',
                'name' => 'title',
                'type' => 'text',
                'instructions' => 'Listing title',
            ),
            array(
                'key' => 'field_fbd_description',
                'label' => 'Description',
                'name' => 'description',
                'type' => 'textarea',
                'instructions' => 'Detailed description of the property',
            ),
            array(
                'key' => 'field_fbd_category',
                'label' => 'Category',
                'name' => 'category',
                'type' => 'text',
            ),
            array(
                'key' => 'field_fbd_feature_id',
                'label' => 'Feature ID',
                'name' => 'feature_id',
                'type' => 'text',
            ),
            array(
                'key' => 'field_fbd_address',
                'label' => 'Address',
                'name' => 'address',
                'type' => 'text',
            ),
            array(
                'key' => 'field_fbd_address_info_borough',
                'label' => 'Borough',
                'name' => 'address_info_borough',
                'type' => 'text',
            ),
            array(
                'key' => 'field_fbd_address_info_address',
                'label' => 'Street Address',
                'name' => 'address_info_address',
                'type' => 'text',
            ),
            array(
                'key' => 'field_fbd_address_info_city',
                'label' => 'City',
                'name' => 'address_info_city',
                'type' => 'text',
            ),
            array(
                'key' => 'field_fbd_address_info_zip',
                'label' => 'ZIP Code',
                'name' => 'address_info_zip',
                'type' => 'text',
            ),
            array(
                'key' => 'field_fbd_address_info_region',
                'label' => 'Region/State',
                'name' => 'address_info_region',
                'type' => 'text',
            ),
            array(
                'key' => 'field_fbd_address_info_country_code',
                'label' => 'Country Code',
                'name' => 'address_info_country_code',
                'type' => 'text',
            ),
            array(
                'key' => 'field_fbd_place_id',
                'label' => 'Place ID',
                'name' => 'place_id',
                'type' => 'text',
            ),
            array(
                'key' => 'field_fbd_phone',
                'label' => 'Phone',
                'name' => 'phone',
                'type' => 'text',
            ),
            array(
                'key' => 'field_fbd_url',
                'label' => 'Website URL',
                'name' => 'url',
                'type' => 'url',
            ),
            array(
                'key' => 'field_fbd_logo',
                'label' => 'Logo URL',
                'name' => 'logo',
                'type' => 'text',
            ),
            array(
                'key' => 'field_fbd_main_image',
                'label' => 'Main Image URL',
                'name' => 'main_image',
                'type' => 'text',
            ),
            array(
                'key' => 'field_fbd_latitude',
                'label' => 'Latitude',
                'name' => 'latitude',
                'type' => 'text',
            ),
            array(
                'key' => 'field_fbd_longitude',
                'label' => 'Longitude',
                'name' => 'longitude',
                'type' => 'text',
            ),
            array(
                'key' => 'field_fbd_attributes',
                'label' => 'Attributes (JSON)',
                'name' => 'attributes',
                'type' => 'textarea',
                'instructions' => 'JSON string: available_attributes, unavailable_attributes',
            ),
            array(
                'key' => 'field_fbd_place_topics',
                'label' => 'Place Topics (JSON)',
                'name' => 'place_topics',
                'type' => 'textarea',
                'instructions' => 'JSON string: topic counts',
            ),
            array(
                'key' => 'field_fbd_rating',
                'label' => 'Rating (JSON)',
                'name' => 'rating',
                'type' => 'textarea',
                'instructions' => 'JSON: rating_type, value, votes_count, rating_max',
            ),
            array(
                'key' => 'field_fbd_hotel_rating',
                'label' => 'Hotel Rating',
                'name' => 'hotel_rating',
                'type' => 'text',
            ),
            array(
                'key' => 'field_fbd_price_level',
                'label' => 'Price Level',
                'name' => 'price_level',
                'type' => 'text',
            ),
            array(
                'key' => 'field_fbd_people_also_search',
                'label' => 'People Also Search (JSON)',
                'name' => 'people_also_search',
                'type' => 'textarea',
            ),
            array(
                'key' => 'field_fbd_work_time',
                'label' => 'Work Hours (JSON)',
                'name' => 'work_time',
                'type' => 'textarea',
                'instructions' => 'JSON: work_hours.timetable structure',
            ),
            array(
                'key' => 'field_fbd_popular_times',
                'label' => 'Popular Times (JSON)',
                'name' => 'popular_times',
                'type' => 'textarea',
            ),
            array(
                'key' => 'field_fbd_contact_info',
                'label' => 'Contact Info (JSON)',
                'name' => 'contact_info',
                'type' => 'textarea',
                'instructions' => 'JSON array: type, value, source',
            ),
            array(
                'key' => 'field_fbd_check_url',
                'label' => 'Check/Map URL',
                'name' => 'check_url',
                'type' => 'url',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => $customPostKey,
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
        'show_in_rest' => true,
    ));
}
