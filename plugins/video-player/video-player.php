<?php

/*
Plugin Name: Huge IT Video Player
Plugin URI: https://huge-it.com/video-player/
Description: Huge-IT Video player is perfect for using for creating various portfolios within various views.
Version: 10.0
Author: Huge-IT
Author URI: https://huge-it.com/
License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
Text Domain: hugeit_vp
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action('media_buttons_context', 'hugeit_vp_custom_button');

add_action('admin_footer', 'hugeit_vp_inline_popup_content');


function hugeit_vp_custom_button($context) {

  $img = plugins_url( '/images/post.button.png' , __FILE__ );

  $container_id = 'huge_it_video_player';

  $title = 'Select Huge IT Video Album to insert into post';

  $context .= '<a class="button thickbox" title="'.$title.'"    href="#TB_inline?width=400&inlineId='.$container_id.'">
		<span class="wp-media-buttons-icon" style="background: url('.$img.'); background-repeat: no-repeat; background-position: left bottom;"></span>
	Add Video Player
	</a>';
  
  return $context;
}

function hugeit_vp_inline_popup_content() {
?>
<script type="text/javascript">
				jQuery(document).ready(function() {
				  jQuery('#hugeitvideo_playerinsert').on('click', function() {
				  	var id = jQuery('#huge_it_video_player-select option:selected').val();
			
				  	window.send_to_editor('[huge_it_video_player id="' + id + '"]');
					tb_remove();
				  })
				});
</script>

<div id="huge_it_video_player" style="display:none;">
  <h3>Select Huge IT Video Album to insert into post</h3>
  <?php 
  	  global $wpdb;
	  $query="SELECT * FROM ".$wpdb->prefix."huge_it_video_players order by id ASC";
			   $shortcodevideo_players=$wpdb->get_results($query);
			   ?>

 <?php 	if (count($shortcodevideo_players)) {
							echo "<select id='huge_it_video_player-select'>";
							foreach ($shortcodevideo_players as $shortcodevideo_player) {
								echo "<option value='".$shortcodevideo_player->id."'>".$shortcodevideo_player->name."</option>";
							}
							echo "</select>";
							echo "<button class='button primary' id='hugeitvideo_playerinsert'>Insert Video Album</button>";
						} else {
							echo "No slideshows found", "huge_it_video_player";
						}
						?>
</div>
<?php
}
/**
 * Shortcode update
 */

add_action('init', 'hugeit_vp_do_output_buffer');
function hugeit_vp_do_output_buffer() {
	if( is_admin() && isset($_GET['page']) && $_GET['page'] == 'hugeit_vp_video_player' ){
		ob_start();
	}
}
add_action('init', 'hugeit_vp_lang_load');

function hugeit_vp_lang_load()
{
    load_plugin_textdomain('sp_video_player', false, basename(dirname(__FILE__)) . '/Languages');
}

function hugeit_vp_images_list_shotrcode($atts)
{
    extract(shortcode_atts(array(
        'id' => 'no huge_it video_player',
    ), $atts));

    wp_enqueue_media();
    wp_enqueue_style("hugeicons",plugins_url("icon-fonts/css/hugeicons.css", __FILE__), FALSE);
    wp_enqueue_script("froogaloop",plugins_url("froogaloop.min.js", __FILE__), FALSE);

    return hugeit_vp_images_list($atts['id']);
}

// end filter

add_shortcode('huge_it_video_player', 'hugeit_vp_images_list_shotrcode');

function hugeit_vp_images_list($id) {

    require_once("Front_end/video_player_front_end_view.php");
    require_once("Front_end/video_player_front_end_func.php");

    $id = absint($id);
    return hugeit_vp_show_published_video_player_1($id);
}

