<?php
/**
 * @package Insurify
 */
/*
Plugin Name: Insurify - Order & Shipping Protection
Plugin URI: https://wordpress.org/plugins/insurify
Description: Ecomsurance (Order & Shipping Protection) allows merchants & store owners like yourself to offer protection plans on the products or services they sell. You can start covering stolen or broken items that have been insured by the consumer.
Version: 1.0
Author: Webinopoly
Author URI: https://webinopoly.com/
License: GPLv2 or later
Text Domain: EcomSurance
*/
defined( 'ABSPATH' ) || exit;

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}



define( 'ECOMSURANCE_VERSION', '1.0' );
define( 'ECOMSURANCE__MINIMUM_WP_VERSION', '5.0' );
define( 'ECOMSURANCE__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'ECOMSURANCE_DELETE_LIMIT', 100000 );
define( 'ECOMSURANCE_API_URL', "https://phplaravel-606044-2253128.cloudwaysapps.com" );
 
$apiResponse = wp_remote_post( ECOMSURANCE_API_URL.'/api/getkey', array(
	'body'    => "" 
) );
$apiBody = json_decode( wp_remote_retrieve_body( $apiResponse ) );  

define( 'ECOMSURANCE_STRIPE_KEY', $apiBody->stripe_pk_key );
register_activation_hook( __FILE__, array( 'EcomSurance', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'EcomSurance', 'plugin_deactivation' ) );

require_once( ECOMSURANCE__PLUGIN_DIR . 'class.ecomsurance.php' );
require_once( ECOMSURANCE__PLUGIN_DIR . 'class.ecomsurance-stripe.php' );
// require_once( ECOMSURANCE__PLUGIN_DIR . 'class.ecomsurance-rest-api.php' );

add_action( 'init', array( 'EcomSurance', 'init' ) );
add_action( 'init', array( 'EcomSurance_STRIPE', 'init' ) );
// add_action( 'rest_api_init', array( 'EcomSurance_REST_API', 'init' ) );
 
function ecomsurance_Insurify_load_plugin_css($hook) {  
	if (strpos($hook, 'ecomsurance') !== false) {
		wp_enqueue_style( 'tailwind-css', plugin_dir_url( __FILE__ ) . 'css/tailwind.min.css' ); 	 
		wp_enqueue_style( 'font-css', 'https://fonts.googleapis.com/css2?family=Titillium+Web:wght@300;400;600&amp;display=swap' );
		wp_enqueue_style( 'dataTables-css', plugin_dir_url( __FILE__ ) . 'css/jquery.dataTables.css' ); 	
		wp_enqueue_script( 'dataTables-js', plugin_dir_url( __FILE__ ) . 'js/jquery.dataTables.js' );	
		wp_enqueue_script( 'stripe-js', 'https://js.stripe.com/v3/' );
		wp_enqueue_script( 'unlayer-js', '//editor.unlayer.com/embed.js' );
	}

    wp_enqueue_style( 'style1', plugin_dir_url( __FILE__ ) . 'css/style.css' ); 
    wp_enqueue_script( 'custom-jquery', plugin_dir_url( __FILE__ ) . 'js/custom.js', array(), '1.0.0', true ); 
	wp_enqueue_script( 'chartjs', plugin_dir_url( __FILE__ ) . 'js/Chart.js' );
	wp_enqueue_script( 'axios', plugin_dir_url( __FILE__ ) . 'js/axios.min.js' );
	wp_enqueue_script( 'vuejs', plugin_dir_url( __FILE__ ) . 'js/vue@2.6.14' ); 
	
	wp_localize_script( 'custom-jquery', 'admin_ecomsurance_ajax_js', array(
		'ajaxurl' => admin_url('admin-ajax.php'),
		'baseurl' => get_site_url(),
		'nonce' => wp_create_nonce('_wpnonce')
	));
}
add_action( 'admin_enqueue_scripts', 'ecomsurance_Insurify_load_plugin_css' );
 
function ecomsurance_Insurify_frontend_scripts() {
    global $wp_styles;
	
	global $post;
	if( is_page( wc_get_page_id( 'cart' ) ) || is_page( wc_get_page_id( 'checkout' ) ) ){ 
		wp_enqueue_style( 'Titillium-font-css', 'https://fonts.googleapis.com/css2?family=Titillium+Web:wght@300;400;600&amp;display=swap' );
	}
	 
	if( $post->post_name == 'order-claim' ){  
		
		foreach( $wp_styles->queue as $style ) :
			wp_dequeue_style($style); 
		endforeach;
		wp_enqueue_style( 'tailwind-css', plugin_dir_url( __FILE__ ) . 'css/tailwind.min.css' ); 
		wp_enqueue_style( 'Lato-font-css', 'https://fonts.googleapis.com/css?family=Lato:400,300,700&display=swap' );	 

	}
    wp_enqueue_style( 'ecomsurance-css', plugin_dir_url( __FILE__ ) . 'css/ecomsurance.css' );
    wp_enqueue_script( 'ecomsurance-js', plugin_dir_url( __FILE__ ) . '/js/ecomsurance.js', array(), '1.0.0', true );
	wp_localize_script( 'ecomsurance-js', 'ecomsurance_ajax_js', array(
		'ajaxurl' => admin_url('admin-ajax.php'),
		'baseurl' => get_site_url(),
		'nonce' => wp_create_nonce('_wpnonce')
	));
}
add_action( 'wp_enqueue_scripts', 'ecomsurance_Insurify_frontend_scripts' );

require_once( ECOMSURANCE__PLUGIN_DIR . 'includes/class-ec-ajax.php' );

if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
	require_once( ECOMSURANCE__PLUGIN_DIR . 'includes/class.ecomsurance-admin.php' );
	require_once( ECOMSURANCE__PLUGIN_DIR . 'includes/ec-admin-ajax.php' );
	add_action( 'init', array( 'EcomSurance_Admin', 'init' ) );
}else{ 
	// Test to see if WooCommerce is active (including network activated).
	$plugin_path = trailingslashit( WP_PLUGIN_DIR ) . 'woocommerce/woocommerce.php';

	if ( in_array( $plugin_path, wp_get_active_and_valid_plugins()  )
	) { 
		require_once( ECOMSURANCE__PLUGIN_DIR . 'includes/class.ecomsurance-frontend.php' );
		add_action( 'init', array( 'EcomSurance_Frontend', 'init' ) );
	}
	
} 

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	// require_once( ECOMSURANCE__PLUGIN_DIR . 'class.ecomsurance-cli.php' );
} 