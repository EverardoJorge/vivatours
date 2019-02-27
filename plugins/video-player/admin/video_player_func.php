<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function hugeit_vp_show_video_player() {
	  
  global $wpdb;
	
	if(isset($_POST['search_events_by_title'])){
	$search_tag = esc_html(stripslashes($_POST['search_events_by_title']));
	}
	else {
	$search_tag = '';
	}
	$cat_row_query="SELECT id,name FROM ".$wpdb->prefix."huge_it_video_players";
	$cat_row=$wpdb->get_results($cat_row_query);

	 $query =$wpdb->prepare("SELECT  a.* ,  COUNT(b.id) AS count, g.par_name AS par_name FROM ".$wpdb->prefix."huge_it_video_players  AS a LEFT JOIN ".$wpdb->prefix."huge_it_video_players AS b ON a.id = b.ht_videos 
LEFT JOIN (SELECT  ".$wpdb->prefix."huge_it_video_players.ordering as ordering,".$wpdb->prefix."huge_it_video_players.id AS id, COUNT( ".$wpdb->prefix."huge_it_videos.video_player_id ) AS prod_count
FROM ".$wpdb->prefix."huge_it_videos, ".$wpdb->prefix."huge_it_video_players
WHERE ".$wpdb->prefix."huge_it_videos.video_player_id = ".$wpdb->prefix."huge_it_video_players.id
GROUP BY ".$wpdb->prefix."huge_it_videos.video_player_id) AS c ON c.id = a.id LEFT JOIN
(SELECT ".$wpdb->prefix."huge_it_video_players.name AS par_name,".$wpdb->prefix."huge_it_video_players.id FROM ".$wpdb->prefix."huge_it_video_players) AS g
 ON a.ht_videos=g.id WHERE a.name LIKE %s  group by a.id  ","%".$search_tag."%");

	$rows = $wpdb->get_results($query);

	$rows = hugeit_vp_open_cat_in_tree($rows);
		$query ="SELECT  ".$wpdb->prefix."huge_it_video_players.ordering,".$wpdb->prefix."huge_it_video_players.id, COUNT( ".$wpdb->prefix."huge_it_videos.video_player_id ) AS prod_count
	FROM ".$wpdb->prefix."huge_it_videos, ".$wpdb->prefix."huge_it_video_players
	WHERE ".$wpdb->prefix."huge_it_videos.video_player_id = ".$wpdb->prefix."huge_it_video_players.id
	GROUP BY ".$wpdb->prefix."huge_it_videos.video_player_id " ;
		$prod_rows = $wpdb->get_results($query);

	foreach($rows as $row)
	{
		foreach($prod_rows as $row_1)
		{
			if ($row->id == $row_1->id)
			{
				$row->ordering = $row_1->ordering;
				$row->prod_count = $row_1->prod_count;
			}
		}
	}
	$pageNav = '';
	$sort = '';
		$cat_row=hugeit_vp_open_cat_in_tree($cat_row);
			hugeit_vp_html_show_video_players( $rows, $pageNav,$sort,$cat_row);
}

function hugeit_vp_open_cat_in_tree($catt, $tree_problem='', $hihiih=1){

	static $trr_cat=array();
	if(!isset($search_tag))
	$search_tag='';
	if($hihiih)
	$trr_cat=array();
	foreach($catt as $local_cat){
		$local_cat->name=$tree_problem.$local_cat->name;
		array_push($trr_cat,$local_cat);
	}
	return $trr_cat;
}

function hugeit_vp_edit_video_player($id) {
	  global $wpdb;
		if(isset($_GET["removeslide"])){
			if($_GET["removeslide"] != ''){
				$idfordelete = absint($_GET["removeslide"]);
				$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."huge_it_videos  WHERE id = %d ", $idfordelete));
			}
		}
	
	   $query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_it_video_players WHERE id= %d",$id);
	   $row=$wpdb->get_row($query);
	   $cat_row=$wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_it_video_players WHERE id!= %d", $id));
       $cat_row=hugeit_vp_open_cat_in_tree($cat_row);
	   	  $query="SELECT name,ordering FROM ".$wpdb->prefix."huge_it_video_players ORDER BY `ordering` ";
	   $ord_elem=$wpdb->get_results($query);
	   $query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_it_videos where video_player_id = %d order by ordering ASC  ",$row->id);
			   $rowim=$wpdb->get_results($query);
			   if(isset($_GET["addslide"])){
			   if($_GET["addslide"] == 1){
			$table_name = $wpdb->prefix . "huge_it_videos";
				$sql_2 = "
			INSERT INTO 		
			`" . $table_name . "` ( `name`, `video_player_id`, `video_url_1`, `image_url`, `video_url_2`, `sl_type`, `video_width`, `ordering`, `published`) VALUES
			( '', '". absint($row->id)."', '', '', '', 'par_TV', 2, '1' )";

			$wpdb->query($sql_2);
	   		}
	   }
	  $query="SELECT * FROM ".$wpdb->prefix."huge_it_video_players order by id ASC";
			   $rowsld=$wpdb->get_results($query);
			  
			    $query = "SELECT *  from " . $wpdb->prefix . "huge_it_video_params ";

    $rowspar = $wpdb->get_results($query);

    $paramssld = array();
    foreach ($rowspar as $rowpar) {
        $key = $rowpar->name;
        $value = $rowpar->value;
        $paramssld[$key] = $value;
    }

	 $query="SELECT * FROM ".$wpdb->prefix."posts where post_type = 'post' and post_status = 'publish' order by id ASC";
			   $rowsposts=$wpdb->get_results($query);
	 
	 $rowsposts8 = '';
	 $postsbycat = '';
	 if(isset($_POST["iframecatid"])){
	 	  $query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."term_relationships where term_taxonomy_id = %d order by object_id ASC", sanitize_text_field($_POST["iframecatid"]));
		$rowsposts8=$wpdb->get_results($query);

		   foreach($rowsposts8 as $rowsposts13){
			   $query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."posts where post_type = 'post' and post_status = 'publish' and ID = %d  order by ID ASC",$rowsposts13->object_id);
			   $rowsposts1=$wpdb->get_results($query);
			   $postsbycat = $rowsposts1;
	 		}
	 }
    hugeit_vp_html_edit_video_player($ord_elem, $row, $cat_row, $rowim, $rowsld, $paramssld, $rowsposts, $rowsposts8, $postsbycat);
}
  
