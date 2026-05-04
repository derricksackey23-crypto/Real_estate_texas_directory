/**
 * Real Estate Texas Directory - Main JavaScript
 */
jQuery(document).ready(function($) {
    'use strict';
    
    // Mobile Menu Toggle
    $('.menu-toggle').on('click', function() {
        $(this).attr('aria-expanded', function(i, attr) {
            return attr === 'true' ? 'false' : 'true';
        });
        $('.main-navigation').toggleClass('active');
    });
    
    // Close mobile menu when clicking a link
    $('.main-navigation a').on('click', function() {
        if ($('.main-navigation').hasClass('active')) {
            $('.main-navigation').removeClass('active');
            $('.menu-toggle').attr('aria-expanded', 'false');
        }
    });
    
    // AJAX Taxonomy Dropdown
    $('#first-level-taxonomy').on('change', function() {
        var parent_id = $(this).val();
        var $child_dropdown = $('#child-taxonomy');
        
        $child_dropdown.empty().append('<option value="">Select City</option>');
        
        if (parent_id && typeof ajax_object !== 'undefined') {
            $child_dropdown.prop('disabled', true).append('<option>Loading...</option>');
            
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
                    $child_dropdown.empty().append('<option value="">Select City</option>');
                    
                    if (response.success && response.data && response.data.length > 0) {
                        $.each(response.data, function(index, term) {
                            $child_dropdown.append(
                                $('<option>', {
                                    value: term.link,
                                    text: term.name
                                })
                            );
                        });
                        $child_dropdown.prop('disabled', false);
                    } else {
                        $child_dropdown.prop('disabled', true);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    $child_dropdown.empty().append('<option value="">Error loading cities</option>');
                    $child_dropdown.prop('disabled', true);
                }
            });
        } else {
            $child_dropdown.prop('disabled', true);
        }
    });
    
    $('#child-taxonomy').on('change', function() {
        var term_link = $(this).val();
        if (term_link) {
            window.location.href = term_link;
        }
    });
    
    // Smooth scroll for anchor links
    $('a[href^="#"]').on('click', function(e) {
        var target = $(this.getAttribute('href'));
        if (target.length) {
            e.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 80
            }, 300);
        }
    });
    
    // Lazy load images (basic implementation)
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        imageObserver.unobserve(img);
                    }
                }
            });
        });
        
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
});
