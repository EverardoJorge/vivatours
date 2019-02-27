<?php

function EWD_FEUP_Redirect_add_ob_start() {
    ob_start();
}
add_action('init', 'EWD_FEUP_Redirect_add_ob_start', 1);

function EWD_FEUP_Redirect_flush_ob_end() {
    ob_end_flush();
}
add_action('wp_footer', 'EWD_FEUP_Redirect_flush_ob_end');