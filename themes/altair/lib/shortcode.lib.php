<?php
function dropcap_func($atts, $content) {
	extract(shortcode_atts(array(
		'style' => 1
	), $atts));

	//get first char
	$first_char = substr($content, 0, 1);
	$text_len = strlen($content);
	$rest_text = substr($content, 1, $text_len);

	$return_html = '<span class="dropcap'.$style.'">'.$first_char.'</span>';
	$return_html.= do_shortcode($rest_text);
	$return_html.= '<br class="clear"/><br/>';

	return $return_html;

}
add_shortcode('dropcap', 'dropcap_func');


function quote_func($atts, $content) {
	$return_html = '<blockquote>'.do_shortcode($content).'</blockquote>';

	return $return_html;
}
add_shortcode('quote', 'quote_func');


function pre_func($atts, $content) {
	$return_html = '<pre>'.strip_tags($content).'</pre>';

	return $return_html;
}
add_shortcode('pre', 'pre_func');


function tg_button_func($atts, $content) {
	extract(shortcode_atts(array(
		'href' => '',
		'align' => '',
		'bg_color' => '',
		'text_color' => '',
		'size' => 'small',
		'style' => '',
		'color' => '',
		'shadow' => '',
		'target' => '_self',
	), $atts));

	if(!empty($color))
	{
		switch(strtolower($color))
		{
			case 'black':
				$bg_color = '#000000';
				$text_color = '#ffffff';
			break;

			case 'grey':
				$bg_color = '#97a2a2';
				$text_color = '#ffffff';
			break;

			case 'white':
				$bg_color = '#f5f5f5';
				$text_color = '#444444';
			break;

			case 'blue':
				$bg_color = '#5babe1';
				$text_color = '#ffffff';
			break;
			
			case 'dark blue':
				$bg_color = '#2980b9';
				$text_color = '#ffffff';
			break;

			case 'yellow':
				$bg_color = '#f2ce3e';
				$text_color = '#ffffff';
			break;

			case 'red':
				$bg_color = '#cb5f54';
				$text_color = '#ffffff';
			break;

			case 'orange':
				$bg_color = '#f4ae40';
				$text_color = '#ffffff';
			break;

			case 'green':
				$bg_color = '#76bb2c';
				$text_color = '#ffffff';
			break;
			
			case 'emerald':
				$bg_color = '#4ec380';
				$text_color = '#ffffff';
			break;

			case 'pink':
				$bg_color = '#ea6288';
				$text_color = '#ffffff';
			break;

			case 'purple':
				$bg_color = '#a368bc';
				$text_color = '#ffffff';
			break;
		}
	}
	
	if(!empty($bg_color))
	{
		$border_color = $bg_color;
	}
	else
	{
		$border_color = 'transparent';
	}
	
	//Get darker shadow color
	$shadow_color = '#'.hex_darker(substr($bg_color, 1), 12);
	
	if(!empty($bg_color))
	{
		$return_html = '<a class="button '.$size.' '.$align.'" style="background-color:'.$bg_color.' !important;color:'.$text_color.' !important;border:1px solid '.$bg_color.' !important;';
		
		if(!empty($shadow))
		{
			$return_html.= 'box-shadow: 0 3px 0 0 '.$shadow_color.';';
		}
		
		$return_html.= $style.'"';
	}
	else
	{
		$return_html = '<a class="button '.$size.' '.$align.'"';
	}
	
	if(!empty($href))
	{
		$return_html.= ' onclick="window.open(\''.esc_js($href).'\', \''.esc_js($target).'\')"';
	}

	$return_html.= '>'.$content.'</a>';

	return $return_html;

}
add_shortcode('tg_button', 'tg_button_func');


function tg_social_icons_func($atts, $content) {

	extract(shortcode_atts(array(
		'style' => '',
		'size' => 'small',
	), $atts));

	$return_html = '<div class="social_wrapper shortcode '.$style.' '.$size.'"><ul>';
	
	$pp_facebook_username = get_option('pp_facebook_username');		    		
	if(!empty($pp_facebook_username))
	{
		$return_html.='<li class="facebook"><a target="_blank" title="Facebook" href="'.$pp_facebook_username.'"><i class="fa fa-facebook"></i></a></li>';
	}
	
	$pp_twitter_username = get_option('pp_twitter_username');
	if(!empty($pp_twitter_username))
	{
		$return_html.='<li class="twitter"><a target="_blank" title="Twitter" href="http://twitter.com/'.$pp_twitter_username.'"><i class="fa fa-twitter"></i></a></li>';
	}
	
	$pp_flickr_username = get_option('pp_flickr_username');
		    		
	if(!empty($pp_flickr_username))
	{
		$return_html.='<li class="flickr"><a target="_blank" title="Flickr" href="http://flickr.com/people/'.$pp_flickr_username.'"><i class="fa fa-flickr"></i></a></li>';
	}
		    		
	$pp_youtube_username = get_option('pp_youtube_username');
	if(!empty($pp_youtube_username))
	{
		$return_html.='<li class="youtube"><a target="_blank" title="Youtube" href="http://youtube.com/user/'.$pp_youtube_username.'"><i class="fa fa-youtube"></i></a></li>';
	}

	$pp_vimeo_username = get_option('pp_vimeo_username');
	if(!empty($pp_vimeo_username))
	{
		$return_html.='<li class="vimeo"><a target="_blank" title="Vimeo" href="http://vimeo.com/'.$pp_vimeo_username.'"><i class="fa fa-vimeo-square"></i></a></li>';
	}

	$pp_tumblr_username = get_option('pp_tumblr_username');
	if(!empty($pp_tumblr_username))
	{
		$return_html.='<li class="tumblr"><a target="_blank" title="Tumblr" href="http://'.$pp_tumblr_username.'.tumblr.com"><i class="fa fa-tumblr"></i></a></li>';
	}
	
	$pp_google_username = get_option('pp_google_username');
		    		
	if(!empty($pp_google_username))
	{
		$return_html.='<li class="google"><a target="_blank" title="Google+" href="'.$pp_google_username.'"><i class="fa fa-google-plus"></i></a></li>';
	}
		    		
	$pp_dribbble_username = get_option('pp_dribbble_username');
	if(!empty($pp_dribbble_username))
	{
		$return_html.='<li class="dribbble"><a target="_blank" title="Dribbble" href="http://dribbble.com/'.$pp_dribbble_username.'"><i class="fa fa-dribbble"></i></a></li>';
	}
	
	$pp_linkedin_username = get_option('pp_linkedin_username');
	if(!empty($pp_linkedin_username))
	{
		$return_html.='<li class="linkedin"><a target="_blank" title="Linkedin" href="'.$pp_linkedin_username.'"><i class="fa fa-linkedin"></i></a></li>';
	}
		            
	$pp_pinterest_username = get_option('pp_pinterest_username');
	if(!empty($pp_pinterest_username))
	{
		$return_html.='<li class="pinterest"><a target="_blank" title="Pinterest" href="http://pinterest.com/'.$pp_pinterest_username.'"><i class="fa fa-pinterest"></i></a></li>';
	}
		        	
	$pp_instagram_username = get_option('pp_instagram_username');
	if(!empty($pp_instagram_username))
	{
		$return_html.='<li class="instagram"><a target="_blank" title="Instagram" href="http://instagram.com/'.$pp_instagram_username.'"><i class="fa fa-instagram"></i></a></li>';
	}
	
	$return_html.= '</ul></div>';

	return $return_html;

}
add_shortcode('tg_social_icons', 'tg_social_icons_func');