function hugeit_vp_add_video_player() {
	global $wpdb;
	
	$query="SELECT name,ordering FROM ".$wpdb->prefix."huge_it_video_players ORDER BY `ordering`";
	$ord_elem=$wpdb->get_results($query); 
	$cat_row=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."huge_it_video_players");
	$cat_row=hugeit_vp_open_cat_in_tree($cat_row);

	$table_name = $wpdb->prefix . "huge_it_video_players";
    $sql_2 = "
INSERT INTO 

`" . $table_name . "` ( `name`, `album_single`, `layout`, `width`, `ordering`, `align`, `margin_top`, `margin_bottom`, `autoplay`, `preload`, `published`, `ht_videos`) VALUES
( 'New Video Album', 'single', 'right', '640', '1', 'left', '0', '0', '0', '0', '300', '1')";

    $wpdb->query($sql_2);

   $query="SELECT * FROM ".$wpdb->prefix."huge_it_video_players order by id ASC";
			   $rowsldcc=$wpdb->get_results($query);
			   $last_key = key( array_slice( $rowsldcc, -1, 1, TRUE ) );
			   
	foreach($rowsldcc as $key=>$rowsldccs){
		if($last_key == $key){
			$rowsldccs_id = absint($rowsldccs->id);

			$url_apply = admin_url( 'admin.php?page=hugeit_vp_video_player&id='. $rowsldccs_id . '&task=apply' );
			$url_apply = wp_nonce_url( $url_apply, 'hugeit_vp_add_new_' . $rowsldccs_id, 'hugeit_vp_save_data' );
			$url_apply = html_entity_decode( $url_apply );
			wp_redirect( wp_sanitize_redirect( $url_apply ) );
		}
	}
	
	html_add_video_player($ord_elem, $cat_row);
}