add_action('admin_menu', 'hugeit_vp_options_panel');
function hugeit_vp_options_panel()
{
    $GLOBALS['hugeit_vp_page_category'] = add_menu_page('Theme page title', 'Video Player', 'publish_pages', 'hugeit_vp_video_player', 'hugeit_vp_video_player', plugins_url('images/huge_it_video_player_logo_for_menu.png', __FILE__));
    $GLOBALS['hugeit_vp_page_option'] = add_submenu_page('hugeit_vp_video_player', 'General Options', 'General Options', 'publish_pages', 'hugeit_vp_Options_styles', 'hugeit_vp_Options_styles');
	$GLOBALS['hugeit_vp_page_featured'] = add_submenu_page('hugeit_vp_video_player', 'Featured Plugins', 'Featured Plugins', 'publish_pages', 'hugeit_vp_featured_plugins', 'hugeit_vp_featured_plugins');

}
function hugeit_vp_featured_plugins()
{
	include_once("admin/huge_it_featured_plugins.php");
}

////////////////////////// Huge it Slider ///////////////////////////////////////////

add_action( 'admin_enqueue_scripts', 'hugeit_vp_admin_script' );

function hugeit_vp_admin_script($hook)
{
    global $hugeit_vp_page_category;
    global $hugeit_vp_page_option;
    global $hugeit_vp_page_featured;

    if($hook == $hugeit_vp_page_category) {
        wp_enqueue_media();
        wp_enqueue_style("jquery_ui_smoothness", plugins_url("style/smoothness.css", __FILE__), FALSE);
        wp_enqueue_style("admin_css", plugins_url("style/admin.style.css", __FILE__), FALSE);
        wp_enqueue_script("admin_js", plugins_url("js/admin.js", __FILE__), FALSE);
        wp_localize_script('admin_js', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
    }
    if($hook == $hugeit_vp_page_option) {
        wp_enqueue_script('jquery');
        wp_enqueue_script("simple_slider_js",  plugins_url("js/simple-slider.js", __FILE__), FALSE);
        wp_enqueue_style("simple_slider_css", plugins_url("style/simple-slider.css", __FILE__), FALSE);
        wp_enqueue_style("admin_css", plugins_url("style/admin.style.css", __FILE__), FALSE);
        wp_enqueue_script("admin_js", plugins_url("js/admin.js", __FILE__), FALSE);
        wp_enqueue_script('param_block2', plugins_url("elements/jscolor/jscolor.js", __FILE__));
        wp_localize_script('admin_js', 'ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' )));
    }
    if($hook == $hugeit_vp_page_featured) {
        wp_enqueue_style("admin_css", plugins_url("style/admin.style.css", __FILE__), FALSE);
    }
}

