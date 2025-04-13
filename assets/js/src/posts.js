(function ($) {
	"use strict";

	var envisionBlocksPostsSlider = function ($scope, $) {
		let id = $scope.data("id");
		let slider = $(".envision-blocks-posts-slider-" + id);
		let settings = slider.data("slider-settings");
		const Swiper = elementorFrontend.utils.swiper;

		if (slider.length > 0) {
			initSwiper();
			function initSwiper() {
				new Swiper(slider, settings).then(function (newSwiper) {
					var swiper = newSwiper;

					// Watch the changes of spacing control
					if (elementorFrontend.isEditMode()) {
						elementor.channels.editor.on("change", function (view) {
							let changed = view.container.settings.changed;
							if (changed.posts_space_between) {
								swiper.destroy();

								reinitSwiper();
								function reinitSwiper() {
									new Swiper(slider, settings).then(function (newSwiper) {
										var swiper = newSwiper;
									});
								}
							}
						});
					}
				});
			}
		}
	};

	jQuery(window).on("elementor/frontend/init", () => {
		elementorFrontend.hooks.addAction(
			"frontend/element_ready/envision-blocks-posts.default",
			envisionBlocksPostsSlider
		);
	});
})(jQuery);
