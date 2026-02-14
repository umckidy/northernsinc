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
	// Simple fishing lure silhouette mark (inline SVG) for header branding.
	return '<span class="nsi-mark" aria-hidden="true">'
		. '<svg viewBox="0 0 64 64" role="img" focusable="false" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">'
		. '<path fill="currentColor" d="M45.6 15.1c-8.3-3.2-17.2-2.9-26.4.8-1 .4-1.5 1.6-1.1 2.6.4 1 1.6 1.5 2.6 1.1 8.2-3.2 16-3.5 23.2-.9.9.3 1.8.8 2.6 1.4l-4 2.7c-.9.6-1.1 1.8-.5 2.7.4.6 1 .9 1.6.9.4 0 .8-.1 1.1-.3l6.6-4.5c.5-.4.9-1 .9-1.7 0-.7-.3-1.3-.9-1.7-1.7-1.2-3.6-2.2-5.7-3.1Z"/>'
		. '<path fill="currentColor" d="M19.2 24.8c-.8-.7-2.1-.6-2.8.2-2.9 3.2-4.4 6.6-4.4 10.3 0 3.6 1.4 6.9 4.1 9.9.7.8 2 .9 2.8.2.8-.7.9-2 .2-2.8-2.1-2.3-3.1-4.7-3.1-7.3 0-2.6 1.1-5.2 3.4-7.7.7-.8.6-2.1-.2-2.8Z"/>'
		. '<path fill="currentColor" d="M54 33.2c-1.1-2.4-2.7-4.6-4.7-6.5-5.3-5-12.2-7.4-20.6-7.4-1.1 0-2 .9-2 2s.9 2 2 2c7.4 0 13.3 2.1 17.9 6.4 2.2 2.1 3.8 4.6 4.7 7.5-1.8 5.9-6.4 9.7-14.2 11.3-1.1.2-1.8 1.3-1.5 2.4.2.9 1 1.6 2 1.6h.4c9.4-1.9 15.3-6.8 17.7-14.7.2-.5.2-1.1 0-1.6Z"/>'
		. '<path fill="currentColor" d="M26.8 50.6c-.8 0-1.5-.4-1.8-1.2-1.3-3.2-4.2-4.8-8.8-4.8-1.1 0-2-.9-2-2s.9-2 2-2c5.7 0 9.8 2.4 12.4 7.1.4 1 0 2.2-1 2.7-.3.1-.6.2-.8.2Z"/>'
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
