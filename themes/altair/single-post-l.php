<?php
/**
 * ESTE ARCHIVO HA SIDO REVISADO
 */
/**
 * The main template file for display single post page.
 *
 * @package WordPress
*/

get_header();

global $global_pp_topbar;

/**
*	Get current page id
**/

$current_page_id = $post->ID;

?>

<br class="clear"/>

<?php
	$page_menu_transparent = get_post_meta($current_page_id, 'post_menu_transparent', true);
	$pp_page_bg = '';
	
	//Get page featured image
	$post_header_background = get_post_meta($current_page_id, 'post_header_background', true);
	if(!empty($post_header_background))
	{
		//Get image width and height
		$post_header_background_id = pp_get_image_id($post_header_background);
		$post_header_background_image = wp_get_attachment_image_src($post_header_background_id, 'original');
	    
	    $background_image = $post_header_background_image[0];
		$background_image_width = $post_header_background_image[1];
		$background_image_height = $post_header_background_image[2];
	}
	
	//Check if display post featured imageas background
	if(isset($background_image[0]) && !empty($background_image[0]))
	{
	    $pp_page_bg = $background_image[0];
	}
?>

<div id="page_caption" <?php if(!empty($pp_page_bg)) { ?>class="hasbg parallax <?php if(empty($page_menu_transparent)) { ?>notransparent<?php } ?>" data-image="<?php echo $background_image; ?>" data-width="<?php echo $background_image_width; ?>" data-height="<?php echo $background_image_height; ?>"<?php } ?>>
	<div class="page_title_wrapper">
		<h1 <?php if(!empty($pp_page_bg) && !empty($global_pp_topbar)) { ?>class ="withtopbar"<?php } ?>><?php the_title() ?></h1>
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
	</div>
	<?php if(!empty($pp_page_bg)) { ?>
		<div class="parallax_overlay_header"></div>
	<?php } ?>
</div>

<?php
//If display feat content
$pp_blog_feat_content = get_option('pp_blog_feat_content');

/**
*	Get current page id
**/

$current_page_id = $post->ID;
$post_gallery_id = '';
if(!empty($pp_blog_feat_content))
{
	$post_gallery_id = get_post_meta($current_page_id, 'post_gallery_id', true);
}
?>
<div id="page_content_wrapper" class="<?php if(!empty($pp_page_bg)) { ?>hasbg<?php } ?> <?php if(empty($page_menu_transparent)) { ?>notransparent<?php } ?> <?php if(!empty($pp_page_bg) && !empty($global_pp_topbar)) { ?>withtopbar<?php } ?>">
    
    <div class="inner">

    	<!-- Begin main content -->
    	<div class="inner_wrapper">

    		<div class="sidebar_content left_sidebar">
					
<?php

global $more; $more = false; # some wordpress wtf logic

if (have_posts()) : while (have_posts()) : the_post();

	$image_thumb = '';
								
	if(!empty($pp_blog_feat_content) && has_post_thumbnail(get_the_ID(), 'large'))
	{
	    $image_id = get_post_thumbnail_id(get_the_ID());
	    $image_thumb = wp_get_attachment_image_src($image_id, 'large', true);
	}
?>
						
