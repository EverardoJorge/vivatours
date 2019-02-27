<?php 
	header("Content-Type: text/css");
	$absolute_path = __FILE__;
	$path_to_file = explode( 'wp-content', $absolute_path );
	$path_to_wp = $path_to_file[0];
	require_once( $path_to_wp.'/wp-load.php' );

	//Check if hide portfolio navigation
	$pp_portfolio_single_nav = get_option('pp_portfolio_single_nav');
	if(empty($pp_portfolio_single_nav))
	{
?>
.portfolio_nav { display:none; }
<?php
	}
?>
<?php
	$pp_fixed_menu = get_option('pp_fixed_menu');
	
	if(!empty($pp_fixed_menu))
	{
?>
.top_bar.fixed
{
	position: fixed;
	animation-name: fadeIn;
	-webkit-animation-name: fadeIn;	
	animation-duration: 0.5s;	
	-webkit-animation-duration: 0.5s;
	visibility: visible !important;
}
<?php
}
?>

<?php
	//Hack animation CSS for Safari
	$current_browser = getBrowser();
	
	//If enable animation
	$pp_animation = get_option('pp_animation');
	
	if($pp_animation && isset($current_browser['name']) && $current_browser['name'] != 'Internet Explorer')
	{
?>
@-webkit-keyframes fadeIn { from { opacity:0; } to { opacity:0.99; } }
@-moz-keyframes fadeIn { from { opacity:0; } to { opacity:0.99; } }
@-ms-keyframes fadeIn { from { opacity:0; } to { opacity:0.99; } }
@keyframes fadeIn { from { opacity:0; } to { opacity:0.99; } }
 
.fade-in {
    animation-name: fadeIn;
	-webkit-animation-name: fadeIn;
	-ms-animation-name: fadeIn;	

	animation-duration: 0.7s;	
	-webkit-animation-duration: 0.7s;
	-ms-animation-duration: 0.7s;

	animation-timing-function: ease-out;	
	-webkit-animation-timing-function: ease-out;	
	-ms-animation-timing-function: ease-out;	

	-webkit-animation-fill-mode:forwards; 
    -moz-animation-fill-mode:forwards;
    -ms-animation-fill-mode:forwards;
    animation-fill-mode:forwards;
    
    visibility: visible !important;
}
<?php
	}
	else
	{
?>
	.fadeIn, .fade-in, #supersized, #blog_grid_wrapper .post.type-post, #galleries_grid_wrapper .gallery.type-gallery, .one_half.portfolio2_wrapper, .one_third.portfolio3_wrapper, .one_fourth.portfolio4_wrapper, .mansory_thumbnail, #photo_wall_wrapper .wall_entry, #portfolio_filter_wrapper .element, .gallery_type, .portfolio_type, .one_fourth.gallery4 .mask .mask_circle, .one_half.gallery2 .mask .mask_circle, .one_third.gallery3 .mask .mask_circle, .one_fourth.gallery4 .mask .mask_circle, .post_img .mask .mask_circle, .mansory_thumbnail .mask .mask_circle, .wall_thumbnail .mask .mask_circle, .gallery_img { opacity: 1 !important; visibility: visible !important; }
.isotope-item { z-index: 2 !important; }

.isotope-hidden.isotope-item { pointer-events: none; display: none; z-index: 1 !important; }
<?php
	}
?>

<?php
	for($i=1;$i<=50;$i++)
	{
?>
.animated<?php echo $i; ?>
{
	-webkit-animation-delay: <?php echo $i/5; ?>s;
	-moz-animation-delay: <?php echo $i/5; ?>s;
	animation-delay: <?php echo $i/5; ?>s;
}
<?php
	}
?>