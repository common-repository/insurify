<?php 

defined( 'ABSPATH' ) || exit;
if(isset($_POST["submit"])){
	$to = "support@webinopoly.com"; 
	$subject = sanitize_text_field($_POST["subject"]);
	$message = sanitize_text_field($_POST["message"]);
	$admin_email = get_option("admin_email");
	$siteurl = get_option("siteurl");
	$headers[]= "From: ".esc_html($admin_email)." <".esc_html($siteurl).">";  
	wp_mail( $to, $subject, $message, $headers );
	 
}
?>

<div class="min-h-screen bg-gray-100">
	<?php require ECOMSURANCE__PLUGIN_DIR.'views/includes/navigation.php'; ?>
	<header class="bg-white shadow">
		<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
			<h2 class="font-semibold text-xl text-gray-800 leading-tight"> Submit Your Query </h2>
		</div>
	</header>
	<div class="py-12">
 
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

			<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-10">

				<div class="col-span-6 sm:col-span-4 py-4">
					<div class="grid grid-cols-2 gap-4">
						<div>
							<p class="pb-3">Please go to <span class="bg-gray-300 text-red-700 p-1 text-xs rounded">Storefront -> My themes -> Edit your theme -> templates -> pages -> cart.html</span></p>

							<p class="pb-3">The app auto loads under <span class="bg-gray-300 text-red-700 p-1 text-xs rounded">&lt;div data-cart-content &gt;...&lt;/div&gt;</span></p>

							<p class="pb-3">Some themes might not allow the app to inject the script automatically, so to load the app widget manually on the cart page please search for this line of code </p>

							<p class="pb-3"><span class="bg-gray-300 text-red-700 p-1 text-xs rounded">&lt;div data-cart-content &gt;...</span> and insert below tag:</p> 
							<p class="pb-3">
								<code class="bg-gray-200 px-2 pb-1 pt-2 rounded">
									&lt;div class="insurify-widget-container"&gt;&lt;/div&gt;
								</code>
							</p>

							<p class="pb-3">For more info, please watch this short video:</p> 
							<p class="pb-3">https://watch.screencastify.com/v/0dMhLNn6RZJ6ooWVtw8m</p>
						</div>
						<div>
							<object class="max-w-3/4 mx-auto" data="https://watch.screencastify.com/v/0dMhLNn6RZJ6ooWVtw8m"
								width="500"
								height="500"
								type="text/html">
							</object> 
						</div>
					</div>
				</div>
			</div>

			<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-10 mt-10">
				<form action="#" method="POST">
				<div class="col-span-6 sm:col-span-4 py-4">
					<h2 class="text-xl font-bold text-center">Need help in installing our app or got general queries? Please contact us and one of our engineers will get back to you</h2>
				</div>  
				<div class="col-span-6 sm:col-span-4 py-4">
					<label>Subject</label>
					<input id="subject" type="text" class="mt-1 block w-full"
								name="subject"
								ref="subject" required/> 
				</div>

				<div class="col-span-6 sm:col-span-4 py-4">
					<label>Message</label>
					<textarea id="message" type="text"
								name="message"
								rows="10"
								class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full"
								ref="message" required></textarea> 
				</div>

				<div class="col-span-6 sm:col-span-4 py-4">
					<button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" name="submit"> Submit </button>
				</div>
				</form>
			</div>

		</div>
	</div>
</div>