function highlight_func($atts, $content) {
	extract(shortcode_atts(array(
		'type' => 'yellow',
	), $atts));
	
	$return_html = '';
	$return_html.= '<span class="highlight_'.$type.'">'.strip_tags($content).'</span>';

	return $return_html;
}
add_shortcode('highlight', 'highlight_func');


function one_half_func($atts, $content) {
	extract(shortcode_atts(array(
		'class' => '',
	), $atts));

	$return_html = '<div class="one_half '.$class.'">'.do_shortcode($content).'</div>';	

	return $return_html;
}
add_shortcode('one_half', 'one_half_func');


function one_half_bg_func($atts, $content) {
	extract(shortcode_atts(array(
		'class' => '',
		'bg' => '',
		'style' => '',
	), $atts));

	$return_html = '<div class="one_half_bg '.$class.'"';
	
	if(!empty($bg) OR !empty($style))
	{
		$return_html.= 'style="background: transparent url('.esc_url($bg).') no-repeat;'.$style.'"';
	}
	
	$return_html.= '>'.do_shortcode($content).'</div>';	

	return $return_html;
}
add_shortcode('one_half_bg', 'one_half_bg_func');


function one_half_last_func($atts, $content) {
	extract(shortcode_atts(array(
		'class' => '',
	), $atts));

	$return_html = '<div class="one_half last '.$class.'">'.do_shortcode($content).'</div>';

	return $return_html;
}
add_shortcode('one_half_last', 'one_half_last_func');


function one_third_func($atts, $content) {
	$return_html = '<div class="one_third">'.do_shortcode($content).'</div>';

	return $return_html;
}
add_shortcode('one_third', 'one_third_func');


function one_third_bg_func($atts, $content) {
	extract(shortcode_atts(array(
		'class' => '',
		'bg' => '',
		'style' => '',
	), $atts));

	$return_html = '<div class="one_third_bg '.$class.'"';
	
	if(!empty($bg) OR !empty($style))
	{
		$return_html.= 'style="background: transparent url('.esc_url($bg).') no-repeat;'.$style.'"';
	}
	
	$return_html.= '>'.do_shortcode($content).'</div>';	

	return $return_html;
}
add_shortcode('one_third_bg', 'one_third_bg_func');


function one_third_last_func($atts, $content) {
	$return_html = '<div class="one_third last">'.do_shortcode($content).'</div>';

	return $return_html;
}
add_shortcode('one_third_last', 'one_third_last_func');


function two_third_func($atts, $content) {
	$return_html = '<div class="two_third">'.do_shortcode($content).'</div>';

	return $return_html;
}
add_shortcode('two_third', 'two_third_func');


function two_third_bg_func($atts, $content) {
	extract(shortcode_atts(array(
		'class' => '',
		'bg' => '',
		'style' => '',
	), $atts));

	$return_html = '<div class="two_third_bg '.$class.'"';
	
	if(!empty($bg) OR !empty($style))
	{
		$return_html.= 'style="background: transparent url('.esc_url($bg).') no-repeat;'.$style.'"';
	}
	
	$return_html.= '>'.do_shortcode($content).'</div>';	

	return $return_html;
}
add_shortcode('two_third_bg', 'two_third_bg_func');


function two_third_last_func($atts, $content) {
	$return_html = '<div class="two_third last">'.do_shortcode($content).'</div>';

	return $return_html;
}
add_shortcode('two_third_last', 'two_third_last_func');


function one_fourth_func($atts, $content) {
	$return_html = '<div class="one_fourth">'.do_shortcode($content).'</div>';

	return $return_html;
}
add_shortcode('one_fourth', 'one_fourth_func');


function one_fourth_bg_func($atts, $content) {
	extract(shortcode_atts(array(
		'class' => '',
		'bg' => '',
		'style' => '',
	), $atts));

	$return_html = '<div class="one_fourth_bg '.$class.'"';
	
	if(!empty($bg) OR !empty($style))
	{
		$return_html.= 'style="background: transparent url('.esc_url($bg).') no-repeat;'.$style.'"';
	}
	
	$return_html.= '>'.do_shortcode($content).'</div>';	

	return $return_html;
}
add_shortcode('one_fourth_bg', 'one_fourth_bg_func');


