<?php
//Get all galleries
$args = array(
    'numberposts' => -1,
    'post_type' => array('galleries'),
);

$galleries_arr = get_posts($args);
$galleries_select = array();
$galleries_select[''] = '';

foreach($galleries_arr as $gallery)
{
    $galleries_select[$gallery->ID] = $gallery->post_title;
}

//Get all categories
$categories_arr = get_categories();
$categories_select = array();
$categories_select[''] = '';

foreach ($categories_arr as $cat) {
	$categories_select[$cat->cat_ID] = $cat->cat_name;
}

//Get all tour categories
$tour_cats_arr = get_terms('tourcats', 'hide_empty=0&hierarchical=0&parent=0&orderby=menu_order');
$tour_cats_select = array();
$tour_cats_select[''] = '';

foreach ($tour_cats_arr as $tour_cat) {
	$tour_cats_select[$tour_cat->slug] = $tour_cat->name;
}

//Get all service categories
$service_cats_arr = get_terms('servicecats', 'hide_empty=0&hierarchical=0&parent=0&orderby=menu_order');
$service_cats_select = array();
$service_cats_select[''] = '';

foreach ($service_cats_arr as $service_cat) {
	$service_cats_select[$service_cat->slug] = $service_cat->name;
}

//Get all team categories
$team_cats_arr = get_terms('teamcats', 'hide_empty=0&hierarchical=0&parent=0&orderby=menu_order');
$team_cats_select = array();
$team_cats_select[''] = '';

foreach ($team_cats_arr as $team_cat) {
	$team_cats_select[$team_cat->slug] = $team_cat->name;
}

//Get all testimonials categories
$testimonial_cats_arr = get_terms('testimonialcats', 'hide_empty=0&hierarchical=0&parent=0&orderby=menu_order');
$testimonial_cats_select = array();
$testimonial_cats_select[''] = '';

foreach ($testimonial_cats_arr as $testimonial_cat) {
	$testimonial_cats_select[$testimonial_cat->slug] = $testimonial_cat->name;
}

//Get order options
$order_select = array(
	'default' 	=> 'By Default',
	'newest'	=> 'By Newest',
	'oldest'	=> 'By Oldest',
	'title'		=> 'By Title',
	'random'	=> 'By Random',
);

//Get column options
$team_column_select = array(
	'2' => '2 Columns',
	'3'	=> '3 Columns',
	'4'	=> '4 Columns',
);

$gallery_column_select = array(
	'3'	=> '3 Columns',
	'4'	=> '4 Columns',
);

$text_block_layout_select = array(
	'fixedwidth'=> 'Fixed Width',
	'fullwidth'	=> 'Fullwidth',
);

$portfolio_column_select = array(
	'3'	=> '3 Columns',
	'4'	=> '4 Columns',
);


$tour_layout_select = array(
	'fullwidth'	=> 'Fullwidth',
	'fixedwidth'=> 'Fixed Width',
);

$gallery_layout_select = array(
	'fullwidth'	=> 'Fullwidth',
	'fixedwidth'=> 'Fixed Width',
);

//Get parallax type options
$parallax_select = array(
	'' 	=> 'None',
	'scroll_pos'   => 'Scroll Position',
);

//Get tour pages
$tour_pages_select = array(
	'' => '---- Please select tour page template ----'
);
$args = array(
'meta_query' => array(
       array(
           'key' => '_wp_page_template',
           'value' => array('tour-classic-contain.php', 'tour-classic-fullwidth.php', 'tour-grid-contain.php', 'tour-grid-fullwidth.php', 'tour-list-image.php', 'tour-list.php'),
           'compare' => 'IN',
       )
    ),

    'sort_column' => 'post_title',
    'sort_order' => 'ASC',
    'posts_per_page' => -1,
    'post_type' => 'page'
);
$tour_pages = get_posts($args);
foreach($tour_pages as $tour_page)
{
	$tour_pages_select[$tour_page->ID] = $tour_page->post_title;
}

