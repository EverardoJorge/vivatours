<?php
/**
 * The template for displaying the footer.
 *
 * @package WordPress
 */
?>
	
<?php
	/**
    *	Setup Google Analyric Code
    **/
	$pp_ga_code = get_option('pp_ga_code');
	
	if(!empty($pp_ga_code))
	{
		echo stripslashes($pp_ga_code);
	}
	
	//Check if blank template
	global $is_no_header;
	
	if(!is_bool($is_no_header) OR !$is_no_header)
	{
?>

</div>

<?php
	global $pp_homepage_style;
?>
<div class="footer_bar <?php if(isset($pp_homepage_style) && !empty($pp_homepage_style)) { echo $pp_homepage_style; } ?>">
	<?php
	if($pp_homepage_style!='flow' && $pp_homepage_style!='fullscreen' && $pp_homepage_style!='carousel' && $pp_homepage_style!='flip' && $pp_homepage_style!='fullscreen_video')
	{
	    $pp_footer_display_sidebar = get_option('pp_footer_display_sidebar');
	
	    if(!empty($pp_footer_display_sidebar))
	    {
	    	$pp_footer_style = get_option('pp_footer_style');
	    	$footer_class = '';
	    	
	    	switch($pp_footer_style)
	    	{
	    		case 1:
	    			$footer_class = 'one';
	    		break;
	    		case 2:
	    			$footer_class = 'two';
	    		break;
	    		case 3:
	    			$footer_class = 'three';
	    		break;
	    		case 4:
	    			$footer_class = 'four';
	    		break;
	    		default:
	    			$footer_class = 'four';
	    		break;
	    	}
	    	
	    	global $pp_homepage_style;
	?>
	<div id="footer" class="<?php if(isset($pp_homepage_style) && !empty($pp_homepage_style)) { echo $pp_homepage_style; } ?>">
	<ul class="sidebar_widget <?php echo $footer_class; ?>">
	    <?php dynamic_sidebar('Footer Sidebar'); ?>
	</ul>
	
	<br class="clear"/>
	</div>
	<?php
	    }
	}
	?>

	<div class="footer_bar_wrapper <?php if(isset($pp_homepage_style) && !empty($pp_homepage_style)) { echo $pp_homepage_style; } ?>">
		<?php
			if($pp_homepage_style!='flow' && $pp_homepage_style!='fullscreen' && $pp_homepage_style!='carousel' && $pp_homepage_style!='flip' && $pp_homepage_style!='fullscreen_video')
			{	
				//Check if open link in new window
				$pp_footer_social_link_blank = get_option('pp_footer_social_link_blank');
		?>
		<div class="social_wrapper">
		    <ul>
		    	<?php
		    		$pp_facebook_username = get_option('pp_facebook_username');
		    		
		    		if(!empty($pp_facebook_username))
		    		{
		    	?>
		    	<li class="facebook"><a <?php if(!empty($pp_footer_social_link_blank)) { ?>target="_blank"<?php } ?> href="<?php echo $pp_facebook_username; ?>"><i class="fa fa-facebook"></i></a></li>
		    	<?php
		    		}
		    	?>
		    	<?php
		    		$pp_twitter_username = get_option('pp_twitter_username');
		    		
		    		if(!empty($pp_twitter_username))
		    		{
		    	?>
		    	<li class="twitter"><a <?php if(!empty($pp_footer_social_link_blank)) { ?>target="_blank"<?php } ?> href="http://twitter.com/<?php echo $pp_twitter_username; ?>"><i class="fa fa-twitter"></i></a></li>
		    	<?php
		    		}
		    	?>
		    	<?php
		    		$pp_flickr_username = get_option('pp_flickr_username');
		    		
		    		if(!empty($pp_flickr_username))
		    		{
		    	?>
		    	<li class="flickr"><a <?php if(!empty($pp_footer_social_link_blank)) { ?>target="_blank"<?php } ?> title="Flickr" href="http://flickr.com/people/<?php echo $pp_flickr_username; ?>"><i class="fa fa-flickr"></i></a></li>
		    	<?php
		    		}
		    	?>
		    	<?php
		    		$pp_youtube_username = get_option('pp_youtube_username');
		    		
		    		if(!empty($pp_youtube_username))
		    		{
		    	?>
		    	<li class="youtube"><a <?php if(!empty($pp_footer_social_link_blank)) { ?>target="_blank"<?php } ?> title="Youtube" href="http://youtube.com/channel/<?php echo $pp_youtube_username; ?>"><i class="fa fa-youtube"></i></a></li>
		    	<?php
		    		}
		    	?>
		    	<?php
		    		$pp_vimeo_username = get_option('pp_vimeo_username');
		    		
		    		if(!empty($pp_vimeo_username))
		    		{
		    	?>
		    	<li class="vimeo"><a <?php if(!empty($pp_footer_social_link_blank)) { ?>target="_blank"<?php } ?> title="Vimeo" href="http://vimeo.com/<?php echo $pp_vimeo_username; ?>"><i class="fa fa-vimeo-square"></i></a></li>
		    	<?php
		    		}
		    	?>
		    	<?php
		    		$pp_tumblr_username = get_option('pp_tumblr_username');
		    		
		    		if(!empty($pp_tumblr_username))
		    		{
		    	?>
		    	<li class="tumblr"><a <?php if(!empty($pp_footer_social_link_blank)) { ?>target="_blank"<?php } ?> title="Tumblr" href="http://<?php echo $pp_tumblr_username; ?>.tumblr.com"><i class="fa fa-tumblr"></i></a></li>
		    	<?php
		    		}
		    	?>
		    	<?php
		    		$pp_google_username = get_option('pp_google_username');
		    		
		    		if(!empty($pp_google_username))
		    		{
		    	?>
		    	<li class="google"><a <?php if(!empty($pp_footer_social_link_blank)) { ?>target="_blank"<?php } ?> title="Google+" href="<?php echo $pp_google_username; ?>"><i class="fa fa-google-plus"></i></a></li>
		    	<?php
		    		}
		    	?>
		    	<?php
		    		$pp_dribbble_username = get_option('pp_dribbble_username');
		    		
		    		if(!empty($pp_dribbble_username))
		    		{
		    	?>
		    	<li class="dribbble"><a <?php if(!empty($pp_footer_social_link_blank)) { ?>target="_blank"<?php } ?> title="Dribbble" href="http://dribbble.com/<?php echo $pp_dribbble_username; ?>"><i class="fa fa-dribbble"></i></a></li>
		    	<?php
		    		}
		    	?>
		    	<?php
		    		$pp_linkedin_username = get_option('pp_linkedin_username');
		    		
		    		if(!empty($pp_linkedin_username))
		    		{
		    	?>
		    	<li class="linkedin"><a <?php if(!empty($pp_footer_social_link_blank)) { ?>target="_blank"<?php } ?> title="Linkedin" href="<?php echo $pp_linkedin_username; ?>"><i class="fa fa-linkedin"></i></a></li>
		    	<?php
		    		}
		    	?>
		    	<?php
		            $pp_pinterest_username = get_option('pp_pinterest_username');
		            
		            if(!empty($pp_pinterest_username))
		            {
		        ?>
		        <li class="pinterest"><a <?php if(!empty($pp_footer_social_link_blank)) { ?>target="_blank"<?php } ?> title="Pinterest" href="http://pinterest.com/<?php echo $pp_pinterest_username; ?>"><i class="fa fa-pinterest"></i></a></li>
		        <?php
		            }
		        ?>
		        <?php
		        	$pp_instagram_username = get_option('pp_instagram_username');
		        	
		        	if(!empty($pp_instagram_username))
		        	{
		        ?>
		        <li class="instagram"><a <?php if(!empty($pp_footer_social_link_blank)) { ?>target="_blank"<?php } ?> title="Instagram" href="http://instagram.com/<?php echo $pp_instagram_username; ?>"><i class="fa fa-instagram"></i></a></li>
		        <?php
		        	}
		        ?>
		        <?php
				    $pp_behance_username = get_option('pp_behance_username');
				    
				    if(!empty($pp_behance_username))
				    {
				?>
				<li class="behance"><a <?php if(!empty($pp_topbar_social_link_blank)) { ?>target="_blank"<?php } ?> title="Behance" href="http://behance.net/<?php echo $pp_behance_username; ?>"><i class="fa fa-behance-square"></i></a></li>
				<?php
				    }
				?>
				<?php
		    		$pp_tripadvisor_url = get_option('pp_tripadvisor_url');
		    		
		    		if(!empty($pp_tripadvisor_url))
		    		{
		    	?>
		    	<li class="tripadvisor"><a <?php if(!empty($pp_footer_social_link_blank)) { ?>target="_blank"<?php } ?> title="Tripadvisor" href="<?php echo $pp_tripadvisor_url; ?>"><i class="fa fa-tripadvisor"></i></a></li>
		    	<?php
		    		}
		    	?>
		    </ul>
		</div>
		<?php
			}
		?>
	    <?php
	        $pp_footer_text = get_option('pp_footer_text');
	        if(!empty($pp_footer_text))
	        {
	        	echo '<div id="copyright">'.stripslashes($pp_footer_text).'</div><br class="clear"/>';
	        }
	    ?>
	    
	    <div id="toTop"><i class="fa fa-angle-up"></i></div>
	</div>
</div>

<?php
    } //End if not blank template
?>

<?php
	/**
    *	Setup code before </body>
    **/
	$pp_before_body_code = get_option('pp_before_body_code');
	
	if(!empty($pp_before_body_code))
	{
		echo stripslashes($pp_before_body_code);
	}
?>

<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	wp_footer();
?>
</body>
</html>
