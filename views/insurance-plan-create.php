<?php 
defined( 'ABSPATH' ) || exit;

global $woocommerce;

$category = "protection";
$catexist = term_exists($category);
 
if(empty($catexist)){ 
	wp_insert_term(
		$category,
		'product_cat',
		array(
			'description' => 'This category used for ecommsurance plans.',
			'slug'        => $category,
		)
	);

	$catexist = term_exists($category);
}

 



if(isset($_REQUEST["edit"]) && !empty( sanitize_text_field($_REQUEST["edit"])) ){
	$title = "Edit";
	$product = wc_get_product( sanitize_text_field($_REQUEST["edit"]) ); 
	

	if(isset($_REQUEST["editinsuranceplan"])){
		$product = wc_get_product( sanitize_text_field($_REQUEST["edit"]) );
		if( ! $product )
			return false;
	
		$product->set_name( sanitize_title($_REQUEST['name']) ); // Name (title)
		// Description and short description:
		$product->set_description( "Ecomsurance Insurance Plans." );
		$product->set_short_description( "Ecomsurance Insurance Plans." );
	 
		$product->set_status( sanitize_text_field($_REQUEST['active']) ); 
		$product->set_catalog_visibility( 'hidden' ); 
	 
		$product->set_regular_price( sanitize_text_field($_REQUEST['regular_price']) );
		$product->set_category_ids( array($catexist) );
		$product->update_meta_data( 'cart_minimum_amount', sanitize_text_field($_REQUEST['cart_minimum_amount']));
		$product->update_meta_data( 'cart_maximum_amount', sanitize_text_field($_REQUEST['cart_maximum_amount']));
		$product->update_meta_data( 'ec_insurance_plan', "protection");
		$product->update_meta_data( 'surcharge', sanitize_text_field($_REQUEST['surcharge']));
	
		$product_id = $product->save();
	
		$image = plugins_url( 'images/insurify-icon.webp', dirname(__DIR__));
		$mediaid = media_sideload_image( esc_url($image), "", "", 'id');
		set_post_thumbnail( $product_id, $mediaid); 
	}	
	$cart_minimum_amount = get_post_meta($product->get_id(), "cart_minimum_amount", true);
	$cart_maximum_amount = get_post_meta($product->get_id(), "cart_maximum_amount", true);
	$surcharge = get_post_meta($product->get_id(), "surcharge", true);
	$submitbtn = "editinsuranceplan";
	$product_status = "updated";
	
}elseif(isset($_REQUEST["delete"]) && !empty( sanitize_text_field($_REQUEST["delete"]))){ 
	wp_trash_post( sanitize_text_field($_REQUEST["delete"]) );
	wp_safe_redirect( admin_url("admin.php?page=ecomsurance-insurance-plan") );
}else{ 
	$title = "Create";
	$submitbtn = "saveinsuranceplan";
	$product_status = "Added";

	if(isset($_REQUEST["saveinsuranceplan"])){
		$product = new WC_Product_Simple();
		if( ! $product )
			return false; 
		$product->set_name( sanitize_title($_REQUEST['name']) );  
		$product->set_description( "Ecomsurance Insurance Plans." );
		$product->set_short_description( "Ecomsurance Insurance Plans." ); 
		$product->set_status( sanitize_text_field($_REQUEST['active']) ); 
		$product->set_catalog_visibility( 'hidden' ); 
	 
		$product->set_regular_price( sanitize_text_field($_REQUEST['regular_price']) );
		$product->set_category_ids( array($catexist) );
		$product->update_meta_data( 'cart_minimum_amount', sanitize_text_field($_REQUEST['cart_minimum_amount']));
		$product->update_meta_data( 'cart_maximum_amount', sanitize_text_field($_REQUEST['cart_maximum_amount']));
		$product->update_meta_data( 'ec_insurance_plan', "protection");
		$product->update_meta_data( 'surcharge', sanitize_text_field($_REQUEST['surcharge']));
	
		$product_id = $product->save(); 
	}
}

?>

<div class="min-h-screen bg-gray-100">
	<?php require ECOMSURANCE__PLUGIN_DIR.'views/includes/navigation.php'; ?>
	<header class="bg-white shadow">
		<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
			<h2 class="font-semibold text-xl text-gray-800 leading-tight"> <?php echo esc_html($title); ?> Insurance Plan</h2>
		</div>
	</header>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-10"> 
				<?php if(isset($product_id) && !empty($product_id)){ ?>
					<div class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3" role="alert">
						<p class="font-bold">Informational message</p>
						<p class="text-sm">Product <?php echo esc_html($product_status);?> Successfully.</p>
					</div>
				<?php } ?>
				<form action="#" method="post">
					<div class="col-span-6 sm:col-span-4 py-4">
						<label>Insurance Plan Name* (Name must be unique)</label>
						<input id="insurance_plna_name" type="text" class="mt-1 block w-full"
								value='<?php echo isset($product) ? esc_html($product->get_title()) : ""; ?>'	name="name" required/> 
					</div>

					<div class="col-span-6 sm:col-span-4 py-4">
						<label>Cart Minimum</label>
						<input id="insurance_plan_cart_minimum" type="number" class="mt-1 block w-full"
								value='<?php echo isset($cart_minimum_amount) ? esc_attr($cart_minimum_amount) : ""; ?>'	name="cart_minimum_amount" step="0.01" required/>
					</div>

					<div class="col-span-6 sm:col-span-4 py-4">
						<label>Cart Maximum</label>
						<input id="insurance_plan_cart_maximum" type="number" class="mt-1 block w-full"
						value='<?php echo isset($cart_maximum_amount) ? esc_attr($cart_maximum_amount) : ""; ?>'	name="cart_maximum_amount" step="0.01" required/> 
					</div>

					<div class="col-span-6 sm:col-span-4 py-4">
						<label>Amount</label>
						<input id="insurance_plan_amount" type="number" class="mt-1 block w-full"
						value='<?php echo isset($product) ? esc_attr($product->get_regular_price()) : ""; ?>'	name="regular_price" step="0.01" required/> 
					</div>

					<div class="col-span-6 sm:col-span-4 py-4">
						<label>Surcharge</label>
						<input id="insurance_plan_surcharge" type="number" class="mt-1 block w-full"
						value='<?php echo isset($surcharge) ? esc_attr($surcharge) : ""; ?>'	name="surcharge" required/> 
					</div>

					<div class="col-span-6 sm:col-span-4 py-4">
						<label>Active</label>
						<input type="hidden" name="active" value="draft" />
						<input
							id="insurance_plan_active"
							type="checkbox"
							class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 p-3"
							name="active" value="publish" <?php if(isset($product) && esc_html($product->get_status()) == "publish") { echo 'checked'; } ?>/> 
					</div>

					<div class="col-span-6 sm:col-span-4 py-4">
						<button type="submit" name="<?php echo esc_html($submitbtn); ?>" class="bg-blue-500 text-white px-4 py-3 rounded-md focus:outline-none">
							Save
						</button>
						<a href="<?php echo admin_url("admin.php?page=ecomsurance-insurance-plan");?>" class="bg-blue-500 text-white px-4 py-3 rounded-md focus:outline-none">
							Back
						</a>
					</div>
				</form>
			</div>

		</div>
	</div>  
</div>