function one_fourth_last_func($atts, $content) {
	$return_html = '<div class="one_fourth last">'.do_shortcode($content).'</div>';

	return $return_html;
}
add_shortcode('one_fourth_last', 'one_fourth_last_func');


function one_fifth_func($atts, $content) {
	$return_html = '<div class="one_fifth">'.do_shortcode($content).'</div>';

	return $return_html;
}
add_shortcode('one_fifth', 'one_fifth_func');


function one_fifth_last_func($atts, $content) {
	$return_html = '<div class="one_fifth last">'.do_shortcode($content).'</div>';

	return $return_html;
}
add_shortcode('one_fifth_last', 'one_fifth_last_func');


function one_sixth_func($atts, $content) {
	$return_html = '<div class="one_sixth">'.do_shortcode($content).'</div>';

	return $return_html;
}
add_shortcode('one_sixth', 'one_sixth_func');


function one_sixth_last_func($atts, $content) {
	$return_html = '<div class="one_sixth last">'.do_shortcode($content).'</div>';

	return $return_html;
}
add_shortcode('one_sixth_last', 'one_sixth_last_func');


function tg_pre_func($atts, $content) {
	extract(shortcode_atts(array(
		'title' => '',
		'close' => 1,
	), $atts));
	
	$return_html = '';
	$return_html.= '<pre>';
	$return_html.= $content;
	$return_html.= '</pre>';

	return $return_html;
}
add_shortcode('tg_pre', 'tg_pre_func');


function tg_map_func($atts) {
	//extract short code attr
	extract(shortcode_atts(array(
		'width' => 400,
		'height' => 300,
		'lat' => 0,
		'long' => 0,
		'zoom' => 12,
		'type' => '',
		'popup' => '',
		'address' => '',
	), $atts));

	$custom_id = time().rand();
	$return_html = '<div class="map_shortcode_wrapper" id="map'.$custom_id.'" style="width:'.$width.'px;height:'.$height.'px"></div>';
	
	$ext_attr = array(
		'id' => 'map'.$custom_id,
		'lat' => $lat,
		'long' => $long,
		'zoom' => $zoom,
		'type' => $type,
		'popup' => $popup,
		'address' => $address,
	);
	
	$ext_attr_serialize = serialize($ext_attr);
	
	wp_enqueue_script("gmap", get_template_directory_uri()."/js/gmap.js", false, THEMEVERSION, true);
	wp_enqueue_script("script-contact-map".$custom_id, get_template_directory_uri()."/templates/script-map-shortcode.php?data=".$ext_attr_serialize, false, THEMEVERSION, true);

	return $return_html;

}

add_shortcode('tg_map', 'tg_map_func');


function video_func($atts) {
	extract(shortcode_atts(array(
		'width' => 640,
		'height' => 385,
		'img_src' => '',
		'video_src' => '',
	), $atts));

	$custom_id = time().rand();

	$return_html = '<div id="video_self_'.$custom_id.'" style="width:'.$width.'px;height:'.$height.'px">';
	$return_html.= '<div id="self_hosted_vid_'.$custom_id.'"></div>';
	$return_html.= '<script type="text/javascript">';
	$return_html.= 'jwplayer("#self_hosted_vid_'.$custom_id.'").setup({';
	$return_html.= 'flashplayer: "'.get_template_directory_uri().'/js/player.swf",';
	$return_html.= 'file: "'.$video_src.'",';
	$return_html.= 'image: "'.$img_src.'",';
	$return_html.= 'width: '.$width.',';
	$return_html.= 'height: '.$height;
	$return_html.= '});';
	$return_html.= '</script>';
	$return_html.= '</div>';

	return $return_html;
}
add_shortcode('video', 'video_func');


function tg_thumb_gallery_func($atts, $content) {
	extract(shortcode_atts(array(
		'gallery_id' => '',
		'width' => 150,
		'height' => 150,
	), $atts));

	$images_arr = get_post_meta($gallery_id, 'wpsimplegallery_gallery', true);
	$return_html = '';

	if(!empty($images_arr))
	{
		foreach($images_arr as $key => $image)
		{
			$image_url = wp_get_attachment_image_src($image, 'large', true);
			
			if($width==150 && $height==150)
			{
				$small_image_url = wp_get_attachment_image_src($image, 'thumbnail', true);
				$thumb_url = $small_image_url[0];
			}
			else
			{
				require_once(get_template_directory() . "/modules/aq_resizer.php");
				$small_image_url = aq_resize($image_url[0],$width,$height,true);
				$thumb_url = $small_image_url;
			}
			
			$image_title = get_the_title($image);
		    $image_caption = get_post_field('post_excerpt', $image);
		    $image_desc = get_post_field('post_content', $image);
		    
		    $pp_gallery_shortcode_title = get_option('pp_gallery_shortcode_title');
    		$pp_social_sharing = get_option('pp_social_sharing');
			
			$return_html.= '<div class="post_img small square_thumb" style="float:left;margin-right:10px;margin-bottom:10px">';
			$return_html.= '<a rel="gallery" class="fancy-gallery" href="'.$image_url[0].'" ';
			
			if(!empty($pp_gallery_shortcode_title)) 
			{
				$return_html.= 'data-title="<strong>'.$image_title.'</strong> ';
			}
			if(!empty($image_desc)) 
			{
				$return_html.= htmlentities($image_desc);
			}
			if(!empty($pp_social_sharing)) 
			{
				$return_html.= '<br/><br/><br/><br/><a class=\'button\' href=\''.get_permalink($image).'\'>'.__( 'Comment & share', THEMEDOMAIN ).'</a>';
			}
			if(!empty($pp_gallery_shortcode_title)) 
			{
				$return_html.='"';
			}
			
			$return_html.= '>';
			$return_html.= '<img src="'.$thumb_url.'" class="thumbnail_gallery" alt=""/>';
			$return_html.=	'<div class="mask"><div class="mask_circle"><i class="fa fa-expand"/></i></div></div>';
			$return_html.= '</a>';
			$return_html.= '</div>';
		}
	}
	else
	{
		$return_html.= 'Empty gallery item. Please make sure you have upload image to it or check the short code.';
	}

	$return_html.= '<br class="clear"/>';

	return $return_html;
}
add_shortcode('tg_thumb_gallery', 'tg_thumb_gallery_func');


