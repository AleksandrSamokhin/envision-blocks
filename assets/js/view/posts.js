/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
(function ($) {
  "use strict";

  var envisionBlocksPostsSlider = function envisionBlocksPostsSlider($scope, $) {
    var id = $scope.data("id");
    var slider = $(".envision-blocks-posts-slider-" + id);
    var settings = slider.data("slider-settings");
    var Swiper = elementorFrontend.utils.swiper;
    if (slider.length > 0) {
      var initSwiper = function initSwiper() {
        new Swiper(slider, settings).then(function (newSwiper) {
          var swiper = newSwiper;

          // Watch the changes of spacing control
          if (elementorFrontend.isEditMode()) {
            elementor.channels.editor.on("change", function (view) {
              var changed = view.container.settings.changed;
              if (changed.posts_space_between) {
                var reinitSwiper = function reinitSwiper() {
                  new Swiper(slider, settings).then(function (newSwiper) {
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
    elementorFrontend.hooks.addAction("frontend/element_ready/envision-blocks-posts.default", envisionBlocksPostsSlider);
  });
})(jQuery);
/******/ })()
;