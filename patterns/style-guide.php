<?php
/**
 * Title: Style guide
 * Slug: jcore/style-guide
 * Description: The base blocks the customer uses, with their styles: headings, text and links, colours, buttons, lists, pullquote, images, gallery, cover, embed, groups and the CTA box, Ruudukko grids and image and text sections, responsive spacers, tables, Lohko accordions and file. For checking the theme against the design on each site.
 * Categories: text
 * Keywords: style guide, tyyliopas, stilguide, blocks
 * Viewport Width: 1440
 *
 * @package Jcore\Ilme
 */

$image    = get_theme_file_uri( 'assets/images/placeholder.jpg' );
$text     = 'A short sample text. Replace the theme\'s sample texts with the project\'s own when the site has content, so the style guide shows real text lengths.';
$prices   = array( array( 'Service one', 'from 45 €/h' ), array( 'Service two', 'on request' ), array( 'Service three', 'from 90 €/h' ) );
$rows     = static function ( int $count ) use ( $prices ): string {
	$html = '';
	foreach ( array_slice( $prices, 0, $count ) as $row ) {
		$html .= '<tr><td>' . esc_html( $row[0] ) . '</td><td>' . esc_html( $row[1] ) . '</td></tr>';
	}
	return $html;
};
?>
<!-- wp:paragraph {"className":"style-guide-label"} -->
<p class="style-guide-label">Otsikot (heading)</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">Otsikko 1</h1>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php echo esc_html( $text ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Otsikko 2</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php echo esc_html( $text ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Otsikko 3</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php echo esc_html( $text ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":4} -->
<h4 class="wp-block-heading">Otsikko 4</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php echo esc_html( $text ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":5} -->
<h5 class="wp-block-heading">Otsikko 5</h5>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php echo esc_html( $text ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":6} -->
<h6 class="wp-block-heading">Otsikko 6</h6>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php echo esc_html( $text ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:paragraph {"className":"style-guide-label"} -->
<p class="style-guide-label">Teksti (paragraph)</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"fontSize":"ingress"} -->
<p class="has-ingress-font-size">Ingressi (koko Ingress): <?php echo esc_html( $text ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Leipäteksti, jossa on <a href="#">tavallinen tekstilinkki</a>, <strong>lihavointi</strong> ja <em>kursiivi</em>. <?php echo esc_html( $text ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"fontSize":"small"} -->
<p class="has-small-font-size">Pieni teksti (small), esimerkiksi huomautuksiin.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"fontSize":"mini"} -->
<p class="has-mini-font-size">Minikoko (mini), esimerkiksi kuvateksteihin.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"dropCap":true} -->
<p class="has-drop-cap">Anfangi (drop cap): <?php echo esc_html( $text ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:paragraph {"className":"style-guide-label"} -->
<p class="style-guide-label">Värit (sivuston omat arvot)</p>
<!-- /wp:paragraph -->

<!-- wp:group {"className":"style-guide-colours","layout":{"type":"default"}} -->
<div class="wp-block-group style-guide-colours"><!-- wp:paragraph {"backgroundColor":"brand","textColor":"light"} -->
<p class="has-light-color has-brand-background-color has-text-color has-background">Brand<br>brand</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"backgroundColor":"brand-dark","textColor":"light"} -->
<p class="has-light-color has-brand-dark-background-color has-text-color has-background">Brand dark<br>brand-dark</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"backgroundColor":"accent","textColor":"accent-contrast"} -->
<p class="has-accent-contrast-color has-accent-background-color has-text-color has-background">Accent<br>accent</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"backgroundColor":"accent-contrast","textColor":"dark"} -->
<p class="has-dark-color has-accent-contrast-background-color has-text-color has-background">Accent contrast<br>accent-contrast</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"backgroundColor":"surface","textColor":"dark"} -->
<p class="has-dark-color has-surface-background-color has-text-color has-background">Surface<br>surface</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"backgroundColor":"white","textColor":"dark"} -->
<p class="has-dark-color has-white-background-color has-text-color has-background">White<br>white</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"backgroundColor":"light","textColor":"dark"} -->
<p class="has-dark-color has-light-background-color has-text-color has-background">Light<br>light</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"backgroundColor":"dark","textColor":"light"} -->
<p class="has-light-color has-dark-background-color has-text-color has-background">Dark<br>dark</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"backgroundColor":"dark-lighter","textColor":"light"} -->
<p class="has-light-color has-dark-lighter-background-color has-text-color has-background">Dark lighter<br>dark-lighter</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"backgroundColor":"black","textColor":"light"} -->
<p class="has-light-color has-black-background-color has-text-color has-background">Black<br>black</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"backgroundColor":"success","textColor":"light"} -->
<p class="has-light-color has-success-background-color has-text-color has-background">Success<br>success</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"backgroundColor":"warning","textColor":"dark"} -->
<p class="has-dark-color has-warning-background-color has-text-color has-background">Warning<br>warning</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"backgroundColor":"failure","textColor":"light"} -->
<p class="has-light-color has-failure-background-color has-text-color has-background">Failure<br>failure</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:paragraph {"className":"style-guide-label"} -->
<p class="style-guide-label">Painikkeet (buttons)</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#">Painike (oletus)</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#">Outline (vaalea)</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:group {"className":"is-style-rounded-corners","backgroundColor":"brand","textColor":"light","layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-rounded-corners has-light-color has-brand-background-color has-text-color has-background"><!-- wp:paragraph -->
<p>Samat painikkeet tummalla (brand) taustalla:</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#">Painike (oletus)</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#">Outline (vaalea)</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:paragraph {"className":"style-guide-label"} -->
<p class="style-guide-label">Listat (list)</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li>Luettelo, pallukat</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Toinen kohta, jossa on pidempi teksti, joka jatkuu toiselle riville kapealla näytöllä</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:list {"ordered":true} -->
<ol class="wp-block-list"><!-- wp:list-item -->
<li>Numeroitu luettelo</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Toinen kohta</li>
<!-- /wp:list-item --></ol>
<!-- /wp:list -->

