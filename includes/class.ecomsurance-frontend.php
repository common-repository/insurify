<?php
defined( 'ABSPATH' ) || exit;

class EcomSurance_Frontend {

    private static $initiated = false;

    public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}

    public static function init_hooks() {
		// The standalone stats page was removed in 3.0 for an all-in-one config and stats page.
		// Redirect any links that might have been bookmarked or in browser history.
		if ( isset( $_GET['page'] ) && 'ecomsurance-stats-display' == sanitize_text_field($_GET['page']) ) {
			wp_safe_redirect( esc_url_raw( self::get_page_url( 'stats' ) ), 301 );
			die;
		}

		self::$initiated = true;   
		$response= new EcomSurance_STRIPE();
		$response_update = $response->HasPaymentMethod();
		if($response == false){
			return false;
		}
		$protection_toggle_cartorcheckout = get_option("insurify_protection_toggle_cartorcheckout");

		if($protection_toggle_cartorcheckout == 0){
			add_action( 'woocommerce_before_cart_contents', array( "EcomSurance_Frontend", 'action_woocommerce_after_add_to_cart_form'), 10, 0 ); 
		}else{
			add_action( 'woocommerce_checkout_before_terms_and_conditions', array( "EcomSurance_Frontend", 'action_woocommerce_after_add_to_cart_form'), 10, 0 ); 
		}
		 
		add_action('woocommerce_thankyou', array( "EcomSurance_Frontend", 'add_data_into_stripe_for_pay'), 10, 1);

		add_filter("page_template", array( "EcomSurance_Frontend", "our_own_custom_page_template"));
 
    } 

	public static function our_own_custom_page_template()
	{
		global $post;

		if($post->post_name == "order-claim"){  
			include(plugin_dir_path( __DIR__  )."views/frontend/ecomsurance-frontpage-template.php");
			die();
		}
	}
	
	// define the woocommerce_after_add_to_cart_form callback 
	public static function action_woocommerce_after_add_to_cart_form() { 
		// make action magic happen here... 
		$dir = plugin_dir_path( __DIR__  );  
		include($dir."views/frontend/cart.php");
	} 
    
	public static function add_data_into_stripe_for_pay( $order_id ) {
		if ( ! $order_id )
			return;
	
		// Allow code execution only once 
		if( ! get_post_meta( $order_id, '_thankyou_action_done', true ) ) {

			$data["protection"] = false;
			$order = wc_get_order( $order_id );
			// Flag the action as done (to avoid repetitions on reload for example)
			$order->update_meta_data( '_thankyou_action_done', true );
			$order->save();

			// Get an instance of the WC_Order object
			$data["order"] = $order->get_data();
			$data["order"]["get_date_created"] = $order->get_date_created();
			$data["order"]["get_date_modified"] = $order->get_date_modified();
			$data["order"]["get_date_paid"] = $order->get_date_paid();
			$data["order"]["get_date_completed"] = $order->get_date_completed();
			$data["order"]["shipping"] = $order->get_address("shipping");
			$data["order"]["billing"] = $order->get_address("billing");

			// Iterating through each order item
			$i = 0;
			foreach ($order->get_items() as $item_id => $item ) { 
					 
				$data["getorderitems"][$i]["itemdata"] = $item->get_data(); 
				$product_id = $item['product_id'];
				$terms = get_the_terms( $product_id, 'product_cat' );
				foreach ( $terms as $term ) {
					// Categories by slug
					if($term->slug == "protection"){ 
						$data["getorderitems"][$i]["slug"] = $term->slug;
						$data["protection"] = true;
					}
				}
				$i++;
			}
			
			$data["domain"] = get_site_url(); 
			$api_url = ECOMSURANCE_API_URL.'/api/createorder';
			$apiResponse = wp_remote_post( $api_url, array(
				'body'    => $data
			) );
			$apiBody = json_decode( wp_remote_retrieve_body( $apiResponse ) );	
			// echo "<pre>"; print_r($data["getorderitems"]); echo "</pre>";		
		}
	}
}
