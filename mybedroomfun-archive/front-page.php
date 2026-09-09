<?php
/**
 * Homepage template.
 *
 * Section order recreates the archived layout: hero, browse
 * categories, bestsellers, sale, featured, new arrivals, promotional
 * category banners, SEO content block (contains the page's only
 * H1), benefits strip, newsletter signup. Every product/category
 * query is small and cached — see inc/template-tags.php. Nothing here
 * imports, copies, or fabricates catalog data; it only reads
 * WooCommerce's existing products and terms.
 *
 * @package MyBedroomFun_Archive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="primary" class="mbf-main mbf-main--home">

	<section class="mbf-hero">
		<div class="mbf-container mbf-hero__inner">
			<?php $mbf_hero_image = get_theme_mod( 'mbf_hero_image' ); ?>
			<?php if ( $mbf_hero_image ) : ?>
				<img
					class="mbf-hero__image"
					src="<?php echo esc_url( $mbf_hero_image ); ?>"
					alt=""
					fetchpriority="high"
					decoding="async"
				/>
			<?php endif; ?>
			<div class="mbf-hero__copy">
				<p class="mbf-hero__eyebrow"><?php esc_html_e( 'New Season', 'mybedroomfun-archive' ); ?></p>
				<p class="mbf-hero__headline">
					<?php echo esc_html( get_theme_mod( 'mbf_hero_headline', __( 'Feel good, on your own terms.', 'mybedroomfun-archive' ) ) ); ?>
				</p>
				<p class="mbf-hero__subhead">
					<?php echo esc_html( get_theme_mod( 'mbf_hero_subhead', __( 'Explore our full range of wellness and intimacy essentials.', 'mybedroomfun-archive' ) ) ); ?>
				</p>
				<?php if ( function_exists( 'wc_get_page_permalink' ) ) : ?>
					<a class="mbf-button mbf-button--primary" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
						<?php esc_html_e( 'Shop Now', 'mybedroomfun-archive' ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<?php if ( function_exists( 'mbf_get_shopping_categories' ) ) : ?>
		<?php $mbf_categories = mbf_get_shopping_categories( 8 ); ?>
		<?php if ( ! empty( $mbf_categories ) ) : ?>
			<section class="mbf-categories">
				<div class="mbf-container">
					<h2 class="mbf-categories__heading"><?php esc_html_e( 'Browse Our Categories', 'mybedroomfun-archive' ); ?></h2>
					<ul class="mbf-categories__grid">
						<?php foreach ( $mbf_categories as $mbf_category_row ) : ?>
							<?php
							$mbf_term         = $mbf_category_row['term'];
							$mbf_thumbnail_id = get_term_meta( $mbf_term->term_id, 'thumbnail_id', true );
							?>
							<li class="mbf-categories__item">
								<a href="<?php echo esc_url( get_term_link( $mbf_term ) ); ?>">
									<?php if ( $mbf_thumbnail_id ) : ?>
										<?php echo wp_get_attachment_image( $mbf_thumbnail_id, 'mbf-card', false, array( 'loading' => 'lazy', 'alt' => $mbf_term->name ) ); ?>
									<?php else : ?>
										<span class="mbf-categories__placeholder" aria-hidden="true"></span>
									<?php endif; ?>
									<span class="mbf-categories__name"><?php echo esc_html( $mbf_term->name ); ?></span>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</section>
		<?php endif; ?>
	<?php endif; ?>

	<?php if ( function_exists( 'mbf_render_product_rail' ) ) : ?>
		<?php mbf_render_product_rail( __( 'Our Bestsellers', 'mybedroomfun-archive' ), mbf_get_bestseller_ids() ); ?>
		<?php mbf_render_product_rail( __( 'Latest on Sale', 'mybedroomfun-archive' ), mbf_get_sale_ids() ); ?>
		<?php mbf_render_product_rail( __( 'Weekly Featured Products', 'mybedroomfun-archive' ), mbf_get_featured_ids() ); ?>
		<?php mbf_render_product_rail( __( 'New Arrivals', 'mybedroomfun-archive' ), mbf_get_new_arrival_ids() ); ?>
	<?php endif; ?>

	<section class="mbf-banners">
		<div class="mbf-container mbf-banners__grid">
			<?php for ( $mbf_i = 1; $mbf_i <= 2; $mbf_i++ ) : ?>
				<?php
				$mbf_banner_title = get_theme_mod( "mbf_banner_{$mbf_i}_title" );
				$mbf_banner_url   = get_theme_mod( "mbf_banner_{$mbf_i}_url" );
				$mbf_banner_image = get_theme_mod( "mbf_banner_{$mbf_i}_image" );

				if ( ! $mbf_banner_title ) {
					continue;
				}
				?>
				<a class="mbf-banners__item" href="<?php echo esc_url( $mbf_banner_url ? $mbf_banner_url : '#' ); ?>">
					<?php if ( $mbf_banner_image ) : ?>
						<img src="<?php echo esc_url( $mbf_banner_image ); ?>" alt="" loading="lazy" />
					<?php endif; ?>
					<span class="mbf-banners__title"><?php echo esc_html( $mbf_banner_title ); ?></span>
				</a>
			<?php endfor; ?>
		</div>
	</section>

	<section class="mbf-seo-block">
		<div class="mbf-container">
			<h1><?php esc_html_e( 'Sex Toys &amp; Intimate Wellness Products', 'mybedroomfun-archive' ); ?></h1>
			<p>
				<?php esc_html_e( 'From first-time exploration to everyday self-care, our curated range covers every stage of your wellness journey. Every order ships in plain, unmarked packaging.', 'mybedroomfun-archive' ); ?>
			</p>
			<p>
				<?php esc_html_e( 'Browse by category to find exactly what you need, or check out this week\'s featured picks and current deals above.', 'mybedroomfun-archive' ); ?>
			</p>
		</div>
	</section>

	<section class="mbf-benefits">
		<div class="mbf-container mbf-benefits__grid">
			<div class="mbf-benefits__item">
				<h2><?php esc_html_e( 'Discreet Packaging', 'mybedroomfun-archive' ); ?></h2>
				<p><?php esc_html_e( 'No branding, no surprises.', 'mybedroomfun-archive' ); ?></p>
			</div>
			<div class="mbf-benefits__item">
				<h2><?php esc_html_e( 'Free Shipping', 'mybedroomfun-archive' ); ?></h2>
				<p><?php esc_html_e( 'On orders over $49.', 'mybedroomfun-archive' ); ?></p>
			</div>
			<div class="mbf-benefits__item">
				<h2><?php esc_html_e( 'Secure Checkout', 'mybedroomfun-archive' ); ?></h2>
				<p><?php esc_html_e( 'Encrypted payment processing.', 'mybedroomfun-archive' ); ?></p>
			</div>
			<div class="mbf-benefits__item">
				<h2><?php esc_html_e( 'Easy Returns', 'mybedroomfun-archive' ); ?></h2>
				<p><?php esc_html_e( '30-day return window.', 'mybedroomfun-archive' ); ?></p>
			</div>
		</div>
	</section>

	<section class="mbf-newsletter">
		<div class="mbf-container mbf-newsletter__inner">
			<h2><?php esc_html_e( 'Get the newest arrivals and deals in your inbox', 'mybedroomfun-archive' ); ?></h2>
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
	</section>

</main>

<?php
get_footer();
