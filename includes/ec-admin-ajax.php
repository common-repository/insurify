<?php 
defined( 'ABSPATH' ) || exit;

function ecomsurance_Insurify_ecinit_session() {
    if ( ! session_id() ) {
        session_start();
    }
}
// Start session on init hook.
add_action( 'init', 'ecomsurance_Insurify_ecinit_session' ); 
    
add_action( 'wp_ajax_removeimg_popup', 'ecomsurance_Insurify_popup' );
add_action( 'wp_ajax_nopriv_removeimg_popup', 'ecomsurance_Insurify_popup' );
function ecomsurance_Insurify_popup() { 
    delete_option(sanitize_text_field($_REQUEST["name"]));   
    wp_die(); // all ajax handlers should die when finished
}


?>