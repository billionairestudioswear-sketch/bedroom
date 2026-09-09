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
│   └── js/
│       └── navigation.js     Mega menu toggle — the only custom script
├── screenshot.png
├── ASSET-INVENTORY.md         Archived-asset decisions + what to supply before launch
└── DEPLOYMENT.md              Install / rollback steps for the staging site
```

## Homepage section order

Hero → Browse Our Categories → Our Bestsellers → Latest on Sale →
Weekly Featured Products → New Arrivals → promotional category
banners → SEO content block (contains the page's **only** `<h1>`,
*"Sex Toys & Intimate Wellness Products"*) → benefits strip →
newsletter signup. Global chrome (announcement bar, utility links,
search-centered header, shipping message, account/wishlist/cart
controls, dark nav + Shopping Categories mega menu) is in
`header.php`; the multi-column footer is in `footer.php`.

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
- System font stack only (`-apple-system, "Segoe UI", Roboto, ...`) —
  no webfont download, no external font/icon library requests.
- All icon-like elements (sale badge, benefit strip, mega-menu caret)
  are CSS shapes/text, not an icon font or SVG sprite library.

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

`Appearance → Menus`: **Primary Navigation**, **Utility Links**,
**Footer Column 1/2/3**. None are required for the theme to render —
each `wp_nav_menu()` call is wrapped in `has_nav_menu()` / falls back
to nothing rather than WordPress's default "Pages" fallback list.

## Validation performed

See the final task output for the full validation log (PHP lint on
every file, static checks for hard-coded staging/production URLs and
product/category IDs). No PHP or JS runtime testing was performed here
because the theme has not been installed on any WordPress
instance — do that on `staging.mybedroomfun.com` first (see
`DEPLOYMENT.md`) before treating this as launch-ready.
