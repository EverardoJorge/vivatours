<?php
function ppb_text_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'size' => 'one',
		'layout' => 'fixedwidth',
		'background' => '',
		'background_parallax' => 'none',
		'custom_css' => '',
	), $atts));

	$return_html = '<div class="'.$size.' withsmallpadding ';
	
	if(!empty($background))
	{
		$return_html.= 'withbg ';
	}
	
	if(!empty($layout) && $layout == 'fullwidth')
	{
		$return_html.= 'fullwidth ';
	}
	
	if(!empty($background_parallax) && $background_parallax!='none')
	{
		$return_html.= 'parallax';
	}
	$return_html.= '" ';
	
	$parallax_data = '';
	
	//Get image width and height
	$background = esc_url($background);
	$pp_background_image_id = pp_get_image_id($background);
	if(!empty($pp_background_image_id))
	{
		$background_image_arr = wp_get_attachment_image_src($pp_background_image_id, 'original');
		
		$background_image = $background_image_arr[0];
		$background_image_width = $background_image_arr[1];
		$background_image_height = $background_image_arr[2];
	}
	else
	{
		$background_image = $background;
		$background_image_width = '';
		$background_image_height = '';
	}

	//Check parallax background

	switch($background_parallax)
	{
		case 'scroll_pos':
		case 'mouse_pos':
		case 'scroll_pos':
		case 'mouse_scroll_pos':
			$parallax_data = ' data-image="'.esc_attr($background_image).'" data-width="'.esc_attr($background_image_width).'" data-height="'.esc_attr($background_image_height).'"';
		break;
	}
	
	if((empty($background_parallax) OR $background_parallax=='none') && !empty($background))
	{
		$return_html.= 'style="background-image:url('.esc_attr($background_image).');background-size:cover;'.urldecode(esc_attr($custom_css)).'" ';
	}
	elseif(!empty($custom_css))
	{
		$return_html.= 'style="'.urldecode(esc_attr($custom_css)).'" ';
	}
	
	$return_html.= $parallax_data;
	
	$return_html.= '><div class="page_content_wrapper">'.do_shortcode(tg_apply_content($content)).'</div>';
	
	$return_html.= '</div>';

	return $return_html;

}

add_shortcode('ppb_text', 'ppb_text_func');


function ppb_divider_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'size' => 'one'
	), $atts));

	$return_html = '<div class="divider '.$size.'">&nbsp;</div>';

	return $return_html;

}

add_shortcode('ppb_divider', 'ppb_divider_func');

function ppb_tour_search_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'size' => 'one',
		'action' => '',
		'custom_css' => '',
	), $atts));
	
    wp_enqueue_script("jquery-ui-core");
    wp_enqueue_script("jquery-ui-datepicker");
    wp_enqueue_script("custom_date", get_template_directory_uri()."/js/custom-date.js", false, THEMEVERSION, "all");

	$return_html = '<div class="one pp_tour_search" ';

	if(!empty($custom_css))
	{
		$return_html.= 'style="'.urldecode($custom_css).'" ';
	}
	
	$return_html.= '><div class="page_content_wrapper">';
	
	//Begin search form
	$return_html.= '<form id="tour_search_form" name="tour_search_form" method="get" action="'.get_the_permalink($action).'">
    <div class="tour_search_wrapper">';
    
    $return_html.= '<div class="one_fourth">
    		<label for="keyword">'.__( 'Destination', THEMEDOMAIN ).'</label>
    		<input id="keyword" name="keyword" type="text" placeholder="'.__( 'City, region or keywords', THEMEDOMAIN ).'"/>
    	</div>';
    	
    $return_html.= '<div class="one_fourth">
    		<label for="start_date">'.__( 'Date', THEMEDOMAIN ).'</label>
    		<div class="start_date_input">
    			<input id="start_date" name="start_date" type="text" placeholder="'.__( 'Departure', THEMEDOMAIN ).'" />
    			<input id="start_date_raw" name="start_date_raw" type="hidden"/>
    			<i class="fa fa-calendar"></i>
    		</div>
    		<div class="end_date_input">
    			<input id="end_date" name="end_date" type="text" placeholder="'.__( 'Arrival', THEMEDOMAIN ).'"/>
    			<input id="end_date_raw" name="end_date_raw" type="hidden"/>
    			<i class="fa fa-calendar"></i>
    		</div>
    	</div>';
    	
    $return_html.= '<div class="one_fourth">
    		<label for="budget">'.__( 'Max Budgets', THEMEDOMAIN ).'</label>
    		<input id="budget" name="budget" type="text" placeholder="'.__( 'USD EX. 100', THEMEDOMAIN ).'"/>
    	</div>';
    	
    $return_html.= '<div class="one_fourth last">
    		<input id="tour_search_btn" type="submit" value="'.__( 'Search', THEMEDOMAIN ).'"/>
    	</div>';
    
    $return_html.= '</div>';
	$return_html.= '</form>';
	$return_html.= '</div>';
	$return_html.= '</div>';

	return $return_html;

}

add_shortcode('ppb_tour_search', 'ppb_tour_search_func');

