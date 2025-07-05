(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = void 0;
var _helpers = require("./helpers");
/* jshint esnext:true */

var general = function general(api, $) {
  'use strict';

  api('vamtam_theme[show-splash-screen]', function (value) {
    value.bind(function (to) {
      if (+to) {
        $('body').triggerHandler('vamtam-preview-splash-screen');
      }
    });
  });
  api('vamtam_theme[splash-screen-logo]', function (value) {
    value.bind(function (to) {
      var wrapper = $('.vamtam-splash-screen-progress-wrapper');
      var current_image = wrapper.find('> img');
      if (current_image.length === 0) {
        current_image = $('<img />');
        wrapper.prepend(current_image);
      }
      current_image.attr('src', to);
      $('body').triggerHandler('vamtam-preview-splash-screen');
    });
  });
  api('vamtam_theme[show-scroll-to-top]', function (value) {
    value.bind(function (to) {
      (0, _helpers.toggle)($('#scroll-to-top'), to);
    });
  });
};
var _default = exports["default"] = general;

},{"./helpers":2}],2:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.hexToHsl = hexToHsl;
exports.hexToRgb = hexToRgb;
exports.isNumeric = isNumeric;
exports.rgbToHsl = rgbToHsl;
exports.toggle = void 0;
function _toConsumableArray(r) { return _arrayWithoutHoles(r) || _iterableToArray(r) || _unsupportedIterableToArray(r) || _nonIterableSpread(); }
function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _iterableToArray(r) { if ("undefined" != typeof Symbol && null != r[Symbol.iterator] || null != r["@@iterator"]) return Array.from(r); }
function _arrayWithoutHoles(r) { if (Array.isArray(r)) return _arrayLikeToArray(r); }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
/* jshint esnext:true */

var toggle = exports.toggle = function toggle(el, visibility) {
  'use strict';

  if (+visibility) {
    el.show();
  } else {
    el.hide();
  }
};
function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

/**
 * Converts an RGB color value to HSL. Conversion formula
 * adapted from http://en.wikipedia.org/wiki/HSL_color_space.
 * Assumes r, g, and b are contained in the set [0, 255] and
 * returns h, s, and l for use with the hsl() notation in CSS
 *
 * @param   Number  r       The red color value
 * @param   Number  g       The green color value
 * @param   Number  b       The blue color value
 * @return  Array           The HSL representation
 */
function rgbToHsl(r, g, b) {
  r /= 255, g /= 255, b /= 255;
  var max = Math.max(r, g, b),
    min = Math.min(r, g, b);
  var h,
    s,
    l = (max + min) / 2;
  if (max == min) {
    h = s = 0; // achromatic
  } else {
    var d = max - min;
    s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
    switch (max) {
      case r:
        h = (g - b) / d + (g < b ? 6 : 0);
        break;
      case g:
        h = (b - r) / d + 2;
        break;
      case b:
        h = (r - g) / d + 4;
        break;
    }
    h /= 6;
  }
  return [h * 360, s * 100, l * 100];
}
function hexToRgb(hex) {
  // Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
  var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
  hex = hex.replace(shorthandRegex, function (m, r, g, b) {
    return r + r + g + g + b + b;
  });
  var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
  return result ? [parseInt(result[1], 16), parseInt(result[2], 16), parseInt(result[3], 16)] : null;
}
function hexToHsl(hex) {
  return rgbToHsl.apply(void 0, _toConsumableArray(hexToRgb(hex)));
}

},{}],3:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = void 0;
var _helpers = require("./helpers");
/* jshint esnext:true */

