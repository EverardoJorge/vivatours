<?php
function uwpm_register_custom_element_section( $section_type, $params = array() ) {
    global $UWPM_Custom_Element_Section_Types;
    global $ewd_uwpm_message;
 
    if ( ! is_array( $UWPM_Custom_Element_Section_Types ) ) {
        $UWPM_Custom_Element_Section_Types = array();
    }
 
    // Sanitize post type name
    $section_type = sanitize_key( $section_type );
 
    if ( empty( $section_type ) || strlen( $section_type ) > 40 ) {
        $ewd_uwpm_message = array('Message_Type' => 'Error', 'Message' => __('Section name must be between 1 and 30 characters', 'ultimate-wp-mail'));
    }
 
    $Custom_Element_Section_Object = new UWPM_Element_Section( $section_type, $params );
 
    $UWPM_Custom_Element_Section_Types[ $section_type ] = $Custom_Element_Section_Object;
 
    //do_action( 'uwpm_custom_element_section_registered', $section_type, $Custom_Element_Section_Object );
 
    return $Custom_Element_Object;
}

function EWD_UWPM_Get_Custom_Element_Sections() {
    global $UWPM_Custom_Element_Section_Types;

    $Return_Sections = array();

    foreach ($UWPM_Custom_Element_Section_Types as $UWPM_Custom_Element_Section) {
        $Section = array('slug' => $UWPM_Custom_Element_Section->slug, 'name' => $UWPM_Custom_Element_Section->label);
        $Return_Sections[] = $Section;
    }

    return $Return_Sections;
}
?>