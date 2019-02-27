<?php

/*
	Begin Create Shortcode Generator Options
*/

add_action('admin_menu', 'pp_shortcode_generator');

function pp_shortcode_generator() {

  //add_submenu_page('functions.php', 'Shortcode Generator', 'Shortcode Generator', 'manage_options', 'pp_shortcode_generator', 'pp_shortcode_generator_options');
  
  global $page_postmetas;
	if ( function_exists('add_meta_box') && isset($page_postmetas) && count($page_postmetas) > 0 ) {  
		add_meta_box( 'shortcode_metabox', 'Shortcode Options', 'pp_shortcode_generator_options', 'page', 'normal', 'high' );
		add_meta_box( 'shortcode_metabox', 'Shortcode Options', 'pp_shortcode_generator_options', 'post', 'normal', 'high' );  
		add_meta_box( 'shortcode_metabox', 'Shortcode Options', 'pp_shortcode_generator_options', 'tours', 'normal', 'high' );
	}
}

function pp_shortcode_generator_options() {

  	$plugin_url = get_stylesheet_directory_uri().'/plugins/shortcode_generator';
  	
  	//Get all galleries
  	$args = array(
	    'numberposts' => -1,
	    'post_type' => array('galleries'),
	);
	
	$galleries_arr = get_posts($args);
	$galleries_select = array();
	$galleries_select[''] = '';
	
	foreach($galleries_arr as $gallery)
	{
		$galleries_select[$gallery->ID] = $gallery->post_title;
	}
	
	//Get all client categories
	$client_cat_arr = get_terms('clientcats', 'hide_empty=0&hierarchical=0&parent=0');
	$client_cat_select = array();
	$client_cat_select[''] = '';
	
	foreach($client_cat_arr as $client_cat)
	{
		$client_cat_select[$client_cat->slug] = $client_cat->name;
	}
	
	//Get all testimonials categories
	$testimonial_cat_arr = get_terms('testimonialcats', 'hide_empty=0&hierarchical=0&parent=0');
	$testimonial_cat_select = array();
	$testimonial_cat_select[''] = '';
	
	foreach($testimonial_cat_arr as $testimonial_cat)
	{
		$testimonial_cat_select[$testimonial_cat->slug] = $testimonial_cat->name;
	}
	
	//Get all button colors options
	$button_color_arr = array(
		'black' => 'black',
		'grey' => 'grey',
		'blue' => 'blue',
		'yellow' => 'yellow',
		'red' => 'red',
		'orange' => 'orange',
		'dark blue' => 'dark blue',
		'green' => 'green',
		'emerald' => 'emerald',
		'pink' => 'pink',
		'purple' => 'purple',
	);

	//Begin shortcode array
	$shortcodes = array(
		'dropcap' => array(
			'name' => 'Dropcap',
			'attr' => array(),
			'desc' => array(),
			'content' => TRUE,
		),
		'quote' => array(
			'name' => 'Quote Text',
			'attr' => array(),
			'desc' => array(),
			'content' => TRUE,
		),
		'tg_button' => array(
			'name' => 'Button',
			'attr' => array(
				'href' => 'text',
				'color' => 'select',
				'bg_color' => 'text',
				'text_color' => 'text',
			),
			'desc' => array(
				'href' => 'Enter URL for button',
				'color' => 'Select predefined button colors',
				'bg_color' => 'Enter custom button background color code ex. #4ec380',
				'text_color' => 'Enter custom button text color code ex. #ffffff',
			),
			'options' => $button_color_arr,
			'content' => TRUE,
			'content_text' => 'Enter text on button',
		),
		'tg_alert_box' => array(
			'name' => 'Alert Box',
			'attr' => array(
				'style' => 'select',
			),
			'desc' => array(
				'style' => 'Select content style',
			),
			'options' => array(
				'error' => 'error',
				'success' => 'success',
				'notice' => 'notice',
			),
			'content' => TRUE,
		),
		'one_half' => array(
			'name' => 'One Half Column',
			'attr' => array(),
			'desc' => array(),
			'content' => TRUE,
			'repeat' => 1,
		),
		'one_half_last' => array(
			'name' => 'One Half Last Column',
			'attr' => array(),
			'desc' => array(),
			'content' => TRUE,
			'repeat' => 1,
		),
		'one_third' => array(
			'name' => 'One Third Column',
			'attr' => array(),
			'desc' => array(),
			'content' => TRUE,
			'repeat' => 2,
		),
		'one_third_last' => array(
			'name' => 'One Third Last Column',
			'attr' => array(),
			'desc' => array(),
			'content' => TRUE,
		),
		'two_third' => array(
			'name' => 'Two Third Column',
			'attr' => array(),
			'desc' => array(),
			'content' => TRUE,
		),
		'two_third_last' => array(
			'name' => 'Two Third Last Column',
			'attr' => array(),
			'desc' => array(),
			'content' => TRUE,
		),
		'one_fourth' => array(
			'name' => 'One Fourth Column',
			'attr' => array(),
			'desc' => array(),
			'content' => TRUE,
			'repeat' => 3,
		),
		'one_fifth' => array(
			'name' => 'One Fifth Column',
			'attr' => array(),
			'desc' => array(),
			'content' => TRUE,
			'repeat' => 4,
		),
		'one_sixth' => array(
			'name' => 'One Sixth Column',
			'attr' => array(),
			'desc' => array(),
			'content' => TRUE,
			'repeat' => 5,
		),
		'one_half_bg' => array(
			'name' => 'One Half Column With Background',
			'attr' => array(
				'bg' => 'text',
			),
			'desc' => array(
				'bg' => 'Enter background image URL',
			),
			'content' => TRUE,
		),
		'one_third_bg' => array(
			'name' => 'One Third Column With Background',
			'attr' => array(
				'bg' => 'text',
			),
			'desc' => array(
				'bg' => 'Enter background image URL',
			),
			'content' => TRUE,
		),
		'two_third_bg' => array(
			'name' => 'Two Third Column With Background',
			'attr' => array(
				'bg' => 'text',
			),
			'desc' => array(
				'bg' => 'Enter background image URL',
			),
			'content' => TRUE,
		),
		'one_fourth_bg' => array(
			'name' => 'One Fourth Column With Background',
			'attr' => array(
				'bg' => 'text',
			),
			'desc' => array(
				'bg' => 'Enter background image URL',
			),
			'content' => TRUE,
		),
		'tg_map' => array(
			'name' => 'Google Map',
			'attr' => array(
				'type' => 'select',
				'width' => 'text',
				'height' => 'text',
				'lat' => 'text',
				'long' => 'text',
				'zoom' => 'text',
				'popup' => 'text',
			),
			'desc' => array(
				'type' => 'Select map display type',
				'width' => 'Map width in pixels',
				'height' => 'Map height in pixels',
				'lat' => 'Map latitude <a href="http://www.tech-recipes.com/rx/5519/the-easy-way-to-find-latitude-and-longitude-values-in-google-maps/">Find here</a>',
				'long' => 'Map longitude <a href="http://www.tech-recipes.com/rx/5519/the-easy-way-to-find-latitude-and-longitude-values-in-google-maps/">Find here</a>',
				'zoom' => 'Enter zoom number (1-16)',
				'popup' => 'Enter text to display as popup above location on map for example. your company name',
			),
			'content' => FALSE,
			'options' => array(
				'MapTypeId.ROADMAP' => 'Roadmap',
				'MapTypeId.SATELLITE' => 'Satellite',
				'MapTypeId.HYBRID' => 'Hybrid',
				'MapTypeId.TERRAIN' => 'Terrain',
			),
		),
		'googlefont_func' => array(
			'name' => 'Google Font',
			'attr' => array(
				'font' => 'text',
				'fontsize' => 'text',
			),
			'desc' => array(
				'font' => 'Enter Google Web Font Name you want to use',
				'fontsize' => 'Enter font size in pixels',
			),
			'content' => FALSE,
		),
		'tg_gallery_slider' => array(
			'name' => 'Gallery Slider',
			'attr' => array(
				'gallery_id' => 'select',
			),
			'options' => $galleries_select,
			'desc' => array(
				'gallery_id' => 'Select gallery you want to display its images',
			),
			'content' => FALSE,
		),
		'tg_social_icons' => array(
			'name' => 'Social Icons',
			'attr' => array(
				'style' => 'select',
				'size' => 'text',
			),
			'options' => array(
				'dark' => 'Dark',
				'light' => 'Light',
			),
			'desc' => array(
				'style' => 'Select color style for social icons',
				'size' => 'Enter icon size between small and large',
			),
			'content' => FALSE,
		),
		'tg_promo_box' => array(
			'name' => 'Promo Box',
			'attr' => array(
				'title' => 'text',
				'border' => 'text',
				'shadow' => 'select',
				'button_text' => 'text',
				'button_url' => 'text',
			),
			'options' => array(
				0 => 'No Shadow',
				1 => 'Display Shadow',
			),
			'desc' => array(
				'title' => 'Enter promo box title',
				'border' => 'Enter border color code. For example #3facd6',
				'shadow' => 'Select "Display Shadow" if you want to display promo box drop shadow',
				'button_text' => 'Enter promo box button text. For example More Info',
				'button_url' => 'Enter promo box button link URL',
			),
			'content' => TRUE,
		),
		'tg_accordion' => array(
			'name' => 'Accordion & Toggle',
			'attr' => array(
				'title' => 'text',
				'icon' => 'text',
				'close' => 'select',
			),
			'desc' => array(
				'title' => 'Enter Accordion\'s title',
				'icon' => 'Enter icon class name ex. fa-star. <a href="http://fontawesome.io/cheatsheet/">See all possible here</a>',
				'close' => 'Select default status (close or open)',
			),
			'content' => TRUE,
			'options' => array(
				0 => 'Open',
				1 => 'Close',
			),
		),
		'tg_image' => array(
			'name' => 'Image Animation',
			'attr' => array(
				'src' => 'text',
				'animation' => 'select',
			),
			'desc' => array(
				'src' => 'Enter image URL',
				'animation' => 'Select animation type',
			),
			'content' => TRUE,
			'options' => array(
				'slideRight' => 'Slide Right',
				'slideLeft' => 'Slide Left',
				'slideUp' => 'Slide Up',
				'fadeIn' => 'Fade In',
			),
			'content' => FALSE,
		),
		'tg_divider' => array(
			'name' => 'Divider',
			'attr' => array(
				'style' => 'select',
			),
			'desc' => array(
				'style' => 'Select HR divider style',
			),
			'options' => array(
				'normal' => 'Normal',
				'thick' => 'Thick',
				'dotted' => 'Dotted',
				'dashed' => 'Dashed',
				'faded' => 'Faded',
				'totop' => 'Go To Top',
			),
			'content' => FALSE,
		),
		'tg_teaser' => array(
			'name' => 'Image Teaser',
			'attr' => array(
				'columns' => 'select',
				'image' => 'text',
				'title' => 'text',
				'align' => 'text',
			),
			'desc' => array(
				'columns' => 'Select ligthbox content type',
				'image' => 'Enter full image URL',
				'title' => 'Enter teaser title',
				'align' => 'Enter teaser content text align from left, center and right',
			),
			'options' => array(
				'one' => 'Fullwidth',
				'one_half' => 'One Half',
				'one_half last' => 'One Half Last',
				'one_third' => 'One Third',
				'one_half last' => 'One Third Last',
				'one_fourth' => 'One Fourth',
				'one_fourth last' => 'One Fourth Last',
			),
			'content' => TRUE,
		),
		'tg_lightbox' => array(
			'name' => 'Media Lightbox',
			'attr' => array(
				'type' => 'select',
				'src' => 'text',
				'href' => 'text',
				'vimeo_id' => 'text',
				'youtube_id' => 'text',
			),
			'desc' => array(
				'type' => 'Select ligthbox content type',
				'src' => 'Enter lightbox preview image URL',
				'href' => 'If you selected "Image". Enter full image URL here',
				'vimeo_id' => 'If you selected "Vimeo". Enter Vimeo video ID here ex. 82095744',
				'youtube_id' => 'If you selected "Youtube". Enter Youtube video ID here ex. hT_nvWreIhg',
			),
			'content' => TRUE,
			'options' => array(
				'image' => 'Image',
				'vimeo' => 'Vimeo',
				'youtube' => 'Youtube',
			),
			'content' => FALSE,
		),
		'tg_youtube' => array(
			'name' => 'Youtube Video',
			'attr' => array(
				'width' => 'text',
				'height' => 'text',
				'video_id' => 'text',
			),
			'desc' => array(
				'width' => 'Enter video width in pixels',
				'height' => 'Enter video height in pixels',
				'video_id' => 'Enter Youtube video ID here ex. hT_nvWreIhg',
			),
			'content' => FALSE,
		),
		'tg_vimeo' => array(
			'name' => 'Vimeo Video',
			'attr' => array(
				'width' => 'text',
				'height' => 'text',
				'video_id' => 'text',
			),
			'desc' => array(
				'width' => 'Enter video width in pixels',
				'height' => 'Enter video height in pixels',
				'video_id' => 'Enter Vimeo video ID here ex. 82095744',
			),
			'content' => FALSE,
		),
		'tg_animate_counter' => array(
			'name' => 'Animated Counter',
			'attr' => array(
				'start' => 'text',
				'end' => 'text',
				'fontsize' => 'text',
				'fontcolor' => 'text',
				'count_subject' => 'text',
			),
			'desc' => array(
				'start' => 'Enter start number ex. 0',
				'end' => 'Enter end number ex. 100',
				'fontsize' => 'Enter counter number font size in pixel ex. 38',
				'fontcolor' => 'Enter counter number font color code ex. #000000',
				'count_subject' => 'Enter count subject ex. followers',
			),
			'content' => TRUE,
		),
		'tg_animate_bar' => array(
			'name' => 'Animated Progress Bar',
			'attr' => array(
				'percent' => 'text',
				'color' => 'text',
			),
			'desc' => array(
				'percent' => 'Enter number of percent value (maximum 100)',
				'color' => 'Enter progress background color code ex. #000000',
			),
			'content' => TRUE,
		),
		'tg_animate_circle' => array(
			'name' => 'Animated Circle',
			'attr' => array(
				'percent' => 'text',
				'dimension' => 'text',
				'width' => 'text',
				'color' => 'text',
				'fontsize' => 'text',
				'subject' => 'text',
			),
			'desc' => array(
				'percent' => 'Enter percent completion number ex. 90',
				'dimension' => 'Enter circle dimension ex. 200',
				'width' => 'Enter circle border width ex. 10',
				'color' => 'Enter circle border color code ex. #000000',
				'fontsize' => 'Enter title font size in pixel ex. 38',
				'subject' => 'Enter circle subject info ex. completion',
			),
			'content' => TRUE,
		),
		'tg_pricing' => array(
			'name' => 'Pricing',
			'attr' => array(
				'columns' => 'select',
				'items' => 'text',
			),
			'desc' => array(
				'columns' => 'Select Number of Pricing Columns',
				'items' => 'Enter number of items you want to display',
			),
			'content' => TRUE,
			'options' => array(
				2 => '2 Columns',
				3 => '3 Columns',
				4 => '4 Columns',
			),
			'content' => FALSE,
		),
		'ppb_client_carousel' => array(
			'name' => 'Client Logo Carousel',
			'attr' => array(
				'cat' => 'select',
				'items' => 'text',
			),
			'options' => $client_cat_select,
			'desc' => array(
				'cat' => 'Select client category you want to display its contents',
				'items' => 'Enter number of items you want to display',
			),
			'content' => FALSE,
		),
		'tg_team' => array(
			'name' => 'Team',
			'attr' => array(
				'columns' => 'select',
				'items' => 'text',
			),
			'desc' => array(
				'columns' => 'Select Number of Columns to display',
				'items' => 'Enter number of items you want to display',
			),
			'content' => TRUE,
			'options' => array(
				2 => '2 Columns',
				3 => '3 Columns',
				4 => '4 Columns',
			),
			'content' => FALSE,
		),
		'tg_testimonial' => array(
			'name' => 'Testimonials',
			'attr' => array(
				'columns' => 'select',
				'items' => 'text',
			),
			'desc' => array(
				'columns' => 'Select Number of Columns to display',
				'items' => 'Enter number of items you want to display',
			),
			'options' => array(
				2 => '2 Columns',
				3 => '3 Columns',
				4 => '4 Columns',
			),
			'content' => FALSE,
		),
		'tg_testimonial_slider' => array(
			'name' => 'Testimonials Slider',
			'attr' => array(
				'cat' => 'select',
				'items' => 'text',
			),
			'desc' => array(
				'cat' => 'Select testimonials category you want to display its contents',
				'items' => 'Enter number of items you want to display',
			),
			'options' => $testimonial_cat_select,
			'content' => FALSE,
		),
		'tg_header' => array(
			'name' => 'Header',
			'attr' => array(
				'title' => 'text',
				'subtitle' => 'text',
			),
			'desc' => array(
				'title' => 'Enter header title',
				'subtitle' => 'Enter header subtitle',
			),
			'content' => FALSE,
		),
		'tg_program' => array(
			'name' => 'Tour Program',
			'attr' => array(
				'title' => 'text',
				'place' => 'text',
			),
			'desc' => array(
				'title' => 'Enter title of tour program ex. Day 1',
				'subtitle' => 'Enter tour program places ex. Rome, Venice',
			),
			'content' => TRUE,
		),
	);
	
	aasort($shortcodes,"name");

?>
<script>
function nl2br (str, is_xhtml) {   
	var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';    
	return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
}

jQuery(document).ready(function(){ 
	jQuery('#shortcode_select').change(function() {
  		var target = jQuery(this).val();
  		jQuery('.rm_section').hide()
  		jQuery('#div_'+target).fadeIn()
	});	
	
	jQuery('.code_area').click(function() { 
		document.getElementById(jQuery(this).attr('id')).focus();
    	document.getElementById(jQuery(this).attr('id')).select();
	});
	
	jQuery('.shortcode_button').click(function() { 
		var target = jQuery(this).attr('id');
		var gen_shortcode = '';
  		gen_shortcode+= '['+target;
  		
  		if(jQuery('#'+target+'_attr_wrapper .attr').length > 0)
  		{
  			jQuery('#'+target+'_attr_wrapper .attr').each(function() {
				gen_shortcode+= ' '+jQuery(this).attr('name')+'="'+jQuery(this).val()+'"';
			});
		}
		
		gen_shortcode+= ']';
		
		if(jQuery('#'+target+'_content').length > 0)
  		{
  			gen_shortcode+= jQuery('#'+target+'_content').val()+'[/'+target+']';
  			gen_shortcode+= '\n';
  			
  			var repeat = jQuery('#'+target+'_content_repeat').val();
  			for (count=1;count<=repeat;count=count+1)
			{
				if(count<repeat)
				{
					gen_shortcode+= '['+target+']';
					gen_shortcode+= jQuery('#'+target+'_content').val()+'[/'+target+']';
					gen_shortcode+= '\n';
				}
				else
				{
					gen_shortcode+= '['+target+'_last]';
					gen_shortcode+= jQuery('#'+target+'_content').val()+'[/'+target+'_last]';
					gen_shortcode+= '\n';
				}
			}
  		}
  		jQuery('#'+target+'_code').val(gen_shortcode);
  		jQuery('#pp-insert-to-post').attr('rel', '#'+target+'_code');
  		
  		jQuery("#"+target+"-pp-insert-to-post").click(function() { 
			var current_id = jQuery(this).attr('rel');
			var current_code = jQuery('#'+target+'_code').val();
			
			tinyMCE.activeEditor.selection.setContent(nl2br(current_code));
		});
	});
});
</script>

	<div style="padding:20px 10px 10px 10px">
	<?php
		if(!empty($shortcodes))
		{
	?>
			<strong>Select Shortcode</strong><hr class="pp_widget_hr">
			<div class="pp_widget_description">Please select short code from list below then enter short code attributes and click "Generate Shortcode". <a href="<?php echo THEMEDEMOURL; ?>" target="_blank">Go to demo site</a> and click "Shortcode" to see all short codes available and their sample code.</div>
			<br/>
			<select id="shortcode_select">
				<option value="">(no short code selected)</option>
			
	<?php
			foreach($shortcodes as $shortcode_name => $shortcode)
			{
				$shortcode_key = $shortcode_name;
				
				if(isset($shortcodes[$shortcode_name]['name']))
				{
					$shortcode_name = $shortcodes[$shortcode_name]['name'];
				}
	?>
	
			<option value="<?php echo $shortcode_key; ?>"><?php echo $shortcode_name; ?></option>
	
	<?php
			}
	?>
			</select>
	<?php
		}
	?>
	
	<br/><br/>
	
	<?php
		if(!empty($shortcodes))
		{
			foreach($shortcodes as $shortcode_name => $shortcode)
			{
	?>
	
			<div id="div_<?php echo $shortcode_name; ?>" class="rm_section" style="display:none">
				<div style="width:47%;float:left">
			
				<div class="rm_title">
					<h3><?php echo ucfirst($shortcode_name); ?></h3>
					<div class="clearfix"></div>
				</div>
				
				<div class="rm_text" style="padding: 10px 0 20px 0">
				
				<!-- img src="<?php echo $plugin_url.'/'.$shortcode_name.'.png'; ?>" alt=""/><br/><br/><br/ -->
				
				<?php
					if(isset($shortcode['content']) && $shortcode['content'])
					{
						if(isset($shortcode['content_text']))
						{
							$content_text = $shortcode['content_text'];
						}
						else
						{
							$content_text = 'Your Content';
						}
				?>
				
				<strong><?php echo $content_text; ?>:</strong><br/><br/>
				<?php if(isset($shortcode['repeat'])) { ?>
					<input type="hidden" id="<?php echo $shortcode_name; ?>_content_repeat" value="<?php echo $shortcode['repeat']; ?>"/>
				<?php } ?>
				<textarea id="<?php echo $shortcode_name; ?>_content" style="width:100%;height:70px" rows="3" wrap="off"></textarea><br/><br/>
				
				<?php
					}
				?>
			
				<?php
					if(isset($shortcode['attr']) && !empty($shortcode['attr']))
					{
				?>
						
						<div id="<?php echo $shortcode_name; ?>_attr_wrapper">
						
				<?php
						foreach($shortcode['attr'] as $attr => $type)
						{
				?>
				
							<?php echo '<strong>'.ucfirst($attr).'</strong>: '.$shortcode['desc'][$attr]; ?><br/><br/>
							
							<?php
								switch($type)
								{
									case 'text':
							?>
							
									<input type="text" id="<?php echo $shortcode_name; ?>_text" style="width:100%" class="attr" name="<?php echo $attr; ?>"/>
							
							<?php
									break;
									
									case 'select':
							?>
							
									<select id="<?php echo $shortcode_name; ?>_select" style="width:100%" class="attr" name="<?php echo $attr; ?>">
									
										<?php
											if(isset($shortcode['options']) && !empty($shortcode['options']))
											{
												foreach($shortcode['options'] as $select_key => $option)
												{
										?>
										
													<option value="<?php echo $select_key; ?>"><?php echo $option; ?></option>
										
										<?php	
												}
											}
										?>							
									
									</select>
							
							<?php
									break;
								}
							?>
							
							<br/><br/>
				
				<?php
						} //end attr foreach
				?>
				
						</div>
				
				<?php
					}
				?>
				
				</div>
				
				</div>
				
				<div style="width:47%;float:right">
				
				<strong>Shortcode:</strong><br/><br/>
				<textarea id="<?php echo $shortcode_name; ?>_code" style="width:100%;height:200px" rows="3" readonly="readonly" class="code_area" wrap="off"></textarea>
				
				<br/><br/>
				<input type="button" id="<?php echo $shortcode_name; ?>" value="Generate Shortcode" class="button shortcode_button button-primary"/>
				</div>
				
				<br style="clear:both"/>
			</div>
	
	<?php
			} //end shortcode foreach
		}
	?>
	
</div>

<?php

}

/*
	End Create Shortcode Generator Options
*/

?>