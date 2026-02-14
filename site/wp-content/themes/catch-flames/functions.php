<?php
/**
 * Catch Flames functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * The first function, catchflames_setup(), sets up the theme by registering support
 * for various features in WordPress, such as post thumbnails, navigation menus, and the like.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * We can remove the parent theme's hook only after it is attached, which means we need to
 * wait until setting up the child theme:
 *
 * <code>
 * add_action( 'after_setup_theme', 'my_child_theme_setup' );
 * function my_child_theme_setup() {
 *     // We are providing our own filter for excerpt_length (or using the unfiltered value)
 *     remove_filter( 'excerpt_length', 'catchflames_excerpt_length' );
 *     ...
 * }
 * </code>
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package Catch Themes
 * @subpackage Catch Flames
 * @since Catch Flames 1.0
 */


if ( ! function_exists( 'catchflames_content_width' ) ) :
	/**
	 * Set the content width in pixels, based on the theme's design and stylesheet.
	 *
	 * Priority 0 to make it available to lower priority callbacks.
	 *
	 * @global int $content_width
	 */
	function catchflames_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'catchflames_content_width', 600 );
	}
endif;
add_action( 'after_setup_theme', 'catchflames_content_width', 0 );


if ( ! function_exists( 'catchflames_template_redirect' ) ) :
	/**
	 * Set the content width in pixels, based on the theme's design and stylesheet for different value other than the default one
	 *
	 * @global int $content_width
	 */
	function catchflames_template_redirect() {
	    $layout  = catchflames_get_theme_layout();

		if ( 'right-sidebar' == $layout || 'left-sidebar' == $layout || 'no-sidebar' == $layout ) {
			$GLOBALS['content_width'] = 710;
		}
	}
endif;
add_action( 'template_redirect', 'catchflames_template_redirect' );


/**
 * Tell WordPress to run catchflames_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'catchflames_setup' );


if ( ! function_exists( 'catchflames_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override catchflames_setup() in a child theme, add your own catchflames_setup to your child theme's
 * functions.php file.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To style the visual editor.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,custom headers and backgrounds.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Catch Flames 1.0
 */
