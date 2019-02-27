<?php
	//If Contact Us with Map template
	global $pp_contact_display_map;
    
    if(!empty($pp_contact_display_map))
    {
    	wp_enqueue_script("gmap", get_template_directory_uri()."/js/gmap.js", false, THEMEVERSION, true);
    	wp_enqueue_script("script-contact-map", get_template_directory_uri()."/templates/script-contact-map.php", false, THEMEVERSION, true);
?>
    <div class="map_shadow fullwidth">
    	<div id="map_contact"></div>
    </div>
<?php
    }
?>

<?php
$current_page_id = '';
$page_revslider = '';

if(is_object($post) && !isset($_GET['action']))
{
	/**
	*	Get Current page object
	**/
	$page = get_page($post->ID);
	
	/**
	*	Get current page id
	**/
	
	if(isset($page->ID))
	{
	    $current_page_id = $page->ID;
	}

    //Get Page RevSlider
    $page_revslider = get_post_meta($current_page_id, 'page_revslider', true);
    $page_menu_transparent = get_post_meta($current_page_id, 'page_menu_transparent', true);
    $page_header_below = get_post_meta($current_page_id, 'page_header_below', true);
    
    if(!empty($page_revslider) && $page_revslider != -1 && empty($page_header_below))
    {
    	echo '<div class="page_slider ';
    	if(!empty($page_menu_transparent))
    	{
	    	echo 'menu_transparent';
    	}
    	echo '">'.do_shortcode('[rev_slider '.$page_revslider.']').'</div>';
    }
}
?>

<?php
//Get page header display setting
$page_title = get_the_title();
$page_hide_header = get_post_meta($current_page_id, 'page_hide_header', true);

if($page_revslider != -1 && !empty($page_menu_transparent))
{
	$page_hide_header = 1;
}

if(!empty($term))
{
	$page_hide_header = '';
	$page_revslider = '-1';
	$custom_term = get_term_by('slug', $term, 'tourcats');   
	$page_title = $custom_term->name;
}

if(isset($_GET['action']) && $_GET['action'] == 'pp_tour_search')
{
	$page_revslider = '-1';
	$page_title = sprintf( __( 'Results for &quot;%s&quot;', '' ), '' . $_GET['keyword'] . '' );
}

if(is_tag())
{
	$page_hide_header = '';
	$page_revslider = '-1';
	$page_title = single_cat_title( '', false );
	$term = 'tag';
} 
elseif(is_category())
{
    $page_hide_header = '';
	$page_revslider = '-1';
	$page_title = single_cat_title( '', false );
	$term = 'category';
}
elseif(is_archive() && empty($term))
{
	$page_hide_header = '';
	$page_revslider = '-1';

	if ( is_day() ) : 
		$page_title = get_the_date(); 
    elseif ( is_month() ) : 
    	$page_title = get_the_date('F Y'); 
    elseif ( is_year() ) : 
    	$page_title = get_the_date('Y'); 
    else :
    	$page_title = __( 'Blog Archives', THEMEDOMAIN); 
    endif;
    
    $term = 'archive';
} 

if(empty($page_hide_header) && ($page_revslider == -1 OR empty($page_revslider) OR !empty($page_header_below)))
{
	$pp_page_bg = '';
	//Get page featured image
	if(has_post_thumbnail($current_page_id, 'full') && empty($term) && empty($pp_contact_display_map))
    {
        $image_id = get_post_thumbnail_id($current_page_id); 
        $image_thumb = wp_get_attachment_image_src($image_id, 'full', true);
        $pp_page_bg = $image_thumb[0];
    }
    
    if(isset($_GET['action']) && $_GET['action'] == 'pp_tour_search')
	{
	    $pp_tour_search_bg = get_option('pp_tour_search_bg');
	    
	    if(!empty($pp_tour_search_bg))
	    {
	    	$image_id = pp_get_image_id($pp_tour_search_bg);
	    	$image_thumb = wp_get_attachment_image_src($image_id, 'full', true);
			$pp_page_bg = $image_thumb[0];
	    }
	}
    
    if(isset($image_thumb[0]))
    {
	    $background_image = $image_thumb[0];
		$background_image_width = $image_thumb[1];
		$background_image_height = $image_thumb[2];
	}
    
    global $global_pp_topbar;
?>
<div id="page_caption" <?php if(!empty($pp_page_bg)) { ?>class="hasbg parallax <?php if(empty($page_menu_transparent)) { ?>notransparent<?php } ?> <?php if(!empty($pp_page_bg) && !empty($global_pp_topbar)) { ?> withtopbar<?php } ?>" data-image="<?php echo $background_image; ?>" data-width="<?php echo $background_image_width; ?>" data-height="<?php echo $background_image_height; ?>"<?php } ?>>
	<div class="page_title_wrapper">
		<h1 <?php if(!empty($pp_page_bg) && !empty($global_pp_topbar)) { ?>class="withtopbar"<?php } ?>><?php echo $page_title; ?></h1>
		<?php 
			$pp_breadcrumbs_display = get_option('pp_breadcrumbs_display');
			
			if(!empty($pp_breadcrumbs_display))
			{
				echo dimox_breadcrumbs(); 
			}
		?>
	</div>
	<?php if(!empty($pp_page_bg)) { ?>
		<div class="parallax_overlay_header"></div>
	<?php } ?>
</div>
<?php
}
elseif($page_revslider == -1)
{
?>
<br class="clear"/>
<?php
}
?>

<?php 
global $is_fullwidth_page;
$page_content_wrapper_class = '';
if($is_fullwidth_page)
{
	$page_content_wrapper_class = 'fullwidth ';
}

if(empty($page_menu_transparent)) 
{
	$page_content_wrapper_class.= 'notransparent';
}
?>
<!-- Begin content -->
<div id="page_content_wrapper" <?php if(!empty($pp_page_bg)) { ?>class="hasbg <?php echo $page_content_wrapper_class ?><?php if(!empty($pp_page_bg) && !empty($global_pp_topbar)) { ?> withtopbar<?php } ?>"<?php } else { ?>class="<?php echo $page_content_wrapper_class ?>"<?php } ?>>