function tg_image_func($atts, $content) {
	extract(shortcode_atts(array(
		'src' => '',
		'animation' => '',
		'style' => '',
	), $atts));

	$return_html = '<img src="'.$src.'" alt="" class="animated" data-animation="'.esc_attr($animation).'" style="'.esc_attr($style).'" />';

	return $return_html;

}
add_shortcode('tg_image', 'tg_image_func');


function tg_teaser_func($atts, $content) {
	extract(shortcode_atts(array(
		'image' => '',
		'columns' => 'one_third',
		'title' => '',
		'align' => '',
	), $atts));

	$return_html = '<div class="teaser_wrapper '.$columns.' '.$align.'">';
	
	if(!empty($image))
	{
		$return_html.= '<img src="'.esc_url($image).'" alt="" />';
	}
	
	if(!empty($title) OR !empty($content))
	{
		$return_html.= '<div class="teaser_content_wrapper">';
		
		if(!empty($title))
		{
			$return_html.= '<h5>'.$title.'</h5>';
		}
		
		if(!empty($content))
		{
			$return_html.= '<div class="teaser_content">'.$content.'</div>';
		}
		
		$return_html.= '</div>';
	}
	
	$return_html.= '</div>';

	return $return_html;

}
add_shortcode('tg_teaser', 'tg_teaser_func');


function tg_promo_box_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'title' => '',
		'border' => '',
		'shadow' => '',
		'button_text' => '',
		'button_url' => '',
		'button_style' => 'button',
	), $atts));
	
	$return_html = '<div class="promo_box" ';
	if(!empty($border))
	{
		$return_html.= 'style="border-top:2px solid '.$border.'"';
	}
	$return_html.= '>';
	if(!empty($button_text))
	{
		$return_html.= '<a href="'.esc_url($button_url).'" class="'.esc_attr($button_style).'">'.$button_text.'</a>';
	}
	if(!empty($title))
	{
		$return_html.= '<h5>'.$title.'</h5>';
	}
	$return_html.= $content;
	$return_html.= '</div>';
	
	return $return_html;
}

add_shortcode('tg_promo_box', 'tg_promo_box_func');


function tg_alert_box_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'style' => 'general',
	), $atts));
	
	$fa_class = 'fa-bullhorn';
	switch($style)
	{
		case 'error':
			$fa_class = 'fa-exclamation-circle';
		break;
		
		case 'success':
			$fa_class = 'fa-flag';
		break;
		
		case 'notice':
			$fa_class = 'fa-info-circle';
		break;
	}
	
	$custom_id = time().rand();
	
	$return_html = '<div id="'.$custom_id.'" class="alert_box '.$style.'">';
	$return_html.= '<i class="fa '.$fa_class.' alert_icon"></i>';
	$return_html.= '<div class="alert_box_msg">'.do_shortcode($content).'</div>';
	$return_html.= '<a href="#" class="close_alert" data-target="'.$custom_id.'"><i class="fa fa-times"></i></a>';
	$return_html.= '</div>';
	
	return $return_html;
}

add_shortcode('tg_alert_box', 'tg_alert_box_func');


function tg_tab_func($atts, $content) {
	//extract short code attr
	extract(shortcode_atts(array(
		'tab1' => '',
		'tab2' => '',
		'tab3' => '',
		'tab4' => '',
		'tab5' => '',
		'tab6' => '',
		'tab7' => '',
		'tab8' => '',
		'tab9' => '',
		'tab10' => '',
	), $atts));
	
	$tab_arr = array(
		$tab1,
		$tab2,
		$tab3,
		$tab4,
		$tab5,
		$tab6,
		$tab7,
		$tab8,
		$tab9,
		$tab10,
	);

	//Add jquery ui script dynamically
	wp_enqueue_script("jquery-ui-core");
	wp_enqueue_script("jquery-ui-tabs");
	wp_enqueue_script('custom-tab', get_template_directory_uri()."/js/custom-tab.js", false, THEMEVERSION, true);

	$return_html = '<div class="tabs"><ul>';

	foreach($tab_arr as $key=>$tab)
	{
		//display title1
		if(!empty($tab))
		{
			$return_html.= '<li><a href="#tabs-'.($key+1).'">'.$tab.'</a></li>';
		}
	}

	$return_html.= '</ul>';
	$return_html.= do_shortcode($content);
	$return_html.= '</div>';

	return $return_html;
}

add_shortcode('tg_tab', 'tg_tab_func');


function tg_ver_tab_func($atts, $content) {
	//extract short code attr
	extract(shortcode_atts(array(
		'tab1' => '',
		'tab2' => '',
		'tab3' => '',
		'tab4' => '',
		'tab5' => '',
		'tab6' => '',
		'tab7' => '',
		'tab8' => '',
		'tab9' => '',
		'tab10' => '',
		'align' => 'left',
	), $atts));
	
	$tab_arr = array(
		$tab1,
		$tab2,
		$tab3,
		$tab4,
		$tab5,
		$tab6,
		$tab7,
		$tab8,
		$tab9,
		$tab10,
	);

	//Add jquery ui script dynamically
	wp_enqueue_script("jquery-ui-core");
	wp_enqueue_script("jquery-ui-tabs");
	wp_enqueue_script('custom-tab', get_template_directory_uri()."/js/custom-tab.js", false, THEMEVERSION, true);

	$return_html = '<div class="tabs vertical '.$align.'"><ul>';

	foreach($tab_arr as $key=>$tab)
	{
		//display title1
		if(!empty($tab))
		{
			$return_html.= '<li><a href="#tabs-'.($key+1).'">'.$tab.'</a></li>';
		}
	}

	$return_html.= '</ul>';
	$return_html.= do_shortcode($content);
	$return_html.= '</div>';

	return $return_html;
}

