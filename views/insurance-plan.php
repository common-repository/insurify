<?php 
defined( 'ABSPATH' ) || exit;

global $woocoomerce;

$args = array(
	'post_type'      => 'product',
	'posts_per_page' => -1,
	'product_cat'    => 'protection',
	'meta_key' => 'ec_insurance_plan', 
	'meta_value' => 'protection',
);

$loop = new WP_Query( $args );
 
?>

<div class="min-h-screen bg-gray-100">
	<?php require ECOMSURANCE__PLUGIN_DIR.'views/includes/navigation.php'; ?>
	<header class="bg-white shadow">
		<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
			<h2 class="font-semibold text-xl text-gray-800 leading-tight"> Insurance Plans </h2>
		</div>
	</header>

	<!-- Table For Listing Insurance plans -->
	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="text-right my-6">
				<a class="bg-blue-500 text-white px-4 py-3 rounded-md focus:outline-none" href="<?php echo esc_url(admin_url("admin.php?page=ecomsurance-insurance-plan-create"));?>">Create New</a>
			</div>

			<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
				<table class="min-w-max w-full table-auto">
					<thead>
						<tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
							<th class="py-3 px-6 text-left whitespace-nowrap ">Insurance plan name</th>
							<th>Cart minimum</th>
							<th>Cart maximum</th>
							<th>Insurance amount</th>
							<th>Surcharge</th>
							<th>Status</th>
							<th>Actions</th> 
						</tr>
					</thead>
					
					<tbody class="text-gray-600 text-sm font-light">
						<?php 
						if(!empty($loop)){ 
							while ( $loop->have_posts() ) : $loop->the_post();
							global $product;
							?>
							<tr class="border-b border-gray-200 hover:bg-gray-100">
								<td class="py-3 px-6 text-left whitespace-nowrap"><?php echo esc_html(get_the_title()); ?></td>
								<td class="py-3 px-6 text-right whitespace-nowrap"><?php echo esc_html(get_post_meta(get_the_id(), "cart_minimum_amount", true)); ?></td>
								<td class="py-3 px-6 text-right whitespace-nowrap"><?php echo esc_html(get_post_meta(get_the_id(), "cart_maximum_amount", true)); ?></td>
								<td class="py-3 px-6 text-right whitespace-nowrap"><?php echo esc_attr($product->get_regular_price()); ?></td>
								<td class="py-3 px-6 text-right whitespace-nowrap"><?php echo esc_html(get_post_meta(get_the_id(), "surcharge", true)); ?></td>
								<td class="py-3 px-6 text-right whitespace-nowrap"><button class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs"><?php echo esc_html($product->get_status()); ?></button></td>
								<td class="py-3 px-6 text-center">
									<div class="flex item-center justify-center">
										<a href="<?php echo esc_url(admin_url( 'admin.php?page=ecomsurance-insurance-plan-create&edit=' . get_the_id() )); ?>" class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
											<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
												stroke="currentColor">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
													d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
											</svg>
										</a>
										<a href="<?php echo esc_url(admin_url( 'admin.php?page=ecomsurance-insurance-plan-create&delete=' . esc_attr(get_the_id()) )); ?>" class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
											<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
												stroke="currentColor">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
													d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
											</svg>
										</a>
									</div>
								</td>
							</tr>
							<?php
							endwhile;
							wp_reset_query();
						}
 						?>
					</tbody>
				</table>
			</div> 

		</div>
	</div>
</div> 