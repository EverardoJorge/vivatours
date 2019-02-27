<?php
/**
 * ESTE ARCHIVO HA SIDO REVISADO
 */
/**
 * Template Name: Tour Grid Fullwidth
 * The main template file for display tours page.
 *
 * @package WordPress
*/

/**
*	Get Current page object
**/
$current_page_id = '';

if(is_object($post))
{
	$page = get_page($post->ID);
	
	if(isset($page->ID))
	{
	    $current_page_id = $page->ID;
	}
}

get_header();
?>

<?php
	global $is_fullwidth_page;
	$is_fullwidth_page = TRUE;

    //Include custom header feature
	get_template_part("/templates/template-header");
?>

<!-- Begin content --> 
<div class="inner">

	<div class="inner_wrapper">
	
	<div id="page_main_content" class="sidebar_content full_width">
	
	<?php
	    if(empty($term) && is_object($post))
	    {
	?>
	    <?php echo tg_apply_content($post->post_content); ?>
	<?php
	    }
	    elseif(!empty($term))
	    { 
	    	$obj_term = get_term_by('slug', $term, 'tourcats');
	?>
	    <?php echo tg_apply_content($obj_term->description); ?>
	<?php
	    }
	?>
	
	<div class="page_content_wrapper">
	<?php
	    //Include custom tour search feature
		get_template_part("/templates/template-tour-search");
	?>
	</div>
	
	<div id="portfolio_filter_wrapper" class="three_cols gallery fullwidth section content clearfix">
	<?php
		$key = 0;
		if (have_posts()) : while (have_posts()) : the_post();
			$key++;
			$image_url = '';
			$tour_ID = get_the_ID();
					
			if(has_post_thumbnail($tour_ID, 'large'))
			{
			    $image_id = get_post_thumbnail_id($tour_ID);
			    $image_url = wp_get_attachment_image_src($image_id, 'full', true);
			    
			    $small_image_url = wp_get_attachment_image_src($image_id, 'gallery_grid', true);
			}
			
			//Get Tour Meta
			$tour_permalink_url = get_permalink($tour_ID);
			$tour_title = get_the_title();
			$tour_country= get_post_meta($tour_ID, 'tour_country', true);
			$tour_price= get_post_meta($tour_ID, 'tour_price', true);
			$tour_price_discount= get_post_meta($tour_ID, 'tour_price_discount', true);
			$tour_price_currency= get_post_meta($tour_ID, 'tour_price_currency', true);
			$tour_discount_percentage = 0;
			if(!empty($tour_price_discount))
			{
				if($tour_price_discount < $tour_price)
				{
					$tour_discount_percentage = intval((($tour_price-$tour_price_discount)/$tour_price)*100);
				}
			}
			
			//Get number of your days
			$tour_days = 0;
			$tour_start_date= get_post_meta($tour_ID, 'tour_start_date', true);
			$tour_end_date= get_post_meta($tour_ID, 'tour_end_date', true);
				
			if(!empty($tour_start_date) && !empty($tour_end_date))
			{
				$tour_start_date_raw= get_post_meta($tour_ID, 'tour_start_date_raw', true);
				$tour_end_date_raw= get_post_meta($tour_ID, 'tour_end_date_raw', true);
				$tour_days = pp_date_diff($tour_start_date_raw, $tour_end_date_raw);
				if($tour_days > 0)
				{
					$tour_days = intval($tour_days+1).' '.__( 'Dias', THEMEDOMAIN );
				}
				else
				{
					$tour_days = intval($tour_days+1).' '.__( 'Dia', THEMEDOMAIN );
				}
			}
			
			$tour_price_display = 0;
			if(empty($tour_price_discount))
			{
				if(!empty($tour_price))
				{
					$tour_price_display = $tour_price_currency.pp_number_format($tour_price);
				}
			}
			else
			{
				$tour_price_display = $tour_price_currency.pp_number_format($tour_price_discount);
			}
			
			$last_class = '';
			if(($key)%3==0)
			{
				$last_class = 'last';
			}
	?>
	
	<div class="element portfolio3filter_wrapper">
	
		<div class="one_third gallery3 filterable gallery_type animated<?php echo $key+1; ?>" data-id="post-<?php echo $key+1; ?>">
			<?php 
				if(!empty($image_url[0]))
				{
			?>		
				<a href="<?php echo $tour_permalink_url; ?>">
        		    <img src="<?php echo $small_image_url[0]; ?>" alt="" />
        		</a>
        		
        		<?php
        		if(!empty($tour_discount_percentage))
        		{
        		?>
        		<div class="tour_sale fullwidth">
        			<div class="tour_sale_text"><?php _e( 'Best Deal', THEMEDOMAIN ); ?></div>
        			<?php echo $tour_discount_percentage.'% '.__( 'Descuento', THEMEDOMAIN ); ?>
        		</div>
        		<?php
        		}
        		?>
				
	            <div class="thumb_content fullwidth">
	                <div class="thumb_title">
	                	<?php
	                	if(!empty($tour_country))
	                	{
	                	?>
	                	<div class="tour_country">
	                		<?php echo $tour_country; ?>
	                	</div>
	                	<?php
	                	}
	                	?>
				        <h3><?php echo $tour_title; ?></h3>
	                </div>
	                <div class="thumb_meta fullwidth">
	                	<?php
	                	if(!empty($tour_days))
	                	{
	                	?>
	                	<div class="tour_days">
	                		<?php echo $tour_days; ?>
	                	</div>
	                	<?php
	                	}
	                	?>
	                	<?php
	                	if($tour_price > 0)
	                	{
	                	?>
	                	<div class="tour_price">
	                		<?php echo $tour_price_display; ?>
	                	</div>
	                	<?php
	                	}
	                	?>
	                </div>
				</div>
			<?php
				}		
			?>	
		</div>
	
	</div>
	
	<?php
		endwhile; endif; 
	?>
	</div>
	
	<?php
	    if($wp_query->max_num_pages > 1)
	    {
	?>
		<div class="page_content_wrapper">
	<?php	
	    	if (function_exists("wpapi_pagination")) 
	    	{
	?>
			<br class="clear"/>
	<?php
				if(!is_front_page())
			    {
			    	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			    }
			    else
			    {
				    $paged = (get_query_var('page')) ? get_query_var('page') : 1;
			    }
			    
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
	     	<?php _e( 'Página', THEMEDOMAIN ); ?> <?php echo $paged; ?> <?php _e( 'de ', THEMEDOMAIN ); ?> <?php echo $wp_query->max_num_pages; ?>
	     </div>
		</div>
	<?php
	     }
	?>
	
	</div>
</div>
</div>
</div>
<?php get_footer(); ?>