jQuery(function($) {
	/* Define the main wrapper */
	var $wrapper = $('#CPM-wrap');

	/* Hide all elements used for non-javascript browsers */
	$wrapper.find('.cpm-js-hide').hide();

	/* Append custom controls */
	var $controls = $('<div class="cpm-controls"><span></span></div>');

	$wrapper.find('.cpm-activate').append($controls);

	/* Make controls functional */
	var $toggler = $wrapper.find('.cpm-controls span'),
		togglerPos = $wrapper.find('.cpm-activate input[type="radio"]:checked').index('.cpm-activate input[type="radio"]') * 30;

	$toggler.css('left', togglerPos);

	$(document).on('click', '.cpm-controls span', function() {

		var togglerX = parseInt($toggler.css('left')),
			moveTo = (togglerX == 0) ? 30 : 0,
			radioIdx = (togglerX == 0) ? 1 : 0;

		$toggler.stop().animate({
			left: moveTo
		}, 200, 'linear', function() {
			$toggler.toggleClass('on');
			$wrapper.find('.cpm-activate input[type="radio"]').prop('checked', false).eq(radioIdx).prop('checked', true);
		});
	});

});