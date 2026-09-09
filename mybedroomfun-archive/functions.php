<?php
/**
 * Theme bootstrap.
 *
 * @package MyBedroomFun_Archive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MBF_THEME_VERSION', '1.0.0' );
define( 'MBF_THEME_DIR', get_template_directory() );
define( 'MBF_THEME_URI', get_template_directory_uri() );

$mbf_includes = array(
	'inc/theme-setup.php',
	'inc/enqueue.php',
	'inc/template-tags.php',
	'inc/woocommerce-support.php',
	'inc/newsletter.php',
);

foreach ( $mbf_includes as $mbf_file ) {
	$mbf_path = MBF_THEME_DIR . '/' . $mbf_file;
	if ( is_readable( $mbf_path ) ) {
		require_once $mbf_path;
	}
}
unset( $mbf_includes, $mbf_file, $mbf_path );