function catchflames_setup() {

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on Catch Flames, use a find and replace
	 * to change 'catch-flames' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'catch-flames', get_template_directory() . '/languages' );

	/**
     * Add callback for custom TinyMCE editor stylesheets. (editor-style.css)
     * @see http://codex.wordpress.org/Function_Reference/add_editor_style
     */
	add_editor_style();

	// Add default posts and comments RSS feed links to <head>.
	add_theme_support( 'automatic-feed-links' );

	// Add support for a variety of post formats
	add_theme_support( 'post-formats', array( 'aside', 'link', 'gallery', 'status', 'quote', 'image', 'chat' ) );

	/*
	* Let WordPress manage the document title.
	* By adding theme support, we declare that this theme does not use a
	* hard-coded <title> tag in the document head, and expect WordPress to
	* provide it for us.
	*/
	add_theme_support( 'title-tag' );

	// Load up theme options defaults
	require( get_template_directory() . '/inc/panel/catchflames-themeoptions-defaults.php' );

	// Load up our Catch Flames metabox
	require( get_template_directory() . '/inc/catchflames-metabox.php' );

	// Load up our Catch Flames Functions
	require( get_template_directory() . '/inc/catchflames-functions.php' );

	// Load up our Catch Flames Slider Function
	require( get_template_directory() . '/inc/catchflames-slider.php' );

	// Register Sidebar and Widget.
	require( get_template_directory() . '/inc/catchflames-widgets.php' );

	// Load up our Catch Flames Menus
	require( get_template_directory() . '/inc/catchflames-menus.php' );

	// Load up our Catch Flames Menus
	require( get_template_directory() . '/inc/catchflames-featured-content.php' );


	/**
     * This feature enables Jetpack plugin Infinite Scroll
     */
    add_theme_support( 'infinite-scroll', array(
		'type'           => 'click',
        'container'      => 'content',
        'footer_widgets' => array( 'sidebar-2', 'sidebar-3', 'sidebar-4' ),
        'footer'         => 'page',
    ) );

	/**
     * This feature enables custom-menus support for a theme.
     * @see http://codex.wordpress.org/Function_Reference/register_nav_menus
     */
	register_nav_menus(array(
		'top' 		=> __( 'Fixed Header Top Menu', 'catch-flames' ),
		'primary' 	=> __( 'Primary Menu', 'catch-flames' ),
	) );

	// Add support for custom backgrounds
	add_theme_support( 'custom-background' );

	/**
     * This feature enables post-thumbnail support for a theme.
     * @see http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
     */
	add_theme_support( 'post-thumbnails' );


	//Featured Posts for Full Width
	add_image_size( 'featured-slider-full', 1600, 533, true ); // 1:3 ratio Used for featured posts if a large-feature doesn't exist

	add_image_size( 'featured', 750, 470, true ); // 4:3 Used for featured posts if a large-feature doesn't exist

	add_image_size( 'featured-three', 640, 401, true ); // 1.6 Used for featured posts if a large-feature doesn't exist

	add_image_size( 'featured-content', 350, 263, true ); // used in Featured Content Options Ratio 4:3

	if ( function_exists('catchflames_woocommerce' ) ) {
 		catchflames_woocommerce();
    }

    //@remove Remove check when WordPress 4.8 is released
	if ( function_exists( 'has_custom_logo' ) ) {
		/**
		* Setup Custom Logo Support for theme
		* Supported from WordPress version 4.5 onwards
		* More Info: https://make.wordpress.org/core/2016/03/10/custom-logo/
		*/
		add_theme_support( 'custom-logo' );
	}

	// Add support for Block Styles.
	add_theme_support( 'wp-block-styles' );

	// Add support for editor styles.
	add_theme_support( 'editor-styles' );

	// Add support for full and wide align images.
	add_theme_support( 'align-wide' );

	// Add support for responsive embeds.
	add_theme_support( 'responsive-embeds' );

	// Add custom editor font sizes.
	add_theme_support(
		'editor-font-sizes',
		array(
			array(
				'name'      => esc_html__( 'Small', 'catch-flames' ),
				'shortName' => esc_html__( 'S', 'catch-flames' ),
				'size'      => 13,
				'slug'      => 'small',
			),
			array(
				'name'      => esc_html__( 'Normal', 'catch-flames' ),
				'shortName' => esc_html__( 'M', 'catch-flames' ),
				'size'      => 16,
				'slug'      => 'normal',
			),
			array(
				'name'      => esc_html__( 'Large', 'catch-flames' ),
				'shortName' => esc_html__( 'L', 'catch-flames' ),
				'size'      => 42,
				'slug'      => 'large',
			),
			array(
				'name'      => esc_html__( 'Huge', 'catch-flames' ),
				'shortName' => esc_html__( 'XL', 'catch-flames' ),
				'size'      => 56,
				'slug'      => 'huge',
			),
		)
	);

	// Add support for custom color scheme.
	add_theme_support( 'editor-color-palette', array(
		array(
			'name'  => esc_html__( 'White', 'catch-flames' ),
			'slug'  => 'white',
			'color' => '#ffffff',
		),
		array(
			'name'  => esc_html__( 'Black', 'catch-flames' ),
			'slug'  => 'black',
			'color' => '#111111',
		),
		array(
			'name'  => esc_html__( 'Gray', 'catch-flames' ),
			'slug'  => 'gray',
			'color' => '#f4f4f4',
		),
		array(
			'name'  => esc_html__( 'Yellow', 'catch-flames' ),
			'slug'  => 'yellow',
			'color' => '#e5ae4a',
		),
		array(
			'name'  => esc_html__( 'Blue', 'catch-flames' ),
			'slug'  => 'blue',
			'color' => '#1b8be0',
		),
	) );
}
endif; // catchflames_setup


if ( ! function_exists( 'catchflames_get_theme_layout' ) ) :
	/**
	 * Returns Theme Layout prioritizing the meta box layouts
	 *
	 * @uses  get_options
	 *
	 * @action wp_head
	 *
	 * @since Catch Flames 2.9
	 */
	function catchflames_get_theme_layout() {
		$id = '';

		global $post, $wp_query;

	    // Front page displays in Reading Settings
		$page_on_front  = get_option('page_on_front') ;
		$page_for_posts = get_option('page_for_posts');

		// Get Page ID outside Loop
		$page_id = $wp_query->get_queried_object_id();

		// Blog Page or Front Page setting in Reading Settings
		if ( $page_id == $page_for_posts || $page_id == $page_on_front ) {
	        $id = $page_id;
	    }
	    elseif ( is_singular() ) {
	 		if ( is_attachment() ) {
				$id = $post->post_parent;
			}
			else {
				$id = $post->ID;
			}
		}

		//Get appropriate metabox value of layout
		if ( '' != $id ) {
			$layout = get_post_meta( $id, 'catchflames-sidebarlayout', true );
		}
		else {
			$layout = 'default';
		}

		//Load options data
		global $catchflames_options_settings;
   		$options = $catchflames_options_settings;

   		//check empty and load default
		if ( empty( $layout ) || 'default' == $layout ) {
			$layout = $options['sidebar_layout'];
		}

		return $layout;
	}
