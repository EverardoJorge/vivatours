<?php
/**
 * The main template file for display gallery grid fullwidth
 *
 * @package WordPress
 */

/**
*	Get Current page object
**/
$page = get_page($post->ID);
$current_page_id = '';

if(isset($page->ID))
{
    $current_page_id = $page->ID;
}

//Check if gallery template
global $page_gallery_id;
if(!empty($page_gallery_id))
{
	$current_page_id = $page_gallery_id;
}

//Get gallery images
$all_photo_arr = get_post_meta($current_page_id, 'wpsimplegallery_gallery', true);

$page_menu_transparent = get_post_meta($current_page_id, 'page_menu_transparent', true);

//Get global gallery sorting
$all_photo_arr = pp_resort_gallery_img($all_photo_arr);

get_header(); 
?>

<?php
	$pp_page_bg = '';
	//Get page featured image
	if(has_post_thumbnail($current_page_id, 'full'))
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
	
	global $global_pp_topbar;
?>
<div id="page_caption" <?php if(!empty($pp_page_bg)) { ?>class="hasbg parallax <?php if(empty($page_menu_transparent)) { ?>notransparent<?php } ?> <?php if(!empty($pp_page_bg) && !empty($global_pp_topbar)) { ?> withtopbar<?php } ?>" data-image="<?php echo $background_image; ?>" data-width="<?php echo $background_image_width; ?>" data-height="<?php echo $background_image_height; ?>"<?php } ?>>
	<div class="page_title_wrapper">
		<h1><?php the_title(); ?></h1>
	</div>
	<?php if(!empty($pp_page_bg)) { ?>
		<div class="parallax_overlay_header"></div>
	<?php } ?>
</div>

<?php
	global $global_pp_topbar;
?>
<div id="page_content_wrapper" class="<?php if(!empty($pp_page_bg)) { ?>hasbg<?php } ?> <?php if(!empty($pp_page_bg) && !empty($global_pp_topbar)) { ?>withtopbar<?php } ?> fullwidth">
    
    <div class="inner">

    	<div class="inner_wrapper">
    	
    	<div id="page_main_content" class="sidebar_content full_width">
    	
    	<?php 
    		//Get gallery content
    		$gallery_post_content = get_post_field('post_content', $current_page_id);
    		
    		if(!empty($gallery_post_content))
    		{
	    		echo nl2br($gallery_post_content);
    		}
    	?>
    	
    	<div id="portfolio_filter_wrapper" class="three_cols gallery fullwidth section content clearfix">
    	<?php
    		$key = 0;
    		foreach($all_photo_arr as $photo_id)
    		{
    			$small_image_url = '';
    			$hyperlink_url = get_permalink($photo_id);
    			
    			if(!empty($photo_id))
    			{
    				$image_url = wp_get_attachment_image_src($photo_id, 'original', true);
    			    $small_image_url = wp_get_attachment_image_src($photo_id, 'gallery_grid', true);
    			}
    			
    			$last_class = '';
    			if(($key+1)%4==0)
    			{
    				$last_class = 'last';
    			}
    			
    			$current_image_arr = wp_get_attachment_image_src($photo_id, 'gallery_grid');
    			
    			//Get image meta data
    			$image_title = get_the_title($photo_id);
    			$image_caption = get_post_field('post_excerpt', $photo_id);
    			$image_desc = get_post_field('post_content', $photo_id);
    	?>
    	
    	<div class="element portfolio3filter_wrapper">
	
			<div class="one_third gallery3 filterable gallery_type animated<?php echo $key+1; ?>" data-id="post-<?php echo $key+1; ?>">
		    	
    		<?php 
    			if(!empty($small_image_url))
    			{
    				$pp_lightbox_enable_title = get_option('pp_lightbox_enable_title');
				    $pp_lightbox_enable_comment_share = get_option('pp_lightbox_enable_comment_share');
    				$pp_social_sharing = get_option('pp_social_sharing');
    		?>		
    				<a <?php if(!empty($pp_lightbox_enable_title)) { ?>title="<?php echo $image_caption; ?>"<?php } ?> class="fancy-gallery" href="<?php echo $image_url[0]; ?>">
	    				<img src="<?php echo $small_image_url[0]; ?>" alt="" class=""/>
						
						<?php if(!empty($pp_lightbox_enable_title) && !empty($image_caption)) { ?>
	    				<div class="thumb_content">
			                <h3><?php echo $image_caption; ?></h3>
			            </div>
			            <?php } ?>
    				</a>
    		<?php
    			}		
    		?>	
    		
		    </div>		
    		
    	</div>
    	
    	<?php
    			$key++;
    		}
    	?>
    	</div>
    	
    	</div>
    </div>
    
</div>
</div>
<br class="clear"/>
<?php get_footer(); ?>