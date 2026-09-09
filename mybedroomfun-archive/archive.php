<?php
/**
 * Generic archive template (author/date archives, or any non-product
 * post type). WooCommerce's own shop and category archives are
 * rendered by the WooCommerce plugin's templates using the wrapper
 * hooks and card markup registered in inc/woocommerce-support.php and
 * woocommerce/content-product.php — this file is the plain-WordPress
 * fallback, not the shop page.
 *
 * @package MyBedroomFun_Archive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="primary" class="mbf-main">
	<div class="mbf-container">
		<header class="mbf-archive__header">
			<h1 class="mbf-archive__title"><?php the_archive_title(); ?></h1>
			<?php the_archive_description( '<div class="mbf-archive__description">', '</div>' ); ?>
		</header>

		<?php if ( have_posts() ) : ?>
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article <?php post_class( 'mbf-post-summary' ); ?>>
					<h2 class="mbf-post-summary__title">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</h2>
					<div class="mbf-post-summary__excerpt">
						<?php the_excerpt(); ?>
					</div>
				</article>
				<?php
			endwhile;

			the_posts_pagination();
			?>
		<?php else : ?>
			<p><?php esc_html_e( 'Nothing found.', 'mybedroomfun-archive' ); ?></p>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
