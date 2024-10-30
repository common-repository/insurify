<?php 
wp_head();

// This file is used without head and footer. That's why we are calling css and font from external link directly. 
defined( 'ABSPATH' ) || exit;
$wordpress_upload_dir = wp_upload_dir();
    
    if(isset($_POST["orderId"]) && !empty( sanitize_text_field($_POST["orderId"]) )){
        
        $order = wc_get_order( sanitize_text_field($_REQUEST["orderId"]) ); 
        if(!empty($order)){  
            
        	$order_items = $order->get_items();
        	// Iterating through each "line" items in the order      
        	foreach ($order->get_items() as $item_id => $item ) {
        		$product = $item->get_product();
        		$ec_insurance_plan = get_post_meta($product->get_id(), "ec_insurance_plan", true);

        		// Get a list of categories and extract their names
        		$product_categories = get_the_terms( $product->get_id(), 'product_cat' );
        		if ( ! empty( $product_categories ) && ! is_wp_error( $product_categories ) ) {
        			$categories = wp_list_pluck( $product_categories, 'name' );
        		}

        		if($ec_insurance_plan == "protection" && in_array("protection", $categories)){
        			$protection_order["order"] = $order;
        			$protection_order["protection_product"] = $product;
        		}
        	}  

            $order_data = $protection_order["order"]->get_data();	
            $shipping_address = $protection_order["order"]->get_address('billing'); 
            $order_items = $protection_order["order"]->get_items();
            // Iterating through each "line" items in the order      
            $currency_code = $protection_order["order"]->get_currency(); 
            $claimstatus = get_post_meta( sanitize_text_field($_REQUEST["orderId"]) , "claim-status", true);
        }else{
            $_POST["step"] = 1;
            $error = "Sorry, We couldn’t find this order.";
        } 
        
        if(!isset($claimstatus) || empty($claimstatus)){
            $claimstatus = "Unclaimed";
        }
    }