add_shortcode('tg_ver_tab', 'tg_ver_tab_func');


function tab_func($atts, $content) {
	//extract short code attr
	extract(shortcode_atts(array(
		'id' => '',
	), $atts));
	
	$return_html = '';
	$return_html.= '<div id="tabs-'.$id.'" class="tab_wrapper"><br class="clear"/>'.do_shortcode($content).'</div>';

	return $return_html;

}

add_shortcode('tab', 'tab_func');


function tg_accordion_func($atts, $content) {
	//extract short code attr
	extract(shortcode_atts(array(
		'title' => '',
		'icon' => '',
		'close' => 0,
	), $atts));

	$close_class = '';

	if(!empty($close))
	{
		$close_class = 'pp_accordion_close';
	}
	else
	{
		$close_class = 'pp_accordion';
	}

	//Add jquery ui script dynamically
	wp_enqueue_script("jquery-ui-core");
	wp_enqueue_script("jquery-ui-accordion");
	wp_enqueue_script('custom-accordion', get_template_directory_uri()."/js/custom-accordion.js", false, THEMEVERSION, true);

	$return_html = '<div class="'.$close_class.'"><h3><a href="#">';
	
	if(!empty($icon))
	{
		$return_html.= '<i class="fa '.$icon.'"></i>';
	}
	
	$return_html.= $title.'</a></h3>';
	$return_html.= '<div><p>';
	$return_html.= do_shortcode($content);
	$return_html.= '</p></div></div>';

	return $return_html;
}

add_shortcode('tg_accordion', 'tg_accordion_func');


function tg_divider_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'style' => 'normal'
	), $atts));

	$return_html = '<hr class="'.$style.'"/>';
	if($style == 'totop')
	{
		$return_html.= '<a class="hr_totop" href="#">'.__( 'Go to top', THEMEDOMAIN ).'&nbsp;<i class="fa fa-arrow-up"></i></a>';
	}

	return $return_html;

}

add_shortcode('tg_divider', 'tg_divider_func');


function ppb_client_carousel_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'size' => 'one',
		'items' => 5,
		'cat' => '',
		'columns' => '',
	), $atts));
	
	if(!is_numeric($items))
	{
		$items = 1;
	}
	
	if(!is_numeric($columns))
	{
		$columns = 4;
	}

	//Get clients
	$args = array(
	    'numberposts' => $items,
	    'order' => 'ASC',
	    'orderby' => 'menu_order',
	    'post_type' => array('clients'),
	);
	if(!empty($cat))
	{
		$args['clientcats'] = $cat;
	}

	$clients_arr = get_posts($args);
	
	$return_html = '';

	if(!empty($clients_arr))
	{	
		//Enqueue CSS and javascript
		wp_enqueue_script("flexslider-js", get_template_directory_uri()."/js/flexslider/jquery.flexslider-min.js", false, THEMEVERSION, true);
		wp_enqueue_script("script-ppb-client", get_template_directory_uri()."/templates/script-ppb-client.php", false, THEMEVERSION, true);
		
		$return_html.= '<div class="flexslider post_carousel post_fullwidth post_type_gallery"><ul class="slides">';
		
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
		
		$return_html.= '</ul></div>';
	}
	else
	{
		$return_html.= 'Empty client Please make sure you have created it.';
	}

	return $return_html;

}

add_shortcode('ppb_client_carousel', 'ppb_client_carousel_func');

function tg_lightbox_func($atts, $content) {

	extract(shortcode_atts(array(
		'type' => 'image',
		'src' => '',
		'href' => '',
		'youtube_id' => '',
		'vimeo_id' => '',
	), $atts));

	$class = 'lightbox';

	if($type != 'image')
	{
		$class.= '_'.$type;
	}

	if($type == 'youtube')
	{
		$href = '#video_'.$youtube_id;
	}

	if($type == 'vimeo')
	{
		$href = '#video_'.$vimeo_id;
	}
	
	$return_html = '<div class="post_img">';
	$return_html.= '<a href="'.$href.'" class="img_frame">';
	
	if(!empty($src))
	{
		$return_html.= '<img src="'.$src.'"img_frame"/>';
	}

	if(!empty($youtube_id))
	{
		$return_html.= '<div style="display:none;"><div id="video_'.$youtube_id.'" style="width:900px;height:488px;overflow:hidden;" class="video-container"><iframe width="900" height="488" src="http://www.youtube.com/embed/'.$youtube_id.'?theme=dark&amp;rel=0&amp;wmode=opaque" frameborder="0"></iframe></div></div>';
	}

	if(!empty($vimeo_id))
	{
		$return_html.= '<div style="display:none;"><div id="video_'.$vimeo_id.'" style="width:900px;height:506px;overflow:hidden;" class="video-container"><iframe src="http://player.vimeo.com/video/'.$vimeo_id.'?title=0&amp;byline=0&amp;portrait=0" width="900" height="506" frameborder="0"></iframe></div></div>';
	}
	
	$return_html.= '</a></div>';

	return $return_html;

}

add_shortcode('tg_lightbox', 'tg_lightbox_func');


function tg_youtube_func($atts) {
	extract(shortcode_atts(array(
		'width' => 640,
		'height' => 385,
		'video_id' => '',
	), $atts));

	$custom_id = time().rand();

	$return_html = '<div class="video-container"><iframe title="YouTube video player" width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$video_id.'?theme=dark&rel=0&wmode=transparent&controls=0" frameborder="0" allowfullscreen></iframe></div>';

	return $return_html;
}

add_shortcode('tg_youtube', 'tg_youtube_func');


function tg_vimeo_func($atts, $content) {
	extract(shortcode_atts(array(
		'width' => 640,
		'height' => 385,
		'video_id' => '',
	), $atts));

	$custom_id = time().rand();

	$return_html = '<div class="video-container"><iframe src="http://player.vimeo.com/video/'.$video_id.'?title=0&amp;byline=0&amp;portrait=0" width="'.$width.'" height="'.$height.'"></iframe></div>';

	return $return_html;
}

