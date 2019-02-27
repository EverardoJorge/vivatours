<?php header("content-type: application/x-javascript"); ?>
<?php
$absolute_path = __FILE__;
$path_to_file = explode( 'wp-content', $absolute_path );
$path_to_wp = $path_to_file[0];
require_once( $path_to_wp.'/wp-load.php' );
?>
jQuery(document).ready(function(){ 
	jQuery('.testimonial_slider_wrapper').flexslider({
	      animation: "fade",
	      animationLoop: true,
	      itemMargin: 0,
	      minItems: 1,
	      maxItems: 1,
	      slideshow: true,
	      controlNav: true,
	      smoothHeight: false,
	      pauseOnHover: true,
	      directionNav: false,
	      move: 1
	});
});