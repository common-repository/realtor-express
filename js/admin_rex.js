/**
 * @file
 * General admin scripts for Realtor Express.
 */
(function ($) {
  
// Reload page when closing thickbox in order to refresh gallery.
$(document).bind('tb_unload', function() {
	if (pagenow == 'rex_listing') {
		var url = '../wp-content/plugins/realtor-express/includes/RexGalleryServer.php';
		var wpnonce = $('#listing-ajax-validation').val();
		var post_id = $('#post_ID').val();
		var data = {'postID': post_id, 'wpnonce': wpnonce};
		
		var ajaxLoader = '<img src="../wp-content/plugins/realtor-express/images/ajax-loader.gif" />';
		$('#rex-listing-gallery').html(ajaxLoader);
		
		$('#rex-listing-gallery').load(url, data, updateGalleryText);
	}
});

function updateGalleryText(responseText, textStatus, XMLHttpRequest) {
	if (responseText == '') {
		$('#rex-listing-gallery').html($('#rex-no-gallery-images').html());
	}
}

$(document).ready(function() {
	// Return if we're not in a listing edit window.
	if ($("a#rex-media-uploader-link").length == 0) {
		return;
	}
	
	var address;
	var mediaUploaderTarget = $("a#content-add_media").attr("href");
	
	// Attache media uploader link to images field/link.
	$("a#rex-media-uploader-link").attr("href", mediaUploaderTarget);
	
	// Validate current address.
	address = $('#rex_address').val();
	if (address != "") {
		validateGmapAddress(address);
	}
	
	// Address Gmap validation on blur event.
	$('#rex_address').blur(function(eventObject) {
		address = $(this).val();
		validateGmapAddress(address);
	});
});

/**
 * Returns number of gmap addresses returned from Google.
 */
function validateGmapAddress(address) {
	// Ajax loader
	var ajaxLoader = '<img src="../wp-content/plugins/realtor-express/images/ajax-loader.gif" />';
	$('#rex-address-status').html(ajaxLoader);
	
	var address = address.replace(/(\r\n|\n|\r|[^a-zA-Z0-9])/gm, '+');
	address = address.replace(/\++/gm, '+');
	var queryStr = 'https://maps.googleapis.com/maps/api/geocode/json?address=' + address + '&sensor=false';
	var wpnonce = $('#listing-ajax-validation').val();
	
	results = $.ajax({
	  	url: '../wp-content/plugins/realtor-express/includes/RexGmapServer.php',
	  	type: 'POST',
	  	dataType: 'json',
	  	data: {'addressString': queryStr, 'wpnonce': wpnonce},
	  	success: function(data) {
	  		if (data == null) {
	  	        $('#rex-address-status').html($('#rex-request-failed').html()).css('color', 'red');
	  	        return;
	  	    }
	  		
	  		var resultCount = Object.keys(data.results).length;
	  		if (resultCount < 1) {
	  			$('#rex-address-status').html($('#rex-invalid-address').html()).css('color', 'red');
	  		}
	  		else if (resultCount > 1) {
	  			$('#rex-address-status').html($('#rex-multiple-addresses').html()).css('color', 'red');
	  		}
	  		else {
	  			$('#rex-address-status').html($('#rex-valid-address').html()).css('color', 'green');
	  			
	  			var lat = data.results[0].geometry.location.lat;
	  			var lng = data.results[0].geometry.location.lng;
	  			updateRexAdminGmap(lat, lng);
	  		}
	  	}
	});
}
  
}) (jQuery);