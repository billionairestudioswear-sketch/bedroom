<?php
/**
 * Multi-column site footer.
 *
 * 4 columns, matching the archived footer exactly: About the Store,
 * two link columns both literally titled "Information" in the
 * archive (kept as-is -- see ASSET-INVENTORY.md), and the newsletter
 * signup, which lives here (not as a standalone homepage section)
 * because that's where the archive actually placed it.
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
				<h2 class="mbf-footer__heading"><?php esc_html_e( 'About the Store', 'mybedroomfun-archive' ); ?></h2>
				<p class="mbf-footer__about">
					<?php echo esc_html( get_theme_mod( 'mbf_footer_about', __( 'Your online store for wellness and intimacy products.', 'mybedroomfun-archive' ) ) ); ?>
				</p>
			</div>

			<div class="mbf-footer__column">
				<h2 class="mbf-footer__heading"><?php esc_html_e( 'Information', 'mybedroomfun-archive' ); ?></h2>
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
				<h2 class="mbf-footer__heading"><?php esc_html_e( 'Information', 'mybedroomfun-archive' ); ?></h2>
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
				<h2 class="mbf-footer__heading"><?php esc_html_e( 'Free toys & special offers', 'mybedroomfun-archive' ); ?></h2>
				<p class="mbf-footer__about">
					<?php esc_html_e( 'Sign up for tips, offers, and updates.', 'mybedroomfun-archive' ); ?>
				</p>
				<form
					class="mbf-newsletter__form"
					method="post"
					action="<?php echo esc_url( apply_filters( 'mbf_newsletter_action_url', admin_url( 'admin-post.php' ) ) ); ?>"
				>
					<input type="hidden" name="action" value="mbf_newsletter_signup" />
					<?php wp_nonce_field( 'mbf_newsletter_signup', 'mbf_newsletter_nonce' ); ?>
					<label class="screen-reader-text" for="mbf-newsletter-email"><?php esc_html_e( 'Email address', 'mybedroomfun-archive' ); ?></label>
					<input
						id="mbf-newsletter-email"
						type="email"
						name="email"
						required
						placeholder="<?php esc_attr_e( 'Your email address', 'mybedroomfun-archive' ); ?>"
					/>
					<button type="submit" class="mbf-button mbf-button--primary">
						<?php esc_html_e( 'Subscribe', 'mybedroomfun-archive' ); ?>
					</button>
				</form>
				<?php do_action( 'mbf_after_newsletter_form' ); ?>
			</div>

		</div>

		<div class="mbf-footer__bottom mbf-container">
			<p>
				&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>.
				<?php esc_html_e( 'All Rights Reserved.', 'mybedroomfun-archive' ); ?>
			</p>
		</div>
	</footer>

<?php wp_footer(); ?>
</body>
</html>