var layout = function layout(api, $) {
  'use strict';

  api('vamtam_theme[full-width-header]', function (value) {
    value.bind(function (to) {
      $('.header-maybe-limit-wrapper').toggleClass('limit-wrapper', to);
    });
  });
};
var _default = exports["default"] = layout;

},{"./helpers":2}],4:[function(require,module,exports){
"use strict";

var _general = _interopRequireDefault(require("./general"));
var _layout = _interopRequireDefault(require("./layout"));
var _styles = _interopRequireDefault(require("./styles"));
function _interopRequireDefault(e) { return e && e.__esModule ? e : { "default": e }; }
/* jshint esnext:true */

(function ($, undefined) {
  'use strict';

  (0, _general["default"])(wp.customize, $);
  (0, _layout["default"])(wp.customize, $);
  (0, _styles["default"])(wp.customize, $);
})(jQuery);

},{"./general":1,"./layout":3,"./styles":5}],5:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = void 0;
var _helpers = require("./helpers");
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); } /* jshint esnext:true */
var styles = function styles(api, $) {
  'use strict';

  var prepare_background = function prepare_background(to) {
    if (to['background-image'] !== '') {
      to['background-image'] = 'url(' + to['background-image'] + ')';
    }
    return to;
  };
  {
    var compiler_options = VAMTAM_CUSTOMIZE_PREVIEW.compiler_options;
    var real_id = function real_id(id) {
      return id.replace(/vamtam_theme\[([^\]]+)]/, '$1');
    };
    var change_handler_by_type = {
      number: function number(to) {
        var id = real_id(this.id);
        if (VAMTAM_CUSTOMIZE_PREVIEW.percentages.indexOf(id) !== -1) {
          to += '%';
        } else if (VAMTAM_CUSTOMIZE_PREVIEW.numbers.indexOf(id) !== -1) {
          // as is
        } else {
          to += 'px';
        }
        document.documentElement.style.setProperty("--vamtam-".concat(id), to);

        // trigger a resize event if we change any dimension
        $(window).resize();
      },
      background: function background(to) {
        var id = real_id(this.id);
        to = prepare_background(to);
        var noCssVarProps = ['background-gradient', 'background-type', 'background-image-attachment', 'customizer-dummy'];
        for (var prop in to) {
          if (!noCssVarProps.includes(prop)) {
            document.documentElement.style.setProperty("--vamtam-".concat(id, "-").concat(prop), to[prop]);
          }
        }

        // We generate the background-generated string that is used for the bg.
        if (to['background-type'] === 'gradient') {
          var gradient = to['background-gradient'];
          if (gradient) {
            // Gradient bg.
            var generated = "".concat(gradient.type, "-gradient(").concat(gradient.type === 'linear' ? gradient.angle + 'deg' : 'at ' + gradient.position, ", ").concat(gradient['color-1'], " ").concat(gradient['location-1'], "%, ").concat(gradient['color-2'], " ").concat(gradient['location-2'], "%)");
            document.documentElement.style.setProperty("--vamtam-".concat(id, "-background-generated"), generated);
          }
        } else {
          // Default bg.
          var _generated = "".concat(to['background-image'], " ").concat(to['background-position'], "/").concat(to['background-size'], " ").concat(to['background-repeat'], " ").concat(to['background-attachment'], " ").concat(to['background-color']);
          document.documentElement.style.setProperty("--vamtam-".concat(id, "-background-generated"), _generated);
        }
      },
      radio: function radio(to) {
        var id = real_id(this.id);
        if ((0, _helpers.isNumeric)(to)) {
          change_handler_by_type.number.call(this, to);
        } else {
          document.documentElement.style.setProperty("--vamtam-".concat(id), to);
        }
      },
      select: function select(to) {
        var id = real_id(this.id);
        change_handler_by_type.radio.call(this, to);
      },
      typography: function typography(to, from) {
        var id = real_id(this.id);
        var noCssVarProps = ['customizer-dummy'];
        var _loop = function _loop(prop) {
            if (!noCssVarProps.includes(prop)) {
              if (_typeof(to[prop]) === 'object' && to[prop] !== null) {
                ['desktop', 'tablet', 'phone'].forEach(function (device) {
                  if (to[prop].hasOwnProperty(device)) {
                    if (to[prop].hasOwnProperty('unit')) {
                      document.documentElement.style.setProperty("--vamtam-".concat(id, "-").concat(prop, "-").concat(device), to[prop][device] + to[prop]['unit'][device]);
                    } else {
                      document.documentElement.style.setProperty("--vamtam-".concat(id, "-").concat(prop, "-").concat(device), to[prop][device]);
                    }
                  }
                });
              } else {
                validScalarProps = ['font-family', 'font-weight', 'font-style', 'color', 'transform', 'decoration'];
                if (!validScalarProps.includes(prop)) {
                  return 1; // continue
                  // Avoid adding irrelevant css vars to the cssom.
                }
                if (prop === 'transform' || prop === 'decoration') {
                  document.documentElement.style.setProperty("--vamtam-".concat(id, "-").concat(prop), to[prop] ? to[prop] : 'none');
                } else {
                  document.documentElement.style.setProperty("--vamtam-".concat(id, "-").concat(prop), to[prop] ? to[prop] : 'unset');
                }
              }
            }
          },
          validScalarProps;
        for (var prop in to) {
          if (_loop(prop)) continue;
        }

        // if the font-family is changed - we need to load the new font stylesheet
        if (to['font-family'] !== from['font-family'] || to['variant'] !== from['variant']) {
          var new_font = window.top.VAMTAM_ALL_FONTS[to['font-family']];
          if (new_font && new_font.gf) {
            var family = encodeURIComponent(to['font-family']) + ':' + new_font.weights.join(',').replace(' ', '');
            var link = document.createElement("link");
            link.href = 'https://fonts.googleapis.com/css?family=' + family;
            link.type = 'text/css';
            link.rel = 'stylesheet';
            document.getElementsByTagName('head')[0].appendChild(link);
          }
        }
      },
      'color-row': function colorRow(to) {
        var id = real_id(this.id);
        var noCssVarProps = ['customizer-dummy'];
        for (var prop in to) {
          if (!noCssVarProps.includes(prop)) {
            document.documentElement.style.setProperty("--vamtam-".concat(id, "-").concat(prop), to[prop]);
          }
        }
        if (id === 'accent-color') {
          // accents need readable colors
          for (var i = 1; i <= 8; i++) {
            var hex = to[i];
            var hsl = (0, _helpers.hexToHsl)(hex);
            var readable = '';
            var hc = '';
            if (hsl[2] > 80) {
              readable = "hsl(".concat(hsl[0], ", ").concat(hsl[1], "%, ").concat(Math.max(0, hsl[2] - 50), "%)"); //  $color->darken( 50 );
              hc = '#000000';
            } else {
              readable = "hsl(".concat(hsl[0], ", ").concat(hsl[1], "%, ").concat(Math.min(0, hsl[2] + 50), "%)"); //  $color->lighten( 50 );
              hc = '#ffffff';
            }
            document.documentElement.style.setProperty("--vamtam-accent-color-".concat(i, "-readable"), readable);
            document.documentElement.style.setProperty("--vamtam-accent-color-".concat(i, "-hc"), hc);
            document.documentElement.style.setProperty("--vamtam-accent-color-".concat(i, "-transparent"), "hsl(".concat(hsl[0], ", ").concat(hsl[1], "%, ").concat(hsl[2], "%, 0)"));
          }
        }
      },
      color: function color(to) {
        var id = real_id(this.id);
        document.documentElement.style.setProperty("--vamtam-".concat(id), to);
      },
      'responsive-box-fields': function responsiveBoxFields(to) {
        var id = real_id(this.id);
        ['desktop', 'tablet', 'phone'].forEach(function (device) {
          ['top', 'right', 'bottom', 'left'].forEach(function (side) {
            document.documentElement.style.setProperty("--vamtam-".concat(id, "-").concat(side, "-").concat(device), to[side][device] + to['unit'][device]);
          });
        });
      }
    };

    // const compiler_option_handler = ;
    var _loop2 = function _loop2(opt_name) {
      api(opt_name, function (setting) {
        var type = compiler_options[opt_name];
        if (type in change_handler_by_type) {
          setting.bind(change_handler_by_type[type]);
        } else {
          console.error("VamTam Customzier: Missing handler for option type ".concat(type, " - option ").concat(opt_name));
          window.wpvval = setting;
        }
      });
    };
    for (var opt_name in compiler_options) {
      _loop2(opt_name);
    }
  }
};
var _default = exports["default"] = styles;

},{"./helpers":2}]},{},[4]);
