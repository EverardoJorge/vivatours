<?php
function EWD_FEUP_Mailchimp_Subscriber_Sync($Options) {
    global $wpdb;
    global $ewd_feup_user_table_name;
    global $ewd_feup_fields_table_name;
    global $ewd_feup_user_fields_table_name;

    $Username_Is_Email = get_option("EWD_FEUP_Username_Is_Email");
    $Email_Field = get_option("EWD_FEUP_Email_Field");

    $apiKey = get_option("EWD_FEUP_Mailchimp_API_Key");
    $listId = get_option("EWD_FEUP_Mailchimp_List_ID");
    $Mailchimp_Sync_Fields = get_option("EWD_FEUP_Mailchimp_Sync_Fields");
    if (!is_array($Mailchimp_Sync_Fields)) {$Mailchimp_Sync_Fields = array();}

    if (!isset($Options['User_ID'])) {return;}

    if ($Username_Is_Email == "Yes") {$Email = $wpdb->get_var($wpdb->prepare("SELECT Username FROM $ewd_feup_user_table_name WHERE User_ID=%d", $Options['User_ID']));} 
    else {
        $Field_ID = $wpdb->get_var($wpdb->prepare("SELECT Field_ID FROM $ewd_feup_fields_table_name WHERE Field_Name=%s", $Email_Field));
        $Email = $wpdb->get_var($wpdb->prepare("SELECT Field_Value FROM $ewd_feup_user_fields_table_name WHERE User_ID=%d and Field_ID=%d", $Options['User_ID'], $Field_ID));
    }

    $User_Data = array(
        'email_address' => $Email,
        'status'        => "subscribed",
        'merge_fields'  => array()
    );

     foreach ($Mailchimp_Sync_Fields as $Mailchimp_Sync_Field) {
        $Value = $wpdb->get_var($wpdb->prepare("SELECT Field_Value FROM $ewd_feup_user_fields_table_name WHERE User_ID=%d AND Field_ID=%d", $Options['User_ID'], $Mailchimp_Sync_Field['Field_ID']));
        if ($Value != '') {$User_Data['merge_fields'][$Mailchimp_Sync_Field['Mailchimp_Field_ID']] = $Value;}
    }

    $memberId = md5(strtolower($Email));
    $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
    $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;
    
    $json = json_encode($User_Data);

    $response = wp_remote_post($url, array(
        'headers' => array(
            'Authorization' => 'Basic ' . base64_encode('user:' . $apiKey)
        ),
        'timeout' => 10,
        'method' => 'PUT',
        'sslverify' => false,
        'body' => $json
    ));                                                                                                               

    return $response['response']['code'];
}


function EWD_FEUP_Mailchimp_Sync_Current_Users() {
    global $wpdb;
    global $ewd_feup_user_table_name;

    $Users = $wpdb->get_results("SELECT User_ID FROM $ewd_feup_user_table_name");
    foreach ($Users as $User) {EWD_FEUP_Mailchimp_Subscriber_Sync(array('User_ID' => $User->User_ID));}
}
?>