<?php
/**
 * Title: Tyyliopas
 * Slug: jcore/style-guide
 * Description: Every base block and every block style on one page: colours, font sizes, headings, text, buttons, lists, pullquote, tables, file, separators, images, gallery, cover, group, Ruudukko grids, spacers and Lohko accordions. Create a page from it while building, to check the theme; trim it to what the customer uses before handover.
 * Categories: text
 * Keywords: style guide, tyyliopas, stilguide, blocks
 * Viewport Width: 1440
 *
 * @package Jcore\Ilme
 */

$image   = esc_url( get_theme_file_uri( 'assets/images/placeholder.jpg' ) );
$text    = 'Lyhyt esimerkkiteksti. Vaihda teeman esimerkkitekstit projektin omiin, kun sivustolla on sisältöä, niin tyyliopas näyttää oikeat tekstin pituudet.';
$label   = static function ( string $name ): string {
	return '<!-- wp:paragraph {"className":"style-guide-label"} -->' . "\n" . '<p class="style-guide-label">' . esc_html( $name ) . '</p>' . "\n" . '<!-- /wp:paragraph -->';
};
$para    = static function ( string $html, string $class = '' ): string {
	$attrs = $class ? ' {"className":"' . $class . '"}' : '';
	$cls   = $class ? ' class="' . $class . '"' : '';
	return '<!-- wp:paragraph' . $attrs . ' -->' . "\n" . '<p' . $cls . '>' . $html . '</p>' . "\n" . '<!-- /wp:paragraph -->';
};
$settings = wp_get_global_settings();
$palette  = $settings['color']['palette']['theme'] ?? array();
$sizes    = $settings['typography']['fontSizes']['theme'] ?? array();

/**
 * Dark text on light colours, white text on dark ones.
 *
 * @param string $hex A #rrggbb colour.
 */
$text_on = static function ( string $hex ): string {
	$hex = ltrim( $hex, '#' );
	if ( 6 !== strlen( $hex ) ) {
		return 'black';
	}
	list( $r, $g, $b ) = array_map( 'hexdec', str_split( $hex, 2 ) );
	return ( 0.299 * $r + 0.587 * $g + 0.114 * $b ) > 150 ? 'black' : 'white';
};

$table = static function ( string $class = '' ): string {
	$attrs = $class ? ',"className":"' . $class . '"' : '';
	$cls   = $class ? ' ' . $class : '';
	$rows  = '';
	foreach ( array( array( 'Palvelu yksi', 'alk. 45 €/h' ), array( 'Palvelu kaksi', 'pyynnöstä' ), array( 'Palvelu kolme', 'alk. 90 €/h' ), array( 'Palvelu neljä', 'alk. 60 €/h' ) ) as $row ) {
		$rows .= '<tr><td>' . $row[0] . '</td><td>' . $row[1] . '</td></tr>';
	}
	return '<!-- wp:table {"hasFixedLayout":false' . $attrs . '} -->' . "\n"
		. '<figure class="wp-block-table' . $cls . '"><table><thead><tr><th>Palvelu</th><th>Hinta</th></tr></thead><tbody>' . $rows . '</tbody><tfoot><tr><td>Yhteensä</td><td>—</td></tr></tfoot></table><figcaption class="wp-element-caption">Taulukon kuvateksti.</figcaption></figure>' . "\n"
		. '<!-- /wp:table -->';
};

$img = static function ( string $class = '', string $caption = '' ) use ( $image ): string {
	$attrs = $class ? ',"className":"' . $class . '"' : '';
	$cls   = $class ? ' ' . $class : '';
	$cap   = $caption ? '<figcaption class="wp-element-caption">' . $caption . '</figcaption>' : '';
	return '<!-- wp:image {"sizeSlug":"large"' . $attrs . '} -->' . "\n"
		. '<figure class="wp-block-image size-large' . $cls . '"><img src="' . $image . '" alt=""/>' . $cap . '</figure>' . "\n"
		. '<!-- /wp:image -->';
};

