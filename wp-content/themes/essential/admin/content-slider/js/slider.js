jQuery(function($) {
	
	$('#da-slider').cslider({
		bgincrement : parseInt( slider.bgincrement ),
		autoplay    : parseInt( slider.autoplay ),
		interval    : parseInt( slider.interval ),
	});

	$('#da-slider').each(function(index) {
		// Call touchSwipe script on each parallax-slider
		$(this).swipe({

			swipeLeft : function() {
				$('#da-slider').find('span.da-arrows-next').click();
			},
			swipeRight : function() {
				$('#da-slider').find('span.da-arrows-prev').click();
			},
		});
	});

}); 