/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : String(i); }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
function _callSuper(t, o, e) { return o = _getPrototypeOf(o), _possibleConstructorReturn(t, _isNativeReflectConstruct() ? Reflect.construct(o, e || [], _getPrototypeOf(t).constructor) : o.apply(t, e)); }
function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } else if (call !== void 0) { throw new TypeError("Derived constructors may only return object or undefined"); } return _assertThisInitialized(self); }
function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }
function _isNativeReflectConstruct() { try { var t = !Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); } catch (t) {} return (_isNativeReflectConstruct = function _isNativeReflectConstruct() { return !!t; })(); }
function _get() { if (typeof Reflect !== "undefined" && Reflect.get) { _get = Reflect.get.bind(); } else { _get = function _get(target, property, receiver) { var base = _superPropBase(target, property); if (!base) return; var desc = Object.getOwnPropertyDescriptor(base, property); if (desc.get) { return desc.get.call(arguments.length < 3 ? target : receiver); } return desc.value; }; } return _get.apply(this, arguments); }
function _superPropBase(object, property) { while (!Object.prototype.hasOwnProperty.call(object, property)) { object = _getPrototypeOf(object); if (object === null) break; } return object; }
function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf.bind() : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }
function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); Object.defineProperty(subClass, "prototype", { writable: false }); if (superClass) _setPrototypeOf(subClass, superClass); }
function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf ? Object.setPrototypeOf.bind() : function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }
var Hotspot = /*#__PURE__*/function (_elementorModules$fro) {
  _inherits(Hotspot, _elementorModules$fro);
  function Hotspot() {
    _classCallCheck(this, Hotspot);
    return _callSuper(this, Hotspot, arguments);
  }
  _createClass(Hotspot, [{
    key: "getDefaultSettings",
    value: function getDefaultSettings() {
      return {
        selectors: {
          hotspot: ".envision-blocks-hotspot",
          tooltip: ".envision-blocks-hotspot__tooltip"
        }
      };
    }
  }, {
    key: "getDefaultElements",
    value: function getDefaultElements() {
      var selectors = this.getSettings("selectors");
      return {
        $hotspot: this.$element.find(selectors.hotspot),
        $hotspotsExcludesLinks: this.$element.find(selectors.hotspot).filter(":not(.envision-blocks-hotspot--no-tooltip)"),
        $tooltip: this.$element.find(selectors.tooltip)
      };
    }
  }, {
    key: "bindEvents",
    value: function bindEvents() {
      var _this = this;
      var tooltipTrigger = this.getCurrentDeviceSetting("tooltip_trigger"),
        tooltipTriggerEvent = "mouseenter" === tooltipTrigger ? "mouseleave mouseenter" : tooltipTrigger;
      if (tooltipTriggerEvent !== "none") {
        this.elements.$hotspotsExcludesLinks.on(tooltipTriggerEvent, function (event) {
          return _this.onHotspotTriggerEvent(event);
        });
      }
    }
  }, {
    key: "onDeviceModeChange",
    value: function onDeviceModeChange() {
      this.elements.$hotspotsExcludesLinks.off();
      this.bindEvents();
    }
  }, {
    key: "onHotspotTriggerEvent",
    value: function onHotspotTriggerEvent(event) {
      var elementTarget = jQuery(event.target),
        isHotspotButtonEvent = elementTarget.closest(".envision-blocks-hotspot__button").length,
        isTooltipMouseLeave = "mouseleave" === event.type && (elementTarget.is(".envision-blocks-hotspot--tooltip-position") || elementTarget.parents(".envision-blocks-hotspot--tooltip-position").length),
        isMobile = "mobile" === elementorFrontend.getCurrentDeviceMode(),
        isHotspotLink = elementTarget.closest(".envision-blocks-hotspot--link").length,
        triggerTooltip = !(isHotspotLink && isMobile && ("mouseleave" === event.type || "mouseenter" === event.type));
      if (triggerTooltip && (isHotspotButtonEvent || isTooltipMouseLeave)) {
        var currentHotspot = jQuery(event.currentTarget);
        this.elements.$hotspot.not(currentHotspot).removeClass("envision-blocks-hotspot--active");
        currentHotspot.toggleClass("envision-blocks-hotspot--active");
      }
    } // Fix bad UX of "Sequenced Animation" when editing other controls
  }, {
    key: "editorAddSequencedAnimation",
    value: function editorAddSequencedAnimation() {
      this.elements.$hotspot.toggleClass("envision-blocks-hotspot--sequenced", "yes" === this.getElementSettings("hotspot_sequenced_animation"));
    }
  }, {
    key: "hotspotSequencedAnimation",
    value: function hotspotSequencedAnimation() {
      var _this2 = this;
      var elementSettings = this.getElementSettings(),
        isSequencedAnimation = elementSettings.hotspot_sequenced_animation;
      if ("no" === isSequencedAnimation) {
        return;
      } //start sequenced animation when element on viewport

      var hotspotObserver = elementorModules.utils.Scroll.scrollObserver({
        callback: function callback(event) {
          if (event.isInViewport) {
            hotspotObserver.unobserve(_this2.$element[0]); //add delay for each hotspot

            _this2.elements.$hotspot.each(function (index, element) {
              if (0 === index) {
                return;
              }
              var sequencedAnimation = elementSettings.hotspot_sequenced_animation_duration,
                sequencedAnimationDuration = sequencedAnimation ? sequencedAnimation.size : 1000,
                animationDelay = index * (sequencedAnimationDuration / _this2.elements.$hotspot.length);
              element.style.animationDelay = animationDelay + "ms";
            });
          }
        }
      });
      hotspotObserver.observe(this.$element[0]);
    }
  }, {
    key: "setTooltipPositionControl",
    value: function setTooltipPositionControl() {
      var elementSettings = this.getElementSettings(),
        isDirectionAnimation = "undefined" !== typeof elementSettings.tooltip_animation && elementSettings.tooltip_animation.match(/^envision-blocks-hotspot--(slide|fade)-direction/);
      if (isDirectionAnimation) {
        this.elements.$tooltip.removeClass("envision-blocks-hotspot--tooltip-animation-from-left envision-blocks-hotspot--tooltip-animation-from-top envision-blocks-hotspot--tooltip-animation-from-right envision-blocks-hotspot--tooltip-animation-from-bottom");
        this.elements.$tooltip.addClass("envision-blocks-hotspot--tooltip-animation-from-" + elementSettings.tooltip_position);
      }
    }
  }, {
    key: "onInit",
    value: function onInit() {
      var _get2,
        _this3 = this;
      for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
        args[_key] = arguments[_key];
      }
      (_get2 = _get(_getPrototypeOf(Hotspot.prototype), "onInit", this)).call.apply(_get2, [this].concat(args));
      this.hotspotSequencedAnimation();
      this.setTooltipPositionControl();
      if (window.elementor) {
        elementor.listenTo(elementor.channels.deviceMode, "change", function () {
          return _this3.onDeviceModeChange();
        });
      }
    }
  }, {
    key: "onElementChange",
    value: function onElementChange(propertyName) {
      if (propertyName.startsWith("tooltip_position")) {
        this.setTooltipPositionControl();
      }
      if (propertyName.startsWith("hotspot_sequenced_animation")) {
        this.editorAddSequencedAnimation();
      }
    }
  }]);
  return Hotspot;
}(elementorModules.frontend.handlers.Base);
jQuery(window).on("elementor/frontend/init", function () {
  var addHandler = function addHandler($element) {
    elementorFrontend.elementsHandler.addHandler(Hotspot, {
      $element: $element
    });
  };
  elementorFrontend.hooks.addAction("frontend/element_ready/envision-blocks-hotspot.default", addHandler);
});
/******/ })()
;