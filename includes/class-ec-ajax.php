<?php 
defined( 'ABSPATH' ) || exit;

function ecomsurance_Insurify_wpse16119876_init_session() {
    if ( ! session_id() ) {
        session_start();
    }
}
// Start session on init hook.
add_action( 'init', 'ecomsurance_Insurify_wpse16119876_init_session' );
    
add_action( 'wp_ajax_toggle_btn_clicked_update_data', 'ecomsurance_Insurify_tbcud' );
add_action( 'wp_ajax_nopriv_toggle_btn_clicked_update_data', 'ecomsurance_Insurify_tbcud' );
function ecomsurance_Insurify_tbcud() { 
    // print_r($_SESSION);  
    $_SESSION["toggle_btn_status"] = sanitize_text_field( $_POST["checkbox"] ); 
    wp_die(); // all ajax handlers should die when finished
}
    
add_action( 'wp_ajax_saveemailtemplatedesign', 'ecomsurance_Insurify_saveemailtemplatedesign' );
add_action( 'wp_ajax_nopriv_saveemailtemplatedesign', 'ecomsurance_Insurify_saveemailtemplatedesign' );
function ecomsurance_Insurify_saveemailtemplatedesign() { 
    $optionname = sanitize_text_field( $_REQUEST["optionname"] );
    $insurify_email_template_design = get_option($optionname);  
    if(empty($insurify_email_template_design)){
        delete_option($optionname);
        add_option( $optionname, array_map( 'sanitize_text_field', stripslashes_deep($_REQUEST) ) ); 
    }else{
        update_option( $optionname,  array_map( 'sanitize_text_field', stripslashes_deep($_REQUEST) ) ); 
    }
    wp_die(); // all ajax handlers should die when finished
}
    