?>  
<style> 
  body{
      font-family: 'Lato', sans-serif;
  }
  .footer_div {
    border-top: 1px solid #e0e0e0;
    position: sticky;
    bottom: 0;
    top: 100%;
    position: -webkit-sticky;
}
.footer_div h6 { 
    padding: 20px 0;
    text-align: center;
    color: #363636;
    font-family: 'Lato', sans-serif;
    font-weight: 300;
    margin: 0;
}
</style>
<div class="min-h-screen bg-white alignfull">  
	<div class="claim-wizard-container">
        <div class="w-full">
            <div class="flex my-8 mx-12">
                <div class="flex w-1/3">
                    <p class="w-full m-auto text-left text-gray-500">
                        <?php 
                            if(isset($_POST['step'])){
                                $step = sanitize_text_field($_POST["step"]);
                            }else{
                                $step = 1;
                            }
                        ?>
                        Step <?php echo esc_attr($step); ?> of 5
                    </p>
                </div>
                <div class="flex w-1/3">
                    <h1 class="w-full text-6xl text-center font-bold">EcomSurance</h1>
                </div>
            </div> 
                <div class="relative">
                    <div class="overflow-hidden h-1 text-xs flex rounded bg-gray-200">
                        <div 
                        class="w-<?php echo esc_attr($step); ?>/5
                            rounded-full
                            shadow-none
                            flex flex-col
                            text-center
                            whitespace-nowrap
                            text-white
                            justify-center
                            bg-gray-900
                        "
                        ></div>
                    </div> 
            </div>
        </div>

        <?php if(!isset($_POST["step"]) || sanitize_text_field($_POST["step"]) == 1){ ?>
            <form action="#" method="POST" id="claimform" class="pt-10">
                <input type="hidden" name="step" value="2"> 
                <div class="w-full step-1">
                    <div class="w-1/3 mx-auto px-2">
                        <h3 class="text-5xl mb-4 font-light">Find Order</h3>
                        <p class="text-base text-gray-400 mb-10">We’re here to help quickly resolve your issue. Please help us
                            find your order.</p>
                        
                        <?php if(isset($error)){ ?>
                        <div id="error-div" style="margin-top: 1.5rem!important;"><span class="error"><?php echo esc_html($error); ?></span></div>
                        <?php } ?>
                        <div class="w-full mb-2">
                            <input type="email" class="form-control border-2 w-full py-4 px-6 text-gray-400 border-gray-300 email"
                                placeholder="Email Address" name="email" required>

                        </div>
                        <div class="w-full mb-2">
                            <input type="number" class="form-control border-2 w-full py-4 px-6 text-gray-400 border-gray-300 orderId"
                                placeholder="Order Number" name="orderId" required>

                        </div>
                    </div>
                    <div class="my-6 w-full">

                        <div class="w-1/3 mx-auto flex">

                            <div class="flex ml-auto">
                                <div class="flex ml-auto mr-4">
                                    <a href="<?php echo esc_url(get_site_url()); ?>" class="my-auto mr-2 cursor-pointer">Back to store</a>
                                    <a href="<?php echo esc_url(get_site_url()); ?>" class="bg-gray-300 p-2 rounded-full w-12 h-12">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            <div class="flex mr-auto">
                                <div class="flex">
                                    <button class="bg-black text-white p-2 rounded-full w-12 h-12 step1complete">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                        </svg>
                                    </button> 
                                    <span class="my-auto ml-2 step1complete">File a Claim</span>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </form>
        <?php } ?>

        <?php if(isset($_POST["step"]) && sanitize_text_field($_POST["step"]) == 2){  
            ?>
            <form action="#" method="POST" id="claimform" class="pt-10">
                <input type="hidden" name="step" value="3">
                <input type="hidden" name="email" value="<?php echo esc_html($_POST["email"]); ?>">
                <input type="hidden" name="orderId" value="<?php echo esc_attr($_POST["orderId"]); ?>">
                <div class="w-full step-2">
                    <?php 
                    if(empty($order)){
                        echo esc_html('<div class="text-5xl mb-4 font-light">
                            Order Not Found
                        </div>');
                    }else{ ?>
                    <div class="w-1/3 mx-auto px-2">
                        <h3 class="text-5xl mb-4 font-light"><?php echo esc_html('Order Found'); ?></h3>
                        <div class="flex">
                            <div class="w-1/2 text-lg">
                                <div class="text-gray-500"> 
                                    <p class="pb-2">Order date: <?php echo esc_attr($order_data['date_created']->date('Y-m-d H:i:s')); ?></p>
                                    <p class="pb-2">Price: <?php echo wp_kses_post($protection_order["order"]->get_formatted_order_total()); ?> </p> 
                                    <p class="pb-2">Order Number: #<?php echo esc_attr($protection_order["order"]->get_order_number()); ?></p>
                                    <p class="pb-2">Claim staus: <span class="text-capitalize"><?php echo esc_attr($claimstatus); ?></span>
                                    </p>
                                </div>
                                <div class="text-gray-500 mt-12">
                                    <p class="text-semibold text-black"><?php echo esc_html('Shipping Address:'); ?></p> 
                                    <div class="py-2">
                                        <p class="pb-2"><?php echo esc_attr( isset($shipping_address)?$shipping_address["address_1"]:"N/A"); ?></p>
                                        <p class="pb-2"><?php echo esc_attr(isset($shipping_address)?$shipping_address["address_2"]:"N/A"); ?></p>
                                        <p class="pb-2">
                                            <?php echo esc_attr(isset($shipping_address)?$shipping_address["city"]:"N/A"); ?>, <?php echo esc_attr(isset($shipping_address)?$shipping_address["state"]:"N/A"); ?>,
                                            <?php echo esc_attr(isset($shipping_address)?$shipping_address["postcode"]:"N/A"); ?><br>
                                            <?php echo esc_attr(isset($shipping_address)?$shipping_address["country"]:"N/A"); ?>
                                        </p>
                                    </div> 
                                </div>
                            </div>
                            <div class="w-1/2">
                                <img class="ml-auto border-none" src="<?php echo esc_url( plugins_url( 'images/search_img.png', dirname(__DIR__)  ) ); ?>" height="227" width="227"/>
                            </div>
                        </div>

                        <div class="w-full my-6 border-t-2  border-gray-300">
                            <?php  foreach ($order_items as $item_id => $item ) {
                                    $product = $item->get_product();  

                                    $protectionexist = get_post_meta($product->get_id(), "ec_insurance_plan", true);
                                    if(empty($protectionexist)){
                                    ?> 
                                    <div class="flex flex-row py-5 border-b-2 py-4 border-gray-300">
                                        <div class="w-full">
                                            <h3>
                                            <?php echo esc_attr($item->get_name()); ?>
                                            </h3>
                                            <p class="text-lg">
                                                <?php echo get_woocommerce_currency_symbol( $currency_code ); ?> 
                                                <?php echo esc_attr($item->get_total()); ?> 
                                            </p>
                                        </div>
                                        <div class="w-1/5">
                                            <?php  
                                            $image = wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), 'single-post-thumbnail' );?>
    
                                            <img class="w-2/3 ml-auto" src="<?php  echo esc_attr($image[0]); ?>" alt="">
                                        </div>  
                                    </div>  
                            <?php } }?>   
                        </div>
                    </div>
                    <div class="my-6 w-full"> 
                        <div class="w-1/3 mx-auto flex"> 
                            <div class="flex ml-auto">
                                <div class="flex ml-auto mr-4">
                                    <span class="my-auto mr-2 cursor-pointer gotoback">Back</span>
                                    <button class="bg-gray-300 p-2 rounded-full w-12 h-12 gotoback">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="flex mr-auto">
                                <?php if($claimstatus == "Unclaimed") { ?>
                                <div class="flex">
                                    <button class="bg-black text-white p-2 rounded-full w-12 h-12">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                        </svg>
                                    </button> 
                                    <span class="my-auto ml-2">Next</span>
                                </div>
                                <?php } ?>
                            </div> 
                        </div> 
                    </div>
                    <?php } ?>
                </div>
            </form>
        <?php } ?>

        <?php if(isset($_POST["step"]) && $_POST["step"] == 3){ ?>
            <form action="#" method="POST" id="claimform" class="pt-10 issue-section">
                <input type="hidden" name="step" value="4">
                <input type="hidden" name="selected-issue-option" value="">
                <input type="hidden" name="email" value="<?php echo esc_html($_POST["email"]); ?>">
                <input type="hidden" name="orderId" value="<?php echo esc_attr($_POST["orderId"]); ?>">

                <div class="w-full step-3" >
                    <?php 
                    if(empty($order)){
                        echo '<div>
                            Order Not Found
                        </div>';
                    }else{ ?>
                    <div class="w-1/3 mx-auto px-2">
                        <h3 class="text-5xl mb-4 font-light">Issue</h3>
                        <p class="text-lg text-gray-500">We’re here to help quickly resolve your issue. What was the issue with
                            your items?</p>
                        <div class="my-4">
                            <select class="w-full border-2 h-12 select-issue-option" name="type" required>
                                <option value="" selected="true" disabled="disabled">Select your option</option>
                                <option value="lost_stolen">Lost or Stolen in transit</option>
                                <option value="broken">Broken in transit</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <p class="text-lg text-gray-500 other hidden issue-options">
                            Insurify covers your item from loss, damage, and theft during shipping.
                            If your are unsatisfied with your order or would like to exchange items please contact the merchant
                            directly.
                        </p>

                        <p class="text-lg text-gray-500 lost_stolen hidden issue-options">
                            Tracking indicates that no packages have been delivered yet. You can file as lost in transit.
                        </p>

                        <div class="broken hidden issue-options">
                            <p class="text-lg text-gray-500">
                                An item is considered broken if it is unusable, clearly fractured, shattered, bent (if not
                                bendable), crushed, etc.
                                If an item is broken in transit you can file a claim and a new one will be sent to you upon
                                approval.
                            </p>
                            <div class="flex my-6">
                                <div class="w-1/2 mx-12 my-4">
                                    <img class="w-full" src="<?php echo esc_url( plugins_url( 'images/broken-product.jpg', dirname(__DIR__)  ) ); ?>">
                                    <p class="">This is broken.</p>
                                </div>
                                <div class="w-1/2 mx-12 my-4">
                                    <img class="w-full" src="<?php echo esc_url( plugins_url( 'images/not-broken-product.jpg', dirname(__DIR__)  ) ); ?>">
                                    <p class="">This is not.</p>
                                </div>
                            </div>
                        </div>

                    </div>
                    <?php } ?>
                </div>
                <div class="my-6 w-full"> 
                    <div class="w-1/3 mx-auto flex"> 
                        <div class="flex ml-auto">
                            <div class="flex ml-auto mr-4">
                                <span class="my-auto mr-2 cursor-pointer gotoback">Back</span>
                                <button class="bg-gray-300 p-2 rounded-full w-12 h-12 gotoback">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex mr-auto ">
                            <div class="flex hidden next-btn">
                                <button class="bg-black text-white p-2 rounded-full w-12 h-12">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </button> 
                                <span class="my-auto ml-2 next-btn-text">Next</span>
                            </div>
                        </div> 
                    </div> 
                </div>
            </form>
        <?php } ?>


        <?php if(isset($_POST["step"]) && $_POST["step"] == 4){ ?>
            <form action="#" method="POST" id="claimform" class="pt-10 affected-item-section" enctype="multipart/form-data">
                <input type="hidden" name="step" value="5">
                <input type="hidden" name="selected-issue-option" value="<?php echo esc_html($_POST["selected-issue-option"]); ?>">
                <input type="hidden" name="email" value="<?php echo esc_html($_POST["email"]); ?>">
                <input type="hidden" name="orderId" value="<?php echo esc_attr($_POST["orderId"]); ?>">
                <div class="w-full step-4">
                    <div class="w-1/3 mx-auto px-2">
                        <h3 class="text-5xl mb-4 font-light">Affected Items</h3>
                        <p class="text-lg text-gray-500">Please select the items that were lost in transit.</p>
                        <div class="w-full  my-6 border-t-2  border-gray-300"> 
                            <?php  foreach ($order_items as $item_id => $item ) {
                                    $product = $item->get_product();  

                                    $protectionexist = get_post_meta($product->get_id(), "ec_insurance_plan", true);
                                    if(empty($protectionexist)){
                                    ?> 
                                    <div class="product-<?php echo esc_attr($product->get_id()); ?> affected-products-loop">
                                        <div class="flex flex-row py-5 border-b-2 py-4 border-gray-300">
                                            <div class="w-1/5">
                                                <input type="checkbox" name="products[<?php echo esc_attr($product->get_id()); ?>][id]" value="<?php echo esc_attr($product->get_id()); ?>" class="claim-product-checkbox">
                                            </div>
                                            <div class="w-full">
                                                <h3>
                                                <?php echo esc_html($item->get_name()); ?>
                                                </h3>
                                                <p class="text-lg">
                                                    <?php echo esc_attr(get_woocommerce_currency_symbol( $currency_code )); ?> 
                                                    <?php echo esc_attr($item->get_total()); ?> 
                                                </p>
                                            </div>
                                            <div class="w-1/5">
                                                <?php  
                                                $image = wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), 'single-post-thumbnail' );?>
        
                                                <img class="w-2/3 ml-auto" src="<?php  echo esc_url($image[0]); ?>" alt="">
                                            </div> 
                                            
                                        </div>  
                                        <!-- Product Photo -->
                                        <div class="addDamagedItemPhoto hidden">
                                            <?php if(isset($_POST["selected-issue-option"]) && $_POST["selected-issue-option"] == "broken"){ ?>
                                                <div class="w-full my-4 py-4">
                                                    <div class="flex">
                                                        <div class="my-4 ml-auto">
                                                            <span class="text-lg">Add photos of damaged item:</span>
                                                            <label class="bg-blue-400 text-white p-2 rounded">
                                                                Add Photos
                                                                <input type="file" class="w-0 h-0 upload-photos" name="products[<?php echo $product->get_id(); ?>][]" accept="image/gif,image/jpeg,image/jpg,image/png" multiple="multiple">
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="flex preview-uploaded-photos"><div><!--Don't remove - It is used for append preview images--></div></div> 
                                                </div>
                                            <?php } ?> 
                                            <?php if(isset($_POST["selected-issue-option"]) && $_POST["selected-issue-option"] == "broken"){ ?>
                                            <!-- Package Photo -->
                                            <div class="w-full my-4 py-4">
                                                <div class="flex">
                                                    <div class="my-4 ml-auto">
                                                        <span class="text-lg"> Add a photo of the packaging: </span>
                                                        <label class="bg-blue-400 text-white p-2 rounded">
                                                            Add Photo
                                                            <input type="file" class="w-1 h-1  upload-photos" accept="image/gif,image/jpeg,image/jpg,image/png" name="package_photo[<?php echo $product->get_id(); ?>][]" multiple="multiple">
                                                        </label> 
                                                    </div>
                                                </div> 
                                                <div class="flex preview-uploaded-photos"><div><!--Don't remove - It is used for append preview images--></div></div> 
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div> 
                            <?php } }?>    
                        </div> 
                    </div>
                </div>
                <div class="my-6 w-full"> 
                    <div class="w-1/3 mx-auto flex"> 
                        <div class="flex ml-auto">
                            <div class="flex ml-auto mr-4">
                                <span class="my-auto mr-2 cursor-pointer gotoback">Back</span>
                                <button class="bg-gray-300 p-2 rounded-full w-12 h-12 gotoback">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex mr-auto">
                            <div class="flex hidden next-btn">
                                <button class="bg-black text-white p-2 rounded-full w-12 h-12">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </button> 
                                <span class="my-auto ml-2">Next</span>
                            </div>
                        </div> 
                    </div> 
                </div>
            </form>
        <?php } ?>


        <?php if(isset($_POST["step"]) && sanitize_text_field($_POST["step"]) == 5){ 
            
            if(isset($_POST["products"]) && count( $_POST["products"] ) == 0){
                echo "<script>window.history.back();</script>";    
            }   
            $getorderclaimstatus = get_post_meta( sanitize_text_field($_POST["orderId"]), 'claim-status', true ); 
            if(!$getorderclaimstatus){ 
                $products = array();
                if( sanitize_text_field($_POST["selected-issue-option"]) == "broken"){
                    foreach ($_FILES as $fileskey => $filesvalue) {
                    $files = $_FILES[$fileskey]; 
                        foreach ($files as $key => $value) {
                            foreach ( $value as $key1 => $value1) {
                                foreach ( $value1 as $key2 => $value2) { 
                                    $i = 1;
                                    if ($files['name'][$key1][$key2]) {
                                        $new_file_path = $wordpress_upload_dir['path'] . '/' . $files['name'][$key1][$key2];  
                                        if( empty( $files['name'][$key1][$key2] ) )
                                            die( 'File is not selected.' );
        
                                        if( $files['error'][$key1][$key2] )
                                            die( $files['error'][$key1][$key2] );
                                            
                                        if( $$files['size'][$key1][$key2] > wp_max_upload_size() )
                                            die( 'It is too large than expected.' );
                                            
                                        
                                        while( file_exists( $new_file_path ) ) {
                                            $i++;
                                            $new_file_path = $wordpress_upload_dir['path'] . '/' . $i . '_' . $files['name'][$key1][$key2];
                                        }
                                        if( move_uploaded_file( $files['tmp_name'][$key1][$key2], $new_file_path ) ) {
                
        
                                            $upload_id = wp_insert_attachment( array(
                                                'guid'           => $new_file_path, 
                                                'post_mime_type' => $files['type'][$key1][$key2],
                                                'post_title'     => preg_replace( '/\.[^.]+$/', '', $files['name'][$key1][$key2] ),
                                                'post_content'   => '',
                                                'post_status'    => 'inherit'
                                            ), $new_file_path );
                                        
                                            // wp_generate_attachment_metadata() won't work if you do not include this file
                                            require_once( ABSPATH . 'wp-admin/includes/image.php' );
                                        
                                            // Generate and save the attachment metas into the database
                                            wp_update_attachment_metadata( $upload_id, wp_generate_attachment_metadata( $upload_id, $new_file_path ) );
                                            
                                            $products[$fileskey][$key1][] = $wordpress_upload_dir['url'] . '/' . basename( $new_file_path );
                                        
                                        } 
                                    }
                                }
                            }
                        } 
                    } 
                }

                $api_url = ECOMSURANCE_API_URL.'/api/claimsubmit';
                $data["domain"] = get_site_url(); 
                $data["data"] = stripslashes_deep($_POST);         
                $data["photos"] = $products;

                $apiResponse = wp_remote_post( $api_url, array(
                    'body'    => $data 
                ) );
                $apiBody = json_decode( wp_remote_retrieve_body( $apiResponse ) );  

                if($apiBody->message){  
                    $headers = array('Content-Type: text/html; charset=UTF-8');  
                     
                    $order = wc_get_order( sanitize_text_field($_POST["orderId"]) );
                        
                    $shipping_address = $order->get_address('billing');  
                    $customername = $shipping_address["first_name"].' '.$shipping_address["last_name"];
                    $claimedprodduct = "";
                    foreach($apiBody->data as $insuranceorderdata){
                        if($insuranceorderdata->claimproducts != null){
                            foreach($insuranceorderdata->claimproducts as $claimproduct){ 
                                $order_items = $order->get_items();
                                foreach ($order_items as $item_id => $item ) {
                                    $product = $item->get_product(); 
                                    if($claimproduct->wc_order_product_id == $item->get_product_id()){
                                        $claimedprodduct .= "<div class='px-6 py-4 mt-2 border-b-2'>
                                        <span class='w-full inline-block'><b>Title:</b> ".esc_html($item->get_name())."</span> 
                                        <span class='w-full inline-block'><b>Qty</b> ".esc_html($item->get_quantity())."</span> 
                                        <span class='w-full inline-block'><b>Price</b> ".get_woocommerce_currency_symbol( esc_html($currency_code) )." ".esc_html($item->get_total())."</span> 
                                        </div>";
                                    }  
                                } 
                               
                            }
                        } 
                    }

                    // Send mail to customer
                    $to_customer = isset($shipping_address)?$shipping_address["email"]:"N/A";
                    $content_type = function() { return 'text/html'; };
                    add_filter( 'wp_mail_content_type', $content_type );
            
                     
                    $getemailtemplatehtml_customer = get_option("insurify_claim_requested_email_for_customer");
                    if(empty($getemailtemplatehtml_customer)){
                        require_once( ECOMSURANCE__PLUGIN_DIR . 'views/email-templates/filed-customer-template.php' );
                        $getemailtemplatehtml_customer["html"] = $default;  
                    } 
                    $templatehtml_customer = stripslashes($getemailtemplatehtml_customer["html"]);
                    $templatehtml_customer = str_replace( '{{customer.name}}', esc_html($customername), $templatehtml_customer );  
                    $templatehtml_customer = str_replace( '{{site.url}}', esc_url(get_site_url()), $templatehtml_customer );  
                    $templatehtml_customer = str_replace( '{{products.claimed}}', esc_html($claimedprodduct), $templatehtml_customer );  
                      
            
                    wp_mail( esc_html($to_customer), "Your Claim has been filed", esc_html($templatehtml_customer), esc_html($headers) );
                    remove_filter( 'wp_mail_content_type', $content_type );
                    // Send mail to customer  
            
                    // Send mail to Admin
                    $to_admin = get_option("admin_email"); 
                    $default = "";
                    add_filter( 'wp_mail_content_type', $content_type );
                                 
                    $getemailtemplatehtml_admin = get_option("insurify_claim_requested_email_for_admin");
                    if(empty($getemailtemplatehtml_admin)){
                        require_once( ECOMSURANCE__PLUGIN_DIR . 'views/email-templates/filed-admin-template.php' );
                        $getemailtemplatehtml_admin["html"] = $default;  
                    } 
                    $templatehtml_admin = stripslashes($getemailtemplatehtml_admin["html"]);
                    $templatehtml_admin = str_replace( '{{customer.name}}', esc_html($customername), $templatehtml_admin );  
                    $templatehtml_admin = str_replace( '{{order.no}}', sanitize_text_field($_POST["orderId"]), $templatehtml_admin );  
                    $templatehtml_admin = str_replace( '{{products.claimed}}', esc_html($claimedprodduct), $templatehtml_admin );  
                      
            
                    wp_mail( $to_admin, "New Claim Filed", $templatehtml_admin, $headers );
                    remove_filter( 'wp_mail_content_type', $content_type );
                    // Send mail to Admin  
                    
                    update_post_meta( sanitize_text_field($_POST["orderId"]), 'claim-status', 'filed' ); 
                }

            } 
            ?>
            <div class="w-full step-5">
                <div class="w-1/3 mx-auto my-12">
                    <img class="m-auto" src="<?php echo esc_url( plugins_url( 'images/check_img.png', dirname(__DIR__) ) ); ?>" height="129" width="134"/>
                </div>
                <div class="w-1/2 mx-auto">
                    <h2 class="text-center text-4xl">
                        Your claim has been submitted!
                    </h2>
                    <p class="text-center my-4 text-lg text-gray-400">Your claim was received by our team and will get back
                        with you soon.</p>
                </div>
            </div>
            <div class="my-6 w-full">

                <div class="w-1/3 mx-auto flex">

                    <div class="flex ml-auto">
                        <div class="flex ml-auto mr-4">
                            <a href="<?php echo esc_url(get_site_url()); ?>" class="my-auto mr-2 cursor-pointer">Back to store</a>
                            <a href="<?php echo esc_url(get_site_url()); ?>" class="bg-gray-300 p-2 rounded-full w-12 h-12">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div class="flex mr-auto"> </div>

                </div>

            </div>
        <?php } ?> 
    </div>
    <div class="w-full text-center text-1xl footer_div">
        <h6> © EcomSurance - All Right Resreved</h6>
    </div>