function ppb_tour_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'size' => 'one',
		'title' => '',
		'items' => 4,
		'tourcat' => '',
		'order' => 'default',
		'custom_css' => '',
		'layout' => 'fullwidth',
	), $atts));
	
	if(!is_numeric($items))
	{
		$items = 4;
	}
	
	$return_html = '<div class="ppb_tour '.$size.' withpadding ';
	
	$columns_class = 'three_cols';
	if($layout=='fullwidth')
	{
		$columns_class.= ' fullwidth';
	}
	$element_class = 'one_third gallery3';
	$tour_h = 'h5';
	
	if(empty($content) && empty($title))
	{
		$return_html.='nopadding ';
	}
	
	$return_html.= '" ';
	
	if(!empty($custom_css))
	{
		$return_html.= 'style="'.urldecode($custom_css).'" ';
	}
	
	$return_html.= '>';
	
	$return_html.='<div class="page_content_wrapper ';
	
	if($layout == 'fullwidth')
	{
		$return_html.='full_width';
	}
	
	$return_html.= '" style="text-align:center">';

	//Display Title
	if(!empty($title))
	{
		$return_html.= '<h2 class="ppb_title">'.$title.'</h2>';
	}
	
	//Display Content
	if(!empty($content) && !empty($title))
	{
		$return_html.= '<div class="page_caption_desc">'.$content.'</div>';
	}
	
	//Display Horizontal Line
	if(empty($content) && !empty($title))
	{
		$return_html.= '<br/>';
	}
	
	$tour_order = 'ASC';
	$tour_order_by = 'menu_order';
	switch($order)
	{
		case 'default':
			$tour_order = 'ASC';
			$tour_order_by = 'menu_order';
		break;
		
		case 'newest':
			$tour_order = 'DESC';
			$tour_order_by = 'post_date';
		break;
		
		case 'oldest':
			$tour_order = 'ASC';
			$tour_order_by = 'post_date';
		break;
		
		case 'title':
			$tour_order = 'ASC';
			$tour_order_by = 'title';
		break;
		
		case 'random':
			$tour_order = 'ASC';
			$tour_order_by = 'rand';
		break;
	}
	
	//Get tour items
	$args = array(
	    'numberposts' => $items,
	    'order' => $tour_order,
	    'orderby' => $tour_order_by,
	    'post_type' => array('tours'),
	    'suppress_filters' => 0,
	);
	
	if(!empty($tourcat))
	{
		$args['tourcats'] = $tourcat;
	}
	$tours_arr = get_posts($args);
	
	if(!empty($tours_arr) && is_array($tours_arr))
	{
		$return_html.= '<div class="portfolio_filter_wrapper '.$columns_class.' shortcode gallery section content clearfix">';
	
		foreach($tours_arr as $key => $tour)
		{
			$image_url = '';
			$tour_ID = $tour->ID;
					
			if(has_post_thumbnail($tour_ID, 'large'))
			{
			    $image_id = get_post_thumbnail_id($tour_ID);
			    $image_url = wp_get_attachment_image_src($image_id, 'full', true);
			    $small_image_url = wp_get_attachment_image_src($image_id, 'gallery_grid', true);
			}
			
			//Get Tour Meta
			$tour_permalink_url = get_permalink($tour_ID);
			$tour_title = $tour->post_title;
			$tour_country= get_post_meta($tour_ID, 'tour_country', true);
			$tour_price= get_post_meta($tour_ID, 'tour_price', true);
			$tour_price_discount= get_post_meta($tour_ID, 'tour_price_discount', true);
			$tour_price_currency= get_post_meta($tour_ID, 'tour_price_currency', true);
			$tour_discount_percentage = 0;
			$tour_price_display = '';
			
			if(!empty($tour_price))
			{
				if(!empty($tour_price_discount))
				{
					if($tour_price_discount < $tour_price)
					{
						$tour_discount_percentage = intval((($tour_price-$tour_price_discount)/$tour_price)*100);
					}
				}
				
				if(empty($tour_price_discount))
				{
					$tour_price_display = $tour_price_currency.pp_number_format($tour_price);
				}
				else
				{
					$tour_price_display = $tour_price_currency.pp_number_format($tour_price_discount);
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
			
			$tour_permalink_url = get_permalink($tour_ID);
			
			//Begin display HTML
			$return_html.= '<div class="element portfolio3filter_wrapper">';
			$return_html.= '<div class="'.$element_class.' filterable gallery_type animated'.($key+1).'">';
			
			if(!empty($image_url[0]))
			{
				$return_html.= '<a href="'.$tour_permalink_url.'">
        		    <img src="'.$small_image_url[0].'" alt="" />
        		</a>';
			}
			if(!empty($tour_discount_percentage))
			{
				$return_html.= '<div class="tour_sale ';
				if($layout=='fullwidth')
				{
					$return_html.= 'fullwidth';;
				}
        			$return_html.= '"><div class="tour_sale_text">'.__( 'Best Deal', THEMEDOMAIN ).'</div>
        			'.$tour_discount_percentage.'% '.__( 'Descuento', THEMEDOMAIN ).'
        		</div>';
			}
			
			$return_html.= '<div class="thumb_content ';
			if($layout=='fullwidth')
			{
			    $return_html.= 'fullwidth';
			}
			$return_html.= ' classic">
	            <div class="thumb_title">';
	            	
			if(!empty($tour_country))
	        {
	            	$return_html.= '<div class="tour_country">
	            		'.$tour_country.'
	            	</div>';
			}
			        $return_html.= '<h3>'.$tour_title.'</h3>
	            </div>
	            <div class="thumb_meta">';
	        if(!empty($tour_days))
	        {
	            	$return_html.= '<div class="tour_days">
	            		'.$tour_days.'
	            	</div>';
	        }
	        if(!empty($tour_price))
	        {
	            	$return_html.= '<div class="tour_price">
	            		'.$tour_price_display.'
	            	</div>';
	        }  
	        $return_html.= '</div>';
	           
	        $tour_excerpt = $tour->post_excerpt;
	        if(!empty($tour_excerpt))
	        {
	            $return_html.= '<br class="clear"/>
	            <div class="tour_excerpt">'.nl2br($tour_excerpt).'</div>';
	        }
			$return_html.= '</div>';
			$return_html.= '</div>';
			$return_html.= '</div>';
		}
		
		$return_html.= '</div>';
	}
	
	$return_html.= '</div></div>';
	
	return $return_html;
}

add_shortcode('ppb_tour', 'ppb_tour_func');


function ppb_tour_grid_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'size' => 'one',
		'title' => '',
		'items' => 4,
		'tourcat' => '',
		'order' => 'default',
		'custom_css' => '',
		'layout' => 'fullwidth',
	), $atts));
	
	if(!is_numeric($items))
	{
		$items = 4;
	}
	
	$return_html = '<div class="ppb_tour '.$size.' withpadding ';
	
	$columns_class = 'three_cols';
	if($layout=='fullwidth')
	{
		$columns_class.= ' fullwidth';
	}
	$element_class = 'one_third gallery3';
	$tour_h = 'h5';
	
	if(empty($content) && empty($title))
	{
		$return_html.='nopadding ';
	}
	
	$return_html.= '" ';
	
	if(!empty($custom_css))
	{
		$return_html.= 'style="'.urldecode($custom_css).'" ';
	}
	
	$return_html.= '>';
	
	$return_html.='<div class="page_content_wrapper ';
	
	if($layout == 'fullwidth')
	{
		$return_html.='full_width';
	}
	
	$return_html.= '" style="text-align:center">';

	//Display Title
	if(!empty($title))
	{
		$return_html.= '<h2 class="ppb_title">'.$title.'</h2>';
	}
	
	//Display Content
	if(!empty($content) && !empty($title))
	{
		$return_html.= '<div class="page_caption_desc">'.$content.'</div>';
	}
	
	//Display Horizontal Line
	if(empty($content) && !empty($title))
	{
		$return_html.= '<br/>';
	}
	
	$tour_order = 'ASC';
	$tour_order_by = 'menu_order';
	switch($order)
	{
		case 'default':
			$tour_order = 'ASC';
			$tour_order_by = 'menu_order';
		break;
		
		case 'newest':
			$tour_order = 'DESC';
			$tour_order_by = 'post_date';
		break;
		
		case 'oldest':
			$tour_order = 'ASC';
			$tour_order_by = 'post_date';
		break;
		
		case 'title':
			$tour_order = 'ASC';
			$tour_order_by = 'title';
		break;
		
		case 'random':
			$tour_order = 'ASC';
			$tour_order_by = 'rand';
		break;
	}
	
	//Get tour items
	$args = array(
	    'numberposts' => $items,
	    'order' => $tour_order,
	    'orderby' => $tour_order_by,
	    'post_type' => array('tours'),
	    'suppress_filters' => 0,
	);
	
	if(!empty($tourcat))
	{
		$args['tourcats'] = $tourcat;
	}
	$tours_arr = get_posts($args);
	
	if(!empty($tours_arr) && is_array($tours_arr))
	{
		$return_html.= '<div class="portfolio_filter_wrapper '.$columns_class.' shortcode gallery section content clearfix">';
	
		foreach($tours_arr as $key => $tour)
		{
			$image_url = '';
			$tour_ID = $tour->ID;
					
			if(has_post_thumbnail($tour_ID, 'large'))
			{
			    $image_id = get_post_thumbnail_id($tour_ID);
			    $image_url = wp_get_attachment_image_src($image_id, 'full', true);
			    $small_image_url = wp_get_attachment_image_src($image_id, 'gallery_grid', true);
			}
			
			//Get Tour Meta
			$tour_permalink_url = get_permalink($tour_ID);
			$tour_title = $tour->post_title;
			$tour_country= get_post_meta($tour_ID, 'tour_country', true);
			$tour_price= get_post_meta($tour_ID, 'tour_price', true);
			$tour_price_discount= get_post_meta($tour_ID, 'tour_price_discount', true);
			$tour_price_currency= get_post_meta($tour_ID, 'tour_price_currency', true);
			$tour_discount_percentage = 0;
			$tour_price_display = '';
			
			if(!empty($tour_price))
			{
				if(!empty($tour_price_discount))
				{
					if($tour_price_discount < $tour_price)
					{
						$tour_discount_percentage = intval((($tour_price-$tour_price_discount)/$tour_price)*100);
					}
				}
				
				if(empty($tour_price_discount))
				{
					$tour_price_display = $tour_price_currency.pp_number_format($tour_price);
				}
				else
				{
					$tour_price_display = $tour_price_currency.pp_number_format($tour_price_discount);
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
			$tour_permalink_url = get_permalink($tour_ID);
			
			//Begin display HTML
			$return_html.= '<div class="element portfolio3filter_wrapper">';
			$return_html.= '<div class="'.$element_class.' filterable gallery_type animated'.($key+1).'">';
			
			if(!empty($image_url[0]))
			{
				$return_html.= '<a href="'.$tour_permalink_url.'">
        		    <img src="'.$small_image_url[0].'" alt="" />
        		</a>';
			}
			if(!empty($tour_discount_percentage))
			{
				$return_html.= '<div class="tour_sale ';
				if($layout=='fullwidth')
				{
					$return_html.= 'fullwidth';;
				}
        			$return_html.= '"><div class="tour_sale_text">'.__( 'Best Deal', THEMEDOMAIN ).'</div>
        			'.$tour_discount_percentage.'% '.__( 'Descuento', THEMEDOMAIN ).'
        		</div>';
			}
			
			$return_html.= '<div class="thumb_content ';
			if($layout=='fullwidth')
			{
			    $return_html.= 'fullwidth';
			}
			$return_html.= ' "><div class="thumb_title">';
	            	
			if(!empty($tour_country))
	        {
	            	$return_html.= '<div class="tour_country">
	            		'.$tour_country.'
	            	</div>';
			}
			        $return_html.= '<h3>'.$tour_title.'</h3>
	            </div>
	            <div class="thumb_meta">';
	        if(!empty($tour_days))
	        {
	            	$return_html.= '<div class="tour_days">
	            		'.$tour_days.'
	            	</div>';
	        }
	        if(!empty($tour_price_display))
	        {
	            	$return_html.= '<div class="tour_price">
	            		'.$tour_price_display.'
	            	</div>';
	        }
	            $return_html.= '</div>';
	            
			$return_html.= '</div>';
			$return_html.= '</div>';
			$return_html.= '</div>';
		}
		
		$return_html.= '</div>';
	}
	
	$return_html.= '</div></div>';
	
	return $return_html;
}

add_shortcode('ppb_tour_grid', 'ppb_tour_grid_func');


function ppb_gallery_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'size' => 'one',
		'title' => '',
		'gallery' => '',
		'background' => '',
		'custom_css' => '',
		'layout' => 'fullwidth',
	), $atts));
	
	$return_html = '<div class="'.$size.' ppb_gallery withpadding ';
	
	if(!empty($background))
	{
		$return_html.= 'withbg';
	}
	
	$columns_class = 'three_cols';
	if($layout == 'fullwidth')
	{
		$columns_class.= ' fullwidth';
	}
	$element_class = 'one_third gallery3';
	
	$return_html.= '" ';
	
	if(!empty($custom_css))
	{
		$return_html.= 'style="'.urldecode($custom_css).' ';
	}
	
	if(!empty($background))
	{
		$background = esc_url($background);
		if(!empty($custom_css))
		{
			$return_html.= 'background-image: url('.$background.');background-attachment: fixed;background-position: center top;background-repeat: no-repeat;background-size: cover;" ';
		}
		else
		{
			$return_html.= 'style="background-image: url('.$background.');background-attachment: fixed;background-position: center top;background-repeat: no-repeat;background-size: cover;" ';
		}
		
		$return_html.= 'data-type="background" data-speed="10"';
	}
	else
	{
		$return_html.= '"';
	}
	
	$return_html.= '>';
	
	$return_html.='<div class="page_content_wrapper ';
	
	if($layout == 'fullwidth')
	{
		$return_html.='full_width';
	}
	
	$return_html.= '" style="text-align:center">';

	//Display Title
	if(!empty($title))
	{
		$return_html.= '<h2 class="ppb_title">'.$title.'</h2>';
	}
	
	//Display Content
	if(!empty($content))
	{
		$return_html.= '<div class="page_caption_desc">'.$content.'</div>';
	}
	
	//Display Horizontal Line
	if(empty($content) && !empty($title))
	{
		$return_html.= '<br/><br/>';
	}
	
	//Get gallery images
	$all_photo_arr = get_post_meta($gallery, 'wpsimplegallery_gallery', true);
	
	//Get global gallery sorting
	$all_photo_arr = pp_resort_gallery_img($all_photo_arr);

	if(!empty($all_photo_arr) && is_array($all_photo_arr))
	{
		$return_html.= '<div class="'.$columns_class.' portfolio_filter_wrapper gallery section content clearfix">';
		
		foreach($all_photo_arr as $key => $photo_id)
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
		    
		    //Get image meta data
		    $image_title = get_the_title($photo_id);
		    $image_desc = get_post_field('post_content', $photo_id);
		    $image_caption = get_post_field('post_excerpt', $photo_id);
		    
		    $return_html.= '<div class="element portfolio3filter_wrapper">';
			$return_html.= '<div class="'.$element_class.' filterable gallery_type animated'.($key+1).' '.$last_class.'">';
			
			if(!empty($small_image_url[0]))
			{
	    		$pp_lightbox_enable_title = get_option('pp_lightbox_enable_title');
	    		$pp_social_sharing = get_option('pp_social_sharing');

				$return_html.= '<a ';
				if(!empty($pp_lightbox_enable_title)) 
				{ 
					$return_html.= 'title="'.$image_caption.'" ';
				}
				
				$return_html.= 'class="fancy-gallery" href="'.$image_url[0].'">';
    			$return_html.= '<img src="'.$small_image_url[0].'" alt="" class=""/>';
    			
    			if(!empty($pp_lightbox_enable_title) && !empty($image_caption)) 
				{
	    			$return_html.= '<div class="thumb_content">
						    	<h3>'.$image_caption.'</h3>
						    </div>
			    		';
		    	}
			}		
			$return_html.= '</a></div></div>';
		}
		
		$return_html.= '</div>';
	}
	
	$return_html.= '</div></div>';
	
	return $return_html;
}

