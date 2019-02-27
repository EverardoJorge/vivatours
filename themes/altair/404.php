<?php
/**
 * The main template file for display error page.
 *
 * @package WordPress
*/


get_header(); 
?>

<br class="clear"/>

<div id="page_caption">
	<div class="page_title_wrapper">
		<h1><?php _e( 'Page Not Found', THEMEDOMAIN ); ?></h1>
	</div>
</div>
<br class="clear"/>

<!-- Begin content -->
<div id="page_content_wrapper">

    <div class="inner">
    
    	<!-- Begin main content -->
    	<div class="inner_wrapper">
    	
	    	<div class="sidebar_content full_width">
	    		<div class="search_form_wrapper">
	    			<h5><?php _e( 'New Search', THEMEDOMAIN ); ?></h5>
	    			<?php _e( "Oops, This Page Could Not Be Found. Try a new search.", THEMEDOMAIN ); ?><br/><br/>
	    			
	    			<form class="searchform" role="search" method="get" action="<?php echo home_url(); ?>">
						<input style="width:96%" type="text" class="field searchform-s" name="s" value="<?php the_search_query(); ?>" title="<?php _e( 'Type and hit enter', THEMEDOMAIN ); ?>">
					</form>
    			</div>	    		
	    	</div>
	    	
	    		<h4><?php _e( 'Or try to browse our latest posts instead?', THEMEDOMAIN ); ?></h4>
	    		
	    		<div id="blog_grid_wrapper" class="sidebar_content full_width">
	    		<?php
				global $more; $more = false; 
				
				$query_string ="items=6&post_type=post&paged=$paged";
				query_posts($query_string);
				$key = 0;
				
				if (have_posts()) : while (have_posts()) : the_post();
					
					$animate_layer = $key+7;
					$image_thumb = '';
												
					if(has_post_thumbnail(get_the_ID(), 'large'))
					{
					    $image_id = get_post_thumbnail_id(get_the_ID());
					    $image_thumb = wp_get_attachment_image_src($image_id, 'large', true);
					}
				?>
				
				<!-- Begin each blog post -->
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				
					<div class="post_wrapper grid_layout">
					
						<?php
						    //Get post featured content
						    $post_ft_type = get_post_meta(get_the_ID(), 'post_ft_type', true);
						    
						    switch($post_ft_type)
						    {
						    	case 'Image':
						    	default:
						        	if(!empty($image_thumb))
						        	{
						        		$small_image_url = wp_get_attachment_image_src($image_id, 'blog_g', true);
						?>
						
						    	    <div class="post_img small static">
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
						    		<?php echo do_shortcode('[tg_gallery_slider gallery_id="'.$post_ft_gallery.'" size="gallery_2" width="670" height="270"]'); ?>
						<?php
						    	break;
						    	
						    } //End switch
						?>
					    
					    <div class="blog_grid_content">
							<?php
						    	//Check post format
						    	$post_format = get_post_format(get_the_ID());
								
								switch($post_format)
								{
									case 'quote':
							?>
									
									<div class="post_header quote">
										<div class="post_quote_title grid">
											<a href="<?php the_permalink(); ?>"><i class="post_qoute_mark fa fa-quote-right"></i><?php the_content(); ?></a>
											
											<div class="post_detail">
										    	<?php echo get_the_time(THEMEDATEFORMAT); ?>&nbsp;
											</div>
										</div>
									</div>
							<?php
									break;
									
									case 'link':
							?>
									
									<div class="post_header quote">
										<h6><?php the_content(); ?></h6>
											
										<div class="post_detail">
										   	<?php echo get_the_time(THEMEDATEFORMAT); ?>
										</div>
									</div>
							<?php
									break;
									
									default:
						    ?>
							    <div class="post_header grid">
							    	<h6><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h6>
							    	<div class="post_detail">
									    <?php echo get_the_time(THEMEDATEFORMAT); ?>
									</div>
									
									<br class="clear"/>
									<?php the_excerpt(); ?>
							    </div>
							    
							    <a href="<?php comments_link(); ?>" class="comment_counter"><i class="fa fa-comments"></i><span><?php comments_number(0, 1, '%'); ?></span></a>
								<a class="readmore" href="<?php the_permalink(); ?>"><?php echo _e( 'Read More', THEMEDOMAIN ); ?><i class="fa fa-angle-right"></i></a>
								<br class="clear"/>
						    <?php
						    		break;
						    	}
						    ?>
					    </div>
					    
					</div>
				
				</div>
				<!-- End each blog post -->
				
				<?php $key++; ?>
				<?php endwhile; endif; ?>
	    		</div>
    		
    	</div>
    	
    </div>
</div>
<br class="clear"/><br/><br/>
<?php get_footer(); ?>