(function() {
	var $toggle = document.querySelector('.cpm-controls');

	if (! $toggle) {
		return;
	}

	$toggle.onclick = function() {
		$toggle.classList.toggle('cpm-on');

		var radioIndex   = $toggle.classList.contains('cpm-on') ? 1 : 0;
		var radioButtons = document.querySelectorAll('.cpm-activate input[type="radio"]');

		Array.from(radioButtons).map(function(radio, index) {
			if (radioIndex !== index) {
				return;
			}

			radio.checked = true;
		});
	}
})();