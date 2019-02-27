<?php

function tg_register_sidebars() {
    /**
	*	Setup Page side bar
	**/
	if ( function_exists('register_sidebar') )
	    register_sidebar(array('id' => 'sidebar-1', 'name' => 'Page Sidebar', 'description' => 'The default sidebar for every pages'));
	    
	/**
	*	Setup Blog side bar
	**/
	if ( function_exists('register_sidebar') )
	    register_sidebar(array('id' => 'sidebar-2', 'name' => 'Blog Sidebar', 'description' => 'The default sidebar for blog page templates'));
	    
	/**
	*	Setup Contact side bar
	**/
	if ( function_exists('register_sidebar') )
	    register_sidebar(array('id' => 'sidebar-3', 'name' => 'Contact Sidebar', 'description' => 'The default sidebar for contact page template'));
	    
	/**
	*	Setup Single Post side bar
	**/
	if ( function_exists('register_sidebar') )
	    register_sidebar(array('id' => 'sidebar-4', 'name' => 'Single Post Sidebar', 'description' => 'The default sidebar for single post page'));
	    
	if ( function_exists('register_sidebar') )
	    register_sidebar(array('id' => 'sidebar-5', 'name' => 'Single Image Page Sidebar', 'description' => 'The default sidebar for single attachment (image) page'));
	    
	if ( function_exists('register_sidebar') )
	    register_sidebar(array('id' => 'sidebar-6', 'name' => 'Archives Sidebar', 'description' => 'The default sidebar for archive page'));
	    
	if ( function_exists('register_sidebar') )
	    register_sidebar(array('id' => 'sidebar-7', 'name' => 'Category Sidebar', 'description' => 'The default sidebar for post category page'));
	    
	if ( function_exists('register_sidebar') )
	    register_sidebar(array('id' => 'sidebar-8', 'name' => 'Search Sidebar', 'description' => 'The default sidebar for search result page'));
	    
	if ( function_exists('register_sidebar') )
	    register_sidebar(array('id' => 'sidebar-9', 'name' => 'Tag Sidebar', 'description' => 'The default sidebar for tag post page'));
	    
	if ( function_exists('register_sidebar') )
	    register_sidebar(array('id' => 'sidebar-10', 'name' => 'Footer Sidebar', 'description' => 'The default sidebar for footer area of every pages'));
	
	if ( function_exists('register_sidebar') )
	    register_sidebar(array('id' => 'sidebar-11', 'name' => 'Shop Sidebar', 'description' => 'The default sidebar for shop pages'));
	
	
	//Register dynamic sidebar
	$dynamic_sidebar = get_option('pp_sidebar');
	$dynamic_sidenar_id = 11;
	
	if(!empty($dynamic_sidebar))
	{
		foreach($dynamic_sidebar as $sidebar)
		{
			$dynamic_sidenar_id++;
		
			if ( function_exists('register_sidebar') )
		    register_sidebar(array('id' => 'sidebar-'.$dynamic_sidenar_id, 'name' => $sidebar));
		}
	}
}
add_action( 'widgets_init', 'tg_register_sidebars' );

?>