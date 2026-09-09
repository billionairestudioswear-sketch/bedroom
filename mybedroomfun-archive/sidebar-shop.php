<?php
/**
 * Shop navigation sidebar — shown on the shop page and category/tag
 * archives only (see mbf_woocommerce_sidebar() in
 * inc/woocommerce-support.php). Lists existing product categories;
 * creates nothing.
 *
 * @package MyBedroomFun_Archive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<aside id="secondary" class="mbf-shop-sidebar" aria-label="<?php esc_attr_e( 'Shop navigation', 'mybedroomfun-archive' ); ?>">

	<?php if ( function_exists( 'mbf_get_shopping_categories' ) ) : ?>
		<?php $mbf_sidebar_categories = mbf_get_shopping_categories(); ?>
		<?php if ( ! empty( $mbf_sidebar_categories ) ) : ?>
			<nav class="mbf-shop-sidebar__categories">
				<h2 class="mbf-widget__title"><?php esc_html_e( 'Categories', 'mybedroomfun-archive' ); ?></h2>
				<ul>
					<?php foreach ( $mbf_sidebar_categories as $mbf_row ) : ?>
						<li>
							<a href="<?php echo esc_url( get_term_link( $mbf_row['term'] ) ); ?>">
								<?php echo esc_html( $mbf_row['term']->name ); ?>
							</a>
							<?php if ( ! empty( $mbf_row['children'] ) ) : ?>
								<ul>
									<?php foreach ( $mbf_row['children'] as $mbf_child ) : ?>
										<li>
											<a href="<?php echo esc_url( get_term_link( $mbf_child ) ); ?>">
												<?php echo esc_html( $mbf_child->name ); ?>
											</a>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</nav>
		<?php endif; ?>
	<?php endif; ?>

	<?php if ( is_active_sidebar( 'shop-sidebar' ) ) : ?>
		<?php dynamic_sidebar( 'shop-sidebar' ); ?>
	<?php endif; ?>

</aside>
