/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
(function ($) {
  "use strict";

  var animatedText = function animatedText($scope, $) {
    var instance = $scope.find(".envision-blocks-typed").eq(0);
    var settings = instance.data("typed");
    var widgetID = instance.data("widget-id");
    var strings = instance.data("typed-strings").split(", ");
    var options = {
      strings: strings,
      loop: settings.loop,
      typeSpeed: settings.typeSpeed,
      backSpeed: settings.backSpeed,
      backDelay: settings.backDelay,
      startDelay: settings.startDelay,
      cursorChar: settings.cursorChar
    };
    var typed = new Typed("#envision-blocks-typed__text-".concat(widgetID), options);
  };
  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction("frontend/element_ready/envision-blocks-animated-text.default", animatedText);
  });
})(jQuery);
/******/ })()
;