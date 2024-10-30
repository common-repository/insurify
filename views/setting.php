<?php 
 
defined( 'ABSPATH' ) || exit;
global $current_user; 

if(isset($_REQUEST["submitappform"])){ 
	$wordpress_upload_dir = wp_upload_dir(); 
	if(isset($_FILES["storeLogo"]) && $_FILES["storeLogo"]["error"] == 0 ){
		$profilepicture = $_FILES["storeLogo"];
		$new_file_path = $wordpress_upload_dir['path'] . '/' . $profilepicture['name'];
		$new_file_mime = mime_content_type( $profilepicture['tmp_name'] );

		if( $profilepicture['size'] > wp_max_upload_size() )
			die( 'It is too large than expected.' );

		if( !in_array( $new_file_mime, get_allowed_mime_types() ) )
			die( 'WordPress doesn\'t allow this type of uploads.' );

		while( file_exists( $new_file_path ) ) {
			$i++;
			$new_file_path = $wordpress_upload_dir['path'] . '/' . $i . '_' . $profilepicture['name'];
		}

		if( move_uploaded_file( $profilepicture['tmp_name'], $new_file_path ) ) {
	

			$upload_id = wp_insert_attachment( array(
				'guid'           => $new_file_path, 
				'post_mime_type' => $new_file_mime,
				'post_title'     => preg_replace( '/\.[^.]+$/', '', $profilepicture['name'] ),
				'post_content'   => '',
				'post_status'    => 'inherit'
			), $new_file_path );
		
			// wp_generate_attachment_metadata() won't work if you do not include this file
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
		
			// Generate and save the attachment metas into the database
			wp_update_attachment_metadata( $upload_id, wp_generate_attachment_metadata( $upload_id, $new_file_path ) ); 
			$insurify_storelogo = get_option("insurify_storelogo"); 
			if(empty($insurify_storelogo)){
				delete_option("insurify_storelogo");
				add_option( "insurify_storelogo" , $wordpress_upload_dir['url'] . '/' . basename( $new_file_path) ); 
			}else{
				update_option( "insurify_storelogo" , $wordpress_upload_dir['url'] . '/' . basename( $new_file_path) ); 
			} 
			 
		}
	}

	if(isset($_FILES["coverImage"]) && $_FILES["coverImage"]["error"] == 0 ){
		$profilepicture = $_FILES["coverImage"];
		$new_file_path = $wordpress_upload_dir['path'] . '/' . $profilepicture['name'];
		$new_file_mime = mime_content_type( $profilepicture['tmp_name'] );

		if( $profilepicture['size'] > wp_max_upload_size() )
			die( 'It is too large than expected.' );

		if( !in_array( $new_file_mime, get_allowed_mime_types() ) )
			die( 'WordPress doesn\'t allow this type of uploads.' );

		while( file_exists( $new_file_path ) ) {
			$i++;
			$new_file_path = $wordpress_upload_dir['path'] . '/' . $i . '_' . $profilepicture['name'];
		}

		if( move_uploaded_file( $profilepicture['tmp_name'], $new_file_path ) ) {
	

			$upload_id = wp_insert_attachment( array(
				'guid'           => $new_file_path, 
				'post_mime_type' => $new_file_mime,
				'post_title'     => preg_replace( '/\.[^.]+$/', '', $profilepicture['name'] ),
				'post_content'   => '',
				'post_status'    => 'inherit'
			), $new_file_path );
		
			// wp_generate_attachment_metadata() won't work if you do not include this file
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
		
			// Generate and save the attachment metas into the database
			wp_update_attachment_metadata( $upload_id, wp_generate_attachment_metadata( $upload_id, $new_file_path ) ); 
			
			$insurify_coverimage = get_option("insurify_coverimage"); 
			if(empty($insurify_coverimage)){
				delete_option("insurify_coverimage");
				add_option( "insurify_coverimage" , $wordpress_upload_dir['url'] . '/' . basename( $new_file_path) ); 
			}else{
				update_option( "insurify_coverimage" , $wordpress_upload_dir['url'] . '/' . basename( $new_file_path) ); 
			}   
		}
	}
 
	$insurify_app_status = get_option("insurify_app_status"); 
	$sanitizedapp_status = filter_var($_REQUEST["app_status"], FILTER_SANITIZE_NUMBER_INT);
	if(empty($insurify_app_status)){
		delete_option("insurify_app_status");
		add_option( "insurify_app_status" , $sanitizedapp_status ); 
	}else{
		update_option( "insurify_app_status" , $sanitizedapp_status ); 
	}

	$insurify_protection_toggle_default = get_option("insurify_protection_toggle_default"); 
	$sanitizedprotection_toggle_default = filter_var($_REQUEST["protection_toggle_default"], FILTER_SANITIZE_NUMBER_INT);
	if(empty($insurify_protection_toggle_default)){
		delete_option("insurify_protection_toggle_default");
		add_option( "insurify_protection_toggle_default" , $sanitizedprotection_toggle_default ); 
	}else{
		update_option( "insurify_protection_toggle_default" , $sanitizedprotection_toggle_default ); 
	}

	$insurify_protection_toggle_cartorcheckout = get_option("insurify_protection_toggle_cartorcheckout"); 
	$sanitizedprotection_toggle_cartorcheckout = filter_var($_REQUEST["protection_toggle_cartorcheckout"], FILTER_SANITIZE_NUMBER_INT);
	if(empty($insurify_protection_toggle_cartorcheckout)){
		delete_option("insurify_protection_toggle_cartorcheckout");
		add_option( "insurify_protection_toggle_cartorcheckout" , $sanitizedprotection_toggle_cartorcheckout ); 
	}else{
		update_option( "insurify_protection_toggle_cartorcheckout" , $sanitizedprotection_toggle_cartorcheckout ); 
	}

	$insurify_toggle_type = get_option("insurify_toggle_type"); 
	$sanitizedtoggle_type = sanitize_text_field($_REQUEST["toggle_type"]);
	if(empty($insurify_toggle_type)){
		delete_option("insurify_toggle_type");
		add_option( "insurify_toggle_type" , $sanitizedtoggle_type ); 
	}else{
		update_option( "insurify_toggle_type" , $sanitizedtoggle_type ); 
	}

	$insurify_protection_title = get_option("insurify_protection_title"); 
	$sanitizedtoggle_type = sanitize_text_field($_REQUEST["toggle_type"]);
	if(empty($insurify_protection_title)){
		delete_option("insurify_protection_title");
		add_option( "insurify_protection_title" , $sanitizedtoggle_type ); 
	}else{
		update_option( "insurify_protection_title" , $sanitizedtoggle_type ); 
	}

	$insurify_protection_subtitle = get_option("insurify_protection_subtitle"); 
	$sanitizedprotection_subtitle = sanitize_text_field($_REQUEST["protection_subtitle"]);
	if(empty($insurify_protection_subtitle)){
		delete_option("insurify_protection_subtitle");
		add_option( "insurify_protection_subtitle" , $sanitizedprotection_subtitle ); 
	}else{
		update_option( "insurify_protection_subtitle" , $sanitizedprotection_subtitle ); 
	}

	$insurify_protection_description = get_option("insurify_protection_description"); 
	$sanitizedprotection_description = sanitize_text_field($_REQUEST["protection_description"]);
	if(empty($insurify_protection_description)){
		delete_option("insurify_protection_description");
		add_option( "insurify_protection_description" , $sanitizedprotection_description ); 
	}else{
		update_option( "insurify_protection_description" , $sanitizedprotection_description ); 
	}

	$insurify_toggle_inline_css = get_option("insurify_toggle_inline_css"); 
	$sanitizedtoggle_inline_css = sanitize_textarea_field($_REQUEST["toggle_inline_css"]);
	if(empty($insurify_toggle_inline_css)){
		delete_option("insurify_toggle_inline_css");
		add_option( "insurify_toggle_inline_css" , $sanitizedtoggle_inline_css ); 
	}else{
		update_option( "insurify_toggle_inline_css" , $sanitizedtoggle_inline_css ); 
	} 

}

