<?php
add_filter( 'block_categories', 'ewd_uwpm_add_block_category' );
function ewd_uwpm_add_block_category( $categories ) {
	$categories[] = array(
		'slug'  => 'ewd-uwpm-blocks',
		'title' => __( 'Ultimate WP Mail', 'ultimate-wp-mail' ),
	);
	return $categories;
}