function hugeit_vp_video_player_video($id){
	global $wpdb;
    $protocol = is_ssl() ? 'https:' : 'http:';

	$id = absint($id);
	if(isset($_POST["show_video_url_1"]) or isset($_POST["show_video_url_2"])){
		if($_POST["show_video_url_1"] != ''){
			
		$youtubeviminsert = esc_url($_POST["show_video_url_1"]);
		$youtubeinsert = explode("youtu", $youtubeviminsert);
		$vimeoinsert = explode("vimeo", $youtubeviminsert);
	
		$table_name = $wpdb->prefix . "huge_it_videos";
		$query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_it_video_players WHERE id= %d",$id);
		$row=$wpdb->get_row($query);
		$query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_it_videos where video_player_id = %s ", $row->id);
		$rowplusorder=$wpdb->get_results($query);
		foreach ($rowplusorder as $key=>$rowplusorders){
			if($rowplusorders->ordering == 0){
				$rowplusorderspl = 1;
				$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_it_videos SET ordering = %d WHERE id = %s ", $rowplusorderspl, $rowplusorders->id));
			}else{
				$rowplusorderspl=$rowplusorders->ordering+1;
				$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_it_videos SET ordering = %d WHERE id = %s ", $rowplusorderspl, $rowplusorders->id));
			}
		}
		if(isset($youtubeinsert[1])){
			$video_thumb_url = sanitize_text_field(hugeit_vp_get_youtube_thumb_id_from_url($_POST["show_video_url_1"]));
			$sql_video = "INSERT INTO 
			`" . $table_name . "` ( `name`, `video_player_id`, `video_url_1`, `image_url`, `sl_type`, `ordering`, `published`) VALUES 
			( '".sanitize_text_field($_POST["show_title"])."', '".$id."', '".esc_url($_POST["show_video_url_1"])."', '".$protocol."//img.youtube.com/vi/".$video_thumb_url."/mqdefault.jpg', 'youtube', '0', '1' )";
		}else{
			if(isset($vimeoinsert[1])){
				$vidid = explode( "/", esc_url($_POST["show_video_url_1"]));
				$vidid = end($vidid);
				$hash = file_get_contents($protocol."//vimeo.com/api/v2/video/".$vidid.".php");
				$hash = unserialize($hash);
				$video_thumb_url = esc_url($hash[0]['thumbnail_large']);
				if($_POST["show_title"]==""){
					$title = esc_sql($hash[0]['title']);
				}else{
					$title = sanitize_text_field($_POST["show_title"]);
				}
				$sql_video = "INSERT INTO 
				`" . $table_name . "` ( `name`, `video_player_id`, `video_url_1`, `image_url`, `sl_type`, `ordering`, `published`) VALUES 
				( '".$title."', '".$id."', '".esc_url($_POST["show_video_url_1"])."', '".$video_thumb_url."', 'vimeo', '0', '1' )";
			}else{
				$sql_video = "INSERT INTO 
				`" . $table_name . "` ( `name`, `video_player_id`, `video_url_1`, `image_url`, `sl_type`, `ordering`, `published`) VALUES 
				( '".sanitize_text_field($_POST["show_title"])."', '".$id."', '".esc_url($_POST["show_video_url_1"])."', '".esc_url($_POST["show_video_image_url"])."', 'video', '0', '1' )";
			}
		}
		$wpdb->query($sql_video);
	  }
	}
	hugeit_vp_html_video_player_video();
}

function hugeit_vp_get_youtube_thumb_id_from_url($url){
	if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
		return $match[1];
	}
}
function hugeit_vp_remove_video_player($id){
	global $wpdb;
	 $sql_remov_tag=$wpdb->prepare("DELETE FROM ".$wpdb->prefix."huge_it_video_players WHERE id = %d", $id);
	 if(!$wpdb->query($sql_remov_tag))
	 {
		  ?>
		  <div id="message" class="error"><p>video_player Deleted</p></div>
		  <?php
	 }
	 else{
	 ?>
	 <div class="updated"><p><strong><?php _e('Item Deleted.' ); ?></strong></p></div>
	 <?php
	 }
}

