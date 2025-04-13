/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
(function ($) {
  "use strict";

  var heroSlider = function heroSlider($scope, $) {
    var id = $scope.data("id");
    var $slider = $(".envision-blocks-hero-slider-" + id);
    var settings = $slider.data("slider-settings");
    var Swiper = elementorFrontend.utils.swiper;
    if ($slider.length > 0) {
      var initSwiper = function initSwiper() {
        new Swiper($slider, settings).then(function (newSwiper) {
          var swiper = newSwiper;

          // Videos
          var $videos = $slider.find("video");
          swiper.on("slideChange", function () {
            $videos.each(function (index) {
              this.currentTime = 0;
            });
            var prevVideo = $("[data-swiper-slide-index=" + this.previousIndex + "]").find("video");
            var currentVideo = $("[data-swiper-slide-index=" + this.realIndex + "]").find("video");
          });

          // LazyLoad videos
          var lazyLoadInstance = new LazyLoad({
            elements_selector: ".lazy"
          });
          if (lazyLoadInstance) {
            lazyLoadInstance.update();
          }

          // Watch the changes of spacing control
          if (elementorFrontend.isEditMode()) {
            elementor.channels.editor.on("change", function (view) {
              var changed = view.container.settings.changed;
              if (changed.hero_slider_space_between) {
                var reinitSwiper = function reinitSwiper() {
                  new Swiper($slider, settings).then(function (newSwiper) {
                    var swiper = newSwiper;
                  });
                };
                swiper.destroy();
                reinitSwiper();
              }
            });
          }
        });
      };
      initSwiper();
    }
  };
  jQuery(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction("frontend/element_ready/envision-blocks-hero-slider.default", heroSlider);
  });
})(jQuery);
/******/ })()
;