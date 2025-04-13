(function ($) {
	var testimonialsFeed = function ($scope, $) {
		const id = $scope.data("id");
		const $testimonial = $(".envision-blocks-testimonials-feed__item-" + id);
		$testimonial.on(
			"click",
			".envision-blocks-testimonials-feed__play-btn",
			function () {
				const $this = $(this);
				const $video = $this
					.closest(".envision-blocks-testimonials-feed__video-holder")
					.find(".envision-blocks-testimonials-feed__video");
				const $review = $this
					.closest(".envision-blocks-testimonials-feed__video-holder")
					.find(".envision-blocks-testimonials-feed__video-review");
				const $pauseBtn = $this.siblings(
					".envision-blocks-testimonials-feed__pause-btn"
				);

				$this.css("display", "none");
				$review.css("display", "none");
				$pauseBtn.css("display", "block");
				$video[0].play();
			}
		);

		$testimonial.on(
			"click",
			".envision-blocks-testimonials-feed__pause-btn",
			function () {
				const $this = $(this);
				const $video = $this
					.closest(".envision-blocks-testimonials-feed__video-holder")
					.find(".envision-blocks-testimonials-feed__video");
				const $review = $this
					.closest(".envision-blocks-testimonials-feed__video-holder")
					.find(".envision-blocks-testimonials-feed__video-review");
				const $playBtn = $this.siblings(
					".envision-blocks-testimonials-feed__play-btn"
				);

				$this.css("display", "none");
				$review.css("display", "block");
				$playBtn.css("display", "block");
				$video[0].pause();
			}
		);
	};

	jQuery(window).on("elementor/frontend/init", () => {
		elementorFrontend.hooks.addAction(
			"frontend/element_ready/envision-blocks-testimonials-feed.default",
			testimonialsFeed
		);
	});
})(jQuery);
