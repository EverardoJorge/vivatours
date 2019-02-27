<?php
function uwpm_register_custom_element( $element_type, $params = array() ) {
    global $UWPM_Custom_Element_Types;
    global $ewd_uwpm_message;
 
    if ( ! is_array( $UWPM_Custom_Element_Types ) ) {
        $UWPM_Custom_Element_Types = array();
    }
 
    // Sanitize post type name
    $element_type = sanitize_key( $element_type );
 
    if ( empty( $element_type ) || strlen( $element_type ) > 40 ) {
        $ewd_uwpm_message = array('Message_Type' => 'Error', 'Message' => __('Custom element name must be between 1 and 20 characters', 'ultimate-wp-mail'));
    }
 
    $Custom_Element_Object = new UWPM_Element( $element_type, $params );
 
    $UWPM_Custom_Element_Types[ $element_type ] = $Custom_Element_Object;
 
    //do_action( 'uwpm_custom_element_registered', $element_type, $Custom_Element_Object );
 
    return $Custom_Element_Object;
}

function EWD_UWPM_Get_Custom_Elements() {
    global $UWPM_Custom_Element_Types;

    $Return_Elements = array();

    foreach ($UWPM_Custom_Element_Types as $UWPM_Custom_Element_Type) {
        $Element = array('slug' => $UWPM_Custom_Element_Type->slug, 'name' => $UWPM_Custom_Element_Type->label, 'section' => $UWPM_Custom_Element_Type->section, 'attributes' => $UWPM_Custom_Element_Type->attributes);
        $Return_Elements[] = $Element;
    }

    return $Return_Elements;
}
?>