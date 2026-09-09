<?php
/**
 * Fallback template for any request no more specific template matches.
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