<!-- wp:list {"className":"arrow-list"} -->
<ul class="wp-block-list arrow-list"><!-- wp:list-item -->
<li><a href="#">Nuolilista (arrow-list), linkki</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="#">Toinen linkki</a></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:paragraph {"className":"style-guide-label"} -->
<p class="style-guide-label">Lainaus (pullquote)</p>
<!-- /wp:paragraph -->

<!-- wp:pullquote -->
<figure class="wp-block-pullquote"><blockquote><p>Pullquote: nostettu lainaus tekstistä.</p><cite>Asiakas, Karis</cite></blockquote></figure>
<!-- /wp:pullquote -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:paragraph {"className":"style-guide-label"} -->
<p class="style-guide-label">Kuvat (image)</p>
<!-- /wp:paragraph -->

<!-- wp:image {"sizeSlug":"large"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( $image ); ?>" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:image {"sizeSlug":"large"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( $image ); ?>" alt=""/><figcaption class="wp-element-caption">Kuva ja kuvateksti</figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"sizeSlug":"large","className":"is-style-rounded-corners"} -->
<figure class="wp-block-image size-large is-style-rounded-corners"><img src="<?php echo esc_url( $image ); ?>" alt=""/><figcaption class="wp-element-caption">Kuva, tyyli Rounded corners</figcaption></figure>
<!-- /wp:image -->

<!-- wp:paragraph -->
<p><strong>Wide band -tyyli</strong> (Kuva-lohko → Tyylit → Wide band) rajaa minkä tahansa kuvan matalaksi, leveäksi kaistaleeksi pyöristetyin kulmin: työpöydällä 872 × 366, mobiilissa 16:9. Kuvaa ei tarvitse rajata etukäteen, eikä kuvasuhdetta tarvitse asettaa erikseen.</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li><strong>Valitse kuvan tärkein kohta:</strong> Kuva-lohko → Asetukset (ratas) → Focal Point. Rajaus pysyy sen ympärillä kaikilla näytön koilla.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><strong>Kuvan koko:</strong> vähintään noin 1800 px leveä, jotta kuva pysyy terävänä tarkoilla näytöillä.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><strong>Tyylin poisto</strong> palauttaa kuvan omat mittasuhteet.</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:image {"sizeSlug":"large","scale":"cover","className":"is-style-band"} -->
<figure class="wp-block-image size-large is-style-band"><img src="<?php echo esc_url( $image ); ?>" alt="" style="object-fit:cover"/><figcaption class="wp-element-caption">Kuva, tyyli Wide band</figcaption></figure>
<!-- /wp:image -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:paragraph {"className":"style-guide-label"} -->
<p class="style-guide-label">Galleria (gallery)</p>
<!-- /wp:paragraph -->