add_shortcode('ppb_gallery', 'ppb_gallery_func');


function ppb_gallery_slider_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'size' => 'one',
		'title' => '',
		'gallery' => '',
		'custom_css' => '',
		'layout' => 'fullwidth',
	), $atts));
	
	$return_html = '<div class="'.$size.' ppb_gallery ';
		
	$columns_class = 'three_cols';
	if($layout == 'fullwidth')
	{
		$columns_class.= ' fullwidth';
	}
	$element_class = 'one_third gallery3';
	
	$return_html.= '" ';
	
	if(!empty($custom_css))
	{
		$return_html.= 'style="'.urldecode($custom_css).' ';
	}
	
	$return_html.= '">';
	
	$return_html.='<div class="page_content_wrapper ';
	
	if($layout == 'fullwidth')
	{
		$return_html.='full_width';
	}
	
	$return_html.= '" style="text-align:center">';

	//Display Title
	if(!empty($title))
	{
		$return_html.= '<h2 class="ppb_title">'.$title.'</h2>';
	}
	
	//Display Content
	if(!empty($content))
	{
		$return_html.= '<div class="page_caption_desc">'.$content.'</div>';
	}
	
	//Display Horizontal Line
	if(empty($content) && !empty($title))
	{
		$return_html.= '<br/><br/>';
	}
	
	$return_html.= do_shortcode('[tg_gallery_slider gallery_id="'.$gallery.'" size="full"]');
	
	$return_html.= '</div></div>';
	
	return $return_html;
}

