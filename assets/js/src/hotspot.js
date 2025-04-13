class Hotspot extends elementorModules.frontend.handlers.Base {
	getDefaultSettings() {
		return {
			selectors: {
				hotspot: ".envision-blocks-hotspot",
				tooltip: ".envision-blocks-hotspot__tooltip",
			},
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings("selectors");
		return {
			$hotspot: this.$element.find(selectors.hotspot),
			$hotspotsExcludesLinks: this.$element
				.find(selectors.hotspot)
				.filter(":not(.envision-blocks-hotspot--no-tooltip)"),
			$tooltip: this.$element.find(selectors.tooltip),
		};
	}

	bindEvents() {
		const tooltipTrigger = this.getCurrentDeviceSetting("tooltip_trigger"),
			tooltipTriggerEvent =
				"mouseenter" === tooltipTrigger
					? "mouseleave mouseenter"
					: tooltipTrigger;

		if (tooltipTriggerEvent !== "none") {
			this.elements.$hotspotsExcludesLinks.on(tooltipTriggerEvent, (event) =>
				this.onHotspotTriggerEvent(event)
			);
		}
	}

	onDeviceModeChange() {
		this.elements.$hotspotsExcludesLinks.off();
		this.bindEvents();
	}

	onHotspotTriggerEvent(event) {
		const elementTarget = jQuery(event.target),
			isHotspotButtonEvent = elementTarget.closest(
				".envision-blocks-hotspot__button"
			).length,
			isTooltipMouseLeave =
				"mouseleave" === event.type &&
				(elementTarget.is(".envision-blocks-hotspot--tooltip-position") ||
					elementTarget.parents(".envision-blocks-hotspot--tooltip-position")
						.length),
			isMobile = "mobile" === elementorFrontend.getCurrentDeviceMode(),
			isHotspotLink = elementTarget.closest(
				".envision-blocks-hotspot--link"
			).length,
			triggerTooltip = !(
				isHotspotLink &&
				isMobile &&
				("mouseleave" === event.type || "mouseenter" === event.type)
			);

		if (triggerTooltip && (isHotspotButtonEvent || isTooltipMouseLeave)) {
			const currentHotspot = jQuery(event.currentTarget);
			this.elements.$hotspot
				.not(currentHotspot)
				.removeClass("envision-blocks-hotspot--active");
			currentHotspot.toggleClass("envision-blocks-hotspot--active");
		}
	} // Fix bad UX of "Sequenced Animation" when editing other controls

	editorAddSequencedAnimation() {
		this.elements.$hotspot.toggleClass(
			"envision-blocks-hotspot--sequenced",
			"yes" === this.getElementSettings("hotspot_sequenced_animation")
		);
	}

	hotspotSequencedAnimation() {
		const elementSettings = this.getElementSettings(),
			isSequencedAnimation = elementSettings.hotspot_sequenced_animation;

		if ("no" === isSequencedAnimation) {
			return;
		} //start sequenced animation when element on viewport

		const hotspotObserver = elementorModules.utils.Scroll.scrollObserver({
			callback: (event) => {
				if (event.isInViewport) {
					hotspotObserver.unobserve(this.$element[0]); //add delay for each hotspot

					this.elements.$hotspot.each((index, element) => {
						if (0 === index) {
							return;
						}

						const sequencedAnimation =
								elementSettings.hotspot_sequenced_animation_duration,
							sequencedAnimationDuration = sequencedAnimation
								? sequencedAnimation.size
								: 1000,
							animationDelay =
								index *
								(sequencedAnimationDuration / this.elements.$hotspot.length);
						element.style.animationDelay = animationDelay + "ms";
					});
				}
			},
		});
		hotspotObserver.observe(this.$element[0]);
	}

	setTooltipPositionControl() {
		const elementSettings = this.getElementSettings(),
			isDirectionAnimation =
				"undefined" !== typeof elementSettings.tooltip_animation &&
				elementSettings.tooltip_animation.match(
					/^envision-blocks-hotspot--(slide|fade)-direction/
				);

		if (isDirectionAnimation) {
			this.elements.$tooltip.removeClass(
				"envision-blocks-hotspot--tooltip-animation-from-left envision-blocks-hotspot--tooltip-animation-from-top envision-blocks-hotspot--tooltip-animation-from-right envision-blocks-hotspot--tooltip-animation-from-bottom"
			);
			this.elements.$tooltip.addClass(
				"envision-blocks-hotspot--tooltip-animation-from-" +
					elementSettings.tooltip_position
			);
		}
	}

	onInit(...args) {
		super.onInit(...args);
		this.hotspotSequencedAnimation();
		this.setTooltipPositionControl();

		if (window.elementor) {
			elementor.listenTo(elementor.channels.deviceMode, "change", () =>
				this.onDeviceModeChange()
			);
		}
	}

	onElementChange(propertyName) {
		if (propertyName.startsWith("tooltip_position")) {
			this.setTooltipPositionControl();
		}

		if (propertyName.startsWith("hotspot_sequenced_animation")) {
			this.editorAddSequencedAnimation();
		}
	}
}

jQuery(window).on("elementor/frontend/init", () => {
	const addHandler = ($element) => {
		elementorFrontend.elementsHandler.addHandler(Hotspot, {
			$element,
		});
	};

	elementorFrontend.hooks.addAction(
		"frontend/element_ready/envision-blocks-hotspot.default",
		addHandler
	);
});
