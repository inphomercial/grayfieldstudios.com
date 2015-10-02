
var GREYFIELD = window.GREYFIELD || {};

/**
 * Preload Splash
 */
GREYFIELD.preLoad = function() {

    // Start with white
    $('#fullPage').addClass('fullPageOverlay');

    // Preload the page with jPreLoader
	$('body').jpreLoader({
		splashID: "#jSplash",
		showSplash: true,
		showPercentage: true,
		autoClose: true,
		splashFunction: function() {
			$('#circle').delay(250).animate({'opacity' : 1}, 500, 'linear');
        }
    }, function() {
            // Fade white out to actual site
            $('#fullPage').fadeOut(1000);
        }
    );
};

/**
 * Contact Form
 */
GREYFIELD.contactForm = function(){

	$("#contact-submit").on('click',function() {
		$contact_form = $('#contact-form');
        var fields = $contact_form.serialize();

		$.ajax({
			type: "POST",
			url: "php/contact.php",
			data: fields,
			dataType: 'json',
			success: function(response) {

				if(response.status){
                    $("#contact-form").find( "#name" ).val('');
                    $("#contact-form").find( "#email" ).val('');
                    $("#contact-form").find( "#message" ).val('');
				}

				$('#contact-error-name').empty().html(response.html.name);
				$('#contact-error-email').empty().html(response.html.email);
				$('#contact-error-message').empty().html(response.html.message);
			}
		});
		return false;
	});
};

$(document).ready(function() {

    console.log("Greyfield Studios JS - Loaded");

    GREYFIELD.preLoad();
    GREYFIELD.contactForm();
});