add_shortcode('ppb_gallery_slider', 'ppb_gallery_slider_func');


function ppb_blog_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'size' => 'one',
		'title' => '',
		'category' => '',
		'items' => '',
		'background' => '',
		'background_parallax' => 'none',
		'custom_css' => '',
	), $atts));
	
	$return_html = '<div class="'.$size.' withsmallpadding ';
	
	if(!empty($background))
	{
		$return_html.= 'withbg ';
	}
	
	if(!empty($background_parallax))
	{
		$return_html.= 'parallax';
	}
	$return_html.= '" ';
	
	$parallax_data = '';
	
	//Get image width and height
	$background = esc_url($background);
	$pp_background_image_id = pp_get_image_id($background);
	$background = esc_url($background);
	if(!empty($pp_background_image_id))
	{
		$background_image_arr = wp_get_attachment_image_src($pp_background_image_id, 'original');
		
		$background_image = $background_image_arr[0];
		$background_image_width = $background_image_arr[1];
		$background_image_height = $background_image_arr[2];
	}
	else
	{
		$background_image = $background;
		$background_image_width = '';
		$background_image_height = '';
	}

	//Check parallax background
	switch($background_parallax)
	{
		case 'scroll_pos':
		case 'mouse_pos':
		case 'scroll_pos':
		case 'mouse_scroll_pos':
			$parallax_data = ' data-image="'.esc_attr($background_image).'" data-width="'.esc_attr($background_image_width).'" data-height="'.esc_attr($background_image_height).'"';
		break;
	}
	
	if((empty($background_parallax) OR $background_parallax=='none') && !empty($background))
	{
		$return_html.= 'style="background-image:url('.$background_image.');background-size:cover;';
		
		if(!empty($custom_css))
		{
			$return_html.= urldecode($custom_css);
		}
		
		$return_html.= '" ';
	}
	else
	{
		if(!empty($custom_css))
		{
			$return_html.= 'style="'.urldecode($custom_css).'" ';
		}
	}
	
	$return_html.= $parallax_data;
	
	$return_html.= '>';
	
	$return_html.='<div class="page_content_wrapper fullwidth" style="text-align:center">';
	
	if(!is_numeric($items))
	{
		$items = 3;
	}
	
	//Display Title
	if(!empty($title))
	{
		$return_html.= '<h2 class="ppb_title">'.$title.'</h2>';
	}
	
	//Display Content
	if(!empty($content))
	{
		$return_html.= '<div class="page_caption_desc">'.$content.'</div>';
	}
	
	//Display Horizontal Line
	if(empty($content))
	{
		$return_html.= '<br/><br/>';
	}
	
	//Get blog posts
	$args = array(
	    'numberposts' => $items,
	    'order' => 'DESC',
	    'orderby' => 'post_date',
	    'post_type' => array('post'),
	    'suppress_filters' => 0,
	);

	if(!empty($category))
	{
		$args['category'] = $category;
	}
	$posts_arr = get_posts($args);
	
	if(!empty($posts_arr) && is_array($posts_arr))
	{
		$return_html.= '<div id="blog_grid_wrapper" class="sidebar_content full_width ppb_blog_posts" style="text-align:left">';
	
		foreach($posts_arr as $key => $ppb_post)
		{
			$animate_layer = $key+7;
			$image_thumb = '';
										
			if(has_post_thumbnail($ppb_post->ID, 'large'))
			{
			    $image_id = get_post_thumbnail_id($ppb_post->ID);
			    $image_thumb = wp_get_attachment_image_src($image_id, 'large', true);
			}
			
			$return_html.= '<div id="post-'.$ppb_post->ID.'" class="post type-post hentry status-publish">';
			$return_html.= '<div class="post_wrapper grid_layout" ';
			
			if(isset($image_thumb[0]) && !empty($image_thumb[0]))
			{
				$return_html.= 'style="background-image:url(\''.$image_thumb[0].'\');"';
			}
			
			$return_html.= '>';
		    $return_html.= '<div class="parallax_overlay_header"></div>';
		    $return_html.= '<div class="grid_wrapper">';
		    $return_html.= '<div class="post_header grid">';
		    $return_html.= '<a href="'.get_permalink($ppb_post->ID).'" title="'.get_the_title($ppb_post->ID).'">';
			$return_html.= '<h6>'.get_the_title($ppb_post->ID).'</h6></a>
			    <div class="post_detail">
			        '.__( 'On', THEMEDOMAIN ).'&nbsp;'.get_the_time(THEMEDATEFORMAT, $ppb_post->ID).'
			    </div>
			</div>';
		    $return_html.= '
	    </div>
	</div>
</div>';
		}
		
		$return_html.= '</div>';
	}
	
	$return_html.= '<br class="clear"/></div></div>';
	
	return $return_html;
}

add_shortcode('ppb_blog', 'ppb_blog_func');


