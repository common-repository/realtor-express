/**
 * @file
 * General front-end scripts for Realtor Express.
 */
(function ($) {
	
$(document).ready(function() {
	// Reset filter form fields.
	$('#rex-listing-filter-reset').bind('click', function() {
		$('#rex-searchform :input').each(function() {
			var inputType = this.type;
			switch (inputType) {
				case 'text':
				case 'select-one':
					$(this).val('');
					break;
			}
		});
	});
	
	// Validate filter form fields.
	$('#rex_filter_min_price').blur(validatePriceRange);
	$('#rex_filter_max_price').blur(validatePriceRange);
	
	// Add Sold/Leased ribbons to listings.
	$('.sold-wrapper').each(function() {
		attachRibbon($(this), 'sold');
	});
	// Add Sold/Leased ribbons to listings.
	$('.leased-wrapper').each(function() {
		attachRibbon($(this), 'leased');
	});

});

// Highlights the price range field if range doesn't make sense.
function validatePriceRange() {
	var highlightStyle = '2px solid red';
	
	var maxPrice = parseInt($('#rex_filter_max_price').val());
	var minPrice = parseInt($('#rex_filter_min_price').val());
	
	if (isNaN(maxPrice) || isNaN(minPrice)) return;
	
	if (maxPrice <= minPrice) {
		$('#rex_filter_max_price').css('border', highlightStyle);
		$('#rex_filter_min_price').css('border', highlightStyle);
	}
	else {
		$('#rex_filter_max_price').css('border', 'none');
		$('#rex_filter_min_price').css('border', 'none');
	}
}

function attachRibbon(wrapper, type) {
	var thumbWidth = $('img', wrapper).width();
	var ribbonWidth = Math.ceil(thumbWidth / 1.2);
	var offset = thumbWidth - Math.floor(thumbWidth / 1.2);
	var ribbon = '../wp-content/plugins/realtor-express/images/' + type + '-ribbon.png';
	wrapper.append( '<div class="rex-ribbon"><img src="' + ribbon + '" alt="sold ribbon" width="' + ribbonWidth + '" /></div>');
	$('.rex-ribbon').css('left', offset + 'px');
}
  
}) (jQuery);