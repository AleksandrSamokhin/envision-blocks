/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
(function ($) {
  var verticalTabs = function verticalTabs($scope, $) {
    var widgetId = $scope.data("id");
    var $tabs = $(".envision-blocks-vertical-tabs-" + widgetId);
    var $menuItems = $tabs.find(".envision-blocks-vertical-tabs__list-item");
    var $images = $tabs.find(".envision-blocks-vertical-tabs__img");
    var activateOnClick = $tabs.data("activate-on-click");
    var event = "mouseenter";
    var imageRatioHolder = $tabs.find(".envision-blocks-vertical-tabs__ratio");
    var imageHeight = $images.height();
    var imageWidth = $images.width();
    var imageRatio = Math.round(imageHeight / imageWidth * 100);
    imageRatioHolder.css({
      "padding-top": imageRatio + "%"
    });
    if ("yes" === activateOnClick) {
      event = "click";
    }
    $menuItems.on(event, function () {
      $this = $(this);
      var index = $menuItems.index($this);
      $menuItems.removeClass("envision-blocks-vertical-tabs__list-item--active");
      $this.addClass("envision-blocks-vertical-tabs__list-item--active");
      $images.removeClass("envision-blocks-vertical-tabs__img--active");
      $images.eq(index).addClass("envision-blocks-vertical-tabs__img--active");
    });
  };
  jQuery(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction("frontend/element_ready/envision-blocks-vertical-tabs.default", verticalTabs);
  });
})(jQuery);
/******/ })()
;