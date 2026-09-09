<?php
/**
 * Search results template. Renders WooCommerce products as product
 * cards and everything else as a standard title/excerpt row.
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
			<h1 class="mbf-archive__title">
				<?php
				printf(
					/* translators: %s: search query */
					esc_html__( 'Search results for: %s', 'mybedroomfun-archive' ),
					'<span>' . esc_html( get_search_query() ) . '</span>'
				);
				?>
			</h1>
		</header>

		<?php if ( have_posts() ) : ?>
			<ul class="mbf-rail__grid mbf-search-results">
				<?php
				while ( have_posts() ) :
					the_post();

					if ( 'product' === get_post_type() && function_exists( 'mbf_render_product_card' ) ) {
						mbf_render_product_card( get_the_ID() );
						continue;
					}
					?>
					<li class="mbf-post-summary">
						<h2 class="mbf-post-summary__title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h2>
						<div class="mbf-post-summary__excerpt">
							<?php the_excerpt(); ?>
						</div>
					</li>
					<?php
				endwhile;
				?>
			</ul>

			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'No results found. Try a different search term.', 'mybedroomfun-archive' ); ?></p>
			<?php
			if ( function_exists( 'get_product_search_form' ) ) {
				get_product_search_form();
			} else {
				get_search_form();
			}
			?>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
