<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
function hugeit_vp_html_showStyles($param_values, $op_type)
{
    ?>
<script>
jQuery(document).ready(function(){
	var strliID=jQuery(location).attr('hash');
	jQuery('#video_player-view-tabs > li').removeClass('active');
	if(jQuery('#video_player-view-tabs > li > a[href="'+strliID+'"]').length>0){
		jQuery('#video_player-view-tabs > li > a[href="'+strliID+'"]').parent().addClass('active');
	}else {
		jQuery('#video_player-view-tabs > li > a[href="#video_player-view-options-0"]').parent().addClass('active');
	}
	jQuery('#video_player-view-tabs-contents > li').removeClass('active');
	strliID=strliID.replace("#","");
	if(jQuery('#video_player-view-tabs-contents > li[data-id="'+strliID+'"]').length>0){
		jQuery('#video_player-view-tabs-contents > li[data-id="'+strliID+'"]').addClass('active');
	}else {
		jQuery('#video_player-view-tabs-contents > li[data-id="video_player-view-options-0"]').addClass('active');
	}
	jQuery('input[data-slider="true"]').bind("slider:changed", function (event, data) {
		 jQuery(this).parent().find('span').html(parseInt(data.value)+"%");
		 jQuery(this).val(parseInt(data.value));
	});	
});
</script>
<div class="wrap">
<?php $path_site = plugins_url("../images", __FILE__); ?>
<div id="poststuff">
		<?php $path_site = plugins_url("/../Front_images", __FILE__); ?>

		<div id="post-body-content" class="video_player-options">
			<div id="post-body-heading">
				<h3>General Options</h3>
				<a onclick="document.getElementById('adminForm').submit()" class="save-video_player-options button-primary">Save</a>
			</div>
		<form action="admin.php?page=hugeit_vp_Options_styles&task=save" method="post" id="adminForm" name="adminForm">
		<div id="video_player-options-list">			
			<ul class="options-block" id="video_player-view-tabs-contents">				
				<!-- VIEW 2 POPUP -->
				<li data-id="video_player-view-options-0">
					<div>
						<h3>Player Options</h3>
						<div class="has-background">
							<label for="video_pl_background_color">Player Background Color</label>
							<input name="params[video_pl_background_color]" type="text" class="color" id="video_pl_background_color" value="<?php echo esc_attr($param_values['video_pl_background_color']); ?>" />
						</div>
						<div>
							<label for="video_pl_border_size">Border Size</label>
							<input type="number" name="params[video_pl_border_size]" id="video_pl_border_size" value="<?php echo esc_attr($param_values['video_pl_border_size']); ?>" /> <!-- data-slider-highlight="true" data-slider-values="<?php  ?>" type="text" data-slider="true" value="70" --> 
						</div>
						<div class="has-background">
							<label for="video_pl_border_color">Border Color</label>
							<input name="params[video_pl_border_color]" type="text" class="color" id="video_pl_border_color" value="<?php echo esc_attr($param_values['video_pl_border_color']); ?>"  />
						</div>
						<div>
							<label for="video_pl_position">Positioning</label>
							<select name="params[video_pl_position]" id="video_pl_position" >
								<option value="left" <?php if($param_values['video_pl_position']=="left"){ echo 'selected="selected"'; } ?>>Left</option>
								<option value="center" <?php if($param_values['video_pl_position']=="center"){ echo 'selected="selected"'; } ?>>Center</option>
								<option value="right" <?php if($param_values['video_pl_position']=="right"){ echo 'selected="selected"'; } ?>>Right</option>
							</select>
						</div>
						<div class="has-background">
							<label for="video_pl_margin_top">Margin Top</label>
							<input name="params[video_pl_margin_top]" type="number" id="video_pl_margin_top" value="<?php echo esc_attr($param_values['video_pl_margin_top']); ?>"  />px
						</div>
						<div>
							<label for="video_pl_margin_right">Margin Right</label>
							<input name="params[video_pl_margin_right]" type="number"id="video_pl_margin_right" value="<?php echo esc_attr($param_values['video_pl_margin_right']); ?>"  />px
						</div>
						<div class="has-background">
							<label for="video_pl_margin_bottom">Margin Bottom</label>
							<input name="params[video_pl_margin_bottom]" type="number"id="video_pl_margin_bottom" value="<?php echo esc_attr($param_values['video_pl_margin_bottom']); ?>"  />px
						</div>
						<div>
							<label for="video_pl_margin_left">Margin Left</label>
							<input name="params[video_pl_margin_left]" type="number"id="video_pl_margin_left" value="<?php echo esc_attr($param_values['video_pl_margin_left']); ?>"  />px
						</div>
					</div>
					<div>
						<h3>Playlist Options</h3>
						<div class="has-background">
							<label for="video_pl_playlist_color">Playlist Background Color</label>
							<input name="params[video_pl_playlist_color]" type="text" class="color" id="video_pl_playlist_color" value="<?php echo esc_attr($param_values['video_pl_playlist_color']); ?>"  />
						</div>
						<div>
							<label for="video_pl_playlist_text_color">Playlist Text Color</label>
							<input name="params[video_pl_playlist_text_color]" type="text" class="color" id="video_pl_playlist_text_color" value="<?php echo esc_attr($param_values['video_pl_playlist_text_color']); ?>"  />
						</div>
						<div class="has-background">
							<label for="video_pl_playlist_hover_color">Playlist Hover Background Color</label>
							<input name="params[video_pl_playlist_hover_color]" type="text" class="color" id="video_pl_playlist_hover_color" value="<?php echo esc_attr($param_values['video_pl_playlist_hover_color']); ?>"  />
						</div>
						<div>
							<label for="video_pl_playlist_hover_text_color">Playlist Hover Text Color</label>
							<input name="params[video_pl_playlist_hover_text_color]" type="text" class="color" id="video_pl_playlist_hover_text_color" value="<?php echo esc_attr($param_values['video_pl_playlist_hover_text_color']); ?>"  />
						</div>
						<div class="has-background">
							<label for="video_pl_playlist_active_color">Playlist Active Background Color</label>
							<input name="params[video_pl_playlist_active_color]" type="text" class="color" id="video_pl_playlist_active_color" value="<?php echo esc_attr($param_values['video_pl_playlist_active_color']); ?>"  />
						</div>
						<div>
							<label for="video_pl_playlist_active_text_color">Playlist Active Text Color</label>
							<input name="params[video_pl_playlist_active_text_color]" type="text" class="color" id="video_pl_playlist_active_text_color" value="<?php echo esc_attr($param_values['video_pl_playlist_active_text_color']); ?>"  />
						</div>
						<div class="has-background"> 
							<label for="video_pl_playlist_head_color">Playlist Title Color</label>
							<input type="text" class="color" name="params[video_pl_playlist_head_color]" id="video_pl_playlist_head_color" value="<?php echo esc_attr($param_values['video_pl_playlist_head_color']); ?>" />
						</div>
						<div> 
							<label for="video_pl_playlist_scroll_track">Playlist Scrollbar Track Color</label>
							<input type="text" class="color" name="params[video_pl_playlist_scroll_track]" id="video_pl_playlist_scroll_track" value="<?php echo esc_attr($param_values['video_pl_playlist_scroll_track']); ?>" />
						</div>
						<div  class="has-background"> 
							<label for="video_pl_playlist_scroll_thumb">Playlist Scrollbar Thumb Color</label>
							<input type="text" class="color" name="params[video_pl_playlist_scroll_thumb]" id="video_pl_playlist_scroll_thumb" value="<?php echo esc_attr($param_values['video_pl_playlist_scroll_thumb']); ?>" />
						</div>
						<div> 
							<label for="video_pl_playlist_scroll_thumb_hover">Playlist Scrollbar Thumb Hover Color</label>
							<input type="text" class="color" name="params[video_pl_playlist_scroll_thumb_hover" id="video_pl_playlist_scroll_thumb_hover" value="<?php echo esc_attr($param_values['video_pl_playlist_scroll_thumb_hover']); ?>" />
						</div>
					</div>
					<div style="margin-top:-70px">
						<h3>Custom Player</h3>

						<div class="has-background">
							<label for="video_pl_timeline_background">Progress Bar Background Color</label>
							<input name="params[video_pl_timeline_background]" type="text" class="color" id="video_pl_timeline_background" value="#<?php echo $param_values['video_pl_timeline_background']; ?>"  />
						</div>
						<div>
							<label for="video_pl_timeline_background_opacity">Progress Bar Background Color</label>
							<div class="slider-container" style="float:left; width:20%;height:100%;">
								<input name="params[video_pl_timeline_background_opacity]" id="video_pl_timeline_background_opacity" data-slider-highlight="true"  data-slider-values="<?php for($i=0; $i <= 100 ; $i++){ if( $i== 100 ){ echo $i; } else{ echo $i.","; } } ?>" type="text" data-slider="true" value="<?php echo esc_attr($param_values['video_pl_timeline_background_opacity']); ?>" />
								<span style="position:absolute; top: 4px;right: 0px;"><?php echo $param_values['video_pl_timeline_background_opacity']; ?>%</span>
							</div>
						</div>
						<div class="has-background">
							<label for="video_pl_timeline_buffering_color">Progress Bar Buffering Color</label>
							<input name="params[video_pl_timeline_buffering_color]" type="text" class="color" id="video_pl_timeline_buffering_color" value="<?php echo esc_attr($param_values['video_pl_timeline_buffering_color']); ?>"  />
						</div>
						<div>
							<label for="video_pl_timeline_buffering_opacity">Progress Bar Buffering Opacity</label>
							<div class="slider-container" style="float:left; width:20%;height:100%;">
								<input name="params[video_pl_timeline_buffering_opacity]" id="video_pl_timeline_buffering_opacity" data-slider-highlight="true"  data-slider-values="<?php for($i=0; $i <= 100 ; $i++){ if( $i== 100 ){ echo $i; } else{ echo $i.","; } } ?>" type="text" data-slider="true" value="<?php echo esc_attr($param_values['video_pl_timeline_buffering_opacity']); ?>" />
								<span style="position:absolute; top: 4px;right: 0px;"><?php echo $param_values['video_pl_timeline_buffering_opacity']; ?>%</span>
							</div>
						</div>
						<div class="has-background">
							<label for="video_pl_timeline_slider_color">Progress Bar Slider Color</label>
							<input name="params[video_pl_timeline_slider_color]" type="text" class="color" id="video_pl_timeline_slider_color" value="<?php echo esc_attr($param_values['video_pl_timeline_slider_color']); ?>"  />
						</div>
						<div>
							<label for="video_pl_timeline_color">Progress Bar Color</label>
							<input name="params[video_pl_timeline_color]" type="text" class="color" id="video_pl_timeline_color" value="<?php echo esc_attr($param_values['video_pl_timeline_color']); ?>"  />
						</div>
						<div class="has-background">
							<label for="video_pl_timeline_buffering_opacity">Progress Bar Buffering Background Opacity</label>
							<div class="slider-container" style="float:left; width:20%;height:100%;">
								<input name="params[video_pl_timeline_buffering_opacity]" id="video_pl_timeline_buffering_opacity" data-slider-highlight="true"  data-slider-values="<?php for($i=0; $i <= 100 ; $i++){ if( $i== 100 ){ echo $i; } else{ echo $i.","; } } ?>" type="text" data-slider="true" value="<?php echo esc_attr($param_values['video_pl_timeline_buffering_opacity']); ?>" />
								<span style="position:absolute; top: 4px;right: 0px;"><?php echo $param_values['video_pl_timeline_buffering_opacity']; ?>%</span>
							</div>
						</div>
						<div>
							<label for="video_pl_controls_panel_color">Controls Panel Color</label>
							<input name="params[video_pl_controls_panel_color]" type="text" class="color" id="video_pl_controls_panel_color" value="<?php echo esc_attr($param_values['video_pl_controls_panel_color']); ?>"  />
						</div>
						<div class="has-background">
							<label for="video_pl_controls_panel_opacity">Controls Panel Background Opacity</label>
							<div class="slider-container" style="float:left; width:20%;height:100%;">
								<input name="params[video_pl_controls_panel_opacity]" id="video_pl_controls_panel_opacity" data-slider-highlight="true"  data-slider-values="<?php for($i=0; $i <= 100 ; $i++){ if( $i== 100 ){ echo $i; } else{ echo $i.","; } } ?>" type="text" data-slider="true" value="<?php echo esc_attr($param_values['video_pl_controls_panel_opacity']); ?>" />
								<span style="position:absolute; top: 4px;right: 0px;"><?php echo $param_values['video_pl_controls_panel_opacity']; ?>%</span>
							</div>
						</div>
						<div>
							<label for="video_pl_buttons_color">Buttons Color</label>
							<input name="params[video_pl_buttons_color]" type="text" class="color" id="video_pl_buttons_color" value="<?php echo esc_attr($param_values['video_pl_buttons_color']); ?>"  />
						</div>
						<div class="has-background">
							<label for="video_pl_buttons_hover_color">Buttons Hover Color</label>
							<input name="params[video_pl_buttons_hover_color]" type="text" class="color" id="video_pl_buttons_hover_color" value="<?php echo esc_attr($param_values['video_pl_buttons_hover_color']); ?>"  />
						</div>
						<div class="has-background">
							<label for="video_pl_curtime_color">Current Time Text Color</label>
							<input name="params[video_pl_curtime_color]" type="text" class="color" id="video_pl_curtime_color" value="<?php echo esc_attr($param_values['video_pl_curtime_color']); ?>"  />
						</div>
						<div class="has-background">
							<label for="video_pl_durtime_color">Duration Time Text Color</label>
							<input name="params[video_pl_durtime_color]" type="text" class="color" id="video_pl_durtime_color" value="<?php echo esc_attr($param_values['video_pl_durtime_color']); ?>"  />
						</div>
					</div>
					<div>
						<h3>YouTube Player Options</h3>
						<div class="has-background">
							<label for="video_pl_yt_color">Color</label>
							<select id="video_pl_yt_color" name="params[video_pl_yt_color]">
								<option value="red" <?php if($param_values['video_pl_yt_color']=="red"){ echo 'selected="selected"'; } ?>>Red</option>
								<option value="white" <?php if($param_values['video_pl_yt_color']=="white"){ echo 'selected="selected"'; } ?>>White</option>
							</select>
						</div>
						<div>
							<label for="video_pl_yt_theme">Theme</label>
							<select id="video_pl_yt_theme" name="params[video_pl_yt_theme]">
								<option value="dark" <?php if($param_values['video_pl_yt_theme']=="dark"){ echo 'selected="selected"'; } ?>>Dark</option>
								<option value="light" <?php if($param_values['video_pl_yt_theme']=="light"){ echo 'selected="selected"'; } ?>>Light</option>
							</select>
						</div>
						<div class="has-background">
							<label for="video_pl_yt_annotation">Annotations</label>
							<select id="video_pl_yt_annotation" name="params[video_pl_yt_annotation]">
								<option value="1" <?php if($param_values['video_pl_yt_annotation']=="1"){ echo 'selected="selected"'; } ?>>On</option>
								<option value="3" <?php if($param_values['video_pl_yt_annotation']=="3"){ echo 'selected="selected"'; } ?>>Off</option>
							</select>
						</div>
						<div>
							<label for="video_pl_yt_autohide">Autohide</label>
							<select id="video_pl_yt_autohide" name="params[video_pl_yt_autohide]">
								<option value="1" <?php if($param_values['video_pl_yt_autohide']=="1"){ echo 'selected="selected"'; } ?>>On</option>
								<option value="0" <?php if($param_values['video_pl_yt_autohide']=="0"){ echo 'selected="selected"'; } ?>>Off</option>
							</select>
						</div>
						<div class="has-background">
							<label for="video_pl_yt_fullscreen">Full Screen</label>
							<select id="video_pl_yt_fullscreen" name="params[video_pl_yt_fullscreen]">
								<option value="1" <?php if($param_values['video_pl_yt_fullscreen']=="1"){ echo 'selected="selected"'; } ?>>On</option>
								<option value="0" <?php if($param_values['video_pl_yt_fullscreen']=="0"){ echo 'selected="selected"'; } ?>>Off</option>
							</select>
						</div>
						<div>
							<label for="video_pl_yt_showinfo">Show Info</label>
							<select id="video_pl_yt_showinfo" name="params[video_pl_yt_showinfo]">
								<option value="1" <?php if($param_values['video_pl_yt_showinfo']=="1"){ echo 'selected="selected"'; } ?>>On</option>
								<option value="0" <?php if($param_values['video_pl_yt_showinfo']=="0"){ echo 'selected="selected"'; } ?>>Off</option>
							</select>
						</div>
                        <div>
                            <label for="video_pl_yt_related">Related</label>
                            <select id="video_pl_yt_related" name="params[video_pl_yt_related]">
                                <option value="1" <?php if($param_values['video_pl_yt_related']=="1"){ echo 'selected="selected"'; } ?>>On</option>
                                <option value="0" <?php if($param_values['video_pl_yt_related']=="0"){ echo 'selected="selected"'; } ?>>Off</option>
                            </select>
                        </div>
					</div>
					<div>
						<h3>Vimeo Player Options</h3>
						<div class="has-background">
							<label for="video_pl_vimeo_color">Color</label>
							<input type="text" class="color" id="video_pl_vimeo_color" name="params[video_pl_vimeo_color]" value="<?php echo esc_attr($param_values['video_pl_vimeo_color']); ?>" />
						</div>
						<div>
							<label for="video_pl_vimeo_portrait">Portrait</label>
							<select id="video_pl_vimeo_portrait" name="params[video_pl_vimeo_portrait]">
								<option value="1" <?php if($param_values['video_pl_vimeo_portrait']=="1"){ echo 'selected="selected"'; } ?>>On</option>
								<option value="0" <?php if($param_values['video_pl_vimeo_portrait']=="0"){ echo 'selected="selected"'; } ?>>Off</option>
							</select>
						</div>
					</div>
				</li>     
			</ul>
		<div id="post-body-footer">
			<a onclick="document.getElementById('adminForm').submit()" class="save-video_player-options button-primary">Save</a>
			<div class="clear"></div>
		</div>
			<?php wp_nonce_field('save_options_', 'hugeit_vp_save_options'); ?>
		</form>
		</div>
	</div>
</div>
</div>
<input type="hidden" name="option" value=""/>
<input type="hidden" name="task" value=""/>
<input type="hidden" name="controller" value="options"/>
<input type="hidden" name="op_type" value="styles"/>
<input type="hidden" name="boxchecked" value="0"/>
<?php
}