function hugeit_vp_video_player() {

    require_once("admin/video_player_func.php");
    require_once("admin/video_player_view.php");

    $tasks = array('add_cat', 'video_player_video', 'edit_cat', 'apply', 'remove_cat');

    if (isset($_GET["task"]) && in_array($_GET['task'], $tasks))
        $task = esc_html($_GET["task"]);
    else
        $task = '';
    if (isset($_GET["id"]))
        $id = absint($_GET["id"]);
    else
        $id = 0;
    global $wpdb;
    switch ($task) {
        case 'add_cat':
        	$add_cat = !wp_verify_nonce($_GET['hugeit_vp_add_cat_data'], 'hugeit_vp_add_cat_');
			$add_new = !wp_verify_nonce($_GET['hugeit_vp_add_cat_data'], 'hugeit_vp_add_new_');
			if ((!isset($_GET['hugeit_vp_add_cat_data']) && ($add_cat || $add_new) )) {
				wp_die('Security check failure.');
			}
            hugeit_vp_add_video_player();
            break;
		case 'video_player_video':
            if ($id) {
                if (!empty($_POST) && (!isset($_GET['hugeit_vp_add_video']) || !wp_verify_nonce($_GET['hugeit_vp_add_video'], 'add_video_' . absint($_GET['id'])))) {
                    wp_die('Security check failure.');
                }
                hugeit_vp_video_player_video($id);
            } else {
                $id = $wpdb->get_var("SELECT MAX( id ) FROM " . $wpdb->prefix . "huge_it_video_players");
                hugeit_vp_video_player_video($id);
            }
            break;
        case 'edit_cat':
            if ($id) {
				$edit_cat_nonce = !wp_verify_nonce($_GET['hugeit_vp_edit_cat'], 'edit_cat_' . absint($_GET['id']) );
				$remove_slide = !wp_verify_nonce($_GET['hugeit_vp_edit_cat'], 'remove_slide_' . absint($_GET['id']) );

				if(!isset($_GET['hugeit_vp_edit_cat']) && ($edit_cat_nonce || $remove_slide) ) {
					wp_die('Security check failure.');
				}
				hugeit_vp_edit_video_player($id);
			}
            else {
                $id = $wpdb->get_var("SELECT MAX( id ) FROM " . $wpdb->prefix . "huge_it_video_players");
                hugeit_vp_edit_video_player($id);
            }
            break;
        case 'apply':
            if ($id) {
				if (!isset($_REQUEST['hugeit_vp_save_data'])) {
					wp_die('Security check failure.');
				}
				$save_verify_nonce = wp_verify_nonce($_REQUEST['hugeit_vp_save_data'], 'save_data_' . absint($_REQUEST['id'])  );
				$add_new_verify_nonce = wp_verify_nonce($_REQUEST['hugeit_vp_save_data'], 'hugeit_vp_add_new_' . absint($_REQUEST['id']));

				if (!($save_verify_nonce || $add_new_verify_nonce)) {
					wp_die('Security check failure.');
				}
				hugeit_vp_apply_cat($id);
                hugeit_vp_edit_video_player($id);
            } 
            break;
        case 'remove_cat':
        	if(!isset($_GET['hugeit_vp_remove_cat']) || !wp_verify_nonce($_GET['hugeit_vp_remove_cat'], 'remove_cat_' . absint($_GET['id']) )) {
				wp_die('Security check failure.');
			}
			hugeit_vp_remove_video_player($id);
            hugeit_vp_show_video_player();
            break;
        default:
            hugeit_vp_show_video_player();
            break;
    }
}

add_action("wp_ajax_video_player_ajax", "hugeit_vp_ajax_callback");

