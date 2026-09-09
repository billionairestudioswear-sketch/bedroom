# MyBedroomFun Archive

An original, lightweight WordPress/WooCommerce theme rebuilding the
MyBedroomFun storefront layout (visual reference: the November 2023
Wayback Machine capture of mybedroomfun.com). No page builder, no
Flatsome code, no CSS framework — semantic PHP templates, modular CSS,
and a handful of vanilla-JS lines for the mega menu.

**This theme contains no products, categories, or catalog data.**
Every category, bestseller, sale, and featured-product listing on the
homepage/menu is a live, cached WooCommerce query against whatever
catalog is installed on the site — currently ~40,839 products across
~322 categories on production, none of which are copied, scraped, or
duplicated by this theme.

## Requirements

- WordPress 6.4+
- PHP 8.0+
- WooCommerce 8.0+ active

## Structure

```
mybedroomfun-archive/
├── style.css                 Theme header + design tokens + base reset
├── functions.php             Bootstraps inc/*
├── inc/
│   ├── theme-setup.php       Theme supports, nav menus, image sizes, sidebar
│   ├── enqueue.php           Conditional per-template CSS/JS loading + hero preload
│   ├── template-tags.php     Cached WooCommerce queries + product-card/rail markup
│   ├── woocommerce-support.php  WC theme support, wrapper hooks, sidebar, cart fragment
│   └── newsletter.php        Homepage newsletter form handler
├── header.php / footer.php   Global chrome
├── front-page.php            Homepage (see section order below)
├── index.php / page.php / single.php / archive.php / search.php / 404.php
├── sidebar-shop.php          Shop/category sidebar (category nav)
├── woocommerce/
│   └── content-product.php   Restyled product card; all core WC hooks preserved
├── assets/
│   ├── css/                  One stylesheet per concern, loaded only where needed
│   ├── js/
│   │   └── navigation.js     Mega menu toggle — the only custom script
│   ├── fonts/                Self-hosted Lato (2 weights, Latin subset)
│   └── images/recovered/     Real logo/hero/icon files recovered from the archive
├── screenshot.png
├── ASSET-INVENTORY.md         Archived-asset decisions + what to supply before launch
└── DEPLOYMENT.md              Install / rollback steps for the staging site
```

## Homepage section order

Image-only hero → "Browse our categories" → "Our BestSellers" →
"Latest on Sale" → "Weekly Featured Products" → New Arrivals →
promotional category banners → SEO content block (contains the page's
**only** `<h1>`, *"Sex Toys & Intimate Wellness Products"*) → benefits
strip. This order and the exact section-heading casing/wording is
recovered from the archive, not invented — see
`ASSET-INVENTORY.md`.

The newsletter signup is **not** a homepage section — the archive
placed it as the 4th column of the footer, so it lives in
`footer.php` alongside About the Store and the two "Information" link
columns. Global chrome (announcement bar, utility links,
search-centered header, shipping message, account/wishlist/cart
controls, dark nav + "Shopping Categories" mega menu) is in
`header.php`.

## Recovered vs. original (what actually matches the archive)

| Element | Status |
|---|---|
| Turquoise `#23c2be`, accent `#ff3c9c`, typeface Lato | **Recovered** — exact values read from the site's own compiled CSS |
| Section headings, mega-menu label, primary nav ("Home"/"Shop") | **Recovered** — exact text from the saved HTML |
| Logo, 1 hero image, 4 benefit-strip icons | **Recovered files** — see `ASSET-INVENTORY.md` |
| Announcement bar / shipping-message / benefits-strip *wording with numbers* ("$60+", "14 days", "24/7") | **Recovered but not used as defaults** — this is 2023 policy data that can't be verified as still current (see "Use current verified policies" in the build brief). The literal recovered strings are: announcement — *"100% Discreet & Private Adult Sex Toy Shipping"*; shipping strip — *"FREE Shipping Over $60+ • Discreet Shipping & Billing"*; benefits — *"Free delivery for $60+", "Free returns within 14 days", "We are available 24/7", "100% Secure payments"*; footer about — *"MyBedroomFun is your number one all-inclusive sex toy retailer..."*. If the client confirms any of these are still accurate, paste them into the matching Customizer field (table below) — the theme ships with neutral placeholders instead. |
| Promotional category banners | **Not recreated as named** — the archive linked to 3 specific Nov 2023 categories ("Best Selling Lubes", "Crotchless Lingerie", "Jeweled Butt Plugs"). Recreating those by name would mean recreating old taxonomy, which the brief explicitly prohibits. This build auto-populates 2 banners from the *current* catalog's top categories by product count instead (`mbf_get_top_categories_by_count()`), and a manually set Customizer banner always wins over the auto one. |
| Container max-width `1200px` | **Not confirmed from this snapshot** — Flatsome's documented default, used because the site's actual computed value lives in a dynamic stylesheet the page save didn't capture. |
| Flatsome's icon font (account/wishlist/cart/mega-menu icons) | **Deliberately not reused** — proprietary to the paid Flatsome theme, excluded by the brief. Text labels substitute. |

