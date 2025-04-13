(function ($) {
	const $window = $(window);

	var envisionBlocksGraph = function ($scope, $) {
		let id = $scope.data("id");

		var graphs = {
			init: function () {
				this.widget = $(".envision-blocks-graph-" + id);

				if (this.widget.length) {
					this.widget.each(function () {
						graphs.initItem($(this));
					});
				}
			},
			initItem: function ($currentItem) {
				envisionBlocks.isInViewport.check($currentItem, function () {
					$currentItem.addClass("envision-blocks-init");
					let canvas = $currentItem.find("canvas");
					let data = graphs.generateChartData($currentItem, canvas);
					let chart = new Chart(canvas, data);
				});
			},
			generateChartData: function (thisChart, ctx) {
				let type = thisChart.data("type");
				if (type) {
					type = "line";
				} else {
					type = "bar";
				}
				let ticks = thisChart.data("ticks");
				let ticksFont = thisChart.data("ticks-font");
				let ticksLabelColor = thisChart.data("ticks-label-color");
				let ticksGridLinesColor = thisChart.data("ticks-grid-lines-color");
				let fill = thisChart.data("fill");
				let linear = thisChart.data("linear");
				let values = thisChart.data("values");
				let item_labels = thisChart.data("item-labels");
				let labels = thisChart.data("labels");
				let backgroundColors = thisChart.data("background-colors");
				let hoverBackgroundColors = thisChart.data("hover-background-colors");
				let borderColors = thisChart.data("border-colors");
				let hoverBorderColors = thisChart.data("hover-border-colors");
				let borderWidth = thisChart.data("border-width");
				let hoverBorderWidth = thisChart.data("hover-border-width");
				let barSize = thisChart.data("bar-size");
				let catSize = thisChart.data("cat-size");
				let enableLegend = thisChart.data("enable-legend");
				let legendPosition = thisChart.data("legend-position");
				let legendAlignment = thisChart.data("legend-alignment");
				let legendBarWidth = thisChart.data("legend-bar-width");
				let legendBarHeight = thisChart.data("legend-bar-height");
				let legendBarMargin = thisChart.data("legend-bar-margin");
				let legendLabelColor = thisChart.data("legend-label-color");
				let legendLabelFont = thisChart.data("legend-label-font");
				let legendLabelFontSize = thisChart.data("legend-label-font-size");
				let legendLabelFontWeight = thisChart.data("legend-label-font-weight");
				let aspectRatio = thisChart.data("aspect-ratio");

				let datasets = [];

				values.forEach(function (item, index) {
					let dataset_item = {};

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
						instance.options.aspectRatio =
							$scope.data("settings").graph_aspect_ratio_mobile.size;
					} else {
						instance.options.aspectRatio = thisChart.data("aspect-ratio");
					}
				}

				let data_temp = {
					type: type,
					data: {
						labels: labels,
						datasets: datasets,
					},
					options: {
						responsive: true,
						onResize: mobile,
						aspectRatio: aspectRatio,
						resizeDelay: 50,
						hover: {
							mode: "nearest",
							intersect: true,
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
										weight: legendLabelFontWeight,
									},
								},
							},
							tooltip: {
								mode: "nearest",
								intersect: false,
								titleFont: {
									size: 13,
								},
								displayColors: false,
								cornerRadius: 5,
								caretSize: 6,
							},
						},
						scales: {
							x: {
								display: true,
								scaleLabel: {
									display: true,
								},
								ticks: {
									color: ticksLabelColor,
									font: {
										family: ticksFont,
										size: 16,
									},
									padding: 10,
								},
								grid: {
									color: ticksGridLinesColor,
									tickLength: 30,
								},
							},
							y: {
								display: true,
								scaleLabel: {
									display: true,
								},
								suggestedMax: ticks.max,
								suggestedMin: ticks.min,
								ticks: {
									stepSize: ticks.step,
									color: ticksLabelColor,
									font: {
										family: ticksFont,
										size: 16,
									},
									padding: 10,
								},
								grid: {
									color: ticksGridLinesColor,
									tickMarkLength: 30,
								},
							},
						},
					},
				};
				return data_temp;
			},
		};

		graphs.init();
	};

	jQuery(window).on("elementor/frontend/init", () => {
		elementorFrontend.hooks.addAction(
			"frontend/element_ready/envision-blocks-graph.default",
			envisionBlocksGraph
		);
	});
})(jQuery);
