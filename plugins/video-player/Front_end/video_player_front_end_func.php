<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function hugeit_vp_show_published_video_player_1($id)
{
	global $wpdb;
	$query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_it_videos where video_player_id = '%d' order by ordering ASC",$id);
	$videos=$wpdb->get_results($query);
	$query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_it_video_players where id = '%d' order by id ASC",$id);
	$video_player=$wpdb->get_results($query);   
	$query="SELECT * FROM ".$wpdb->prefix."huge_it_video_params";
    $rowspar = $wpdb->get_results($query);
    $paramssld = array();
    foreach ($rowspar as $rowpar) {
        $key = $rowpar->name;
        $value = $rowpar->value;
        $paramssld[$key] = $value;
    }
	return hugeit_vp_front_end_video_player($videos, $paramssld, $video_player);
}
?>