<?php
function EWD_UWPM_Register_WC_Elements() {
	$WooCommerce_Integration = get_option("EWD_UWPM_WooCommerce_Integration");

	if ($WooCommerce_Integration == "Yes") {
		uwpm_register_custom_element('order_name', array('label' => 'Order Name', 'section' => 'woocommerce', 'callback_function' => 'EWD_UWPM_WC_Order_Name'));
		uwpm_register_custom_element('order_status', array('label' => 'Order Status', 'section' => 'woocommerce', 'callback_function' => 'EWD_UWPM_WC_Order_Status'));
		uwpm_register_custom_element('order_date', array('label' => 'Order Date', 'section' => 'woocommerce', 'callback_function' => 'EWD_UWPM_WC_Order_Date'));
		uwpm_register_custom_element('order_phone', array('label' => 'Order Phone Number', 'section' => 'woocommerce', 'callback_function' => 'EWD_UWPM_WC_Order_Phone_Number'));
		uwpm_register_custom_element('order_email', array('label' => 'Order Email', 'section' => 'woocommerce', 'callback_function' => 'EWD_UWPM_WC_Order_Email'));
		uwpm_register_custom_element('order_billing_details', array('label' => 'Order Billing Details', 'section' => 'woocommerce', 'callback_function' => 'EWD_UWPM_WC_Order_Billing_Details'));
		uwpm_register_custom_element('order_shipping_details', array('label' => 'Order Shipping Details', 'section' => 'woocommerce', 'callback_function' => 'EWD_UWPM_WC_Order_Shipping_Details'));
		uwpm_register_custom_element('order_products_list', array('label' => 'List of Products in Order', 'section' => 'woocommerce', 'callback_function' => 'EWD_UWPM_WC_Order_Products_List'));
		uwpm_register_custom_element('order_products_thumbnails', array('label' => 'Thumbnails of Products in Order', 'section' => 'woocommerce', 'callback_function' => 'EWD_UWPM_WC_Order_Products_Thumbnails'));
	}
}
add_action('uwpm_register_custom_element', 'EWD_UWPM_Register_WC_Elements');

function EWD_UWPM_Register_WC_Elements_Section() {
	$WooCommerce_Integration = get_option("EWD_UWPM_WooCommerce_Integration");

	if ($WooCommerce_Integration == "Yes") {
		uwpm_register_custom_element_section('woocommerce', array('label' => 'WooCommerce'));
	}
}
add_action('uwpm_register_custom_element_section', 'EWD_UWPM_Register_WC_Elements_Section');

function EWD_UWPM_WC_Order_Name($Params, $User) {
	global $wpdb;

	if (!isset($Params['post_id'])) {
		$Params['post_id'] = EWD_UWPM_Get_Last_WC_Order($User);
	}

	return $wpdb->get_var($wpdb->prepare("SELECT post_title FROM $wpdb->posts WHERE ID=%d", $Params['post_id']));
}

function EWD_UWPM_WC_Order_Status($Params, $User) {
	global $wpdb;

	if (!isset($Params['post_id'])) {
		$Params['post_id'] = EWD_UWPM_Get_Last_WC_Order($User);
	}

	return $wpdb->get_var($wpdb->prepare("SELECT post_status FROM $wpdb->posts WHERE ID=%d", $Params['post_id']));
}

function EWD_UWPM_WC_Order_Date($Params, $User) {
	global $wpdb;

	if (!isset($Params['post_id'])) {
		$Params['post_id'] = EWD_UWPM_Get_Last_WC_Order($User);
	}

	return $wpdb->get_var($wpdb->prepare("SELECT post_date FROM $wpdb->posts WHERE ID=%d", $Params['post_id']));
}

function EWD_UWPM_WC_Order_Phone_Number($Params, $User) {
	global $wpdb;

	if (!isset($Params['post_id'])) {
		$Params['post_id'] = EWD_UWPM_Get_Last_WC_Order($User);
	}

	return $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE post_id=%d and meta_key='_billing_phone'", $Params['post_id']));
}

function EWD_UWPM_WC_Order_Email($Params, $User) {
	global $wpdb;

	if (!isset($Params['post_id'])) {
		$Params['post_id'] = EWD_UWPM_Get_Last_WC_Order($User);
	}

	return $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE post_id=%d and meta_key='_billing_email'", $Params['post_id']));
}

function EWD_UWPM_WC_Order_Billing_Details($Params, $User) {
	global $wpdb;

	if (!isset($Params['post_id'])) {
		$Params['post_id'] = EWD_UWPM_Get_Last_WC_Order($User);
	}

	$First_Name = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE post_id=%d and meta_key='_billing_first_name'", $Params['post_id']));
	$Last_Name = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE post_id=%d and meta_key='_billing_last_name'", $Params['post_id']));
	$Company = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE post_id=%d and meta_key='_billing_company'", $Params['post_id']));
	$Address_1 = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE post_id=%d and meta_key='_billing_address_1'", $Params['post_id']));
	$Address_2 = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE post_id=%d and meta_key='_billing_address_2'", $Params['post_id']));
	$City = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE post_id=%d and meta_key='_billing_city'", $Params['post_id']));
	$Postal_Code = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE post_id=%d and meta_key='_billing_postcode'", $Params['post_id']));
	$Country = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE post_id=%d and meta_key='_billing_country'", $Params['post_id']));
	$State = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE post_id=%d and meta_key='_billing_state'", $Params['post_id']));

	$Billing_Address = $First_Name . " " . $Last_Name . '<br />';
	$Billing_Address .= ($Company != '' ? $Company . '<br />' : '');
	$Billing_Address .= ($Address_1 != '' ? $Address_1 . '<br />' : '');
	$Billing_Address .= ($Address_2 != '' ? $Address_2 . '<br />' : '');
	$Billing_Address .= ($City != '' ? $City . ', ' : '') . ($State != '' ? $State . ', ' : '') . ($Country != '' ? $Country : '') . '<br />';
	$Billing_Address .= ($Postal_Code != '' ? $Postal_Code : '');

	return $Billing_Address;
}

