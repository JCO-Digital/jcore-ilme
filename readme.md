# JCORE3 Ilme Multisite

The JCORE base theme for **multisite networks** (branch `multisite`, from
`irishcoffee`; first built for the Vaskars/Vivere network). One theme serves
every site; each site gets its own colours, heading font, logo and favicon
without a child theme. Otherwise it is the JCORE base theme. It is copied into a project and detached from this
repository, so it deliberately holds as little as possible: everything that may
need to be updated later lives in the [`jcore/ydin`](https://github.com/JCO-Digital/jcore-ydin)
composer package, and this theme only wires it up.

## Branch

`multisite` is based on **`irishcoffee`**, the JCORE3 Ilme base theme. It
holds only the multisite additions on top of it. To bring in later base
theme changes, merge `irishcoffee` into `multisite`:

```sh
git fetch origin
git merge origin/irishcoffee
```

The branch was created from `origin/irishcoffee`, so at first it tracks that
branch. Push it under its own name with `git push -u origin multisite`, so a
plain `git push` never goes to `irishcoffee`.

## What is here

```
functions.php        Entry point: autoloader and includes.
includes/setup.php   Menus, modules, and which Ydin features to run.
includes/assets.php  The theme's own front end bundle.
templates/           Block templates.
parts/               Template parts.
patterns/            Block patterns.
theme.json           Colours, typography, spacing, layout.
src/                 SCSS and TypeScript sources, built into dist/.
views/               Twig templates for teases and images.
```

## What is not here

| Concern                                                                                                                                                                     | Lives in                 |
| --------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------ |
| Theme supports, menus, body classes, media handling, login and admin styling, comments, ACF JSON storage, block registration, editor restrictions | `jcore/ydin`             |
| Header, footer, navigation and accordion blocks                                                                                                                             | `lohko` plugin           |
| Grid and column blocks                                                                                                                                                      | `ruudukko` plugin        |

## Multisite

Every site runs this theme with the same theme.json, except what its
`sites/<key>.json` overrides. Because SCSS, templates and blocks use the
presets (`var(--wp--preset--color--brand)`, `var(--wp--preset--font-family--heading)`),
an override restyles everything, in the editor and on the login screen too.
`includes/sites.php` documents the file format.

```
includes/sites.php         Site key per blog ID, applies sites/<key>.json, logo, favicon, body class.
sites/alpha.json           Example site 1: theme.json's own values, so only its logos.
sites/beta.json            Example site 2: its colours, CTA gradient, button hovers and heading font (Figtree).
assets/favicon/<key>/      favicon.svg, favicon.ico (16/32/48), apple-touch-icon.png per site.
assets/images/logo-*.svg   Placeholder logos (fill="currentColor", inlined).
```

- **Site keys** are mapped by blog ID (the same in every environment, unlike
  the domains): blog 2 is `alpha`, blog 3 is `beta`. Rename them after the
  project's sites in `site_key()`, or use the `jcore_ilme_sites` filter. The
  network root has no key and shows theme.json as is.
- **Colours and fonts:** override existing slugs only. A font family entry
  replaces the whole theme.json family with that slug, so only the site's own
  font files get an `@font-face`.
- **Logo:** `logo` and `footerLogo` (light version for the dark footer and
  login) are used when no logo is set in the Customizer. SVGs are inlined, so
  `fill="currentColor"` follows the text colour.
- **Favicon:** printed from `assets/favicon/<key>/` unless the site has a Site
  Icon. The example ones are placeholders: make them from each logo's mark.
- **Per-site CSS:** the body has `site-<key>` for the rare rule that can't be
  a preset (example: `.site-beta` in `partials/_footer.scss`).

## What else this branch adds

- The **style guide** pattern (`jcore/style-guide`): every base block and
  style on one page, per site. Create a page "Tyyliopas" from it while
  building; trim it to what the customer uses before handover.
- Block styles (`includes/gutenberg.php`): image *Wide band* and *Rounded
  corners*, group *Rounded corners* and *CTA*, grid *Centred vertically*.
- Cropped images keep their focal point (`src/js/editor.ts`): band images and
  image and text images get `scale: cover`, so a focal point picker (JCORE
  Kuva Kohde) can be used on them.
- One `<title>` with Yoast on block templates (`includes/setup.php`).
- The Social Icons block is allowed.

## Requirements

- `jcore/ydin` **^5** in the project's `composer.json`. With ydin 3 the site
  fails with `Class "Jcore\Ydin\WordPress\Acf" not found`. After changing it,
  run `composer install` (or `composer update jcore/ydin`).
- Lohko (navigation, footer, accordion) and Ruudukko (grid). The footer
  styles (`partials/_footer.scss`) and the per-site footer logo
  (`footer_site_defaults()` in `includes/sites.php`) are written for a Lohko
  footer with logo and content areas; with a bare footer they do nothing.

## Starting a project from this branch

1. Copy the theme into the project and rename it as usual.
2. Make the project a multisite network (as in vaskars-vivere):
   - `env-values.toml`: `multisite = true`
   - `jcore.toml`: `multiSite = true`, every site's local domain in
     `domains`, and a `replace` entry per site for the remote → local domain
     (`'site.example.com|site.localhost'`)
   - restart jcore, create the sites and network-enable the theme
3. Map each blog ID to its key in `site_key()` (or the `jcore_ilme_sites`
   filter). Check the IDs with `wp site list`.
4. Rename the site keys (`alpha`, `beta`) after the sites, with their
   `sites/<key>.json` and `assets/favicon/<key>/`.
5. Put the first site's tokens in theme.json and the others' overrides in
   their site files. Replace the placeholder logos, favicons and the style
   guide's photo (`assets/images/placeholder.jpg`).
6. Swap the example fonts (Rethink Sans, Figtree) for the design's.

## Checking a site's look without a network

On a single-site install (blog 1, no key) you can still check that a site
file applies. Map blog 1 to a key for one WP-CLI call:

```sh
wp eval '$s = wp_get_global_settings(); echo $s["color"]["palette"]["theme"][0]["color"], " ", Jcore\Ilme\site_key();' \
  --exec="WP_CLI::add_wp_hook('jcore_ilme_sites', function () { return array( 1 => 'beta' ); });"
```

To see it in the browser, add the same filter temporarily to a mu-plugin.

## Adding a feature back, or taking one away

`includes/setup.php` lists the Ydin features the theme initializes. Drop a line to
remove a feature. To keep a feature but change it, use its filters — they are
listed in the Ydin readme.

```php
// Bring the Quote block back into the inserter.
add_filter(
    'jcore_restricted_blocks',
    function ( $blocks ) {
        return array_diff( $blocks, array( 'core/quote' ) );
    }
);
```

## Development

```
make install   Install dependencies.
make build     Build once.
make dev       Install, then watch.
```
