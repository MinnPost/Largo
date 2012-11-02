jQuery(document).ready(function($) {

	//html5 placeholders
	$('input[placeholder], textarea[placeholder]').placeholder();

	// dim sidebar
	$(window).scroll(function(){
		if (($(window).scrollTop() > 300) && (document.documentElement.clientWidth >= 767)) {
			$('.showey-hidey').animate({opacity: '0.5'}, 100);
		} else if (($(window).scrollTop() < 300)) {
			$('.showey-hidey').animate({opacity: '1'}, 100);
		}
	});

	// make the sidebar visible if you hover over it
	if (document.documentElement.clientWidth >= 767) {
		$('.showey-hidey').hover(function() {
			$(this).animate({opacity: '1'}, 100);
			}, function() {
				if ($(window).scrollTop() < 300) {
					$(this).animate({opacity: '1'}, 100);
				} else {
				    $(this).animate({opacity: '0.5'}, 100);
				}
		});
	};

	//get the correct sized image for the header, replace it with a new one if the window is resized
	$('.header_img').attr('src', banner_img_src);
	$(window).resize(function() {
		$('.header_img').attr('src', whichHeader());
	});

	//the homepage carousel, make sure we don't load this unless .carousel is defined
	if(jQuery().carousel) {
		$('.carousel').carousel({
			interval: 6000
		});
	};

});