function ppb_service_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'size' => 'one',
		'title' => '',
		'items' => 3,
		'category' => '',
		'order' => 'default',
		'columns' => '3',
		'background' => '',
		'background_parallax' => 'none',
		'custom_css' => '',
	), $atts));

	if(!is_numeric($items))
	{
		$items = 4;
	}
	
	$return_html = '<div class="'.$size.' withsmallpadding ';
	
	if(!empty($background))
	{
		$return_html.= 'withbg ';
	}
	
	if(!empty($background_parallax) && $background_parallax!='none')
	{
		$return_html.= 'parallax';
	}
	$return_html.= ' "';
	
	$parallax_data = '';
	
	//Get image width and height
	$background = esc_url($background);
	$pp_background_image_id = pp_get_image_id($background);
	if(!empty($pp_background_image_id))
	{
		$background_image_arr = wp_get_attachment_image_src($pp_background_image_id, 'original');
		
		$background_image = $background_image_arr[0];
		$background_image_width = $background_image_arr[1];
		$background_image_height = $background_image_arr[2];
	}
	else
	{
		$background_image = $background;
		$background_image_width = '';
		$background_image_height = '';
	}

	//Check parallax background

	switch($background_parallax)
	{
		case 'scroll_pos':
		case 'mouse_pos':
		case 'scroll_pos':
		case 'mouse_scroll_pos':
			$parallax_data = ' data-image="'.esc_attr($background_image).'" data-width="'.esc_attr($background_image_width).'" data-height="'.esc_attr($background_image_height).'"';
		break;
	}
	
	if((empty($background_parallax) OR $background_parallax=='none') && !empty($background))
	{
		$return_html.= 'style="background-image:url('.$background_image.');background-size:cover;'.urldecode($custom_css).'" ';
	}
	elseif(!empty($custom_css))
	{
		$return_html.= 'style="'.urldecode($custom_css).'" ';
	}
	
	$return_html.= $parallax_data;
	$return_html.= '>';
	
	$return_html.='<div class="page_content_wrapper" style="text-align:center">';
	
	//Display Title
	if(!empty($title))
	{
		$return_html.= '<h2 class="ppb_title">'.$title.'</h2>';
	}
	
	$return_html.= '<div style="height:20px"></div><br/>';
	
	$service_order = 'ASC';
	$service_order_by = 'menu_order';
	switch($order)
	{
		case 'default':
			$service_order = 'ASC';
			$service_order_by = 'menu_order';
		break;
		
		case 'newest':
			$service_order = 'DESC';
			$service_order_by = 'post_date';
		break;
		
		case 'oldest':
			$service_order = 'ASC';
			$service_order_by = 'post_date';
		break;
		
		case 'title':
			$service_order = 'ASC';
			$service_order_by = 'title';
		break;
		
		case 'random':
			$service_order = 'ASC';
			$service_order_by = 'rand';
		break;
	}
	
	//Get service items
	$args = array(
	    'numberposts' => $items,
	    'order' => $service_order,
	    'orderby' => $service_order_by,
	    'post_type' => array('services'),
	    'suppress_filters' => 0,
	);
	
	if(!empty($category))
	{
		$args['servicecats'] = $category;
	}
	$services_arr = get_posts($args);
	
	//Check display columns
	$count_column = 3;
	$columns_class = 'one_third';
	$service_h = 'h3';
	
	$count_column = 3;
	$columns_class = 'one_third';
	$service_h = 'h3';
	
	if(!empty($content))
	{
		$return_html.= '<div class="one_third"  style="text-align:left">';
		$content = preg_replace('/^(?:<br\s*\/?>\s*)+/', '', $content);
		$return_html.= $content;
		$return_html.= '</div>';
	}

	if(!empty($services_arr) && is_array($services_arr))
	{
		if(!empty($content))
		{
			$return_html.= '<div class="two_third last">';
		}
	
		$return_html.= '<div class="service_content_wrapper ';
		if(isset($image_url[0]) && !empty($image_url[0]))
		{
		    $return_html.= 'image';
		}
		$return_html.= '">';
		$last_class = '';
	
		foreach($services_arr as $key => $service)
		{
			$image_url = '';
			$service_ID = $service->ID;
			    	
			//check if use font awesome
			$service_icon_code ='';
			$service_font_awesome = get_post_meta($service_ID, 'service_font_awesome', true);
			    	
			if(!empty($service_font_awesome))
			{
			    $service_icon_code = get_post_meta($service_ID, 'service_font_awesome_code', true);
			}
			else
			{
			    if(has_post_thumbnail($service_ID, 'large'))
			    {
			        $image_id = get_post_thumbnail_id($service_ID);
			        $image_url = wp_get_attachment_image_src($image_id, 'thumbnail', true);
			        $service_icon_code = '<img src="'.$image_url[0].'" alt="" />';
			    }
			}
		
			if(($key+1)%$count_column==0)
			{
				$last_class = 'last';
			}
			else
			{
				$last_class = '';
			}
			
			$return_html.= '<div class="'.$columns_class.' '.$last_class.'">';
			$return_html.= '<div class="service_wrapper">';
			
			if(!empty($service_icon_code))
			{
				$return_html.= '<div class="service_icon">'.$service_icon_code.'</div>';
			}
			
			$return_html.= '<div class="service_title">';
			$return_html.= '<'.$service_h.'>'.$service->post_title.'</'.$service_h.'>';
			$return_html.= '<div class="service_content">'.$service->post_content.'</div>';
			$return_html.= '</div>';
			
			$return_html.= '</div>';
			$return_html.= '</div>';
			
			if(($key+1)%$columns==0)
			{
				$return_html.= '<br class="clear"/><br/>';
			}
		}
		
		$return_html.= '</div>';
		
		if(!empty($content))
		{
			$return_html.= '</div>';
		}
	}
	
	$return_html.= '<br class="clear"/></div></div>';
	
	return $return_html;
}

add_shortcode('ppb_service', 'ppb_service_func');


function ppb_transparent_video_bg_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'size' => 'one',
		'title' => '',
		'height' => '300',
		'description' => '',
		'mp4_video_url' => '',
		'webm_video_url' => '',
		'preview_img' => '',
	), $atts));
	
	if(!is_numeric($height))
	{
		$height = 300;
	}
	
	$return_html = '';
	$return_html.= '<div class="'.$size.' ppb_transparent_video_bg" style="position:relative;height:'.$height.'px;max-height:'.$height.'px;" >';
	$return_html.= '<div class="ppb_video_bg_mask"></div>';
	
	if(!empty($title))
	{
		$return_html.= '<div class="post_title entry_post">';
		
		if(!empty($title))
		{
			$return_html.= '<h3>'.$title.'</h3>';
		}
		
		if(!empty($description))
		{
			$return_html.= '<div class="content">'.urldecode($description).'</div>';
		}
		
		$return_html.= '</div>';
	}
	
	$return_html.= '<div style="position:relative;width:100%;height:100%;overflow:hidden">';
	
	if(!empty($mp4_video_url) OR !empty($webm_video_url))
	{
		//Generate unique ID
		$wrapper_id = 'ppb_video_'.uniqid();
		
		$return_html.= '<video ';
		
		if(!empty($preview_img))
		{
			$return_html.= 'poster="'.$preview_img.'"';
		}
		
		$return_html.= 'id="'.$wrapper_id.'" loop="true" autoplay="true" muted="muted" controls="controls">';
		
		if(!empty($mp4_video_url))
		{
			$return_html.= '<source type="video/mp4" src="'.$mp4_video_url.'" />';
		}
		
		if(!empty($webm_video_url))
		{
			$return_html.= '<source type="video/webm" src="'.$webm_video_url.'" />';
		}
		
		$return_html.= '</video>';
		
		wp_enqueue_style("mediaelementplayer", get_template_directory_uri()."/js/mediaelement/mediaelementplayer.css", false, THEMEVERSION, "all");
		wp_enqueue_script("mediaelement-and-player.min", get_template_directory_uri()."/js/mediaelement/mediaelement-and-player.min.js", false, THEMEVERSION);
		wp_enqueue_script("script-ppb-video-bg".$wrapper_id, get_stylesheet_directory_uri()."/templates/script-ppb-video-bg.php?video_id=".$wrapper_id."&height=".$height, false, THEMEVERSION, true);
	}

	$return_html.= '</div>';

	$return_html.= '</div>';

	return $return_html;

}

add_shortcode('ppb_transparent_video_bg', 'ppb_transparent_video_bg_func');


function ppb_fullwidth_button_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'size' => 'one',
		'title' => '',
		'link_url' => '',
	), $atts));
	
	$return_html = '<div class="'.$size.'"><a href="'.esc_url($link_url).'" class="button fullwidth ppb"><i class="fa fa-link"></i>'.htmlentities($title).'</a></div>';

	return $return_html;

}

add_shortcode('ppb_fullwidth_button', 'ppb_fullwidth_button_func');


