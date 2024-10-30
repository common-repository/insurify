<?php
defined( 'ABSPATH' ) || exit;

    if ( ! session_id() ) {
        session_start();
    }
    $carttotal = WC()->cart->cart_contents_total; 

    $protection_product_price = $insurifyappstatuscount = $togglebtndefaultcount = 0;

    $app_status = get_option("insurify_app_status");
    $protection_toggle_default = get_option("insurify_protection_toggle_default");
     
 
    if($app_status > 0 && ( (isset($_SESSION["toggle_btn_status"]) && sanitize_text_field($_SESSION["toggle_btn_status"]) == "true") || $protection_toggle_default == 1) ){ 
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'product_cat'    => 'protection',
            'meta_key' => 'ec_insurance_plan', 
            'meta_value' => 'protection',
        );
        
        $loop = new WP_Query( $args );
        if(!empty($loop) && !empty(WC()->cart->get_cart())){   
            while ( $loop->have_posts() ) : $loop->the_post();
                global $product;
                $cart_minimum_amount = get_post_meta($product->get_id(), "cart_minimum_amount", true);
                $cart_maximum_amount = get_post_meta($product->get_id(), "cart_maximum_amount", true);
                
                foreach( WC()->cart->get_cart() as $cart_item ){  
                    $product_ids[] = $cart_item['product_id']; 
                    if ( has_term( 'protection', 'product_cat', $cart_item['product_id']) )
                    {   
                        $cat_in_cart["product_id"] = $cart_item['product_id'];
                        $cat_in_cart["key"] = $cart_item['key'];
                        $carttotal = abs($carttotal - $cart_item["line_subtotal"]);
                        break;
                    }
                } 

                if($carttotal >= $cart_minimum_amount  && $carttotal <= $cart_maximum_amount){ 
                    $plan_exist = true;
                    $protection_product_price = $product->get_regular_price();
                        
                    if( !empty($product_ids) && !in_array($product->get_id(), $product_ids)){ 

                        if(isset($cat_in_cart) && !empty($cat_in_cart)){
                            WC()->cart->remove_cart_item($cat_in_cart["key"]);
                        }
                            
                        WC()->cart->add_to_cart($product->get_id() ); 
                        break;
                    }elseif(isset($cat_in_cart) && !empty($cat_in_cart) && count($product_ids) == 1){ 
                        WC()->cart->remove_cart_item($cat_in_cart["key"]);
                    }
                }else{
                    if( (isset($cat_in_cart) && !empty($cat_in_cart)) && (isset($plan_exist) && $plan_exist != true)){ 
                        WC()->cart->remove_cart_item($cat_in_cart["key"]);
                    }
                }
            endwhile;
            wp_reset_query();
        }else{
            echo "<br>";
            echo "<p>No Insurance plan added by admin yet.</p>";
        }
        
    }

    if( (isset($_SESSION["toggle_btn_status"]) && sanitize_text_field($_SESSION["toggle_btn_status"]) == "false") || $app_status == 0){ 
        foreach( WC()->cart->get_cart() as $cart_item ){  
            $product_ids[] = $cart_item['product_id'];
            if ( has_term( 'protection', 'product_cat', $cart_item['product_id']) )
            {  
                $cat_in_cart["product_id"] = $cart_item['product_id'];
                $cat_in_cart["key"] = $cart_item['key'];
                $carttotal = $carttotal - $cart_item["line_subtotal"];
                break;
            }
        } 
        if(isset($cat_in_cart)){ 
            WC()->cart->remove_cart_item($cat_in_cart["key"]);
        }
        
    }

    if( (!isset($_SESSION["toggle_btn_status"]) && $protection_toggle_default > 0) || (isset($_SESSION["toggle_btn_status"]) && sanitize_text_field($_SESSION["toggle_btn_status"]) == "true")){ 
        $checked = "checked";
    }else{ 
        $checked = "";
    }

    if( $protection_toggle_default == 0 )
    { 
        $plan_exist = true; 
    } 