function hugeit_vp_ajax_callback(){
    $protocol = is_ssl() ? 'https:' : 'http:';
	function hugeit_vp_get_youtube_thumb_id_from_url($url){
		if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', esc_url($url), $match)) {
			if(!empty($match[1])){
				return $match[1];
			}else{
				return false;
			}
		}
	}
	
	if(isset($_POST['task'])){
        $task = sanitize_text_field($_POST['task']);

		if($task == "get_video_meta_from_url"){
            if(!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'hugeit_vp_add_video_save')){
                wp_die("Security check failure.");
            }

			$video_url = esc_url($_POST['url']);
			$youtube_exp = explode("youtu", $video_url);
			$vimeo_exp = explode("vimeo", $video_url);
			$video_title="";
			$video_image="";
			if(isset($youtube_exp[1])){
				$video_id=hugeit_vp_get_youtube_thumb_id_from_url($video_url);

                $url = esc_url($protocol . "//www.youtube.com/watch?v=".$video_id);
                $page = file_get_contents($url);
                $doc = new DOMDocument();
                $doc->loadHTML($page);
                $title_div = $doc->getElementById('eow-title');
                $video_title = trim($title_div->nodeValue);
				$video_image = esc_url($protocol . '//img.youtube.com/vi/'.$video_id.'/mqdefault.jpg');
				$type="youtube";
			}else{
				if(isset($vimeo_exp[1])){
					$vidid = explode( "/", $video_url);
					$vidid=end($vidid);
					$hash=file_get_contents($protocol . "//vimeo.com/api/v2/video/".$vidid.".php");
					$hash = unserialize($hash);
					$video_image = esc_url($hash[0]['thumbnail_large']);
					$video_title = trim($hash[0]['title']);
					$type="vimeo";
				}
			}

			if($video_title=="" && $video_image==""){
				echo json_encode(array("fail"=>1));
				die();
			}else{
				echo json_encode(array("success"=>1,"image_url"=>$video_image,"title"=>$video_title,"type"=>$type));
				die();
			}
		}
		if($task == "get_video_thumb_from_id"){
			$video_id = esc_html($_POST['video_id']);
			$video_image="";
			if($_POST['type']=="youtube"){
				$video_image = esc_url($protocol . '//img.youtube.com/vi/'.$video_id.'/mqdefault.jpg');
			}
			if($_POST['type']=="vimeo"){
				$hash = file_get_contents($protocol . "//vimeo.com/api/v2/video/".$video_id.".php");
				$hash = unserialize($hash);
				$video_image = esc_url($hash[0]['thumbnail_large']);
			}
			
			if(isset($video_image)){
				echo json_encode(array("success"=>1,"image_url"=>$video_image));
				die();
			}
		}
		
		if($task == "change_video_link"){
			if(isset($_POST['type']) && !empty($_POST['type'])){
				$type = sanitize_text_field($_POST['type']);
			}
			if(isset($_POST['link']) && !empty($_POST['link'])){
				$link = esc_url($_POST['link']);
			}
			
			if($type=="youtube"){
				$video_id =hugeit_vp_get_youtube_thumb_id_from_url($link);
				if($video_id){
					$video_image = esc_url($protocol . '//img.youtube.com/vi/'.$video_id.'/mqdefault.jpg');
				}
				
			}elseif($type=="vimeo"){
				$link_explode = explode( "/", $link);
				$video_id = end($link_explode);
				$hash = file_get_contents($protocol . "//vimeo.com/api/v2/video/".$video_id.".php");
				$hash = unserialize($hash);
				$video_image = esc_url($hash[0]['thumbnail_large']);
			}
			
			if(isset($video_image) && !empty($video_image)){
				echo json_encode(array("success"=>1,"video_image"=>$video_image,"video_id"=>$video_id));
				die();
			}else{
				echo json_encode(array("error"=>"Wrong Video Url"));
				die();
			}
		}
	}
}

function hugeit_vp_Options_styles()
{
    require_once("admin/video_player_Options_func.php");
    require_once("admin/video_player_Options_view.php");
    if (isset($_GET['task'])) {
        $task = sanitize_text_field($_GET['task']);
		if ($task == 'save') {
			if (!isset($_REQUEST['hugeit_vp_save_options']) || !wp_verify_nonce($_REQUEST['hugeit_vp_save_options'], 'save_options_')) {
				wp_die('Security check failure.');
			}
			hugeit_vp_save_styles_options();
		}
	}
    hugeit_vp_showStyles();
}
/**
 * Huge IT Widget
 */
class Huge_it_video_player_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'Huge_it_video_player_Widget', 
			'Huge IT Video Player', 
			array( 'description' => __( 'Huge IT Video Player', 'huge_it_video_player' ), ) 
		);
	}
	
	public function widget( $args, $instance ) {
		extract($args);

		if (isset($instance['video_player_id'])) {
			$video_player_id = $instance['video_player_id'];

			$title = apply_filters( 'widget_title', $instance['title'] );

			echo $before_widget;
			if ( ! empty( $title ) )
				echo $before_title . $title . $after_title;

			echo do_shortcode("[huge_it_video_player id={$video_player_id}]");
			echo $after_widget;
		}
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['video_player_id'] = strip_tags( $new_instance['video_player_id'] );
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	public function form( $instance ) {
		$selected_video_player = 0;
		$title = "";
		$video_players = false;

		if (isset($instance['video_player_id'])) {
			$selected_video_player = $instance['video_player_id'];
		}
		if (isset($instance['title'])) {
			$title = $instance['title'];
		}
		?>
		<p>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            </p>
            <label for="<?php echo $this->get_field_id('video_player_id'); ?>"><?php _e('Select Video Album:', 'huge_it_video_player'); ?></label>
            <select id="<?php echo $this->get_field_id('video_player_id'); ?>" name="<?php echo $this->get_field_name('video_player_id'); ?>">

            <?php
            global $wpdb;
            $query="SELECT * FROM ".$wpdb->prefix."huge_it_video_players ";
            $rowwidget=$wpdb->get_results($query);
            foreach($rowwidget as $rowwidgetecho){
            ?>
                <option <?php if($rowwidgetecho->id == $selected_video_player){ echo 'selected'; } ?> value="<?php echo $rowwidgetecho->id; ?>"><?php echo $rowwidgetecho->name; ?></option>
                <?php } ?>
            </select>
		</p>
		<?php 
	}
}

