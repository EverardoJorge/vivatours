<?php

function EWD_UWPM_Change_User_Interests($User_ID, $Post_Categories = array(), $Possible_Post_Categories = array(), $UWPM_Categories = array(), $Possible_UWPM_Categories = array(), $WC_Categories = array(), $Possible_WC_Categories = array()) {
	$Current_Post_Categories = get_usermeta($User_ID, 'EWD_UWPM_Post_Interests', true); 
	if (!is_array($Current_Post_Categories)) {$Current_Post_Categories = array();}
	$Current_UWPM_Categories = get_usermeta($User_ID, 'EWD_UWPM_UWPM_Interests', true);
	if (!is_array($Current_UWPM_Categories)) {$Current_UWPM_Categories = array();}
	$Current_WC_Categories = get_usermeta($User_ID, 'EWD_UWPM_WC_Interests', true);
	if (!is_array($Current_WC_Categories)) {$Current_WC_Categories = array();}

	$Impossible_Post_Categories = array_diff($Current_Post_Categories, $Possible_Post_Categories);
	$Impossible_UWPM_Categories = array_diff($Current_UWPM_Categories, $Possible_UWPM_Categories);
	$Impossible_WC_Categories = array_diff($Current_WC_Categories, $Possible_WC_Categories);

	$Updated_Post_Categories = array_unique(array_merge($Post_Categories, $Impossible_Post_Categories));
	$Updated_UWPM_Categories = array_unique(array_merge($UWPM_Categories, $Impossible_UWPM_Categories));
	$Updated_WC_Categories = array_unique(array_merge($WC_Categories, $Impossible_WC_Categories));

	update_usermeta($User_ID, 'EWD_UWPM_Post_Interests', $Updated_Post_Categories);
	update_usermeta($User_ID, 'EWD_UWPM_UWPM_Interests', $Updated_UWPM_Categories);
	update_usermeta($User_ID, 'EWD_UWPM_WC_Interests', $Updated_WC_Categories);
	
	return __("Interests successfully updated.", 'ultimate-wp-mail');
}

function EWD_UWPM_Display_Interests($content) {
	$Display_Post_Interests = get_option("EWD_UWPM_Display_Post_Interests");

	$post = get_post();
	if ($post->post_type != 'post') {return $content;}

	if ($Display_Post_Interests != "None") {
		$Categories = wp_get_post_categories($post->ID);
		$Interests_HTML = do_shortcode("[subscription-interests post_categories='" . implode(",", $Categories) . "']");
		if ($Display_Post_Interests == "Before") {$content = $Interests_HTML . $content;}
		if ($Display_Post_Interests == "After") {$content = $content . $Interests_HTML;}
	}

	return $content;
}
add_filter('the_content', 'EWD_UWPM_Display_Interests');
?>