$background_size_select = array(
	'' 	=> 'Cover',
	'110' 	=> '110%',
	'120' 	=> '120%',
	'130' 	=> '130%',
	'140' 	=> '140%',
	'150' 	=> '150%',
	'160' 	=> '160%',
	'170' 	=> '170%',
	'180' 	=> '180%',
	'190' 	=> '190%',
	'200' => '200%',
);

$background_overlay_select = array(
	'' 	=> '0',
	'10' 	=> '10%',
	'20' 	=> '20%',
	'30' 	=> '30%',
	'40' 	=> '40%',
	'50' 	=> '50%',
	'60' 	=> '60%',
	'70' 	=> '70%',
	'80' 	=> '80%',
	'90' 	=> '90%',
);

$ppb_shortcodes = array(
    'ppb_text' => array(
    	'title' =>  'Text Block',
    	'attr' => array(
    		'layout' => array(
    			'title' => 'Layout',
    			'type' => 'select',
    			'options' => $text_block_layout_select,
    			'desc' => 'Select layout you want to display textblock wrapper',
    		),
    		'background' => array(
    			'title' => 'Background Image',
    			'type' => 'file',
    			'desc' => 'Upload background image you want to display for this content',
    		),
    		'background_parallax' => array(
    			'title' => 'Background Parallax Option',
    			'type' => 'select',
    			'options' => $parallax_select,
    			'desc' => 'You can choose parallax type for this content background. Select none to disable parallax',
    		),
    		'custom_css' => array(
    			'title' => 'Custom CSS',
    			'type' => 'text',
    			'desc' => 'You can add custom CSS style for this block (advanced user only)',
    		),
    	),
    	'desc' => array(),
    	'content' => TRUE
    ),
    'ppb_divider' => array(
    	'title' =>  'Divider',
    	'attr' => array(),
    	'desc' => array(),
    	'content' => FALSE
    ),
    'ppb_tour' => array(
    	'title' =>  'Tour Classic',
    	'attr' => array(
    		'tourcat' => array(
    			'title' => 'Filter by tour category',
    			'type' => 'select',
    			'options' => $tour_cats_select,
    			'desc' => 'You can choose to display only some tour items from selected category',
    		),
    		'layout' => array(
    			'title' => 'Layout',
    			'type' => 'select',
    			'options' => $tour_layout_select,
    			'desc' => 'Select layout you want to display tour content',
    		),
    		'order' => array(
    			'title' => 'Order By',
    			'type' => 'select',
    			'options' => $order_select,
    			'desc' => 'Select how you want to order portfolio items',
    		),
    		'items' => array(
    			'type' => 'jslider',
    			'from' => 1,
    			'to' => 50,
    			'desc' => 'Enter number of posts to display (number value only)',
    		),
    		'custom_css' => array(
    			'title' => 'Custom CSS',
    			'type' => 'text',
    			'desc' => 'You can add custom CSS style for this block (advanced user only)',
    		),
    	),
    	'desc' => array(),
    	'content' => TRUE
    ),
    'ppb_tour_search' => array(
    	'title' =>  'Tour Search Form',
    	'attr' => array(
    		'action' => array(
    			'title' => 'Result Page Template',
    			'type' => 'select',
    			'options' => $tour_pages_select,
    			'desc' => 'Select tour pages template you want to display search results',
    		),
    		'custom_css' => array(
    			'title' => 'Custom CSS',
    			'type' => 'text',
    			'desc' => 'You can add custom CSS style for this block (advanced user only)',
    		),
    	),
    	'desc' => array(),
    	'content' => FALSE
    ),
    'ppb_tour_grid' => array(
    	'title' =>  'Tour Grid',
    	'attr' => array(
    		'tourcat' => array(
    			'title' => 'Filter by tour category',
    			'type' => 'select',
    			'options' => $tour_cats_select,
    			'desc' => 'You can choose to display only some tour items from selected category',
    		),
    		'layout' => array(
    			'title' => 'Layout',
    			'type' => 'select',
    			'options' => $tour_layout_select,
    			'desc' => 'Select layout you want to display tour content',
    		),
    		'order' => array(
    			'title' => 'Order By',
    			'type' => 'select',
    			'options' => $order_select,
    			'desc' => 'Select how you want to order portfolio items',
    		),
    		'items' => array(
    			'type' => 'jslider',
    			'from' => 1,
    			'to' => 50,
    			'desc' => 'Enter number of posts to display (number value only)',
    		),
    		'custom_css' => array(
    			'title' => 'Custom CSS',
    			'type' => 'text',
    			'desc' => 'You can add custom CSS style for this block (advanced user only)',
    		),
    	),
    	'desc' => array(),
    	'content' => TRUE
    ),
    'ppb_gallery' => array(
    	'title' =>  'Gallery',
    	'attr' => array(
    		'gallery' => array(
    			'title' => 'Gallery',
    			'type' => 'select',
    			'options' => $galleries_select,
    			'desc' => 'Select the gallery you want to display',
    		),
    		'layout' => array(
    			'title' => 'Layout',
    			'type' => 'select',
    			'options' => $gallery_layout_select,
    			'desc' => 'Select layout you want to display gallery wrapper',
    		),
    		'custom_css' => array(
    			'title' => 'Custom CSS',
    			'type' => 'text',
    			'desc' => 'You can add custom CSS style for this block (advanced user only)',
    		),
    	),
    	'desc' => array(),
    	'content' => TRUE
    ),
    'ppb_gallery_slider' => array(
    	'title' =>  'Gallery Slider',
    	'attr' => array(
    		'gallery' => array(
    			'title' => 'Gallery',
    			'type' => 'select',
    			'options' => $galleries_select,
    			'desc' => 'Select the gallery you want to display',
    		),
    		'layout' => array(
    			'title' => 'Layout',
    			'type' => 'select',
    			'options' => $gallery_layout_select,
    			'desc' => 'Select layout you want to display gallery wrapper',
    		),
    		'custom_css' => array(
    			'title' => 'Custom CSS',
    			'type' => 'text',
    			'desc' => 'You can add custom CSS style for this block (advanced user only)',
    		),
    	),
    	'desc' => array(),
    	'content' => TRUE
    ),
    'ppb_blog' => array(
    	'title' =>  'Blog',
    	'attr' => array(
    		'category' => array(
    			'title' => 'Filter by category',
    			'type' => 'select',
    			'options' => $categories_select,
    			'desc' => 'You can choose to display only some posts from selected category',
    		),
    		'items' => array(
    			'type' => 'jslider',
    			'from' => 1,
    			'to' => 50,
    			'desc' => 'Enter number of posts to display (number value only)',
    		),
    		'background' => array(
    			'title' => 'Background Image',
    			'type' => 'file',
    			'desc' => 'Upload background image you want to display for this content',
    		),
    		'background_parallax' => array(
    			'title' => 'Background Parallax Option',
    			'type' => 'select',
    			'options' => $parallax_select,
    			'desc' => 'You can choose parallax type for this content background. Select none to disable parallax',
    		),
    		'custom_css' => array(
    			'title' => 'Custom CSS',
    			'type' => 'text',
    			'desc' => 'You can add custom CSS style for this block (advanced user only)',
    		),
    	),
    	'desc' => array(),
    	'content' => TRUE
    ),
    'ppb_service' => array(
    	'title' =>  'Service',
    	'attr' => array(
    		'category' => array(
    			'title' => 'Filter by service category',
    			'type' => 'select',
    			'options' => $service_cats_select,
    			'desc' => 'You can choose to display only some service items from selected category',
    		),
    		'order' => array(
    			'title' => 'Order By',
    			'type' => 'select',
    			'options' => $order_select,
    			'desc' => 'Select how you want to order service items',
    		),
    		'items' => array(
    			'type' => 'jslider',
    			'from' => 1,
    			'to' => 50,
    			'desc' => 'Enter number of posts to display (number value only)',
    		),
    		'background' => array(
    			'title' => 'Background Image',
    			'type' => 'file',
    			'desc' => 'Upload background image you want to display for this content',
    		),
    		'background_parallax' => array(
    			'title' => 'Background Parallax Option',
    			'type' => 'select',
    			'options' => $parallax_select,
    			'desc' => 'You can choose parallax type for this content background. Select none to disable parallax',
    		),
    		'custom_css' => array(
    			'title' => 'Custom CSS',
    			'type' => 'text',
    			'desc' => 'You can add custom CSS style for this block (advanced user only)',
    		),
    	),
    	'desc' => array(),
    	'content' => TRUE
    ),
    'ppb_transparent_video_bg' => array(
    	'title' =>  'Transparent Video Background',
    	'attr' => array(
    		'description' => array(
    			'type' => 'textarea',
    			'desc' => 'Enter short description. It displays under the title',
    		),
    		'mp4_video_url' => array(
    			'title' => 'MP4 Video URL',
    			'type' => 'file',
    			'desc' => 'Upload .mp4 video file you want to display for this content',
    		),
    		'webm_video_url' => array(
    			'title' => 'WebM Video URL',
    			'type' => 'file',
    			'desc' => 'Upload .webm video file you want to display for this content',
    		),
    		'preview_img' => array(
    			'title' => 'Preview Image URL',
    			'type' => 'file',
    			'desc' => 'Upload preview image for this video',
    		),
    		'height' => array(
    			'type' => 'text',
    			'desc' => 'Enter number of height for background image (in pixel)',
    		),
    	),
    	'desc' => array(),
    	'content' => FALSE
    ),
    'ppb_fullwidth_button' => array(
    	'title' =>  'Full Width Button',
    	'attr' => array(
    		'link_url' => array(
    			'type' => 'text',
    			'desc' => 'Enter redirected link URL when button is clicked',
    		),
    	),
    	'desc' => array(),
    	'content' => FALSE
    ),
    'ppb_team' => array(
    	'title' =>  'Team',
    	'attr' => array(
    		'columns' => array(
    			'title' => 'Columns',
    			'type' => 'select',
    			'options' => $team_column_select,
    			'desc' => 'Select how many columns you want to display service items in a row',
    		),
    		'cat' => array(
    			'title' => 'Filter by team category',
    			'type' => 'select',
    			'options' => $team_cats_select,
    			'desc' => 'You can choose to display only some team members from selected team category',
    		),
    		'order' => array(
    			'title' => 'Order By',
    			'type' => 'select',
    			'options' => $order_select,
    			'desc' => 'Select how you want to order team members',
    		),
    		'items' => array(
    			'type' => 'jslider',
    			'from' => 1,
    			'to' => 50,
    			'desc' => 'Enter number of posts to display (number value only)',
    		),
    		'background' => array(
    			'title' => 'Background Image',
    			'type' => 'file',
    			'desc' => 'Upload background image you want to display for this content',
    		),
    		'background_parallax' => array(
    			'title' => 'Background Parallax Option',
    			'type' => 'select',
    			'options' => $parallax_select,
    			'desc' => 'You can choose parallax type for this content background. Select none to disable parallax',
    		),
    		'custom_css' => array(
    			'title' => 'Custom CSS',
    			'type' => 'text',
    			'desc' => 'You can add custom CSS style for this block (advanced user only)',
    		),
    	),
    	'desc' => array(),
    	'content' => TRUE
    ),
    'ppb_client' => array(
    	'title' =>  'Client',
    	'attr' => array(
    		'items' => array(
    			'type' => 'jslider',
    			'from' => 1,
    			'to' => 50,
    			'desc' => 'Enter number of posts to display (number value only)',
    		),
    		'custom_css' => array(
    			'title' => 'Custom CSS',
    			'type' => 'text',
    			'desc' => 'You can add custom CSS style for this block (advanced user only)',
    		),
    	),
    	'desc' => array(),
    	'content' => TRUE
    ),
    'ppb_promo_box' => array(
    	'title' =>  'Promo Box',
    	'attr' => array(
    		'background_color' => array(
    			'type' => 'text',
    			'desc' => 'Enter color code for background ex. #222222',
    		),
    		'button_text' => array(
    			'type' => 'text',
    			'desc' => 'Enter promo box button text',
    		),
    		'button_url' => array(
    			'type' => 'text',
    			'desc' => 'Enter redirected link URL when button is clicked',
    		),
    	),
    	'desc' => array(),
    	'content' => TRUE
    ),
    'ppb_testimonial' => array(
    	'title' =>  'Testimonials',
    	'attr' => array(
    		'cat' => array(
    			'title' => 'Filter by testimonials category',
    			'type' => 'select',
    			'options' => $testimonial_cats_select,
    			'desc' => 'You can choose to display only some testimonials from selected testimonial category',
    		),
    		'items' => array(
    			'type' => 'jslider',
    			'from' => 1,
    			'to' => 50,
    			'desc' => 'Enter number of posts to display (number value only)',
    		),
    		'background' => array(
    			'title' => 'Background Image',
    			'type' => 'file',
    			'desc' => 'Upload background image you want to display for this content',
    		),
    		'background_parallax' => array(
    			'title' => 'Background Parallax Option',
    			'type' => 'select',
    			'options' => $parallax_select,
    			'desc' => 'You can choose parallax type for this content background. Select none to disable parallax',
    		),
    		'custom_css' => array(
    			'title' => 'Custom CSS',
    			'type' => 'text',
    			'desc' => 'You can add custom CSS style for this block (advanced user only)',
    		),
    	),
    	'desc' => array(),
    	'content' => FALSE
    ),
    'ppb_contact' => array(
    	'title' =>  'Contact Form',
    	'attr' => array(
    		'address' => array(
    			'title' => 'Address Info',
    			'type' => 'textarea',
    			'desc' => 'Enter company address, email etc. HTML and shortcode are support',
    		),
    		'background' => array(
    			'title' => 'Background Image',
    			'type' => 'file',
    			'desc' => 'Upload background image you want to display for this content',
    		),
    		'background_parallax' => array(
    			'title' => 'Background Parallax Option',
    			'type' => 'select',
    			'options' => $parallax_select,
    			'desc' => 'You can choose parallax type for this content background. Select none to disable parallax',
    		),
    		'custom_css' => array(
    			'title' => 'Custom CSS',
    			'type' => 'text',
    			'desc' => 'You can add custom CSS style for this block (advanced user only)',
    		),
    	),
    	'desc' => array(),
    	'content' => FALSE
    ),
);

