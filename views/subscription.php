<?php
defined( 'ABSPATH' ) || exit; 
global $current_user;
      
$api_urlgetsubscriptiondetails = ECOMSURANCE_API_URL.'/api/getsubscriptiondetails'; 

$id_token = get_user_meta( get_current_user_id(), 'auth_insurify_id_token', true); 
$data["domain"] = get_site_url(); 
$data["email"] = $current_user->user_email; 
$apiResponsegetsubscriptiondetails = wp_remote_post( $api_urlgetsubscriptiondetails, array(
	'body'    => $data,
	'headers' => array(
		'Authorization' => 'Bearer '.$id_token,
	),
) );
$apiBodygetsubscriptiondetails = json_decode( wp_remote_retrieve_body( $apiResponsegetsubscriptiondetails ) ); 
 
?>

<div class="min-h-screen bg-gray-100">
	<?php require ECOMSURANCE__PLUGIN_DIR.'views/includes/navigation.php'; ?>
	<header class="bg-white shadow">
		<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
			<h2 class="font-semibold text-xl text-gray-800 leading-tight"> Manage Subscription </h2>
		</div>
	</header>

	<div class="py-4">
		<div class="container flex flex-wrap pb-6 m-auto">
			<div class="w-full px-0 lg:px-4">

				<!-- Subscription Expires div -->
				<?php if( !empty($apiBodygetsubscriptiondetails->subscription) &&  $apiBodygetsubscriptiondetails->subscription->ends_at != null){ ?>
					<div class="space-x-2 bg-blue-50 p-4 rounded flex items-start text-blue-600 my-4 shadow-lg mx-auto max-w-2xl">
						<div class="">
							<svg xmlns="http://www.w3.org/2000/svg" class="fill-current w-5 pt-1" viewBox="0 0 24 24">
								<path
									d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm-1.5 5h3l-1 10h-1l-1-10zm1.5 14.25c-.69 0-1.25-.56-1.25-1.25s.56-1.25 1.25-1.25 1.25.56 1.25 1.25-.56 1.25-1.25 1.25z"/>
							</svg>
						</div>
						<h3 class="text-blue-800 tracking-wider flex-1">
							Your subscription will expires on  
						</h3>
					</div>
				<?php } ?>
				
				<!-- Subscription trialEndsAt Expires div -->
				<?php if( !empty($apiBodygetsubscriptiondetails->subscription) &&  $apiBodygetsubscriptiondetails->subscription->trial_ends_at != null){ ?>
				<div class="space-x-2 bg-yellow-50 p-4 rounded flex items-start text-yellow-600 my-4 shadow-lg mx-auto max-w-2xl">
					<div class="">
						<svg xmlns="http://www.w3.org/2000/svg" class="fill-current w-5 pt-1" viewBox="0 0 24 24">
							<path
								d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm-1.5 5h3l-1 10h-1l-1-10zm1.5 14.25c-.69 0-1.25-.56-1.25-1.25s.56-1.25 1.25-1.25 1.25.56 1.25 1.25-.56 1.25-1.25 1.25z"/>
						</svg>
					</div>
					<h3 class="text-yellow-800 tracking-wider flex-1">
						Your trial will expires on  
					</h3>
				</div>
				<?php } ?>

				<!-- paymentMethod/Cards div -->
				<div class="flex flex-wrap px-4 py-6 bg-white rounded">
					<div class="flex">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 my-auto" fill="none"
								viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
									d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
						</svg>
						<span class="text-xl px-2 text-gray-500">
						<span class="font-semibold">Payment Method:</span>
						<?php echo esc_attr($apiBodygetsubscriptiondetails->paymentMethod->pm_type); ?> ending  <?php echo esc_attr($apiBodygetsubscriptiondetails->paymentMethod->pm_last_four); ?>
					</span>
					</div>
					<div class="ml-auto">
						<a href="<?php echo admin_url(); ?>admin.php?page=ecomsurance-billing" class="bg-black px-4 py-2 rounded text-white">Manage</a>
					</div>
				</div>

				<div class="bg-white my-6 py-6 px-4 rounded">
					<h3 class="text-2xl">Usage</h3>
					<hr class="my-2">
					<p>Total usage for this billing period: <?php echo esc_attr($apiBodygetsubscriptiondetails->totalUsage); ?> </p>
					<p>Estimated billing amount: <?php echo esc_attr($apiBodygetsubscriptiondetails->totalUsage * $apiBodygetsubscriptiondetails->plan->unit_amount); ?> </p>
				</div>
				
				<div  class="flex flex-wrap items-center justify-center py-4 pt-0"> 
					<div class="w-full p-4 md:w-1/2 lg:w-1/4">
						<label
							class="flex flex-col rounded-lg shadow-lg relative cursor-pointer hover:shadow-2xl">
							<div
								class="w-full px-4 py-8 rounded-t-lg border-blue-500 bg-white hover:bg-blue-50 text-blue-600">
								<h3 class="mx-auto text-base font-semibold text-center underline">
									<?php echo esc_attr($apiBodygetsubscriptiondetails->plan->product->name); ?> 
								</h3>
								<p class="text-5xl font-bold text-center my-2">
									$<?php echo esc_attr($apiBodygetsubscriptiondetails->plan->unit_amount/100); ?>   
								</p>
								<p class="text-xs text-center uppercase ">
									per Protected order
								</p>
								<p class="text-xs text-center uppercase ">
									billed weekly
								</p>
							</div> 
						</label>
					</div> 
				</div>
			</div>
		</div>
	</div>
</div>