?>

<?php 
if($app_status > 0 && (isset($plan_exist) && $plan_exist == true)){ 
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
    $toggle_type = get_option("insurify_toggle_type");
    $protection_title = get_option("insurify_protection_title");
    $protection_subtitle = get_option("insurify_protection_subtitle"); 
    $protection_description = get_option("insurify_protection_description");   
    $toggle_inline_css = get_option("insurify_toggle_inline_css");  
?>
<style>
    <?php echo esc_html($toggle_inline_css);?>
</style>
<div id="shipping-insurance-container" class="shipping-insurance-container checkout_toggle">
        <div class="protection-plan-logo">
            <img src="<?php echo esc_url( plugins_url( 'images/vuexy-logo.png', dirname(__DIR__) ) ); ?>" width="50" alt="">
        </div>
        <div class="protection-plan-details">
            <label for="shipping-insurance-check" class="protection-plan-title"><?php  echo esc_html($protection_title); ?></label>
            <p class="protection-plan-subtitle">
                <span><?php  echo esc_html($protection_subtitle); ?></span>
                <span id="insurify-protection-amount"><?php  echo esc_html($protection_product_price); ?></span> <span id="insurify-store-currency">USD</span>
            </p>
            <p class="protection-plan-subtitle"><?php  echo esc_html($protection_description); ?></p>
            <p><a href="javascript:void(0)" id="insurify-details-popup-link">View details</a></p>
        </div>
        <div class="protection-plan-toggle">
            <input type="checkbox" class="switch_<?php echo esc_html($toggle_type); ?>_checkbox_input" name="shipping-insurance-check" id="shipping-insurance-check" <?php echo esc_html($checked); ?>>
        </div>
        
    <div id="insurify-details-popup" class="lup-modal hide">
        <div class="lup-modal-content" id="lup-modal-content">
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
            <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"> 
            <div id="route-info" class="lightbox-content route-modal" style="display: block;font-family: 'Titillium Web';">
                <div class="route-modal-box relative flex"> 
                    <?php if(empty($popupbgimg)){
                            $popupbgimg = plugins_url( 'images/popup-bg.png', dirname(__DIR__) );
                    } ?> 
                    <img class="background absolute flex items-center" src="<?php echo esc_url($popupbgimg); ?>">
                    <div class="route-modal-header flex flex-row w-full h-full mt-10 z-10 items-center">
                        <?php if(empty($ecomsurance_popup_logo)){
                            $ecomsurance_popup_logo = plugins_url( 'images/insurify-logo.png', dirname(__DIR__) );
                        } ?>
                        <img class="route-rm-route-logo ml-3" src="<?php echo esc_url($ecomsurance_popup_logo); ?>">
                        <div class="route-rm-secure-with w-2/6  ml-3">
                            <?php 
                            if(!empty($title)){ 
                                echo esc_html($title);
                            }else{
                                echo "Secure your shipment and easily<br/>
                                resolve order issues with one tap.";
                            } 
                            ?>
                        </div>
                        <img class="route-rm-close-modal lup-close" id="lup_close_btn" src="<?php echo esc_url(plugins_url( 'images/CloseIcon.svg', dirname(__DIR__) )); ?>"> 
                    </div>
                    <div class="route-modal-content flex w-full items-center mt-5">
                        <div class="icon-box1 ml-5">
                            <span class="route-rm-icon-box1">
                            <?php if(empty($leftimage)){
                                $leftimage = plugins_url( 'images/step-1.png', dirname(__DIR__) );
                            } ?>		
                            <img class="route-rm-icon-box-image" src="<?php echo esc_url($leftimage); ?>" alt="Secured your shipment"></span>
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
                                $centerimage = plugins_url( 'images/step-2.png', dirname(__DIR__) );
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
                                $rightimage = plugins_url( 'images/step-3.png', dirname(__DIR__) );
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
    </div>

</div>
<?php } ?>