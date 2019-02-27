<?php
/**
 * The main template file for display archive page.
 *
 * @package WordPress
*/

//Check if portfolio post type then go to another template
$post_type = get_post_type();

if($post_type == 'tours')
{
	//Get tour category page template
	$obj_term = get_term_by('slug', $term, 'tourcats');
	$term_meta = get_option( "taxonomy_term_$obj_term->term_id" );
	$pp_page_template = $term_meta['tourcats_template'];
	
	if(file_exists(get_template_directory() . "/".$pp_page_template.".php"))
	{
		get_template_part($pp_page_template);
	}
	else
	{
		get_template_part("tour-grid-fullwidth");
	}
	exit;
}
else if($post_type == 'galleries')
{
	get_template_part("galleries");
	exit;
}
else
{
	//Get archive page layout setting
	$pp_blog_archive_layout = get_option('pp_blog_archive_layout');
	if(empty($pp_blog_archive_layout))
	{
		$pp_blog_archive_layout = 'blog_r';
	}
	
	$located = locate_template($pp_blog_archive_layout.'.php');
	if (!empty($located))
	{
		get_template_part($pp_blog_archive_layout);
	}
	else
	{
		echo 'Error can\'t find page template you selected';
	}
}
?>