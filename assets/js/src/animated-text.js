(function ($) {
	"use strict";

	const animatedText = function ($scope, $) {
		let instance = $scope.find(".envision-blocks-typed").eq(0);
		let settings = instance.data("typed");
		let widgetID = instance.data("widget-id");
		let strings = instance.data("typed-strings").split(", ");

		let options = {
			strings: strings,
			loop: settings.loop,
			typeSpeed: settings.typeSpeed,
			backSpeed: settings.backSpeed,
			backDelay: settings.backDelay,
			startDelay: settings.startDelay,
			cursorChar: settings.cursorChar,
		};

		let typed = new Typed(`#envision-blocks-typed__text-${widgetID}`, options);
	};

	$(window).on("elementor/frontend/init", () => {
		elementorFrontend.hooks.addAction(
			"frontend/element_ready/envision-blocks-animated-text.default",
			animatedText
		);
	});
})(jQuery);
