# Deployment & Rollback — staging.mybedroomfun.com only

This theme has been built and validated locally in this repository. It
has **not** been installed or activated anywhere. These are the steps
for doing that on the self-hosted staging installation. Do not run any
of this against production (mybedroomfun.com).

## Install on staging

1. Confirm you're targeting staging: `wp --url=https://staging.mybedroomfun.com/ option get siteurl`
   should print the staging URL, not production.
2. Take a staging backup/snapshot first (database + `wp-content`) so
   step 5's rollback has something to restore.
3. Upload `mybedroomfun-archive.zip` via
   `wp-admin → Appearance → Themes → Add New → Upload Theme`, or
   unzip it directly into `wp-content/themes/mybedroomfun-archive/`
   over SFTP/SSH.
4. Activate: `Appearance → Themes → MyBedroomFun Archive → Activate`
   (or `wp theme activate mybedroomfun-archive`).
5. Configure, in order:
   - `Appearance → Customize` — set the hero image, announcement text,
     shipping message, and the two promotional banners (see
     `ASSET-INVENTORY.md` for what's still needed).
   - `Appearance → Menus` — assign Primary Navigation, Utility Links,
     and the three footer menus (optional — the theme renders fine
     without them).
   - `WooCommerce → Settings` — confirm the Shop page, cart, checkout,
     and account pages are still the same pages staging already uses
     (this theme does not create or move any WooCommerce pages).
6. Manually verify against the checklist below before calling it done.

## Manual verification checklist (staging)

- [ ] Homepage: hero, category grid, all four product rails,
      promotional banners, SEO block (exactly one `<h1>`), benefits
      strip, and newsletter form all render.
- [ ] Shop page and at least one category archive: grid, sidebar,
      pagination, add-to-cart (AJAX) all work; header cart count
      updates without a page reload.
- [ ] A single product page: gallery, price, variations (if any),
      add-to-cart, tabs, related products.
- [ ] Cart → checkout → an actual test order completes on staging's
      test payment gateway.
- [ ] My Account: login, orders, addresses render correctly.
- [ ] Search: try a term that matches a product and a term that
      matches nothing.
- [ ] 404 page.
- [ ] Resize to 360px, 768px, and desktop widths — no horizontal
      scroll, mega menu and category grid reflow correctly.
- [ ] Run Lighthouse (mobile + desktop) on the homepage and a product
      page; confirm against the performance targets in the main
      README (LCP < 2.5s, CLS < 0.10, INP < 200ms, mobile ≥ 90,
      desktop ≥ 95). Re-check after adding real hero/banner imagery —
      an unoptimized hero photo is the most likely regression source.

## Rollback

If anything in the checklist fails and can't be fixed quickly:

1. `wp theme activate <previous-theme-slug>` (or reactivate the
   previous theme from `Appearance → Themes` in wp-admin) —
   WooCommerce data, products, and categories are untouched by a theme
   switch.
2. If theme files were edited directly on staging after upload, restore
   `wp-content/themes/mybedroomfun-archive/` from the step-2 backup
   rather than hand-reverting edits.
3. Clear any page/object cache staging runs after switching back.

A theme activation/deactivation never touches products, orders,
customers, categories, prices, stock, payment gateway settings, or
SEO/redirect configuration — those live entirely outside the theme.

## Explicitly out of scope for this task

- Production (mybedroomfun.com) is not touched at any point.
- No products, categories, prices, stock, or orders are created,
  changed, or imported anywhere.
- No supplier feeds, `config.py`, or cron jobs are modified.
- No payment gateway configuration is touched.
- No SEO/redirect/sitemap changes are made.
- Nothing in this repository has been deployed anywhere — packaging
  the ZIP is the last step this task performs.
