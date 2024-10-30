<?php 
defined( 'ABSPATH' ) || exit;
 
	$order = wc_get_order( sanitize_text_field($_REQUEST["id"]) );

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
		 
	}


	$order_data = $protection_order["order"]->get_data();	
	$shipping_address = $protection_order["order"]->get_address('billing'); 
	$order_items = $protection_order["order"]->get_items();
	// Iterating through each "line" items in the order      
	$currency_code = $protection_order["order"]->get_currency();
?>

<div class="min-h-screen bg-gray-100">
	<?php require ECOMSURANCE__PLUGIN_DIR.'views/includes/navigation.php'; ?>
	<header class="bg-white shadow">
		<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
			<h2 class="font-semibold text-xl text-gray-800 leading-tight"> Order Details </h2>
		</div>
	</header>
	<div class="intro-y box overflow-hidden mt-5 bg-white shadow">
            <div
                class="border-b border-gray-200 dark:border-dark-5 text-center sm:text-left"
            >
                <div class="px-5 py-10 sm:px-20 sm:py-20">
                    <div class="text-theme-1 dark:text-theme-10 font-semibold text-3xl text-blue-800">
                        Order
                    </div>
                    <div class="mt-2">
                        Receipt <span class="font-medium">#<?php echo esc_attr($protection_order["order"]->get_order_number()); ?></span>
                    </div>
                    <div class="mt-1"><?php echo esc_attr($order_data['date_created']->date('Y-m-d H:i:s')); ?></div>
                </div>
                <div
                    class="flex flex-col lg:flex-row px-5 sm:px-20 pt-10 pb-10 sm:pb-20"
                >
                    <div>
                        <div class="text-base  text-blue-600">Customer Details</div>
                        <div class="text-blue-600 text-lg font-medium text-theme-1 dark:text-theme-10 mt-2" >
						<?php   
						echo isset($shipping_address)? esc_attr($shipping_address["first_name"]):"N/A"; 
						echo "&nbsp";
						echo isset($shipping_address)? esc_attr($shipping_address["last_name"]):"N/A";
						?>
                        </div>
                        <div class="mt-1">
						<?php echo isset($shipping_address)? esc_attr($shipping_address["email"]):"N/A"; ?>
                        </div>
                        <div class="mt-1"> 
                            <?php echo isset($shipping_address)? esc_attr($shipping_address["address_1"]):"N/A"; ?><br>
                            <?php echo isset($shipping_address)? esc_attr($shipping_address["address_2"]):"N/A"; ?><br>
                            <?php echo isset($shipping_address)? esc_attr($shipping_address["city"]):"N/A"; ?>, <?php echo isset($shipping_address)? esc_attr($shipping_address["state"]):"N/A"; ?>,
                            <?php echo isset($shipping_address)? esc_attr($shipping_address["postcode"]):"N/A"; ?><br>
                            <?php echo isset($shipping_address)? esc_attr($shipping_address["country"]):"N/A"; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-5 sm:px-16 py-10 sm:py-20">
                <div class="overflow-x-auto">
                    <table class="table-auto w-full">
                        <thead>
                        <tr>
                            <th class="border-b-2 dark:border-dark-5 text-left whitespace-nowrap">
                                Item
                            </th>
                            <th class="border-b-2 dark:border-dark-5 text-right whitespace-nowrap" >
                                QTY
                            </th>
                            <th  class="border-b-2 dark:border-dark-5 text-right whitespace-nowrap" >
                                PRICE
                            </th>
                            <th class="border-b-2 dark:border-dark-5 text-right whitespace-nowrap" >
                                SUBTOTAL
                            </th>
                        </tr>
                        </thead>
                        <tbody> 
							<?php 
									foreach ($order_items as $item_id => $item ) {
										$product = $item->get_product();  
									?> 
                            	<tr>
								
									<td class="w-1/2 border-b dark:border-dark-5">
										<div class="font-medium whitespace-nowrap">
											<?php echo esc_html($item->get_name()); ?>
										</div> 
									</td>
									<td class="text-right border-b dark:border-dark-5 w-32">
										<?php echo esc_attr($item->get_quantity()); ?>
									</td>
									<td class="text-right border-b dark:border-dark-5 w-32">
										<?php echo esc_html(get_woocommerce_currency_symbol( $currency_code )); ?> 
										<?php echo esc_attr($item->get_subtotal()); ?> 
									</td>
									<td class="text-right border-b dark:border-dark-5 w-32 font-medium" >
										<?php echo esc_html(get_woocommerce_currency_symbol( $currency_code )); ?> 
										<?php echo esc_attr($item->get_total()); ?> 
									</td>
                            	</tr> 
							<?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="px-5 sm:px-20 pb-10 sm:pb-20 flex flex-col-reverse sm:flex-row" >
                <div class="text-center sm:text-right sm:ml-auto">
                    <div class="text-base text-gray-600">Total Amount</div>
                    <div class="text-xl text-theme-1 dark:text-theme-10 font-medium mt-2">
						<?php echo esc_html(get_woocommerce_currency_symbol( $currency_code )); ?> 
						<?php echo wp_kses($protection_order["order"]->get_formatted_order_total(), array()); ?> 
                        
                    </div>
                </div>
            </div>
        </div>
</div>