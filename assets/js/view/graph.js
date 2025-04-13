/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
(function ($) {
  var $window = $(window);
  var envisionBlocksGraph = function envisionBlocksGraph($scope, $) {
    var id = $scope.data("id");
    var graphs = {
      init: function init() {
        this.widget = $(".envision-blocks-graph-" + id);
        if (this.widget.length) {
          this.widget.each(function () {
            graphs.initItem($(this));
          });
        }
      },
      initItem: function initItem($currentItem) {
        envisionBlocks.isInViewport.check($currentItem, function () {
          $currentItem.addClass("envision-blocks-init");
          var canvas = $currentItem.find("canvas");
          var data = graphs.generateChartData($currentItem, canvas);
          var chart = new Chart(canvas, data);
        });
      },
      generateChartData: function generateChartData(thisChart, ctx) {
        var type = thisChart.data("type");
        if (type) {
          type = "line";
        } else {
          type = "bar";
        }
        var ticks = thisChart.data("ticks");
        var ticksFont = thisChart.data("ticks-font");
        var ticksLabelColor = thisChart.data("ticks-label-color");
        var ticksGridLinesColor = thisChart.data("ticks-grid-lines-color");
        var fill = thisChart.data("fill");
        var linear = thisChart.data("linear");
        var values = thisChart.data("values");
        var item_labels = thisChart.data("item-labels");
        var labels = thisChart.data("labels");
        var backgroundColors = thisChart.data("background-colors");
        var hoverBackgroundColors = thisChart.data("hover-background-colors");
        var borderColors = thisChart.data("border-colors");
        var hoverBorderColors = thisChart.data("hover-border-colors");
        var borderWidth = thisChart.data("border-width");
        var hoverBorderWidth = thisChart.data("hover-border-width");
        var barSize = thisChart.data("bar-size");
        var catSize = thisChart.data("cat-size");
        var enableLegend = thisChart.data("enable-legend");
        var legendPosition = thisChart.data("legend-position");
        var legendAlignment = thisChart.data("legend-alignment");
        var legendBarWidth = thisChart.data("legend-bar-width");
        var legendBarHeight = thisChart.data("legend-bar-height");
        var legendBarMargin = thisChart.data("legend-bar-margin");
        var legendLabelColor = thisChart.data("legend-label-color");
        var legendLabelFont = thisChart.data("legend-label-font");
        var legendLabelFontSize = thisChart.data("legend-label-font-size");
        var legendLabelFontWeight = thisChart.data("legend-label-font-weight");
        var aspectRatio = thisChart.data("aspect-ratio");
        var datasets = [];
        values.forEach(function (item, index) {
          var dataset_item = {};
          dataset_item.data = values[index].split(",");
          dataset_item.label = item_labels[index];
          dataset_item.backgroundColor = backgroundColors[index];
          dataset_item.hoverBackgroundColor = hoverBackgroundColors[index];
          dataset_item.borderColor = borderColors[index];
          dataset_item.hoverBorderColor = hoverBorderColors[index];
          dataset_item.borderWidth = borderWidth;
          dataset_item.hoverBorderWidth = hoverBorderWidth;
          dataset_item.pointBackgroundColor = "rgba(0,0,0,0)";
          dataset_item.pointBorderColor = "rgba(0,0,0,0)";
          dataset_item.pointHoverBackgroundColor = "rgba(0,0,0,0)";
          dataset_item.pointHoverBorderColor = "rgba(0,0,0,0)";
          dataset_item.cubicInterpolationMode = "default";
          dataset_item.fill = fill[index];
          dataset_item.barPercentage = barSize;
          dataset_item.categoryPercentage = catSize;
          dataset_item.tension = linear[index];
          datasets.push(dataset_item);
        });
        if ($window.width() <= 480) {
          legendPosition = "bottom";
        }
        function mobile(instance) {
          if ($("body").data("elementor-device-mode") === "mobile") {
            instance.options.aspectRatio = $scope.data("settings").graph_aspect_ratio_mobile.size;
          } else {
            instance.options.aspectRatio = thisChart.data("aspect-ratio");
          }
        }
        var data_temp = {
          type: type,
          data: {
            labels: labels,
            datasets: datasets
          },
          options: {
            responsive: true,
            onResize: mobile,
            aspectRatio: aspectRatio,
            resizeDelay: 50,
            hover: {
              mode: "nearest",
              intersect: true
            },
            plugins: {
              legend: {
                display: enableLegend,
                position: legendPosition,
                align: legendAlignment,
                labels: {
                  boxWidth: legendBarWidth,
                  boxHeight: legendBarHeight,
                  padding: legendBarMargin,
                  color: legendLabelColor,
                  font: {
                    family: legendLabelFont,
                    size: legendLabelFontSize,
                    weight: legendLabelFontWeight
                  }
                }
              },
              tooltip: {
                mode: "nearest",
                intersect: false,
                titleFont: {
                  size: 13
                },
                displayColors: false,
                cornerRadius: 5,
                caretSize: 6
              }
            },
            scales: {
              x: {
                display: true,
                scaleLabel: {
                  display: true
                },
                ticks: {
                  color: ticksLabelColor,
                  font: {
                    family: ticksFont,
                    size: 16
                  },
                  padding: 10
                },
                grid: {
                  color: ticksGridLinesColor,
                  tickLength: 30
                }
              },
              y: {
                display: true,
                scaleLabel: {
                  display: true
                },
                suggestedMax: ticks.max,
                suggestedMin: ticks.min,
                ticks: {
                  stepSize: ticks.step,
                  color: ticksLabelColor,
                  font: {
                    family: ticksFont,
                    size: 16
                  },
                  padding: 10
                },
                grid: {
                  color: ticksGridLinesColor,
                  tickMarkLength: 30
                }
              }
            }
          }
        };
        return data_temp;
      }
    };
    graphs.init();
  };
  jQuery(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction("frontend/element_ready/envision-blocks-graph.default", envisionBlocksGraph);
  });
})(jQuery);
/******/ })()
;