function ppb_client_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'size' => 'one',
		'title' => '',
		'description' => '',
		'items' => 5,
		'cat' => '',
		'custom_css' => '',
	), $atts));
	
	if(!is_numeric($items))
	{
		$items = 1;
	}

	//Get clients
	$args = array(
	    'numberposts' => $items,
	    'order' => 'ASC',
	    'orderby' => 'menu_order',
	    'post_type' => array('clients'),
	    'suppress_filters' => 0,
	);
	
	if(!empty($cat))
	{
		$args['clientcats'] = $cat;
	}
	$clients_arr = get_posts($args);
	
	$return_html = '';

	$return_html.= '<input type="hidden" id="post_carousel_column" name="post_carousel_column" value="4"/>';
	$return_html = '<div class="'.$size.' withpadding ppb_carousel" ';
	if(!empty($custom_css))
	{
		$return_html.= 'style="'.urldecode($custom_css).'" ';
	}
	$return_html.= '>';

	if(!empty($clients_arr))
	{	
		//Enqueue CSS and javascript
		wp_enqueue_script("flexslider-js", get_template_directory_uri()."/js/flexslider/jquery.flexslider-min.js", false, THEMEVERSION, true);
		wp_enqueue_script("script-ppb-client", get_stylesheet_directory_uri()."/templates/script-ppb-client.php", false, THEMEVERSION, true);
		
		$return_html.='<div class="page_content_wrapper" style="text-align:center">';
	
		//Display Title
		if(!empty($title))
		{
			$return_html.= '<h2 class="ppb_title">'.$title.'</h2>';
		}
		
		//Display Content
		if(!empty($content))
		{
			$return_html.= '<div class="page_caption_desc">'.$content.'</div>';
		}
		
		$return_html.= '<br class="clear"/><div class="flexslider post_carousel post_fullwidth post_type_gallery"><ul class="slides">';
		
		foreach($clients_arr as $key => $client)
		{
			$return_html.= '<li>';
			
			if(has_post_thumbnail($client->ID, 'original'))
			{
			    $image_id = get_post_thumbnail_id($client->ID);
			    $image_url = wp_get_attachment_image_src($image_id, 'original', true);
			}
			
			if(isset($image_url[0]) && !empty($image_url[0]))
	    	{
	    		$return_html.= '<div class="carousel_img">';
	    		
	    		$client_website_url = get_post_meta($client->ID, 'client_website_url', true);
	    		if(empty($client_website_url))
	    		{
		    		$client_website_url = '#';
	    		}
	    		
	    	    $return_html.= '<a href="'.$client_website_url.'" ';
	    	    if(!empty($client->post_content))
	    	    {
		    	    $return_html.= 'class="client_content tooltip" title="'.$client->post_content.'"';
	    	    }
	    	    $return_html.= '><img class="client_logo" src="'.$image_url[0].'" alt=""/></a>';
	    	    $return_html.= '</div>';
	    	}
			
			$return_html.= '</li>';
		}
		
		$return_html.= '</ul></div></div>';
	}
	else
	{
		$return_html.= 'Empty client Please make sure you have created it.';
	}

	$return_html.= '</div>';

	return $return_html;

}

add_shortcode('ppb_client', 'ppb_client_func');


function ppb_team_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'size' => 'one',
		'columns' => 3,
		'title' => '',
		'items' => 4,
		'cat' => '',
		'order' => 'default',
		'background' => '',
		'background_parallax' => 'none',
		'custom_css' => '',
	), $atts));
	
	if(!is_numeric($items))
	{
		$items = 4;
	}
	
	$return_html = '<div class="'.$size.' withpadding ppb_team ';
	
	if(!empty($background))
	{
		$return_html.= 'withbg ';
	}
	
	if(!empty($background_parallax))
	{
		$return_html.= 'parallax';
	}
	
	$return_html.= '" ';
	
	$parallax_data = '';
	
	//Get image width and height
	$background = esc_url($background);
	$pp_background_image_id = pp_get_image_id($background);
	if(!empty($pp_background_image_id))
	{
		$background_image_arr = wp_get_attachment_image_src($pp_background_image_id, 'original');
		
		$background_image = $background_image_arr[0];
		$background_image_width = $background_image_arr[1];
		$background_image_height = $background_image_arr[2];
	}
	else
	{
		$background_image = $background;
		$background_image_width = '';
		$background_image_height = '';
	}

	//Check parallax background
	switch($background_parallax)
	{
		case 'scroll_pos':
		case 'mouse_pos':
		case 'scroll_pos':
		case 'mouse_scroll_pos':
			$parallax_data = ' data-image="'.esc_attr($background_image).'" data-width="'.esc_attr($background_image_width).'" data-height="'.esc_attr($background_image_height).'"';
		break;
	}
	
	if((empty($background_parallax) OR $background_parallax=='none') && !empty($background))
	{
		$return_html.= 'style="background-image:url('.$background_image.');background-size:cover;" ';
	}
	
	if(!empty($custom_css))
	{
		$return_html.= 'style="'.urldecode($custom_css).'" ';
	}
	
	$return_html.= $parallax_data;
	
	$return_html.= '>';
	
	$return_html.='<div class="page_content_wrapper" style="text-align:center">';
	
	//Display Title
	if(!empty($title))
	{
		$return_html.= '<h2 class="ppb_title">'.stripcslashes($title).'</h2>';
	}
	
	//Display Content
	if(!empty($content))
	{
		$return_html.= '<div class="page_caption_desc">'.$content.'</div>';
	}
	
	//Display Horizontal Line
	if(!empty($title) OR !empty($content))
	{
		$return_html.= '<br/><br/>';
	}
	
	$tour_order = 'ASC';
	$tour_order_by = 'menu_order';
	switch($order)
	{
		case 'default':
			$tour_order = 'ASC';
			$tour_order_by = 'menu_order';
		break;
		
		case 'newest':
			$tour_order = 'DESC';
			$tour_order_by = 'post_date';
		break;
		
		case 'oldest':
			$tour_order = 'ASC';
			$tour_order_by = 'post_date';
		break;
		
		case 'title':
			$tour_order = 'ASC';
			$tour_order_by = 'title';
		break;
		
		case 'random':
			$tour_order = 'ASC';
			$tour_order_by = 'rand';
		break;
	}
	
	//Check display columns
	$count_column = 3;
	$columns_class = 'one_third';
	
	switch($columns)
	{	
		case 2:
			$count_column = 2;
			$columns_class = 'one_half';
		break;
		
		case 3:
		default:
			$count_column = 3;
			$columns_class = 'one_third';
		break;
		
		case 4:
			$count_column = 4;
			$columns_class = 'one_fourth';
		break;
	}
	
	//Get portfolio items
	$args = array(
	    'numberposts' => $items,
	    'order' => $tour_order,
	    'orderby' => $tour_order_by,
	    'post_type' => array('team'),
	    'suppress_filters' => 0,
	);
	
	if(!empty($cat))
	{
		$args['teamcats'] = $cat;
	}
	$team_arr = get_posts($args);
	
	if(!empty($team_arr) && is_array($team_arr))
	{
		$return_html.= '<div class="team_wrapper">';
	
		foreach($team_arr as $key => $member)
		{
			$image_url = '';
			$member_ID = $member->ID;
					
			if(has_post_thumbnail($member_ID, 'team_member'))
			{
			    $image_id = get_post_thumbnail_id($member_ID);
			    $small_image_url = wp_get_attachment_image_src($image_id, 'team_member', true);
			}
			
			$last_class = '';
			if(($key+1)%$count_column==0)
			{
				$last_class = 'last';
			}
			
			//Begin display HTML
			$return_html.= '<div class="'.$columns_class.' animated'.($key+1).' '.$last_class.'">';
			
			if(!empty($small_image_url[0]))
			{
				$return_html.= '<div class="post_img static team animate ';
				
				$member_facebook = get_post_meta($member_ID, 'member_facebook', true);
			    $member_twitter = get_post_meta($member_ID, 'member_twitter', true);
			    $member_google = get_post_meta($member_ID, 'member_google', true);
			    $member_linkedin = get_post_meta($member_ID, 'member_linkedin', true);
				
				if(empty($member_facebook) && empty($member_twitter) && empty($member_google) && empty($member_linkedin))
				{
					$return_html.= 'static';
				}
				
				$return_html.='" style="margin-bottom:10px"><img class="team_pic" src="'.$small_image_url[0].'" alt=""/>';
				$return_html.= '</div>';
			    
			}
			
			$team_position = get_post_meta($member_ID, 'team_position', true);
			
			//Display portfolio detail
			$return_html.= '<br class="clear"/><div id="portfolio_desc_'.$member_ID.'" class="portfolio_desc team shortcode '.$last_class.'">';
            $return_html.= '<h5>'.$member->post_title.'</h5>';
            if(!empty($team_position))
            {
            	$return_html.= '<span class="portfolio_excerpt">'.$team_position.'</span>';
            }
            if(!empty($member->post_content))
            {
            	$return_html.= '<p>'.$member->post_content.'</p>';
            }
            
            if(!empty($member_facebook) OR !empty($member_twitter) OR !empty($member_google) OR !empty($member_linkedin))
			{
			    $return_html.= '<ul class="social_wrapper team">';
			    
			    if(!empty($member_twitter))
			    {
			        $return_html.= '<li><a title="'.$member->post_title.' on Twitter" target="_blank" class="tooltip" href="//twitter.com/'.$member_twitter.'"><i class="fa fa-twitter"></i></a></li>';
			    }
	 
			    if(!empty($member_facebook))
			    {
			        $return_html.= '<li><a title="'.$member->post_title.' on Facebook" target="_blank" class="tooltip" href="//facebook.com/'.$member_facebook.'"><i class="fa fa-facebook"></i></a></li>';
			    }
			    
			    if(!empty($member_google))
			    {
			        $return_html.= '<li><a title="'.$member->post_title.' on Google+" target="_blank" class="tooltip" href="'.$member_google.'"><i class="fa fa-google-plus"></i></a></li>';
			    }
			        
			    if(!empty($member_linkedin))
			    {
			        $return_html.= '<li><a title="'.$member->post_title.' on Linkedin" target="_blank" class="tooltip" href="'.$member_linkedin.'"><i class="fa fa-linkedin"></i></a></li>';
			    }
			    
			    $return_html.= '</ul>';
			}
            
			$return_html.= '</div>';
			$return_html.= '</div>';
			
			if(($key+1)%$count_column==0)
			{
				$return_html.= '<br class="clear"/>';
			}
		}
		
		$return_html.= '</div>';
	}
	
	$return_html.= '</div></div>';
	
	return $return_html;
}