<!-- wp:gallery {"linkTo":"none"} -->
<figure class="wp-block-gallery has-nested-images columns-default is-cropped"><!-- wp:image {"sizeSlug":"large"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( $image ); ?>" alt=""/><figcaption class="wp-element-caption">Galleria</figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"sizeSlug":"large"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( $image ); ?>" alt=""/><figcaption class="wp-element-caption">Toinen kuva</figcaption></figure>
<!-- /wp:image --></figure>
<!-- /wp:gallery -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:paragraph {"className":"style-guide-label"} -->
<p class="style-guide-label">Kansikuva (cover)</p>
<!-- /wp:paragraph -->

<!-- wp:cover {"url":"<?php echo esc_url( $image ); ?>","dimRatio":50,"layout":{"type":"constrained"}} -->
<div class="wp-block-cover"><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( $image ); ?>" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Cover</h3>
<!-- /wp:heading --></div></div>
<!-- /wp:cover -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:paragraph {"className":"style-guide-label"} -->
<p class="style-guide-label">Upotus (embed)</p>
<!-- /wp:paragraph -->

<!-- wp:embed {"url":"https://www.youtube.com/watch?v=aqz-KE-bpKQ","type":"video","providerNameSlug":"youtube","responsive":true,"className":"wp-embed-aspect-16-9 wp-has-aspect-ratio"} -->
<figure class="wp-block-embed is-type-video is-provider-youtube wp-block-embed-youtube wp-embed-aspect-16-9 wp-has-aspect-ratio"><div class="wp-block-embed__wrapper">
https://www.youtube.com/watch?v=aqz-KE-bpKQ
</div><figcaption class="wp-element-caption">Upotettu video</figcaption></figure>
<!-- /wp:embed -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:paragraph {"className":"style-guide-label"} -->
<p class="style-guide-label">Ryhmät (group)</p>
<!-- /wp:paragraph -->

<!-- wp:group {"className":"is-style-rounded-corners","backgroundColor":"surface","layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-rounded-corners has-surface-background-color has-background"><!-- wp:paragraph -->
<p>Group, tyyli Rounded corners, taustana Surface. Tyyli antaa vain pyöristetyt kulmat (sama säde kuin CTA-laatikossa); taustaväri valitaan ryhmän omista asetuksista.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-style-rounded-corners","backgroundColor":"brand","textColor":"light","layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-rounded-corners has-light-color has-brand-background-color has-text-color has-background"><!-- wp:paragraph -->
<p>Group, tyyli Rounded corners, taustana Brand ja vaalea teksti.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:paragraph -->
<p>Group, tyyli <em>CTA box</em>:</p>
<!-- /wp:paragraph -->

<!-- wp:group {"align":"wide","className":"is-style-cta","layout":{"type":"constrained","contentSize":"820px"}} -->
<div class="wp-block-group alignwide is-style-cta"><!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">A heading that invites the visitor to get in touch</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">A sentence or two on what happens next.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button">Contact us</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:paragraph {"className":"style-guide-label"} -->
<p class="style-guide-label">Ruudukko (jcore/grid)</p>
<!-- /wp:paragraph -->

<!-- wp:jcore/grid -->
<section class="wp-block-jcore-grid jgrid"><!-- wp:jcore/column -->
<div class="wp-block-jcore-column"><!-- wp:heading {"level":4} -->
<h4 class="wp-block-heading">Palsta 1 (Ruudukko)</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php echo esc_html( $text ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:jcore/column -->

<!-- wp:jcore/column -->
<div class="wp-block-jcore-column"><!-- wp:heading {"level":4} -->
<h4 class="wp-block-heading">Palsta 2 (Ruudukko)</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php echo esc_html( $text ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:jcore/column --></section>
<!-- /wp:jcore/grid -->

<!-- wp:paragraph -->
<p>Kuva ja teksti (Ruudukko, tyyli Centred vertically, luokka is-image-text):</p>
<!-- /wp:paragraph -->

<!-- wp:jcore/grid {"autoSize":false,"breakpoints":{"xs":1,"md":2},"align":"wide","className":"is-style-center-vertical is-image-text"} -->
<section class="wp-block-jcore-grid alignwide is-style-center-vertical is-image-text jgrid columns-xs-1 columns-md-2"><!-- wp:jcore/column -->
<div class="wp-block-jcore-column"><!-- wp:image {"sizeSlug":"large","scale":"cover","className":"is-style-rounded-corners"} -->
<figure class="wp-block-image size-large is-style-rounded-corners"><img src="<?php echo esc_url( $image ); ?>" alt="" style="object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:jcore/column -->

<!-- wp:jcore/column -->
<div class="wp-block-jcore-column"><!-- wp:heading -->
<h2 class="wp-block-heading">Kuva ja teksti</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>A short text about the subject with <a href="#">a text link</a>. Replace this with your own content.</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li>A first point worth knowing</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>A second point worth knowing</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>A third point worth knowing</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:jcore/column --></section>
<!-- /wp:jcore/grid -->

<!-- wp:paragraph -->
<p>Teksti ja kuva (luokka has-image-right: kuva oikealla, puhelimella ensin):</p>
<!-- /wp:paragraph -->

<!-- wp:jcore/grid {"autoSize":false,"breakpoints":{"xs":1,"md":2},"align":"wide","className":"is-style-center-vertical has-image-right is-image-text"} -->
<section class="wp-block-jcore-grid alignwide is-style-center-vertical has-image-right is-image-text jgrid columns-xs-1 columns-md-2"><!-- wp:jcore/column -->
<div class="wp-block-jcore-column"><!-- wp:image {"sizeSlug":"large","scale":"cover","className":"is-style-rounded-corners"} -->
<figure class="wp-block-image size-large is-style-rounded-corners"><img src="<?php echo esc_url( $image ); ?>" alt="" style="object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:jcore/column -->

<!-- wp:jcore/column -->
<div class="wp-block-jcore-column"><!-- wp:heading -->
<h2 class="wp-block-heading">Teksti ja kuva</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>A short text about the subject with <a href="#">a text link</a>. Replace this with your own content.</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li>A first point worth knowing</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>A second point worth knowing</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>A third point worth knowing</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:jcore/column --></section>
<!-- /wp:jcore/grid -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:paragraph {"className":"style-guide-label"} -->
<p class="style-guide-label">Välit (spacer, responsiiviset)</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>Käytä välin tekemiseen aina näitä tyylejä</strong> (Välike-lohko → Tyylit: Small, Medium, Large, X-Large), älä pelkkää välikkeen korkeutta. Tyylien väli muuttuu näytön koon mukaan; pelkkä korkeus pikseleinä on yhtä suuri mobiilissa ja työpöydällä.</p>
<!-- /wp:paragraph -->

<!-- wp:group {"className":"style-guide-spacers","layout":{"type":"constrained"}} -->
<div class="wp-block-group style-guide-spacers"><!-- wp:paragraph {"fontSize":"mini"} -->
<p class="has-mini-font-size">Spacer Small (is-style-sm): koko small</p>
<!-- /wp:paragraph -->

<!-- wp:spacer {"height":"1rem","className":"is-style-sm"} -->
<div style="height:1rem" aria-hidden="true" class="wp-block-spacer is-style-sm"></div>
<!-- /wp:spacer -->

<!-- wp:paragraph {"fontSize":"mini"} -->
<p class="has-mini-font-size">Spacer Medium (is-style-md): koko medium</p>
<!-- /wp:paragraph -->

<!-- wp:spacer {"height":"1rem","className":"is-style-md"} -->
<div style="height:1rem" aria-hidden="true" class="wp-block-spacer is-style-md"></div>
<!-- /wp:spacer -->

<!-- wp:paragraph {"fontSize":"mini"} -->
<p class="has-mini-font-size">Spacer Large (is-style-lg): koko large</p>
<!-- /wp:paragraph -->

<!-- wp:spacer {"height":"1rem","className":"is-style-lg"} -->
<div style="height:1rem" aria-hidden="true" class="wp-block-spacer is-style-lg"></div>
<!-- /wp:spacer -->

<!-- wp:paragraph {"fontSize":"mini"} -->
<p class="has-mini-font-size">Spacer X-Large (is-style-xl): koko xlarge</p>
<!-- /wp:paragraph -->

<!-- wp:spacer {"height":"1rem","className":"is-style-xl"} -->
<div style="height:1rem" aria-hidden="true" class="wp-block-spacer is-style-xl"></div>
<!-- /wp:spacer --></div>
<!-- /wp:group -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:paragraph {"className":"style-guide-label"} -->
<p class="style-guide-label">Taulukot (table)</p>
<!-- /wp:paragraph -->

<!-- wp:table -->
<figure class="wp-block-table"><table><thead><tr><th>Service</th><th>Price</th></tr></thead><tbody><?php echo $rows( 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in the function. ?></tbody></table><figcaption class="wp-element-caption">Taulukon kuvateksti</figcaption></figure>
<!-- /wp:table -->

<!-- wp:table {"className":"is-style-stripes"} -->
<figure class="wp-block-table is-style-stripes"><table><thead><tr><th>Service</th><th>Price</th></tr></thead><tbody><?php echo $rows( 3 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in the function. ?></tbody></table></figure>
<!-- /wp:table -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:paragraph {"className":"style-guide-label"} -->
<p class="style-guide-label">Haitari (lohko/accordions)</p>
<!-- /wp:paragraph -->

<!-- wp:lohko/accordions -->
<div data-wp-interactive="lohko/accordions" data-wp-context="{&quot;currentAccordion&quot;:&quot;&quot;}" data-wp-init="actions.parseUrl" class="wp-block-lohko-accordions"><!-- wp:lohko/accordion-item -->
<div id="accordion-tyyliopas-3" data-accordion-id="tyyliopas-3" data-wp-class--active="state.isActive" class="wp-block-lohko-accordion-item"><h3 class="accordion-item__header-wrapper"><button id="accordion-toggle-tyyliopas-3" class="accordion-item__header" data-wp-bind--aria-expanded="state.isActive" data-wp-on-async--click="actions.openAccordion" aria-controls="accordion-item-tyyliopas-3"><span class="accordion-item__title">Lohko-haitari, ensimmäinen</span><span class="accordion-item__toggle"><span class="accordion-item__toggle-icon"></span><span class="screen-reader-text">Toggle</span></span></button></h3><div id="accordion-item-tyyliopas-3" class="accordion-item__content" role="region" aria-labelledby="accordion-toggle-tyyliopas-3"><!-- wp:paragraph -->
<p><?php echo esc_html( $text ); ?></p>
<!-- /wp:paragraph --></div></div>
<!-- /wp:lohko/accordion-item -->

<!-- wp:lohko/accordion-item -->
<div id="accordion-tyyliopas-4" data-accordion-id="tyyliopas-4" data-wp-class--active="state.isActive" class="wp-block-lohko-accordion-item"><h3 class="accordion-item__header-wrapper"><button id="accordion-toggle-tyyliopas-4" class="accordion-item__header" data-wp-bind--aria-expanded="state.isActive" data-wp-on-async--click="actions.openAccordion" aria-controls="accordion-item-tyyliopas-4"><span class="accordion-item__title">Lohko-haitari, toinen</span><span class="accordion-item__toggle"><span class="accordion-item__toggle-icon"></span><span class="screen-reader-text">Toggle</span></span></button></h3><div id="accordion-item-tyyliopas-4" class="accordion-item__content" role="region" aria-labelledby="accordion-toggle-tyyliopas-4"><!-- wp:paragraph -->
<p><?php echo esc_html( $text ); ?></p>
<!-- /wp:paragraph --></div></div>
<!-- /wp:lohko/accordion-item --></div>
<!-- /wp:lohko/accordions -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:paragraph {"className":"style-guide-label"} -->
<p class="style-guide-label">Tiedosto (file)</p>
<!-- /wp:paragraph -->

<!-- wp:file {"href":"<?php echo esc_url( $image ); ?>"} -->
<div class="wp-block-file"><a href="<?php echo esc_url( $image ); ?>">Esimerkkitiedosto.pdf</a><a href="<?php echo esc_url( $image ); ?>" class="wp-block-file__button wp-element-button" download>Lataa</a></div>
<!-- /wp:file -->

