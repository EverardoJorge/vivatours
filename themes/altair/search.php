<?php
/**
 * ESTE ARCHIVO HA SIDO REVIDADO
 */
/**
 * The main template file for display blog page.
 *
 * @package WordPress
*/

get_header(); 
?>

<br class="clear"/>
<div id="page_caption">
	<div class="page_title_wrapper">
		<h1><?php printf( __( 'Resultados &quot;%s&quot;', '' ), '' . get_search_query() . '' ); ?></h1>
	</div>
</div>
<br class="clear"/>

<?php
$page_sidebar = 'Search Sidebar';
?>

<!-- Begin content -->

<div id="page_content_wrapper">
    
    <div class="inner">

    	<!-- Begin main content -->
    	<div class="inner_wrapper">
    		
    		<div class="sidebar_content full_width nopadding">

    			<div class="sidebar_content full_width nopadding">
    			
    			<div class="search_form_wrapper">
	    			<h5><?php _e( 'Nueva busqueda', THEMEDOMAIN ); ?></h5>
	    			<?php _e( "Si no encontraste lo que buscabas, intenta de nuevo.", THEMEDOMAIN ); ?><br/>
<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" >

    <input type="text" class="field" name="s" id="s" size="20" placeholder="<?php _e( 'Busca tu viaje por ciudad', THEMEDOMAIN  ); ?> "  autocomplete="off"/> 
 <?php //get_terms_dropdown( 'tourcats' ); ?>
 <select name="tourcats" class="field" style="padding:7px; border:2px #CCC solid;">
   <option value="europa-2018" <?php=($att['tour'] == 'europa-2018') ? "selected" : "";?>>Europa 2018</option>
   <option value="medio-oriente-2018" <?php=($att['tour'] == 'medio-oriente-2018') ? "selected" : "";?>>Medio Oriente 2018</option>
   <option value="lejano-oriente-y-asia-central-2018-2019" selected="selected">Lejano Oriente y Asia central 2018-2019</option>
   <option value="mexico-2018" <?php=($att['tour'] == 'mexico-2018') ? "selected" : "";?>>México 2018</option>
   <option value="viajes-de-autor" <?php=($att['tour'] == 'viajes-de-autor') ? "selected" : "";?>>Viajes de Autor</option>
   <option value="turismo-religioso-2018" <?php=($att['tour'] == 'turismo-religioso-2018') ? "selected" : "";?>>Turismo Religioso</option>
 </select>
 &nbsp;&nbsp;&nbsp;<input type="submit" class="submit" name="submit" id="searchsubmit" value="<?php _e( 'Buscar' ); ?>" />

</form>

    			</div>
		<table class="tablesorter {sortlist: [[1,1],[2,1]]} table table-hover table-condensed" ><thead>
			<tr><th  width="80%">PROGRAMA</th><th width="10%">DURACIÓN</th><th width="10%">PRECIO</th></tr></thead><tbody>
<?php

if (have_posts()) : while (have_posts()) : the_post();
$dias= get_post_meta($post->ID, 'one_fifth', true);
 $tour_price= get_post_meta($post->ID, 'tour_price', true);
 
 $tour_price_currency= get_post_meta($post->ID, 'tour_price_currency', true);
 $tour_country= get_post_meta($post->ID, 'tour_country', true);
 //Get number of your days

			$tour_start_date_raw= get_post_meta($post->ID, 'tour_start_date_raw', true);

			$tour_end_date_raw= get_post_meta($post->ID, 'tour_end_date_raw', true);

			$tour_days = pp_date_diff($tour_start_date_raw, $tour_end_date_raw);
 if($tour_days > 0)

			{

				$tour_days = intval($tour_days+1).' '.__( 'Días', THEMEDOMAIN );

			}

			else

			{

				$tour_days = intval($tour_days+1).' '.__( 'Día', THEMEDOMAIN );

			} 
?>

<!-- Begin each blog post -->

<!--<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>-->
<tr>
<td>	<div class="post_wrapper"> 
	    
	    <div class="post_content_wrapper">
	    
			<div class="one">
				              
                
				<div class="post_type_icon">
					<a href="<?php the_permalink(); ?>" title="<?php echo $post_type_title; ?>" class="tooltip">
         <?php=get_the_post_thumbnail( $post->ID, 'thumbnail' )?><!--<i class="fa <?php echo $post_type_class; ?>"></i>-->
					</a>
				</div>
			    <div class="post_header">
			    	<h6><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php relevanssi_the_title(); ?></a>  </h6>
                    
                      
			    	<div class="post_detail"><?=$tour_country?>
					    <?php // echo _e( 'Posted On', THEMEDOMAIN ); ?>&nbsp;<?php // echo get_the_time(THEMEDATEFORMAT); ?>
					    <?php
					    	$author_ID = get_the_author_meta('ID');
					    	$author_name = get_the_author();
					    	$author_url = get_author_posts_url($author_ID);
					    	
					    	if(!empty($author_name))
					    	{
					    ?>
 <?php // echo _e( 'By', THEMEDOMAIN ); ?><!--<a href="<?php // echo $author_url; ?>">--><?php // echo $author_name; ?><!--</a>-->
					    <?php
					    	}
					    ?>
					    <?php //echo _e( 'And has', THEMEDOMAIN ); ?>&nbsp;<a href="<?php comments_link(); ?>"><?php // comments_number(__('No Comment', THEMEDOMAIN), __('1 Comment', THEMEDOMAIN), __('% Comments', THEMEDOMAIN)); ?></a>
					</div>
				    
				    <?php
				    	the_excerpt();
				    ?>
                    



<span><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">Leer mas...</a></span>
			    </div>
			</div>
	    </div>
	    
	</div></td>
<td align="center" valign="middle"><?php=$tour_days;?></td>
<td align="center" valign="middle" ><?php=$tour_price_currency?><?=$tour_price;?></td>
</tr>
<!--</div>-->
<?php endwhile; endif; ?>
</tbody>
</table>

    	<?php
		    if($wp_query->max_num_pages > 1)
		    {
		    	if (function_exists("wpapi_pagination")) 
		    	{
		?>
				<br class="clear"/>
		<?php
		    	    wpapi_pagination($wp_query->max_num_pages);
		    	}
		    	else
		    	{
		    	?>
		    	    <div class="pagination"><p><?php posts_nav_link(' '); ?></p></div>
		    	<?php
		    	}
		    ?>
		    <div class="pagination_detail">
		     	<?php
		     		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		     	?>
		     	<?php _e( 'Page', THEMEDOMAIN ); ?> <?php echo $paged; ?> <?php _e( 'of', THEMEDOMAIN ); ?> <?php echo $wp_query->max_num_pages; ?>
		     </div>
		     <?php
		     }
		?>
    	
    	<br class="clear"/><br/>	
    	</div>
    	
    		<div class="sidebar_wrapper">
    		
    			<div class="sidebar_top"></div>
    		
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
<br class="clear"/>
<?php get_footer(); ?>