<?php
add_action( 'init', 'EWD_UWPM_Create_Posttypes' );
function EWD_UWPM_Create_Posttypes() {
		$labels = array(
				'name' => __('Emails', 'ultimate-wp-mail'),
				'singular_name' => __('Email', 'ultimate-wp-mail'),
				'menu_name' => __('Emails', 'ultimate-wp-mail'),
				'add_new' => __('Add New', 'ultimate-wp-mail'),
				'add_new_item' => __('Add New Email', 'ultimate-wp-mail'),
				'edit_item' => __('Edit Email', 'ultimate-wp-mail'),
				'new_item' => __('New Email', 'ultimate-wp-mail'),
				'view_item' => __('View Email', 'ultimate-wp-mail'),
				'search_items' => __('Search Emails', 'ultimate-wp-mail'),
				'not_found' =>  __('Nothing found', 'ultimate-wp-mail'),
				'not_found_in_trash' => __('Nothing found in Trash', 'ultimate-wp-mail'),
				'parent_item_colon' => ''
		);

		$args = array(
				'labels' => $labels,
				'public' => true,
				'publicly_queryable' => true,
				'show_ui' => true,
				'show_in_menu' => false,
				'query_var' => true,
				'has_archive' => true,
				'menu_icon' => null,
				'rewrite' => array('slug' => 'email'),
				'capability_type' => 'post',
				'menu_position' => null,
				'menu_icon' => 'dashicons-format-status',
				'supports' => array('title','editor')
	  );

	register_post_type( 'uwpm_mail_template' , $args );
}

function EWD_UWPM_Create_Category_Taxonomy() {

	register_taxonomy('uwpm-category', 'uwpm_mail_template', array(
		// Hierarchical taxonomy (like categories)
		'hierarchical' => false,
		// This array of options controls the labels displayed in the WordPress Admin UI
		'labels' => array(
			'name' => __('Email Categories', 'ultimate-wp-mail'),
			'singular_name' => __('Email Category', 'ultimate-wp-mail'),
			'search_items' =>  __('Search Email Categories', 'ultimate-wp-mail'),
			'all_items' => __('All Email Categories', 'ultimate-wp-mail'),
			'parent_item' => __('Parent Email Category', 'ultimate-wp-mail'),
			'parent_item_colon' => __('Parent Email Category:', 'ultimate-wp-mail'),
			'edit_item' => __('Edit Email Category', 'ultimate-wp-mail'),
			'update_item' => __('Update Email Category', 'ultimate-wp-mail'),
			'add_new_item' => __('Add New Email Category', 'ultimate-wp-mail'),
			'new_item_name' => __('New Email Category Name', 'ultimate-wp-mail'),
			'menu_name' => __('Email Categories', 'ultimate-wp-mail'),
		),
		'query_var' => false
	));
}
add_action( 'init', 'EWD_UWPM_Create_Category_Taxonomy');

?>