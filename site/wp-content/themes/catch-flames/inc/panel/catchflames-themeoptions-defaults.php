<?php
/**
 * @package Catch Themes
 * @subpackage Catch Flames
 * @since Catch Flames 1.0
 */

/**
 * Set the default values for all the settings. If no user-defined values
 * is available for any setting, these defaults will be used.
 */
global $catchflames_options_defaults;
$catchflames_options_defaults = array(
	'featured_logo_header'         => esc_url( get_template_directory_uri() ).'/images/logo.png',
	'remove_header_logo'           => '1',
	'disable_header_menu'          => '0',
	'search_display_text'          => esc_attr__( 'Search', 'catch-flames' ),
	'enable_header_top'            => '0',
	'disable_top_menu_logo'        => '1',
	'top_menu_logo'                => esc_url( get_template_directory_uri() ).'/images/fixed-logo.png',
	'enable_featured_header_image' => 'excludehome',
	'featured_header_image_alt'    => esc_attr( get_bloginfo( 'name', 'display' ) ),
	'featured_header_image_url'    => esc_url( home_url( '/' ) ),
	'reset_header_image'           => '2',
	'color_scheme'                 => 'light',
	//Promotion Headline Options
	'promotion_headline_option'     => 'disabled',
	'promotion_headline_left_width' => '80',
	'promotion_headline'            => esc_html__( 'Catch Flames is a Responsive WordPress Theme', 'catch-flames' ),
	'promotion_subheadline'         => esc_html__( 'This is promotion headline. You can edit this from Appearance -> Customize -> Theme Options -> Promotion Headline Options', 'catch-flames' ),
	'promotion_headline_button'     => esc_html__( 'Learn More', 'catch-flames' ),
	'promotion_headline_url'        => '#',
	'promotion_headline_target'     => 1,

	'sidebar_layout'       => 'three-columns',
	'content_layout'       => 'excerpt-border',
	'reset_sidebar_layout' => '2',
	'front_page_category'  => '0',
	'exclude_slider_post'  => '0',
	'slider_qty'           => 4,
	'select_slider_type'   => 'demo-slider',
	'enable_slider'        => 'enable-slider-homepage',
	'slider_category'      => array(),
	'featured_slider_page' => array(),
	'transition_effect'    => 'fade',
	'transition_delay'     => 4,
	'transition_duration'  => 1,
	'image_loader'         => 'true',

 //Featured Content Options
	'featured_content_option'      => 'disabled',
	'featured_content_layout'      => 'layout-four',
	'featured_content_position'    => 0,
	'featured_content_headline'    => '',
	'featured_content_subheadline' => '',
	'featured_content_type'        => 'demo-featured-content',
	'featured_content_number'      => '4',
	'featured_content_page'        => array(),
	'featured_content_show'        => 'excerpt',

	'disable_footer_social' => 0,
	'social_facebook'       => '',
	'social_twitter'        => '',
	'social_googleplus'     => '',
	'social_pinterest'      => '',
	'social_youtube'        => '',
	'social_vimeo'          => '',
	'social_linkedin'       => '',
	'social_aim'            => '',
	'social_myspace'        => '',
	'social_flickr'         => '',
	'social_tumblr'         => '',
	'social_deviantart'     => '',
	'social_dribbble'       => '',
	'social_wordpress'      => '',
	'social_rss'            => '',
	'social_slideshare'     => '',
	'social_instagram'      => '',
	'social_skype'          => '',
	'social_soundcloud'     => '',
	'social_email'          => '',
	'social_contact'        => '',
	'social_xing'           => '',
	'social_meetup'         => '',
	'social_goodreads'      => '',
	'social_github'         => '',
	'social_vk'      		=> '',
	'social_spotify'        => '',
	'social_thread'        	=> '',
	'social_bluesky'        => '',
	'social_tiktok'         => '',
	'enable_specificfeeds'  => '0',
	'custom_css'            => '',
	'disable_scrollup'      => '0',
	'more_tag_text'         => esc_attr__( 'Continue Reading', 'catch-flames' ) . ' &rarr;',
	'excerpt_length'        => 30,
	'reset_more_tag'        => '2',

	'enable_fitvid' => '0',
);
global $catchflames_options_settings;
$catchflames_options_settings = catchflames_options_set_defaults( $catchflames_options_defaults );

function catchflames_options_set_defaults( $catchflames_options_defaults ) {
	$catchflames_options_settings = array_merge( $catchflames_options_defaults, (array) get_option( 'catchflames_options', array() ) );
	return $catchflames_options_settings;
}

/**
 * Returns an array of color schemes registered for catch-flames.
 *
 * @since Catch Flames 2.7
 */
function catchflames_color_schemes() {
	$options = array(
		'light' => __( 'Light', 'catch-flames' ),
		'dark'  => __( 'Dark', 'catch-flames' ),
	);

	return apply_filters( 'catchflames_color_schemes', $options );
}

