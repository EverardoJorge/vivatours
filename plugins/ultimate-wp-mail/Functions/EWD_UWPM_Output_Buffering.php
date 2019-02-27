<?php
function EWD_UWPM_add_ob_start() {
    ob_start();
}
add_action('init', 'EWD_UWPM_add_ob_start', 1);

function EWD_UWPM_flush_ob_end() {
	if (ob_get_level() > 0){
		ob_end_flush();
	}
}
add_action('shutdown', 'EWD_UWPM_flush_ob_end');

function EWD_UWPM_clean_ob_end() {
	if (ob_get_level() > 0){
		ob_end_clean();
	}
}

?>