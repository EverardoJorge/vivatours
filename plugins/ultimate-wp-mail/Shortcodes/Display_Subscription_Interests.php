<?php
function UWPM_Subscription_Interests_Block() {
    if(function_exists('render_block_core_block')){  
		wp_register_script( 'ewd-uwpm-blocks-js', plugins_url( '../blocks/ewd-uwpm-blocks.js', __FILE__ ), array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor' ) );
		wp_register_style( 'ewd-uwpm-blocks-css', plugins_url( '../blocks/ewd-uwpm-blocks.css', __FILE__ ), array( 'wp-edit-blocks' ), filemtime( plugin_dir_path( __FILE__ ) . '../blocks/ewd-uwpm-blocks.css' ) );
		register_block_type( 'ultimate-wp-mail/ewd-uwpm-subscription-interests-block', array(
			'attributes'      => array(
				'display_interests' => array(
					'type' => 'string',
				),
			),
			'editor_script'   => 'ewd-uwpm-blocks-js',
			'editor_style'  => 'ewd-uwpm-blocks-css',
			'render_callback' => 'EWD_UWPM_Display_Subscription_Interests',
		) );
	}
	// Define our shortcode, too, using the same render function as the block.
	add_shortcode("subscription-interests", "EWD_UWPM_Display_Subscription_Interests");
}
add_action( 'init', 'UWPM_Subscription_Interests_Block' );

function EWD_UWPM_Display_Subscription_Interests($atts) {
	$Display_Interests = get_option("EWD_UWPM_Display_Interests");
	
	// Get the attributes passed by the shortcode, and store them in new variables for processing
	extract( shortcode_atts( array(
			'display_interests' => "",
			'post_categories' => "",
			'uwpm_categories' => "",
			'woocommerce_categories' => "",
			'display_login_message' => "Yes"),
			$atts
		)
	);

	$Post_Categories = array();
	$UWPM_Categories = array();
	$WooCommerce_Categories = array();
	if ($display_interests != "" or $post_categories != "" or $uwpm_categories != "" or $woocommerce_categories != "") {
		if ($post_categories != "") {$Selected_Post_Categories = explode(",", $post_categories);}
		if ($uwpm_categories != "") {$Selected_UWPM_Categories = explode(",", $uwpm_categories);}
		if ($woocommerce_categories != "") {$Selected_WooCommerce_Categories = explode(",", $woocommerce_categories);}

		if ($display_interests != "") {$Overall_Interests = explode(",", $display_interests);}
	}
	else {$Overall_Interests = $Display_Interests;}

	if (isset($Overall_Interests) and is_array($Overall_Interests)) {
		if (in_array("post_categories", $Overall_Interests)) {$Get_Post_Categories = true;}
		if (in_array("uwpm_categories", $Overall_Interests)) {$Get_UWPM_Categories = true;}
		if (in_array("woocommerce_categories", $Overall_Interests)) {$Get_WooCommerce_Categories = true;}
	}

	if (empty($Selected_Post_Categories) and $Get_Post_Categories) {
		$Categories = get_terms(array('taxonomy' => 'category', 'hide_empty' => false));

		foreach ($Categories as $Category) {$Post_Categories[$Category->term_id] = $Category->name;}
	}
	elseif (!empty($Selected_Post_Categories)) {
		$Categories = get_terms(array('taxonomy' => 'category', 'hide_empty' => false));

		foreach ($Categories as $Category) {
			if (in_array($Category->term_id, $Selected_Post_Categories)) {$Post_Categories[$Category->term_id] = $Category->name;}
		}
	}

	if (empty($Selected_UWPM_Categories) and $Get_UWPM_Categories) {
		$Categories = get_terms(array('taxonomy' => 'uwpm-category', 'hide_empty' => false));

		foreach ($Categories as $Category) {$UWPM_Categories[$Category->term_id] = $Category->name;}
	}
	elseif (!empty($Selected_UWPM_Categories)) {
		$Categories = get_terms(array('taxonomy' => 'uwpm-category', 'hide_empty' => false));

		foreach ($Categories as $Category) {
			if (in_array($Category->term_id, $Selected_UWPM_Categories)) {$UWPM_Categories[$Category->term_id] = $Category->name;}
		}
	}

	if (empty($Selected_WooCommerce_Categories) and $Get_WooCommerce_Categories) {
		$Categories = get_terms(array('taxonomy' => 'product_cat', 'hide_empty' => false));

		foreach ($Categories as $Category) {$WooCommerce_Categories[$Category->term_id] = $Category->name;}
	}
	elseif (!empty($Selected_WooCommerce_Categories)) {
		$Categories = get_terms(array('taxonomy' => 'product_cat', 'hide_empty' => false));

		foreach ($Categories as $Category) {
			if (in_array($Category->term_id, $Selected_WooCommerce_Categories)) {$WooCommerce_Categories[$Category->term_id] = $Category->name;}
		}
	}

	$User_ID = get_current_user_id();

	$Post_Interests = get_usermeta($User_ID, 'EWD_UWPM_Post_Interests', true);
	if (!is_array($Post_Interests)) {$Post_Interests = array();}
	$UWPM_Interests = get_usermeta($User_ID, 'EWD_UWPM_UWPM_Interests', true);
	if (!is_array($UWPM_Interests)) {$UWPM_Interests = array();}
	$WC_Interests = get_usermeta($User_ID, 'EWD_UWPM_WC_Interests', true);
	if (!is_array($WC_Interests)) {$WC_Interests = array();}

	$Column_Count = 0;

	$ReturnString = "<div class='ewd-uwpm-subscription-interests'>";

	$Login_To_Select_Topics_Label = get_option("EWD_UWPM_Login_To_Select_Topics_Label");
	if($Login_To_Select_Topics_Label == ''){ $Login_To_Select_Topics_Label = __('Log in to your account so that you can subscribe to topics you\'re interested in!', 'ultimate-wp-mail'); }
	$Select_Topics_Label = get_option("EWD_UWPM_Select_Topics_Label");
	if($Select_Topics_Label == ''){ $Select_Topics_Label = __('Select topics you\'re interested in below to receive emails when new items are posted!', 'ultimate-wp-mail'); }

	if (!is_user_logged_in()) {
		$ReturnString .= "<div class='ewd-uwpm-si-sign-in'>";
		$ReturnString .= $Login_To_Select_Topics_Label;
		$ReturnString .= "<a href='" . wp_login_url( get_permalink() ) . "' title='Login'>" . __('Login', 'ultimate-wp-mail') . "</a>";
		$ReturnString .= "</div>";
	}

	else {
		$ReturnString .= "<div class='ewd-uwpm-si-explanation'>";
		$ReturnString .= $Select_Topics_Label;
		$ReturnString .= "</div>";

		$ReturnString .= "<form>";
		
		if (!empty($Post_Categories)) {
			$ReturnString .= "<div class='ewd-uwpm-si-post-categories ewd-uwpm-si-columns-%COLUMN_COUNT%'>";
			$ReturnString .= "<h3>" . __('Post Categories', 'ultimate-wp-mail') . "</h3>";
			foreach ($Post_Categories as $ID => $Category_Name) {
				$ReturnString .= "<div class='ewd-uwpm-subscription-interests-item'>";
				$ReturnString .= "<input type='hidden' class='ewd-uwpm-si-possible-post-category' value='" . $ID . "' />";
				$ReturnString .= "<input type='checkbox' class='ewd-uwpm-si-post-category' value='" . $ID . "' " . (in_array($ID, $Post_Interests) ? 'checked' : '') . ">";
				$ReturnString .= "<span>" . $Category_Name . "</span>";
				$ReturnString .= "</div>";
			}
			$ReturnString .= "</div>";
			$Column_Count++;
		}

		if (!empty($UWPM_Categories)) {
			$ReturnString .= "<div class='ewd-uwpm-si-uwpm-categories ewd-uwpm-si-columns-%COLUMN_COUNT%'>";
			$ReturnString .= "<h3>" . __('Email Categories', 'ultimate-wp-mail') . "</h3>";
			foreach ($UWPM_Categories as $ID => $Category_Name) {
				$ReturnString .= "<div class='ewd-uwpm-subscription-interests-item'>";
				$ReturnString .= "<input type='hidden' class='ewd-uwpm-si-possible-uwpm-category' value='" . $ID . "' />";
				$ReturnString .= "<input type='checkbox' class='ewd-uwpm-si-uwpm-category' value='" . $ID . "' " . (in_array($ID, $UWPM_Interests) ? 'checked' : '') . ">";
				$ReturnString .= "<span>" . $Category_Name . "</span>";
				$ReturnString .= "</div>";
			}
			$ReturnString .= "</div>";
			$Column_Count++;
		}

		if (!empty($WooCommerce_Categories)) {
			$ReturnString .= "<div class='ewd-uwpm-si-wc-categories ewd-uwpm-si-columns-%COLUMN_COUNT%'>";
			$ReturnString .= "<h3>" . __('Product Categories', 'ultimate-wp-mail') . "</h3>";
			foreach ($WooCommerce_Categories as $ID => $Category_Name) {
				$ReturnString .= "<div class='ewd-uwpm-subscription-interests-item'>";
				$ReturnString .= "<input type='hidden' class='ewd-uwpm-si-possible-wc-category' value='" . $ID . "' />";
				$ReturnString .= "<input type='checkbox' class='ewd-uwpm-si-wc-category' value='" . $ID . "' " . (in_array($ID, $WC_Interests) ? 'checked' : '') . ">";
				$ReturnString .= "<span>" . $Category_Name . "</span>";
				$ReturnString .= "</div>";
			}
			$ReturnString .= "</div>";
			$Column_Count++;
		}

		$ReturnString = str_replace('%COLUMN_COUNT%', $Column_Count, $ReturnString);

		$ReturnString .= "<div class='ewd-uwpm-clear'></div>";
		$ReturnString .= "<button class='ewd-uwpm-topics-sign-up'>" . __('Subscribe', 'ultimate-wp-mail') . "</button>";
		$ReturnString .= "</form>";
	}

	$ReturnString .= "</div>";

	return $ReturnString;
}



