<?php 
header("content-type: application/x-javascript"); 
$absolute_path = __FILE__;
$path_to_file = explode( 'wp-content', $absolute_path );
$path_to_wp = $path_to_file[0];
require_once( $path_to_wp.'/wp-load.php' );
?>

jQuery(document).ready(function() {
	jQuery('#<?php echo $_GET['video_id']; ?>').mediaelementplayer({
	    <?php if(!isset($_GET['width'])) { ?>
	    videoWidth: jQuery(window).width(),
	    <?php } else { ?>
	    videoWidth: <?php echo $_GET['width']; ?>,
	    <?php } ?>
	    videoHeight: <?php echo $_GET['height']; ?>,
	    enableAutosize: true,
	    startVolume: 0,
	    pauseOtherPlayers: false,
	    success: function (mediaElement, domObject) {
	    	 
	    	<?php
	    		if(!isset($_GET['autoplay']))
	    		{
	    	?>
	    	if (mediaElement.pluginType === 'flash')    {
			    mediaElement.addEventListener('canplay', function() {
			        mediaElement.loop = true;
			        mediaElement.play();
			        jQuery("div.mejs-container .mejs-button").trigger('click');
			   }, false);
			}
			else {
			    mediaElement.loop = true;
			    mediaElement.play();
			}
	    	<?php
	    		}
	    	?>
	    	jQuery('#<?php echo $_GET['video_id']; ?>').css('width', jQuery(window).width()+'px');
	    	
	    	/*var currentVideoHeight = jQuery('#<?php echo $_GET['video_id']; ?>').height();
	    	
	    	jQuery('#<?php echo $_GET['video_id']; ?>').parents('.ppb_transparent_video_bg').css('height', currentVideoHeight+'px');
	    	
	    	jQuery(window).resize(function(){
	    		jQuery('#<?php echo $_GET['video_id']; ?>').css('width', jQuery(window).width()+'px');
	    		
	    		var currentVideoHeight = jQuery('#<?php echo $_GET['video_id']; ?>').height();
	    	
	    	jQuery('#<?php echo $_GET['video_id']; ?>').parents('.ppb_transparent_video_bg').css('height', currentVideoHeight+'px');
	    	});*/
	    	 
	    }
	});
});