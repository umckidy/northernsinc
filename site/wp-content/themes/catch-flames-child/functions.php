<?php
/**
 * Catch Flames Child functions and definitions
 */

function catch_flames_child_enqueue_styles() {
    // Parent style
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    
    // Google Fonts
    wp_enqueue_style( 'modern-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap', array(), null );
    
    // Child style
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'parent-style' ), wp_get_theme()->get('Version') );
    
    // Header Scroll Script
    wp_add_inline_script( 'child-style', "
        document.addEventListener('DOMContentLoaded', function() {
            const header = document.querySelector('.site-header');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 20) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
            });
        });
    " );
}
add_action( 'wp_enqueue_scripts', 'catch_flames_child_enqueue_styles' );

// Register a secondary navigation location for the modernized footer
function northern_inc_register_menus() {
    register_nav_menus( array(
        'footer-menu' => __( 'Footer Menu', 'catch-flames-child' ),
    ) );
}
add_action( 'init', 'northern_inc_register_menus' );
