/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
(function ($) {
  "use strict";

  var envisionBlocksBeforeAfter = function envisionBlocksBeforeAfter($scope, $) {
    var widgetID = $scope.data("id");
    var $baSlider = $(".envision-blocks-ba-slider-" + widgetID);
    if ($baSlider.length > 0) {
      $baSlider.beforeAfter();
    }
  };
  jQuery(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction("frontend/element_ready/envision-blocks-before-after.default", envisionBlocksBeforeAfter);
  });
})(jQuery);
/******/ })()
;