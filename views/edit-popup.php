<?php 
defined( 'ABSPATH' ) || exit;
 
global $current_user;
$wordpress_upload_dir = wp_upload_dir();
 
if(isset($_REQUEST["updatepopup"])){ 
	foreach($_FILES as $filekey => $file){ 
		if(isset($file) && $file["error"] == 0 ){
			$profilepicture = $file;
			$new_file_path = $wordpress_upload_dir['path'] . '/' . $profilepicture['name'];
			$new_file_mime = mime_content_type( $profilepicture['tmp_name'] );
	
			if( $profilepicture['size'] > wp_max_upload_size() )
				die( 'It is too large than expected.' );
	
			if( !in_array( $new_file_mime, get_allowed_mime_types() ) )
				die( 'WordPress doesn\'t allow this type of uploads.' );
			$i = 0;
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

				$ecomsurance_img = get_option($filekey); 
				if(empty($ecomsurance_img)){
					add_option( $filekey , $wordpress_upload_dir['url'] . '/' . basename( $new_file_path) );
				}else{
					update_option( $filekey , $wordpress_upload_dir['url'] . '/' . basename( $new_file_path) );
				} 
			}
		}
	}

	$title = get_option("title"); 
	if(empty($title)){
		delete_option("title");
		add_option( "title" , sanitize_text_field($_REQUEST["title"]) ); 
	}else{
		update_option( "title" , sanitize_text_field($_REQUEST["title"]) ); 
	}
	 
	$leftimagetitle = get_option("leftimagetitle"); 
	if(empty($leftimagetitle)){
		delete_option("leftimagetitle");
		add_option( "leftimagetitle" , sanitize_text_field($_REQUEST["leftimagetitle"]) ); 
	}else{
		update_option( "leftimagetitle" , sanitize_text_field($_REQUEST["leftimagetitle"]) ); 
	}
	 
	$centerimagetitle = get_option("centerimagetitle"); 
	if(empty($centerimagetitle)){
		delete_option("centerimagetitle");
		add_option( "centerimagetitle" , sanitize_text_field($_REQUEST["centerimagetitle"]) ); 
	}else{
		update_option( "centerimagetitle" , sanitize_text_field($_REQUEST["centerimagetitle"]) ); 
	}
	 
	$rightimagetitle = get_option("rightimagetitle"); 
	if(empty($rightimagetitle)){
		delete_option("rightimagetitle");
		add_option( "rightimagetitle" , sanitize_text_field($_REQUEST["rightimagetitle"]) ); 
	}else{
		update_option( "rightimagetitle" , sanitize_text_field($_REQUEST["rightimagetitle"]) ); 
	}
	 
	$term = get_option("term"); 
	if(empty($term)){
		delete_option("term");
		add_option( "term" , sanitize_text_field($_REQUEST["term"]) ); 
	}else{
		update_option( "term" , sanitize_text_field($_REQUEST["term"]) ); 
	}
	 
	$copyright = get_option("copyright"); 
	if(empty($copyright)){
		delete_option("copyright");
		add_option( "copyright" , sanitize_text_field($_REQUEST["copyright"]) ); 
	}else{
		update_option( "copyright" , sanitize_text_field($_REQUEST["copyright"]) ); 
	}
	
}
 
$title = get_option("title");
$leftimagetitle = get_option("leftimagetitle");
$centerimagetitle = get_option("centerimagetitle"); 
$rightimagetitle = get_option("rightimagetitle");  
$term = get_option("term"); 
$copyright = get_option("copyright"); 


$ecomsurance_popup_logo = get_option("ecomsurance_popup_logo"); 
$popupbgimg = get_option("popupbgimg");  
$leftimage = get_option("leftimage");  
$centerimage = get_option("centerimage");  
$rightimage = get_option("rightimage"); 
?>

