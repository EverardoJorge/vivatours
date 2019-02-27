<?php
function EWD_FEUP_Remove() {
	global $EWD_FEUP_Full_Version;

	if (get_bloginfo('wpurl') == "kinfluencemedia.xyz") {$EWD_FEUP_Full_Version = "No"; update_option("EWD_FEUP_Full_Version", "No");}
}

?>