$app_status = get_option("insurify_app_status");
$protection_toggle_default = get_option("insurify_protection_toggle_default");
$protection_toggle_cartorcheckout = get_option("insurify_protection_toggle_cartorcheckout");
$toggle_type = get_option("insurify_toggle_type");
$protection_title = get_option("insurify_protection_title"); 
$protection_subtitle = get_option("insurify_protection_subtitle");  
$protection_description = get_option("insurify_protection_description");  
$toggle_inline_css = get_option("insurify_toggle_inline_css");  
$storelogo = get_option("insurify_storelogo");  
$coverimage = get_option("insurify_coverimage"); 
?>

<div class="min-h-screen bg-gray-100 toggle_btn">
	<?php require ECOMSURANCE__PLUGIN_DIR.'views/includes/navigation.php'; ?>
	<header class="bg-white shadow">
		<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
			<h2 class="font-semibold text-xl text-gray-800 leading-tight"> Settings </h2>
		</div>
	</header>
	<div class="mx-auto py-10 sm:px-6 lg:px-8"> 
		<form action="#" method="post" autocomplete="on" enctype="multipart/form-data">
		<!-- App Status --> 
		<div class="md:grid md:grid-cols-3 md:gap-6">
			<div class="md:col-span-1">
				<div class="px-4 sm:px-0">
					<h3 class="font-semibold text-lg font-medium text-gray-900">Enable/Disable App </h3>
					<p class="mt-1 text-sm text-gray-600">
						The entire app can be turned on or off by changing this setting.
					</p>
				</div>
			</div>
			<div class="mt-5 md:mt-0 md:col-span-2">
				<div class="px-4 py-5 bg-white sm:p-6 shadow-md rounded-md">
					<p class="mt-2 mb-4">
						App is
						<label for="app_status_button" class="<?php if($app_status == 1) { echo esc_html('active'); } ?> switch__checkbox">
							<span class="switch__label"><?php if($app_status == 1) { echo esc_html('Enabled'); }else{ echo esc_html("Disabled"); } ?></span>
							<input type="hidden" name="app_status" value="0" /> 	
							<input class="ml-1 switch__checkbox_input" type="checkbox" value="1" name="app_status"  <?php if($app_status == 1) { echo esc_html('checked'); } ?> />
						</label>
					</p>
					<p>App auto loads on.</p> 
				</div>
			</div>
		</div>

		<!-- Toggle Default -->
		<div class="mt-6">
			<div class="md:grid md:grid-cols-3 md:gap-6">
				<div class="md:col-span-1 flex">
					<div class="px-4 sm:px-0 flex">
						<h3 class="font-semibold text-lg font-medium text-gray-900 my-auto">Protection Toggle
							Default (Enable/Disable)</h3>
					</div>
				</div>

				<div class="mt-5 md:mt-0 md:col-span-2">
					<div class="px-4 py-5 bg-white sm:p-6 shadow-md rounded-md">
						<p class="mt-2 mb-4">
							By Default Protection Toggle
							<label class="<?php if($protection_toggle_default == 1) { echo esc_html('active'); } ?> switch__checkbox">
								<span class="switch__label"><?php if($protection_toggle_default == 1) { echo esc_html('Enabled'); }else{ echo esc_html("Disabled"); } ?></span>
								<input type="hidden" name="protection_toggle_default" value="0" /> 	
								<input class="ml-1 switch__checkbox_input" type="checkbox" value="1" name="protection_toggle_default"  <?php if($protection_toggle_default == 1) { echo esc_html('checked'); } ?> />
							</label>  
						</p>
					</div>
				</div>
			</div>

			<div class="hidden sm:block">
				<div class="py-8">
					<div class="border-t border-gray-200"></div>
				</div>
			</div>
		</div> 

		<!-- Toggle for Checkout Or Cart -->
		<div class="mt-6">
			<div class="md:grid md:grid-cols-3 md:gap-6">
				<div class="md:col-span-1 flex">
					<div class="px-4 sm:px-0 flex">
						<h3 class="font-semibold text-lg font-medium text-gray-900 my-auto">Show Toggle On The Checkout Page Instead Of Cart Page.</h3>
					</div>
				</div>

				<div class="mt-5 md:mt-0 md:col-span-2">
					<div class="px-4 py-5 bg-white sm:p-6 shadow-md rounded-md">
						<p class="mt-2 mb-4">
							Toggle Button On The Checkout Page is
							<label class="<?php if($protection_toggle_cartorcheckout == 1) { echo esc_html('active'); } ?> switch__checkbox">
								<span class="switch__label"><?php if($protection_toggle_cartorcheckout == 1) { echo esc_html('Enabled'); }else{ echo esc_html("Disabled"); } ?></span>
								<input type="hidden" name="protection_toggle_cartorcheckout" value="0" /> 	
								<input class="ml-1 switch__checkbox_input" type="checkbox" value="1" name="protection_toggle_cartorcheckout"  <?php if($protection_toggle_cartorcheckout == 1) { echo esc_html('checked'); } ?> />
							</label> 
						</p>
					</div>
				</div>
			</div>

			<div class="hidden sm:block">
				<div class="py-8">
					<div class="border-t border-gray-200"></div>
				</div>
			</div>
		</div>

		<div class="mt-6">
			<div class="md:grid md:grid-cols-3 md:gap-6">
				<div class="md:col-span-1 flex">
					<div class="px-4 sm:px-0 flex">
						<h3 class="font-semibold text-lg font-medium text-gray-900 my-auto">Your Portal URL is</h3>
					</div>
				</div>

				<div class="mt-5 md:mt-0 md:col-span-2">
					<div class="px-4 py-5 bg-white sm:p-6 shadow-md rounded-md">
						<p class="mt-2 mb-4">
							<a target="_blank" href="<?php echo admin_url("admin.php?page=ecomsurance-claims"); ?>"><?php echo admin_url("admin.php?page=ecomsurance-claims"); ?></a><a href="<?php echo admin_url("admin.php?page=ecomsurance-claims"); ?>" class="bg-black text-white rounded py-2 px-4 ml-5">View</a>
						</p>
					</div>
				</div>
			</div>

			<div class="hidden sm:block">
				<div class="py-8">
					<div class="border-t border-gray-200"></div>
				</div>
			</div>
		</div>


		<!-- Toggle Details -->
		<div>
			<div class="md:grid md:grid-cols-3 md:gap-6">
				<div class="md:col-span-1">
					<div class="px-4 sm:px-0">
						<h3 class="font-semibold text-lg font-medium text-gray-900">Protection Settings</h3>
						<p class="mt-1 text-sm text-gray-600">
							Here you can customize toggle text:
						</p>
					</div>
				</div>

				<div class="mt-5 md:mt-0 md:col-span-2">
					<div class="px-4 py-5 bg-white sm:p-6 shadow-md rounded-md">

						<div class="col-span-6 sm:col-span-4">
							<!-- Togle type -->
							<div>
								<p>Toggle Type</p>
								<div class="flex my-2">
									<div class="mx-2">
										<input id="toggle_type_swatch" type="radio" value="swatch"
										name="toggle_type" <?php if($toggle_type == "swatch") { echo esc_html('checked'); } ?> required/>
										<label class="align-middle ml-2" for="toggle_type_swatch">Swatch</label>
									</div>
									<div class="mx-2">
										<input id="toggle_type_checkbox" type="radio" value="checkbox"
												name="toggle_type" <?php if($toggle_type == "checkbox") { echo esc_html('checked'); } ?>/>
										<label class="align-middle ml-2" for="toggle_type_checkbox">Checkbox</label>
									</div>
								</div>
							</div>

							<!-- Protection Title -->
							<div class="col-span-6 sm:col-span-4 my-4">
								<label for="protection_title">Title for protection</label>
								<?php 
								if(empty($protection_title)){
									$protection_title = "Shipping Protection";
								} ?>
								<input id="protection_title" type="text" class="mt-1 block w-full"
								name="protection_title" value="<?php echo esc_html($protection_title); ?>" autocomplete="protection_title" required/> 
							</div>

							<!-- Protection Subtitle -->
							<div class="col-span-6 sm:col-span-4 my-4">
								<label for="protection_title">Subtitle for protection</label>
								<?php 
								if(empty($protection_subtitle)){
									$protection_subtitle = "from Damage, Loss & Theft for";
								} ?>
								<input id="protection_subtitle" type="text" class="mt-1 block w-full"  value="<?php echo esc_html($protection_subtitle); ?>" name="protection_subtitle" autocomplete="protection_subtitle" required/> 
							</div>

							<!-- Protection description -->
							<div class="col-span-6 sm:col-span-4 my-4"> 
								<label for="protection_title">Subtitle for protection</label> 
								<textarea id="protection_description"
											rows="5"
											class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full"
											name="protection_description"
											autocomplete="protection_description"><?php echo esc_html($protection_description); ?></textarea> 
							</div>

						</div>
					</div>
				</div>
			</div>  
			<div class="hidden sm:block">
				<div class="py-8">
					<div class="border-t border-gray-200"></div>
				</div>
			</div>
		</div>

		<!-- Store Logo -->
		<div>
			<div class="md:grid md:grid-cols-3 md:gap-6">
				<div class="md:col-span-1 flex">
					<div class="px-4 sm:px-0">
						<h3 class="font-semibold text-lg font-medium text-gray-900 my-auto">Upload Store Logo</h3>
						<p class="mt-1 text-sm text-gray-600">
							This logo image will be shown in claim portal and email
						</p>
					</div>
				</div>


				<div class="mt-5 md:mt-0 md:col-span-2">
					<div class="px-4 py-5 bg-white sm:p-6 shadow-md rounded-md">
						<div class="flex">
							<div class="w-2/3">
								<input type="file" ref="store_logo" name="storeLogo">
							</div>
							<?php if($storelogo != null){ ?>
							<div class="w-1/3">
								<img src="<?php echo esc_html($storelogo);?>" alt="" class="w-1/4">
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
			<div class="hidden sm:block">
				<div class="py-8">
					<div class="border-t border-gray-200"></div>
				</div>
			</div>
		</div>

		<!-- Cover Image -->
		<div>
			<div class="md:grid md:grid-cols-3 md:gap-6">
				<div class="md:col-span-1 flex">
					<div class="px-4 sm:px-0">
						<h3 class="font-semibold text-lg font-medium text-gray-900 my-auto">Upload Cover Image</h3>
						<p class="mt-1 text-sm text-gray-600">
							This cover image will be shown in claim portal
						</p>
					</div>
				</div>


				<div class="mt-5 md:mt-0 md:col-span-2">
					<div class="px-4 py-5 bg-white sm:p-6 shadow-md rounded-md">
						<div class="flex">
							<div class="w-2/3">
								<input type="file" ref="cover_image" name="coverImage">
							</div>
							<?php if($coverimage != null){ ?>
							<div class="w-1/3">
								<img src="<?php echo esc_html($coverimage);?>" alt="" class="w-1/4">
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
			<div class="hidden sm:block">
				<div class="py-8">
					<div class="border-t border-gray-200"></div>
				</div>
			</div>
		</div>

		<!-- Inline Css -->
		<div>
			<div class="md:grid md:grid-cols-3 md:gap-6">
				<div class="md:col-span-1 flex">
					<div class="px-4 sm:px-0">
						<h3 class="font-semibold text-lg font-medium text-gray-900 my-auto">Inline CSS</h3>
						<p class="mt-1 text-sm text-gray-600">
							Here you can more customize your popup design by adding your custom CSS. <br>
							(Do not include style tag)
						</p>
					</div>
				</div>


				<div class="mt-5 md:mt-0 md:col-span-2">
					<div class="px-4 py-5 bg-white sm:p-6 shadow-md rounded-md">
						<textarea
							name="toggle_inline_css"
							class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full"
							name="inline_css"
							rows="5"><?php echo esc_html($toggle_inline_css); ?></textarea>
					</div>
				</div>
			</div>
			<div class="hidden sm:block">
				<div class="py-8">
					<div class="border-t border-gray-200"></div>
				</div>
			</div>
		</div>

		<!-- Popup Edit -->
		<div>
			<div class="md:grid md:grid-cols-3 md:gap-6">
				<div class="md:col-span-1 flex">
					<div class="px-4 sm:px-0">
						<h3 class="font-semibold text-lg font-medium text-gray-900 my-auto">Edit Popup</h3>
						<p class="mt-1 text-sm text-gray-600">
							Here you can more customize your popup design.
						</p>
					</div>
				</div>


				<div class="mt-5 md:mt-0 md:col-span-2">
					<div class="px-4 py-5 bg-white sm:p-6 shadow-md rounded-md">
						<a href="<?php echo admin_url("admin.php?page=ecomsurance-edit-popup"); ?>" class="bg-black text-white rounded py-2 px-4 ml-5">
									Click here to Edit Popup
						</a>
					</div>
				</div>
			</div> 
			<div class="hidden sm:block">
				<div class="py-8">
					<div class="border-t border-gray-200"></div>
				</div>
			</div>
		</div>

		<div>
			<div class="flex items-center justify-end px-4 py-3 text-right"> 
				<button class="bg-black py-2 px-4 rounded text-white flex" type="submit" name="submitappform">Save</button>
			</div>
		</div>
	</form>
	</div>
</div>