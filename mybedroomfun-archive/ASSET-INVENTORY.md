# Archived Asset Inventory

This inventory was rebuilt after the client supplied a genuine browser
save ("Save As → Webpage, Complete") of the Nov 20, 2023 Wayback
Machine capture, including its CSS/JS and every image that had loaded
by the time the page was saved. This environment cannot reach
`web.archive.org` or `mybedroomfun.com` itself (both are blocked by
this sandbox's network egress policy), so this file — not a live
browse — is the actual source of everything below.

## Recovered and used in the theme

| Asset | Recovered from | Used as |
|---|---|---|
| `assets/images/recovered/mybedroomfun-logo.png` | `MyBedroomFun-Logo-Final-Files-1.png` | Header logo fallback shown when no Customizer logo is set (`header.php`) |
| `assets/images/recovered/hero-banner-1.jpg` | `0920-RB-LoraDiCarlo-1-2.jpg` (2000×938) | Homepage hero default image |
| `assets/images/recovered/icon-delivery.png` | `archive-line.png` (120×120) | Benefits strip: "Delivery" |
| `assets/images/recovered/icon-returns.png` | `arrow-go-back-line.png` (120×120) | Benefits strip: "Returns" |
| `assets/images/recovered/icon-secure.png` | `secure-payment-line.png` (120×120) | Benefits strip: "Secure Payments" |
| `assets/images/recovered/icon-support.png` | `user-heart-line.png` (120×120) | Benefits strip: "Availability" (best-guess mapping — see below) |

Recovered exact **colors**: `#23c2be` (announcement bar, and the
"Shopping Categories" mega-menu trigger) and `#ff3c9c` (sitewide
`--primary-color`, applied to the round account/wishlist/cart icon
buttons — the archive's markup classed them `icon primary button
round`, Flatsome's filled circular button using that color).
Recovered **typeface**: Lato (site's `font-family:"Lato",sans-serif`)
— self-hosted locally in `assets/fonts/` (two weights, Latin subset,
~46KB total; no external font request at runtime).

Recovered **social links** (utility bar, `header-social-icons` list
item): `https://www.instagram.com/mybedroomfun/` and
`https://twitter.com/mybedroomfun`. Not set as defaults — like the
policy numbers below, these are Nov 2023 data; confirm the accounts
are still current before pasting them into
`mbf_social_instagram_url` / `mbf_social_twitter_url`.

## Recovered menu destinations — need production verification

These are the exact relative paths the archive's utility links and
footer "Information" columns pointed to on `mybedroomfun.com`. This
environment has no access to production and cannot confirm any of
these still exist or still 404 — that has to be checked by whoever has
access, page by page, before building the actual menus:

| Label (from archive) | Path (relative to mybedroomfun.com) |
|---|---|
| About Us | `/about-us/` |
| Contact Us | `/contact-us/` |
| FAQ | `/faq/` |
| Services | `/services/` |
| Klarna FAQ | `/what-is-klarna/` |
| Order Tracking | `/my-account/order-tracking/` |
| Exclusive Brands | `/exclusive-brands/` |

Do not paste these into `Appearance → Menus` as custom links without
checking each one loads on production first — a page can easily have
moved or been removed since Nov 2023, and a menu item pointing at a
404 is worse than no menu item.

Recovered **copy** (see README.md's "Recovered archived copy" table —
kept out of PHP defaults deliberately, explained there).

## Recovered but deliberately NOT used

- **Product photos** (`APA*`, `EL*`, `STDA*`, `033be4...`,
  `476_thumbnail...`, `9d0f5197...` — 30 files). These are per-SKU
  catalog images tied to specific (likely discontinued) 2023
  listings. Using them would misrepresent the current ~40,839-product
  catalog, so none are in the theme package.
- **`Klarna-hero-scaled.jpg`**. Not the homepage hero — it's a
  separate full-width "what is Klarna" financing promo further down
  the archived page. Not used because (a) it asserts an active Klarna
  partnership that can't be verified as current, and (b) it's a
  policy/partnership claim, not a generic layout asset.
- **Flatsome's icon font** (`icon-user`, `icon-shopping-bag`,
  `icon-heart`, etc.). The archive's header account/wishlist/cart
  controls and the "Shopping Categories" mega-menu icon all use
  Flatsome's own bundled icon font. No font file was captured in the
  save (and it wouldn't be usable if it had been — it ships with the
  paid Flatsome theme, and the build brief excludes Flatsome source).
  The theme uses original, hand-drawn inline SVG line icons instead
  (`mbf_icon_svg()` in `inc/template-tags.php` — account/wishlist/cart),
  each with a visually-hidden text label for screen readers. This is
  an honest original substitute, not a claim of visual identity with
  Flatsome's bundled icon set.

## Could not be recovered at all

- **4 of the 5 homepage hero slides.** The archived hero was an
  image-only rotating banner (no headline/subhead/CTA text — that's
  why this build's hero no longer has invented copy over it). Only
  the first slide (`0920-RB-LoraDiCarlo-1-2.jpg`) had loaded by the
  time the page was saved; the other four
  (`0920-RB-ArcwaveIon.jpg`, `1220-RB-CuteLittleFuckers.jpg`,
  `0621-RB-BVibe2.jpg`, `0621-RB-Aneros-scaled.jpg`) were
  lazy-loaded placeholders that never fired, so no image data exists
  for them anywhere in the save. **This build ships one static image,
  not a rotating slider** — building carousel JS for 4 images we
  don't have would be pretending the section is complete when it
  isn't. If the client can supply the missing 4 images (or new ones),
  ask and a real rotation can be added.
- **Exact container max-width.** Flatsome computes this from a
  per-site dynamic stylesheet that isn't part of a static page save.
  The theme uses `1200px`, which is Flatsome's documented framework
  default — a reasonable default, not a confirmed-from-snapshot value.
- **Checking "nearby snapshots"** for anything missing above. This
  environment cannot reach `web.archive.org` at all (network egress
  policy), so no other capture date could be checked. If the client
  can access the Wayback Machine's calendar view for
  `mybedroomfun.com` and pull nearby snapshots or the missing images
  directly, share them and this inventory gets more complete.

## Known quirks preserved, not "fixed"

- **Two footer columns are both literally titled "Information"** in
  the archive. The rebuilt footer keeps that exact duplicate labeling
  rather than inventing a more sensible pair of names — matching the
  archive means matching its quirks too, not idealizing them. Flag
  this to the client; renaming one column (e.g. to "Customer Service")
  is a one-line change in `footer.php` if they'd rather fix it than
  preserve it.
- **`icon-support.png`'s exact original placement is a guess.** The
  archive's footer benefits strip had 4 items with 4 icons; only 3 of
  the 4 `<img alt="">` attributes named their icon file
  (`archive-line`, `arrow-go-back-line`, `secure-payment-line`) — the
  4th ("We are available 24/7") had a blank alt attribute in the
  saved HTML, so its icon file can't be matched with certainty.
  `user-heart-line.png` is used there as the best remaining candidate,
  not a confirmed match.
