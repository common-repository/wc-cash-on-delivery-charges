function setCookie(cname,cvalue,exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires=" + d.toGMTString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i < ca.length; i++) {
            var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}


jQuery(document).ready(function() {

    wpcc_postcode = getCookie("wpcc_cashondelivery");
    jQuery("#billing_postcode").val(wpcc_postcode);



    jQuery("body").on('blur', '#billing_postcode', function() {
        
         if(jQuery('#ship-to-different-address-checkbox').prop('checked')){
        
                var pincode = jQuery('#shipping_postcode').val();
           }else{
        var pincode = jQuery(this).val();
       
    }
        if(pincode != '') {
            jQuery.ajax({
                type: "POST",
                url: wpcccod_ajax_postajax.ajaxurl,
                dataType: 'json',
                data: { 
                        action:"WPCCCOD_pincode_change_checkout",
                        pincode: pincode,
                     },
                success: function(response) {
                   // console.log(response);
                    jQuery("body").trigger("update_checkout");
                }
            });
        }
    
    });
      jQuery("body").on('blur', '#shipping_postcode', function() {
        var pincode = jQuery(this).val();

        if(pincode != '') {
            jQuery.ajax({
                type: "POST",
                url: wpcccod_ajax_postajax.ajaxurl,
                dataType: 'json',
                data: { 
                        action:"WPCCCOD_pincode_change_checkout",
                        pincode: pincode,
                     },
                success: function(response) {
                    jQuery("body").trigger("update_checkout");
                }
            });
        }

    });

jQuery("body").on('click', '#ship-to-different-address-checkbox', function() {
   if(jQuery(this).prop('checked')){
   
        var pincode = jQuery('#shipping_postcode').val();
   }else{
   
        var pincode = jQuery('#billing_postcode').val();
   }
        

        if(pincode != '') {
            jQuery.ajax({
                type: "POST",
                url: wpcccod_ajax_postajax.ajaxurl,
                dataType: 'json',
                data: { 
                        action:"WPCCCOD_pincode_change_checkout",
                        pincode: pincode,
                     },
                success: function(response) {
                    jQuery("body").trigger("update_checkout");
                }
            });
        }
  

    });
    
    jQuery("form.checkout").on('change', 'input[name=payment_method]', function() {
      
   if(jQuery('#ship-to-different-address-checkbox').prop('checked')){
   
        var pincode = jQuery('#shipping_postcode').val();
   }else{
   
        var pincode = jQuery('#billing_postcode').val();
   }
   
            jQuery.ajax({
                type: "POST",
                url: wpcccod_ajax_postajax.ajaxurl,
                dataType: 'json',
                data: { 
                        action:"WPCCCOD_pincode_change_checkout",
                        pincode: pincode,
                     },
                success: function(response) {
                     
                    jQuery("body").trigger("update_checkout");
                }
            });
    });
    
});


