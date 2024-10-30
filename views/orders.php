<?php 
defined( 'ABSPATH' ) || exit;
 
	$orders = wc_get_orders( array('numberposts' => -1) );

	if(!empty($orders)){
		// Loop through each WC_Order object
		$i = 0;
		foreach( $orders as $order ){
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
					$protection_orders[$i]["order"] = $order;
					$protection_orders[$i]["protection_product"] = $product;
					$i++;
				}
			}
		} 
	}

	
			

?>
  
<div class="min-h-screen bg-gray-100">
	<?php require ECOMSURANCE__PLUGIN_DIR.'views/includes/navigation.php'; ?>
	<header class="bg-white shadow">
		<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
			<h2 class="font-semibold text-xl text-gray-800 leading-tight"> Orders </h2>
		</div>
	</header>
	<div class="py-12">
		<div class="mx-auto sm:px-6 lg:px-8">
			<div class="bg-white overflow-x-scroll shadow-xl sm:rounded-lg p-4 border-b border-gray-200 shadow">
				<table class="min-w-max w-full" id="mytable">
					<thead>
					<tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
						<th class="py-3 text-left px-3">Order Number</th>
						<th class="py-3 text-left px-3">Order Status</th>
						<th class="py-3 text-left px-3">Protection Plan</th>
						<th class="py-3 text-left px-3">Protection Amount</th>
						<th class="py-3 text-left px-3">Customer Name</th>
						<th class="py-3 text-left px-3">Order Amount</th>
						<th class="py-3 text-left px-3">Claim Status</th>
						<th class="py-3 text-left px-3">Order Created Date</th>
						<th class="py-3 text-left px-3">Action</th> 
					</tr>
					</thead>
					<tbody class="text-gray-600 text-sm font-light">
						<?php 
						if(isset($protection_orders) && !empty($protection_orders)){
							foreach($protection_orders as $protection_order){
								$order_data = $protection_order["order"]->get_data();
   							?>
							<tr class="border-b border-gray-200 hover:bg-gray-100">
							<td class="py-3 px-3 whitespace-nowrap text-lg" data-order="<?php echo esc_attr($protection_order["order"]->get_order_number()); ?>"><a class='text-blue-600/100' href="<?php echo esc_url(admin_url( 'post.php?post=' . esc_attr($protection_order["order"]->get_order_number() )) . '&action=edit'); ?>">#<?php echo esc_attr($protection_order["order"]->get_order_number()); ?></a></td>
								<td class="py-3 px-3 whitespace-nowrap text-lg capitalize"><?php echo esc_html($protection_order["order"]->get_status()); ?></td>
								<td class="py-3 px-3 whitespace-nowrap text-lg">
									<?php echo esc_attr(get_post_meta( $protection_order["protection_product"]->get_id(), "cart_minimum_amount", true)); ?>
									-
									<?php echo esc_attr(get_post_meta( $protection_order["protection_product"]->get_id(), "cart_maximum_amount", true)); ?>
								</td>
								<td class="py-3 px-3 whitespace-nowrap text-lg"><?php echo esc_attr($protection_order["protection_product"]->get_regular_price()); ?></td>
								<td class="py-3 px-3 whitespace-nowrap text-lg">
									<?php  
									$shipping_address = $protection_order["order"]->get_address('billing'); 
									echo isset($shipping_address)? esc_html($shipping_address["first_name"]):"N/A"; 
									echo "&nbsp";
									echo isset($shipping_address)? esc_attr($shipping_address["last_name"]):"N/A";
									?>
								</td>
								<td class="py-3 px-3 whitespace-nowrap text-lg"><?php echo wp_kses($protection_order["order"]->get_formatted_order_total(), array()); ?></td>
								<td class="py-3 px-3 whitespace-nowrap ">
									<span class="bg-blue-300 text-white py-1 px-3 text-lg capitalize rounded font-bold"> <?php 
									$order_status = get_post_meta( $protection_order["order"]->get_id(), "claim-status", true);  
									echo !empty($order_status) ? esc_html($order_status) : "Unclaimed";
									?></span>
								</td>
								<td class="py-3 px-3 whitespace-nowrap text-lg"> <?php echo esc_html($order_data['date_created']->date('Y-m-d H:i:s')); ?></td>
								<td class="py-3 px-3 text-center">
									<div class="flex item-center justify-center">
										<a href="?page=ecomsurance-order-show&id=<?php echo esc_attr($protection_order["order"]->get_order_number()); ?>" class="bg-black text-white rounded py-2 px-4 mr-2">
											View
										</a>
										<a href="<?php echo esc_url(get_site_url()); ?>/order-claim" class="bg-black text-white rounded py-2 px-4 mr-2">
											File
										</a>
									</div>
								</td>
							</tr>
						<?php } }else{
							echo "No Data Found";
						} ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script>
jQuery(document).ready( function () {
    jQuery('#mytable').DataTable({
		aaSorting: [[0, 'desc']],
	});
} );
</script>