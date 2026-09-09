<?php
/**
 * Homepage template.
 *
 * Section order recreates the archived layout: image-only hero,
 * browse categories, bestsellers, sale, featured, new arrivals,
 * promotional category banners, SEO content block (contains the
 * page's only H1), benefits strip. The newsletter signup lives in
 * footer.php as its 4th column, matching the archive (it was a footer
 * column there, not a standalone homepage section). Every
 * product/category query is small and cached — see
 * inc/template-tags.php. Nothing here imports, copies, or fabricates
 * catalog data; it only reads WooCommerce's existing products and
 * terms.
 *
 * @package MyBedroomFun_Archive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="primary" class="mbf-main mbf-main--home">

	<?php
	/**
	 * The archived homepage hero was an image-only rotating banner (5
	 * slides), no headline/subhead/CTA text. Slide 1 defaults to the
	 * one recovered archive image (see ASSET-INVENTORY.md); slides 2-5
	 * are configured via Appearance > Customize > Homepage Hero Slider
	 * and simply don't exist in the markup until an image is set for
	 * them (mbf_get_hero_slides() omits unset slots entirely). With
	 * only 1 slide, this renders as a single static image and no
	 * slider JS/controls are enqueued at all -- see inc/enqueue.php.
	 */
	$mbf_hero_slides = function_exists( 'mbf_get_hero_slides' ) ? mbf_get_hero_slides() : array();
	$mbf_hero_count  = count( $mbf_hero_slides );
	?>
	<?php if ( $mbf_hero_count > 0 ) : ?>
		<section class="mbf-hero"<?php echo ( $mbf_hero_count > 1 ) ? ' data-mbf-hero' : ''; ?>>
			<div class="mbf-hero__viewport">
				<?php foreach ( $mbf_hero_slides as $mbf_index => $mbf_slide_src ) : ?>
					<?php $mbf_is_first = ( 0 === $mbf_index ); ?>
					<div
						class="mbf-hero__slide<?php echo $mbf_is_first ? ' is-active' : ''; ?>"
						<?php echo $mbf_is_first ? '' : 'aria-hidden="true"'; ?>
					>
						<?php if ( function_exists( 'wc_get_page_permalink' ) ) : ?>
							<a class="mbf-hero__link" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" <?php echo $mbf_is_first ? '' : 'tabindex="-1"'; ?>>
						<?php endif; ?>
								<img
									class="mbf-hero__image"
									src="<?php echo esc_url( $mbf_slide_src ); ?>"
									alt=""
									<?php if ( $mbf_is_first ) : ?>
										width="2000"
										height="938"
										fetchpriority="high"
									<?php else : ?>
										loading="lazy"
									<?php endif; ?>
									decoding="async"
								/>
						<?php if ( function_exists( 'wc_get_page_permalink' ) ) : ?>
							</a>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
			<?php if ( $mbf_hero_count > 1 ) : ?>
				<button type="button" class="mbf-hero__control mbf-hero__control--prev" data-hero-prev>
					<span class="screen-reader-text"><?php esc_html_e( 'Previous slide', 'mybedroomfun-archive' ); ?></span>
				</button>
				<button type="button" class="mbf-hero__control mbf-hero__control--next" data-hero-next>
					<span class="screen-reader-text"><?php esc_html_e( 'Next slide', 'mybedroomfun-archive' ); ?></span>
				</button>
				<div class="mbf-hero__dots" data-hero-dots>
					<?php foreach ( $mbf_hero_slides as $mbf_index => $mbf_slide_src ) : ?>
						<button
							type="button"
							class="mbf-hero__dot<?php echo ( 0 === $mbf_index ) ? ' is-active' : ''; ?>"
							data-hero-dot="<?php echo esc_attr( $mbf_index ); ?>"
							<?php echo ( 0 === $mbf_index ) ? 'aria-current="true"' : ''; ?>
						>
							<span class="screen-reader-text">
								<?php
								printf(
									/* translators: %d: slide number */
									esc_html__( 'Show slide %d', 'mybedroomfun-archive' ),
									absint( $mbf_index ) + 1
								);
								?>
							</span>
						</button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</section>
	<?php endif; ?>

	<section class="mbf-categories">
		<div class="mbf-container">
			<h2 class="mbf-categories__heading"><?php esc_html_e( 'Browse our categories', 'mybedroomfun-archive' ); ?></h2>
			<?php $mbf_categories = function_exists( 'mbf_get_shopping_categories' ) ? mbf_get_shopping_categories( 8 ) : array(); ?>
			<?php if ( empty( $mbf_categories ) ) : ?>
				<p class="mbf-categories__empty"><?php esc_html_e( 'No product categories to show here yet.', 'mybedroomfun-archive' ); ?></p>
			<?php else : ?>
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
			<?php endif; ?>
		</div>
	</section>

	<?php if ( function_exists( 'mbf_render_product_rail' ) ) : ?>
		<?php mbf_render_product_rail( __( 'Our BestSellers', 'mybedroomfun-archive' ), mbf_get_bestseller_ids() ); ?>
		<?php mbf_render_product_rail( __( 'Latest on Sale', 'mybedroomfun-archive' ), mbf_get_sale_ids() ); ?>
		<?php mbf_render_product_rail( __( 'Weekly Featured Products', 'mybedroomfun-archive' ), mbf_get_featured_ids() ); ?>
		<?php mbf_render_product_rail( __( 'New Arrivals', 'mybedroomfun-archive' ), mbf_get_new_arrival_ids() ); ?>
	<?php endif; ?>

	<?php
	/**
	 * The archive had 3 promotional banners linking to specific Nov
	 * 2023 categories ("Best Selling Lubes" etc). Those exact
	 * categories are not recreated (old taxonomy, per the build brief).
	 * Matching the archive's banner *layout* doesn't require matching
	 * its taxonomy: each of these 3 slots is picked explicitly by the
	 * admin from the site's own existing product categories via
	 * Appearance > Customize > Promotional Banners -- see
	 * inc/customizer.php. No automatic selection; an unconfigured slot
	 * is simply omitted.
	 */
	$mbf_banners = function_exists( 'mbf_get_promo_banners' ) ? mbf_get_promo_banners() : array();
	?>
	<?php if ( ! empty( $mbf_banners ) ) : ?>
		<section class="mbf-banners">
			<div class="mbf-container mbf-banners__grid">
				<?php foreach ( $mbf_banners as $mbf_banner ) : ?>
					<a class="mbf-banners__item" href="<?php echo esc_url( $mbf_banner['url'] ); ?>">
						<?php if ( ! empty( $mbf_banner['image'] ) ) : ?>
							<img src="<?php echo esc_url( $mbf_banner['image'] ); ?>" alt="" loading="lazy" />
						<?php endif; ?>
						<span class="mbf-banners__title"><?php echo esc_html( $mbf_banner['title'] ); ?></span>
					</a>
				<?php endforeach; ?>
			</div>
		</section>
	<?php endif; ?>

	<section class="mbf-seo-block">
		<div class="mbf-container">
			<h1><?php esc_html_e( 'Sex Toys &amp; Intimate Wellness Products', 'mybedroomfun-archive' ); ?></h1>
			<p>
				<?php esc_html_e( 'From first-time exploration to everyday self-care, our curated range covers every stage of your wellness journey.', 'mybedroomfun-archive' ); ?>
			</p>
			<p>
				<?php esc_html_e( 'Browse by category to find exactly what you need, or check out this week\'s featured picks and current deals above.', 'mybedroomfun-archive' ); ?>
			</p>
		</div>
	</section>

	<?php
	/**
	 * Archive had 4 items here, each with a line-icon: delivery,
	 * returns, availability, secure payments (icons recovered from the
	 * snapshot -- see ASSET-INVENTORY.md). The archive's actual wording
	 * ("Free delivery for $60+", "Free returns within 14 days", "We are
	 * available 24/7") is a Nov 2023 policy claim that can't be
	 * verified as still current, so the numbers/promises are replaced
	 * with neutral copy while the structure, order, and icons match.
	 */
	$mbf_benefits = array(
		array(
			'icon'  => 'icon-delivery.png',
			'title' => __( 'Delivery', 'mybedroomfun-archive' ),
			'text'  => __( 'Details available at checkout.', 'mybedroomfun-archive' ),
		),
		array(
			'icon'  => 'icon-returns.png',
			'title' => __( 'Returns', 'mybedroomfun-archive' ),
			'text'  => __( 'See our returns policy.', 'mybedroomfun-archive' ),
		),
		array(
			'icon'  => 'icon-support.png',
			'title' => __( 'Availability', 'mybedroomfun-archive' ),
			'text'  => __( 'Customer support available.', 'mybedroomfun-archive' ),
		),
		array(
			'icon'  => 'icon-secure.png',
			'title' => __( 'Secure Payments', 'mybedroomfun-archive' ),
			'text'  => __( 'Payments processed securely.', 'mybedroomfun-archive' ),
		),
	);
	?>
	<section class="mbf-benefits">
		<div class="mbf-container mbf-benefits__grid">
			<?php foreach ( $mbf_benefits as $mbf_benefit ) : ?>
				<div class="mbf-benefits__item">
					<img
						class="mbf-benefits__icon"
						src="<?php echo esc_url( MBF_THEME_URI . '/assets/images/recovered/' . $mbf_benefit['icon'] ); ?>"
						alt=""
						width="32"
						height="32"
						loading="lazy"
					/>
					<h2><?php echo esc_html( $mbf_benefit['title'] ); ?></h2>
					<p><?php echo esc_html( $mbf_benefit['text'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</section>


</main>

<?php
get_footer();
