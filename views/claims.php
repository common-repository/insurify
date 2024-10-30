<?php  
defined( 'ABSPATH' ) || exit;

	$orders = wc_get_orders( array('numberposts' => -1, 'type' => 'shop_order') );

	if(!empty($orders)){
		// Loop through each WC_Order object
		$i = 0;
		foreach( $orders as $order ){
			$order_items = $order->get_items();
			// Iterating through each "line" items in the order      
			foreach ($order->get_items() as $item_id => $item ) {
				$product = $item->get_product();
				$ec_insurance_plan = get_post_meta($product->get_id(), "ec_insurance_plan", true);
				$claimstatus = get_post_meta($order->get_id(), "claim-status", true);
				// Get a list of categories and extract their names
				$product_categories = get_the_terms( $product->get_id(), 'product_cat' );
				if ( ! empty( $product_categories ) && ! is_wp_error( $product_categories ) ) {
					$categories = wp_list_pluck( $product_categories, 'name' );
				}

				if($ec_insurance_plan == "protection" && in_array("protection", $categories) && !empty($claimstatus)){
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
			<h2 class="font-semibold text-xl text-gray-800 leading-tight"> Claims </h2>
		</div>
	</header>
	<div class="py-12">
		<div class="mx-auto sm:px-6 lg:px-8">
			<div class="bg-white overflow-x-scroll shadow-xl sm:rounded-lg p-4 border-b border-gray-200 shadow">
				<table class="min-w-max w-full" id="mytableClaims">
					<thead>
					<tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
						<th class="py-3 text-left px-3">Order Number</th>
						<th class="py-3 text-left px-3">Order EMAIL</th> 
						<th class="py-3 text-left px-3">Customer Name</th> 
						<th class="py-3 text-left px-3">Claim Status</th>
						<th class="py-3 text-left px-3">Date</th>
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
								<td class="py-3 px-3 whitespace-nowrap text-lg"  data-order="<?php echo esc_attr($protection_order["order"]->get_order_number()); ?>"><a class='text-blue-600/100' href="<?php echo esc_url(admin_url( 'post.php?post=' . esc_attr($protection_order["order"]->get_order_number() )) . '&action=edit'); ?>">#<?php echo esc_attr($protection_order["order"]->get_order_number()); ?></a></td>
								<td class="py-3 px-3 whitespace-nowrap text-lg"><?php  
									$shipping_address = $protection_order["order"]->get_address('billing');  
									echo isset($shipping_address)? esc_html($shipping_address["email"]):"N/A";
									?></td>
								<td class="py-3 px-3 whitespace-nowrap text-lg">
									<?php   
									echo isset($shipping_address)? esc_html($shipping_address["first_name"]):"N/A"; 
									echo "&nbsp";
									echo isset($shipping_address)? esc_html($shipping_address["last_name"]):"N/A";
									?>
								</td>
								<td class="py-3 px-3 whitespace-nowrap">
									<span class="bg-blue-300 text-white px-4  text-lg capitalize rounded font-bold"><?php 
									$order_status = get_post_meta( $protection_order["order"]->get_id(), "claim-status", true);  
									echo !empty($order_status) ? esc_html($order_status) : "Unclaimed";
									?></span>
								</td>
								<td class="py-3 px-3 whitespace-nowrap text-lg"> <?php echo esc_html($order_data['date_created']->date('d M, Y')); ?></td>
								<td class="py-3 px-3 text-center">
									<div class="flex">
										<button data-orderid="<?php echo esc_attr($protection_order["order"]->get_order_number()); ?>" class="block text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800 mr-2 viewclaimmodal" data-modal="my-modal-viewclaim">
											View
										</button>
										<button class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 openPopup" type="button" data-orderid="<?php echo esc_attr($protection_order["order"]->get_order_number()); ?>">
											Update
										</button>
									</div>
								</td>
							</tr>
						<?php } } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<!-- my-modal-popuppreview start Update Status-->
	<div id="my-modal-popuppreview" class="fixed z-10 inset-0 overflow-y-auto hidden update-claim-status" aria-labelledby="modal-title" role="dialog" aria-modal="true">
		<div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
			<div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

			<!-- This element is to trick the browser into centering the modal contents. -->
			<span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
 
			<div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
			<div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4"> 
				<div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
					<h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
					Update Claim Status
					</h3>
					<div class="mt-5"> 
						<div class="mb-4">
							<input type="hidden" name="claimId" value="">
							<label class="block text-gray-700 text-sm font-bold mb-2" for="username">
								Claim Status
							</label>
							<select class="w-full max-w-none px-2 py-2" name="updateStatusSelect">
								<option value="filed">Filed</option>
								<option value="inprogress">In Progress</option>
								<option value="reorder">Reorder</option>
								<option value="refund">Refund</option>
								<option value="other">Other</option>
							</select>
						</div>
						<div class="mb-4">
							<label class="block text-gray-700 text-sm font-bold mb-2">
								Claim Notes
							</label>
							<textarea name="claimNotes" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Notes"></textarea>
						</div>
					
					</div>
				</div> 
			</div>
			<div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
				<button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm closeBtn">
				close
				</button> 
				<button type="button" id="saveStatus" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
				Save
				</button>
			</div>
			</div>
		</div>
	</div>

	
	<!-- View Claim Popup Start-->
	<div id="my-modal-viewclaim" class="fixed z-10 inset-0 overflow-y-auto hidden closeModalClass" aria-labelledby="modal-title" role="dialog" aria-modal="true">
		<div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
			<div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

			<!-- This element is to trick the browser into centering the modal contents. -->
			<span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
 
			<div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
			<div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4"> 
				<div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
					<h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
					Claim Details
					</h3>
					<div class="mt-5 insert_data_here">  
					
					</div>
				</div> 
			</div>
			<div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
				<button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm closeModal" data-modal="my-modal-viewclaim">
				close
				</button>  
			</div>
			</div>
		</div>
	</div>
	<!-- View Claim Popup End-->
</div> 

<script>  
jQuery(document).ready( function () {
    jQuery('#mytableClaims').DataTable({
		aaSorting: [[0, 'desc']],
	});
} );
</script>