<div class="min-h-screen bg-gray-100 toggle_btn">
	<?php require ECOMSURANCE__PLUGIN_DIR.'views/includes/navigation.php'; ?>
	<header class="bg-white shadow">
		<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
			<h2 class="font-semibold text-xl text-gray-800 leading-tight"> Detail Popup </h2>
		</div>
	</header>
	<div class="mx-auto py-10 sm:px-6 lg:px-8"> 
	<form method="post" enctype="multipart/form-data" action="#">
            <div class="p-12 w-1/2 m-auto my-8 rounded-lg shadow-xl bg-gray-100">
                <div class="flex justify-center">
                    <a href="void:javascript(0)" class="font-bold rounded text-2xl openPopup">
                        Please click here to view a sample
                    </a>  
                </div>
                <div class="flex justify-center mt-8">
                    <div class="rounded-lg shadow-xl bg-gray-50 mx-1 w-1/3">
                        <div class="m-4">
                            <label class="inline-block mb-2 text-gray-500">Logo (180x50px)</label>
                                        
                            <div class="flex items-center justify-center w-full relative">
								<?php if(isset($ecomsurance_popup_logo) && !empty($ecomsurance_popup_logo)){ ?>
                                <button class="text-white font-bold rounded absolute top-1 right-1 z-50 removeImg" data-id="ecomsurance_popup_logo">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
								<?php } ?>
                                <label class="flex flex-col w-full h-32 border-4 border-dashed hover:bg-gray-100 hover:border-gray-300"> 
									<?php if(!empty($ecomsurance_popup_logo)){
										$imgpreview = "visible";
										$hideafterimgprevw = "hidden";
									}else{
										$imgpreview = "hidden";
										$hideafterimgprevw = "visible";
									} ?>
									<div class="flex flex-col items-center justify-center overflow-auto imgpreview <?php echo esc_html($imgpreview); ?>">
										<img class="w-full h-full text-gray-400 group-hover:text-gray-600"  src="<?php echo esc_url($ecomsurance_popup_logo); ?>" />
									</div> 
                                    <div class="flex flex-col items-center justify-center pt-7 hideafterimgprevw <?php echo esc_html($hideafterimgprevw); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="w-12 h-12 text-gray-400 group-hover:text-gray-600" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <p class="pt-1 text-sm tracking-wider text-gray-400 group-hover:text-gray-600">
                                            Select a photo</p>
                                    </div> 
                                    <input type="file" name="ecomsurance_popup_logo" enctype="multipart/form-data" class="opacity-0 onFileChange" />
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-lg shadow-xl bg-gray-50 mx-1 w-1/3">
                        <div class="m-4">
                            <label class="inline-block mb-2 text-gray-500">Header Text</label>
                            <div class="w-full h-32">
                                <textarea type="text" name="title" class="border-gray-400 w-full h-full" ><?php echo esc_html($title); ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-lg shadow-xl bg-gray-50 w-1/3 mx-1">
                        <div class="m-4">
                            <label class="inline-block mb-2 text-gray-500">Background Image (1550x1450px) </label>
                            <div class="flex items-center justify-center w-full relative">
								<?php if(isset($popupbgimg) && !empty($popupbgimg)){ ?>
									<button class="text-white font-bold rounded absolute top-1 right-1 removeImg" data-id="popupbgimg" >
										<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
										</svg>
									</button>
								<?php } ?>
                                <label class="flex flex-col w-full h-32 border-4 border-dashed hover:bg-gray-100 hover:border-gray-300">
								<?php if(!empty($popupbgimg)){
										$imgpreview = "visible";
										$hideafterimgprevw = "hidden";
									}else{
										$imgpreview = "hidden";
										$hideafterimgprevw = "visible";
									} ?>
									<div class="flex flex-col items-center justify-center overflow-auto imgpreview <?php echo esc_html($imgpreview); ?>">
										<img class="w-full h-full text-gray-400 group-hover:text-gray-600"  src="<?php echo esc_html($popupbgimg); ?>" />
									</div> 
                                    <div class="flex flex-col items-center justify-center pt-7 hideafterimgprevw <?php echo esc_html($hideafterimgprevw); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-400 group-hover:text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <p class="pt-1 text-sm tracking-wider text-gray-400 group-hover:text-gray-600">
                                            Select a photo</p>
                                    </div>  
                                    <input type="file" name="popupbgimg" enctype="multipart/form-data" class="opacity-0 onFileChange" />
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-center mt-8">
                    <div class="rounded-lg shadow-xl bg-gray-50 w-1/3 mx-1">
                        <div class="m-4">
                            <label class="inline-block mb-2 text-gray-500">Left image (450x600px)</label>
                            <div class="flex items-center justify-center w-full relative">
								<?php if(isset($leftimage) && !empty($leftimage)){ ?>
									<button class="text-white font-bold rounded absolute top-1 right-1 removeImg" data-id="leftimage">
										<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
										</svg>
									</button>
								<?php } ?>
                                <label class="flex flex-col w-full h-32 border-4 border-dashed hover:bg-gray-100 hover:border-gray-300">
								<?php if(!empty($leftimage)){
										$imgpreview = "visible";
										$hideafterimgprevw = "hidden";
									}else{
										$imgpreview = "hidden";
										$hideafterimgprevw = "visible";
									} ?>
									<div class="flex flex-col items-center justify-center overflow-auto imgpreview <?php echo esc_html($imgpreview); ?>">
										<img class="w-full h-full text-gray-400 group-hover:text-gray-600"  src="<?php echo esc_html($leftimage); ?>" />
									</div> 
                                    <div class="flex flex-col items-center justify-center pt-7 hideafterimgprevw <?php echo esc_html($hideafterimgprevw); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="w-12 h-12 text-gray-400 group-hover:text-gray-600" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <p class="pt-1 text-sm tracking-wider text-gray-400 group-hover:text-gray-600">
                                            Select a photo</p>
                                    </div> 
                                    <input type="file" name="leftimage" enctype="multipart/form-data" class="opacity-0 onFileChange" />
                                </label>
                            </div>
                        </div>
                        <div class="m-4">
                            <label class="inline-block mb-2 text-gray-500">Title</label>
                            <div class="flex items-center justify-center w-full">
                                <input type="text" value="<?php echo esc_html($leftimagetitle); ?>" name="leftimagetitle" class="border-gray-400 w-full" />
                            </div>
                        </div>
                    </div>
                    <div class="rounded-lg shadow-xl bg-gray-50 w-1/3 mx-1">
                        <div class="m-4">
                            <label class="inline-block mb-2 text-gray-500">Center image (450x600px)</label>
                            <div class="flex items-center justify-center w-full relative">
								<?php if(isset($centerimage) && !empty($centerimage)){ ?>
                                <button class="text-white font-bold rounded absolute top-1 right-1 removeImg" data-id="centerimage">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
								<?php } ?>
                                <label class="flex flex-col w-full h-32 border-4 border-dashed hover:bg-gray-100 hover:border-gray-300">
								<?php if(!empty($centerimage)){
										$imgpreview = "visible";
										$hideafterimgprevw = "hidden";
									}else{
										$imgpreview = "hidden";
										$hideafterimgprevw = "visible";
									} ?>
									<div class="flex flex-col items-center justify-center overflow-auto imgpreview <?php echo esc_html($imgpreview); ?>">
										<img class="w-full h-full text-gray-400 group-hover:text-gray-600"  src="<?php echo esc_url($centerimage); ?>" />
									</div> 
                                    <div class="flex flex-col items-center justify-center pt-7 hideafterimgprevw <?php echo esc_html($hideafterimgprevw); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="w-12 h-12 text-gray-400 group-hover:text-gray-600" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <p class="pt-1 text-sm tracking-wider text-gray-400 group-hover:text-gray-600">
                                            Select a photo</p>
                                    </div> 
                                    <input type="file" name="centerimage" enctype="multipart/form-data" class="opacity-0 onFileChange" />
                                </label>
                            </div>
                        </div>
                        <div class="m-4">
                            <label class="inline-block mb-2 text-gray-500">Title</label>
                            <div class="flex items-center justify-center w-full">
                                <input type="text" value="<?php echo esc_html($centerimagetitle); ?>" name="centerimagetitle" class="border-gray-400 w-full" />
                            </div>
                        </div>
                    </div>
                    <div class="rounded-lg shadow-xl bg-gray-50 w-1/3 mx-1">
                        <div class="m-4">
                            <label class="inline-block mb-2 text-gray-500">Right image (450x600px)</label>
                            <div class="flex items-center justify-center w-full relative">
								<?php if(isset($rightimage) && !empty($rightimage)){ ?>
                                <button class="text-white font-bold rounded absolute top-1 right-1 removeImg" data-id="rightimage">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
								<?php } ?>
                                <label class="flex flex-col w-full h-32 border-4 border-dashed hover:bg-gray-100 hover:border-gray-300">
								<?php if(!empty($rightimage)){
										$imgpreview = "visible";
										$hideafterimgprevw = "hidden";
									}else{
										$imgpreview = "hidden";
										$hideafterimgprevw = "visible";
									} ?>
									<div class="flex flex-col items-center justify-center overflow-auto imgpreview <?php echo esc_html($imgpreview); ?>">
										<img class="w-full h-full text-gray-400 group-hover:text-gray-600"  src="<?php echo esc_url($rightimage); ?>" />
									</div> 
                                    <div class="flex flex-col items-center justify-center pt-7 hideafterimgprevw <?php echo esc_html($hideafterimgprevw); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="w-12 h-12 text-gray-400 group-hover:text-gray-600" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <p class="pt-1 text-sm tracking-wider text-gray-400 group-hover:text-gray-600">
                                            Select a photo</p>
                                    </div> 
                                    <input type="file" name="rightimage" enctype="multipart/form-data" class="opacity-0 onFileChange" />
                                </label>
                            </div>
                        </div>
                        <div class="m-4">
                            <label class="inline-block mb-2 text-gray-500">Title</label>
                            <div class="flex items-center justify-center w-full">
                                <input type="text" value="<?php echo esc_html($rightimagetitle); ?>" name="rightimagetitle" class="border-gray-400 w-full" />
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-center mt-8">
                    <div class="rounded-lg shadow-xl bg-gray-50 w-full mx-1">
                        <div class="m-4">
                            <label class="inline-block mb-2 text-gray-500">Term Text</label>
                            <div class="flex items-center justify-center w-full">
                                <input type="text" value="<?php echo esc_html($term); ?>" name="term" class="border-gray-400 w-full" />
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-center mt-8">
                    <div class="rounded-lg shadow-xl bg-gray-50 w-full mx-1">
                        <div class="m-4">
                            <label class="inline-block mb-2 text-gray-500">Copyright Text</label>
                            <div class="flex items-center justify-center w-full">
                                <input type="text" value="<?php echo esc_html($copyright); ?>" name="copyright" class="border-gray-400 w-full" />
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-center">
                    <div class="m-4">
                        <button type="submit" name="updatepopup" class="py-2 px-4 bg-green-500 text-white font-semibold rounded-lg shadow-md hover:bg-rose-700 focus:outline-none mr-2">Update Popup</button>     
                    </div>
                </div>
            </div>
        </form>
	</div>
