/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
(function ($) {
  var prevWidth;
  var marquee = function marquee($scope, $) {
    var id = $scope.data("id");
    var $marquee = $(".envision-blocks-marquee-" + id);
    var $animation = $marquee.find(".envision-blocks-marquee__animation");
    var vertical = "yes" === $marquee.data("v-direction");
    prevWidth = $(window).width();
    function checkIfLoaded($img) {
      return new Promise(function (resolve) {
        if ($img[0].complete && $img[0].naturalWidth !== 0) {
          resolve();
        } else {
          $img.on("load", function () {
            resolve();
          });
        }
      });
    }

    /* Get initial summation images length */
    function getInitialLength(el, direction) {
      var $images = el.find("img:visible");
      if ($images.length === 0) {
        // If there are no images, calculate length immediately
        return calculateLength();
      } else {
        // If there are images, wait for them to load
        return new Promise(function (resolve) {
          Promise.race($images.map(function () {
            return checkIfLoaded($(this));
          })).then(function () {
            resolve(calculateLength());
          });
        });
      }
      function calculateLength() {
        var length = 0;
        var space = parseFloat(el.css("--items-gap"));
        el.find(".envision-blocks-marquee__item").each(function (i, el) {
          if (direction) {
            length += $(this).height() + space;
          } else {
            length += $(this).width() + space;
          }
        });
        return length;
      }
    }
    function setValues(el, length, direction) {
      if (direction) {
        var ratio = Math.ceil(el.parent().height() / length),
          total = ratio + 1;
      } else {
        var ratio = Math.ceil(el.parent().width() / length),
          total = ratio + 1;
      }

      // Store original content
      if (!el.data("original-content")) {
        el.data("original-content", el.html());
      }
      el.empty();
      for (var i = 0; i < total; i++) {
        el.append(el.data("original-content"));
      }
      if (direction) {
        el.height(length * total);
      } else {
        el.width(length * total);
      }
      el.css("--total", total);
      el.css("--est-speed", length / 100);
    }
    function setDirection(el, length, direction) {
      if (direction) {
        if (el.css("--direction") == -1) {
          el.css("margin-top", -1 * length + "px");
        }
      } else {
        if (el.css("--direction") == -1) {
          el.css("margin-left", -1 * length + "px");
        }
      }
    }
    function setPauseOnHover(el) {
      var pauseOnHover = $(window).width() > 767 ? "--pause-on-hover" : "--pause-on-hover-mobile";
      if (el.css(pauseOnHover) && el.css(pauseOnHover).trim() == "true") {
        el.css("--poh", "paused");
      } else {
        el.css("--poh", "running");
      }
    }
    function adjustMarquee() {
      var length = getInitialLength($animation, vertical);
      if (length instanceof Promise) {
        length.then(handleLength);
      } else {
        handleLength(length);
      }
      function handleLength(length) {
        if (length) {
          setValues($animation, length, vertical);
          setDirection($animation, length, vertical);
        }
        setPauseOnHover($animation);
      }
    }

    // Initial setup
    $marquee.find(".envision-blocks-marquee__animation").each(function () {
      $(this).data("original-content", $(this).html());
      setPauseOnHover($(this));
    });
    $marquee.addClass("showing");

    // Adjust marquee when in viewport
    envisionBlocks.isInViewport.check($marquee, function () {
      adjustMarquee();

      // Start the animation when in the viewport
      $animation.css("animation-play-state", "running");
    });

    // Adjust on resize
    $(window).on("resize", function () {
      if ($(window).width() == prevWidth) {
        return;
      }
      prevWidth = $(window).width();
      adjustMarquee();
    });
  };
  jQuery(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction("frontend/element_ready/envision-blocks-marquee.default", marquee);
  });
})(jQuery);
/******/ })()
;