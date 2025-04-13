/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
(function ($) {
  var shareButtons = function shareButtons($scope, $) {
    var id = $scope.data("id");
    var $socials = $(".envision-blocks-share-buttons-" + id);
    var $social = $socials.find("a");
    var width = window.innerWidth;
    var height = window.innerHeight;
    if (!$socials) {
      return;
    }
    $social.on("click", function (e) {
      if (700 < width && 500 < height) {
        var url = this.getAttribute("href");
        window.open(url, "", "width=700, height=500,left=" + (width / 2 - 350) + ",top=" + (height / 2 - 250) + ",scrollbars=yes");
        e.preventDefault();
      }
    });
  };
  jQuery(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction("frontend/element_ready/envision-blocks-share-buttons.default", shareButtons);
  });
})(jQuery);
/******/ })()
;