endif; //catchflames_get_theme_layout


/**
 * Adds support for a custom header image.
 */
require( get_template_directory() . '/inc/catchflames-custom-header.php' );


/**
 * Adds support for WooCommerce Plugin
 */
if ( class_exists( 'WooCommerce' ) ) {
	add_theme_support( 'woocommerce' );
    require( get_template_directory() . '/inc/catchflames-woocommerce.php' );
}


/**
 * Adds support for mqtranslate and qTranslate Plugin
 */
if ( in_array( 'qtranslate/qtranslate.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ||
in_array( 'mqtranslate/mqtranslate.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    require( get_template_directory() . '/inc/catchflames-qtranslate.php' );
}

/**
 * Adds support for WPML Plugin and Polyland
 */
if ( defined( 'ICL_SITEPRESS_VERSION' ) || class_exists( 'Polylang' ) ) {
	require( get_template_directory() . '/inc/catchflames-wpml.php' );
}


/**
  * Filters the_category() to output html 5 valid rel tag
  *
  * @param string $text
  * @return string
  */
function catchflames_html_validate( $text ) {
	$string = 'rel="tag"';
	$replace = 'rel="category"';
	$text = str_replace( $replace, $string, $text );

	return $text;
}
add_filter( 'the_category', 'catchflames_html_validate' );
add_filter( 'wp_list_categories', 'catchflames_html_validate' );

/**
 * Migrate Logo to New WordPress core Custom Logo
 *
 * Runs if version number saved in theme_mod "logo_version" doesn't match current theme version.
 */
function catchflames_logo_migrate() {
	$ver = get_theme_mod( 'logo_version', false );

	// Return if update has already been run
	if ( version_compare( $ver, '2.9' ) >= 0 ) {
		return;
	}

	/**
	 * Get Theme Options Values
	 */
	global $catchflames_options_settings;
   	$options = $catchflames_options_settings;

   	// If a logo has been set previously, update to use logo feature introduced in WordPress 4.5
	if ( function_exists( 'the_custom_logo' ) ) {
		if ( isset( $options['featured_logo_header'] ) && '' != $options['featured_logo_header'] ) {
			// Since previous logo was stored a URL, convert it to an attachment ID
			$logo = attachment_url_to_postid( $options['featured_logo_header'] );

			if ( is_int( $logo ) ) {
				set_theme_mod( 'custom_logo', $logo );
			}
		}

		// Delete transients after migration
		delete_transient( 'catchflames_logo' );

  		// Update to match logo_version so that script is not executed continously
		set_theme_mod( 'logo_version', '2.9' );
	}
}
add_action( 'after_setup_theme', 'catchflames_logo_migrate' );


/**
 * Migrate Custom CSS to WordPress core Custom CSS
 *
 * Runs if version number saved in theme_mod "custom_css_version" doesn't match current theme version.
 */
function catchflames_custom_css_migrate(){
	$ver = get_theme_mod( 'custom_css_version', false );

	// Return if update has already been run
	if ( version_compare( $ver, '4.7' ) >= 0 ) {
		return;
	}

	if ( function_exists( 'wp_update_custom_css_post' ) ) {
	    // Migrate any existing theme CSS to the core option added in WordPress 4.7.

	    /**
		 * Get Theme Options Values
		 */
		global $catchflames_options_settings;
	   	$options = $catchflames_options_settings;

	    if ( '' != $options['custom_css'] ) {
			$core_css = wp_get_custom_css(); // Preserve any CSS already added to the core option.
			$return   = wp_update_custom_css_post( $core_css . $options['custom_css'] );

	        if ( ! is_wp_error( $return ) ) {
	            // Remove the old theme_mod, so that the CSS is stored in only one place moving forward.
	            unset( $options['custom_css'] );
	            update_option( 'catchflames_options', $options );

	            // Update to match custom_css_version so that script is not executed continously
				set_theme_mod( 'custom_css_version', '4.7' );
	        }
	    }
	}
}
add_action( 'after_setup_theme', 'catchflames_custom_css_migrate' );

//Include customizer options
require trailingslashit( get_template_directory() ) . 'inc/panel/customizer/customizer.php';