function EWD_UWPM_WC_Order_Shipping_Details($Params, $User) {
	global $wpdb;

	if (!isset($Params['post_id'])) {
		$Params['post_id'] = EWD_UWPM_Get_Last_WC_Order($User);
	}

	$First_Name = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE post_id=%d and meta_key='_shipping_first_name'", $Params['post_id']));
	$Last_Name = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE post_id=%d and meta_key='_shipping_last_name'", $Params['post_id']));
	$Company = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE post_id=%d and meta_key='_shipping_company'", $Params['post_id']));
	$Address_1 = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE post_id=%d and meta_key='_shipping_address_1'", $Params['post_id']));
	$Address_2 = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE post_id=%d and meta_key='_shipping_address_2'", $Params['post_id']));
	$City = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE post_id=%d and meta_key='_shipping_city'", $Params['post_id']));
	$Postal_Code = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE post_id=%d and meta_key='_shipping_postcode'", $Params['post_id']));
	$Country = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE post_id=%d and meta_key='_shipping_country'", $Params['post_id']));
	$State = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE post_id=%d and meta_key='_shipping_state'", $Params['post_id']));

	$Shipping_Address = $First_Name . " " . $Last_Name . '<br />';
	$Shipping_Address .= ($Company != '' ? $Company . '<br />' : '');
	$Shipping_Address .= ($Address_1 != '' ? $Address_1 . '<br />' : '');
	$Shipping_Address .= ($Address_2 != '' ? $Address_2 . '<br />' : '');
	$Shipping_Address .= ($City != '' ? $City . ', ' : '') . ($State != '' ? $State . ', ' : '') . ($Country != '' ? $Country : '') . '<br />';
	$Shipping_Address .= ($Postal_Code != '' ? $Postal_Code : '');

	return $Shipping_Address;
}


function EWD_UWPM_WC_Order_Products_List($Params, $User) {
	global $wpdb;

	if (!isset($Params['post_id'])) {
		$Params['post_id'] = EWD_UWPM_Get_Last_WC_Order($User);
	}

	$Post_Type = $wpdb->get_var($wpdb->prepare("SELECT post_type FROM $wpdb->posts WHERE ID=%d", $Params['post_id']));
	if ($Post_Type != 'shop_order') {return;}

	$Order = new WC_Order($Params['post_id']);

	$Product_String = "";

	$Products = $Order->get_items();
	foreach ($Products as $Product) {$Product_String .= $Product->get_name() . ",";}

	return trim($Product_String, ",");	
}

function EWD_UWPM_WC_Order_Products_Thumbnails($Params, $User) {
	global $wpdb;

	if (!isset($Params['post_id'])) {
		$Params['post_id'] = EWD_UWPM_Get_Last_WC_Order($User);
	}

	$Post_Type = $wpdb->get_var($wpdb->prepare("SELECT post_type FROM $wpdb->posts WHERE ID=%d", $Params['post_id']));
	if ($Post_Type != 'shop_order') {return;}

	$Order = new WC_Order($Params['post_id']);

	$Products = $Order->get_items();

	$Product_Thumbnails_String = '';
	foreach ($Products as $Product) {
		$Product_Object = wc_get_product($Product->get_product_id());
		$Product_Image = wp_get_attachment_image($Product_Object->get_image_id());
		$Product_Thumbnails_String .= '<div style="max-width:180px; margin-right:12px; float:left;">';
		$Product_Thumbnails_String .= str_replace("//", "http://", $Product_Object->get_image());
		$Product_Thumbnails_String .= '<a href="' . $Product_Object->get_permalink() . '">';
		$Product_Thumbnails_String .= $Product_Object->get_name();
		$Product_Thumbnails_String .= '</a><br/>';
		$Product_Thumbnails_String .= $Product_Object->get_price_html();
		$Product_Thumbnails_String .= '</div>';
	}

	return $Product_Thumbnails_String;	
}

function EWD_UWPM_Get_Last_WC_Order($User) {
	global $wpdb;

	return $wpdb->get_var("
			SELECT ID FROM $wpdb->posts
			INNER JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id
			WHERE $wpdb->postmeta.meta_value = $User->ID
			AND $wpdb->postmeta.meta_key = '_customer_user'
			ORDER BY $wpdb->posts.post_date DESC
			LIMIT 0,1
		");
}

function Test_WC_Integration() {
	$Params = array(
		'Email_ID' => 11160,
		'User_ID' => 20
	);

	EWD_UWPM_Email_User($Params);
}
//add_action('admin_head', 'Test_WC_Integration');