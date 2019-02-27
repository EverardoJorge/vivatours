<?php
/**
 * ESTE ARCHIVO HA SIDO REVISADO
 */
/**
 * The main template file for display single post page.
 *
 * @package WordPress
*/

/**
*	Get current page id
**/

$current_page_id = $post->ID;

if($post->post_type=='attachment')
{
	get_template_part("single-attachment");
	exit;
}

if($post_type == 'galleries')
{
	//Get gallery template
	$gallery_template = get_post_meta($current_page_id, 'gallery_template', true);
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
}
elseif($post_type == 'tours')
{
	get_template_part("single-tour-f");
	exit;
}
else
{
	$post_layout = get_post_meta($post->ID, 'post_layout', true);
	
	switch($post_layout)
	{
		case "With Right Sidebar":
		default:
			get_template_part("single-post-r");
			exit;
		break;
		
		case "With Left Sidebar":
			get_template_part("single-post-l");
			exit;
		break;
		
		case "Fullwidth":
			get_template_part("single-post-f");
			exit;
		break;
	}
}
?>