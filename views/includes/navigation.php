<?php  
defined( 'ABSPATH' ) || exit;
$ecomsuranceactive = $ecomsuranceinsuranceplanactive = $ecomsuranceordersactive = $ecomsuranceclaimsactive = $ecomsuranceemailtemplatesactive = $ecomsurancesettingactive = $ecomsuranceinquiryactive = $ecomsurancebillingactive = $ecomsurancesubscriptionactive = $ecomsurancesubscriptionactive = "";
if( sanitize_text_field($_REQUEST["page"]) == "ecomsurance"){
	$ecomsuranceactive = "border-b-2 border-indigo-400";
}elseif(sanitize_text_field($_REQUEST["page"]) == "ecomsurance-insurance-plan"){
	$ecomsuranceinsuranceplanactive = "border-b-2 border-indigo-400";
}elseif(sanitize_text_field($_REQUEST["page"]) == "ecomsurance-orders"){
	$ecomsuranceordersactive = "border-b-2 border-indigo-400";
}elseif(sanitize_text_field($_REQUEST["page"]) == "ecomsurance-claims"){
	$ecomsuranceclaimsactive = "border-b-2 border-indigo-400";
}elseif(sanitize_text_field($_REQUEST["page"]) == "ecomsurance-email-templates"){
	$ecomsuranceemailtemplatesactive = "border-b-2 border-indigo-400";
}elseif(sanitize_text_field($_REQUEST["page"]) == "ecomsurance-setting"){
	$ecomsurancesettingactive = "border-b-2 border-indigo-400";
}elseif(sanitize_text_field($_REQUEST["page"]) == "ecomsurance-inquiry"){
	$ecomsuranceinquiryactive = "border-b-2 border-indigo-400";
}elseif(sanitize_text_field($_REQUEST["page"]) == "ecomsurance-billing"){
	$ecomsurancebillingactive = "border-b-2 border-indigo-400";
}elseif(sanitize_text_field($_REQUEST["page"]) == "ecomsurance-subscription"){
	$ecomsurancesubscriptionactive = "border-b-2 border-indigo-400";
} 
?> 
<nav class="bg-white border-b border-gray-100">
	<!-- Primary Navigation Menu -->
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="flex justify-between h-16">
			<div class="flex">
				<!-- Logo -->
				<div class="flex-shrink-0 flex items-center">
					<a href="?page=ecomsurance">
						<img src="<?php echo esc_url( plugins_url( 'images/insurify-icon.webp', dirname(__DIR__)) ); ?>" alt="" class="block border-0 h-9 w-auto">
					</a>
				</div>
				<!-- Navigation Links -->
				<div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
					<a class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out <?php echo esc_html($ecomsuranceactive);?>" href="?page=ecomsurance">
						Dashboard
					</a>
				</div>
				
				<div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
					<a class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out <?php echo esc_html($ecomsuranceinsuranceplanactive);?>" href="?page=ecomsurance-insurance-plan">
					Insurance Plans
					</a>
				</div>
				
				<div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
					<a class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out <?php echo esc_html($ecomsuranceordersactive);?>" href="?page=ecomsurance-orders">
					Orders
					</a>
				</div>
				
				<div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
					<a class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out <?php echo esc_html($ecomsuranceclaimsactive);?>" href="?page=ecomsurance-claims">
					Claims
					</a>
				</div>
				
				<div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
					<a class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out <?php echo esc_html($ecomsuranceemailtemplatesactive);?>" href="?page=ecomsurance-email-templates">
					Email Templates
					</a>
				</div>
				
				<div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
					<a class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out <?php echo esc_html($ecomsurancesettingactive);?>" href="?page=ecomsurance-setting">
					Settings
					</a>
				</div>
				
				<div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
					<a class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out <?php echo esc_html($ecomsuranceinquiryactive);?>" href="?page=ecomsurance-inquiry">
					Help
					</a>
				</div> 
				
				<div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
					<a class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out <?php echo esc_html($ecomsurancesubscriptionactive);?>" href="?page=ecomsurance-subscription">
					Subscription
					</a>
				</div> 
				
				<div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
					<a class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out <?php echo esc_html($ecomsurancebillingactive);?>" href="?page=ecomsurance-billing">
					Billing
					</a>
				</div> 
			</div>
		</div>
	</div>
</nav>