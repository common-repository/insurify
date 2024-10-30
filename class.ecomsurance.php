<?php 

defined( 'ABSPATH' ) || exit;

class EcomSurance { 

	private static $initiated = false;

	public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}

	/**
	 * Initializes WordPress hooks
	 */
	private static function init_hooks() {
		self::$initiated = true;			
	}

	/**
	 * Attached to activate_{ plugin_basename( __FILES__ ) } by register_activation_hook()
	 * @static
	 */
	public static function plugin_activation() {
		if ( version_compare( $GLOBALS['wp_version'], ECOMSURANCE__MINIMUM_WP_VERSION, '<' ) ) {
			load_plugin_textdomain( 'ecomsurance' );
			
			$message = sprintf(esc_html__( 'EcomSurance %s requires WordPress %s or higher.' , 'ecomsurance'), ECOMSURANCE_VERSION, ECOMSURANCE__MINIMUM_WP_VERSION ).' '.sprintf(__('Please <a href="%1$s">upgrade WordPress</a> to a current version, or <a href="%2$s">downgrade to version 2.4 of the EcomSurance plugin</a>.', 'ecomsurance'), 'https://codex.wordpress.org/Upgrading_WordPress', 'https://wordpress.org/extend/plugins/ecomsurance/download/');

			self::bail_on_activation( $message );
		} elseif ( ! empty( $_SERVER['SCRIPT_NAME'] ) && false !== strpos( $_SERVER['SCRIPT_NAME'], '/wp-admin/plugins.php' ) ) {
			if ( class_exists( 'WooCommerce' ) ) {				
				global $current_user, $woocommerce;
				$data["domain"] = get_site_url();
				$data["name"] = $current_user->user_nicename;
				$data["email"] = $current_user->user_email;
				$data["password"] = $current_user->user_email;
				$data["timezone"] = wp_timezone_string();
				$data["currency"] = get_woocommerce_currency_symbol();
				$data["decimal_separator"] = wc_get_price_decimal_separator();
				  
				$apiResponse = wp_remote_post( ECOMSURANCE_API_URL.'/api/register', array('body' => $data) );
				$apiBody     = json_decode( wp_remote_retrieve_body( $apiResponse ) );  
				// echo "<pre>";print_r($apiBody);echo "</pre>";die();
				if(!empty($apiBody)){
					// Will return false if the previous value is the same as $new_value.
					$updated = update_user_meta( $current_user->id, 'auth_insurify_id_token', $apiBody->access_token );
					
					// So check and make sure the stored value matches $new_value.
					if ( $apiBody->access_token != get_user_meta( $current_user->id,  'auth_insurify_id_token', true ) ) {
						wp_die( __( 'An error occurred', 'something went wrong' ) );
					} 
				}else{
					wp_die( __( 'An error occurred', 'something went wrong' ) );
				} 
				add_option( 'Activated_EcomSurance', true );
			} else {
				$message = sprintf(esc_html__( 'EcomSurance %s requires %s plugin active.' , 'ecomsurance'), ECOMSURANCE_VERSION, "Woocoomerce" );

				self::bail_on_activation( $message );
			}
			
		}
	}

	/**
	 * Removes all connection options
	 * @static
	 */
	public static function plugin_deactivation( ) {
		global $current_user; 

		$id_token = get_user_meta( get_current_user_id(), 'auth_insurify_id_token', true); 
		$api_url = ECOMSURANCE_API_URL.'/api/deactivatestore';
		$data["domain"] = get_site_url();  

		$apiResponse = wp_remote_post( $api_url, array(
			'body'    => $data,
			'headers' => array(
				'Authorization' => 'Bearer '.$id_token,
			),
		) );
		$apiBody = json_decode( wp_remote_retrieve_body( $apiResponse ) );  
		 
		if(!empty($apiBody->message)){
			
			delete_option("insurify_app_status");
			delete_option("insurify_protection_toggle_default");
			delete_option("insurify_protection_toggle_cartorcheckout");
			delete_option("insurify_toggle_type");
			delete_option("insurify_protection_title");
			delete_option("insurify_protection_subtitle");
			delete_option("insurify_protection_description");
			delete_option("insurify_toggle_inline_css");
			delete_option("insurify_storelogo");
			delete_option("insurify_coverimage");

			return true;
		}else{ 
			$message = esc_html__( 'Plugin not deactivated due to some reason.');

			self::bail_on_activation( $message );
		} 
	}

	public static function view( $name, array $args = array() ) {
		$args = apply_filters( 'ecomsurance_view_arguments', $args, $name );
		
		foreach ( $args AS $key => $val ) {
			$$key = $val;
		}
		
		load_plugin_textdomain( 'ecomsurance' );

		$file = ECOMSURANCE__PLUGIN_DIR . 'views/'. $name . '.php';

		include( $file );
	}

	private static function bail_on_activation( $message, $deactivate = true ) {
	?>
		<!doctype html>
		<html>
			<head>
			<meta charset="<?php bloginfo( 'charset' ); ?>" />
			<style>
			* {
				text-align: center;
				margin: 0;
				padding: 0;
				font-family: "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif;
			}
			p {
				margin-top: 1em;
				font-size: 18px;
			}
			</style>
			</head>
			<body>
			<p><?php echo esc_html( $message ); ?></p>
			</body>
		</html>
		<?php
		if ( $deactivate ) {
			$plugins = get_option( 'active_plugins' );
			$akismet = plugin_basename( ECOMSURANCE__PLUGIN_DIR . 'akismet.php' );
			$update  = false;
			foreach ( $plugins as $i => $plugin ) {
				if ( $plugin === $akismet ) {
					$plugins[$i] = false;
					$update = true;
				}
			}

			if ( $update ) {
				update_option( 'active_plugins', array_filter( $plugins ) );
			}
		}
		exit;
	}
}
