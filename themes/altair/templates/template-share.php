<?php
	$post_type = get_post_type();
	global $page_gallery_id;
	if(!empty($page_gallery_id))
	{
		$post_type = 'galleries';
	}
	
	$show_share = FALSE;

	$pp_social_sharing = get_option('pp_social_sharing');
	if(!empty($pp_social_sharing))
	{
	    $show_share = TRUE;
	}
    
    if($show_share)
    {
    	$pin_thumb = wp_get_attachment_image_src($post->ID, 'gallery_4', true);
    	if(!isset($pin_thumb[0]))
    	{
	    	$pin_thumb[0] = '';
    	}
    	
    	global $share_class;
    	global $share_id;
?>
<div <?php if(!empty($share_id)) { ?>id="<?php echo $share_id; ?>"<?php } ?> class="social_share_wrapper <?php echo $share_class; ?>">
	<ul>
		<li><a class="tooltip" title="<?php _e( 'Share On Facebook', THEMEDOMAIN ); ?>" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_permalink(); ?>"><i class="fa fa-facebook marginright"></i></a></li>
		<li><a class="tooltip" title="<?php _e( 'Share On Twitter', THEMEDOMAIN ); ?>" target="_blank" href="https://twitter.com/intent/tweet?original_referer=<?php echo get_permalink(); ?>&amp;url=<?php echo get_permalink(); ?>"><i class="fa fa-twitter marginright"></i></a></li>
		<li><a class="tooltip" title="<?php _e( 'Share On Pinterest', THEMEDOMAIN ); ?>" target="_blank" href="http://www.pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink()); ?>&amp;media=<?php echo urlencode($pin_thumb[0]); ?>"><i class="fa fa-pinterest marginright"></i></a></li>
		<li><a class="tooltip" title="<?php _e( 'Share On Google+', THEMEDOMAIN ); ?>" target="_blank" href="https://plus.google.com/share?url=<?php echo get_permalink(); ?>"><i class="fa fa-google-plus marginright"></i></a></li>
	</ul>
</div>
<?php
    }
?>