$gallery = static function ( string $class = '' ) use ( $image ): string {
	$attrs = $class ? ',"className":"' . $class . '"' : '';
	$cls   = $class ? ' ' . $class : '';
	$item  = '<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->' . "\n" . '<figure class="wp-block-image size-large"><img src="' . $image . '" alt=""/><figcaption class="wp-element-caption">Kuvateksti</figcaption></figure>' . "\n" . '<!-- /wp:image -->';
	return '<!-- wp:gallery {"linkTo":"none"' . $attrs . '} -->' . "\n"
		. '<figure class="wp-block-gallery has-nested-images columns-default is-cropped' . $cls . '">' . $item . "\n\n" . $item . "\n\n" . $item . '<figcaption class="blocks-gallery-caption wp-element-caption">Gallerian kuvateksti.</figcaption></figure>' . "\n"
		. '<!-- /wp:gallery -->';
};

$buttons = '<!-- wp:buttons -->' . "\n" . '<div class="wp-block-buttons"><!-- wp:button -->' . "\n" . '<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#">Painike (fill)</a></div>' . "\n" . '<!-- /wp:button -->' . "\n\n"
	. '<!-- wp:button {"className":"is-style-outline"} -->' . "\n" . '<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#">Painike (outline)</a></div>' . "\n" . '<!-- /wp:button --></div>' . "\n" . '<!-- /wp:buttons -->';

$accordion_item = static function ( string $id, string $title, string $body ): string {
	return '<!-- wp:lohko/accordion-item -->' . "\n"
		. '<div class="wp-block-lohko-accordion-item" id="accordion-' . $id . '" data-accordion-id="' . $id . '" data-wp-class--active="state.isActive"><h3 class="accordion-item__header-wrapper"><button id="accordion-toggle-' . $id . '" class="accordion-item__header" data-wp-bind--aria-expanded="state.isActive" data-wp-on-async--click="actions.openAccordion" aria-controls="accordion-item-' . $id . '"><span class="accordion-item__title">' . $title . '</span><span class="accordion-item__toggle"><span class="accordion-item__toggle-icon"></span><span class="screen-reader-text">Toggle</span></span></button></h3><div id="accordion-item-' . $id . '" class="accordion-item__content" role="region" aria-labelledby="accordion-toggle-' . $id . '"><!-- wp:paragraph -->' . "\n" . '<p>' . $body . '</p>' . "\n" . '<!-- /wp:paragraph --></div></div>' . "\n"
		. '<!-- /wp:lohko/accordion-item -->';
};
$accordions = static function ( string $prefix, string $class = '' ) use ( $accordion_item, $text ): string {
	$attrs = $class ? ' {"className":"' . $class . '"}' : '';
	$cls   = $class ? ' ' . $class : '';
	return '<!-- wp:lohko/accordions' . $attrs . ' -->' . "\n"
		. '<div class="wp-block-lohko-accordions' . $cls . '" data-wp-interactive="lohko/accordions" data-wp-context="{&quot;currentAccordion&quot;:&quot;&quot;}" data-wp-init="actions.parseUrl">'
		. $accordion_item( $prefix . '1', 'Ensimmäinen kysymys', $text ) . "\n\n" . $accordion_item( $prefix . '2', 'Toinen kysymys', $text )
		. '</div>' . "\n" . '<!-- /wp:lohko/accordions -->';
};

$spacer = static function ( string $style ): string {
	return '<!-- wp:spacer {"height":"1rem","className":"is-style-' . $style . '"} -->' . "\n" . '<div style="height:1rem" aria-hidden="true" class="wp-block-spacer is-style-' . $style . '"></div>' . "\n" . '<!-- /wp:spacer -->';
};

$separator = static function ( string $style = '' ): string {
	$attrs = $style ? ' {"className":"is-style-' . $style . '"}' : '';
	$cls   = $style ? ' is-style-' . $style : '';
	return '<!-- wp:separator' . $attrs . ' -->' . "\n" . '<hr class="wp-block-separator has-alpha-channel-opacity' . $cls . '"/>' . "\n" . '<!-- /wp:separator -->';
};

// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup built above from theme values.
echo $label( 'Värit (theme.json palette)' );
?>

<!-- wp:group {"className":"style-guide-colours","layout":{"type":"default"}} -->
<div class="wp-block-group style-guide-colours">
<?php
foreach ( $palette as $colour ) {
	$slug = sanitize_key( $colour['slug'] );
	$on   = $text_on( (string) $colour['color'] );
	echo '<!-- wp:paragraph {"backgroundColor":"' . $slug . '","textColor":"' . $on . '"} -->' . "\n"
		. '<p class="has-' . $on . '-color has-' . $slug . '-background-color has-text-color has-background"><strong>' . esc_html( $colour['name'] ) . '</strong><br>' . $slug . '<br>' . esc_html( $colour['color'] ) . '</p>' . "\n"
		. '<!-- /wp:paragraph -->' . "\n\n";
}
?>
</div>
<!-- /wp:group -->

