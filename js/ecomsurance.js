jQuery(document).ready(function() { 

    jQuery(document).on('click', '#insurify-details-popup-link', function() {
        jQuery("#insurify-details-popup").show();
    });

    jQuery(document).on('click', '#lup_close_btn', function() {
        jQuery("#insurify-details-popup").hide();
    }); 
    /* Jquery For toggle button click load protection plans*/
    jQuery(document).on('change', '#shipping-insurance-check', function(e) {
        console.log("Chnage fired");
        e.preventDefault();
        var checkbox_checked = jQuery(this).is( ':checked' );
        jQuery.ajax({
            url : ecomsurance_ajax_js.ajaxurl,
            type : 'post',
            data : {
                action : 'toggle_btn_clicked_update_data',
                checkbox : checkbox_checked
            },
            success : function( response ) {
                location.reload();
            }
        });
    }); 
});