</div>
<?php
wp_footer();
?> 
<script> 

/* Claim File Js Start */

jQuery(document).on('click', '.gotoback', function(e) {
    window.history.back();
    e.preventDefault();
}); 

/* Select Option change step 4 start */
jQuery(document).on('change', '.select-issue-option', function(e) {
    e.preventDefault();
    jQuery("input[name^='selected-issue-option']").val("");   
    jQuery(".issue-options").hide();
    jQuery(".issue-section .next-btn").hide();   
    
    var selectedval = jQuery(this).val(); 
    if(selectedval != ""){ 
        if(selectedval == "lost_stolen"){ 
            jQuery(".issue-section .next-btn-text").text("File As Lost");   
            jQuery(".issue-section .next-btn").show();     
        }else if(selectedval == "broken"){
            jQuery(".issue-section .next-btn-text").text("Next");     
            jQuery(".issue-section .next-btn").show();   
        } 
        jQuery("input[name^='selected-issue-option']").val(selectedval);  
        jQuery("."+selectedval+"").show();    
    } 
}); 

/* Select Option change step 4 start */
jQuery(document).on('change', '.claim-product-checkbox', function(e) {
    e.preventDefault();
    jQuery(".affected-item-section .next-btn").hide(); 
    var atLeastOneIsChecked = jQuery('.claim-product-checkbox:checked').length > 0;
    
    if(atLeastOneIsChecked == true){  
        jQuery(".affected-item-section .next-btn").show(); 
    }

    
    /* affected-products-loop start here*/ 
    jQuery(this).parent().parent().parent().find(".addDamagedItemPhoto").show();
    /* affected-products-loop End here*/
}); 

/* upload-photos start */
jQuery(document).on('change', '.upload-photos', function(e) {
    e.preventDefault();
    var previewUploadededPhotos = jQuery(this).parent().parent().parent().parent().find(".preview-uploaded-photos div");
    console.log(previewUploadededPhotos);
    var files = e.target.files,
        filesLength = files.length;
    for (var i = 0; i < filesLength; i++) {
        var f = files[i]
        var fileReader = new FileReader();
        fileReader.onload = (function(e) {
          var file = e.target;
          jQuery("<span class=\"pip\ w-1/6 mb-4 ml-auto\">" +
            "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
            "<br/><span class=\"remove bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-2 border border-gray-400 rounded shadow cursor-pointer\">Remove</span>" +
            "</span>").insertAfter(previewUploadededPhotos);
            jQuery(".remove").click(function(){
                jQuery(this).parent(".pip").remove();
            }); 
          
        });
        fileReader.readAsDataURL(f);
      }
}); 
/*upload-photos End here*/




</script>