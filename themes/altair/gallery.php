<?php
/**
 * Template Name: Gallery
 * The main template file for display gallery page
 *
 * @package WordPress
*/

$page_gallery_id = get_post_meta($post->ID, 'page_gallery_id', true);
$gallery_template = get_post_meta($page_gallery_id, 'gallery_template', true);
global $page_gallery_id;

switch($gallery_template)
{   
    default:
	case 'Gallery Grid Fullwidth':
	    get_template_part("gallery-grid-fullwidth");
	break;
	
	case 'Gallery Grid Contain':
	    get_template_part("gallery-grid-contain");
	break;
	
	case 'Gallery Fullscreen':
	    get_template_part("gallery-fullscreen");
	break;
}

exit;
?>