</div>

<!-- Popup Modal Start -->
<div class="hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true" id="my-modal-popuppreview">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"> 
	<div id="route-info" class="lightbox-content route-modal" style="display: block;font-family: 'Titillium Web';">
		<div class="route-modal-box relative flex"> 
			<?php if(empty($popupbgimg)){
					$popupbgimg = plugin_dir_url( __DIR__ ) . 'images/popup-bg.png';
			} ?>
			<img class="background absolute flex items-center" src="<?php echo esc_html($popupbgimg); ?>">
			<div class="route-modal-header flex flex-row w-full h-full mt-10 z-10 items-center">
				<?php if(empty($ecomsurance_popup_logo)){
					$ecomsurance_popup_logo = plugin_dir_url( __DIR__ ) . 'images/insurify-logo.png';
				} ?>
				<img class="route-rm-route-logo ml-3" src="<?php echo esc_url($ecomsurance_popup_logo); ?>">
				<div class="route-rm-secure-with w-2/6  ml-3">
					<?php 
					if(!empty($title)){ 
						echo esc_html($title);
					}else{
						echo "Secure your shipment and easily
						resolve order issues with one tap.";
					} 
					?>
				</div>
				<a href="#" id="insurify-details-popup-close"><img class="route-rm-close-modal lup-close" id="lup_close_btn" src="<?php echo esc_url(plugin_dir_url( __DIR__ ) . 'images/CloseIcon.svg'); ?>"></a>
			</div>
			<div class="route-modal-content flex w-full items-center mt-5">
				<div class="icon-box1 ml-5">
					<span class="route-rm-icon-box1">
					<?php if(empty($leftimage)){
						$leftimage = plugin_dir_url( __DIR__ ) . 'images/step-1.png';
					} ?>		
					<img class="route-rm-icon-box-image" src="<?php echo esc_html($leftimage); ?>" alt="Secured your shipment"></span>
					<div class="route-rm-text1"><?php 
					if(!empty($leftimagetitle)){ 
						echo esc_html($leftimagetitle);
					}else{
						echo "Protect orders from loss,<br/>
						damage, or theft. Resolve<br/>
						your issues without any hassle.";
					} 
					?></div>
				</div>
				<div class="icon-box2">
					<span class="route-rm-icon-box2">
					<?php if(empty($centerimage)){
						$centerimage = plugin_dir_url( __DIR__ ) . 'images/step-2.png';
					} ?>	
					<img class="route-rm-icon-box-image" src="<?php echo esc_url($centerimage); ?>" alt="Instantly insured"></span>
					<div class="route-rm-text2"><?php 
					if(!empty($centerimagetitle)){ 
						echo esc_html($centerimagetitle);
					}else{
						echo "No Hassle claim issues, Get<br/>
						refund or new item shipped<br/>
						within 24 hours. Fast and Easy.";
					} 
					?></div>
				</div>
				<div class="icon-box3 mr-5">
					<?php if(empty($rightimage)){
						$rightimage = plugin_dir_url( __DIR__ ) . 'images/step-3.png';
					} ?>	
					<span class="route-rm-icon-box3"><img class="route-rm-icon-box-image" src="<?php echo esc_url($rightimage); ?>"  alt="One click claims"></span>
					<div class="route-rm-text3"><?php 
					if(!empty($rightimagetitle)){ 
						echo esc_html($rightimagetitle);
					}else{
						echo "Refunds or reorders<br/>
						in just a few clicks.";
					} 
					?></div>
				</div>
			</div>
			<div class="route-modal-footer flex w-full items-center">
				<div class="rm-footer-1">
					<div class="route-rm-terms"><?php 
					if(!empty($term)){ 
						echo esc_html($term);
					}else{
						echo "This optional protection is offered to you solely in order to effectuate the shipment of your package(s).";
					} 
					?></div>
				</div>
				<div class="rm-footer-2">
					<div class="route-rm-copyright"><?php 
					if(!empty($copyright)){ 
						echo esc_html($copyright);
					}else{
						echo "© eComSurance - undefined - All Right Reserved";
					} 
					?></div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Popup Modal End --> 