add_action('widgets_init', 'hugeit_vp_register_Widget');

function hugeit_vp_register_Widget() {
    register_widget('Huge_it_video_player_Widget');
}

//////////////////////////////////////////////////////                                             ///////////////////////////////////////////////////////
//////////////////////////////////////////////////////               Activate video_player         ///////////////////////////////////////////////////////
//////////////////////////////////////////////////////                                             ///////////////////////////////////////////////////////
//////////////////////////////////////////////////////                                             ///////////////////////////////////////////////////////

function hugeit_vp_activate()
{
    global $wpdb;

// create database tables

    $collate = $wpdb->get_charset_collate();

    $sql_huge_it_video_params = "
CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "huge_it_video_params`(
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `title` varchar(200) CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `value` varchar(200) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) AUTO_INCREMENT=8 {$collate}";

    $sql_huge_it_videos = "
CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "huge_it_videos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `video_player_id` varchar(200) DEFAULT NULL,
  `video_url_1` text,
  `image_url` text,
  `video_url_2` varchar(128) DEFAULT NULL,
  `sl_type` text NOT NULL,
  `video_width` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` tinyint(4) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) AUTO_INCREMENT=5 {$collate}";

    $sql_huge_it_video_players = "
CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "huge_it_video_players` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `album_single` text NOT NULL,
  `layout` text NOT NULL,
  `width` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `align` varchar(128) DEFAULT 'left',
  `margin_top` int(11) DEFAULT '5',
  `margin_bottom` int(11) DEFAULT '5',
  `autoplay` int(11) DEFAULT '0',
  `preload` int(11) DEFAULT '0',
  `published` text,
  `ht_videos` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) AUTO_INCREMENT=2 {$collate}";

    $table_name = $wpdb->prefix . "huge_it_video_params";
    $sql_1 = <<<query1
INSERT INTO `$table_name` (`name`, `title`,`description`, `value`) VALUES

