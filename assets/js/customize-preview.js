(function () {
	'use strict';
	if (typeof wp === 'undefined' || !wp.customize) {
		return;
	}
	var vars = {
		kronos_brand_primary: '--brand-primary',
		kronos_brand_hover: '--brand-primary-hover',
		kronos_brand_secondary: '--brand-secondary',
		kronos_bg_base: '--bg-base',
		kronos_text_primary: '--text-primary'
	};
	Object.keys(vars).forEach(function (setting) {
		wp.customize(setting, function (value) {
			value.bind(function (to) {
				document.documentElement.style.setProperty(vars[setting], to);
			});
		});
	});
})();
