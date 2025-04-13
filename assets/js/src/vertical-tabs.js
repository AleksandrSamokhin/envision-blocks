(function ($) {
	var verticalTabs = function ($scope, $) {
		let widgetId = $scope.data("id");
		let $tabs = $(".envision-blocks-vertical-tabs-" + widgetId);
		let $menuItems = $tabs.find(".envision-blocks-vertical-tabs__list-item");
		let $images = $tabs.find(".envision-blocks-vertical-tabs__img");
		let activateOnClick = $tabs.data("activate-on-click");
		let event = "mouseenter";
		let imageRatioHolder = $tabs.find(".envision-blocks-vertical-tabs__ratio");

		let imageHeight = $images.height();
		let imageWidth = $images.width();
		let imageRatio = Math.round((imageHeight / imageWidth) * 100);
		imageRatioHolder.css({ "padding-top": imageRatio + "%" });

		if ("yes" === activateOnClick) {
			event = "click";
		}

		$menuItems.on(event, function () {
			$this = $(this);
			var index = $menuItems.index($this);

			$menuItems.removeClass(
				"envision-blocks-vertical-tabs__list-item--active"
			);
			$this.addClass("envision-blocks-vertical-tabs__list-item--active");

			$images.removeClass("envision-blocks-vertical-tabs__img--active");
			$images.eq(index).addClass("envision-blocks-vertical-tabs__img--active");
		});
	};

	jQuery(window).on("elementor/frontend/init", () => {
		elementorFrontend.hooks.addAction(
			"frontend/element_ready/envision-blocks-vertical-tabs.default",
			verticalTabs
		);
	});
})(jQuery);
