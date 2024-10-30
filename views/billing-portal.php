<?php 
defined( 'ABSPATH' ) || exit;
global $current_user; 

$id_token = get_user_meta( get_current_user_id(), 'auth_insurify_id_token', true); 
$api_url = ECOMSURANCE_API_URL.'/api/createsetupintent';
$data["domain"] = get_site_url(); 
$data["email"] = $current_user->user_email; 

$apiResponse = wp_remote_post( $api_url, array(
	'body'    => $data,
	'headers' => array(
		'Authorization' => 'Bearer '.$id_token,
	),
) );
$apiBody = json_decode( wp_remote_retrieve_body( $apiResponse ) ); 

$getsavingcardsapi = wp_remote_post( ECOMSURANCE_API_URL.'/api/getsavingcards', array(
	'body'    => $data,
	'headers' => array(
		'Authorization' => 'Bearer '.$id_token,
	),
) );
$apiBody_getsavingcards = json_decode( wp_remote_retrieve_body( $getsavingcardsapi )); 
// echo "<pre>";print_r($apiBody_getsavingcards);echo "</pre>";die();

$api_urlgetsubscriptiondetails = ECOMSURANCE_API_URL.'/api/getsubscriptiondetails'; 

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
			<h2 class="font-semibold text-xl text-gray-800 leading-tight"> Payment Methods </h2>
		</div>
	</header>

	<div class="py-4">
		<div class="container flex-wrap pb-6 m-auto grid grid-cols-2">
			<div class="absolute inset-0 spinner-insurify hidden bg-gray-400 opacity-20">
				<div class="relative mx-auto top-1/4 animate-spin rounded-full h-32 w-32 border-b-4 border-gray-900" ></div>
			</div>
			<div class="max-w-md mx-auto  text-center bg-white rounded mt-5">
				<div class="cell example strelement p-5"> 
					<form id="payment-form">
						<div class="title mb-4">
							<h2 class="font-bold text-left text-2xl font-extrabold text-gray-600 tracking-tight dark:text-gray-200">Billing Information</h2>
						</div>
						<input id="card-holder-name" type="text"  class="w-full mb-6 h-10" placeholder="Card Holder Name" required>

						<div id="card-element" style="padding-top: .7em;border: 1px solid darkgray;border-radius: 4px;padding-left: 5px;">

						</div>  

						<div class="form mt-4">
							<div class="flex flex-col text-sm"> 
								<input class="appearance-none border border-gray-200 p-2 mb-6 h-10 focus:outline-none focus:border-gray-500 line1" type="text" placeholder="Address line 1" required> 
							</div>

							<div class="text-sm flex flex-col"> 
								<input class="appearance-none border border-gray-200 p-2 mb-6 h-10 focus:outline-none focus:border-gray-500 line2" type="text" placeholder="Address line 2"> 
							</div>

							<div class="text-sm grid grid-cols-2 gap-4"> 
								<select class="appearance-none border border-gray-200 p-2 mb-6 h-10 focus:outline-none focus:border-gray-500 country" type="text" required>
									<option value="" disabled selected>Country</option> 
									<option value="AF">Afghanistan</option>
									<option value="AX">Aland Islands</option>
									<option value="AL">Albania</option>
									<option value="DZ">Algeria</option>
									<option value="AS">American Samoa</option>
									<option value="AD">Andorra</option>
									<option value="AO">Angola</option>
									<option value="AI">Anguilla</option>
									<option value="AQ">Antarctica</option>
									<option value="AG">Antigua and Barbuda</option>
									<option value="AR">Argentina</option>
									<option value="AM">Armenia</option>
									<option value="AW">Aruba</option>
									<option value="AU">Australia</option>
									<option value="AT">Austria</option>
									<option value="AZ">Azerbaijan</option>
									<option value="BS">Bahamas</option>
									<option value="BH">Bahrain</option>
									<option value="BD">Bangladesh</option>
									<option value="BB">Barbados</option>
									<option value="BY">Belarus</option>
									<option value="BE">Belgium</option>
									<option value="BZ">Belize</option>
									<option value="BJ">Benin</option>
									<option value="BM">Bermuda</option>
									<option value="BT">Bhutan</option>
									<option value="BO">Bolivia</option>
									<option value="BQ">Bonaire, Sint Eustatius and Saba</option>
									<option value="BA">Bosnia and Herzegovina</option>
									<option value="BW">Botswana</option>
									<option value="BV">Bouvet Island</option>
									<option value="BR">Brazil</option>
									<option value="IO">British Indian Ocean Territory</option>
									<option value="BN">Brunei Darussalam</option>
									<option value="BG">Bulgaria</option>
									<option value="BF">Burkina Faso</option>
									<option value="BI">Burundi</option>
									<option value="KH">Cambodia</option>
									<option value="CM">Cameroon</option>
									<option value="CA">Canada</option>
									<option value="CV">Cape Verde</option>
									<option value="KY">Cayman Islands</option>
									<option value="CF">Central African Republic</option>
									<option value="TD">Chad</option>
									<option value="CL">Chile</option>
									<option value="CN">China</option>
									<option value="CX">Christmas Island</option>
									<option value="CC">Cocos (Keeling) Islands</option>
									<option value="CO">Colombia</option>
									<option value="KM">Comoros</option>
									<option value="CG">Congo</option>
									<option value="CD">Congo, Democratic Republic of the Congo</option>
									<option value="CK">Cook Islands</option>
									<option value="CR">Costa Rica</option>
									<option value="CI">Cote D'Ivoire</option>
									<option value="HR">Croatia</option>
									<option value="CU">Cuba</option>
									<option value="CW">Curacao</option>
									<option value="CY">Cyprus</option>
									<option value="CZ">Czech Republic</option>
									<option value="DK">Denmark</option>
									<option value="DJ">Djibouti</option>
									<option value="DM">Dominica</option>
									<option value="DO">Dominican Republic</option>
									<option value="EC">Ecuador</option>
									<option value="EG">Egypt</option>
									<option value="SV">El Salvador</option>
									<option value="GQ">Equatorial Guinea</option>
									<option value="ER">Eritrea</option>
									<option value="EE">Estonia</option>
									<option value="ET">Ethiopia</option>
									<option value="FK">Falkland Islands (Malvinas)</option>
									<option value="FO">Faroe Islands</option>
									<option value="FJ">Fiji</option>
									<option value="FI">Finland</option>
									<option value="FR">France</option>
									<option value="GF">French Guiana</option>
									<option value="PF">French Polynesia</option>
									<option value="TF">French Southern Territories</option>
									<option value="GA">Gabon</option>
									<option value="GM">Gambia</option>
									<option value="GE">Georgia</option>
									<option value="DE">Germany</option>
									<option value="GH">Ghana</option>
									<option value="GI">Gibraltar</option>
									<option value="GR">Greece</option>
									<option value="GL">Greenland</option>
									<option value="GD">Grenada</option>
									<option value="GP">Guadeloupe</option>
									<option value="GU">Guam</option>
									<option value="GT">Guatemala</option>
									<option value="GG">Guernsey</option>
									<option value="GN">Guinea</option>
									<option value="GW">Guinea-Bissau</option>
									<option value="GY">Guyana</option>
									<option value="HT">Haiti</option>
									<option value="HM">Heard Island and Mcdonald Islands</option>
									<option value="VA">Holy See (Vatican City State)</option>
									<option value="HN">Honduras</option>
									<option value="HK">Hong Kong</option>
									<option value="HU">Hungary</option>
									<option value="IS">Iceland</option>
									<option value="IN">India</option>
									<option value="ID">Indonesia</option>
									<option value="IR">Iran, Islamic Republic of</option>
									<option value="IQ">Iraq</option>
									<option value="IE">Ireland</option>
									<option value="IM">Isle of Man</option>
									<option value="IL">Israel</option>
									<option value="IT">Italy</option>
									<option value="JM">Jamaica</option>
									<option value="JP">Japan</option>
									<option value="JE">Jersey</option>
									<option value="JO">Jordan</option>
									<option value="KZ">Kazakhstan</option>
									<option value="KE">Kenya</option>
									<option value="KI">Kiribati</option>
									<option value="KP">Korea, Democratic People's Republic of</option>
									<option value="KR">Korea, Republic of</option>
									<option value="XK">Kosovo</option>
									<option value="KW">Kuwait</option>
									<option value="KG">Kyrgyzstan</option>
									<option value="LA">Lao People's Democratic Republic</option>
									<option value="LV">Latvia</option>
									<option value="LB">Lebanon</option>
									<option value="LS">Lesotho</option>
									<option value="LR">Liberia</option>
									<option value="LY">Libyan Arab Jamahiriya</option>
									<option value="LI">Liechtenstein</option>
									<option value="LT">Lithuania</option>
									<option value="LU">Luxembourg</option>
									<option value="MO">Macao</option>
									<option value="MK">Macedonia, the Former Yugoslav Republic of</option>
									<option value="MG">Madagascar</option>
									<option value="MW">Malawi</option>
									<option value="MY">Malaysia</option>
									<option value="MV">Maldives</option>
									<option value="ML">Mali</option>
									<option value="MT">Malta</option>
									<option value="MH">Marshall Islands</option>
									<option value="MQ">Martinique</option>
									<option value="MR">Mauritania</option>
									<option value="MU">Mauritius</option>
									<option value="YT">Mayotte</option>
									<option value="MX">Mexico</option>
									<option value="FM">Micronesia, Federated States of</option>
									<option value="MD">Moldova, Republic of</option>
									<option value="MC">Monaco</option>
									<option value="MN">Mongolia</option>
									<option value="ME">Montenegro</option>
									<option value="MS">Montserrat</option>
									<option value="MA">Morocco</option>
									<option value="MZ">Mozambique</option>
									<option value="MM">Myanmar</option>
									<option value="NA">Namibia</option>
									<option value="NR">Nauru</option>
									<option value="NP">Nepal</option>
									<option value="NL">Netherlands</option>
									<option value="AN">Netherlands Antilles</option>
									<option value="NC">New Caledonia</option>
									<option value="NZ">New Zealand</option>
									<option value="NI">Nicaragua</option>
									<option value="NE">Niger</option>
									<option value="NG">Nigeria</option>
									<option value="NU">Niue</option>
									<option value="NF">Norfolk Island</option>
									<option value="MP">Northern Mariana Islands</option>
									<option value="NO">Norway</option>
									<option value="OM">Oman</option>
									<option value="PK">Pakistan</option>
									<option value="PW">Palau</option>
									<option value="PS">Palestinian Territory, Occupied</option>
									<option value="PA">Panama</option>
									<option value="PG">Papua New Guinea</option>
									<option value="PY">Paraguay</option>
									<option value="PE">Peru</option>
									<option value="PH">Philippines</option>
									<option value="PN">Pitcairn</option>
									<option value="PL">Poland</option>
									<option value="PT">Portugal</option>
									<option value="PR">Puerto Rico</option>
									<option value="QA">Qatar</option>
									<option value="RE">Reunion</option>
									<option value="RO">Romania</option>
									<option value="RU">Russian Federation</option>
									<option value="RW">Rwanda</option>
									<option value="BL">Saint Barthelemy</option>
									<option value="SH">Saint Helena</option>
									<option value="KN">Saint Kitts and Nevis</option>
									<option value="LC">Saint Lucia</option>
									<option value="MF">Saint Martin</option>
									<option value="PM">Saint Pierre and Miquelon</option>
									<option value="VC">Saint Vincent and the Grenadines</option>
									<option value="WS">Samoa</option>
									<option value="SM">San Marino</option>
									<option value="ST">Sao Tome and Principe</option>
									<option value="SA">Saudi Arabia</option>
									<option value="SN">Senegal</option>
									<option value="RS">Serbia</option>
									<option value="CS">Serbia and Montenegro</option>
									<option value="SC">Seychelles</option>
									<option value="SL">Sierra Leone</option>
									<option value="SG">Singapore</option>
									<option value="SX">Sint Maarten</option>
									<option value="SK">Slovakia</option>
									<option value="SI">Slovenia</option>
									<option value="SB">Solomon Islands</option>
									<option value="SO">Somalia</option>
									<option value="ZA">South Africa</option>
									<option value="GS">South Georgia and the South Sandwich Islands</option>
									<option value="SS">South Sudan</option>
									<option value="ES">Spain</option>
									<option value="LK">Sri Lanka</option>
									<option value="SD">Sudan</option>
									<option value="SR">Suriname</option>
									<option value="SJ">Svalbard and Jan Mayen</option>
									<option value="SZ">Swaziland</option>
									<option value="SE">Sweden</option>
									<option value="CH">Switzerland</option>
									<option value="SY">Syrian Arab Republic</option>
									<option value="TW">Taiwan, Province of China</option>
									<option value="TJ">Tajikistan</option>
									<option value="TZ">Tanzania, United Republic of</option>
									<option value="TH">Thailand</option>
									<option value="TL">Timor-Leste</option>
									<option value="TG">Togo</option>
									<option value="TK">Tokelau</option>
									<option value="TO">Tonga</option>
									<option value="TT">Trinidad and Tobago</option>
									<option value="TN">Tunisia</option>
									<option value="TR">Turkey</option>
									<option value="TM">Turkmenistan</option>
									<option value="TC">Turks and Caicos Islands</option>
									<option value="TV">Tuvalu</option>
									<option value="UG">Uganda</option>
									<option value="UA">Ukraine</option>
									<option value="AE">United Arab Emirates</option>
									<option value="GB">United Kingdom</option>
									<option value="US">United States</option>
									<option value="UM">United States Minor Outlying Islands</option>
									<option value="UY">Uruguay</option>
									<option value="UZ">Uzbekistan</option>
									<option value="VU">Vanuatu</option>
									<option value="VE">Venezuela</option>
									<option value="VN">Viet Nam</option>
									<option value="VG">Virgin Islands, British</option>
									<option value="VI">Virgin Islands, U.s.</option>
									<option value="WF">Wallis and Futuna</option>
									<option value="EH">Western Sahara</option>
									<option value="YE">Yemen</option>
									<option value="ZM">Zambia</option>
									<option value="ZW">Zimbabwe</option>
								</select>
								<input class="appearance-none border border-gray-200 p-2 mb-6 h-10 focus:outline-none focus:border-gray-500 city" type="text" placeholder="City" required> 
							</div>

							<div class="text-sm grid grid-cols-1 gap-4">  
								<input class="appearance-none border border-gray-200 p-2 mb-6 h-10 focus:outline-none focus:border-gray-500 state" type="text" placeholder="State/Province" required>  
							</div>
						</div>  

						<button type="submit" class="bg-green-400 mt-3" id="submit">
							Save Payment Method
						</button>  
						<div id="error-message">
							<!-- Display error message to your customers here -->
						</div>
					</form>
				</div>
			</div> 
			
			<?php if(empty($apiBody_getsavingcards)){ ?> 
				<div class="max-w-xl mx-auto  text-center bg-white rounded  mt-5 p-5 title mb-4">
					<h2 class="font-bold text-left text-2xl font-extrabold text-gray-600 tracking-tight dark:text-gray-200">No payment method Added, please add a payment method.</h2>
				</div> 
			<?php }else{ ?>
				<div class="max-w-xl mx-auto  text-center bg-white rounded  mt-5 p-5">
					<div class="md:col-span-1">
						<div class="px-4 sm:px-0">
							<div class="title mb-4">
								<h2 class="font-bold text-left text-2xl font-extrabold text-gray-600 tracking-tight dark:text-gray-200">Added Payment Methods</h2>
							</div> 
						</div>
					</div>
					<?php 
					foreach($apiBody_getsavingcards as $key => $method){ ?>
						<div class="grid grid-cols-6 border p-5 mb-2">
								<div class="col-span-1">
									<?php echo esc_html($method->brand); ?>
								</div>
								<div class="col-span-3">
									Ending In: <?php echo esc_html($method->last_four); ?> Exp: <?php echo esc_html($method->exp_month); ?> / <?php echo esc_html($method->exp_year); ?>
								</div>
								<?php if($method->method_type != "default"){ ?>
									<div class="col-span-2 grid grid-cols-4 gap-1">
										<span class="col-span-3 cursor-pointer inline-flex text-indigo-900 focus:shadow-outline bg-blue-600 text-white px-4 py-1 font-bold rounded" onclick="primaryPaymentMethod('<?php echo esc_attr($method->id); ?>')">Make Primary</span>
										<span class="col-span-1 cursor-pointer h-6 inline-flex text-indigo-100 rounded-full focus:shadow-outline justify-end" onclick="removePaymentMethod('<?php echo esc_attr($method->id); ?>')">
											<svg viewBox="0 0 512 512"><path fill="#E04F5F" d="M504.1,256C504.1,119,393,7.9,256,7.9C119,7.9,7.9,119,7.9,256C7.9,393,119,504.1,256,504.1C393,504.1,504.1,393,504.1,256z"></path><path fill="#FFF" d="M285,256l72.5-84.2c7.9-9.2,6.9-23-2.3-31c-9.2-7.9-23-6.9-30.9,2.3L256,222.4l-68.2-79.2c-7.9-9.2-21.8-10.2-31-2.3c-9.2,7.9-10.2,21.8-2.3,31L227,256l-72.5,84.2c-7.9,9.2-6.9,23,2.3,31c4.1,3.6,9.2,5.3,14.3,5.3c6.2,0,12.3-2.6,16.6-7.6l68.2-79.2l68.2,79.2c4.3,5,10.5,7.6,16.6,7.6c5.1,0,10.2-1.7,14.3-5.3c9.2-7.9,10.2-21.8,2.3-31L285,256z"></path></svg>
										</span>
									</div>
								<?php } else {?>
									<div class="col-span-2  w-20 bg-red-600 text-white px-4 py-1 font-bold rounded ml-2">
										Primary
									</div>
								<?php }?>
						</div>
					<?php } ?>
				</div>
			<?php } ?>
		</div>
	</div>  
	<div class="container flex flex-wrap pb-6 m-auto mt-5">
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

			<div class="bg-white my-6 py-6 px-4 rounded">
				<h3 class="text-2xl">Usage</h3>
				<hr class="my-2">
				<p>Total usage for this billing period: <?php echo esc_attr($apiBodygetsubscriptiondetails->totalUsage); ?> </p>
				<p>Estimated billing amount: <?php echo esc_attr($apiBodygetsubscriptiondetails->totalUsage) * esc_attr($apiBodygetsubscriptiondetails->plan->unit_amount); ?> </p>
			</div>
			
			<div  class="flex flex-wrap items-center justify-center py-4 pt-0"> 
				<div class="w-full p-4 md:w-1/2 lg:w-1/4">
					<label
						class="flex flex-col rounded-lg shadow-lg relative cursor-pointer hover:shadow-2xl">
						<div
							class="w-full px-4 py-8 rounded-t-lg border-blue-500 bg-white hover:bg-blue-50 text-blue-600">
							<h3 class="mx-auto text-base font-semibold text-center underline">
								<?php echo esc_html($apiBodygetsubscriptiondetails->plan->product->name); ?> 
							</h3>
							<p class="text-5xl font-bold text-center my-2">
								$<?php echo esc_attr($apiBodygetsubscriptiondetails->plan->unit_amount)/100; ?>   
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

