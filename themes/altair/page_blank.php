<?php

/**
 * ARCHIVO REVISADO Y CORREGIDO
 */
/**
 * Template Name: Blank Page
 * The main template file for display page.
 *
 * @package WordPress
*/

global $is_no_header;
$is_no_header = TRUE;

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

get_header(); 
?>

<!-- Begin content -->
<div id="page_content_wrapper" class="blank_page">
    <div class="inner">
    	<!-- Begin main content -->
    	<div class="inner_wrapper">
    		<div class="page_content_wrapper" style="margin-top:0">
	    		<div style="width:70%;margin:auto;">
	    		<?php
	    			//Get page header display setting
					$page_hide_header = get_post_meta($current_page_id, 'page_hide_header', true);
					if(empty($page_hide_header))
					{
				?>
	    		<h2 class="ppb_title"><?php the_title(); ?></h1>
	    		<?php
	    			}
	    		?>
	    		<?php 
	    			if ( have_posts() ) {
	    		    while ( have_posts() ) : the_post(); ?>		
	    	
	    		    <?php the_content(); break;  ?>
	
	    		<?php endwhile; 
	    		}
	    		?>
	    		</div>
    		</div>
    	</div>
    	<!-- End main content -->
    </div> 
</div>
<?php get_footer(); ?>