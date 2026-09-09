<?php
/**
 * Multi-column site footer.
 *
 * @package MyBedroomFun_Archive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
	<footer class="mbf-footer">
		<div class="mbf-container mbf-footer__columns">

			<div class="mbf-footer__column">
				<h2 class="mbf-footer__heading"><?php bloginfo( 'name' ); ?></h2>
				<p class="mbf-footer__about">
					<?php echo esc_html( get_theme_mod( 'mbf_footer_about', __( 'Wellness and intimacy products.', 'mybedroomfun-archive' ) ) ); ?>
				</p>
			</div>

			<div class="mbf-footer__column">
				<h2 class="mbf-footer__heading"><?php esc_html_e( 'Shop', 'mybedroomfun-archive' ); ?></h2>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'footer-1',
					'container'      => false,
					'menu_class'     => 'mbf-footer__menu',
					'fallback_cb'    => false,
					'depth'          => 1,
				) );
				?>
			</div>

			<div class="mbf-footer__column">
				<h2 class="mbf-footer__heading"><?php esc_html_e( 'Customer Care', 'mybedroomfun-archive' ); ?></h2>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'footer-2',
					'container'      => false,
					'menu_class'     => 'mbf-footer__menu',
					'fallback_cb'    => false,
					'depth'          => 1,
				) );
				?>
			</div>

			<div class="mbf-footer__column">
				<h2 class="mbf-footer__heading"><?php esc_html_e( 'Company', 'mybedroomfun-archive' ); ?></h2>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'footer-3',
					'container'      => false,
					'menu_class'     => 'mbf-footer__menu',
					'fallback_cb'    => false,
					'depth'          => 1,
				) );
				?>
			</div>

		</div>

		<div class="mbf-footer__bottom mbf-container">
			<p>
				&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>.
				<?php esc_html_e( 'All rights reserved.', 'mybedroomfun-archive' ); ?>
			</p>
		</div>
	</footer>

<?php wp_footer(); ?>
</body>
</html>
