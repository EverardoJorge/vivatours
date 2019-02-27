<?php
	$query_string = '';

    //Get number of tour per page
    $pp_tour_items_page = get_option('pp_tour_items_page');
    if(empty($pp_tour_items_page))
    {
    	$pp_tour_items_page = 9;
    }
    
    //Get all tours items for paging
    global $wp_query;
    if(!is_front_page())
    {
    	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    }
    else
    {
	    $paged = (get_query_var('page')) ? get_query_var('page') : 1;
    }
    
    $args = array(
        'post_type' => 'tours',
        'paged' => $paged,
        'order' => 'ASC',
        'suppress_filters' => 0,
        'posts_per_page' => $pp_tour_items_page,
    );
    
    if(!empty($term))
    {
    	$args['tourcats'] = $term;
    }
    
    if(isset($_GET['start_date_raw']) && !empty($_GET['start_date_raw']))
    {
    	$args['meta_query'][] = array(
        	'key' => 'tour_start_date_raw',
        	'value' => $_GET['start_date_raw'],
        	'type' => 'numeric',
        	'compare' => '>='
        );
    }
    
    if(isset($_GET['end_date_raw']) && !empty($_GET['end_date_raw']))
    {
    	$args['meta_query'][] = array(
        	'key' => 'tour_end_date_raw',
        	'value' => $_GET['end_date_raw'],
        	'type' => 'numeric',
        	'compare' => '<='
        );
    }
    
    if(isset($_GET['budget']) && !empty($_GET['budget']))
    {
    	$args['meta_query'][] = array(
        	'key' => 'tour_price',
        	'value' => $_GET['budget'],
        	'type' => 'numeric',
        	'compare' => '<='
        );
    }
    
    if(isset($_GET['keyword']) && !empty($_GET['keyword']))
    {  
    	$args['meta_query'][] = array(
        	'key' => 'tour_country',
        	'value' => $_GET['keyword'],
        	'type' => 'CHAR',
        	'compare' => 'LIKE'
        );
        $args['wpse18703_title'] = $_GET['keyword'];
    }
    
    query_posts($args);

    $pp_tour_search = get_option('pp_tour_search');
    if(!empty($pp_tour_search) && empty($term))
    {
    	wp_enqueue_script("jquery-ui-core");
    	wp_enqueue_script("jquery-ui-datepicker");
    	wp_enqueue_script("custom_date", get_template_directory_uri()."/js/custom-date.js", false, THEMEVERSION, "all");
?>
<form id="tour_search_form" name="tour_search_form" method="get" action="<?php echo get_the_permalink($id); ?>">
    <div class="tour_search_wrapper">
    	<div class="one_fourth">
    		<label for="keyword"><?php echo _e( 'Destination', THEMEDOMAIN ); ?></label>
    		<input id="keyword" name="keyword" type="text" placeholder="<?php echo _e( 'City, region or keywords', THEMEDOMAIN ); ?>" <?php if(isset($_GET['keyword'])) { ?>value="<?php echo $_GET['keyword']; ?>"<?php } ?>/>
    	</div>
    	<div class="one_fourth">
    		<label for="start_date"><?php echo _e( 'Date', THEMEDOMAIN ); ?></label>
    		<div class="start_date_input">
    			<input id="start_date" name="start_date" type="text" placeholder="<?php echo _e( 'Departure', THEMEDOMAIN ); ?>" <?php if(isset($_GET['start_date'])) { ?>value="<?php echo $_GET['start_date']; ?>"<?php } ?>/>
    			<input id="start_date_raw" name="start_date_raw" type="hidden" <?php if(isset($_GET['start_date_raw'])) { ?>value="<?php echo $_GET['start_date_raw']; ?>"<?php } ?>/>
    			<i class="fa fa-calendar"></i>
    		</div>
    		<div class="end_date_input">
    			<input id="end_date" name="end_date" type="text" placeholder="<?php echo _e( 'Arrival', THEMEDOMAIN ); ?>" <?php if(isset($_GET['end_date'])) { ?>value="<?php echo $_GET['end_date']; ?>"<?php } ?>/>
    			<input id="end_date_raw" name="end_date_raw" type="hidden" <?php if(isset($_GET['end_date_raw'])) { ?>value="<?php echo $_GET['end_date_raw']; ?>"<?php } ?>/>
    			<i class="fa fa-calendar"></i>
    		</div>
    	</div>
    	<div class="one_fourth">
    		<label for="budget"><?php echo _e( 'Max Budgets', THEMEDOMAIN ); ?></label>
    		<input id="budget" name="budget" type="text" placeholder="<?php echo _e( 'USD EX. 100', THEMEDOMAIN ); ?>" <?php if(isset($_GET['budget'])) { ?>value="<?php echo $_GET['budget']; ?>"<?php } ?>/>
    	</div>
    	<div class="one_fourth last">
    		<input id="tour_search_btn" type="submit" value="<?php echo _e( 'Search', THEMEDOMAIN ); ?>"/>
    	</div>
    </div>
</form>
<?php
    }
?>