add_shortcode('tg_vimeo', 'tg_vimeo_func');

function tg_animate_counter_func($atts, $content) {
	extract(shortcode_atts(array(
		'start' => 0,
		'end' => 100,
		'fontsize' => 60,
		'fontcolor' => '',
		'count_subject' => '',
	), $atts));

	$custom_id = time().rand();

	wp_enqueue_script("odometer-js", get_template_directory_uri()."/js/odometer.min.js", false, THEMEVERSION, true);
	wp_enqueue_script("script-animate-counter".$custom_id, get_template_directory_uri()."/templates/script-animate-counter-shortcode.php?id=".$custom_id."&start=".$start."&end=".$end."&fontsize=".$fontsize, false, THEMEVERSION, true);
	
	$return_html = '<div class="animate_counter_wrapper">';
	
	if(!empty($content))
	{
		$return_html.= $content.'<br/>';
	}
	
	$return_html.= '<div id="'.$custom_id.'" class="odometer" style="font-size:'.$fontsize.'px;line-height:'.$fontsize.'px;';
	
	if(!empty($fontcolor))
	{
		$return_html.= 'color:'.$fontcolor;
	}
	
	$return_html.= '">'.number_format($start).'</div>';
	$return_html.= '<div class="count_separator"><span></span></div>';
	$return_html.= '<div class="counter_subject">'.$count_subject.'</div>';
	$return_html.= '</div>';

	return $return_html;
}

add_shortcode('tg_animate_counter', 'tg_animate_counter_func');

function tg_animate_circle_func($atts, $content) {
	extract(shortcode_atts(array(
		'percent' => 100,
		'dimension' => 200,
		'width' => 10,
		'color' => '',
		'fontsize' => '20',
		'subject' => '',
	), $atts));

	$custom_id = time().rand();

	wp_enqueue_style("jquery.circliful", get_template_directory_uri()."/css/jquery.circliful.css", false, THEMEVERSION, "all");
	wp_enqueue_script("jquery.circliful", get_template_directory_uri()."/js/jquery.circliful.min.js", false, THEMEVERSION, true);
	wp_enqueue_script("script-animate-counter".$custom_id, get_template_directory_uri()."/templates/script-animate-circle-shortcode.php?id=".$custom_id, false, THEMEVERSION, true);
	
	$return_html = '
				<div class="visual_circle">
					<div id="'.$custom_id.'" data-dimension="'.esc_attr($dimension).'" data-width="'.esc_attr($width).'" data-percent="'.esc_attr($percent).'" data-fgcolor="'.esc_attr($color).'" data-bgcolor="#f0f0f0" data-text="'.esc_attr($content).'" data-fontsize="'.esc_attr($fontsize).'" data-info="'.esc_attr($subject).'"></div>';
				
	$return_html.= '</div>';

	return $return_html;
}

add_shortcode('tg_animate_circle', 'tg_animate_circle_func');

function tg_animate_bar_func($atts, $content) {
	extract(shortcode_atts(array(
		'percent' => 0,
		'color' => '',
	), $atts));
	
	if($percent < 0)
	{
		$percent = 0;
	}
	
	if($percent > 100)
	{
		$percent = 100;
	}
	
	$return_html = '<div class="progress_bar"><div class="progress_holder">';
	$return_html.= '<div class="progress_bar_title">'.$content.'</div>';
	$return_html.= '<div class="progress_number">'.$percent.'%</div>';
	$return_html.= '</div>';
	$return_html.= '<div class="progress_bar_holder">';
	$return_html.= '<div class="progress_bar_content" data-score="'.esc_attr($percent).'" style="width:0;background:'.$color.'"></div>';
	$return_html.= '</div>';
	$return_html.= '</div>';

	return $return_html;
}

add_shortcode('tg_animate_bar', 'tg_animate_bar_func');


function tg_pricing_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'size' => 'one',
		'items' => 3,
		'category' => '',
		'columns' => '3',
	), $atts));

	if(!is_numeric($items))
	{
		$items = 4;
	}
	
	$return_html = '';
	
	$pricing_order = 'ASC';
	$pricing_order_by = 'menu_order';
	
	//Get portfolio items
	$args = array(
	    'numberposts' => $items,
	    'order' => $pricing_order,
	    'orderby' => $pricing_order_by,
	    'post_type' => array('pricing'),
	);
	
	if(!empty($category))
	{
		$args['pricingcats'] = $category;
	}
	$pricing_arr = get_posts($args);
	
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

	if(!empty($pricing_arr) && is_array($pricing_arr))
	{
		$return_html.= '<div class="pricing_content_wrapper">';
		$last_class = '';
	
		foreach($pricing_arr as $key => $pricing)
		{
			if(($key+1)%$count_column==0)
			{
				$last_class = 'last';
			}
			else
			{
				$last_class = '';
			}
			
			$return_html.= '<div class="pricing '.$columns_class.' '.$last_class.'">';
			$return_html.= '<ul class="pricing_wrapper">';
			
			//Check if featured
			$priing_featured_class = '';
			$priing_button_class = '';
			$pricing_plan_featured = get_post_meta($pricing->ID, 'pricing_featured', true);
			if(!empty($pricing_plan_featured))
			{
				$priing_featured_class = 'featured';
			}
			
			$return_html.= '<li class="title_row '.$priing_featured_class.'">'.$pricing->post_title.'</li>';
			
			//Check price
			$pricing_plan_currency = get_post_meta($pricing->ID, 'pricing_plan_currency', true);
			$pricing_plan_price = get_post_meta($pricing->ID, 'pricing_plan_price', true);
			$pricing_plan_time = get_post_meta($pricing->ID, 'pricing_plan_time', true);
			
			$return_html.= '<li class="price_row">';
			$return_html.= '<strong>'.$pricing_plan_currency.'</strong>';
			$return_html.= '<em class="exact_price">'.$pricing_plan_price.'</em>';
			$return_html.= '<em class="time">'.$pricing_plan_time.'</em>';
			$return_html.= '</li>';
			
			//Get pricing features
			$pricing_plan_features = get_post_meta($pricing->ID, 'pricing_plan_features', true);
			$pricing_plan_features = trim($pricing_plan_features);
			$pricing_plan_features_arr = explode("\n", $pricing_plan_features);
			$pricing_plan_features_arr = array_filter($pricing_plan_features_arr, 'trim');
			
			foreach ($pricing_plan_features_arr as $feature) {
			    $return_html.= '<li>'.$feature.'</li>';
			}
			
			//Get button
			$pricing_button_text = get_post_meta($pricing->ID, 'pricing_button_text', true);
			$pricing_button_url = get_post_meta($pricing->ID, 'pricing_button_url', true);
			
			$return_html.= '<li class="button_row"><a href="'.esc_url($pricing_button_url).'" class="button">'.$pricing_button_text.'</a></li>';
			
			$return_html.= '</ul>';
			$return_html.= '</div>';
		}
		
		$return_html.= '</div>';
	}
	
	$return_html.= '<br class="clear"/>';
	
	return $return_html;
}

