<?php
/**
 * Title: FAQs
 * Slug: jcore/faq-made-with-wp-details-block
 * Description: Outputs a list of configurable FAQ items.
 * Categories: text
 * Keywords: faq, accordion, toggle, questions, answers
 * Viewport Width: 640
 */
?>
<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
	<!-- wp:details -->
	<details class="wp-block-details">
		<summary><?php esc_html_e( 'Question 1?', 'jcore' ); ?></summary>
		<!-- wp:paragraph {"placeholder":"<?php esc_attr_e( 'Add an answer to the question.', 'jcore' ); ?>"} -->
		<p></p>
		<!-- /wp:paragraph -->
	</details>
	<!-- /wp:details -->

	<!-- wp:details -->
	<details class="wp-block-details">
		<summary><?php esc_html_e( 'Question 2?', 'jcore' ); ?></summary>
		<!-- wp:paragraph {"placeholder":"<?php esc_attr_e( 'Add an answer to the question.', 'jcore' ); ?>"} -->
		<p></p>
		<!-- /wp:paragraph -->
	</details>
	<!-- /wp:details -->

	<!-- wp:details -->
	<details class="wp-block-details">
		<summary><?php esc_html_e( 'Question 3?', 'jcore' ); ?></summary>
		<!-- wp:paragraph {"placeholder":"<?php esc_attr_e( 'Add an answer to the question.', 'jcore' ); ?>"} -->
		<p></p>
		<!-- /wp:paragraph -->
	</details>
	<!-- /wp:details -->

	<!-- wp:details -->
	<details class="wp-block-details">
		<summary><?php esc_html_e( 'Question 4?', 'jcore' ); ?></summary>
		<!-- wp:paragraph {"placeholder":"<?php esc_attr_e( 'Add an answer to the question.', 'jcore' ); ?>"} -->
		<p></p>
		<!-- /wp:paragraph -->
	</details>
	<!-- /wp:details -->
</div>
<!-- /wp:group -->