('video_pl_position', 'Position', 'Position', 'center'),
('video_pl_yt_autohide', 'Autohide Youtube Controls', 'Autohide Youtube Controls', '1'),
('video_pl_yt_fullscreen', 'Allow Full Screen', 'Allow Full Screen', '1'),
('video_pl_yt_showinfo', 'Show Video Information', 'Show Video Information', '1'),
('video_pl_vimeo_portrait', 'Show User Portrait', 'Show User Portrait', '1'),
('video_pl_playlist_head_size', 'Playlist Title Size', 'Playlist Title Size', '15'),
('video_pl_curtime_color', 'Current Time Text Color', 'Current Time Text Color', 'FFFFFF'),
('video_pl_durtime_color', 'Duration Time Text Color', 'Duration Time Text Color', 'DDDDDD'),
('video_pl_playlist_scroll_track', 'Playlist Scrollbar Track Color', 'Playlist Scrollbar Track Color', '444444'),
('video_pl_playlist_scroll_thumb', 'Playlist Scrollbar Thumb Color', 'Playlist Scrollbar Thumb Color', 'CCCCCC'),
('video_pl_playlist_scroll_thumb_hover', 'Playlist Scrollbar Thumb Hover Color', 'Playlist Scrollbar Thumb Hover Color', 'AAAAAA'),
('video_pl_playlist_head_color', 'Playlist Heading Color', 'Playlist Heading Color', 'FFFFFF'),
('video_pl_playlist_active_color', 'Playlist Active Color', 'Playlist Active Color', '525252'),
('video_pl_playlist_hover_color', 'Playlist Hover Color', 'Playlist Hover Color', '525252'),
('video_pl_playlist_hover_text_color', 'Playlist Hover Text Color', 'Playlist Hover Text Color', 'FFFFFF'),
('video_pl_playlist_active_text_color', 'Playlist Active Text Color', 'Playlist Active Text Color', 'FFFFFF'),
('video_pl_playlist_text_color', 'Playlist Text Color', 'Playlist Text Color', 'FFFFFF'),
('video_pl_border_size', 'Border Size', 'Border Size', '0'),
('video_pl_margin_bottom', 'margin bottom', 'margin bottom', '5'),
('video_pl_margin_top', 'Margin top', 'Margin top', '5'),
('video_pl_margin_right', 'Margin right', 'Margin right', '5'),
('video_pl_margin_left', 'Margin left', 'Margin left', '5'),
('video_pl_timeline_color', 'Time line color', 'Time line color', 'F12B24'),
('video_pl_timeline_buffering_color', 'Buffer color', 'Buffer color', 'FFFFFF'),
('video_pl_timeline_buffering_opacity', 'Buffer opacity', 'Buffer opacity', '40'),
('video_pl_timeline_background', 'Timeline background color', 'Timeline background color', 'FFFFFF'),
('video_pl_timeline_background_opacity', 'Timeline background opacity', 'Timeline background opacity', '20'),
('video_pl_buttons_color', 'Buttons color', 'Buttons color', 'FFFFFF'),
('video_pl_buttons_hover_color', ' Buttons hover color', ' Buttons hover color', 'FFFFFF'),
('video_pl_controls_panel_color', 'Controls color', 'Controls color', '333'),
('video_pl_controls_panel_opacity', 'Controls', 'Controls', '0'),
('video_pl_volume_background_color', 'Volume time color', 'Volume time color', 'FFFFFF'),
('video_pl_background_color', 'Background color', 'Background color', 'EEEEEE'),
('video_pl_playlist_color', 'Background color', 'Background color', '000000'),
('video_pl_timeline_slider_color', 'Slider color', 'Slider color', 'f12b24'),
('video_pl_title_font_size', 'Title font size', 'Title font size', '13'),
('video_pl_title_font_color', 'Title Font color', 'Title Font color', 'FFFFFF'),
('video_pl_title_background_color', 'Title background color', 'Title background color', '000000'),
('video_pl_title_show', 'show title', 'show title', 'on'),
('video_pl_border_color', 'Border color', 'Border color', '009BE3'),
('video_pl_yt_color', 'Youtube Color', 'Youtube Color', 'red'),
('video_pl_yt_annotation', 'Youtube Annotations', 'Youtube Annotations', '1'),
('video_pl_yt_theme', 'Youtube Theme', 'Youtube Theme', 'dark'),
('video_pl_vimeo_color', 'Vimeo Color', 'Vimeo Color', '00adef');

query1;

    $video_pl_yt_related_query = "SELECT count(name) FROM " . $table_name . " WHERE name = 'video_pl_yt_related'";
    $video_pl_yt_related = $wpdb->get_var($video_pl_yt_related_query);
    $sql_1_1 = "
INSERT INTO `" . $table_name . "`(`name`, `title`,`description`, `value`) VALUES
                                 ('video_pl_yt_related', 'Allow Related Videos', 'Allow Related Videos', '1')";
    if(absint($video_pl_yt_related) === 0) {
        $wpdb->query($sql_1_1);
    }

    $table_name = $wpdb->prefix . "huge_it_videos";
    $sql_2 = "
