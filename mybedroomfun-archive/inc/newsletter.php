<?php
/**
 * Minimal newsletter signup handler for the footer newsletter form.
 *
 * This does not send email itself — it verifies the request, stores
 * nothing beyond what `mbf_newsletter_signup` subscribers choose to do,
 * and redirects back with a status flag the template can read. Wire a
 * real email service by hooking `mbf_newsletter_signup` (e.g. from a
 * small mu-plugin or a dedicated ESP integration plugin).
 *
 * @package MyBedroomFun_Archive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle the newsletter form POST (admin-post.php?action=mbf_newsletter_signup).
 */
function mbf_handle_newsletter_signup() {
	$redirect = wp_get_referer() ? wp_get_referer() : home_url( '/' );

	$nonce_ok = isset( $_POST['mbf_newsletter_nonce'] )
		&& wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mbf_newsletter_nonce'] ) ), 'mbf_newsletter_signup' );

	if ( ! $nonce_ok ) {
		wp_safe_redirect( add_query_arg( 'mbf_newsletter', 'error', $redirect ) );
		exit;
	}

	$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';

	if ( ! is_email( $email ) ) {
		wp_safe_redirect( add_query_arg( 'mbf_newsletter', 'invalid', $redirect ) );
		exit;
	}

	/**
	 * Fires with a validated email address from the footer newsletter
	 * form. Hook this to send it to a real email service provider.
	 *
	 * @param string $email Subscriber email address.
	 */
	do_action( 'mbf_newsletter_signup', $email );

	wp_safe_redirect( add_query_arg( 'mbf_newsletter', 'success', $redirect ) );
	exit;
}
add_action( 'admin_post_mbf_newsletter_signup', 'mbf_handle_newsletter_signup' );
add_action( 'admin_post_nopriv_mbf_newsletter_signup', 'mbf_handle_newsletter_signup' );

/**
 * Show a small inline confirmation/error message after redirect back.
 */
function mbf_newsletter_notice() {
	if ( empty( $_GET['mbf_newsletter'] ) ) {
		return;
	}

	$status  = sanitize_key( wp_unslash( $_GET['mbf_newsletter'] ) );
	$message = '';

	if ( 'success' === $status ) {
		$message = __( 'Thanks — you\'re on the list.', 'mybedroomfun-archive' );
	} elseif ( 'invalid' === $status ) {
		$message = __( 'Please enter a valid email address.', 'mybedroomfun-archive' );
	} elseif ( 'error' === $status ) {
		$message = __( 'Something went wrong. Please try again.', 'mybedroomfun-archive' );
	}

	if ( ! $message ) {
		return;
	}
	?>
	<p class="mbf-newsletter__notice" role="status"><?php echo esc_html( $message ); ?></p>
	<?php
}
add_action( 'mbf_after_newsletter_form', 'mbf_newsletter_notice' );
