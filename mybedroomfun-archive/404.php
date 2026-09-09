<?php
/**
 * 404 template.
 *
 * @package MyBedroomFun_Archive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="primary" class="mbf-main">
	<div class="mbf-container mbf-404">
		<h1><?php esc_html_e( 'Page not found', 'mybedroomfun-archive' ); ?></h1>
		<p><?php esc_html_e( 'The page you were looking for doesn\'t exist. Try searching, or head back to the shop.', 'mybedroomfun-archive' ); ?></p>

		<?php
		if ( function_exists( 'get_product_search_form' ) ) {
			get_product_search_form();
		} else {
			get_search_form();
		}
		?>

		<?php if ( function_exists( 'wc_get_page_permalink' ) ) : ?>
			<p>
				<a class="mbf-button mbf-button--primary" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
					<?php esc_html_e( 'Back to Shop', 'mybedroomfun-archive' ); ?>
				</a>
			</p>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