<style>
	#card-element iframe{
		height: 30px !important;
	}
</style>
<script>

	const headers = {
		'Content-Type': 'application/json;charset=UTF-8;*/*',
		"Access-Control-Allow-Origin": "*",
		'Authorization': 'Bearer <?php echo esc_attr($id_token); ?>'
	}

	const stripe = Stripe('<?php echo esc_html(ECOMSURANCE_STRIPE_KEY); ?>');
 
	const elements = stripe.elements(); 
	var cardElement = elements.create('card', { style:
		{
			base: {
			fontSize: '16px',
			lineHeight: '1.429',
			border: '1px solid gray'
			}
		}, 

	}); 
	cardElement.mount('#card-element'); 

	const form = document.getElementById('payment-form');
	form.addEventListener('submit', async (event) => {
		event.preventDefault();

		jQuery(".spinner-insurify").show();
		 
		stripe.confirmCardSetup("<?php echo esc_html($apiBody->intent->client_secret); ?>", {
			payment_method:{
				card: cardElement,
				billing_details: {
					name: jQuery("#card-holder-name").val(),
					address: {
						"city":jQuery(".city").val(), 
						"country": jQuery(".country").find(":selected").val(),
						"state": jQuery(".state").val(),
						"line1": jQuery(".line1").val(),
						"line2": jQuery(".line2").val(),
					}
				}
			}
		}).then(function(result) { 
			const messageContainer = document.querySelector('#error-message');
			if (result.error) {  
				messageContainer.textContent = result.error.message;
				jQuery(".spinner-insurify").hide();
			} else {
				axios.post('<?php echo esc_url(ECOMSURANCE_API_URL); ?>/api/savepaymentmethod', {
					headers: {'Authorization':'Bearer <?php echo esc_attr($id_token) ?>'},
					payment_method: result.setupIntent.payment_method,
					domain: '<?php echo esc_attr($data["domain"]); ?>'
				}, { headers: headers }).then( function(response){ 
					messageContainer.textContent = "Payment Method Added.";
					location.reload();
				}); 
			}	 
		}); 
	});

	/* Make Method Primary */
	function primaryPaymentMethod(paymentID){
		
		jQuery(".spinner-insurify").show();
		axios.post('<?php echo esc_url(ECOMSURANCE_API_URL); ?>/api/make-primary', {
				id: paymentID,
				domain: '<?php echo $data["domain"]; ?>'
			}, { headers: headers }
		).then( function( response ){
			location.reload();
		});
	}

	/* Remove payment method */
	function removePaymentMethod(paymentID){
		
		jQuery(".spinner-insurify").show();
		axios.post('<?php echo esc_url(ECOMSURANCE_API_URL); ?>/api/remove-payment', {
				id: paymentID,
				domain: '<?php echo esc_url($data["domain"]); ?>'
			}, { headers: headers }
		).then( function( response ){
			location.reload();
		});
	}
</script>