add_shortcode('tg_pricing', 'tg_pricing_func');


function tg_gallery_slider_func($atts, $content) {
	extract(shortcode_atts(array(
		'gallery_id' => '',
		'size' => 'gallery_a',
	), $atts));
	
	wp_enqueue_script("flexslider-js", get_template_directory_uri()."/js/flexslider/jquery.flexslider-min.js", false, THEMEVERSION, true);
	wp_enqueue_script("script-gallery-flexslider", get_template_directory_uri()."/templates/script-gallery-flexslider.php", false, THEMEVERSION, true);

	$images_arr = get_post_meta($gallery_id, 'wpsimplegallery_gallery', true);
	
	$return_html = '';

	if(!empty($images_arr))
	{
		$return_html.= '<div class="slider_wrapper">';
		$return_html.= '<div class="flexslider" data-height="750">';
		$return_html.= '<ul class="slides">';
		
		foreach($images_arr as $key => $image)
		{
			$image_url = wp_get_attachment_image_src($image, $size, true);
			
			$return_html.= '<li>';
			$return_html.= '<img src="'.$image_url[0].'" alt=""/>';
			$return_html.= '</li>';
		}
		
		$return_html.= '</ul>';
		$return_html.= '</div>';
		$return_html.= '</div>';
	}
	else
	{
		$return_html.= 'Empty gallery item. Please make sure you have upload image to it or check the short code.';
	}

	return $return_html;
}
add_shortcode('tg_gallery_slider', 'tg_gallery_slider_func');


function pp_audio_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'src' => '',
		'width' => '80',
		'height' => '30',
	), $atts));

	$custom_id = time().rand();
	
	wp_enqueue_style("mediaelementplayer", get_template_directory_uri()."/js/mediaelement/mediaelementplayer.css", false, THEMEVERSION, "all");
	wp_enqueue_script("mediaelement-and-player.min", get_template_directory_uri()."/js/mediaelement/mediaelement-and-player.min.js", false, THEMEVERSION);
	wp_enqueue_script("script-audio-shortcode", get_template_directory_uri()."/templates/script-audio-shortcode.php?id=".$custom_id, false, THEMEVERSION, true);
	
	$return_html = '<audio id="'.$custom_id.'" src="'.esc_url($src).'" width="'.$width.'" height="'.$height.'"></audio>';
	return $return_html;
}

add_shortcode('pp_audio', 'pp_audio_func');


function jwplayer_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'id' => '',
		'file' => '',
		'image' => '',
		'width' => '80',
		'height' => '30',
	), $atts));
	
	wp_enqueue_style("mediaelementplayer", get_template_directory_uri()."/js/mediaelement/mediaelementplayer.css", false, THEMEVERSION, "all");
	wp_enqueue_script("swfobject", "http://ajax.googleapis.com/ajax/libs/swfobject/2.1/swfobject.js", false, THEMEVERSION, true);
	wp_enqueue_script("jwplayer", get_template_directory_uri()."/js/jwplayer.js", false, THEMEVERSION);
	wp_enqueue_script("mediaelement-and-player.min", get_template_directory_uri()."/js/mediaelement/mediaelement-and-player.min.js", false, THEMEVERSION);
	wp_enqueue_script("script-jwplayer-shortcode".$id, get_template_directory_uri()."/templates/script-jwplayer-shortcode.php?id=".$id."&file=".$file."&image=".$image."&width=".$width."&height=".$height, false, THEMEVERSION, true);
}

add_shortcode('jwplayer', 'jwplayer_func');


function googlefont_func($atts, $content) {

	//extract short code attr
	extract(shortcode_atts(array(
		'font' => '',
		'fontsize' => '',
	), $atts));

	$return_html = '';

	if(!empty($font))
	{
		$encoded_font = urlencode($font);
		wp_enqueue_style($encoded_font, "https://fonts.googleapis.com/css?family=".$encoded_font, false, "", "all");
		
		$return_html = '<div class="googlefont" style="font-family:'.$font.';font-size:'.$fontsize.'px">'.$content.'</div>';
	}

	return $return_html;
}

add_shortcode('googlefont', 'googlefont_func');