<?php
echo $label( 'Fonttikoot (font sizes)' ) . "\n\n";
foreach ( $sizes as $size ) {
	$slug = sanitize_key( $size['slug'] );
	echo '<!-- wp:paragraph {"fontSize":"' . $slug . '"} -->' . "\n" . '<p class="has-' . $slug . '-font-size">' . esc_html( $size['name'] ) . ' (' . $slug . ')</p>' . "\n" . '<!-- /wp:paragraph -->' . "\n\n";
}

echo $label( 'Otsikot (heading)' ) . "\n\n";
for ( $level = 1; $level <= 6; $level++ ) {
	$attrs = 2 === $level ? '' : ' {"level":' . $level . '}';
	echo '<!-- wp:heading' . $attrs . ' -->' . "\n" . '<h' . $level . ' class="wp-block-heading">Otsikko ' . $level . '</h' . $level . '>' . "\n" . '<!-- /wp:heading -->' . "\n\n" . $para( $text ) . "\n\n";
}

echo $label( 'Teksti (paragraph)' ) . "\n\n";
echo $para( 'Ingressi (is-style-ingress): sivun tai artikkelin aloituskappale. ' . $text, 'is-style-ingress' ) . "\n\n";
echo $para( 'Leipäteksti, jossa on <a href="#">linkki</a>, <strong>lihavointi</strong> ja <em>kursivointi</em>. ' . $text ) . "\n\n";
echo $para( 'Toinen kappale, josta näkee kappaleiden välin. ' . $text ) . "\n\n";
echo $para( 'Pieni teksti ennen otsikkoa (mini-luokka)', 'mini' ) . "\n\n";