<!-- Begin each blog post -->
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="post_wrapper">
	    
	    <div class="post_content_wrapper">
	    
	    	<?php
	    	if(!empty($pp_blog_feat_content) )
	    	{
			    //Get post featured content
			    $post_ft_type = get_post_meta(get_the_ID(), 'post_ft_type', true);
			    
			    switch($post_ft_type)
			    {
			    	case 'Image':
			    	default:
			        	if(!empty($image_thumb))
			        	{
			        		$large_image_url = wp_get_attachment_image_src($image_id, 'original', true);
			        		$small_image_url = wp_get_attachment_image_src($image_id, 'blog', true);
			?>
			
			    	    <div class="post_img static">
			    	    	<img src="<?php echo $small_image_url[0]; ?>" alt="" class="" style="width:<?php echo $small_image_url[1]; ?>px;height:<?php echo $small_image_url[2]; ?>px;"/>
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
			} //End if
			?>
		    
		    <?php
		    	//Check post format
		    	$post_format = get_post_format(get_the_ID());
				
				switch($post_format)
				{
					case 'quote':
			?>
					
					<i class="post_qoute_mark fa fa-quote-right"></i>
					
					<div class="post_header">
						<div class="post_quote_title">
							<a href="<?php the_permalink(); ?>"><?php the_content(); ?></a>
							
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
						</div>
						
						<?php
					    	if(has_tag())
					    	{
					    ?>
						    <div class="post_excerpt post_tag" style="text-align:left">
						    	<i class="fa fa-tags"></i>
						    	<?php the_tags('', ', ', '<br />'); ?>
						    </div>
					    <?php
					    	}
					    ?>
					</div>
			<?php
					break;
					
					case 'link':
						$link_content = str_ireplace('<p>','',get_the_content());
						$link_content = str_ireplace('</p>','',get_the_content());
			?>
					
					<div class="post_header link">
						<h5><?php echo $link_content; ?></h5>
							
					    <div class="post_detail">
					    	<?php echo _e( 'Posted On', THEMEDOMAIN ); ?>&nbsp;<?php echo get_the_time(THEMEDATEFORMAT); ?>; ?>&nbsp;
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
					        <?php echo _e( 'And has', THEMEDOMAIN ); ?>&nbsp;<a href="<?php comments_link(); ?>"><?php comments_number(__('No Comment', THEMEDOMAIN), __('1 Comment', THEMEDOMAIN), __('% Comments', THEMEDOMAIN)); ?></a>
					    </div>
					</div>
					
					<?php
					    if(has_tag())
					    {
					?>
					    <div class="post_excerpt post_tag" style="text-align:left">
					    	<i class="fa fa-tags"></i>
					    	<?php the_tags('', ', ', '<br />'); ?>
					    </div>
					<?php
					    }
					?>
			<?php
					break;
					
					default:
		    ?>
		    
			    <div class="post_header">
				    
				    <?php
				    	the_content();
						wp_link_pages();
				    ?>
				    
				    <?php
					    if(has_tag())
					    {
					?>
					    <div class="post_excerpt post_tag" style="text-align:left">
					    	<?php the_tags('', ', ', ''); ?>
					    	<i class="fa fa-tags"></i>
					    </div>
					<?php
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
			    </div>
		    <?php
		    		break;
		    	}
		    ?>
			
	    </div>
	    
	</div>

</div>
<!-- End each blog post -->
<br class="clear"/><br/><hr/><br/>

<?php
    $args = array(
    	'before'           => '<p>' . __('Pages:', THEMEDOMAIN),
    	'after'            => '</p>',
    	'link_before'      => '',
    	'link_after'       => '',
    	'next_or_number'   => 'number',
    	'nextpagelink'     => __('Next page', THEMEDOMAIN),
    	'previouspagelink' => __('Previous page', THEMEDOMAIN),
    	'pagelink'         => '%',
    	'echo'             => 1
    );
    wp_link_pages($args);

    $pp_blog_next_prev = get_option('pp_blog_next_prev');
    
    if($pp_blog_next_prev)
    {
?>
<?php
    	//Get Previous and Next Post
    	$prev_post = get_previous_post();
    	$next_post = get_next_post();
?>
<div class="blog_next_prev_wrapper">
   <div class="post_previous">
      	<?php
    	    //Get Previous Post
    	    if (!empty($prev_post)): 
    	    	$prev_image_thumb = wp_get_attachment_image_src(get_post_thumbnail_id($prev_post->ID), 'thumbnail', true);
    	    	if(isset($prev_image_thumb[0]))
    	    	{
					$image_file_name = basename($prev_image_thumb[0]);
    	    	}
    	?>
      		<span class="post_previous_icon"><i class="fa fa-angle-left"></i></span>
      		<div class="post_previous_content">
      			<h6><?php echo _e( 'Previous Article', THEMEDOMAIN ); ?></h6>
      			<strong><a <?php if(isset($prev_image_thumb[0]) && $image_file_name!='default.png') { ?>class="post_prev_next_link" data-img="<?php echo $prev_image_thumb[0]; ?>"<?php } ?> href="<?php echo get_permalink( $prev_post->ID ); ?>"><?php echo $prev_post->post_title; ?></a></strong>
      		</div>
      	<?php endif; ?>
   </div>
<span class="separated"></span>
   <div class="post_next">
   		<?php
    	    //Get Next Post
    	    if (!empty($next_post)): 
    	    	$next_image_thumb = wp_get_attachment_image_src(get_post_thumbnail_id($next_post->ID), 'thumbnail', true);
    	    	if(isset($next_image_thumb[0]))
    	    	{
					$image_file_name = basename($next_image_thumb[0]);
    	    	}
    	?>
      		<span class="post_next_icon"><i class="fa fa-angle-right"></i></span>
      		<div class="post_next_content">
      			<h6><?php echo _e( 'Next Article', THEMEDOMAIN ); ?></h6>
      			<strong><a <?php if(isset($prev_image_thumb[0]) && $image_file_name!='default.png') { ?>class="post_prev_next_link" data-img="<?php echo $next_image_thumb[0]; ?>"<?php } ?> href="<?php echo get_permalink( $next_post->ID ); ?>"><?php echo $next_post->post_title; ?></a></strong>
      		</div>
      	<?php endif; ?>
   </div>
</div>
<?php
    	//If has previous or next post then add line break
    	if(!empty($prev_post) OR !empty($next_post))
    	{
    		echo '<br/>';
    	}
?>
<?php
    }
?>

<?php
    $pp_blog_next_prev = get_option('pp_blog_next_prev');
    
    if($pp_blog_next_prev)
    {
?>
<?php
    	//Get Previous and Next Post
    	$prev_post = get_previous_post();
    	$next_post = get_next_post();
?>
<div class="blog_next_prev_wrapper">
   <div class="post_previous">
      	<?php
    	    //Get Previous Post
    	    if (!empty($prev_post)): 
    	    	$prev_image_thumb = wp_get_attachment_image_src(get_post_thumbnail_id($prev_post->ID), 'thumbnail', true);
    	    	if(isset($prev_image_thumb[0]))
    	    	{
					$image_file_name = basename($prev_image_thumb[0]);
    	    	}
    	?>
      		<span class="post_previous_icon"><i class="fa fa-angle-left"></i></span>
      		<div class="post_previous_content">
      			<h6><?php echo _e( 'Previous Article', THEMEDOMAIN ); ?></h6>
      			<strong><a <?php if(isset($prev_image_thumb[0]) && $image_file_name!='default.png') { ?>class="post_prev_next_link" data-img="<?php echo $prev_image_thumb[0]; ?>"<?php } ?> href="<?php echo get_permalink( $prev_post->ID ); ?>"><?php echo $prev_post->post_title; ?></a></strong>
      		</div>
      	<?php endif; ?>
   </div>
<span class="separated"></span>
   <div class="post_next">
   		<?php
    	    //Get Next Post
    	    if (!empty($next_post)): 
    	    	$next_image_thumb = wp_get_attachment_image_src(get_post_thumbnail_id($next_post->ID), 'thumbnail', true);
    	    	if(isset($next_image_thumb[0]))
    	    	{
					$image_file_name = basename($next_image_thumb[0]);
    	    	}
    	?>
      		<span class="post_next_icon"><i class="fa fa-angle-right"></i></span>
      		<div class="post_next_content">
      			<h6><?php echo _e( 'Next Article', THEMEDOMAIN ); ?></h6>
      			<strong><a <?php if(isset($prev_image_thumb[0]) && $image_file_name!='default.png') { ?>class="post_prev_next_link" data-img="<?php echo $next_image_thumb[0]; ?>"<?php } ?> href="<?php echo get_permalink( $next_post->ID ); ?>"><?php echo $next_post->post_title; ?></a></strong>
      		</div>
      	<?php endif; ?>
   </div>
</div>
<?php
    	//If has previous or next post then add line break
    	if(!empty($prev_post) OR !empty($next_post))
    	{
    		echo '<br/>';
    	}
?>
<?php
    }
?>

<?php
    $pp_blog_display_related = get_option('pp_blog_display_related');
    
    if($pp_blog_display_related)
    {
?>

<?php
//for use in the loop, list 9 post titles related to post's tags on current post
$tags = wp_get_post_tags($post->ID);

if ($tags) {

    $tag_in = array();
  	//Get all tags
  	foreach($tags as $tags)
  	{
      	$tag_in[] = $tags->term_id;
  	}

  	$args=array(
      	  'tag__in' => $tag_in,
      	  'post__not_in' => array($post->ID),
      	  'showposts' => 3,
      	  'ignore_sticky_posts' => 1,
      	  'orderby' => 'date',
      	  'meta_query' => array(
		        array(
		         'key' => '_thumbnail_id',
		         'compare' => 'EXISTS'
		        ),
		   ),
      	  'order' => 'DESC'
  	 );
  	$my_query = new WP_Query($args);
  	$i_post = 1;
  	
  	if( $my_query->have_posts() ) {
  	  	echo '<br/><h5 class="widgettitle"><span>'.__( 'You might also like', THEMEDOMAIN ).'</span></h5><br class="clear"/>';
 ?>
 	<div class="one related">
    	 <?php
    	 	global $have_related;
    	    while ($my_query->have_posts()) : $my_query->the_post();
    	    $have_related = TRUE; 
    	 ?>
    	    <div class="one_third <?php if($i_post%3==0){ ?>last<?php } ?>">

				<div class="post_wrapper grid_layout">
				
    	    	<?php
    	    		if(has_post_thumbnail($post->ID, 'blog_g'))
    				{
    					$image_id = get_post_thumbnail_id($post->ID);
    					$image_url = wp_get_attachment_image_src($image_id, 'blog_g', true);
    	    	?>
    	    	<div class="post_img small static">
		    	    <a href="<?php the_permalink(); ?>">
		    	    	<img src="<?php echo $image_url[0]; ?>" alt="" class="" style="width:<?php echo $image_url[1]; ?>px;height:<?php echo $image_url[2]; ?>px;"/>
		            </a>
		    	    </div>
    	    	<?php
    	    		}
    	    	?>
    	    	<div class="blog_grid_content">
    	    		<div class="post_header grid">
				    	<h6><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h6>
				    	<div class="post_detail">
						    <?php echo get_the_time(THEMEDATEFORMAT); ?>
						</div>
				    </div>
    	    	</div>
    	    	
				</div>
    		</div>
    	  <?php
    	  		$i_post++;
    			endwhile;
    			    
    			wp_reset_query();
    	  ?>
      
  	</div>
    <br class="clear"/><br/>
<?php
  	}
}
?>

<?php
    } //end if show related
?>

<?php comments_template( '' ); ?>

<?php endwhile; endif; ?>
						
    	</div>

    		<div class="sidebar_wrapper left_sidebar">
    		
    			<div class="sidebar_top"></div>
    		
    			<div class="sidebar">
    			
    				<div class="content">
    			
    					<ul class="sidebar_widget">
    					<?php dynamic_sidebar('Single Post Sidebar'); ?>
    					</ul>
    				
    				</div>
    		
    			</div>
    			<br class="clear"/>
    	
    			<div class="sidebar_bottom"></div>
    		</div>
    
    </div>
    <!-- End main content -->
   
</div>

<?php
    //Get More Story Module
    $pp_blog_more_story = get_option('pp_blog_more_story');
    
    if(!empty($prev_post) && !empty($pp_blog_more_story))
    {
    	$post_more_image = '';
    	if(has_post_thumbnail(get_the_ID(), 'blog_g'))
    	{
    	    $post_more_image_id = get_post_thumbnail_id($prev_post->ID);
    	    $post_more_image = wp_get_attachment_image_src($post_more_image_id, 'blog_g', true);
    	}
    	
    	if(isset($post_more_image[0]))
    	{
		    $image_file_name = basename($post_more_image[0]);
    	}
?>
<div id="post_more_wrapper" class="hiding">
    <h5><span><?php _e( 'More Story', THEMEDOMAIN ); ?></span><a href="javascript:;" id="post_more_close"><i class="fa fa-times"></i></a></h5><br class="clear"/>
    <?php
    	if(!empty($post_more_image) && $image_file_name!='default.png')
    	{
    ?>
    <div class="post_img grid">
	    <a href="<?php echo get_permalink($prev_post->ID); ?>">
	    	<img src="<?php echo $post_more_image[0]; ?>" alt="" class="" style="width:<?php echo $post_more_image[1]; ?>px;height:<?php echo $post_more_image[2]; ?>px;"/>
	    	<div class="mask">
            	<div class="mask_circle">
	            	<i class="fa fa-external-link"/></i>
	    		</div>
	        </div>
	    </a>
	</div>
    <?php
    	}
    ?>
    <div class="content">
	    <a class="post_more_title" href="<?php echo get_permalink($prev_post->ID); ?>">
	    	<h6><?php echo $prev_post->post_title; ?></h6>
	    </a>
	    <div class="post_detail">
		    <?php echo date_i18n(THEMEDATEFORMAT, strtotime($prev_post->post_date)); ?>&nbsp;-&nbsp;<?php echo date_i18n(THEMETIMEFORMAT, strtotime($prev_post->post_date)); ?>
		</div>
		
	    <?php echo pp_substr(strip_tags(strip_shortcodes($prev_post->post_content)), 110); ?>
    </div>

</div>
<?php
    }
?>
<br class="clear"/>
<?php get_footer(); ?>