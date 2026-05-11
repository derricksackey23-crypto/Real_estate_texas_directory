/**
 * Real Estate Texas Directory - Main JavaScript
 */

jQuery(document).ready(function($) {
    'use strict';

    // Mobile Menu Toggle
    $('.mobile-menu-toggle').on('click', function() {
        $('.main-navigation').toggleClass('mobile-active');
        $(this).find('i').toggleClass('fa-bars fa-times');
    });

    // AJAX Dropdown Functionality for Taxonomy Search
    $('#first-level-taxonomy').on('change', function() {
        var parent_id = $(this).val();
        var $child_dropdown = $('#child-taxonomy');
        
        $child_dropdown.empty().append('<option value=""><?php _e('Select City', 'real-estate-texas'); ?></option>');

        if (parent_id) {
            $.ajax({
                url: ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'get_child_taxonomies',
                    parent_id: parent_id,
                    taxonomy: 'state',
                    nonce: ajax_object.nonce
                },
                success: function(response) {
                    if (response && response.length > 0) {
                        $.each(response, function(index, term) {
                            $child_dropdown.append('<option value="' + term.link + '">' + term.name + '</option>');
                        });
                        $child_dropdown.prop('disabled', false);
                    } else {
                        $child_dropdown.prop('disabled', true);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    $child_dropdown.prop('disabled', true);
                }
            });
        } else {
            $child_dropdown.prop('disabled', true);
        }
    });

    // Redirect on child taxonomy selection
    $('#child-taxonomy').on('change', function() {
        var term_link = $(this).val();
        if (term_link) {
            window.location.href = term_link;
        }
    });

    // Smooth Scroll for Anchor Links
    $('a[href^="#"]').on('click', function(e) {
        var target = $(this.getAttribute('href'));
        if (target.length) {
            e.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 100
            }, 800);
        }
    });

    // Lazy Loading Images
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        observer.unobserve(img);
                    }
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }

    // Form Validation
    $('#taxonomy-search-form').on('submit', function(e) {
        var childValue = $('#child-taxonomy').val();
        if (!childValue) {
            e.preventDefault();
            alert('<?php _e('Please select a city', 'real-estate-texas'); ?>');
            return false;
        }
    });

    // Parallax Effect for Hero Section
    $(window).on('scroll', function() {
        var scrolled = $(window).scrollTop();
        var $hero = $('.hero-section');
        if ($hero.length) {
            $hero.css('background-position-y', (scrolled * 0.5) + 'px');
        }
    });

    // Add active class to current menu item
    var currentUrl = window.location.href;
    $('.main-navigation a').each(function() {
        if (this.href === currentUrl) {
            $(this).parent().addClass('current-menu-item');
        }
    });
});
