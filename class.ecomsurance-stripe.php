<?php 

defined( 'ABSPATH' ) || exit;

class EcomSurance_STRIPE { 

    private static $initiated = false;

    public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}

    public static function init_hooks() {
		// The standalone stats page was removed in 3.0 for an all-in-one config and stats page.
		// Redirect any links that might have been bookmarked or in browser history.
		if ( isset( $_GET['page'] ) && 'ecomsurance-stats-display' == $_GET['page'] ) {
			wp_safe_redirect( esc_url_raw( self::get_page_url( 'stats' ) ), 301 );
			die;
		}

		self::$initiated = true;

		add_action( 'admin_init', array( 'EcomSurance_Admin', 'admin_init' ) ); 
    }

    public static function admin_init() {
		if ( get_option( 'Activated_EcomSurance' ) ) {
			delete_option( 'Activated_EcomSurance' );
			if ( ! headers_sent() ) {
				wp_redirect( add_query_arg( array( 'page' => 'ecomsurance'  ),  admin_url( 'admin.php' ) ) );
			}
		} 
	}

	

    public static function HasPaymentMethod() {
		global $current_user, $woocommerce; 
		$id_token = get_user_meta( get_current_user_id(), 'auth_insurify_id_token', true); 
		$api_url = ECOMSURANCE_API_URL.'/api/haspaymentmethod';
		$data["domain"] = get_site_url(); 
		$data["email"] = $current_user->user_email; 

		$apiResponse = wp_remote_post( $api_url, array(
			'body'    => $data,
			'headers' => array(
				'Authorization' => 'Bearer '.$id_token,
			),
		) );
		$apiBody = json_decode( wp_remote_retrieve_body( $apiResponse ) );  
		 
		if(isset($apiBody->message) && !empty($apiBody->message)){ 
			return true; 
		}else{ 
			return false; 
		}
	}

	
    
}
