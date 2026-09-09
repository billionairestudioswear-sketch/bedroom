# Archived Asset Inventory

Per the build brief, archived imagery is a *visual reference only*. This
theme does not embed, link to, or bundle any image, icon, or graphic
pulled from the Wayback Machine capture or from mybedroomfun.com.

## Why

- Ownership of the archived photography/graphics (product photos, hero
  banners, category tiles, logo artwork) cannot be reasonably confirmed
  from this environment.
- The live site's Flatsome-based markup and any of its bundled assets
  are excluded outright (license terms unknown, and the brief
  disallows using Flatsome source).
- No images could be fetched from web.archive.org or mybedroomfun.com
  from this build environment (both are outside the sandbox's network
  allowlist), so nothing was downloaded even for offline inspection.

## What the theme ships instead

Every visual slot is either:

1. **CSS-only** (category placeholder tiles, banner backgrounds,
   badges) — solid/gradient fills using the theme's own color tokens,
   zero image files.
2. **A theme-mod driven `<img>` that renders nothing until an image is
   set** (hero image, banner images, category thumbnails via
   WooCommerce's own category `thumbnail_id` term meta, custom logo).
   These fall back to the CSS placeholder or are simply omitted — no
   broken image requests.
3. **`screenshot.png`** — a theme-generated abstract mockup (solid
   color blocks matching the layout: announcement bar, header, dark
   nav, hero, category grid, product rail, footer) built purely with
   PHP GD, not derived from any archived or live screenshot.

## Missing / needs real assets before launch

Supply these through **Appearance → Customize** (theme mods) once the
theme is installed on staging:

| Slot | Theme mod | Notes |
|---|---|---|
| Site logo | Custom Logo (core) | 220×60 recommended |
| Homepage hero image | `mbf_hero_image` | Preloaded as the LCP element — use a compressed, appropriately licensed photo |
| Category tile images | WooCommerce category "Thumbnail" (per-term, in Products → Categories) | Falls back to a turquoise gradient tile if unset |
| Promotional banner 1 & 2 | `mbf_banner_1_image` / `mbf_banner_2_image` + matching `_title` / `_url` | Two homepage banner slots |

Product photography itself is **not a theme concern** — it comes from
the existing WooCommerce product catalog (media library), which this
theme reads dynamically and never duplicates or modifies.
