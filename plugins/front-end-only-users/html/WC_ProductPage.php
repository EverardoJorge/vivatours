<?php
global $feup_show_woocommerce_message;

$feup_Label_Restrict_Access_Message =  get_option("EWD_FEUP_Label_Restrict_Access_Message");
if ($feup_Label_Restrict_Access_Message == "") {$feup_Label_Restrict_Access_Message =  __('Please log in to access this content.', 'front-end-only-users');}

if ($feup_show_woocommerce_message == "Yes") {echo $feup_Label_Restrict_Access_Message;}

?>