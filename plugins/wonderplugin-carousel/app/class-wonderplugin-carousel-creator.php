<?php

class WonderPlugin_Carousel_Creator {

	private $parent_view, $list_table;
	
	function __construct($parent) {
		
		$this->parent_view = $parent;
	}
	
	function render( $id, $config, $thumbnailsize ) {
		
		?>
		
		<h3><?php _e( 'General Options', 'wonderplugin_carousel' ); ?></h3>
		
		<div id="wonderplugin-carousel-id" style="display:none;"><?php echo $id; ?></div>
		
		<?php 
		$config = str_replace('\\\"', '"', $config);
		$config = str_replace("\\\'", "'", $config);
		$config = str_replace("<", "&lt;", $config);
		$config = str_replace(">", "&gt;", $config);
		$config = str_replace("\\\\", "\\", $config);
		$config = str_replace("&quot;", "\&quot;", $config);		
		?>
		
		<div id="wonderplugin-carousel-id-config" style="display:none;"><?php echo $config; ?></div>
		<div id="wonderplugin-carousel-pluginfolder" style="display:none;"><?php echo WONDERPLUGIN_CAROUSEL_URL; ?></div>
		<div id="wonderplugin-carousel-jsfolder" style="display:none;"><?php echo WONDERPLUGIN_CAROUSEL_URL . 'engine/'; ?></div>
		<div id="wonderplugin-carousel-viewadminurl" style="display:none;"><?php echo admin_url('admin.php?page=wonderplugin_carousel_show_item'); ?></div>
		<div id="wonderplugin-carousel-wp-history-media-uploader" style="display:none;"><?php echo ( function_exists("wp_enqueue_media") ? "0" : "1"); ?></div>
		<div id="wonderplugin-carousel-thumbnailsize" style="display:none;"><?php echo $thumbnailsize; ?></div>
		<div id="wonderplugin-carousel-ajaxnonce" style="display:none;"><?php echo wp_create_nonce( 'wonderplugin-carousel-ajaxnonce' ); ?></div>
		<div id="wonderplugin-carousel-saveformnonce" style="display:none;"><?php wp_nonce_field('wonderplugin-carousel', 'wonderplugin-carousel-saveform'); ?></div>
		<?php 
			$cats = get_categories();
			$catlist = array();
			foreach ( $cats as $cat )
			{
				$catlist[] = array(
						'ID' => $cat->cat_ID,
						'cat_name' => $cat ->cat_name
				);
			}
		?>
		<div id="wonderplugin-carousel-catlist" style="display:none;"><?php echo json_encode($catlist); ?></div>
	
		<?php 
		$custom_post_types = get_post_types( array('_builtin' => false), 'objects' );
	
		$custom_post_list = array();
		foreach($custom_post_types as $custom_post)
		{
			$custom_post_list[] = array(
					'name' => $custom_post->name,
					'taxonomies' => array()
				);
		}

		foreach($custom_post_list as &$custom_post)
		{
			$taxonomies = get_object_taxonomies($custom_post['name'], 'objects');			
			if (!empty($taxonomies))
			{
				
				$taxonomies_list = array();
				foreach($taxonomies as $taxonomy)
				{
					$terms = get_terms($taxonomy->name);
					
					$terms_list = array();
					foreach($terms as $term)
					{
						$terms_list[] = array(
								'name' => $term->name,
								'slug' => $term->slug
							);
					}

					$taxonomies_list[] = array(
							'name' => $taxonomy->name,
							'terms' => $terms_list
						);
				}
				
				$custom_post['taxonomies'] = $taxonomies_list;
			}
		}
		?>
		<div id="wonderplugin-carousel-custompostlist" style="display:none;"><?php echo json_encode($custom_post_list); ?></div>
		
		<?php 
			$pages = get_pages();
			$pagelist = array();
			foreach ( $pages as $page ) 
			{
				$pagelist[] = array(
					'ID' => $page->ID,
					'post_author' => get_the_author_meta('display_name', $page->post_author),
					'post_title' => $page->post_title,
					'post_level' => $page->post_parent ? count(get_post_ancestors($page->ID)) : 0
				);
			}
		?>
		<div id="wonderplugin-carousel-pagelist" style="display:none;"><?php echo json_encode($pagelist); ?></div>
		
		<div style="margin:0 12px;">
		<table class="wonderplugin-form-table">
			<tr>
				<th><?php _e( 'Name', 'wonderplugin_carousel' ); ?></th>
				<td><input name="wonderplugin-carousel-name" type="text" id="wonderplugin-carousel-name" value="My Carousel" class="regular-text" /></td>
			</tr>
		</table>
		</div>
		
		<h3><?php _e( 'Designing', 'wonderplugin_carousel' ); ?></h3>
		
		<div style="margin:0 12px;">
		<ul class="wonderplugin-tab-buttons" id="wonderplugin-carousel-toolbar">
			<li class="wonderplugin-tab-button step1 wonderplugin-tab-buttons-selected"><?php _e( 'Images & Videos', 'wonderplugin_carousel' ); ?></li>
			<li class="wonderplugin-tab-button step2"><?php _e( 'Skins', 'wonderplugin_carousel' ); ?></li>
			<li class="wonderplugin-tab-button step3"><?php _e( 'Options', 'wonderplugin_carousel' ); ?></li>
			<li class="wonderplugin-tab-button step4"><?php _e( 'Preview', 'wonderplugin_carousel' ); ?></li>
			<li class="laststep"><input class="button button-primary" type="button" value="<?php _e( 'Save & Publish', 'wonderplugin_carousel' ); ?>"></input></li>
		</ul>
				
		<ul class="wonderplugin-tabs" id="wonderplugin-carousel-tabs">
			<li class="wonderplugin-tab wonderplugin-tab-selected">	
			
				<div class="wonderplugin-toolbar">	
					<input type="button" class="button" id="wonderplugin-add-image" value="<?php _e( 'Add Image', 'wonderplugin_carousel' ); ?>" />
					<input type="button" class="button" id="wonderplugin-add-video" value="<?php _e( 'Add Video', 'wonderplugin_carousel' ); ?>" />
					<input type="button" class="button" id="wonderplugin-add-youtube" value="<?php _e( 'Add YouTube', 'wonderplugin_carousel' ); ?>" />
					<input type="button" class="button" id="wonderplugin-add-youtube-playlist" value="<?php _e( 'Add YouTube Playlist', 'wonderplugin_carousel' ); ?>" />
					<input type="button" class="button" id="wonderplugin-add-vimeo" value="<?php _e( 'Add Vimeo', 'wonderplugin_carousel' ); ?>" />
					<input type="button" class="button" id="wonderplugin-add-pdf" value="<?php _e( 'Add PDF', 'wonderplugin_carousel' ); ?>" />
					<input type="button" class="button" id="wonderplugin-add-posts" value="<?php _e( 'Add WordPress Posts', 'wonderplugin_carousel' ); ?>" />
					<input type="button" class="button" id="wonderplugin-add-custompost" value="<?php _e( 'Add WooCommerce / Custom Post Type', 'wonderplugin_carousel' ); ?>" />
					<input type="button" class="button" id="wonderplugin-add-page" value="<?php _e( 'Add WordPress Page', 'wonderplugin_carousel' ); ?>" />
					<label style="float:right;"><input type="button" class="button" id="wonderplugin-deleteall" value="<?php _e( 'Delete All', 'wonderplugin_carousel' ); ?>" /></label>
					<label style="float:right;margin-right:4px;"><input type="button" class="button" id="wonderplugin-reverselist" value="<?php _e( 'Reverse List', 'wonderplugin_carousel' ); ?>" /></label>
					<label style="float:right;padding-top:4px;margin-right:8px;"><input type='checkbox' id='wonderplugin-newestfirst' value='' /> Add new item to the beginning</label>
				</div>
        		
        		<ul class="wonderplugin-table" id="wonderplugin-carousel-media-table">
			    </ul>
			    <div style="clear:both;"></div>
      
			</li>
			<li class="wonderplugin-tab">
				<form>
					<fieldset>
						
						<?php 
						$skins = array(
								"classic" => "Classic",
								"classicwithflip" => "Classic with Flip",
								"gallery" => "Gallery",
								"readmore" => "Read More",
								"hoverover" => "Hover Over",
								"flip" => "Flip",
								"readmorebutton" => "Read More Button",
								"teammember" => "Team Members with Social Media",
								"teammemberflip" => "Team Members with Social Media and Flip Effect",
								"scroller" => "Auto Scroll",
								"textimageslider" => "Text and Image Slider",
								"numbering" => "Numbering",
								"highlight" => "Highlight",
								"textonly" => "Text Only",
								"navigator" => "Navigator",
								"simplicity" => "Simplicity",
								"stylish" => "Stylish",
								"testimonial" => "Testimonial",
								"fashion" => "Fashion",
								"flow" => "Flow - Same Height",
								"navigator" => "Navigator",
								"testimonialcarousel" => "Testimonial Carousel",
								"list" => "List",
								"showcase" => "Showcase",
								"thumbnail" => "Thumbnail",
								"vertical" => "Vertical",
								"rotator" => "Rotator",
								"tworows" => "Two Rows"
								);
						
						$skin_index = 0;
						foreach ($skins as $key => $value) {
							if ($skin_index > 0 && $skin_index % 3 == 0)
								echo '<div style="clear:both;"></div>';
							$skin_index++;
						?>
							<div class="wonderplugin-tab-skin">
							<label><input type="radio" name="wonderplugin-carousel-skin" value="<?php echo $key; ?>" selected> <?php echo $value; ?> <br /><img class="selected" src="<?php echo WONDERPLUGIN_CAROUSEL_URL; ?>images/<?php echo $key; ?>.jpg" /></label>
							</div>
						<?php
						}
						?>
						
					</fieldset>
				</form>
			</li>
			<li class="wonderplugin-tab">
			
				<div class="wonderplugin-carousel-options">
					<div class="wonderplugin-carousel-options-menu" id="wonderplugin-carousel-options-menu">
						<div class="wonderplugin-carousel-options-menu-item wonderplugin-carousel-options-menu-item-selected"><?php _e( 'Skin options', 'wonderplugin_carousel' ); ?></div>
						<div class="wonderplugin-carousel-options-menu-item"><?php _e( 'Movement options', 'wonderplugin_carousel' ); ?></div>
						<div class="wonderplugin-carousel-options-menu-item"><?php _e( 'Responsive options', 'wonderplugin_carousel' ); ?></div>
						<div class="wonderplugin-carousel-options-menu-item"><?php _e( 'Content template', 'wonderplugin_carousel' ); ?></div>
						<div class="wonderplugin-carousel-options-menu-item"><?php _e( 'Skin CSS', 'wonderplugin_carousel' ); ?></div>
						<div class="wonderplugin-carousel-options-menu-item"><?php _e( 'Lightbox options', 'wonderplugin_carousel' ); ?></div>
						<div class="wonderplugin-carousel-options-menu-item"><?php _e( 'Advanced options', 'wonderplugin_carousel' ); ?></div>
					</div>
					
					<div class="wonderplugin-carousel-options-tabs" id="wonderplugin-carousel-options-tabs">
					
						<div class="wonderplugin-carousel-options-tab wonderplugin-carousel-options-tab-selected">
							<p class="wonderplugin-carousel-options-tab-title"><?php _e( 'Options will be restored to the default value if you switch to a new skin in the Skins tab.', 'wonderplugin_carousel' ); ?></p>
							<table class="wonderplugin-form-table-noborder">
							
								<tr>
									<th>Width / Height of thumbnail</th>
									<td><label><input name="wonderplugin-carousel-width" type="number" id="wonderplugin-carousel-width" value="300" class="small-text" /> / <input name="wonderplugin-carousel-height" type="number" id="wonderplugin-carousel-height" value="300" class="small-text" /></label>
								</tr>
								
								<tr>
									<th>Thumbnail reszing options</th>
									<td>
									<label><input name='wonderplugin-carousel-fixaspectratio' type='checkbox' id='wonderplugin-carousel-fixaspectratio'  /> Use the aspect ratio for all thumbnail images</label>
									<label><input name='wonderplugin-carousel-centerimage' type='checkbox' id='wonderplugin-carousel-centerimage'  /> Center image</label></p>
									<p><label><input name='wonderplugin-carousel-sameheight' type='checkbox' id='wonderplugin-carousel-sameheight'  /> Display thumbnail images as same height and different width if they have different aspect ratio (for horizontal skins only)</label></p>
									<p><label><input name='wonderplugin-carousel-fitimage' type='checkbox' id='wonderplugin-carousel-fitimage'  /> Fit images into the carousel</label>
									<label><input name='wonderplugin-carousel-fitcenterimage' type='checkbox' id='wonderplugin-carousel-fitcenterimage'  /> Center fitted image</label></p>
									</td>
								</tr>
								
								<tr>
									<th>Initialization</th>
									<td><label><input name='wonderplugin-carousel-hidecontaineroninit' type='checkbox' id='wonderplugin-carousel-hidecontaineroninit'  /> Hide the whole carousel before the initialization finishes.</label>
									<br><label><input name='wonderplugin-carousel-hidecontainerbeforeloaded' type='checkbox' id='wonderplugin-carousel-hidecontainerbeforeloaded'  /> Hide the whole carousel before all carousel images are loaded.</label>
									</td>
								</tr>
								
								<tr>
									<th>Spacing between carousel items (px)</th>
									<td><label><input name="wonderplugin-carousel-spacing" type="number" id="wonderplugin-carousel-spacing" value="8" min="0" class="small-text" /></label></td>
								</tr>
								
								<tr>
									<th>Row number</th>
									<td><label><input name="wonderplugin-carousel-rownumber" type="number" id="wonderplugin-carousel-rownumber" value="1" min="1" class="small-text" /></label></td>
								</tr>
								
								<tr>
									<th>Arrows</th>
									<td><label>
										<select name='wonderplugin-carousel-arrowstyle' id='wonderplugin-carousel-arrowstyle'>
										  <option value="mouseover">Show on mouseover</option>
										  <option value="always">Always show</option>
										  <option value="none">Hide</option>
										</select>
									</label></td>
								</tr>
								<tr>
									<th>Arrow image</th>
									<td>
										<div>
											<div style="float:left;margin-right:12px;">
											<label>
											<img id="wonderplugin-carousel-displayarrowimage" />
											</label>
											</div>
											<div style="float:left;">
											<label>
												<input type="radio" name="wonderplugin-carousel-arrowimagemode" value="custom">
												<span style="display:inline-block;min-width:240px;">Use own image (absolute URL required):</span>
												<input name='wonderplugin-carousel-customarrowimage' type='text' class="regular-text" id='wonderplugin-carousel-customarrowimage' value='' />
											</label>
											<br />
											<label>
												<input type="radio" name="wonderplugin-carousel-arrowimagemode" value="defined">
												<span style="display:inline-block;min-width:240px;">Select from pre-defined images:</span>
												<select name='wonderplugin-carousel-arrowimage' id='wonderplugin-carousel-arrowimage'>
												<?php 
													$arrowimage_list = array("arrows-28-28-0.png", 
															"arrows-32-32-0.png", "arrows-32-32-1.png", "arrows-32-32-2.png", "arrows-32-32-3.png", "arrows-32-32-4.png", 
															"arrows-36-36-0.png", "arrows-36-36-1.png",
															"arrows-36-80-0.png",
															"arrows-42-60-0.png",
															"arrows-48-48-0.png", "arrows-48-48-1.png", "arrows-48-48-2.png", "arrows-48-48-3.png", "arrows-48-48-4.png",
															"arrows-72-72-0.png");
													foreach ($arrowimage_list as $arrowimage)
														echo '<option value="' . $arrowimage . '">' . $arrowimage . '</option>';
												?>
												</select>
											</label>
											</div>
											<div style="clear:both;"></div>
										</div>
										<script language="JavaScript">
										jQuery(document).ready(function(){
											jQuery("input:radio[name=wonderplugin-carousel-arrowimagemode]").click(function(){
												if (jQuery(this).val() == 'custom')
													jQuery("#wonderplugin-carousel-displayarrowimage").attr("src", jQuery('#wonderplugin-carousel-customarrowimage').val());
												else
													jQuery("#wonderplugin-carousel-displayarrowimage").attr("src", "<?php echo WONDERPLUGIN_CAROUSEL_URL . 'engine/'; ?>" + jQuery('#wonderplugin-carousel-arrowimage').val());
											});

											jQuery("#wonderplugin-carousel-arrowimage").change(function(){
												if (jQuery("input:radio[name=wonderplugin-carousel-arrowimagemode]:checked").val() == 'defined')
													jQuery("#wonderplugin-carousel-displayarrowimage").attr("src", "<?php echo WONDERPLUGIN_CAROUSEL_URL . 'engine/'; ?>" + jQuery(this).val());
												var arrowsize = jQuery(this).val().split("-");
												if (arrowsize.length > 2)
												{
													if (!isNaN(arrowsize[1]))
														jQuery("#wonderplugin-carousel-arrowwidth").val(arrowsize[1]);
													if (!isNaN(arrowsize[2]))
														jQuery("#wonderplugin-carousel-arrowheight").val(arrowsize[2]);
												}
													
											});
										});
										</script>
										<label><span style="display:inline-block;min-width:100px;">Width:</span> <input name='wonderplugin-carousel-arrowwidth' type='text' size="10" id='wonderplugin-carousel-arrowwidth' value='32' /></label>
										<label><span style="display:inline-block;min-width:100px;margin-left:36px;">Height:</span> <input name='wonderplugin-carousel-arrowheight' type='text' size="10" id='wonderplugin-carousel-arrowheight' value='32' /></label><br />										
									</td>
								</tr>
								
								<tr>
									<th>Navigation</th>
									<td><label>
										<select name='wonderplugin-carousel-navstyle' id='wonderplugin-carousel-navstyle'>
										  <option value="bullets">Bullets</option>
										  <option value="numbering">Numbering</option>
										  <option value="none">None</option>
										</select>
									</label>
									<label><span style="display:inline-block;">Width:</span> <input name='wonderplugin-carousel-navwidth' type='number' class="small-text" id='wonderplugin-carousel-navwidth' value='32' /></label>
									<label><span style="display:inline-block;margin-left:12px;">Height:</span> <input name='wonderplugin-carousel-navheight' type='number' class="small-text" id='wonderplugin-carousel-navheight' value='32' /></label>										
									<label><span style="display:inline-block;margin-left:12px;">Spacing:</span> <input name='wonderplugin-carousel-navspacing' type='number' class="small-text" id='wonderplugin-carousel-navspacing' value='32' /></label>	
									</td>
								</tr>
								<tr>
									<th>Bullet image</th>
									<td>
										<div>
											<div style="float:left;margin-right:12px;margin-top:4px;">
											<label>
											<img id="wonderplugin-carousel-displaynavimage" />
											</label>
											</div>
											<div style="float:left;">
											<label>
												<input type="radio" name="wonderplugin-carousel-navimagemode" value="custom">
												<span style="display:inline-block;min-width:240px;">Use own image (absolute URL required):</span>
												<input name='wonderplugin-carousel-customnavimage' type='text' class="regular-text" id='wonderplugin-carousel-customnavimage' value='' />
											</label>
											<br />
											<label>
												<input type="radio" name="wonderplugin-carousel-navimagemode" value="defined">
												<span style="display:inline-block;min-width:240px;">Select from pre-defined images:</span>
												<select name='wonderplugin-carousel-navimage' id='wonderplugin-carousel-navimage'>
												<?php 
													$navimage_list = array("bullet-12-12-0.png", "bullet-12-12-1.png", 
															"bullet-16-16-0.png", "bullet-16-16-1.png", "bullet-16-16-2.png", "bullet-16-16-3.png", 
															"bullet-20-20-0.png", "bullet-20-20-1.png", 
															"bullet-24-24-0.png", "bullet-24-24-1.png", "bullet-24-24-2.png", "bullet-24-24-3.png", "bullet-24-24-4.png", "bullet-24-24-5.png", "bullet-24-24-6.png");
													foreach ($navimage_list as $navimage)
														echo '<option value="' . $navimage . '">' . $navimage . '</option>';
												?>
												</select>
											</label>
											</div>
											<div style="clear:both;"></div>
										</div>
										<script language="JavaScript">
										jQuery(document).ready(function(){
											jQuery("input:radio[name=wonderplugin-carousel-navimagemode]").click(function(){
												if (jQuery(this).val() == 'custom')
													jQuery("#wonderplugin-carousel-displaynavimage").attr("src", jQuery('#wonderplugin-carousel-customnavimage').val());
												else
													jQuery("#wonderplugin-carousel-displaynavimage").attr("src", "<?php echo WONDERPLUGIN_CAROUSEL_URL . 'engine/'; ?>" + jQuery('#wonderplugin-carousel-navimage').val());
											});

											jQuery("#wonderplugin-carousel-navimage").change(function(){
												if (jQuery("input:radio[name=wonderplugin-carousel-navimagemode]:checked").val() == 'defined')
													jQuery("#wonderplugin-carousel-displaynavimage").attr("src", "<?php echo WONDERPLUGIN_CAROUSEL_URL . 'engine/'; ?>" + jQuery(this).val());
												var navsize = jQuery(this).val().split("-");
												if (navsize.length > 2)
												{
													if (!isNaN(navsize[1]))
														jQuery("#wonderplugin-carousel-navwidth").val(navsize[1]);
													if (!isNaN(navsize[2]))
														jQuery("#wonderplugin-carousel-navheight").val(navsize[2]);
												}
													
											});
										});
										</script>									
										</td>
								</tr>
								
								<tr>
									<th>Hover overlay</th>
									<td>
										<div>
											<div>
											<label><input name='wonderplugin-carousel-showhoveroverlay' type='checkbox' id='wonderplugin-carousel-showhoveroverlay'  /> Show hover overlay image</label>
											</div>
											<div style="float:left;margin-right:12px;">
											<label>
											<img id="wonderplugin-carousel-displayhoveroverlayimage" />
											</label>
											</div>
											<div style="float:left;">
											<label>
												<input type="radio" name="wonderplugin-carousel-hoveroverlayimagemode" value="custom">
												<span style="display:inline-block;min-width:240px;">Use own image (absolute URL required):</span>
												<input name='wonderplugin-carousel-customhoveroverlayimage' type='text' class="regular-text" id='wonderplugin-carousel-customhoveroverlayimage' value='' />
											</label>
											<br />
											<label>
												<input type="radio" name="wonderplugin-carousel-hoveroverlayimagemode" value="defined">
												<span style="display:inline-block;min-width:240px;">Select from pre-defined images:</span>
												<select name='wonderplugin-carousel-hoveroverlayimage' id='wonderplugin-carousel-hoveroverlayimage'>
												<?php 
													$hoveroverlayimage_list = array("hoveroverlay-64-64-0.png", "hoveroverlay-64-64-1.png", "hoveroverlay-64-64-2.png", "hoveroverlay-64-64-3.png", "hoveroverlay-64-64-4.png", "hoveroverlay-64-64-5.png", "hoveroverlay-64-64-6.png", "hoveroverlay-64-64-7.png", "hoveroverlay-64-64-8.png", "hoveroverlay-64-64-9.png");
													foreach ($hoveroverlayimage_list as $hoveroverlayimage)
														echo '<option value="' . $hoveroverlayimage . '">' . $hoveroverlayimage . '</option>';
												?>
												</select>
											</label>
											</div>
											<div style="clear:both;"></div>
										</div>
										<script language="JavaScript">
										jQuery(document).ready(function(){
											jQuery("input:radio[name=wonderplugin-carousel-hoveroverlayimagemode]").click(function(){
												if (jQuery(this).val() == 'custom')
													jQuery("#wonderplugin-carousel-displayhoveroverlayimage").attr("src", jQuery('#wonderplugin-carousel-customhoveroverlayimage').val());
												else
													jQuery("#wonderplugin-carousel-displayhoveroverlayimage").attr("src", "<?php echo WONDERPLUGIN_CAROUSEL_URL . 'engine/'; ?>" + jQuery('#wonderplugin-carousel-hoveroverlayimage').val());
											});
											jQuery("#wonderplugin-carousel-hoveroverlayimage").change(function(){
												if (jQuery("input:radio[name=wonderplugin-carousel-hoveroverlayimagemode]:checked").val() == 'defined')
													jQuery("#wonderplugin-carousel-displayhoveroverlayimage").attr("src", "<?php echo WONDERPLUGIN_CAROUSEL_URL . 'engine/'; ?>" + jQuery(this).val());
											});
										});
										</script>
										<label><input name='wonderplugin-carousel-showhoveroverlayalways' type='checkbox' id='wonderplugin-carousel-showhoveroverlayalways'  /> Show hover image for both Lightbox and weblink</label>
										<br><label><input name='wonderplugin-carousel-hidehoveroverlayontouch' type='checkbox' id='wonderplugin-carousel-hidehoveroverlayontouch'  /> Do not show hover image on touch screen</label>									
									</td>
								</tr>
								
								<tr>
									<th>Video play button</th>
									<td>
										<div>
											<div>
											<label><input name='wonderplugin-carousel-showplayvideo' type='checkbox' id='wonderplugin-carousel-showplayvideo'  /> Show play button on video item</label>
											</div>
											<div style="float:left;margin-right:12px;">
											<label>
											<img id="wonderplugin-carousel-displayplayvideoimage" />
											</label>
											</div>
											<div style="float:left;">
											<label>
												<input type="radio" name="wonderplugin-carousel-playvideoimagemode" value="custom">
												<span style="display:inline-block;min-width:240px;">Use own image (absolute URL required):</span>
												<input name='wonderplugin-carousel-customplayvideoimage' type='text' class="regular-text" id='wonderplugin-carousel-customplayvideoimage' value='' />
											</label>
											<br />
											<label>
												<input type="radio" name="wonderplugin-carousel-playvideoimagemode" value="defined">
												<span style="display:inline-block;min-width:240px;">Select from pre-defined images:</span>
												<select name='wonderplugin-carousel-playvideoimage' id='wonderplugin-carousel-playvideoimage'>
												<?php 
													$playvideoimage_list = array("playvideo-64-64-0.png", "playvideo-64-64-1.png", "playvideo-64-64-2.png", "playvideo-64-64-3.png", "playvideo-64-64-4.png", "playvideo-64-64-5.png");
													foreach ($playvideoimage_list as $playvideoimage)
														echo '<option value="' . $playvideoimage . '">' . $playvideoimage . '</option>';
												?>
												</select>
											</label>
											</div>
											<div style="clear:both;"></div>
										</div>
										<script language="JavaScript">
										jQuery(document).ready(function(){
											jQuery("input:radio[name=wonderplugin-carousel-playvideoimagemode]").click(function(){
												if (jQuery(this).val() == 'custom')
													jQuery("#wonderplugin-carousel-displayplayvideoimage").attr("src", jQuery('#wonderplugin-carousel-customplayvideoimage').val());
												else
													jQuery("#wonderplugin-carousel-displayplayvideoimage").attr("src", "<?php echo WONDERPLUGIN_CAROUSEL_URL . 'engine/'; ?>" + jQuery('#wonderplugin-carousel-playvideoimage').val());
											});
											jQuery("#wonderplugin-carousel-playvideoimage").change(function(){
												if (jQuery("input:radio[name=wonderplugin-carousel-playvideoimagemode]:checked").val() == 'defined')
													jQuery("#wonderplugin-carousel-displayplayvideoimage").attr("src", "<?php echo WONDERPLUGIN_CAROUSEL_URL . 'engine/'; ?>" + jQuery(this).val());
											});
										});
										</script>	
										<p>Play button position:<label>
										<select name='wonderplugin-carousel-playvideoimagepos' id='wonderplugin-carousel-playvideoimagepos'>
										  <option value="center">center</option>
										  <option value="topleft">topleft</option>
										  <option value="topright">topright</option>
										  <option value="bottomleft">bottomleft</option>
										  <option value="bottomright">bottomright</option>
										</select></label></p>			
									</td>
								</tr>
								
								<tr>
									<th>Add extra tags or attributes to IMG elements</th>
									<td><label><input name="wonderplugin-carousel-imgextraprops" type="text" id="wonderplugin-carousel-imgextraprops" value="" class="regular-text" /></label></td>
								</tr>
								
								<tr>
									<th>Add extra tags or attributes to A elements</th>
									<td><label><input name="wonderplugin-carousel-aextraprops" type="text" id="wonderplugin-carousel-aextraprops" value="" class="regular-text" /></label></td>
								</tr>
								
								<tr>
									<th>&lt;img&gt; tags</th>
									<td><label><input name='wonderplugin-carousel-showimgtitle' type='checkbox' id='wonderplugin-carousel-showimgtitle' value='' /> Add the following text as &lt;img&gt; tag title attribute: </label>
									<select name='wonderplugin-carousel-imgtitle' id='wonderplugin-carousel-imgtitle'>
										  <option value="title">Title</option>
										  <option value="description">Description</option>
										  <option value="alt">Alt</option>
										</select>
									</td>
								</tr>
								
								<tr>
									<th>WooCommerce carousel</th>
									<td><label><input name='wonderplugin-carousel-addwoocommerceclass' type='checkbox' id='wonderplugin-carousel-addwoocommerceclass' value='' /> Add class name woocommerce to WordPress custom post type carousels</label>
									</td>
								</tr>
							</table>
						</div>
						
						<div class="wonderplugin-carousel-options-tab">
							<table class="wonderplugin-form-table-noborder">
								
								<tr>
									<th>Play mode</th>
									<td><p><label><input name='wonderplugin-carousel-autoplay' type='checkbox' id='wonderplugin-carousel-autoplay'  /> Auto play - direction:</label>
									<select name='wonderplugin-carousel-autoplaydir' id='wonderplugin-carousel-autoplaydir'>
										  <option value="left">Left</option>
										  <option value="right">Right</option>
										</select></p>
									<p><label><input name='wonderplugin-carousel-random' type='checkbox' id='wonderplugin-carousel-random'  /> Random</label></p>
									<p><label><input name='wonderplugin-carousel-pauseonmouseover' type='checkbox' id='wonderplugin-carousel-pauseonmouseover'  /> Pause on mouse over</label></p>
									<p><label><input name='wonderplugin-carousel-circular' type='checkbox' id='wonderplugin-carousel-circular'  /> Circular (loop images) </label></p>
									<p><label><input name='wonderplugin-carousel-donotcircularforless' type='checkbox' id='wonderplugin-carousel-donotcircularforless'  /> Do not circle/loop images when the total number is less than or equal to </label>
									<input name="wonderplugin-carousel-circularlimit" type="number" id="wonderplugin-carousel-circularlimit" value="3" min="0" class="small-text" /></p>
									</td>
								</tr>
								
								<tr>
									<th>Scroll mode</th>
									<td><label>
										<select name='wonderplugin-carousel-scrollmode' id='wonderplugin-carousel-scrollmode'>
										  <option value="page">Page</option>
										  <option value="item">Item</option>
										</select>
									</label></td>
								</tr>
								
								<tr>
									<th>Interval (ms)</th>
									<td><label><input name="wonderplugin-carousel-interval" type="number" id="wonderplugin-carousel-interval" value="3000" min="0" class="small-text" /></label></td>
								</tr>
								
								<tr>
									<th>Transition duration (ms)</th>
									<td><label><input name="wonderplugin-carousel-transitionduration" type="number" id="wonderplugin-carousel-transitionduration" value="1000" min="0" class="small-text" /></label></td>
								</tr>
								
								<tr>
									<th>Continuous playing</th>
									<td><label><input name='wonderplugin-carousel-continuous' type='checkbox' id='wonderplugin-carousel-continuous'  /> Continuous playing</label>
									<br /><label>Duration of moving one item (ms): <input name="wonderplugin-carousel-continuousduration" type="number" id="wonderplugin-carousel-continuousduration" value="2500" min="0" class="small-text" /></label>
									</td>
								</tr>
								
							</table>
						</div>
							
						<div class="wonderplugin-carousel-options-tab">
							<table class="wonderplugin-form-table-noborder">

								<tr>
									<th>Visible items</th>
									<td><label><input name='wonderplugin-carousel-visibleitems' type='number' size="10" id='wonderplugin-carousel-visibleitems' value='3' /></label></td>
								</tr>
								
								<tr>
									<th>Responsive</th>
									<td><label><input name='wonderplugin-carousel-responsive' type='checkbox' id='wonderplugin-carousel-responsive'  /> Responsive</label>
									&nbsp;&nbsp;&nbsp;&nbsp;<label><input name='wonderplugin-carousel-fullwidth' type='checkbox' id='wonderplugin-carousel-fullwidth'  /> Create a full width carousel</label>
									</td>
								</tr>
								
								<tr>
									<th>Responsive mode</th>
									<td>
										<label>
											<input type="radio" name="wonderplugin-carousel-usescreenquery" value="fixedsize">
											Change the number of visible items according to the container size, keep item size unchanged
										</label>
										<br /><br />
										<label>
											<input type="radio" name="wonderplugin-carousel-usescreenquery" value="screensize">
											Change the number of visible items according to the screen size, adjust item size accordingly:
										</label>
										<textarea style="margin-left:16px;" name='wonderplugin-carousel-screenquery' id='wonderplugin-carousel-screenquery' value='' class='large-text' rows="10"></textarea>
									</td>
								</tr>
									
								<tr>
									<th>When the option "Display thumbnail images as same height and different width" is selected</th>
									<td>
									<label><input name='wonderplugin-carousel-sameheightresponsive' type='checkbox' id='wonderplugin-carousel-sameheightresponsive'  /> Change the carousel height on small screens:</label>
									<p>When the screen width is less than (px) <input name='wonderplugin-carousel-sameheightmediumscreen' type='number' id='wonderplugin-carousel-sameheightmediumscreen' value='769' class='small-text' />, change the carosuel height to (px) <input name='wonderplugin-carousel-sameheightmediumheight' type='number' class="small-text"  id='wonderplugin-carousel-sameheightmediumheight' value='200' /></p>
									<p>When the screen width is less than (px) <input name='wonderplugin-carousel-sameheightsmallscreen' type='number' id='wonderplugin-carousel-sameheightsmallscreen' value='415' class='small-text' />, change the carosuel height to (px) <input name='wonderplugin-carousel-sameheightsmallheight' type='number' class="small-text"  id='wonderplugin-carousel-sameheightsmallheight' value='150' /></p>
									</td>
								</tr>		
							</table>
						</div>
						
						<div class="wonderplugin-carousel-options-tab">
							<table class="wonderplugin-form-table-noborder">
								<tr>
									<th>Skin template</th>
									<td><textarea name='wonderplugin-carousel-skintemplate' id='wonderplugin-carousel-skintemplate' value='' class='large-text' rows="20"></textarea></td>
								</tr>
							</table>
						</div>
						
						<div class="wonderplugin-carousel-options-tab">
							<table class="wonderplugin-form-table-noborder">
								<tr>
									<th>Skin CSS</th>
									<td><textarea name='wonderplugin-carousel-skincss' id='wonderplugin-carousel-skincss' value='' class='large-text' rows="20"></textarea></td>
								</tr>
							</table>
						</div>
						
						<div class="wonderplugin-carousel-options-tab" style="padding:24px;">
						
						
						<ul class="wonderplugin-tab-buttons-horizontal" data-panelsid="wonderplugin-lightbox-panels">
							<li class="wonderplugin-tab-button-horizontal wonderplugin-tab-button-horizontal-selected"><?php _e( 'General', 'wonderplugin_carousel' ); ?></li>
							<li class="wonderplugin-tab-button-horizontal"></span><?php _e( 'Video', 'wonderplugin_carousel' ); ?></li>
							<li class="wonderplugin-tab-button-horizontal"></span><?php _e( 'Thumbnails', 'wonderplugin_carousel' ); ?></li>
							<li class="wonderplugin-tab-button-horizontal"></span><?php _e( 'Text', 'wonderplugin_carousel' ); ?></li>
							<li class="wonderplugin-tab-button-horizontal"></span><?php _e( 'Social Media', 'wonderplugin_carousel' ); ?></li>
							<li class="wonderplugin-tab-button-horizontal"></span><?php _e( 'Lightbox Advanced Options', 'wonderplugin_carousel' ); ?></li>
							<div style="clear:both;"></div>
						</ul>
						
						<ul class="wonderplugin-tabs-horizontal" id="wonderplugin-lightbox-panels">
						
							<li class="wonderplugin-tab-horizontal wonderplugin-tab-horizontal-selected">
							<table class="wonderplugin-form-table-noborder">
								<tr>
									<th>General</th>
									<td><label><input name='wonderplugin-carousel-lightboxresponsive' type='checkbox' id='wonderplugin-carousel-lightboxresponsive'  /> Responsive</label>
									<br><label><input name="wonderplugin-carousel-lightboxfullscreenmode" type="checkbox" id="wonderplugin-carousel-lightboxfullscreenmode" /> Display in fullscreen mode (the close button on top right of the web browser)</label>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row">Slideshow</th>
									<td><label><input name="wonderplugin-carousel-lightboxautoslide" type="checkbox" id="wonderplugin-carousel-lightboxautoslide" /> Auto play slideshow</label>
									<label> - slideshow interval (ms): <input name="wonderplugin-carousel-lightboxslideinterval" type="number" min=0 id="wonderplugin-carousel-lightboxslideinterval" value="5000" class="small-text" /></label>
									<br><label><input name="wonderplugin-carousel-lightboximagekeepratio" type="checkbox" id="wonderplugin-carousel-lightboximagekeepratio" /> Keep image aspect ratio</label>
									<br><label><input name="wonderplugin-carousel-lightboxalwaysshownavarrows" type="checkbox" id="wonderplugin-carousel-lightboxalwaysshownavarrows" /> Always show left and right navigation arrows</label>
									<br><label><input name="wonderplugin-carousel-lightboxshowplaybutton" type="checkbox" id="wonderplugin-carousel-lightboxshowplaybutton" /> Show play slideshow button</label>
									<br><label><input name="wonderplugin-carousel-lightboxshowtimer" type="checkbox" id="wonderplugin-carousel-lightboxshowtimer" /> Show line timer for image slideshow</label>
									<br>Timer position: <select name="wonderplugin-carousel-lightboxtimerposition" id="wonderplugin-carousel-lightboxtimerposition">
										  <option value="bottom">Bottom</option>
										  <option value="top">Top</option>
										</select>
									Timer color: <input name="wonderplugin-carousel-lightboxtimercolor" type="text" id="wonderplugin-carousel-lightboxtimercolor" value="#dc572e" class="medium-text" />
									Timer height: <input name="wonderplugin-carousel-lightboxtimerheight" type="number" min=0 id="wonderplugin-carousel-lightboxtimerheight" value="2" class="small-text" />
									Timer opacity: <input name="wonderplugin-carousel-lightboxtimeropacity" type="number" min=0 max=1 step="0.1" id="wonderplugin-carousel-lightboxtimeropacity" value="1" class="small-text" />
									</td>
								</tr>
								<tr valign="top">
									<th scope="row">Overlay</th>
									<td>Color: <input name="wonderplugin-carousel-lightboxoverlaybgcolor" type="text" id="wonderplugin-carousel-lightboxoverlaybgcolor" value="#333" class="medium-text" />
									Opacity: <input name="wonderplugin-carousel-lightboxoverlayopacity" type="number" min=0 max=1 step="0.1" id="wonderplugin-carousel-lightboxoverlayopacity" value="0.9" class="small-text" />
									<label><input name="wonderplugin-carousel-lightboxcloseonoverlay" type="checkbox" id="wonderplugin-carousel-lightboxcloseonoverlay" /> Close the lightbox when clicking on the overlay background</label></td>
								</tr>
								
								<tr valign="top">
									<th scope="row">Background color</th>
									<td><input name="wonderplugin-carousel-lightboxbgcolor" type="text" id="wonderplugin-carousel-lightboxbgcolor" value="#fff" class="medium-text" /></td>
								</tr>
		
								<tr valign="top">
									<th scope="row">Border</th>
									<td>Border radius (px): <input name="wonderplugin-carousel-lightboxborderradius" type="number" min=0 id="wonderplugin-carousel-lightboxborderradius" value="0" class="small-text" />
									Border size (px): <input name="wonderplugin-carousel-lightboxbordersize" type="number" min=0 id="wonderplugin-carousel-lightboxbordersize" value="8" class="small-text" />
									</td>
								</tr>
								<tr>
									<th>Group</th>
									<td><label><input name='wonderplugin-carousel-lightboxnogroup' type='checkbox' id='wonderplugin-carousel-lightboxnogroup'  /> Do not display lightboxes as a group</label>
									</td>
								</tr>
							</table>
							</li>
							
							<li class="wonderplugin-tab-horizontal">
							<table class="wonderplugin-form-table-noborder">
								<tr valign="top">
									<th scope="row">Default volume of MP4/WebM videos</th>
									<td><label><input name="wonderplugin-carousel-lightboxdefaultvideovolume" type="number" min=0 max=1 step="0.1" id="wonderplugin-carousel-lightboxdefaultvideovolume" value="1" class="small-text" /> (0 - 1)</label></td>
								</tr>
		
								<tr>
									<th>Video</th>
									<td><label><input name='wonderplugin-carousel-lightboxvideohidecontrols' type='checkbox' id='wonderplugin-carousel-lightboxvideohidecontrols'  /> Hide MP4/WebM video play control bar</label>
									<p style="font-style:italic;">* Video autoplay is not supported on mobile and tables. The limitation comes from iOS and Android.</p>
									</td>
								</tr>
							</table>
							</li>
							
							<li class="wonderplugin-tab-horizontal">
							<table class="wonderplugin-form-table-noborder">
								<tr>
									<th>Thumbnails</th>
									<td><label><input name='wonderplugin-carousel-lightboxshownavigation' type='checkbox' id='wonderplugin-carousel-lightboxshownavigation'  /> Show thumbnails</label>
									</td>
								</tr>
								<tr>
									<th></th>
									<td><label>Thumbnail size: <input name="wonderplugin-carousel-lightboxthumbwidth" type="number" id="wonderplugin-carousel-lightboxthumbwidth" value="96" class="small-text" /> x <input name="wonderplugin-carousel-lightboxthumbheight" type="number" id="wonderplugin-carousel-lightboxthumbheight" value="72" class="small-text" /></label> 
									<label>Thumbnail top margin: <input name="wonderplugin-carousel-lightboxthumbtopmargin" type="number" id="wonderplugin-carousel-lightboxthumbtopmargin" value="12" class="small-text" /> Thumbnail bottom margin: <input name="wonderplugin-carousel-lightboxthumbbottommargin" type="number" id="wonderplugin-carousel-lightboxthumbbottommargin" value="12" class="small-text" /></label>
									</td>
								</tr>
							</table>
							</li>
							
							<li class="wonderplugin-tab-horizontal">
							<table class="wonderplugin-form-table-noborder">
								<tr valign="top">
									<th scope="row">Text position</th>
									<td>
										<select name="wonderplugin-carousel-lightboxtitlestyle" id="wonderplugin-carousel-lightboxtitlestyle">
										  <option value="bottom">Bottom</option>
										  <option value="inside">Inside</option>
										  <option value="right">Right</option>
										  <option value="left">Left</option>
										</select>
									</td>
								</tr>
								<tr>
									<th>Maximum text bar height when text position is bottom</th>
									<td><label><input name="wonderplugin-carousel-lightboxbarheight" type="number" id="wonderplugin-carousel-lightboxbarheight" value="64" class="small-text" /></label>
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">Image/video width percentage when text position is right or left</th>
									<td><input name="wonderplugin-carousel-lightboximagepercentage" type="number" id="wonderplugin-carousel-lightboximagepercentage" value="75" class="small-text" />%</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">Title</th>
									<td><label><input name="wonderplugin-carousel-lightboxshowtitle" type="checkbox" id="wonderplugin-carousel-lightboxshowtitle" /> Show title</label></td>
								</tr>
								
								<tr valign="top">
									<th scope="row">Add the following prefix to title</th>
									<td><label><input name="wonderplugin-carousel-lightboxshowtitleprefix" type="checkbox" id="wonderplugin-carousel-lightboxshowtitleprefix" /> Add prefix:</label><input name="wonderplugin-carousel-lightboxtitleprefix" type="text" id="wonderplugin-carousel-lightboxtitleprefix" value="" class="regular-text" /></td>
								</tr>
		
								<tr>
									<th>Title CSS</th>
									<td><label><textarea name="wonderplugin-carousel-lightboxtitlebottomcss" id="wonderplugin-carousel-lightboxtitlebottomcss" rows="2" class="large-text code"></textarea></label>
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">Title CSS when text position is inside</th>
									<td><textarea name="wonderplugin-carousel-lightboxtitleinsidecss" id="wonderplugin-carousel-lightboxtitleinsidecss" rows="2" class="large-text code"></textarea></td>
								</tr>
		
								<tr valign="top">
									<th scope="row">Description</th>
									<td><label><input name="wonderplugin-carousel-lightboxshowdescription" type="checkbox" id="wonderplugin-carousel-lightboxshowdescription" /> Show description</label></td>
								</tr>
								
								<tr>
									<th>Description CSS</th>
									<td><label><textarea name="wonderplugin-carousel-lightboxdescriptionbottomcss" id="wonderplugin-carousel-lightboxdescriptionbottomcss" rows="2" class="large-text code"></textarea></label>
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">Description CSS when text position is inside</th>
									<td><textarea name="wonderplugin-carousel-lightboxdescriptioninsidecss" id="wonderplugin-carousel-lightboxdescriptioninsidecss" rows="2" class="large-text code"></textarea></td>
								</tr>
								
							</table>
							</li>
							
							<li class="wonderplugin-tab-horizontal">
							<table class="wonderplugin-form-table-noborder">
							
							<tr valign="top">
								<th scope="row">Social Media Buttons</th>
								<td><label><input name="wonderplugin-carousel-lightboxaddsocialmedia" type="checkbox" id="wonderplugin-carousel-lightboxaddsocialmedia" /> Show social media button links defined in step 1</label></td>
							</tr>
								
							<tr valign="top">
								<th scope="row">Social Media Share</th>
								<td><label for="wonderplugin-carousel-lightboxshowsocial"><input name="wonderplugin-carousel-lightboxshowsocial" type="checkbox" id="wonderplugin-carousel-lightboxshowsocial" /> Show social media share buttons</label>
								<p><label for="wonderplugin-carousel-lightboxshowfacebook"><input name="wonderplugin-carousel-lightboxshowfacebook" type="checkbox" id="wonderplugin-carousel-lightboxshowfacebook" /> Show Facebook share button</label>
								<br><label for="wonderplugin-carousel-lightboxshowtwitter"><input name="wonderplugin-carousel-lightboxshowtwitter" type="checkbox" id="wonderplugin-carousel-lightboxshowtwitter" /> Show Twitter share button</label>
								<br><label for="wonderplugin-carousel-lightboxshowpinterest"><input name="wonderplugin-carousel-lightboxshowpinterest" type="checkbox" id="wonderplugin-carousel-lightboxshowpinterest" /> Show Pinterest share button</label></p>
								</td>
							</tr>
				        	
				        	<tr valign="top">
								<th scope="row">Position and Size</th>
								<td>
								Position CSS: <input name="wonderplugin-carousel-lightboxsocialposition" type="text" id="wonderplugin-carousel-lightboxsocialposition" value="" class="regular-text" />
								<p>Position CSS on small screen: <input name="wonderplugin-carousel-lightboxsocialpositionsmallscreen" type="text" id="wonderplugin-carousel-lightboxsocialpositionsmallscreen" value="" class="regular-text" /></p>
								<p>Button size: <input name="wonderplugin-carousel-lightboxsocialbuttonsize" type="number" id="wonderplugin-carousel-lightboxsocialbuttonsize" value="32" class="small-text" />
								Button font size: <input name="wonderplugin-carousel-lightboxsocialbuttonfontsize" type="number" id="wonderplugin-carousel-lightboxsocialbuttonfontsize" value="18" class="small-text" />
								Buttons direction:
								<select name="wonderplugin-carousel-lightboxsocialdirection" id="wonderplugin-carousel-lightboxsocialdirection">
								  <option value="horizontal" selected="selected">horizontal</option>
								  <option value="vertical">>vertical</option>
								</select>
								</p>
								<p><label for="wonderplugin-carousel-lightboxsocialrotateeffect"><input name="wonderplugin-carousel-lightboxsocialrotateeffect" type="checkbox" id="wonderplugin-carousel-lightboxsocialrotateeffect" /> Enable button rotating effect on mouse hover</label></p>	
								</td>
							</tr>
							</table>
							</li>
							
							<li class="wonderplugin-tab-horizontal">
							<table class="wonderplugin-form-table-noborder">
								<tr valign="top">
									<th scope="row">Lightbox Advanced Options</th>
									<td><textarea name="wonderplugin-carousel-lightboxadvancedoptions" id="wonderplugin-carousel-lightboxadvancedoptions" rows="4" class="large-text code"></textarea></td>
								</tr>
							</table>
							</li>
						</ul>
						
						</div>
						
						<div class="wonderplugin-carousel-options-tab">
							<table class="wonderplugin-form-table-noborder">
								<tr>
									<th></th>
									<td><p><label><input name='wonderplugin-carousel-donotinit' type='checkbox' id='wonderplugin-carousel-donotinit'  /> Do not init the carousel when the page is loaded. Check this option if you would like to manually init the carousel with JavaScript API.</label></p>
									<p><label><input name='wonderplugin-carousel-addinitscript' type='checkbox' id='wonderplugin-carousel-addinitscript'  /> Add init scripts together with carousel HTML code. Check this option if your WordPress site uses Ajax to load pages and posts.</label></p>
									<p><label><input name='wonderplugin-carousel-doshortcodeontext' type='checkbox' id='wonderplugin-carousel-doshortcodeontext'  /> Support shortcode in title and description</label></p>
									<p><label><input name='wonderplugin-carousel-triggerresize' type='checkbox' id='wonderplugin-carousel-triggerresize'  /> Trigger window resize event after (ms): </label><input name="wonderplugin-carousel-triggerresizedelay" type="number" min=0 id="wonderplugin-carousel-triggerresizedelay" value="0" class="small-text" /></p>
									</td>
								</tr>
								<tr>
									<th>Custom CSS</th>
									<td><textarea name='wonderplugin-carousel-custom-css' id='wonderplugin-carousel-custom-css' value='' class='large-text' rows="10"></textarea></td>
								</tr>
								<tr>
									<th>Advanced Options</th>
									<td><textarea name='wonderplugin-carousel-data-options' id='wonderplugin-carousel-data-options' value='' class='large-text' rows="10"></textarea></td>
								</tr>
								<tr>
									<th>Custom JavaScript</th>
									<td><textarea name='wonderplugin-carousel-customjs' id='wonderplugin-carousel-customjs' value='' class='large-text' rows="10"></textarea><br />
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				<div style="clear:both;"></div>
				
			</li>
			<li class="wonderplugin-tab">
				<div id="wonderplugin-carousel-preview-tab">
					<div id="wonderplugin-carousel-preview-message"></div>
					<div id="wonderplugin-carousel-preview-container">
					</div>
				</div>
			</li>
			<li class="wonderplugin-tab">
				<div id="wonderplugin-carousel-publish-loading"></div>
				<div id="wonderplugin-carousel-publish-information"></div>
			</li>
		</ul>
		</div>
		
		<?php
	}
	
	function get_list_data() {
		return array();
	}
}