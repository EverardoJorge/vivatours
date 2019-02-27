<?php
add_filter( 'block_categories', 'ewd_feup_add_block_category' );
function ewd_feup_add_block_category( $categories ) {
	$categories[] = array(
		'slug'  => 'ewd-feup-blocks',
		'title' => __( 'Front End Users', 'front-end-only-users' ),
	);
	return $categories;
}

