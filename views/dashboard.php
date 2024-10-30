<?php 
defined( 'ABSPATH' ) || exit;
	
	$orders = wc_get_orders( array('numberposts' => -1, 'type' => 'shop_order') );
	for ($i = -2; $i <= 0; $i++){
		$array_month[] = date('F', strtotime("$i month"));
	} 
	$protection_orders = $total_revenue = $total_earnings = $claim_amount = $total_revenue_month =  $filed_amount =  $inprogress_amount =  $reorder_amount =  $refund_amount =  $other_amount = array();
	if(!empty($orders)){
		// Loop through each WC_Order object
		$i = 0;
		foreach( $orders as $order ){ 
			$created_date = $order->get_date_created()->getTimestamp();
			
			
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
					$protection_orders[] = $order; 
					$total_earnings[] = $product->get_regular_price();
					$order_status = get_post_meta( $order->get_id(), "claim-status", true);  
					
					if($created_date >= strtotime('-90 days')) {  
						$total_revenue[] = $product->get_regular_price();
						if($order_status == "filed"){
							$filed_amount[] = $order->get_total();
						}
						if($order_status == "inprogress"){
							$inprogress_amount[] = $order->get_total();
						}
						if($order_status == "reorder"){
							$reorder_amount[] = $order->get_total();
						}
						if($order_status == "refund"){
							$refund_amount[] = $order->get_total();
						}
						if($order_status == "other"){
							$other_amount[] = $order->get_total();
						}
						if(!empty($order_status)){
							$claim_amount[] = $order->get_total();
						}
					}

					if($created_date >= strtotime('-30 days')) {  
						$total_revenue_month[] = $product->get_regular_price(); 
					}

					if($created_date >= strtotime('-60 days') && $created_date <= strtotime('-30 days')) {  
						$total_revenue_month[] = $product->get_regular_price(); 
					}

					if($created_date >= strtotime('-90 days') && $created_date <= strtotime('-60 days')) {  
						$total_revenue_month[] = $product->get_regular_price(); 
					}

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
			<h2 class="font-semibold text-xl text-gray-800 leading-tight"> Dashboard </h2>
		</div>
	</header>

	<div class="py-12">
		<div class="mx-auto sm:px-6 lg:px-8">

			<div class="flex flex-wrap">

				<div class="flex-1 bg-white rounded shadow p-4">
					<div class="flex flex-row items-center">
						<div class="flex-shrink pr-4 w-16 h-16">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
								<path fill-rule="evenodd"
										d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
										clip-rule="evenodd"/>
							</svg>
						</div>
						<div class="flex-1 text-right md:text-center">
							<h5 class="font-bold uppercase text-gray-400">Total Protected Orders</h5>
							<h3 class="font-bold text-3xl text-gray-600"><?php echo esc_html(number_format(count($protection_orders), 2));?> <span
								class="text-green-500"></span></h3>
						</div>
					</div>
				</div>

				<div class="flex-1 bg-white rounded shadow p-4 ml-2">
					<div class="flex flex-row items-center">
						<div class="flex-shrink pr-4 w-16 h-16">
							<svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
								<path
									d="M15 9a2 2 0 1 1-.001 4.001A2 2 0 0 1 15 9zm-5 0a2 2 0 1 1 .001-4.001A2 2 0 0 1 10 9zm6-7a2 2 0 1 1-.001 4.001A2 2 0 0 1 16 2zm-3 14a1 1 0 1 1 0 2H7a.998.998 0 0 1-.243-.03l-4-1A1 1 0 0 1 2 16v-3c0-.431.275-.813.684-.948l3-1a.947.947 0 0 1 .294-.047C5.985 11.004 5.992 11 6 11h3a1 1 0 1 1 0 2H7.166c.599 1.807 2.828 3 5.834 3z"></path>
							</svg>
						</div>
						<div class="flex-1 text-right md:text-center">
							<h5 class="font-bold uppercase text-gray-400">Total Revenue</h5>
							<h3 class="font-bold text-3xl text-gray-600"><?php echo esc_html(number_format(array_sum($total_revenue), 2)); ?><span
								class="text-green-500"></span></h3>
						</div>
					</div>
				</div>

				<div class="flex-1 bg-white rounded shadow p-4 ml-2">
					<div class="flex flex-row items-center">
						<div class="flex-shrink pr-4 w-16 h-16">
							<svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
								<path
									d="M15 12h-2v-1c0-.551-.449-1-1-1H9.414l.586.586A1 1 0 1 1 8.586 12L6.293 9.707a1 1 0 0 1 0-1.414L8.586 6A1 1 0 1 1 10 7.414L9.414 8H12c1.654 0 3 1.346 3 3v1zm2-8.5A1.5 1.5 0 0 0 15.5 2h-11A1.5 1.5 0 0 0 3 3.5V17a1 1 0 0 0 1.3.954c.18-.057.317-.195.439-.338l1.121-1.321 1.349 1.399a1.002 1.002 0 0 0 1.415.026l1.364-1.318 1.305 1.305a.997.997 0 0 0 1.414 0l1.42-1.42 1.136 1.332c.12.141.257.277.434.334A1 1 0 0 0 17 17V3.5z"></path>
							</svg>
						</div>
						<div class="flex-1 text-right md:text-center">
							<h5 class="font-bold uppercase text-gray-400">Refund Amount</h5>
							<h3 class="font-bold text-3xl text-gray-600">
								<span class="text-gray-500"><?php echo esc_html(number_format(array_sum($refund_amount), 2)); ?></span>
							</h3>
						</div>
					</div>
				</div>

				<div class="flex-1 bg-white rounded shadow p-4 ml-2">
					<div class="flex flex-row items-center">
						<div class="flex-shrink pr-4 w-16 h-16">
							<svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
								<path
									d="M10 20C4.486 20 0 15.514 0 10S4.486 0 10 0s10 4.486 10 10-4.486 10-10 10zm1-15a1 1 0 1 0-2 0v.17A3 3 0 0 0 7 8c0 1.013.36 1.77 1.025 2.269.54.405 1.215.572 1.666.685l.066.016c.55.138.835.224 1.018.361.085.064.225.182.225.669a1 1 0 0 1-.984 1 1.611 1.611 0 0 1-.325-.074 2.533 2.533 0 0 1-.984-.633 1 1 0 0 0-1.414 1.414A4.548 4.548 0 0 0 9 14.804V15a1 1 0 1 0 2 0v-.17A3 3 0 0 0 13 12c0-1.013-.36-1.77-1.025-2.269-.54-.405-1.215-.572-1.666-.685l-.066-.016c-.55-.138-.835-.224-1.018-.361C9.14 8.605 9 8.487 9 8a1 1 0 0 1 .984-1 1.618 1.618 0 0 1 .325.074c.245.081.606.255.984.633a1 1 0 1 0 1.414-1.414A4.547 4.547 0 0 0 11 5.196V5z"></path>
							</svg>
						</div>
						<div class="flex-1 text-right md:text-center">
							<h5 class="font-bold uppercase text-gray-400">Total Earnings</h5>
							<h3 class="font-bold text-3xl text-gray-600"><?php echo esc_html(number_format(array_sum($total_earnings), 2)); ?> <span
								class="text-green-500"></span></h3>
						</div>
					</div>
				</div>

				<div class="flex-1 bg-white rounded shadow p-4 ml-2">
					<div class="flex flex-row items-center">
						<div class="flex-shrink pr-4 w-16 h-16">
							<svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
								<path
									d="M12.984 18a.999.999 0 0 1-.94-.658L8.967 8.888l-2.05 5.465a1.001 1.001 0 0 1-1.794.163L2.935 10.87l-1.124 1.685a1.002 1.002 0 0 1-1.665-1.11l2.001-3a.966.966 0 0 1 .856-.444.998.998 0 0 1 .834.485l1.936 3.223L8.044 5.65A1 1 0 0 1 8.982 5h.005a1.001 1.001 0 0 1 .935.658l2.948 8.1 3.154-11.033c.12-.42.502-.714.94-.724.43-.026.834.268.97.683l2.002 6a1 1 0 1 1-1.897.631l-.98-2.932-3.112 10.888a1 1 0 0 1-.928.726h-.035"></path>
							</svg>
						</div>
						<div class="flex-1 text-right md:text-center">
							<h5 class="font-bold uppercase text-gray-400">Total Protected Percentage</h5>
							<h3 class="font-bold text-3xl text-gray-600">
								<?php echo esc_html(number_format((count($protection_orders)*100)/count($orders), 2)); ?>% <span
								class="text-green-500"></span></h3>
						</div>
					</div>
				</div>

				<div class="flex-1 bg-white rounded shadow p-4 ml-2">
					<div class="flex flex-row items-center">
						<div class="flex-shrink pr-4 w-16 h-16">
							<svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
								<path
									d="M10 20C4.486 20 0 15.514 0 10S4.486 0 10 0s10 4.486 10 10-4.486 10-10 10zm1-15a1 1 0 1 0-2 0v.17A3 3 0 0 0 7 8c0 1.013.36 1.77 1.025 2.269.54.405 1.215.572 1.666.685l.066.016c.55.138.835.224 1.018.361.085.064.225.182.225.669a1 1 0 0 1-.984 1 1.611 1.611 0 0 1-.325-.074 2.533 2.533 0 0 1-.984-.633 1 1 0 0 0-1.414 1.414A4.548 4.548 0 0 0 9 14.804V15a1 1 0 1 0 2 0v-.17A3 3 0 0 0 13 12c0-1.013-.36-1.77-1.025-2.269-.54-.405-1.215-.572-1.666-.685l-.066-.016c-.55-.138-.835-.224-1.018-.361C9.14 8.605 9 8.487 9 8a1 1 0 0 1 .984-1 1.618 1.618 0 0 1 .325.074c.245.081.606.255.984.633a1 1 0 1 0 1.414-1.414A4.547 4.547 0 0 0 11 5.196V5z"></path>
							</svg>
						</div>
						<div class="flex-1 text-right md:text-center">
							<h5 class="font-bold uppercase text-gray-400">Claims Amount</h5>
							<h3 class="font-bold text-3xl text-gray-600">
								<span class="text-gray-500"><?php echo esc_html(number_format(array_sum($claim_amount), 2)); ?></span>
							</h3>
						</div>
					</div>
				</div>

			</div>

			<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg my-6">
				<canvas id="myChart" style="width:100%;"></canvas>
				<script>
				var arrayformonth = <?php echo json_encode($array_month); ?>; 
				var arrayforprice = <?php echo json_encode($total_revenue_month); ?>; 
				var totalearnings = <?php echo round(array_sum($total_earnings),0,PHP_ROUND_HALF_UP ); ?>; 
				var xValues = arrayformonth;
				var yValues = arrayforprice;

				new Chart("myChart", {
				type: "line",
				data: {
					labels: xValues,
					datasets: [{
					fill: false,
					lineTension: 0,
					backgroundColor: "rgba(0,0,255,1.0)",
					borderColor: "rgba(0,0,255,0.1)",
					data: yValues
					}]
				},
				options: {
					plugins: {
						legend: {display: false},
						scales: {
						yAxes: [{ticks: {min: 0, max:totalearnings}}],
						}
					}
				}
				});
				</script>
			</div>

			<h3 class="font-semibold mb-2 ml-2 mt-4 text-xl">Claim Insights</h3>

			<div class="flex flex-wrap">

				<div class="flex-1 bg-white rounded shadow p-4 ml-2 mr-1">
					<div class="flex-1">
						<h5 class="font-bold mb-4">Filed</h5>
						<h3 class="font-bold text-3xl text-gray-600"><?php echo esc_html(count($filed_amount)); ?></h3>
					</div>
				</div>

				<div class="flex-1 bg-white rounded shadow p-4 ml-2 mr-1">
					<div class="flex-1">
						<h5 class="font-bold mb-4">In Progress</h5>
						<h3 class="font-bold text-3xl text-gray-600"><?php echo esc_html(count($inprogress_amount)); ?></h3>
					</div>
				</div>

				<div class="flex-1 bg-white rounded shadow p-4 ml-2 mr-1">
					<div class="flex-1">
						<h5 class="font-bold mb-4">Reorder</h5>
						<h3 class="font-bold text-3xl text-gray-600"><?php echo esc_html(count($reorder_amount)); ?></h3>
					</div>
				</div>

				<div class="flex-1 bg-white rounded shadow p-4 ml-2 mr-1">
					<div class="flex-1">
						<h5 class="font-bold mb-4">Refund</h5>
						<h3 class="font-bold text-3xl text-gray-600"><?php echo esc_html(count($refund_amount)); ?></h3>
					</div>
				</div>

				<div class="flex-1 bg-white rounded shadow p-4 ml-2 mr-1">
					<div class="flex-1">
						<h5 class="font-bold mb-4">Other</h5>
						<h3 class="font-bold text-3xl text-gray-600"><?php echo esc_html(count($other_amount)); ?></h3>
					</div>
				</div>

			</div>

		</div>
	</div>
</div> 