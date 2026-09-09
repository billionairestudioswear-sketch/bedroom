<?php
/**
 * Customizer controls for the homepage hero slider (5 slots) and the
 * 3 promotional homepage banners. Each banner's destination is a
 * dropdown of the site's *existing* product categories only -- there
 * is no free-text URL field and no automatic selection, so a banner
 * can never point anywhere but a real category the admin explicitly
 * picked.
 *
 * @package MyBedroomFun_Archive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the hero slider Customizer section: one image control per
 * slot, slide 1 through 5. Slide 1 defaults to the recovered archive
 * image; slides 2-5 default to empty, which hides that slot entirely
 * (see mbf_get_hero_slides() in inc/template-tags.php).
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 */
function mbf_customize_register_hero( $wp_customize ) {
	$wp_customize->add_section( 'mbf_hero_slider', array(
		'title'       => __( 'Homepage Hero Slider', 'mybedroomfun-archive' ),
		'description' => __( 'Up to 5 full-width homepage images. Slide 1 has a default image; slides 2-5 are hidden until an image is set for them.', 'mybedroomfun-archive' ),
		'priority'    => 155,
	) );

	for ( $i = 1; $i <= 5; $i++ ) {
		$default = ( 1 === $i )
			? MBF_THEME_URI . '/assets/images/recovered/hero-banner-1.jpg'
			: '';

		$wp_customize->add_setting( 1 === $i ? 'mbf_hero_image' : "mbf_hero_slide_{$i}_image", array(
			'default'           => $default,
			'sanitize_callback' => 'esc_url_raw',
		) );

		$wp_customize->add_control( new WP_Customize_Image_Control(
			$wp_customize,
			1 === $i ? 'mbf_hero_image' : "mbf_hero_slide_{$i}_image",
			array(
				'section'  => 'mbf_hero_slider',
				/* translators: %d: slide number (1-5) */
				'label'    => sprintf( __( 'Slide %d image', 'mybedroomfun-archive' ), $i ),
				'priority' => $i * 10,
			)
		) );
	}
}
add_action( 'customize_register', 'mbf_customize_register_hero' );

/**
 * Sanitize a submitted category id: must be 0 (unset) or the id of an
 * existing, non-error product_cat term.
 *
 * @param mixed $value Raw Customizer value.
 * @return int
 */
function mbf_sanitize_banner_category( $value ) {
	$term_id = absint( $value );

	if ( 0 === $term_id ) {
		return 0;
	}

	$term = get_term( $term_id, 'product_cat' );

	return ( $term && ! is_wp_error( $term ) ) ? $term_id : 0;
}

/**
 * Register the banner Customizer section, one category-dropdown +
 * one image control per banner slot.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 */
function mbf_customize_register( $wp_customize ) {
	if ( ! taxonomy_exists( 'product_cat' ) ) {
		return;
	}

	$wp_customize->add_section( 'mbf_promo_banners', array(
		'title'       => __( 'Promotional Banners', 'mybedroomfun-archive' ),
		'description' => __( 'Up to 3 homepage banners. Each links to one of your existing product categories. A slot needs BOTH a category and an image to appear -- pick "None" or leave the image empty to hide it.', 'mybedroomfun-archive' ),
		'priority'    => 160,
	) );

	$terms   = get_terms( array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => false,
		'orderby'    => 'name',
		'order'      => 'ASC',
	) );
	$choices = array( 0 => __( '— None —', 'mybedroomfun-archive' ) );

	if ( ! is_wp_error( $terms ) ) {
		foreach ( $terms as $term ) {
			$choices[ $term->term_id ] = $term->name;
		}
	}

	for ( $i = 1; $i <= 3; $i++ ) {
		$wp_customize->add_setting( "mbf_banner_{$i}_category_id", array(
			'default'           => 0,
			'sanitize_callback' => 'mbf_sanitize_banner_category',
		) );

		$wp_customize->add_control( "mbf_banner_{$i}_category_id", array(
			'section'  => 'mbf_promo_banners',
			/* translators: %d: banner number (1-3) */
			'label'    => sprintf( __( 'Banner %d category', 'mybedroomfun-archive' ), $i ),
			'type'     => 'select',
			'choices'  => $choices,
			'priority' => ( $i * 10 ),
		) );

		$wp_customize->add_setting( "mbf_banner_{$i}_image", array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		) );

		$wp_customize->add_control( new WP_Customize_Image_Control(
			$wp_customize,
			"mbf_banner_{$i}_image",
			array(
				'section'  => 'mbf_promo_banners',
				/* translators: %d: banner number (1-3) */
				'label'    => sprintf( __( 'Banner %d image', 'mybedroomfun-archive' ), $i ),
				'priority' => ( $i * 10 ) + 1,
			)
		) );
	}
}
add_action( 'customize_register', 'mbf_customize_register' );

/**
 * Resolve the 3 configured banners into renderable data (title, URL,
 * image) for front-page.php. A slot is included only when BOTH its
 * category and its image are set -- not auto-filled from the
 * category's own thumbnail, and not shown with a missing image.
 *
 * @return array<int, array{title: string, url: string, image: string}>
 */
function mbf_get_promo_banners() {
	$banners = array();

	for ( $i = 1; $i <= 3; $i++ ) {
		$term_id = (int) get_theme_mod( "mbf_banner_{$i}_category_id", 0 );
		$image   = get_theme_mod( "mbf_banner_{$i}_image", '' );

		if ( ! $term_id || ! $image ) {
			continue;
		}

		$term = get_term( $term_id, 'product_cat' );

		if ( ! $term || is_wp_error( $term ) ) {
			continue;
		}

		$banners[] = array(
			'title' => $term->name,
			'url'   => get_term_link( $term ),
			'image' => $image,
		);
	}

	return $banners;
}
