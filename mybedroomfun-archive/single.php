<?php
/**
 * Single post template.
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
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article <?php post_class( 'mbf-post' ); ?>>
				<h1 class="mbf-post__title"><?php the_title(); ?></h1>
				<p class="mbf-post__meta">
					<?php echo esc_html( get_the_date() ); ?>
				</p>
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="mbf-post__thumbnail">
						<?php the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) ); ?>
					</div>
				<?php endif; ?>
				<div class="mbf-post__content">
					<?php the_content(); ?>
				</div>
			</article>

			<?php
			the_post_navigation( array(
				'prev_text' => __( '&larr; %title', 'mybedroomfun-archive' ),
				'next_text' => __( '%title &rarr;', 'mybedroomfun-archive' ),
			) );

			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>
			<?php
		endwhile;
		?>
	</div>
</main>

<?php
get_footer();