echo $label( 'Painikkeet (buttons): fill, outline' ) . "\n\n" . $buttons . "\n\n";
?>
<!-- wp:group {"backgroundColor":"dark","textColor":"white","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-white-color has-dark-background-color has-text-color has-background"><?php echo $para( 'Tummalla taustalla:' ) . "\n\n" . $buttons; ?></div>
<!-- /wp:group -->

<?php
echo $label( 'Listat (list)' );
?>

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li>Listan kohta</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Toinen kohta, jossa on alalista<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li>Alalistan kohta</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Kolmas kohta</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:list {"ordered":true} -->
<ol class="wp-block-list"><!-- wp:list-item -->
<li>Numeroitu kohta</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Toinen numeroitu kohta</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Kolmas numeroitu kohta</li>
<!-- /wp:list-item --></ol>
<!-- /wp:list -->

<?php
echo $label( 'Nosto (pullquote)' );
?>

<!-- wp:pullquote -->
<figure class="wp-block-pullquote"><blockquote><p>Nostettu lainaus tekstistä: lyhyt ja ytimekäs ajatus.</p><cite>Asiakas, Helsinki</cite></blockquote></figure>
<!-- /wp:pullquote -->

<?php
echo $label( 'Taulukko (table): regular' ) . "\n\n" . $table() . "\n\n";
echo $label( 'Taulukko (table): stripes' ) . "\n\n" . $table( 'is-style-stripes' ) . "\n\n";

echo $label( 'Tiedosto (file)' );
?>

<!-- wp:file {"href":"<?php echo $image; ?>"} -->
<div class="wp-block-file"><a href="<?php echo $image; ?>">Hinnasto 2026.pdf</a><a href="<?php echo $image; ?>" class="wp-block-file__button wp-element-button" download>Lataa</a></div>
<!-- /wp:file -->

<?php
echo $label( 'Erotin (separator): default, wide, dots' ) . "\n\n" . $separator() . "\n\n" . $separator( 'wide' ) . "\n\n" . $separator( 'dots' ) . "\n\n";

echo $label( 'Kuva (image): default' ) . "\n\n" . $img( '', 'Kuvateksti.' ) . "\n\n";
echo $label( 'Kuva (image): rounded corners' ) . "\n\n" . $img( 'is-style-rounded-corners', 'Pyöristetyt kulmat.' ) . "\n\n";
echo $label( 'Kuva (image): rounded' ) . "\n\n";
?>
<!-- wp:image {"width":"200px","height":"200px","scale":"cover","sizeSlug":"large","className":"is-style-rounded"} -->
<figure class="wp-block-image size-large is-resized is-style-rounded"><img src="<?php echo $image; ?>" alt="" style="object-fit:cover;width:200px;height:200px"/></figure>
<!-- /wp:image -->

<?php
echo $label( 'Galleria (gallery): default' ) . "\n\n" . $gallery() . "\n\n";
echo $label( 'Galleria (gallery): rounded corners' ) . "\n\n" . $gallery( 'is-style-rounded-corners' ) . "\n\n";

echo $label( 'Kansikuva (cover)' );
?>

<!-- wp:cover {"url":"<?php echo $image; ?>","dimRatio":40,"overlayColor":"black","isUserOverlayColor":true,"layout":{"type":"constrained"}} -->
<div class="wp-block-cover"><img class="wp-block-cover__image-background" alt="" src="<?php echo $image; ?>" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-black-background-color has-background-dim-40 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:heading {"textColor":"white"} -->
<h2 class="wp-block-heading has-white-color has-text-color">Kansikuvan otsikko</h2>
<!-- /wp:heading --></div></div>
<!-- /wp:cover -->

<?php
echo $label( 'Ryhmä (group): taustaväri' );
?>

<!-- wp:group {"backgroundColor":"light","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-light-background-color has-background"><?php echo $para( 'Ryhmä taustavärillä: teeman täyte reunoilla. ' . $text ); ?></div>
<!-- /wp:group -->

<?php
echo $label( 'Ruudukko (jcore/grid): palstat' );
$column = static function ( string $title ) use ( $text ): string {
	return '<!-- wp:jcore/column -->' . "\n" . '<div class="wp-block-jcore-column"><!-- wp:heading {"level":4} -->' . "\n" . '<h4 class="wp-block-heading">' . $title . '</h4>' . "\n" . '<!-- /wp:heading -->' . "\n\n" . '<!-- wp:paragraph -->' . "\n" . '<p>' . esc_html( $text ) . '</p>' . "\n" . '<!-- /wp:paragraph --></div>' . "\n" . '<!-- /wp:jcore/column -->';
};
?>

<!-- wp:jcore/grid -->
<section class="wp-block-jcore-grid jgrid"><?php echo $column( 'Palsta 1' ) . "\n\n" . $column( 'Palsta 2' ); ?></section>
<!-- /wp:jcore/grid -->

<?php echo $para( 'Kolme palstaa, puhelimella yksi (breakpoints xs 1, md 3):', 'mini' ); ?>

<!-- wp:jcore/grid {"autoSize":false,"breakpoints":{"xs":1,"md":3},"align":"wide"} -->
<section class="wp-block-jcore-grid alignwide jgrid columns-xs-1 columns-md-3"><?php echo $column( 'Palsta 1' ) . "\n\n" . $column( 'Palsta 2' ) . "\n\n" . $column( 'Palsta 3' ); ?></section>
<!-- /wp:jcore/grid -->

<?php
echo $label( 'Välikkeet (spacer): sm, md, lg, xl' ) . "\n\n";
echo $para( 'Käytä aina näitä välikkeen tyylejä (Välike → Tyylit), ei pelkkää korkeutta pikseleinä: tyylit pienenevät mobiilissa.' ) . "\n\n";
echo '<!-- wp:group {"className":"style-guide-spacers","layout":{"type":"default"}} -->' . "\n" . '<div class="wp-block-group style-guide-spacers">';
foreach ( array( 'sm', 'md', 'lg', 'xl' ) as $style ) {
	echo $para( 'Välike ' . $style . ' (is-style-' . $style . '):', 'mini' ) . "\n\n" . $spacer( $style ) . "\n\n";
}
echo '</div>' . "\n" . '<!-- /wp:group -->' . "\n\n";

echo $label( 'Lohko-haitari (lohko/accordions): default' ) . "\n\n" . $accordions( 'sgd' ) . "\n\n";
echo $label( 'Lohko-haitari (lohko/accordions): compact' ) . "\n\n" . $accordions( 'sgc', 'is-style-compact' ) . "\n";
// phpcs:enable