INSERT INTO 
`" . $table_name . "` (`name`, `video_player_id`, `video_url_1`, `image_url`, `video_url_2`, `sl_type`, `ordering`, `published`) VALUES
('Big Buck Bunny Trailer', '1', 'http://butlerccwebdev.net/support/html5-video/media/bigbuckbunnytrailer-480p.mp4', 'https://peach.blender.org/wp-content/uploads/bbb-splash.png', '', 'video', '0', '1')";

 $sql_2_2 = "
INSERT INTO 
`" . $table_name . "` (`name`, `video_player_id`, `video_url_1`, `image_url`, `video_url_2`, `sl_type`, `ordering`, `published`) VALUES
('Big Buck Bunny(Youtube)', '1', 'https://www.youtube.com/watch?v=YE7VzlLtp-4', 'http://img.youtube.com/vi/YE7VzlLtp-4/mqdefault.jpg', '', 'youtube', '0', '1')";

 $sql_2_3 = "
INSERT INTO 
`" . $table_name . "` (`name`, `video_player_id`, `video_url_1`, `image_url`, `video_url_2`, `sl_type`, `ordering`, `published`) VALUES
('Big Buck Bunny(Vimeo)', '1', 'https://vimeo.com/1084537', 'http://i.vimeocdn.com/video/20963649_640.jpg', '', 'vimeo', '0', '1')";

$table_name = $wpdb->prefix . "huge_it_video_players";

    $sql_3 = "
INSERT INTO `$table_name` (`id`, `name`, `layout`, `width`, `album_single`, `ordering`, `published`, `ht_videos`) VALUES
(1, 'My First Video Album', 'right', '640', 'album', '1', '1', '1')";

    $wpdb->query($sql_huge_it_video_params);
    $wpdb->query($sql_huge_it_videos);
    $wpdb->query($sql_huge_it_video_players);

    if (!$wpdb->get_var("select count(*) from " . $wpdb->prefix . "huge_it_video_params")) {
        $wpdb->query($sql_1);
    }
    if (!$wpdb->get_var("select count(*) from " . $wpdb->prefix . "huge_it_videos")) {
      $wpdb->query($sql_2);

      $wpdb->query($sql_2_2);
      $wpdb->query($sql_2_3);
    }
    if (!$wpdb->get_var("select count(*) from " . $wpdb->prefix . "huge_it_video_players")) {
      $wpdb->query($sql_3);
    }
	
	if(!$wpdb->get_row("select * from ".$wpdb->prefix."huge_it_video_params WHERE name='video_pl_timeline_background_opacity'")){
		$wpdb->query("INSERT INTO `".$wpdb->prefix."huge_it_video_params` (`name`, `title`,`description`, `value`) VALUES ('video_pl_timeline_background_opacity', 'Timeline background opacity', 'Timeline background opacity', '20')");
	}
	
	if(!$wpdb->get_row("select * from ".$wpdb->prefix."huge_it_video_params WHERE name='video_pl_timeline_buffering_opacity'")){
		$wpdb->query("INSERT INTO `".$wpdb->prefix."huge_it_video_params` (`name`, `title`,`description`, `value`) VALUES ('video_pl_timeline_buffering_opacity', 'Buffer color', 'Buffer color', '40')");
	}
	
	if(!$wpdb->get_row("select * from ".$wpdb->prefix."huge_it_video_params WHERE name='video_pl_controls_panel_opacity'")){
		$wpdb->query("INSERT INTO `".$wpdb->prefix."huge_it_video_params` (`name`, `title`,`description`, `value`) VALUES ('video_pl_controls_panel_opacity', 'Controls', 'Controls', '0')");
	}
}
register_activation_hook(__FILE__, 'hugeit_vp_activate');