add_shortcode('ppb_team', 'ppb_team_func');


function ppb_promo_box_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'title' => '',
		'button_text' => '',
		'button_url' => '',
		'background_color' => '',
	), $atts));
	
	$return_html = '<div class="one skinbg" ';
	
	if(!empty($background_color))
	{
		$return_html.= 'style="background:'.$background_color.'"';
	}
	
	$return_html.= '>';
	$return_html.='<div class="page_content_wrapper promo_box_wrapper">';
	$return_html.= do_shortcode('[tg_promo_box title="'.$title.'" button_text="'.urldecode($button_text).'" button_url="'.esc_url($button_url).'" button_style="button transparent"]'.$content.'[/tg_promo_box]');
	$return_html.='</div></div>';
	
	return $return_html;
}

add_shortcode('ppb_promo_box', 'ppb_promo_box_func');


function ppb_testimonial_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'title' => '',
		'items' => '',
		'cat' => '',
		'background' => '',
		'background_parallax' => 'none',
		'custom_css' => '',
	), $atts));
	
	$return_html = '<div class="one withsmallpadding ';
	
	if(!empty($background))
	{
		$return_html.= 'withbg ';
	}
	
	if(!empty($background_parallax))
	{
		$return_html.= 'parallax';
	}
	
	$return_html.= '" ';
	
	$parallax_data = '';
	
	//Get image width and height
	$background = esc_url($background);
	$pp_background_image_id = pp_get_image_id($background);
	if(!empty($pp_background_image_id))
	{
		$background_image_arr = wp_get_attachment_image_src($pp_background_image_id, 'original');
		
		$background_image = $background_image_arr[0];
		$background_image_width = $background_image_arr[1];
		$background_image_height = $background_image_arr[2];
	}
	else
	{
		$background_image = $background;
		$background_image_width = '';
		$background_image_height = '';
	}

	//Check parallax background
	switch($background_parallax)
	{
		case 'scroll_pos':
		case 'mouse_pos':
		case 'scroll_pos':
		case 'mouse_scroll_pos':
			$parallax_data = ' data-image="'.esc_attr($background_image).'" data-width="'.esc_attr($background_image_width).'" data-height="'.esc_attr($background_image_height).'"';
		break;
	}
	
	if((empty($background_parallax) OR $background_parallax=='none') && !empty($background))
	{
		$return_html.= 'style="background-image:url('.$background_image.');background-size:cover;" ';
	}
	
	if(!empty($custom_css))
	{
		$return_html.= 'style="'.urldecode($custom_css).'" ';
	}
	
	$return_html.= $parallax_data;
	
	$return_html.= '>';
	
	$return_html.= '<div class="page_content_wrapper" style="text-align:center">';
	
	//Display Title
	if(!empty($title))
	{
		$return_html.= '<h2 class="ppb_title">'.$title.'</h2>';
	}
	
	//Display Content
	if(!empty($content))
	{
		$return_html.= '<div class="page_caption_desc">'.$content.'</div>';
	}
	
	//Display Horizontal Line
	if(empty($content))
	{
		$return_html.= '<br/>';
	}
	
	$return_html.= do_shortcode('[tg_testimonial_slider cat="'.$cat.'" items="'.$items.'"]');
	$return_html.= '</div>';
	
	$return_html.= '</div>';

	return $return_html;
}

add_shortcode('ppb_testimonial', 'ppb_testimonial_func');


