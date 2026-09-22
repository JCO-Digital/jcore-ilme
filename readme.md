# JCORE3 Ilme

The JCORE base theme. It is copied into a project and detached from this
repository, so it deliberately holds as little as possible: everything that may
need to be updated later lives in the [`jcore/ydin`](https://github.com/JCO-Digital/jcore-ydin)
composer package, and this theme only wires it up.

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

| Concern | Lives in |
| --- | --- |
| Theme supports, menus, body classes, media handling, login and admin styling, comments, analytics, ACF JSON storage, options pages, block registration, editor restrictions | `jcore/ydin` |
| Header, footer, navigation and accordion blocks | `lohko` plugin |
| Grid and column blocks | `ruudukko` plugin |
| Archive filtering and pagination | `dynamic-archive` plugin |

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

## Settings

Options pages are built from an array, so a project adds its own fields with a
filter rather than a new class:

```php
add_filter(
    'jcore_init_settings_fields',
    function ( $fields ) {
        $fields['keys']['fields']['hubspot_id'] = array(
            'type'    => 'text',
            'label'   => 'HubSpot ID',
            'default' => '',
        );

        return $fields;
    }
);
```

Read them with `Jcore\Ydin\Settings\AcfOptions::get( 'keys', 'hubspot_id' )`.

## Development

```
make install   Install dependencies.
make build     Build once.
make dev       Install, then watch.
```