//Check if Layer slider is installed	
$revslider = ABSPATH . '/wp-content/plugins/revslider/revslider.php';

// Check if the file is available to prevent warnings
$pp_revslider_activated = file_exists($revslider);

if($pp_revslider_activated)
{
	//Get WPDB Object
	global $wpdb;
	
	// Get Rev Sliders
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	$is_revslider_active = is_plugin_active('revslider/revslider.php');
	$wp_revsliders = array();
	
	if($is_revslider_active)
	{
		$wp_revsliders = array(
			-1		=> "Choose a slide",
		);
		$revslider_objs = new RevSlider();
		$revslider_obj_arr = $revslider_objs->getArrSliders();
		
		foreach($revslider_obj_arr as $revslider_obj)
		{
			$wp_revsliders[$revslider_obj->getAlias()] = $revslider_obj->getTitle();
		}
	}
	
	$ppb_shortcodes['ppb_revslider'] = array(
    	'title' =>  'Revolution Slider',
    	'attr' => array(
    		'slider_id' => array(
    			'title' => 'Select Slider to display',
    			'type' => 'select',
    			'options' => $wp_revsliders,
    			'desc' => 'Choose which revolution slider to display (if it\'s empty. You need to create a revolution slider first.)',
    		),
    	),
    	'desc' => array(),
    	'content' => FALSE
    );
}

ksort($ppb_shortcodes);
?>