function ppb_contact_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'title' => '',
		'address' => '',
		'background' => '',
		'background_parallax' => 'none',
		'custom_css' => '',
	), $atts));
	
	$return_html = '<div class="one withsmallpadding ';
	
	if(!empty($background))
	{
		$return_html.= 'withbg ';
	}
	
	if(!empty($background_parallax))
	{
		$return_html.= 'parallax';
	}
	$return_html.= '" ';
	
	$parallax_data = '';
	
	//Get image width and height
	$background = esc_url($background);
	$pp_background_image_id = pp_get_image_id($background);
	if(!empty($pp_background_image_id))
	{
		$background_image_arr = wp_get_attachment_image_src($pp_background_image_id, 'original');
		
		$background_image = $background_image_arr[0];
		$background_image_width = $background_image_arr[1];
		$background_image_height = $background_image_arr[2];
	}
	else
	{
		$background_image = $background;
		$background_image_width = '';
		$background_image_height = '';
	}

	//Check parallax background
	switch($background_parallax)
	{
		case 'scroll_pos':
		case 'mouse_pos':
		case 'scroll_pos':
		case 'mouse_scroll_pos':
			$parallax_data = ' data-image="'.esc_attr($background_image).'" data-width="'.esc_attr($background_image_width).'" data-height="'.esc_attr($background_image_height).'"';
		break;
	}
	
	if((empty($background_parallax) OR $background_parallax=='none') && !empty($background))
	{
		$return_html.= 'style="background-image:url('.$background_image.');background-size:cover;" ';
	}
	
	if(!empty($custom_css))
	{
		$return_html.= 'style="'.urldecode($custom_css).'" ';
	}
	
	$return_html.= $parallax_data;
	
	$return_html.= '>';
	
	$return_html.= '<div class="page_content_wrapper" style="text-align:center">';
	
	//Display Title
	if(!empty($title))
	{
		$return_html.= '<h2 class="ppb_title">'.$title.'</h2>';
	}
	
	//Display Content
	if(!empty($content))
	{
		$return_html.= '<div class="page_caption_desc">'.$content.'</div>';
	}
	
	//Display Horizontal Line
	if(empty($content))
	{
		$return_html.= '<br/><br/>';
	}
	
	$return_html.= '<div style="text-align:left">';
	
	//Displat address
	$return_html.= '<div class="one_half">';
	$return_html.= do_shortcode(html_entity_decode($address));
	$return_html.= '</div>';
	
	//Display contact form
	$return_html.= '<div class="one_half last">';

	//Get contact form random ID
	$custom_id = time().rand();
    $pp_contact_form = unserialize(get_option('pp_contact_form_sort_data'));
    wp_enqueue_script("jquery.validate", get_template_directory_uri()."/js/jquery.validate.js", false, THEMEVERSION, true);
    
    wp_register_script("script-contact-form", get_template_directory_uri()."/templates/script-contact-form.php?form=".$custom_id.'&amp;skin=dark', false, THEMEVERSION, true);
	$params = array(
	  'ajaxurl' => admin_url('admin-ajax.php'),
	  'ajax_nonce' => wp_create_nonce('tgajax-post-contact-nonce'),
	);
	wp_localize_script( 'script-contact-form', 'tgAjax', $params );
	wp_enqueue_script("script-contact-form", get_template_directory_uri()."/templates/script-contact-form.php?form=".$custom_id.'&amp;skin=dark', false, THEMEVERSION, true);

    $return_html.= '<div id="reponse_msg_'.$custom_id.'" class="contact_form_response"><ul></ul></div>';
    
    $return_html.= '<form id="contact_form_'.$custom_id.'" class="contact_form_wrapper" method="post" action="/wp-admin/admin-ajax.php">';
	$return_html.= '<input type="hidden" id="action" name="action" value="pp_contact_mailer"/>';

    if(is_array($pp_contact_form) && !empty($pp_contact_form))
    {
        foreach($pp_contact_form as $form_input)
        {
        	switch($form_input)
        	{
    				case 1:
    				
    				$return_html.= '<label for="your_name">'.__( 'Name *', THEMEDOMAIN ).'</label>
    				<input id="your_name" name="your_name" type="text" class="required_field" placeholder="'.__( 'Name *', THEMEDOMAIN ).'"/>
    				';	

    				break;
    				
    				case 2:
    				
    				$return_html.= '<label for="email">'.__( 'Email *', THEMEDOMAIN ).'</label>
    				<input id="email" name="email" type="text" class="required_field email" placeholder="'.__( 'Email *', THEMEDOMAIN ).'"/>
    				';	

    				break;
    				
    				case 3:
    				
    				$return_html.= '<label for="message">'.__( 'Message *', THEMEDOMAIN ).'</label>
    				<textarea id="message" name="message" rows="7" cols="10" class="required_field" style="width:91%;" placeholder="'.__( 'Message *', THEMEDOMAIN ).'"></textarea>
    				';	

    				break;
    				
    				case 4:
    				
    				$return_html.= '<label for="address">'.__( 'Address', THEMEDOMAIN ).'</label>
    				<input id="address" name="address" type="text" placeholder="'.__( 'Address', THEMEDOMAIN ).'"/>
    				';	

    				break;
    				
    				case 5:
    				
    				$return_html.= '<label for="phone">'.__( 'Phone', THEMEDOMAIN ).'</label>
    				<input id="phone" name="phone" type="text" placeholder="'.__( 'Phone', THEMEDOMAIN ).'"/>
    				';

    				break;
    				
    				case 6:
    				
    				$return_html.= '<label for="mobile">'.__( 'Mobile', THEMEDOMAIN ).'</label>
    				<input id="mobile" name="mobile" type="text" placeholder="'.__( 'Mobile', THEMEDOMAIN ).'"/>
    				';		

    				break;
    				
    				case 7:
    				
    				$return_html.= '<label for="company">'.__( 'Company Name', THEMEDOMAIN ).'</label>
    				<input id="company" name="company" type="text" placeholder="'.__( 'Company Name', THEMEDOMAIN ).'"/>
    				';

    				break;
    				
    				case 8:
    				
    				$return_html.= '<label for="country">'.__( 'Country', THEMEDOMAIN ).'</label>				
    				<input id="country" name="country" type="text" placeholder="'.__( 'Country', THEMEDOMAIN ).'"/>
    				';
    				break;
    			}
    		}
    	}

    	$pp_contact_enable_captcha = get_option('pp_contact_enable_captcha');
    	
    	if(!empty($pp_contact_enable_captcha))
    	{
    	
    	$return_html.= '<div id="captcha-wrap">
    		<div class="captcha-box">
    			<img src="'.get_stylesheet_directory_uri().'/get_captcha.php" alt="" id="captcha" />
    		</div>
    		<div class="text-box">
    			<label>Type the two words:</label>
    			<input name="captcha-code" type="text" id="captcha-code">
    		</div>
    		<div class="captcha-action">
    			<img src="'.get_stylesheet_directory_uri().'/images/refresh.jpg"  alt="" id="captcha-refresh" />
    		</div>
    	</div>
    	<br class="clear"/><br/><br/>';
    
    }
    
    $return_html.= '<br/><br/><p>
    	<input id="contact_submit_btn" type="submit" class="solidbg" value="'.__( 'Send', THEMEDOMAIN ).'"/>
    </p>';
    
	$return_html.= '</form>';
	$return_html.= '</div>';
	
	
	$return_html.= '</div>';
	
	$return_html.= '</div>';
	
	$return_html.= '</div>';

	return $return_html;
}

add_shortcode('ppb_contact', 'ppb_contact_func');

//Check if Layer slider is installed	
$revslider = ABSPATH . '/wp-content/plugins/revslider/revslider.php';

// Check if the file is available to prevent warnings
$pp_revslider_activated = file_exists($revslider);

if($pp_revslider_activated)
{
	function ppb_revslider_func($atts, $content) {
	
		//extract short code attr
		extract(shortcode_atts(array(
			'size' => 'one',
			'slider_id' => '',
		), $atts));
	
		$return_html = '<div class="'.$size.' fullwidth">';
		$return_html.= do_shortcode('[rev_slider '.$slider_id.']');
		$return_html.= '</div>';
	
		return $return_html;
	
	}
	
	add_shortcode('ppb_revslider', 'ppb_revslider_func');
}
?>