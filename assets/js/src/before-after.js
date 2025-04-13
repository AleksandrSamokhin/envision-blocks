(function ($) {
	"use strict";

	var envisionBlocksBeforeAfter = function ($scope, $) {
		let widgetID = $scope.data("id");
		let $baSlider = $(".envision-blocks-ba-slider-" + widgetID);

		if ($baSlider.length > 0) {
			$baSlider.beforeAfter();
		}
	};

	jQuery(window).on("elementor/frontend/init", () => {
		elementorFrontend.hooks.addAction(
			"frontend/element_ready/envision-blocks-before-after.default",
			envisionBlocksBeforeAfter
		);
	});
})(jQuery);