## Dynamic data — how it stays cheap

`inc/template-tags.php` exposes:

- `mbf_get_bestseller_ids()` — `wc_get_products( orderby: popularity )`
- `mbf_get_sale_ids()` — `wc_get_product_ids_on_sale()`
- `mbf_get_featured_ids()` — `wc_get_products( featured: true )`
- `mbf_get_new_arrival_ids()` — `wc_get_products( orderby: date )`
- `mbf_get_shopping_categories()` — top-level `product_cat` terms + one
  level of children, for the mega menu, the homepage category grid,
  and the shop sidebar

Every one of these is limited (8 products / homepage rail, 12
children per parent category) and cached in a transient (3–12 hours
depending on volatility). Transients are flushed on
`save_post_product`, `woocommerce_update_product`, and category
create/edit/delete — so a catalog change on staging/production shows
up within one page load of the next transient expiry or immediately
after the relevant save action, never by re-querying the full catalog
on every request.

## WooCommerce template compatibility

The theme does **not** override WooCommerce's shop/category/single-
product/cart/checkout/account templates wholesale. It only:

- Declares `add_theme_support( 'woocommerce' )` (no gallery
  zoom/slider/lightbox support, to avoid pulling in extra jQuery
  plugin bundles the design doesn't use — WooCommerce's core
  thumbnail-click gallery script still works).
- Swaps the default content wrapper for the theme's own `<main>` via
  the standard `woocommerce_before_main_content` /
  `woocommerce_after_main_content` hooks.
- Overrides only `woocommerce/content-product.php`, and only to add a
  CSS class — every default WooCommerce loop-item action hook (sale
  flash, title, rating, price, add-to-cart button) is preserved, so
  third-party plugins hooking into those still work.
- Adds a cart-count AJAX fragment (`a.cart-contents`) so the header
  cart updates after add-to-cart without a full reload.

This means WooCommerce core updates and most extensions keep working
without theme changes.

## Performance choices

- No Elementor/page builder, no Flatsome, no Bootstrap/Tailwind.
- CSS/JS are split by concern and enqueued only on the templates that
  need them (see `inc/enqueue.php`) — a blog post never loads
  `shop.css`, checkout never loads `homepage.css`.
- Only `assets/js/navigation.js` is custom; it is dependency-free and
  loaded in the footer.
- Product/category/rail images use WordPress's responsive `srcset` via
  `wc_get_gallery_image_html()` / `wp_get_attachment_image()` and
  `loading="lazy"` everywhere except the single homepage hero image,
  which is instead preloaded (`fetchpriority="high"`, no `lazy`) as the
  LCP candidate.
- Lato (the recovered typeface) is self-hosted as 2 woff2 files
  (~46KB total, Latin subset only) with `font-display: swap` and a
  system-font fallback stack — no runtime request to Google Fonts or
  any other external host.
- The sale badge and mega-menu caret are CSS shapes/text, not an icon
  font or SVG sprite library. The 4 benefits-strip icons are small
  (~1-2.6KB each) recovered PNGs, `loading="lazy"`, not an icon font.

## Customizer theme mods

| Mod | Purpose |
|---|---|
| `mbf_announcement_text` | Announcement bar copy |
| `mbf_shipping_message` | Shipping strip copy under the header |
| `mbf_hero_headline` / `mbf_hero_subhead` / `mbf_hero_image` | Homepage hero |
| `mbf_banner_1_title` / `_url` / `_image`, `mbf_banner_2_*` | Promotional category banners |
| `mbf_footer_about` | Footer "about" blurb |

Set via `Appearance → Customize → Additional CSS` panel isn't required
— use `get_theme_mod()`/Customizer API or a small mu-plugin to register
controls for these mods; the theme reads them with safe fallback
copy, so it works out of the box before anyone configures them.

## Menus to assign after activation

`Appearance → Menus`: **Primary Navigation** (archive: "Home", "Shop"),
**Utility Links** (archive: "About Us", "Contact Us", "FAQ",
"Services"), **Footer Information Column 1** (archive: "About Us",
"FAQ", "Contact Us", "Klarna FAQ"), **Footer Information Column 2**
(archive: "Services", "Order Tracking", "Exclusive Brands"). Verify
each destination page actually exists on production before linking to
it — this environment can't check that. None of these menus are
required for the theme to render —
each `wp_nav_menu()` call is wrapped in `has_nav_menu()` / falls back
to nothing rather than WordPress's default "Pages" fallback list.

## Validation performed

See the final task output for the full validation log (PHP lint on
every file, static checks for hard-coded staging/production URLs and
product/category IDs). No PHP or JS runtime testing was performed here
because the theme has not been installed on any WordPress
instance — do that on `staging.mybedroomfun.com` first (see
`DEPLOYMENT.md`) before treating this as launch-ready.
