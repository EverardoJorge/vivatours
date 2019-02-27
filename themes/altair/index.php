<?php
/**
 * The main template file.
 *
 * @package WordPress
 */

get_header(); 
?>

<br class="clear"/>

<div id="page_caption">
	<div class="page_title_wrapper">
		<?php
		    //Get site title
		    $site_title = get_bloginfo('title');
		?>
		<h1 class="cufon"><?php _e( 'The Blog', THEMEDOMAIN ); ?></h1>
		<?php echo dimox_breadcrumbs(); ?>
	</div>
</div>

<div id="page_content_wrapper">
    
    <div class="inner">

    	<!-- Begin main content -->
    	<div class="inner_wrapper">
    		
    		<div class="sidebar_content full_width blog_f">
					
<?php

global $more; $more = false; 

$query_string ="post_type=post&paged=$paged";
query_posts($query_string);

if (have_posts()) : while (have_posts()) : the_post();
	$image_thumb = '';
								
	if(has_post_thumbnail(get_the_ID(), 'large'))
	{
	    $image_id = get_post_thumbnail_id(get_the_ID());
	    $image_thumb = wp_get_attachment_image_src($image_id, 'large', true);
	}
?>

<!-- Begin each blog post -->
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="post_wrapper full">
	
		<?php
		    //Get post featured content
		    $post_ft_type = get_post_meta(get_the_ID(), 'post_ft_type', true);
		    
		    switch($post_ft_type)
		    {
		    	case 'Image':
		    	default:
		        	if(!empty($image_thumb))
		        	{
		        		$small_image_url = wp_get_attachment_image_src($image_id, 'blog_f', true);
		?>
		
		    	    <div class="post_img static">
		    	    	<a href="<?php the_permalink(); ?>">
		    	    		<img src="<?php echo $small_image_url[0]; ?>" alt="" class="" style="width:<?php echo $small_image_url[1]; ?>px;height:<?php echo $small_image_url[2]; ?>px;"/>
		                </a>
		    	    </div>
		
		<?php
		    		}
		    	break;
		    	
		    	case 'Vimeo Video':
		    		$post_ft_vimeo = get_post_meta(get_the_ID(), 'post_ft_vimeo', true);
		?>
		    		<?php echo do_shortcode('[tg_vimeo video_id="'.$post_ft_vimeo.'" width="670" height="377"]'); ?>
		<?php
		    	break;
		    	
		    	case 'Youtube Video':
		    		$post_ft_youtube = get_post_meta(get_the_ID(), 'post_ft_youtube', true);
		?>
		    		<?php echo do_shortcode('[tg_youtube video_id="'.$post_ft_youtube.'" width="670" height="377"]'); ?>
		<?php
		    	break;
		    	
		    	case 'Gallery':
		    		$post_ft_gallery = get_post_meta(get_the_ID(), 'post_ft_gallery', true);
		?>
		    		<?php echo do_shortcode('[tg_gallery_slider gallery_id="'.$post_ft_gallery.'" width="670" height="270"]'); ?>
		<?php
		    	break;
		    	
		    } //End switch
		?>
	    
	    <?php
		    	//Check post format
		    	$post_format = get_post_format(get_the_ID());
				
				switch($post_format)
				{
					case 'quote':
			?>
					
					<div class="post_header">
						<div class="post_quote_title">
							<a href="<?php the_permalink(); ?>"><i class="post_qoute_mark fa fa-quote-right"></i><?php the_content(); ?></a>
						</div>
						
						<a href="<?php comments_link(); ?>" class="comment_counter"><i class="fa fa-comments"></i><span><?php comments_number(0, 1, '%'); ?></span></a>
						
						<?php 
					    	$pp_social_sharing = get_option('pp_social_sharing');
							if(!empty($pp_social_sharing))
							{
						    	global $share_id;
						    	$share_id = 'share_post_'.$post->ID;
					    ?>
					    	<a href="javascript:;" class="post_share" data-share="<?php echo $share_id; ?>"><i class="fa fa-share-alt"></i></a>
						    <?php
						    	global $share_class;
						    	$share_class = 'hidden post_list';
						    
								//Get Social Share
								get_template_part("/templates/template-share");
							 ?>
						<?php
							}
						?>
				    
						<a class="readmore" href="<?php the_permalink(); ?>"><?php echo _e( 'Read More', THEMEDOMAIN ); ?><i class="fa fa-angle-right"></i></a>
					</div>
			<?php
					break;
					
					case 'link':
			?>
					
					<div class="post_header link">
						<h5><?php the_content(); ?></h5>
						
						<div class="post_detail">
						    <?php echo get_the_time(THEMEDATEFORMAT); ?>&nbsp;
						    <?php
						    	$author_ID = get_the_author_meta('ID');
						    	$author_name = get_the_author();
						    	$author_url = get_author_posts_url($author_ID);
						    	
						    	if(!empty($author_name))
						    	{
						    ?>
						    	<?php echo _e( 'By', THEMEDOMAIN ); ?>&nbsp;<a href="<?php echo $author_url; ?>"><?php echo $author_name; ?></a>&nbsp;
						    <?php
						    	}
						    ?>
						</div>
						
						<a href="<?php comments_link(); ?>" class="comment_counter"><i class="fa fa-comments"></i><span><?php comments_number(0, 1, '%'); ?></span></a>
						
						<?php 
					    	$pp_social_sharing = get_option('pp_social_sharing');
							if(!empty($pp_social_sharing))
							{
						    	global $share_id;
						    	$share_id = 'share_post_'.$post->ID;
					    ?>
					    	<a href="javascript:;" class="post_share" data-share="<?php echo $share_id; ?>"><i class="fa fa-share-alt"></i></a>
						    <?php
						    	global $share_class;
						    	$share_class = 'hidden post_list';
						    
								//Get Social Share
								get_template_part("/templates/template-share");
							 ?>
						<?php
							}
						?>
				    
						<a class="readmore" href="<?php the_permalink(); ?>"><?php echo _e( 'Read More', THEMEDOMAIN ); ?><i class="fa fa-angle-right"></i></a>
					</div>
			<?php
					break;
					
					default:
		    ?>
		    
			    <div class="post_header">
			    	<h5><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h5>
			    	
			    	<div class="post_detail">
				    	<?php echo get_the_time(THEMEDATEFORMAT); ?>&nbsp;
				    	<?php
							$author_ID = get_the_author_meta('ID');
					    	$author_name = get_the_author();
					    	$author_url = get_author_posts_url($author_ID);
							
							if(!empty($author_name))
							{
						?>
							<?php echo _e( 'By', THEMEDOMAIN ); ?>&nbsp;<a href="<?php echo $author_url; ?>"><?php echo $author_name; ?></a>&nbsp;
						<?php
							}
				    	?>
					</div>
					<br class="clear"/>
				    
				    <?php
				    	$pp_blog_display_full = get_option('pp_blog_display_full');
				    	
				    	if(!empty($pp_blog_display_full))
				    	{
				    		the_content();
				    	}
				    	else
				    	{
				    		the_excerpt();
				    	}
				    ?>
				    <a href="<?php comments_link(); ?>" class="comment_counter"><i class="fa fa-comments"></i><span><?php comments_number(0, 1, '%'); ?></span></a>
				    
				    <?php 
				    	$pp_social_sharing = get_option('pp_social_sharing');
						if(!empty($pp_social_sharing))
						{
					    	global $share_id;
					    	$share_id = 'share_post_'.$post->ID;
				    ?>
				    	<a href="javascript:;" class="post_share" data-share="<?php echo $share_id; ?>"><i class="fa fa-share-alt"></i></a>
					    <?php
					    	global $share_class;
					    	$share_class = 'hidden post_list';
					    
							//Get Social Share
							get_template_part("/templates/template-share");
						 ?>
					<?php
						}
					?>
				    
				    <a class="readmore" href="<?php the_permalink(); ?>"><?php echo _e( 'Read More', THEMEDOMAIN ); ?><i class="fa fa-angle-right"></i></a>
			    </div>
		    <?php
		    		break;
		    	}
		    ?>
		    <br class="clear"/>
	    
	</div>

</div>
<!-- End each blog post -->

<?php endwhile; endif; ?>

	    	<?php
	    	if($wp_query->max_num_pages > 1)
			{
				if (function_exists("wpapi_pagination")) 
				{
				    wpapi_pagination($wp_query->max_num_pages);
				}
				else
				{
				?>
				    <div class="pagination"><p><?php posts_nav_link(' '); ?></p></div>
				<?php
				}
			?>
			<div class="pagination_detail">
		     	<?php
		     		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		     	?>
		     	<?php _e( 'Page', THEMEDOMAIN ); ?> <?php echo $paged; ?> <?php _e( 'of', THEMEDOMAIN ); ?> <?php echo $wp_query->max_num_pages; ?>
		     </div>
		     <br class="clear"/>
		     <?php
		     }
		     ?>
    		
			</div>
    	
    </div>
    <!-- End main content -->

</div>  
<br class="clear"/>
<?php get_footer(); ?>