add_action( 'wp_ajax_updateClaimStatus', 'ecomsurance_Insurify_updateClaimStatus' );
add_action( 'wp_ajax_nopriv_updateClaimStatus', 'ecomsurance_Insurify_updateClaimStatus' );
function ecomsurance_Insurify_updateClaimStatus() {  
    global $current_user; 
    $id_token = get_user_meta( $current_user->id, 'auth_insurify_id_token', true); 
    $api_url = ECOMSURANCE_API_URL.'/api/updateclaimstatus';
    $data["domain"] = get_site_url(); 
    $data["email"] = $current_user->user_email; 
    $data["details"] = wp_kses_post($_REQUEST); 

    $apiResponse = wp_remote_post( $api_url, array(
        'body'    => $data,
        'headers' => array(
            'Authorization' => 'Bearer '.$id_token,
        ),
    ) );
    $apiBody = json_decode( wp_remote_retrieve_body( $apiResponse ) );  
    if($apiBody->message){ 

        $order = wc_get_order( $data["details"]["claimid"] );
            
        $shipping_address = $order->get_address('billing');  
        $customername = $shipping_address["first_name"].' '.$shipping_address["last_name"];

        foreach($apiBody->data as $insuranceorderdata){
            if($insuranceorderdata->claimproducts != null){
                foreach($insuranceorderdata->claimproducts as $claimproduct){ 
                    $order_items = $order->get_items();
                    foreach ($order_items as $item_id => $item ) {
                        $product = $item->get_product(); 
                        if($claimproduct->wc_order_product_id == $item->get_product_id()){
                            $claimedprodduct .= "<div class='px-6 py-4 mt-2 border-b-2'>
                            <span class='w-full inline-block'><b>Title:</b> ".esc_html($item->get_name())."</span> 
                            <span class='w-full inline-block'><b>Qty</b> ".esc_attr($item->get_quantity())."</span> 
                            <span class='w-full inline-block'><b>Price</b> ".get_woocommerce_currency_symbol( esc_html($currency_code) )." ".esc_attr($item->get_total())."</span> 
                            </div>";
                        }  
                    } 
                   
                }
            } 
        }
        $to = isset($shipping_address)?$shipping_address["email"]:"N/A";
        // $to = "elitegill@gmail.com";
        // Send mail to customer when status update start
        $headers = array('Content-Type: text/html; charset=UTF-8');  
        $content_type = function() { return 'text/html'; };
        add_filter( 'wp_mail_content_type', $content_type );

        if( sanitize_text_field($_REQUEST["updateStatusSelect"]) == "inprogress"){
            $getemailtemplatehtml = get_option("insurify_in_progress_email_for_customer");
            if(empty($getemailtemplatehtml)){ 
                require_once( ECOMSURANCE__PLUGIN_DIR . 'views/email-templates/inprogress-template.php' );
                $getemailtemplatehtml["html"] = $default;
            } 
            $templatehtml = stripslashes($getemailtemplatehtml["html"]);
            $templatehtml = str_replace( '{{customer.name}}', $customername, $templatehtml );  
            $templatehtml = str_replace( '{{products.claimed}}', $claimedprodduct, $templatehtml );  
        }elseif( sanitize_text_field($_REQUEST["updateStatusSelect"]) == "reorder"){
            $getemailtemplatehtml = get_option("insurify_reorder_email_for_customer");
            if(empty($getemailtemplatehtml)){
                require_once( ECOMSURANCE__PLUGIN_DIR . 'views/email-templates/reorder-template.php' );
                $getemailtemplatehtml["html"] = $default;
            } 
            $templatehtml = stripslashes($getemailtemplatehtml["html"]);
            $templatehtml = str_replace( '{{customer.name}}', $customername, $templatehtml );  
            $templatehtml = str_replace( '{{products.claimed}}', $claimedprodduct, $templatehtml );  
        }elseif( sanitize_text_field($_REQUEST["updateStatusSelect"]) == "refund"){
            $getemailtemplatehtml = get_option("insurify_refund_email_for_customer");
            if(empty($getemailtemplatehtml)){
                require_once( ECOMSURANCE__PLUGIN_DIR . 'views/email-templates/refund-template.php' );
                $getemailtemplatehtml["html"] = $default; 
            }  
            $templatehtml = stripslashes($getemailtemplatehtml["html"]);
            $templatehtml = str_replace( '{{customer.name}}', $customername, $templatehtml );  
            $templatehtml = str_replace( '{{products.claimed}}', $claimedprodduct, $templatehtml );  
        }elseif( sanitize_text_field($_REQUEST["updateStatusSelect"]) == "other"){
            $getemailtemplatehtml = get_option("insurify_other_email_for_admin");
            if(empty($getemailtemplatehtml)){ 
                require_once( ECOMSURANCE__PLUGIN_DIR . 'views/email-templates/other-template.php' );
                $getemailtemplatehtml["html"] = $default; 
            } 
            $templatehtml = stripslashes($getemailtemplatehtml["html"]);
            $templatehtml = str_replace( '{{customer.name}}', $customername, $templatehtml );  
            $templatehtml = str_replace( '{{products.claimed}}', $claimedprodduct, $templatehtml );  
        }else{
            $getemailtemplatehtml = get_option("insurify_claim_requested_email_for_customer");
            if(empty($getemailtemplatehtml)){
                require_once( ECOMSURANCE__PLUGIN_DIR . 'views/email-templates/filed-customer-template.php' );
                $getemailtemplatehtml["html"] = $default;  
            } 
            $templatehtml = stripslashes($getemailtemplatehtml["html"]);
            $templatehtml = str_replace( '{{customer.name}}', $customername, $templatehtml );  
            $templatehtml = str_replace( '{{site.url}}', get_site_url(), $templatehtml );  
            $templatehtml = str_replace( '{{products.claimed}}', $claimedprodduct, $templatehtml );  
        }  

        wp_mail( $to, "Your Claim Status has been updated.", $templatehtml, $headers );
        remove_filter( 'wp_mail_content_type', $content_type );
        // Send mail to customer when status update End

        update_post_meta( sanitize_text_field($_REQUEST["claimid"]), 'claim-status', sanitize_text_field($_REQUEST["updateStatusSelect"]) );
    }
    wp_die(); // all ajax handlers should die when finished
}
    
