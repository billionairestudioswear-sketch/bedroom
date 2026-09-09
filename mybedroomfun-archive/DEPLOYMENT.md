# Deployment & Rollback — staging only

This theme has been built and validated locally in this repository. It
has **not** been installed or activated by this task. These are the
steps for doing that on your self-hosted staging installation
(currently `https://1stideaweb.com/fun/`). Do not run any of this
against production (mybedroomfun.com).

## Install / update on staging

1. Confirm you're targeting staging, not production, before doing
   anything else.
2. Take a staging backup/snapshot first (database + `wp-content`) so
   the rollback section below has something to restore.
3. **Replace the whole theme folder**, don't merge files in by hand:
   delete the existing `wp-content/themes/mybedroomfun-archive/`
   (or upload to a fresh folder) and then upload
   `mybedroomfun-archive.zip` via
   `wp-admin → Appearance → Themes → Add New → Upload Theme`, or unzip
   it fresh over SFTP/SSH. A partial overwrite can leave stale files
   from a previous version mixed in with new ones.
4. Activate: `Appearance → Themes → MyBedroomFun Archive → Activate`
   (or `wp theme activate mybedroomfun-archive`).
5. **Clear every caching layer between you and the file**, in this
   order, and hard-refresh (Ctrl/Cmd+Shift+R) after each: any WordPress
   caching plugin, the host's own page/object cache if it has one, and
   a CDN cache (e.g. Cloudflare "Purge Everything") if one sits in
   front of the site. As of this build, every CSS/JS file is enqueued
   with a version string equal to that file's own last-modified time
   (see `mbf_asset_version()` in `inc/enqueue.php`), so a genuinely new
   upload always gets a new `?ver=` and can't be served stale by a
   cache that respects query strings — but a cache that ignores query
   strings, or a stale copy from *before* this fix was installed, can
   still serve old content until purged once.
6. **Confirm WooCommerce is active**: `Plugins → Installed Plugins` —
   WooCommerce must show "Active". If it's only installed (not
   activated), or not installed at all, the shop pages, product/
   category sections, and the header cart control will not appear —
   see "Empty-data behavior" below before assuming that's a theme bug.
7. Configure, in order:
   - **Site Title**: `Settings → General → Site Title` → change to
     the real site name (this replaces whatever placeholder title —
     e.g. "Inspiration Station" — the install currently shows; it is
     not something the theme sets or can override, and it appears
     verbatim in the footer copyright and the browser tab).
   - **Menus**: `Appearance → Menus` — create and assign a menu to
     each of: **Primary Navigation**, **Utility Links**, **Footer
     Information Column 1**, **Footer Information Column 2**. None of
     these are required for the theme to render (each one is simply
     empty/absent until assigned — see "Empty-data behavior" below),
     but the header nav, utility links, and both footer link columns
     stay blank until they are. **Do not guess link destinations** —
     `ASSET-INVENTORY.md` lists the archive's exact recovered paths
     (About Us, Contact Us, FAQ, Services, Klarna FAQ, Order Tracking,
     Exclusive Brands) for reference, but every one needs to be
     confirmed against production first; this environment cannot check
     that itself.
   - **Social icons** (optional): set `mbf_social_instagram_url` /
     `mbf_social_twitter_url` (no registered Customizer UI yet — see
     the Customizer section of `README.md`) once you've confirmed the
     account URLs recovered in `ASSET-INVENTORY.md` are still current.
     Each icon only appears once its URL is set.
   - **Promotional Banners**: `Appearance → Customize → Promotional
     Banners` — pick a category and upload an image for up to 3
     banner slots. Each dropdown only lists categories that already
     exist in the catalog; there is no free-text URL field.
   - `Appearance → Customize → Site Identity` — upload the real logo
     (falls back to the recovered archive logo until then) and,
     optionally, the hero image.
   - `WooCommerce → Settings` — confirm the Shop, cart, checkout, and
     account pages are still the ones staging already uses (this
     theme does not create or move any WooCommerce pages).
8. Manually verify against the checklist below before calling it done.

## Empty-data behavior (read before filing anything below as a bug)

This theme never invents products, categories, or menu items. Several
sections are *designed* to render nothing when the underlying data
doesn't exist yet, rather than show placeholder/fake content:

| Symptom | Cause | Fix |
|---|---|---|
| Header cart control missing entirely | WooCommerce not active (`function_exists('WC')` is false) | Activate WooCommerce |
| Category grid / all 4 product rails empty | Either WooCommerce inactive, **or** active with 0 products/categories in the catalog yet | Activate WooCommerce; then confirm `Products → All Products` actually lists items on this install |
| "Shopping Categories" button visible but its dropdown says "No product categories yet" | Same as above | Same as above — the turquoise button itself always renders (it's static chrome, not data); only its dropdown content depends on the catalog |
| Primary nav / Utility Links / either footer "Information" column empty | No menu assigned to that location yet | Assign a menu at `Appearance → Menus` (see step 7 above) |
| Promotional banners section entirely absent | No category picked in `Appearance → Customize → Promotional Banners` yet | Pick a category for at least one of the 3 slots |
| Site title/footer say "Inspiration Station" (or any placeholder) | WordPress `Settings → General → Site Title` hasn't been changed | Change it there — the theme only ever reads this value, never sets it |

None of the rows above are code bugs; they're all one WordPress-admin
step away. If WooCommerce is confirmed active *and* the catalog has
products *and* a menu is assigned to a location and it's still empty,
that's a real bug — report it with which specific location/section.

## Manual verification checklist (staging)

- [ ] Homepage: hero, category grid, all four product rails,
      promotional banners, SEO block (exactly one `<h1>`), and benefits
      strip render once WooCommerce is active and has products.
- [ ] Footer: About the Store, both "Information" columns (once menus
      are assigned), and the newsletter signup form.
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
      scroll, mega menu and category grid reflow correctly. (There is
      no `overflow-x: hidden` anywhere in this theme masking a layout
      bug — if something still overflows at these widths, it's a real
      bug, report which element.)
- [ ] View source and confirm the enqueued CSS/JS `?ver=` values are
      numeric timestamps, not `1.1.0` — if you see `1.1.0` in a
      stylesheet URL after uploading, a caching layer served the old
      HTML `<head>` and needs purging (step 5 above); the theme itself
      only puts version *numbers* on `style.css` (its declared
      release), not on individual asset URLs.
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
3. Clear every caching layer listed in step 5 above after switching
   back, the same way.

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
