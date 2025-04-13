/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
(function ($) {
  var testimonialsFeed = function testimonialsFeed($scope, $) {
    var id = $scope.data("id");
    var $testimonial = $(".envision-blocks-testimonials-feed__item-" + id);
    $testimonial.on("click", ".envision-blocks-testimonials-feed__play-btn", function () {
      var $this = $(this);
      var $video = $this.closest(".envision-blocks-testimonials-feed__video-holder").find(".envision-blocks-testimonials-feed__video");
      var $review = $this.closest(".envision-blocks-testimonials-feed__video-holder").find(".envision-blocks-testimonials-feed__video-review");
      var $pauseBtn = $this.siblings(".envision-blocks-testimonials-feed__pause-btn");
      $this.css("display", "none");
      $review.css("display", "none");
      $pauseBtn.css("display", "block");
      $video[0].play();
    });
    $testimonial.on("click", ".envision-blocks-testimonials-feed__pause-btn", function () {
      var $this = $(this);
      var $video = $this.closest(".envision-blocks-testimonials-feed__video-holder").find(".envision-blocks-testimonials-feed__video");
      var $review = $this.closest(".envision-blocks-testimonials-feed__video-holder").find(".envision-blocks-testimonials-feed__video-review");
      var $playBtn = $this.siblings(".envision-blocks-testimonials-feed__play-btn");
      $this.css("display", "none");
      $review.css("display", "block");
      $playBtn.css("display", "block");
      $video[0].pause();
    });
  };
  jQuery(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction("frontend/element_ready/envision-blocks-testimonials-feed.default", testimonialsFeed);
  });
})(jQuery);
/******/ })()
;