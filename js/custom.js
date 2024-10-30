jQuery(document).ready(function() {
    //set initial state. 


    jQuery('.switch__checkbox_input').change(function() {
        if(this.checked) { 
            jQuery(this).parent().find(".switch__label").text("Enabled"); 
        }else{
            jQuery(this).parent().find(".switch__label").text("Disabled"); 
        }
    }); 

    jQuery(document).on('change', '.onFileChange', function(e) {
        const file = e.target.files[0];
        const name = e.target.name;
        const filename = URL.createObjectURL(file);
         
        if (file.size > 1024 * 1024) {
            e.preventDefault();
            alert('File too big (> 1MB)');
            return;
        }
        
        if(file['type']!='image/jpeg' && file['type']!='image/png') {
            e.preventDefault();
            alert('File only be image');
            return;
        } 
        
        jQuery(this).parent().find(".imgpreview img").attr("src",filename); 
        jQuery(this).parent().find(".imgpreview").show();
        jQuery(this).parent().find(".hideafterimgprevw").hide();
    }); 

    jQuery(document).on('click', '.removeImg', function(e) {
        e.preventDefault();
		var removeimageid = jQuery(this).data("id"); 
		jQuery.ajax({
			url : admin_ecomsurance_ajax_js.ajaxurl,
			type : 'post',
			data : {
				action : 'removeimg_popup',
				name : removeimageid
			},
			success : function( response ) {
				location.reload();
			}
		}); 
    });

    /*  claim page start*/  
    jQuery(document).on('click', '.viewclaimmodal', function(e) {
        e.preventDefault();
        jQuery(".insert_data_here").empty("");
        var orderid = jQuery(this).attr('data-orderid');
        var openmodal = jQuery(this).attr('data-modal'); 
        jQuery("#"+openmodal).show();
        jQuery(".insert_data_here").html("<p>Content Loading please wait.......</p>");
        jQuery.ajax({
			url : admin_ecomsurance_ajax_js.ajaxurl,
			type : 'post',
			data : {
				action : 'getclaimstatus',
				orderid : orderid 
			},
			success : function( response ) {
                jQuery(".insert_data_here").empty("");
				jQuery(".insert_data_here").html(response);
			}
		}); 
    });

    jQuery(document).on('click', '.closeModal', function(e) {
        e.preventDefault();
        var openmodal = jQuery(this).attr('data-modal');
		jQuery("#"+openmodal).hide();
    }); 

    jQuery(document).on('click', '.openPopup', function(e) {
        e.preventDefault();
		jQuery("#my-modal-popuppreview").show();
        jQuery("input[name=claimId]").val(jQuery(this).attr('data-orderid'));
    });

    jQuery(document).on('click', '#lup_close_btn, .closeBtn', function(e) {
        e.preventDefault();
		jQuery("#my-modal-popuppreview").hide();
    });  

    jQuery(document).on('click', '#saveStatus', function(e) {
        var claimid = jQuery("input[name=claimId]").val();
        var updateStatusSelect = jQuery("select[name=updateStatusSelect]").val();
        var claimNotes = jQuery("textarea[name=claimNotes]").val();
        jQuery("#saveStatus").text("Saving.....");

        jQuery.ajax({
			url : admin_ecomsurance_ajax_js.ajaxurl,
			type : 'post',
			data : {
				action : 'updateClaimStatus',
				claimid : claimid,
				updateStatusSelect : updateStatusSelect,
				claimNotes : claimNotes
			},
			success : function( response ) {
                
                jQuery("#saveStatus").text("Reloading..");
				location.reload();
			}
		}); 
    });
    /* claim page end*/

});