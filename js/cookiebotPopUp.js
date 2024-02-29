(function webpackUniversalModuleDefinition(root, factory) {
  if (typeof exports === "object" && typeof module === "object")
    module.exports = factory();
  else if (typeof define === "function" && define.amd) define([], factory);
  else {
    var a = factory();
    for (var i in a) (typeof exports === "object" ? exports : root)[i] = a[i];
  }
})(this, () => {
  return /******/ (() => {
    // webpackBootstrap
    /******/ var __webpack_modules__ = {
      /***/ 749: /***/ function (module) {
        !(function (e, t) {
          true ? (module.exports = t()) : 0;
        })(this, function () {
          return (function (e) {
            var t = {};
            function o(n) {
              if (t[n]) return t[n].exports;
              var r = (t[n] = { i: n, l: !1, exports: {} });
              return (
                e[n].call(r.exports, r, r.exports, o), (r.l = !0), r.exports
              );
            }
            return (
              (o.m = e),
              (o.c = t),
              (o.d = function (e, t, n) {
                o.o(e, t) ||
                  Object.defineProperty(e, t, { enumerable: !0, get: n });
              }),
              (o.r = function (e) {
                "undefined" != typeof Symbol &&
                  Symbol.toStringTag &&
                  Object.defineProperty(e, Symbol.toStringTag, {
                    value: "Module",
                  }),
                  Object.defineProperty(e, "__esModule", { value: !0 });
              }),
              (o.t = function (e, t) {
                if ((1 & t && (e = o(e)), 8 & t)) return e;
                if (4 & t && "object" == typeof e && e && e.__esModule)
                  return e;
                var n = Object.create(null);
                if (
                  (o.r(n),
                  Object.defineProperty(n, "default", {
                    enumerable: !0,
                    value: e,
                  }),
                  2 & t && "string" != typeof e)
                )
                  for (var r in e)
                    o.d(
                      n,
                      r,
                      function (t) {
                        return e[t];
                      }.bind(null, r)
                    );
                return n;
              }),
              (o.n = function (e) {
                var t =
                  e && e.__esModule
                    ? function () {
                        return e.default;
                      }
                    : function () {
                        return e;
                      };
                return o.d(t, "a", t), t;
              }),
              (o.o = function (e, t) {
                return Object.prototype.hasOwnProperty.call(e, t);
              }),
              (o.p = ""),
              o((o.s = 0))
            );
          })([
            function (e, t, o) {
              "use strict";
              Object.defineProperty(t, "__esModule", { value: !0 }),
                (t.default = function (e, t) {
                  if (!e || !e.id) return;
                  t || (t = {});
                  void 0 === t.changeHash && (t.changeHash = !0);
                  void 0 === t.enableEscape && (t.enableEscape = !0);
                  let o,
                    n,
                    l,
                    a,
                    s,
                    c = [];
                  const d = new r.default(e),
                    u = () =>
                      [].slice
                        .call(e.parentNode.childNodes)
                        .filter((t) => 1 === t.nodeType && t !== e),
                    f = function () {
                      let r =
                        !(arguments.length > 0 && void 0 !== arguments[0]) ||
                        arguments[0];
                      r &&
                        !1 !== t.changeHash &&
                        (history.pushState(null, null, "#" + e.id),
                        (n = "#" + e.id)),
                        (s = document.querySelector(
                          "body > [data-gent-modal]"
                        )),
                        s
                          ? document.body.replaceChild(e, s)
                          : document.body.appendChild(e),
                        e.classList.add("visible"),
                        e.setAttribute("aria-hidden", "false");
                      const l = e.dataset.scrollable;
                      (0, i.disableBodyScroll)(l ? e.querySelector(l) : e, {
                        allowTouchMove: () => !0,
                      });
                      const a = u();
                      a.forEach((e) => e.setAttribute("aria-hidden", !0)),
                        document.addEventListener("keydown", v),
                        o && o.setAttribute("aria-expanded", "true"),
                        requestAnimationFrame(() =>
                          requestAnimationFrame(() => e.focus())
                        );
                    },
                    h = () => {
                      if (
                        (u().forEach((e) => e.setAttribute("aria-hidden", !1)),
                        e.classList.remove("visible"),
                        e.setAttribute("aria-hidden", "true"),
                        s)
                      ) {
                        e.parentNode.replaceChild(s, e),
                          (0, i.enableBodyScroll)(e);
                        const t = s.dataset.scrollable;
                        (0, i.disableBodyScroll)(t ? s.querySelector(t) : s, {
                          allowTouchMove: !0,
                        });
                      } else
                        (0, i.clearAllBodyScrollLocks)(),
                          document.removeEventListener("keydown", v);
                      a.insertBefore(e, l),
                        o &&
                          (o.setAttribute("aria-expanded", "false"),
                          location.hash || o.focus());
                    },
                    v = (e) => {
                      if (!d || !d.hasFocusables || !e) return;
                      switch (e.keyCode || e.which) {
                        case 9:
                          e.shiftKey ? d.back(e) : d.next(e);
                          break;
                        case 27:
                          t.enableEscape && (e.preventDefault(), p());
                      }
                    },
                    p = () => {
                      t.changeHash ? history.back() : h();
                    },
                    b = () => {
                      let e;
                      window.addEventListener("resize", () => {
                        clearTimeout(e),
                          (e = setTimeout(() => t.resizeEvent(f, h), 250));
                      });
                    },
                    m = () => {
                      window.addEventListener("hashchange", () => {
                        n === "#" + e.id && h(),
                          (n = window.location.hash),
                          n === "#" + e.id && f(!1);
                      }),
                        n === "#" + e.id &&
                          (history.replaceState(
                            null,
                            null,
                            window.location.href.split("#")[0]
                          ),
                          f());
                    };
                  return (
                    (() => {
                      if (
                        ((c = document.querySelectorAll(
                          `[aria-controls="${e.id}"], [href="#${e.id}"]`
                        )),
                        (l = e.nextElementSibling),
                        (a = e.parentElement),
                        !t.changeHash && 0 === c.length)
                      )
                        return;
                      e.setAttribute("tabindex", "-1"),
                        e.setAttribute("aria-hidden", "true"),
                        e.setAttribute("data-gent-modal", "true");
                      const r = (e) => {
                        (o = e.currentTarget),
                          o.hasAttribute("aria-controls") && f();
                      };
                      for (let e = c.length; e--; )
                        c[e].setAttribute("aria-expanded", "false"),
                          c[e].addEventListener("click", r);
                      const i = e.querySelectorAll(
                        t.closeBtns || ".modal-close"
                      );
                      for (let t = i.length; t--; )
                        (i[t].dataset.target && i[t].dataset.target !== e.id) ||
                          i[t].addEventListener("click", p);
                      (n = window.location.hash), t.changeHash && m();
                      t.resizeEvent && (t.resizeEvent(f, h), b());
                    })(),
                    { close: p, open: f }
                  );
                });
              var n,
                r = (n = o(1)) && n.__esModule ? n : { default: n },
                i = o(2);
              e.exports = t.default;
            },
            function (e, t, o) {
              "use strict";
              Object.defineProperty(t, "__esModule", { value: !0 }),
                (t.default = void 0);
              var n = function (e) {
                let t = o(e);
                function o(e) {
                  let t = e.querySelectorAll(
                    'a[href], area[href], input:not([disabled]):not([hidden]), select:not([disabled]), textarea:not([disabled]), button:not([disabled]), [tabindex="0"]'
                  );
                  return Array.prototype.slice.call(t).filter(n);
                }
                function n(e) {
                  return (
                    e.offsetWidth > 0 ||
                    e.offsetHeight > 0 ||
                    e.getClientRects().length > 0
                  );
                }
                (this.setFocusables = function () {
                  t = o(e);
                }),
                  (this.next = function (e) {
                    this.setFocusables();
                    let o = document.activeElement;
                    o &&
                      o === t[t.length - 1] &&
                      (t[0].focus(), e.preventDefault());
                  }),
                  (this.back = function (e) {
                    this.setFocusables();
                    let o = document.activeElement;
                    o &&
                      o === t[0] &&
                      (t[t.length - 1].focus(), e.preventDefault());
                  }),
                  (this.home = function () {
                    this.setFocusables(), t[0].focus();
                  }),
                  (this.end = function () {
                    this.setFocusables(), t[t.length - 1].focus();
                  }),
                  (this.hasFocusables = t && t.length > 0);
              };
              (t.default = n), (e.exports = t.default);
            },
            function (e, t, o) {
              var n, r, i;
              (r = [t]),
                void 0 ===
                  (i =
                    "function" ==
                    typeof (n = function (e) {
                      "use strict";
                      function t(e) {
                        if (Array.isArray(e)) {
                          for (
                            var t = 0, o = Array(e.length);
                            t < e.length;
                            t++
                          )
                            o[t] = e[t];
                          return o;
                        }
                        return Array.from(e);
                      }
                      Object.defineProperty(e, "__esModule", { value: !0 });
                      var o = !1;
                      if ("undefined" != typeof window) {
                        var n = {
                          get passive() {
                            o = !0;
                          },
                        };
                        window.addEventListener("testPassive", null, n),
                          window.removeEventListener("testPassive", null, n);
                      }
                      function r(e) {
                        return s.some(function (t) {
                          return !(
                            !t.options.allowTouchMove ||
                            !t.options.allowTouchMove(e)
                          );
                        });
                      }
                      function i(e) {
                        var t = e || window.event;
                        return (
                          !!r(t.target) ||
                          1 < t.touches.length ||
                          (t.preventDefault && t.preventDefault(), !1)
                        );
                      }
                      function l() {
                        setTimeout(function () {
                          void 0 !== f &&
                            ((document.body.style.paddingRight = f),
                            (f = void 0)),
                            void 0 !== u &&
                              ((document.body.style.overflow = u),
                              (u = void 0));
                        });
                      }
                      var a =
                          "undefined" != typeof window &&
                          window.navigator &&
                          window.navigator.platform &&
                          (/iP(ad|hone|od)/.test(window.navigator.platform) ||
                            ("MacIntel" === window.navigator.platform &&
                              1 < window.navigator.maxTouchPoints)),
                        s = [],
                        c = !1,
                        d = -1,
                        u = void 0,
                        f = void 0;
                      (e.disableBodyScroll = function (e, n) {
                        if (a) {
                          if (!e)
                            return void console.error(
                              "disableBodyScroll unsuccessful - targetElement must be provided when calling disableBodyScroll on IOS devices."
                            );
                          if (
                            e &&
                            !s.some(function (t) {
                              return t.targetElement === e;
                            })
                          ) {
                            var l = { targetElement: e, options: n || {} };
                            (s = [].concat(t(s), [l])),
                              (e.ontouchstart = function (e) {
                                1 === e.targetTouches.length &&
                                  (d = e.targetTouches[0].clientY);
                              }),
                              (e.ontouchmove = function (t) {
                                var o, n, l, a;
                                1 === t.targetTouches.length &&
                                  ((n = e),
                                  (a = (o = t).targetTouches[0].clientY - d),
                                  r(o.target) ||
                                    ((n && 0 === n.scrollTop && 0 < a) ||
                                    ((l = n) &&
                                      l.scrollHeight - l.scrollTop <=
                                        l.clientHeight &&
                                      a < 0)
                                      ? i(o)
                                      : o.stopPropagation()));
                              }),
                              c ||
                                (document.addEventListener(
                                  "touchmove",
                                  i,
                                  o ? { passive: !1 } : void 0
                                ),
                                (c = !0));
                          }
                        } else {
                          (v = n),
                            setTimeout(function () {
                              if (void 0 === f) {
                                var e = !!v && !0 === v.reserveScrollBarGap,
                                  t =
                                    window.innerWidth -
                                    document.documentElement.clientWidth;
                                e &&
                                  0 < t &&
                                  ((f = document.body.style.paddingRight),
                                  (document.body.style.paddingRight =
                                    t + "px"));
                              }
                              void 0 === u &&
                                ((u = document.body.style.overflow),
                                (document.body.style.overflow = "hidden"));
                            });
                          var h = { targetElement: e, options: n || {} };
                          s = [].concat(t(s), [h]);
                        }
                        var v;
                      }),
                        (e.clearAllBodyScrollLocks = function () {
                          a
                            ? (s.forEach(function (e) {
                                (e.targetElement.ontouchstart = null),
                                  (e.targetElement.ontouchmove = null);
                              }),
                              c &&
                                (document.removeEventListener(
                                  "touchmove",
                                  i,
                                  o ? { passive: !1 } : void 0
                                ),
                                (c = !1)),
                              (s = []),
                              (d = -1))
                            : (l(), (s = []));
                        }),
                        (e.enableBodyScroll = function (e) {
                          if (a) {
                            if (!e)
                              return void console.error(
                                "enableBodyScroll unsuccessful - targetElement must be provided when calling enableBodyScroll on IOS devices."
                              );
                            (e.ontouchstart = null),
                              (e.ontouchmove = null),
                              (s = s.filter(function (t) {
                                return t.targetElement !== e;
                              })),
                              c &&
                                0 === s.length &&
                                (document.removeEventListener(
                                  "touchmove",
                                  i,
                                  o ? { passive: !1 } : void 0
                                ),
                                (c = !1));
                          } else
                            (s = s.filter(function (t) {
                              return t.targetElement !== e;
                            })).length || l();
                        });
                    })
                      ? n.apply(t, r)
                      : n) || (e.exports = i);
            },
          ]);
        });
        //# sourceMappingURL=index.js.map

        /***/
      },

      /***/ 65: /***/ (module, exports) => {
        "use strict";

        Object.defineProperty(exports, "__esModule", {
          value: true,
        });
        exports["default"] = _default;
        function _default(elem, options) {
          var expandedContent = [];

          /**
           * Override default options with options param.
           */
          options = (function () {
            var defaults = {
              expand: function expand(button, content) {
                content.style.maxHeight = "".concat(content.scrollHeight, "px");
              },
              collapse: function collapse(button, content) {
                content.style.maxHeight = 0;
              },
              transitionEnd: function transitionEnd(e) {
                if (e.propertyName !== "max-height") {
                  return;
                }
                if (
                  !e.currentTarget.classList.contains(
                    "SG-CookieConsent--accordion--expanded"
                  )
                ) {
                  e.currentTarget.setAttribute("hidden", "true");
                }
              },
              resizeEvent: function resizeEvent(e, expandedContent) {
                for (var i = expandedContent.length; i--; ) {
                  options.expand(null, expandedContent[i]);
                }
              },
              init: true,
              buttonSelector: ".SG-CookieConsent--accordion--button",
              accordionExpandedClass: "SG-CookieConsent--accordion--expanded",
              accordionOpenClass: "SG-CookieConsent--accordion--button--open",
            };
            var keys = Object.keys(defaults);
            options = options || {};
            for (var i = keys.length; i--; ) {
              options[keys[i]] = options[keys[i]] || defaults[keys[i]];
            }
            return options;
          })();
          var buttons = elem.querySelectorAll(options.buttonSelector);

          /**
           * Toggle aria-expanded attributes and trigger visibility Change function.
           *
           * @param {event} e The triggered event.
           */
          var toggle = function toggle(e) {
            e.preventDefault();
            var button = e.currentTarget;
            button.setAttribute(
              "aria-expanded",
              button.getAttribute("aria-expanded") === "true" ? "false" : "true"
            );
            setVisibility(button);
          };

          /**
           * Handle keyboard input: arrows, home & end.
           *
           * @param {event} e The triggered event.
           */
          var keyDown = function keyDown(e) {
            var keyCode = e.keyCode || e.which;
            var current = (function () {
              for (var i = buttons.length; i--; ) {
                if (buttons[i] === e.currentTarget) {
                  return i;
                }
              }
            })();
            switch (keyCode) {
              case 40:
                // arrow down
                e.preventDefault();
                if (current === buttons.length - 1) {
                  current = -1;
                }
                buttons[current + 1].focus();
                break;
              case 38:
                // arrow up
                e.preventDefault();
                if (current === 0) {
                  current = buttons.length;
                }
                buttons[current - 1].focus();
                break;
              case 36:
                // home
                e.preventDefault();
                buttons[0].focus();
                break;
              case 35:
                // end
                e.preventDefault();
                buttons[buttons.length - 1].focus();
                break;
            }
          };

          /**
           * Add events to buttons.
           * Listen for animationEnd on accordionContent.
           */
          var addEvents = function addEvents() {
            var onResize = function onResize(e) {
              options.resizeEvent(e, expandedContent);
            };
            for (var i = buttons.length; i--; ) {
              var button = buttons[i];
              button.addEventListener("click", toggle);
              button.addEventListener("keydown", keyDown);
              var accordionContent = elem.querySelector(
                "#".concat(button.getAttribute("aria-controls"))
              );
              accordionContent.addEventListener(
                "transitionend",
                options.transitionEnd
              );
              if (options.resizeEvent) {
                window.addEventListener("resize", onResize);
              }
            }
          };

          /**
           * Hide or show the accordion content.
           *
           * @param {Object} button  The accordion button.
           * @param {boolean|false} isInitial  True if this is the first run triggered
           *   by init().
           */
          var setVisibility = function setVisibility(button, isInitial) {
            var accordionContent = elem.querySelector(
              "#".concat(button.getAttribute("aria-controls"))
            );
            if (!accordionContent) {
              return;
            }
            if (button.getAttribute("aria-expanded") === "true") {
              button.classList.add(options.accordionOpenClass);
              accordionContent.classList.add(options.accordionExpandedClass);
              accordionContent.setAttribute("aria-hidden", "false");
              accordionContent.removeAttribute("hidden");
              expandedContent.push(accordionContent);
              options.expand(button, accordionContent);
            } else {
              button.classList.remove(options.accordionOpenClass);
              accordionContent.classList.remove(options.accordionExpandedClass);
              accordionContent.setAttribute("aria-hidden", "true");
              if (isInitial) {
                accordionContent.setAttribute("hidden", "true");
              }
              expandedContent.filter(function (content) {
                return content !== accordionContent;
              });
              options.collapse(button, accordionContent);
            }
          };

          /**
           * Set all attributes and toggle visibility accordingly.
           */
          var setInitial = function setInitial() {
            for (var i = buttons.length; i--; ) {
              setVisibility(buttons[i], true);
            }
          };

          /**
           * Closes all accordion items.
           */
          var closeAll = function closeAll() {
            for (var i = buttons.length; i--; ) {
              buttons[i].setAttribute("aria-expanded", "false");
              setVisibility(buttons[i]);
            }
          };

          /**
           * Opens all accordion items.
           */
          var openAll = function openAll() {
            for (var i = buttons.length; i--; ) {
              buttons[i].setAttribute("aria-expanded", "true");
              setVisibility(buttons[i]);
            }
          };

          /**
           * Enable accordion functionality.
           */
          var init = function init() {
            setInitial();
            addEvents();
          };
          if (options.init !== false) {
            init();
          }
          return {
            init: init,
            closeAll: closeAll,
            openAll: openAll,
          };
        }
        module.exports = exports.default;

        /***/
      },

      /******/
    };
    /************************************************************************/
    /******/ // The module cache
    /******/ var __webpack_module_cache__ = {};
    /******/
    /******/ // The require function
    /******/ function __webpack_require__(moduleId) {
      /******/ // Check if module is in cache
      /******/ var cachedModule = __webpack_module_cache__[moduleId];
      /******/ if (cachedModule !== undefined) {
        /******/ return cachedModule.exports;
        /******/
      }
      /******/ // Create a new module (and put it into the cache)
      /******/ var module = (__webpack_module_cache__[moduleId] = {
        /******/ // no module.id needed
        /******/ // no module.loaded needed
        /******/ exports: {},
        /******/
      });
      /******/
      /******/ // Execute the module function
      /******/ __webpack_modules__[moduleId].call(
        module.exports,
        module,
        module.exports,
        __webpack_require__
      );
      /******/
      /******/ // Return the exports of the module
      /******/ return module.exports;
      /******/
    }
    /******/
    /************************************************************************/
    var __webpack_exports__ = {};
    // This entry need to be wrapped in an IIFE because it need to be in strict mode.
    (() => {
      "use strict";
      var exports = __webpack_exports__;

      Object.defineProperty(exports, "__esModule", {
        value: true,
      });
      exports.SG_Cookieconsent__togglePreferences =
        SG_Cookieconsent__togglePreferences;
      exports.closeStadGentCookieConsent = closeStadGentCookieConsent;
      exports.openStadGentCookieConsent = openStadGentCookieConsent;
      var _modal = _interopRequireDefault(__webpack_require__(749));
      var _Accordion = _interopRequireDefault(__webpack_require__(65));
      function _interopRequireDefault(obj) {
        return obj && obj.__esModule ? obj : { default: obj };
      }
      /**
       * Cookie Consent Popup script.
       */

      var acceptButton = document.getElementById(
        "SG-CookieConsent--AcceptButton"
      );
      var declineButton = document.getElementById(
        "SG-CookieConsent--DeclineButton"
      );
      var preferencesToggleButton = document.getElementById(
        "SG-CookieConsent--TogglePreferencesButton"
      );
      var preferencesCheckboxes = document.querySelectorAll(
        '.SG-CookieConsent--checkbox input[type="checkbox"]'
      );
      var preferencesSaveButton = document.getElementById(
        "SG-CookieConsent--SavePreferencesButton"
      );

      /**
       * Create the Cookie Consent modal instance.
       */
      var SG_CookieConsent = new _modal["default"](
        document.getElementById("SG-CookieConsent"),
        {
          changeHash: false,
          enableEscape: false,
        }
      );

      /**
       * Toggle all preference checkboxes except the first (for necessary cookies).
       */
      function SG_Cookieconsent__togglePreferences() {
        var checked =
          arguments.length > 0 && arguments[0] !== undefined
            ? arguments[0]
            : true;
        for (var i = preferencesCheckboxes.length - 1; i--; ) {
          preferencesCheckboxes[i + 1].checked = checked;
        }
      }
      /**
       * Create the Cookie Consent accordion instances.
       */
      window.addEventListener("CookiebotOnLoad", function () {
        new _Accordion[
          "default"
        ](document.getElementById("SG-CookieConsent--preferences"), {
          expand: function expand(button, content) {
            // Move the accept button.
            preferencesSaveButton.insertAdjacentElement(
              "afterend",
              acceptButton
            );
            acceptButton.classList.add("SG-CookieConsent--button-secondary");
            acceptButton.classList.remove("SG-CookieConsent--button-primary");
            if (document.documentMode) {
              content.style.maxHeight = "none";
              return;
            }
            content.style.maxHeight = "".concat(content.scrollHeight, "px");
          },
        });

        /**
         * Enable all checkboxes and save the preferences on Accept all.
         */
        acceptButton.addEventListener("click", function (e) {
          e.preventDefault();
          SG_Cookieconsent__togglePreferences(true);
          Cookiebot.dialog.submitConsent();
        });

        /**
         * Disable all checkboxes and save the preferences on Decline all.
         */
        declineButton.addEventListener("click", function (e) {
          e.preventDefault();
          SG_Cookieconsent__togglePreferences(false);
          Cookiebot.dialog.submitConsent();
        });

        /**
         * Enable/Disable the accept all button on preferences closed/open.
         */
        preferencesToggleButton.addEventListener("click", function () {
          preferencesToggleButton.disabled === true
            ? (preferencesToggleButton.disabled = false)
            : (preferencesToggleButton.disabled = true);
          declineButton.disabled === true
            ? (declineButton.disabled = false)
            : (declineButton.disabled = true);
        });

        /**
         * Save the custom preferences.
         */
        preferencesSaveButton.addEventListener("click", function (e) {
          e.preventDefault();
          Cookiebot.dialog.submitConsent();
        });
      });
      /**
       * Close the Consent modal when All are accepted or Preferences are saved.
       */
      window.addEventListener(
        "CookiebotOnAccept",
        function () {
          SG_CookieConsent.close();

          // Force page reload to make sure that the proper javascript is loaded and
          // executed.
          location.reload();
        },
        false
      );

      /**
       * Close the Consent modal when not preferences were selected.
       */
      window.addEventListener(
        "CookiebotOnDecline",
        function () {
          SG_CookieConsent.close();

          // Force page reload to make sure that the proper javascript is loaded and
          // executed.
          location.reload();
        },
        false
      );

      /**
       * Callback for Cookiebot to open the Cookie Consent Modal.
       */
      function openStadGentCookieConsent() {
        SG_CookieConsent.open(false);
      }

      /**
       * Callback for Cookiebot to close the Cookie Consent Modal.
       */
      function closeStadGentCookieConsent() {
        // This will do nothing since the close event is already handled by the
        // custom code. This placeholder is here since Cookiebot requires it.
      }
    })();

    /******/ return __webpack_exports__;
    /******/
  })();
});
//# sourceMappingURL=dialog.js.map
