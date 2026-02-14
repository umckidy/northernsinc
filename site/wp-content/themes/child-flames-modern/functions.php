<?php
/**
 * Northerns Inc Modern child theme functions.
 */

function nsi_modern_enqueue_parent_style() {
	$parent = wp_get_theme( 'catch-flames' );
	$parent_version = $parent && $parent->exists() ? $parent->get( 'Version' ) : null;

	wp_enqueue_style(
		'nsi-parent-style',
		get_template_directory_uri() . '/style.css',
		array(),
		$parent_version
	);
}
add_action( 'wp_enqueue_scripts', 'nsi_modern_enqueue_parent_style', 5 );


function nsi_modern_enqueue_assets() {
	$child = wp_get_theme();
	$child_version = $child && $child->exists() ? $child->get( 'Version' ) : null;
	$modern_css_path = get_theme_file_path( 'assets/nsi-modern.css' );
	$modern_css_version = $child_version;
	if ( is_string( $modern_css_path ) && file_exists( $modern_css_path ) ) {
		$modern_css_version = (string) filemtime( $modern_css_path );
	}

	wp_enqueue_style(
		'nsi-modern-google-fonts',
		'https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400..700&family=Manrope:wght@400..800&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'nsi-modern',
		get_theme_file_uri( 'assets/nsi-modern.css' ),
		array( 'nsi-parent-style' ),
		$modern_css_version
	);
}
add_action( 'wp_enqueue_scripts', 'nsi_modern_enqueue_assets', 20 );


function nsi_modern_body_class( $classes ) {
	$classes[] = 'nsi-modern';
	return $classes;
}
add_filter( 'body_class', 'nsi_modern_body_class' );


/**
 * Disable the large featured header image in the modern theme.
 *
 * Catch Flames wires the header image via a hook that adds another hook.
 * Removing the initial hook keeps the header image markup out of the DOM.
 */
function nsi_modern_disable_featured_header_image() {
	remove_action( 'catchflames_before', 'catchflames_featured_header', 10 );
	remove_action( 'catchflames_after_header', 'catchflames_featured_overall_image', 10 );
}
add_action( 'after_setup_theme', 'nsi_modern_disable_featured_header_image', 20 );


/**
 * Override the parent theme's featured header wiring.
 *
 * Catch Flames defines this as pluggable. Providing a no-op implementation
 * prevents the parent from registering the featured header image output.
 */
function catchflames_featured_header() {
	// Intentionally blank.
}


function nsi_modern_brand_mark() {
	// Simple fishing lure mark (inline SVG) for header branding.
	return '<span class="nsi-mark" aria-hidden="true">'
		. '<svg viewBox="0 0 48 48" width="36" height="12" fill="none" xmlns="http://www.w3.org/2000/svg">'
		. '<path d="M24 6c3 0 6 2 6 6 0 5-3 8-6 11-3-3-6-6-6-11 0-4 3-6 6-6Z" stroke="currentColor" stroke-width="2.6" stroke-linejoin="round"/>'
		. '<path d="M24 23v9" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"/>'
		. '<path d="M24 32c-6 0-10 4-10 8 4-2 7-2 10 1 3-3 6-3 10-1 0-4-4-8-10-8Z" stroke="currentColor" stroke-width="2.6" stroke-linejoin="round"/>'
		. '<path d="M30 9h6" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"/>'
		. '</svg>'
		. '</span>';
}


function catchflames_headerdetails() {
	echo '<div id="logo-wrap" class="clearfix">';
	echo '<div class="nsi-brand">';
	echo nsi_modern_brand_mark();
	echo catchflames_site_details();
	echo '</div>';
	echo '</div><!-- #logo-wrap -->';
}
