<?php
/**
 * Core theme supports, menus, image sizes.
 *
 * @package MyBedroomFun_Archive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register theme supports and navigation menus.
 */
function mbf_theme_setup() {
	load_theme_textdomain( 'mybedroomfun-archive', MBF_THEME_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'custom-logo', array(
		'height'      => 60,
		'width'       => 220,
		'flex-height' => true,
		'flex-width'  => true,
	) );

	set_post_thumbnail_size( 640, 640, true );
	add_image_size( 'mbf-card', 480, 480, true );
	add_image_size( 'mbf-banner', 1200, 400, true );

	register_nav_menus( array(
		'primary'  => __( 'Primary Navigation', 'mybedroomfun-archive' ),
		'utility'  => __( 'Utility Links', 'mybedroomfun-archive' ),
		'footer-1' => __( 'Footer Column 1', 'mybedroomfun-archive' ),
		'footer-2' => __( 'Footer Column 2', 'mybedroomfun-archive' ),
		'footer-3' => __( 'Footer Column 3', 'mybedroomfun-archive' ),
	) );
}
add_action( 'after_setup_theme', 'mbf_theme_setup' );

/**
 * Register the shop sidebar widget area used on archive/category templates.
 */
function mbf_register_sidebar() {
	register_sidebar( array(
		'name'          => __( 'Shop Sidebar', 'mybedroomfun-archive' ),
		'id'            => 'shop-sidebar',
		'before_widget' => '<div class="mbf-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="mbf-widget__title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'mbf_register_sidebar' );

/**
 * Constrain default content width for embeds/oEmbed.
 */
function mbf_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'mbf_content_width', 1280 );
}
add_action( 'after_setup_theme', 'mbf_content_width', 0 );