/**
 * Returns an array of enable header image options
 *
 * @since Catch Flames 2.7
 */
function catchflames_enable_header_featured_image_options() {
	$options = array(
		'homepage'    => __( 'Homepage', 'catch-flames' ),
		'excludehome' => __( 'Excluding Homepage', 'catch-flames' ),
		'allpage'     => __( 'Entire Site', 'catch-flames' ),
		'postpage'    => __( 'Entire Site, Page/Post Featured Image', 'catch-flames' ),
		'pagespostes' => __( 'Pages & Posts', 'catch-flames' ),
		'disable'     => __( 'Disable', 'catch-flames' ),
	);

	return apply_filters( 'catchflames_enable_header_featured_image_options', $options );
}

/**
 * Returns an array of sidebar layout options
 *
 * @since Catch Flames 2.7
 */
function catchflames_sidebar_layout_options() {
	$options = array(
		'right-sidebar' => __( 'Right Sidebar', 'catch-flames' ),
		'left-sidebar'  => __( 'Left Sidebar', 'catch-flames' ),
		'no-sidebar'    => __( 'No Sidebar', 'catch-flames' ),
		'three-columns' => __( 'Three Columns', 'catch-flames' ),
	);

	return apply_filters( 'catchflames_sidebar_layout_options', $options );
}


/**
 * Returns an array of content layout options
 *
 * @since Catch Flames 2.7
 */
function catchflames_content_layout_options() {
	$options = array(
		'full'           => __( 'Full Content Display', 'catch-flames' ),
		'excerpt-border' => __( 'Excerpt/Blog Display', 'catch-flames' ),
	);

	return apply_filters( 'catchflames_content_layout_options', $options );
}

/**
 * Returns an array of slider enable options
 *
 * @since Catch Flames 2.7
 */
function catchflames_enable_slider_options() {
	$options = array(
		'enable-slider-homepage' => __( 'Homepage', 'catch-flames' ),
		'enable-slider-allpage'  => __( 'Entire Site', 'catch-flames' ),
		'disable-slider'         => __( 'Disable', 'catch-flames' ),
	);

	return apply_filters( 'catchflames_enable_slider_options', $options );
}

/**
 * Returns an array of slider types
 *
 * @since Catch Flames 2.7
 */
function catchflames_slider_types() {
	$options = array(
		'demo-slider' 		=> __( 'Demo', 'catch-flames' ),
		'page-slider' 		=> __( 'Page', 'catch-flames' ),
	);

	return apply_filters( 'catchflames_slider_types', $options );
}

/**
 * Returns an array of feature content types registered
 *
 * @since Catch Flames 3.0
 */
function catchflames_featured_content_types() {
	$options = array(
		'demo-featured-content'     => __( 'Demo', 'catch-flames' ),
		'featured-page-content'     => __( 'Page', 'catch-flames' ),
	);

	return apply_filters( 'catchflames_featured_content_types', $options );
}

/**
 * Returns an array of featured content enable options
 *
 * @since Catch Flames 3.0
 */
function catchflames_featured_content_options() {
	$options = array(
		'homepage'    => esc_html__( 'Homepage / Frontpage', 'catch-flames' ),
		'entire-site' => esc_html__( 'Entire Site', 'catch-flames' ),
		'disabled'    => esc_html__( 'Disabled', 'catch-flames' )
	);

	return apply_filters( 'catchflames_featured_content_options', $options );
}

/**
 * Returns an array of featured content show registered
 *
 * @since Catch Flames 3.0
 */
function catchflames_featured_content_show() {
	$options = array(
		'excerpt'      => __( 'Show Excerpt', 'catch-flames' ),
		'full-content' => __( 'Show Full Content', 'catch-flames' ),
		'hide-content' => __( 'Hide Content', 'catch-flames' ),
	);

	return apply_filters( 'catchflames_featured_content_show', $options );
}


/**
 * Returns an array of featured content options registered
 *
 * @since Catch Flames 3.0
 */
function catchflames_featured_content_layout_options() {
	$options = array(
		'layout-two'   => esc_html__( '2 columns', 'catch-flames' ),
		'layout-three' => esc_html__( '3 columns', 'catch-flames' ),
		'layout-four'  => esc_html__( '4 columns', 'catch-flames' ),
	);

	return apply_filters( 'catchflames_featured_content_layout_options', $options );
}

/**
 * Returns an array of slider image loader types
 *
 * @since Catch Flames 3.0
 */
function catchflames_image_loader_options() {
	$options = array(
		'true' => esc_html__( 'True', 'catch-flames' ),
		'wait' => esc_html__( 'Wait', 'catch-flames' ),
		'false'=> esc_html__( 'False', 'catch-flames' ),
	);

	return apply_filters( 'catchflames_image_loader_options', $options );
}
