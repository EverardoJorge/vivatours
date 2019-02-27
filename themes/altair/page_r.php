<?php
/**
 * ARCHIVO REVISADO Y CORREGIDO
 */
/**
 * Template Name: Page Right Sidebar
 * The main template file for display page.
 *
 * @package WordPress
*/

/**
*	Get Current page object
**/
$page = get_page($post->ID);


/**
*	Get current page id
**/
$current_page_id = '';

if(isset($page->ID))
{
    $current_page_id = $page->ID;
}

$page_style = 'Right Sidebar';
$page_sidebar = get_post_meta($current_page_id, 'page_sidebar', true);

if(empty($page_sidebar))
{
	$page_sidebar = 'Page Sidebar';
}

get_header(); 
?>

<br class="clear"/>

<?php
    //Include custom header feature
	get_template_part("/templates/template-header");
?>

    <div class="inner">
    
    <!-- Begin main content -->
    <div class="inner_wrapper">
        	
        <div class="sidebar_content full_width nopadding">
        	<div class="sidebar_content">
	        	 <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
				 	<?php the_content(); ?>
				 <?php endwhile; ?>
				 <br class="clear"/><br/><br/>
        	</div>
        	<div class="sidebar_wrapper">
	            <div class="sidebar">
	            
	            	<div class="content">
	            
	            		<ul class="sidebar_widget">
	            		<?php dynamic_sidebar($page_sidebar); ?>
	            		</ul>
	            	
	            	</div>
	        
	            </div>
            <br class="clear"/>
        
            <div class="sidebar_bottom"></div>
			</div>
        </div>
    
    </div>
    <!-- End main content -->
</div>
</div>
<br class="clear"/>
<?php get_footer(); ?>
