/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
// import Collapse from "bootstrap/js/dist/collapse";

(function ($) {
  var $window = $(window);
  var $document = $(document);
  window.envisionBlocks = {};
  envisionBlocks.windowWidth = $window.width();
  $window.resize(function () {
    envisionBlocks.windowWidth = $window.width();
  });

  /* Check if in viewport
  -------------------------------------------------------*/
  envisionBlocks.isInViewport = {
    check: function check($element, callback, onlyOnce) {
      if ($element.length) {
        var offset = typeof $element.data("viewport-offset") !== "undefined" ? $element.data("viewport-offset") : 0.15; // When item is 15% in the viewport
        var observer = new IntersectionObserver(function (entries) {
          // isIntersecting is true when element and viewport are overlapping
          // isIntersecting is false when element and viewport don't overlap
          if (entries[0].isIntersecting === true) {
            callback.call($element);
            // Stop watching the element when it's initialize
            if (onlyOnce !== false) {
              observer.disconnect();
            }
          }
        }, {
          threshold: [offset]
        });
        observer.observe($element[0]);
      }
    }
  };

  /* Debounce
  -------------------------------------------------------*/
  envisionBlocks.debounce = function (func, time) {
    var time = time || 100; // 100 by default if no param
    var timer;
    return function (event) {
      if (timer) clearTimeout(timer);
      timer = setTimeout(func, time, event);
    };
  };

  /* Filter
  -------------------------------------------------------*/
  function initFilter() {
    var $filter = $(".envision-blocks-filter");
    $filter.on("click", function (e) {
      var $this = $(this);
      if (!$this.is(".clicked")) {
        // Function to append posts to DOM
        var appendPostsToDOM = function appendPostsToDOM(posts) {
          var $items = $(posts).hide();
          $gridSizer.after($items);

          // recalc masonry items
          $widgetRow.imagesLoaded(function () {
            $items.show();
            if ($widgetRow.data("isotope")) {
              $widgetRow.isotope("layout").isotope("appended", $items);
            }
          });
        };
        $this.siblings().removeClass("active");
        $this.addClass("clicked active");
        e.preventDefault();
        e.stopPropagation();
        var category = $this.data("filter");
        var widget = $this.parent(".envision-blocks-isotope-filter").siblings(".envision-blocks-load-more-container");
        var $widgetRow = widget.find(".envision-blocks-row");
        var $gridSizer = $widgetRow.find(".envision-blocks-grid-sizer");
        var settings = widget.data("settings");
        var $loadMoreBtn = $this.parent(".envision-blocks-isotope-filter").siblings(".envision-blocks-load-more");
        var data = {
          action: "envision_blocks_widget_post_filter",
          security: envision_blocks_elementor_data.ajax_nonce,
          data: {
            category: category,
            settings: settings
          }
        };
        $.ajax({
          type: "POST",
          url: envision_blocks_elementor_data.ajax_url,
          data: data,
          beforeSend: function beforeSend(xhr) {
            $widgetRow.addClass("envision-blocks-loading");
            $loadMoreBtn.hide();
            $widgetRow.append('<div class="envision-blocks-loader"></div>');
          },
          success: function success(response) {
            if (response) {
              $this.removeClass("clicked");
              $widgetRow.removeClass("envision-blocks-loading");
              $widgetRow.find(".envision-blocks-loader").remove();
              $widgetRow.find(".envision-blocks-masonry-item").remove();
              if ("*" === $this.data("filter")) {
                $loadMoreBtn.show();
              }
              appendPostsToDOM(response);
            } else {
              $this.parent(".envision-blocks-load-more").remove();
            }
          }
        });
      }
      return false;
    });
  }

  /* Load More
  -------------------------------------------------------*/
  function initLoadMore() {
    $(".envision-blocks-load-more__button").on("click", function (e) {
      var button = $(this);
      if (!button.is(".clicked")) {
        button.addClass("clicked");
        e.preventDefault();
        e.stopPropagation();
        var widget = button.parent(".envision-blocks-load-more").siblings(".envision-blocks-load-more-container");
        var $widgetRow = widget.find(".envision-blocks-row");
        var page = widget.data("page");
        var newPage = page + 1;
        var settings = widget.data("settings");
        var data = {
          action: "envision_blocks_widget_load_more",
          security: envision_blocks_elementor_data.ajax_nonce,
          data: {
            page: page,
            settings: settings
          }
        };
        $.ajax({
          type: "POST",
          url: envision_blocks_elementor_data.ajax_url,
          data: data,
          beforeSend: function beforeSend(xhr) {
            button.addClass("envision-blocks-loading");
            button.append('<div class="envision-blocks-loader"></div>');
          },
          success: function success(response) {
            if (response) {
              button.removeClass("envision-blocks-loading clicked");
              button.find(".envision-blocks-loader").remove();
              widget.data("page", newPage);
              var $items = $(response).hide();
              $widgetRow.append($items);

              // recalc masonry items
              $widgetRow.imagesLoaded(function () {
                $items.show();
                if ($widgetRow.data("isotope")) {
                  $widgetRow.isotope("appended", $items);
                }
              });
              if (widget.data("page_max") == widget.data("page")) {
                button.parent(".envision-blocks-load-more").remove();
              }
            } else {
              button.parent(".envision-blocks-load-more").remove();
            }
          }
        });
      }
      return false;
    });
  }

  /* Masonry / filter
  -------------------------------------------------------*/
  function initMasonry($el, $scope, type) {
    var $grid = $el,
      id = $scope.data("id");
    $grid.imagesLoaded(function () {
      $grid.isotope({
        itemSelector: ".envision-blocks-masonry-item",
        masonry: {
          columnWidth: ".envision-blocks-grid-sizer"
        },
        percentPosition: true,
        stagger: 30,
        hiddenStyle: {
          transform: "translateY(100px)",
          opacity: 0
        },
        visibleStyle: {
          transform: "translateY(0px)",
          opacity: 1
        }
      });
      $grid.isotope();
    });

    // Watch the changes of spacing control
    if (elementorFrontend.isEditMode()) {
      elementor.channels.editor.on("change", function (view) {
        var changed = view.container.settings.changed;
        if (changed.grid_style_rows_gap || changed.box_height) {
          $grid.isotope("layout");
        }
      });
    }
  }

  /* Masonry Grid
  -------------------------------------------------------*/
  var envisionBlocksMasonryGrid = function envisionBlocksMasonryGrid($scope, $) {
    var widgetType = $scope.find(".envision-blocks-load-more-container").data("settings").post_type;
    if (widgetType === "post") {
      widgetType = "posts";
    }
    var $grid = $(".envision-blocks-masonry-grid__" + widgetType);
    if ($grid.length > 0) {
      initMasonry($grid, $scope, widgetType);
    }
  };

  /* Sticky Header
  -------------------------------------------------------*/
  function initStickyHeader() {
    var $stickyHeader = $(".envision-blocks-header--is-sticky");
    $stickyHeader.css({
      top: "-" + $stickyHeader.height() + "px"
    });
    if ($window.scrollTop() > 190) {
      $stickyHeader.addClass("envision-blocks-header--is-scrolling");
      $stickyHeader.css({
        top: ""
      });
    } else {
      $stickyHeader.removeClass("envision-blocks-header--is-scrolling");
    }
  }

  /* Product Class
  -------------------------------------------------------*/
  var envisionBlocksWooPostClass = function envisionBlocksWooPostClass($scope, $) {
    var $container = $(".envision-blocks-woocommerce-template .elementor");
    if (!$container.hasClass("product")) {
      $container.addClass("product");
    }
  };
  $document.ready(function () {
    initLoadMore();
    initFilter();
  });
  $window.on("scroll", function () {
    initStickyHeader();
  });
  $window.on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction("frontend/element_ready/envision-blocks-portfolio.default", envisionBlocksMasonryGrid);
    elementorFrontend.hooks.addAction("frontend/element_ready/envision-blocks-posts.default", envisionBlocksMasonryGrid);

    /*
     * WooCommerce
     * Add class product to Elementor post.
     */
    var wooWidgets = ["envision-blocks-woo-product-breadcrumbs", "envision-blocks-woo-product-add-to-cart", "envision-blocks-woo-product-additional-information", "envision-blocks-woo-product-image", "envision-blocks-woo-product-meta", "envision-blocks-woo-product-price", "envision-blocks-woo-product-rating", "envision-blocks-woo-product-related", "envision-blocks-woo-product-short-description", "envision-blocks-woo-product-stock", "envision-blocks-woo-product-tabs", "envision-blocks-woo-product-title", "envision-blocks-woo-product-upsell", "envision-blocks-woo-product-notices"];
    for (var i = 0; i < wooWidgets.length; i++) {
      elementorFrontend.hooks.addAction("frontend/element_ready/" + wooWidgets[i] + ".default", envisionBlocksWooPostClass);
    }
  });
})(jQuery);
/******/ })()
;