function hugeit_vp_apply_cat($id){
        $id = absint($id);

		 global $wpdb;
		 if(!is_numeric($id)){
			 echo 'insert numerc id';
		 	return '';
		 }
		 if(!(isset($_POST['sl_width']) && isset($_POST["album_name"]) ))
		 {
			echo '';
		 }
      
	if(isset($_POST["content"])){
	$script_cat = preg_replace('#<script(.*?)>(.*?)</script>#is', '', sanitize_text_field(stripslashes($_POST["content"])));
	}
        if(isset($_POST["album_name"])){
			if($_POST["album_name"] != ''){
			
	$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_it_video_players SET  name = %s  WHERE id = %d ", sanitize_text_field($_POST["album_name"]), $id));
	$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_it_video_players SET  album_single = %s  WHERE id = %d ", sanitize_text_field($_POST["album_single"]), $id));
	$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_it_video_players SET  layout = %s  WHERE id = %d ", sanitize_text_field($_POST["album_playlist_layout"]), $id));
	$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_it_video_players SET  width = %s  WHERE id = %d ", absint($_POST["album_width"]), $id));
	$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_it_video_players SET  autoplay = %s  WHERE id = %d ", absint($_POST["album_autoplay"]), $id));
	$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_it_video_players SET  ordering = '1'  WHERE id = %d ", $id));
			}
        }
		
	$query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_it_video_players WHERE id = %d", $id);
    $row=$wpdb->get_row($query);

	$query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_it_videos where video_player_id = %d order by id ASC", $row->id);
    $rowim=$wpdb->get_results($query);
			   
    foreach ($rowim as $key=>$rowimages){
		$rowimages_id = absint($rowimages->id);
		if(isset($_POST["order_by_". $rowimages_id .""])){
			$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_it_videos SET  ordering = %d  WHERE ID = %d ", absint($_POST["order_by_".$rowimages_id.""]), $rowimages_id));
			$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_it_videos SET  name = '%s'  WHERE ID = %d ", sanitize_text_field($_POST["titleimage".$rowimages_id.""]), $rowimages_id));
			$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_it_videos SET  video_url_1 = '%s'  WHERE ID = %d ", esc_url($_POST["for_video_1". $rowimages_id .""]), $rowimages_id));
			$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_it_videos SET  image_url = '%s'  WHERE ID = %d ", esc_url($_POST["imagess". $rowimages_id .""]), $rowimages_id));
		}
	}
	if (isset($_POST['params'])) {
		  $params = $_POST['params'];
		  foreach ($params as $key => $value) {
          $wpdb->update($wpdb->prefix . 'huge_it_video_params',
              array('value' => $value),
              array('name' => $key),
              array('%s')
          );
      }
    }
	   
	   if(isset($_POST["imagess"])){
	   if($_POST["imagess"] != ''){
				   		   $query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_it_videos where video_player_id = %d order by id ASC", $row->id);
			   $rowim=$wpdb->get_results($query);
	  foreach ($rowim as $key=>$rowimages){
	  $orderingplus = $rowimages->ordering+1;
	  $wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_it_videos SET  ordering = %d  WHERE id = %d ", $orderingplus, $rowimages->id));
	  }
	$table_name = $wpdb->prefix . "huge_it_videos";
	$imagesnewuploader = explode(";;;", $_POST["imagess"]);
	array_pop($imagesnewuploader);
	foreach($imagesnewuploader as $imagesnewupload){
	
    $sql_2 = "
INSERT INTO 
`" . $table_name . "` ( `name`, `video_player_id`, `video_url_1`, `image_url`, `video_url_2`, `sl_type`, `ordering`, `published`) VALUES
( '', '".absint($row->id)."', '".$imagesnewupload."', '', '', 'video', '0', '1' )";

      $wpdb->query($sql_2);
		}
        }
    }
	   
	if(isset($_POST["posthuge-it-description-length"])){
	 $wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_it_video_players SET  published = %d WHERE id = %d ", absint($_POST["posthuge-it-description-length"]), absint($_GET['id'])));
	}
	?>
	<div class="updated"><p><strong><?php _e('Item Saved'); ?></strong></p></div>
	<?php
    return true;
}
?>