function tg_testimonial_slider_func($atts, $content) {
	extract(shortcode_atts(array(
		'size' => 'one',
		'items' => 3,
		'cat' => '',
	), $atts));

	if(!is_numeric($items))
	{
		$items = 4;
	}
	
	wp_enqueue_script("flexslider-js", get_template_directory_uri()."/js/flexslider/jquery.flexslider-min.js", false, THEMEVERSION, true);
	wp_enqueue_script("sciprt-testimonials-flexslider", get_template_directory_uri()."/templates/sciprt-testimonials-flexslider.php", false, THEMEVERSION, true);
	
	$return_html ='<div>';
	
	$testimonials_order = 'ASC';
	$testimonials_order_by = 'menu_order';
	
	//Get testimonial items
	$args = array(
	    'numberposts' => $items,
	    'order' => $testimonials_order,
	    'orderby' => $testimonials_order_by,
	    'post_type' => array('testimonials'),
	    'suppress_filters' => false,
	);
	
	if(!empty($cat))
	{
		$args['testimonialcats'] = $cat;
	}
	$testimonial_arr = get_posts($args);
	$return_html = '';
	
	if(!empty($testimonial_arr) && is_array($testimonial_arr))
	{
		$return_html.= '<div class="testimonial_slider_wrapper">';
		$return_html.= '<div class="flexslider" data-height="750">';
		$return_html.= '<ul class="slides">';
		
		foreach($testimonial_arr as $key => $testimonial)
		{
			$testimonial_ID = $testimonial->ID;
		
			//Get testimonial meta
			$testimonial_name = get_post_meta($testimonial_ID, 'testimonial_name', true);
			$testimonial_position = get_post_meta($testimonial_ID, 'testimonial_position', true);
			$testimonial_company_name = get_post_meta($testimonial_ID, 'testimonial_company_name', true);
			$testimonial_company_website = get_post_meta($testimonial_ID, 'testimonial_company_website', true);
			
			$return_html.= '<li>';
			$return_html.= '<div class="testimonial_slider_wrapper">';
			
			if(!empty($testimonial->post_content))
			{
				$return_html.= $testimonial->post_content;
			}
			
			if(!empty($testimonial_name))
			{
				$return_html.= '<div class="testimonial_slider_meta">';
				$return_html.= '<h6>'.$testimonial_name.'</h6>';
					
				if(!empty($testimonial_position))
				{
				    $return_html.= '<div class="testimonial_slider_meta_position">'.$testimonial_position.'</div>';
				}
				
				if(!empty($testimonial_company_name))
				{
				    $return_html.= '-<div class="testimonial_slider_meta_company">';
				    
				    if(!empty($testimonial_company_website))
				    {
				    	$return_html.= '<a href="'.esc_url($testimonial_company_website).'" target="_blank">';
				    }
				    
				    $return_html.=$testimonial_company_name;
				    
				    if(!empty($testimonial_company_website))
				    {
				    	$return_html.= '</a>';
				    }
				    
				    $return_html.= '</div>';
				}
				$return_html.= '</div>';
			}
			
			$return_html.= '</div>';
			$return_html.= '</li>';
		}
		
		$return_html.= '</ul>';
		$return_html.= '</div>';
		$return_html.= '</div>';
	}

	return $return_html;
}
add_shortcode('tg_testimonial_slider', 'tg_testimonial_slider_func');


function tg_header_func($atts, $content) {
	extract(shortcode_atts(array(
		'title' => '',
		'subtitle' => '',
	), $atts));
	
	$return_html = '<div style="text-align:center">';
	$return_html.= '<h2 class="ppb_title">'.$title.'</h2>';
	$return_html.= '<div class="page_caption_desc">'.$subtitle.'</div>';
	$return_html.= '</div>';

	return $return_html;
}

add_shortcode('tg_header', 'tg_header_func');


function tg_program_func($atts, $content) {
	extract(shortcode_atts(array(
		'title' => '',
		'place' => '',
	), $atts));
	
	$return_html = '<div class="tour_program">';
	$return_html.= '<div class="tour_program_title">'.$title.'</div>';
	$return_html.= '<div class="tour_program_place">'.$place.'</div>';
	$return_html.= '<div class="tour_program_content">'.$content.'</div>';
	$return_html.= '</div>';

	return $return_html;
}

add_shortcode('tg_program', 'tg_program_func');


// Actual processing of the shortcode happens here
function pp_last_run_shortcode( $content ) {
    global $shortcode_tags;
 
    // Backup current registered shortcodes and clear them all out
    $orig_shortcode_tags = $shortcode_tags;
    remove_all_shortcodes();
 
    add_shortcode( 'one_half', 'one_half_func' );
    add_shortcode( 'one_half_last', 'one_half_last_func' );
    add_shortcode( 'one_half_bg', 'one_half_bg_func' );
    add_shortcode( 'one_third', 'one_third_func' );
    add_shortcode( 'one_third_last', 'one_third_last_func' );
    add_shortcode( 'one_third_bg', 'one_third_bg_func' );
    add_shortcode( 'two_third', 'two_third_func' );
    add_shortcode( 'two_third_bg', 'two_third_bg_func' );
    add_shortcode( 'two_third_last', 'two_third_last_func' );
    add_shortcode( 'one_fourth', 'one_fourth_func' );
    add_shortcode( 'one_fourth_bg', 'one_fourth_bg_func' );
    add_shortcode( 'one_fourth_last', 'one_fourth_last_func' );
    add_shortcode( 'one_fifth', 'one_fifth_func' );
    add_shortcode( 'one_fifth_last', 'one_fifth_last_func' );
    add_shortcode( 'tg_image', 'tg_image_func' );
    add_shortcode( 'tg_tab', 'tg_tab_func' );
    add_shortcode( 'tg_header', 'tg_header_func' );
    add_shortcode( 'tg_program', 'tg_program_func' );
	add_shortcode( 'tg_ver_tab', 'tg_ver_tab_func' );
    add_shortcode( 'tab', 'tab_func' );
    add_shortcode( 'tg_accordion', 'tg_accordion_func' );
    add_shortcode( 'pp_pre', 'pp_pre_func' );
 
    // Do the shortcode (only the one above is registered)
    $content = do_shortcode( $content );
 
    // Put the original shortcodes back
    $shortcode_tags = $orig_shortcode_tags;
 
    return $content;
}
 
add_filter( 'the_content', 'pp_last_run_shortcode', 7 );

?>