<?php
	/**
	*	Get Current page object
	**/
	$page = get_page($post->ID);
	
	/**
	*	Get current page id
	**/
	
	if(!isset($current_page_id) && isset($page->ID))
	{
	    $current_page_id = $page->ID;
	}
	
	$pp_page_bg = '';
	//Get page featured image
	if(has_post_thumbnail($current_page_id, 'full') && empty($term))
    {
        $image_id = get_post_thumbnail_id($current_page_id); 
        $image_thumb = wp_get_attachment_image_src($image_id, 'full', true);
        $pp_page_bg = $image_thumb[0];
    }
    
    if(isset($image_thumb[0]))
    {
	    $background_image = $image_thumb[0];
		$background_image_width = $image_thumb[1];
		$background_image_height = $image_thumb[2];
	}
	
	$tour_title = get_the_title();
	$tour_country= get_post_meta($page->ID, 'tour_country', true);
    
    global $global_pp_topbar;
    
    if(!empty($pp_page_bg)) 
    {
?>
<div id="page_caption" <?php if(!empty($pp_page_bg)) { ?>class="hasbg parallax fullscreen <?php if(empty($page_menu_transparent)) { ?>notransparent<?php } ?>" data-image="<?php echo $background_image; ?>" data-width="<?php echo $background_image_width; ?>" data-height="<?php echo $background_image_height; ?>"<?php } ?>>
	<div class="page_title_wrapper">
		<?php
		if(!empty($tour_country))
		{
		?>
		<div class="tour_country_subheader"><?php echo $tour_country; ?></div><br class="clear"/>
		<?php
		}
		?>
		<h1 <?php if(!empty($pp_page_bg) && !empty($global_pp_topbar)) { ?>class ="withtopbar"<?php } ?>><?php echo $tour_title; ?></h1>
	</div>
	<?php if(!empty($pp_page_bg)) { ?>
		<div class="parallax_overlay_header"></div>
	<?php } ?>
</div>
<?php
	}
?>

<!-- Begin content -->
<div id="page_content_wrapper" <?php if(!empty($pp_page_bg)) { ?>class="hasbg fullwidth fullscreen <?php if(!empty($pp_page_bg) && !empty($global_pp_topbar)) { ?>withtopbar<?php } ?>"<?php } ?>>