add_action( 'wp_ajax_getclaimstatus', 'ecomsurance_Insurify_getclaimstatus' );
add_action( 'wp_ajax_nopriv_getclaimstatus', 'ecomsurance_Insurify_getclaimstatus' );
function ecomsurance_Insurify_getclaimstatus() {  
    global $current_user; 
    $id_token = get_user_meta( $current_user->id, 'auth_insurify_id_token', true); 
    $api_url = ECOMSURANCE_API_URL.'/api/getclaimstatus';
    $data["domain"] = get_site_url();  
    $data["details"] = wp_kses_post($_REQUEST); 

    $apiResponse = wp_remote_post( $api_url, array(
        'body'    => $data,
        'headers' => array(
            'Authorization' => 'Bearer '.$id_token,
        ),
    ) );
    $apiBody = json_decode( wp_remote_retrieve_body( $apiResponse ) );  
    if($apiBody->message){ 
        if(!empty($apiBody->data)){
            // echo "<pre>";
            // print_r($apiBody->data);
            // echo "</pre>";
            $order = wc_get_order( $data["details"]["orderid"] );
            
	        $shipping_address = $order->get_address('billing'); 
            $emailaddress = isset($shipping_address)?$shipping_address["email"]:"N/A";

            echo "
                <div class='flex flex-row'>
                    <div class='w-full mr-2'>
                        <div class='px-6 py-4 border-2'>
                            <span class='w-full'>Customer Email</span>
                            <span class='w-full'>".esc_html($emailaddress)."</span>
                        </div>
                        <div class='px-6 py-4 border-2 mt-2'>
                            <span class='w-full inline-block'>Claim Filed</span>
                            <span class='w-full inline-block'>".date('d M, Y', strtotime(end($apiBody->data)->created_at))."</span>
                        </div>
                        <div class='px-6 py-4 border-2 mt-2'>
                            <span class='w-full'>Claim Status</span>"; 
                            foreach($apiBody->data as $insuranceorderdata){
                                echo "<div class='px-6 py-4 mt-2 border-b-2'>
                                <span class='w-full inline-block'>".esc_html($insuranceorderdata->status)."</span> 
                                <span class='w-full inline-block'>".date('d M, Y', strtotime($insuranceorderdata->created_at))."</span> 
                                </div>";
                            }
                        echo "</div> 
                    </div>
                    <div class='w-full'>
                        <div class='px-6 py-4 border-2'>
                            <span class='w-full'>Store Name</span>
                            <span class='w-full'>".esc_html($apiBody->data[0]->stores_domain)."</span>
                        </div>
                        <div class='px-6 py-4 border-2'>
                            <span class='w-full inline-block'>Store Order ID</span>
                            <span class='w-full inline-block'><a class='text-blue-600/100' href=".admin_url( 'post.php?post=' . esc_attr($data["details"]["orderid"]) ) . '&action=edit'.">#".esc_attr($data["details"]["orderid"])."</a></span>
                        </div>
                        <div class='px-6 py-4 border-2'>
                            <span class='w-full inline-block'>Claim ID</span>
                            <span class='w-full inline-block'>".esc_attr($apiBody->data[0]->id)."</span>
                        </div>
                        <div class='px-6 py-4 border-2'>
                            <span class='w-full inline-block'>Claim Type</span>
                            <span class='w-full inline-block'>".esc_html(end($apiBody->data)->type)."</span>
                        </div>
                        <div class='px-6 py-4 border-2'>
                            <span class='w-full inline-block'>Claim Value</span>
                            <span class='w-full inline-block'>Not Provided</span>
                        </div>
                    </div>
                </div>
                <div class='w-full block border-2'>";
                    foreach($apiBody->data as $insuranceorderdata){
                        if($insuranceorderdata->claimproducts != null){
                            foreach($insuranceorderdata->claimproducts as $claimproduct){ 
                                $order_items = $order->get_items();
                                foreach ($order_items as $item_id => $item ) {
                                    $product = $item->get_product(); 
                                    if($claimproduct->wc_order_product_id == $item->get_product_id()){
                                        echo "<div class='px-6 py-4 mt-2 border-b-2'>
                                        <span class='w-full inline-block'><b>Title:</b> ".esc_html($item->get_name())."</span> 
                                        <span class='w-full inline-block'><b>Qty</b> ".esc_attr($item->get_quantity())."</span> 
                                        <span class='w-full inline-block'><b>Price</b> ".get_woocommerce_currency_symbol( esc_html($currency_code) )." ".esc_attr($item->get_total())."</span> 
                                        </div>";
                                    }  
                                } 
                               
                            }
                        } 
                    }
                echo "</div>
            "; 
        }
    }
    wp_die(); // all ajax handlers should die when finished
}


?>