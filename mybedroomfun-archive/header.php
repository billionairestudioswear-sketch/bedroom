<?php
/**
 * Site header: announcement bar, utility links, search-centered
 * header, shipping message, account/wishlist/cart controls, and the
 * dark navigation bar with the "Shopping Categories" mega menu.
 *
 * @package MyBedroomFun_Archive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="mbf-skip-link" href="#primary"><?php esc_html_e( 'Skip to content', 'mybedroomfun-archive' ); ?></a>

<header class="mbf-header">

	<div class="mbf-announcement">
		<p class="mbf-container">
			<?php echo esc_html( get_theme_mod( 'mbf_announcement_text', __( 'Wellness and intimacy essentials.', 'mybedroomfun-archive' ) ) ); ?>
		</p>
	</div>

	<?php if ( has_nav_menu( 'utility' ) ) : ?>
		<div class="mbf-utility mbf-container">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'utility',
				'container'      => false,
				'menu_class'     => 'mbf-utility__menu',
				'depth'          => 1,
			) );
			?>
		</div>
	<?php endif; ?>

	<div class="mbf-header__main mbf-container">
		<div class="mbf-header__brand">
			<?php
			if ( has_custom_logo() ) {
				the_custom_logo();
			} else {
				?>
				<a class="mbf-header__site-name" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php bloginfo( 'name' ); ?>
				</a>
				<?php
			}
			?>
		</div>

		<div class="mbf-header__search">
			<?php
			if ( function_exists( 'get_product_search_form' ) ) {
				get_product_search_form();
			} else {
				get_search_form();
			}
			?>
		</div>

		<div class="mbf-header__controls">
			<a class="mbf-header__control" href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : admin_url() ); ?>">
				<span class="mbf-header__control-label"><?php esc_html_e( 'Account', 'mybedroomfun-archive' ); ?></span>
			</a>
			<a class="mbf-header__control" href="<?php echo esc_url( function_exists( 'mbf_wishlist_url' ) ? mbf_wishlist_url() : '#' ); ?>">
				<span class="mbf-header__control-label"><?php esc_html_e( 'Wishlist', 'mybedroomfun-archive' ); ?></span>
			</a>
			<?php if ( function_exists( 'WC' ) ) : ?>
				<a class="cart-contents mbf-header__control" href="<?php echo esc_url( wc_get_cart_url() ); ?>">
					<span class="mbf-header__control-label"><?php esc_html_e( 'Cart', 'mybedroomfun-archive' ); ?></span>
					<span class="count"><?php echo esc_html( WC()->cart ? WC()->cart->get_cart_contents_count() : 0 ); ?></span>
				</a>
			<?php endif; ?>
		</div>
	</div>

	<p class="mbf-shipping-message mbf-container">
		<?php echo esc_html( get_theme_mod( 'mbf_shipping_message', __( 'Shipping details are confirmed at checkout.', 'mybedroomfun-archive' ) ) ); ?>
	</p>

	<nav class="mbf-nav" aria-label="<?php esc_attr_e( 'Primary', 'mybedroomfun-archive' ); ?>">
		<div class="mbf-container mbf-nav__inner">

			<?php if ( function_exists( 'mbf_get_shopping_categories' ) ) : ?>
				<?php $mbf_categories = mbf_get_shopping_categories(); ?>
				<?php if ( ! empty( $mbf_categories ) ) : ?>
					<div class="mbf-mega">
						<button
							type="button"
							class="mbf-mega__trigger"
							aria-expanded="false"
							aria-controls="mbf-mega-panel"
						>
							<?php esc_html_e( 'Shopping Categories', 'mybedroomfun-archive' ); ?>
						</button>
						<div id="mbf-mega-panel" class="mbf-mega__panel" hidden>
							<ul class="mbf-mega__columns">
								<?php foreach ( $mbf_categories as $mbf_category ) : ?>
									<li class="mbf-mega__column">
										<a class="mbf-mega__parent" href="<?php echo esc_url( get_term_link( $mbf_category['term'] ) ); ?>">
											<?php echo esc_html( $mbf_category['term']->name ); ?>
										</a>
										<?php if ( ! empty( $mbf_category['children'] ) ) : ?>
											<ul class="mbf-mega__children">
												<?php foreach ( $mbf_category['children'] as $mbf_child ) : ?>
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
						</div>
					</div>
				<?php endif; ?>
			<?php endif; ?>

			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'mbf-nav__menu',
				'fallback_cb'    => false,
				'depth'          => 1,
			) );
			?>
		</div>
	</nav>

</header>
