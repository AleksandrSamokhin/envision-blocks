(function ($) {
	const $window = $(window);
	const $document = $(document);

	var envisionBlocksParallax = function ($scope, $) {
		$document.ready(function () {
			envisionBlocksParallaxImages.init();
		});

		var envisionBlocksParallaxImages = {
			init: function () {
				this.images = $(".envision-blocks-parallax");

				if (this.images.length) {
					$window.on("load", function () {
						envisionBlocksParallaxImages.initParallaxElements();
					});

					this.images.each(function () {
						envisionBlocksParallaxImages.initItem($(this));
					});
				}
			},
			initItem: function ($currentItem) {
				envisionBlocksParallaxImages.parallaxElements($currentItem);
			},
			parallaxElements: function ($image) {
				var itemImage = $image,
					imagesParallax = itemImage.find(".envision-blocks-parallax__image"),
					imgZoom = itemImage.find(".envision-blocks-parallax__main-image img"),
					imgZoomCustomParallaxLevel = itemImage
						.find(".envision-blocks-parallax__main-image")
						.attr("data-parallax-main"),
					imgZoomParallaxLevel = 40,
					imgParallaxLevel = -50,
					imgZoomSmoothness = 30,
					imgParallaxSmoothness = 15;

				if (envisionBlocks.windowWidth > 1024) {
					if (
						typeof imgZoomCustomParallaxLevel !== "undefined" &&
						imgZoomCustomParallaxLevel !== false
					) {
						imgZoomParallaxLevel = imgZoomCustomParallaxLevel;
						imgZoomSmoothness = Math.abs(
							parseInt(imgZoomParallaxLevel / 0.9, 10)
						);
					}

					imgZoom.attr(
						"data-parallax",
						'{"y" : ' +
							imgZoomParallaxLevel +
							' , "smoothness": ' +
							imgZoomSmoothness +
							"}"
					);

					imagesParallax.each(function () {
						var imgParallaxHolder = $(this),
							imgParallax = imgParallaxHolder.find("img"),
							imgCustomParallaxLevel = imgParallaxHolder.attr("data-parallax");

						if (
							typeof imgCustomParallaxLevel !== "undefined" &&
							imgCustomParallaxLevel !== false
						) {
							imgParallaxLevel = imgCustomParallaxLevel;
							imgParallaxSmoothness = Math.abs(
								parseInt(imgParallaxLevel / 2.5, 10)
							);
						}

						imgParallax.attr(
							"data-parallax",
							'{"y" : ' +
								imgParallaxLevel +
								' , "smoothness": ' +
								imgParallaxSmoothness +
								"}"
						);
					});
				}
			},

			initParallaxElements: function () {
				var parallaxInstances = $(".envision-blocks-parallax [data-parallax]");

				if (parallaxInstances.length) {
					ParallaxScroll.init();
				}
			},
		};

		$(window).on("load", function () {
			this.images = $(".envision-blocks-parallax");

			if (this.images.length) {
				envisionBlocksParallaxImages.initParallaxElements();

				setTimeout(function () {
					if ($("body").hasClass("e--ua-firefox")) {
						envisionBlocksParallaxImages.initParallaxElements();
					}
				}, 300);
			}
		});
	};

	jQuery(window).on("elementor/frontend/init", () => {
		elementorFrontend.hooks.addAction(
			"frontend/element_ready/envision-blocks-parallax.default",
			envisionBlocksParallax
		);
	});
})(jQuery);
