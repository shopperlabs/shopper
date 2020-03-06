/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/notiflix-react/dist/notiflix-react-aio-1.4.0.js":
/*!**********************************************************************!*\
  !*** ./node_modules/notiflix-react/dist/notiflix-react-aio-1.4.0.js ***!
  \**********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/*!
* Notiflix React ('https://www.notiflix.com/react')
* Version: 1.4.0
* Author: Furkan MT ('https://github.com/furcan')
* Copyright 2019 Notiflix, MIT Licence ('https://opensource.org/licenses/MIT')
*/

// Internal CSS Codes on
const notiflixInternalCSSCodes = () => {

    const css = `[id^=NotiflixNotifyWrap]{position:fixed;z-index:1001;opacity:1;right:10px;top:10px;width:280px;max-width:96%;box-sizing:border-box;background:0 0}[id^=NotiflixNotifyWrap] *{box-sizing:border-box}[id^=NotiflixNotifyWrap]>div{-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;font-family:Quicksand,sans-serif;width:100%;display:inline-block;position:relative;margin:0 0 10px;border-radius:5px;background:#1e1e1e;color:#fff;padding:10px 12px;font-size:14px;line-height:1.4}[id^=NotiflixNotifyWrap]>div:last-child{margin:0}[id^=NotiflixNotifyWrap]>div.with-callback{cursor:pointer}[id^=NotiflixNotifyWrap] ::selection{background:inherit}[id^=NotiflixNotifyWrap]>div.with-icon{padding:8px}[id^=NotiflixNotifyWrap]>div.with-close{padding:10px 30px 10px 12px}[id^=NotiflixNotifyWrap]>div.with-icon.with-close{padding:6px 30px 6px 6px}[id^=NotiflixNotifyWrap]>div>span.the-message{font-weight:500;font-family:inherit!important;word-break:break-all;word-break:break-word}[id^=NotiflixNotifyWrap]>div>span.click-to-close{cursor:pointer;transition:all .2s ease-in-out;position:absolute;right:8px;top:0;bottom:0;margin:auto;color:inherit;width:16px;height:16px}[id^=NotiflixNotifyWrap]>div>span.click-to-close:hover{transform:rotate(90deg)}[id^=NotiflixNotifyWrap]>div>span.click-to-close>svg{position:absolute;width:16px;height:16px;right:0;top:0}[id^=NotiflixNotifyWrap]>div>.nmi{position:absolute;width:40px;height:40px;font-size:30px;line-height:40px;text-align:center;left:8px;top:0;bottom:0;margin:auto;border-radius:inherit}[id^=NotiflixNotifyWrap]>div>.wfa.shadow{color:inherit;background:rgba(0,0,0,.15);box-shadow:inset 0 0 34px rgba(0,0,0,.2);text-shadow:0 0 10px rgba(0,0,0,.3)}[id^=NotiflixNotifyWrap]>div>span.with-icon{position:relative;float:left;width:calc(100% - 40px);margin:0 0 0 40px;padding:0 0 0 10px;box-sizing:border-box}[id^=NotiflixNotifyWrap]>div.rtl-on>.nmi{left:auto;right:8px}[id^=NotiflixNotifyWrap]>div.rtl-on>span.with-icon{padding:0 10px 0 0;margin:0 40px 0 0}[id^=NotiflixNotifyWrap]>div.rtl-on>span.click-to-close{right:auto;left:8px}[id^=NotiflixNotifyWrap]>div.with-icon.with-close.rtl-on{padding:6px 6px 6px 30px}[id^=NotiflixNotifyWrap]>div.with-close.rtl-on{padding:10px 12px 10px 30px}[id^=NotiflixNotifyOverlay].with-animation,[id^=NotiflixNotifyWrap]>div.with-animation.nx-fade{animation:notify-animation-fade .3s ease-in-out 0s normal;-webkit-animation:notify-animation-fade .3s ease-in-out 0s normal}@keyframes notify-animation-fade{0%{opacity:0}100%{opacity:1}}@-webkit-keyframes notify-animation-fade{0%{opacity:0}100%{opacity:1}}[id^=NotiflixNotifyWrap]>div.with-animation.nx-zoom{animation:notify-animation-zoom .3s ease-in-out 0s normal;-webkit-animation:notify-animation-zoom .3s ease-in-out 0s normal}@keyframes notify-animation-zoom{0%{transform:scale(0)}50%{transform:scale(1.05)}100%{transform:scale(1)}}@-webkit-keyframes notify-animation-zoom{0%{transform:scale(0)}50%{transform:scale(1.05)}100%{transform:scale(1)}}[id^=NotiflixNotifyWrap]>div.with-animation.nx-from-right{animation:notify-animation-from-right .3s ease-in-out 0s normal;-webkit-animation:notify-animation-from-right .3s ease-in-out 0s normal}@keyframes notify-animation-from-right{0%{right:-300px;opacity:0}50%{right:8px;opacity:1}100%{right:0;opacity:1}}@-webkit-keyframes notify-animation-from-right{0%{right:-300px;opacity:0}50%{right:8px;opacity:1}100%{right:0;opacity:1}}[id^=NotiflixNotifyWrap]>div.with-animation.nx-from-left{animation:notify-animation-from-left .3s ease-in-out 0s normal;-webkit-animation:notify-animation-from-left .3s ease-in-out 0s normal}@keyframes notify-animation-from-left{0%{left:-300px;opacity:0}50%{left:8px;opacity:1}100%{left:0;opacity:1}}@-webkit-keyframes notify-animation-from-left{0%{left:-300px;opacity:0}50%{left:8px;opacity:1}100%{left:0;opacity:1}}[id^=NotiflixNotifyWrap]>div.with-animation.nx-from-top{animation:notify-animation-from-top .3s ease-in-out 0s normal;-webkit-animation:notify-animation-from-top .3s ease-in-out 0s normal}@keyframes notify-animation-from-top{0%{top:-50px;opacity:0}50%{top:8px;opacity:1}100%{top:0;opacity:1}}@-webkit-keyframes notify-animation-from-top{0%{top:-50px;opacity:0}50%{top:8px;opacity:1}100%{top:0;opacity:1}}[id^=NotiflixNotifyWrap]>div.with-animation.nx-from-bottom{animation:notify-animation-from-bottom .3s ease-in-out 0s normal;-webkit-animation:notify-animation-from-bottom .3s ease-in-out 0s normal}@keyframes notify-animation-from-bottom{0%{bottom:-50px;opacity:0}50%{bottom:8px;opacity:1}100%{bottom:0;opacity:1}}@-webkit-keyframes notify-animation-from-bottom{0%{bottom:-50px;opacity:0}50%{bottom:8px;opacity:1}100%{bottom:0;opacity:1}}[id^=NotiflixNotifyOverlay].with-animation.remove,[id^=NotiflixNotifyWrap]>div.with-animation.nx-fade.remove{opacity:0;animation:notify-remove-fade .3s ease-in-out 0s normal;-webkit-animation:notify-remove-fade .3s ease-in-out 0s normal}@keyframes notify-remove-fade{0%{opacity:1}100%{opacity:0}}@-webkit-keyframes notify-remove-fade{0%{opacity:1}100%{opacity:0}}[id^=NotiflixNotifyWrap]>div.with-animation.nx-zoom.remove{transform:scale(0);animation:notify-remove-zoom .3s ease-in-out 0s normal;-webkit-animation:notify-remove-zoom .3s ease-in-out 0s normal}@keyframes notify-remove-zoom{0%{transform:scale(1)}50%{transform:scale(1.05)}100%{transform:scale(0)}}@-webkit-keyframes notify-remove-zoom{0%{transform:scale(1)}50%{transform:scale(1.05)}100%{transform:scale(0)}}[id^=NotiflixNotifyWrap]>div.with-animation.nx-from-top.remove{opacity:0;animation:notify-remove-to-top .3s ease-in-out 0s normal;-webkit-animation:notify-remove-to-top .3s ease-in-out 0s normal}@keyframes notify-remove-to-top{0%{top:0;opacity:1}50%{top:8px;opacity:1}100%{top:-50px;opacity:0}}@-webkit-keyframes notify-remove-to-top{0%{top:0;opacity:1}50%{top:8px;opacity:1}100%{top:-50px;opacity:0}}[id^=NotiflixNotifyWrap]>div.with-animation.nx-from-right.remove{opacity:0;animation:notify-remove-to-right .3s ease-in-out 0s normal;-webkit-animation:notify-remove-to-right .3s ease-in-out 0s normal}@keyframes notify-remove-to-right{0%{right:0;opacity:1}50%{right:8px;opacity:1}100%{right:-300px;opacity:0}}@-webkit-keyframes notify-remove-to-right{0%{right:0;opacity:1}50%{right:8px;opacity:1}100%{right:-300px;opacity:0}}[id^=NotiflixNotifyWrap]>div.with-animation.nx-from-bottom.remove{opacity:0;animation:notify-remove-to-bottom .3s ease-in-out 0s normal;-webkit-animation:notify-remove-to-bottom .3s ease-in-out 0s normal}@keyframes notify-remove-to-bottom{0%{bottom:0;opacity:1}50%{bottom:8px;opacity:1}100%{bottom:-50px;opacity:0}}@-webkit-keyframes notify-remove-to-bottom{0%{bottom:0;opacity:1}50%{bottom:8px;opacity:1}100%{bottom:-50px;opacity:0}}[id^=NotiflixNotifyWrap]>div.with-animation.nx-from-left.remove{opacity:0;animation:notify-remove-to-left .3s ease-in-out 0s normal;-webkit-animation:notify-remove-to-left .3s ease-in-out 0s normal}@keyframes notify-remove-to-left{0%{left:0;opacity:1}50%{left:8px;opacity:1}100%{left:-300px;opacity:0}}@-webkit-keyframes notify-remove-to-left{0%{left:0;opacity:1}50%{left:8px;opacity:1}100%{left:-300px;opacity:0}}[id^=NotiflixReportWrap]{position:fixed;z-index:1000;width:320px;max-width:96%;box-sizing:border-box;font-family:Quicksand,sans-serif;left:0;right:0;top:20px;color:#1e1e1e;border-radius:25px;background:0 0;margin:auto}[id^=NotiflixReportWrap] *{box-sizing:border-box}[id^=NotiflixReportWrap]>div[class*="-overlay"]{width:100%;height:100%;left:0;top:0;background:rgba(255,255,255,.5);position:fixed;z-index:0}[id^=NotiflixReportWrap]>div[class*="-content"]{width:100%;float:left;border-radius:inherit;padding:10px;filter:drop-shadow(0 0 5px rgba(0,0,0,.1));border:1px solid rgba(0,0,0,.03);background:#f8f8f8;position:relative;z-index:1}[id^=NotiflixReportWrap]>div[class*="-content"]>div[class$="-icon"]{-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;width:110px;height:110px;display:block;margin:6px auto 12px}[id^=NotiflixReportWrap]>div[class*="-content"]>div[class$="-icon"] svg{min-width:100%;max-width:100%;height:auto}[id^=NotiflixReportWrap]>*>h5{word-break:break-all;word-break:break-word;font-family:inherit!important;font-size:16px;font-weight:500;line-height:1.4;margin:0 0 10px;padding:0 0 10px;border-bottom:1px solid rgba(0,0,0,.1);float:left;width:100%;text-align:center}[id^=NotiflixReportWrap]>*>p{word-break:break-all;word-break:break-word;font-family:inherit!important;font-size:13px;line-height:1.4;float:left;width:100%;padding:0 10px;margin:0 0 10px}[id^=NotiflixReportWrap] a#NXReportButton{word-break:break-all;word-break:break-word;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;font-family:inherit!important;transition:all .25s ease-in-out;cursor:pointer;float:right;padding:7px 17px;background:#00b462;font-size:14px;line-height:1.4;font-weight:500;border-radius:inherit!important;color:#fff}[id^=NotiflixReportWrap] a#NXReportButton:hover{padding:7px 20px}[id^=NotiflixReportWrap].rtl-on a#NXReportButton{float:left}[id^=NotiflixReportWrap]>div[class*="-overlay"].with-animation{animation:report-overlay-animation .3s ease-in-out 0s normal;-webkit-animation:report-overlay-animation .3s ease-in-out 0s normal}@keyframes report-overlay-animation{0%{opacity:0}100%{opacity:1}}@-webkit-keyframes report-overlay-animation{0%{opacity:0}100%{opacity:1}}[id^=NotiflixReportWrap]>div[class*="-content"].with-animation.nx-fade{animation:report-animation-fade .3s ease-in-out 0s normal;-webkit-animation:report-animation-fade .3s ease-in-out 0s normal}@keyframes report-animation-fade{0%{opacity:0}100%{opacity:1}}@-webkit-keyframes report-animation-fade{0%{opacity:0}100%{opacity:1}}[id^=NotiflixReportWrap]>div[class*="-content"].with-animation.nx-zoom{animation:report-animation-zoom .3s ease-in-out 0s normal;-webkit-animation:report-animation-zoom .3s ease-in-out 0s normal}@keyframes report-animation-zoom{0%{opacity:0;transform:scale(.5)}50%{opacity:1;transform:scale(1.05)}100%{opacity:1;transform:scale(1)}}@-webkit-keyframes report-animation-zoom{0%{opacity:0;transform:scale(.5)}50%{opacity:1;transform:scale(1.05)}100%{opacity:1;transform:scale(1)}}[id^=NotiflixReportWrap].remove>div[class*="-overlay"].with-animation{opacity:0;animation:report-overlay-animation-remove .3s ease-in-out 0s normal;-webkit-animation:report-overlay-animation-remove .3s ease-in-out 0s normal}@keyframes report-overlay-animation-remove{0%{opacity:1}100%{opacity:0}}@-webkit-keyframes report-overlay-animation-remove{0%{opacity:1}100%{opacity:0}}[id^=NotiflixReportWrap].remove>div[class*="-content"].with-animation.nx-fade{opacity:0;animation:report-animation-fade-remove .3s ease-in-out 0s normal;-webkit-animation:report-animation-fade-remove .3s ease-in-out 0s normal}@keyframes report-animation-fade-remove{0%{opacity:1}100%{opacity:0}}@-webkit-keyframes report-animation-fade-remove{0%{opacity:1}100%{opacity:0}}[id^=NotiflixReportWrap].remove>div[class*="-content"].with-animation.nx-zoom{opacity:0;animation:report-animation-zoom-remove .3s ease-in-out 0s normal;-webkit-animation:report-animation-zoom-remove .3s ease-in-out 0s normal}@keyframes report-animation-zoom-remove{0%{opacity:1;transform:scale(1)}50%{opacity:.5;transform:scale(1.05)}100%{opacity:0;transform:scale(0)}}@-webkit-keyframes report-animation-zoom-remove{0%{opacity:1;transform:scale(1)}50%{opacity:.5;transform:scale(1.05)}100%{opacity:0;transform:scale(0)}}[id^=NotiflixLoadingWrap]{-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;position:fixed;z-index:1004;width:100%;height:100%;left:0;top:0;right:0;bottom:0;margin:auto;text-align:center;box-sizing:border-box;background:rgba(0,0,0,.8);font-family:Quicksand,sans-serif}[id^=NotiflixLoadingWrap] *{box-sizing:border-box}[id^=NotiflixLoadingWrap].click-to-close{cursor:pointer}[id^=NotiflixLoadingWrap]>div[class*="-icon"]{width:60px;height:60px;position:fixed;transition:top .2s ease-in-out;left:0;top:0;right:0;bottom:0;margin:auto}[id^=NotiflixLoadingWrap]>div[class*="-icon"] img,[id^=NotiflixLoadingWrap]>div[class*="-icon"] svg{max-width:unset;max-height:unset;width:100%;height:100%;position:absolute;left:0;top:0}[id^=NotiflixLoadingWrap]>div[class*="-icon"].with-message{top:-42px}[id^=NotiflixLoadingWrap]>p{position:fixed;left:0;right:0;top:42px;bottom:0;margin:auto;font-family:inherit!important;font-weight:500;line-height:1.4;padding:0 10px;width:100%;font-size:15px;height:18px}[id^=NotiflixLoadingWrap].with-animation{animation:loading-animation-fade .3s ease-in-out 0s normal;-webkit-animation:loading-animation-fade .3s ease-in-out 0s normal}@keyframes loading-animation-fade{0%{opacity:0}100%{opacity:1}}@-webkit-keyframes loading-animation-fade{0%{opacity:0}100%{opacity:1}}[id^=NotiflixLoadingWrap].with-animation.remove{opacity:0;animation:loading-animation-fade-remove .3s ease-in-out 0s normal;-webkit-animation:loading-animation-fade-remove .3s ease-in-out 0s normal}@keyframes loading-animation-fade-remove{0%{opacity:1}100%{opacity:0}}@-webkit-keyframes loading-animation-fade-remove{0%{opacity:1}100%{opacity:0}}[id^=NotiflixLoadingWrap]>p.new{animation:loading-new-message-fade .3s ease-in-out 0s normal;-webkit-animation:loading-new-message-fade .3s ease-in-out 0s normal}@keyframes loading-new-message-fade{0%{opacity:0}100%{opacity:1}}@-webkit-keyframes loading-new-message-fade{0%{opacity:0}100%{opacity:1}}[id^=NotiflixConfirmWrap]{position:fixed;z-index:1003;width:280px;max-width:98%;left:10px;right:10px;top:10px;margin:auto;text-align:center;box-sizing:border-box;background:0 0;font-family:Quicksand,sans-serif}[id^=NotiflixConfirmWrap] *{box-sizing:border-box}[id^=NotiflixConfirmWrap]>div[class*="-overlay"]{width:100%;height:100%;left:0;top:0;background:rgba(255,255,255,.5);position:fixed;z-index:0}[id^=NotiflixConfirmWrap]>div[class*="-content"]{width:100%;float:left;border-radius:25px;padding:10px;margin:0;filter:drop-shadow(0 0 5px rgba(0,0,0,.1));background:#f8f8f8;color:#1e1e1e;position:relative;z-index:1}[id^=NotiflixConfirmWrap]>div[class*="-content"]>div[class*="-head"]{float:left;width:100%}[id^=NotiflixConfirmWrap]>div[class*="-content"]>div[class*="-head"]>h5{float:left;width:100%;margin:0;padding:0 0 10px;border-bottom:1px solid rgba(0,0,0,.1);color:#00b462;font-family:inherit!important;font-size:16px;line-height:1.4;font-weight:500}[id^=NotiflixConfirmWrap]>div[class*="-content"]>div[class*="-head"]>p{font-family:inherit!important;margin:15px 0 20px;padding:0 10px;float:left;width:100%;font-size:14px;line-height:1.4;color:inherit}[id^=NotiflixConfirmWrap]>div[class*="-content"]>div[class*="-buttons"]{-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;border-radius:inherit;float:left;width:100%}[id^=NotiflixConfirmWrap]>div[class*="-content"]>div[class*="-buttons"]>a{cursor:pointer;font-family:inherit!important;transition:all .25s ease-in-out;float:left;width:48%;padding:9px 5px;border-radius:inherit!important;font-weight:500;font-size:15px;line-height:1.4;color:#f8f8f8}[id^=NotiflixConfirmWrap]>div[class*="-content"]>div[class*="-buttons"]>a.confirm-button-ok{margin:0 2% 0 0;background:#00b462}[id^=NotiflixConfirmWrap]>div[class*="-content"]>div[class*="-buttons"]>a.confirm-button-cancel{margin:0 0 0 2%;background:#a9a9a9}[id^=NotiflixConfirmWrap]>div[class*="-content"]>div[class*="-buttons"]>a.full{margin:0;width:100%}[id^=NotiflixConfirmWrap]>div[class*="-content"]>div[class*="-buttons"]>a:hover{box-shadow:inset 0 -60px 5px -5px rgba(0,0,0,.2)}[id^=NotiflixConfirmWrap].rtl-on>div[class*="-content"]>div[class*="-buttons"],[id^=NotiflixConfirmWrap].rtl-on>div[class*="-content"]>div[class*="-buttons"]>a{transform:rotateY(180deg)}[id^=NotiflixConfirmWrap]>div[class*="-overlay"].with-animation{animation:confirm-overlay-animation .3s ease-in-out 0s normal;-webkit-animation:confirm-overlay-animation .3s ease-in-out 0s normal}@keyframes confirm-overlay-animation{0%{opacity:0}100%{opacity:1}}@-webkit-keyframes confirm-overlay-animation{0%{opacity:0}100%{opacity:1}}[id^=NotiflixConfirmWrap].remove>div[class*="-overlay"].with-animation{opacity:0;animation:confirm-overlay-animation-remove .3s ease-in-out 0s normal;-webkit-animation:confirm-overlay-animation-remove .3s ease-in-out 0s normal}@keyframes confirm-overlay-animation-remove{0%{opacity:1}100%{opacity:0}}@-webkit-keyframes confirm-overlay-animation-remove{0%{opacity:1}100%{opacity:0}}[id^=NotiflixConfirmWrap].with-animation.nx-fade>div[class*="-content"]{animation:confirm-animation-fade .3s ease-in-out 0s normal;-webkit-animation:confirm-animation-fade .3s ease-in-out 0s normal}@keyframes confirm-animation-fade{0%{opacity:0}100%{opacity:1}}@-webkit-keyframes confirm-animation-fade{0%{opacity:0}100%{opacity:1}}[id^=NotiflixConfirmWrap].with-animation.nx-zoom>div[class*="-content"]{animation:confirm-animation-zoom .3s ease-in-out 0s normal;-webkit-animation:confirm-animation-zoom .3s ease-in-out 0s normal}@keyframes confirm-animation-zoom{0%{opacity:0;transform:scale(.5)}50%{opacity:1;transform:scale(1.05)}100%{opacity:1;transform:scale(1)}}@-webkit-keyframes confirm-animation-zoom{0%{opacity:0;transform:scale(.5)}50%{opacity:1;transform:scale(1.05)}100%{opacity:1;transform:scale(1)}}[id^=NotiflixConfirmWrap].with-animation.nx-fade.remove>div[class*="-content"]{opacity:0;animation:confirm-animation-fade-remove .3s ease-in-out 0s normal;-webkit-animation:confirm-animation-fade-remove .3s ease-in-out 0s normal}@keyframes confirm-animation-fade-remove{0%{opacity:1}100%{opacity:0}}@-webkit-keyframes confirm-animation-fade-remove{0%{opacity:1}100%{opacity:0}}[id^=NotiflixConfirmWrap].with-animation.nx-zoom.remove>div[class*="-content"]{opacity:0;animation:confirm-animation-zoom-remove .3s ease-in-out 0s normal;-webkit-animation:confirm-animation-zoom-remove .3s ease-in-out 0s normal}@keyframes confirm-animation-zoom-remove{0%{opacity:1;transform:scale(1)}50%{opacity:.5;transform:scale(1.05)}100%{opacity:0;transform:scale(0)}}@-webkit-keyframes confirm-animation-zoom-remove{0%{opacity:1;transform:scale(1)}50%{opacity:.5;transform:scale(1.05)}100%{opacity:0;transform:scale(0)}}`;

    return css;
}
// Internal CSS Codes off

// Internal CSS Func on
const notiflixInternalCSS = () => {
    if (!document.getElementById('NotiflixInternalCSS')) {
        const internalCSS = document.createElement('style');
        internalCSS.id = 'NotiflixInternalCSS';
        // internalCSS.type = 'text/css'; // => not necessary
        internalCSS.innerHTML = notiflixInternalCSSCodes();
        document.head.appendChild(internalCSS);
    }
}
// Internal CSS Func off

// Notiflix: Extend on
const extendNotiflix = function () {
    let extended = {};
    let deep = false;
    let i = 0;
    if (Object.prototype.toString.call(arguments[0]) === '[object Boolean]') {
        deep = arguments[0];
        i++;
    }
    let merge = function (obj) {
        for (let prop in obj) {
            if (obj.hasOwnProperty(prop)) {
                // If property is an object, merge properties
                if (deep && Object.prototype.toString.call(obj[prop]) === '[object Object]') {
                    extended[prop] = extendNotiflix(extended[prop], obj[prop]);
                } else {
                    extended[prop] = obj[prop];
                }
            }
        }
    };
    for (; i < arguments.length; i++) {
        merge(arguments[i]);
    }
    return extended;
};
// Notiflix: Extend off

// Notiflix: Plaintext on
const notiflixPlaintext = function (html) {
    const htmlPool = document.createElement('div');
    htmlPool.innerHTML = html;
    return htmlPool.textContent || htmlPool.innerText || '';
}
// Notiflix: Plaintext off

// Notiflix: GoogleFont on
const notiflixGoogleFont = function () {
    if (!document.getElementById('NotiflixQuicksand')) {
        // google fonts dns prefetch on
        const dns = '<link id="NotiflixGoogleDNS" rel="dns-prefetch" href="//fonts.googleapis.com">';
        const dnsRange = document.createRange();
        dnsRange.selectNode(document.head);
        const dnsFragment = dnsRange.createContextualFragment(dns);
        document.head.appendChild(dnsFragment);
        // google fonts dns prefetch off

        // google fonts style on
        const font = '<link id="NotiflixQuicksand" href="https://fonts.googleapis.com/css?family=Quicksand:300,400,500,700&amp;subset=latin-ext" rel="stylesheet" />';
        const fontRange = document.createRange();
        fontRange.selectNode(document.head);
        const fontFragment = fontRange.createContextualFragment(font);
        document.head.appendChild(fontFragment);
        // google fonts style off
    }
}
// Notiflix: GoogleFont off

// Notiflix: Console Error on
const notiflixConsoleError = function (title, message) {
    return console.error('%c ' + title + ' ', 'padding:2px;border-radius:20px;color:#fff;background:#f44336', '\n' + message + '\nVisit documentation page to learn more: https://www.notiflix.com/documentation');
}
// Notiflix: Console Error off


// Notiflix: Notify Default Settings on
let notifySettings = {
    wrapID: 'NotiflixNotifyWrap', // can not customizable
    width: '280px',
    position: 'right-top', // 'right-top' - 'right-bottom' - 'left-top' - 'left-bottom'
    distance: '10px',
    opacity: 1,
    borderRadius: '5px',
    rtl: false,
    timeout: 3000,
    messageMaxLength: 110,
    backOverlay: false,
    backOverlayColor: 'rgba(0,0,0,0.5)',

    ID: 'NotiflixNotify',
    className: 'notiflix-notify',
    zindex: 4001,
    useGoogleFont: true,
    fontFamily: 'Quicksand',
    fontSize: '13px',
    cssAnimation: true,
    cssAnimationDuration: 400,
    cssAnimationStyle: 'fade', // 'fade' - 'zoom' - 'from-right' - 'from-top' - 'from-bottom' - 'from-left'
    closeButton: false,
    useIcon: true,
    useFontAwesome: false,
    fontAwesomeIconStyle: 'basic', // 'basic' - 'shadow'
    fontAwesomeIconSize: '34px',

    plainText: true,

    success: {
        background: '#00b462',
        textColor: '#fff',
        childClassName: 'success',
        notiflixIconColor: 'rgba(0, 0, 0, 0.25)',
        fontAwesomeClassName: 'fas fa-check-circle',
        fontAwesomeIconColor: 'rgba(0, 0, 0, 0.2)',
    },

    failure: {
        background: '#f44336',
        textColor: '#fff',
        childClassName: 'failure',
        notiflixIconColor: 'rgba(0, 0, 0, 0.2)',
        fontAwesomeClassName: 'fas fa-times-circle',
        fontAwesomeIconColor: 'rgba(0, 0, 0, 0.2)',
    },

    warning: {
        background: '#f2bd1d',
        textColor: '#fff',
        childClassName: 'warning',
        notiflixIconColor: 'rgba(0, 0, 0, 0.2)',
        fontAwesomeClassName: 'fas fa-exclamation-circle',
        fontAwesomeIconColor: 'rgba(0, 0, 0, 0.2)',
    },

    info: {
        background: '#00bcd4',
        textColor: '#fff',
        childClassName: 'info',
        notiflixIconColor: 'rgba(0, 0, 0, 0.2)',
        fontAwesomeClassName: 'fas fa-info-circle',
        fontAwesomeIconColor: 'rgba(0, 0, 0, 0.2)',
    },
};
// Notiflix: Notify Default Settings off

// Notiflix: Report Default Settings on
let reportSettings = {
    ID: 'NotiflixReportWrap', // can not customizable
    className: 'notiflix-report',
    width: '320px',
    backgroundColor: '#f8f8f8',
    borderRadius: '25px',
    rtl: false,
    zindex: 4002,
    backOverlay: true,
    backOverlayColor: 'rgba(0, 0, 0, 0.5)',
    useGoogleFont: true,
    fontFamily: 'Quicksand',
    svgSize: '110px',
    plainText: true,
    titleFontSize: '16px',
    titleMaxLength: 34,
    messageFontSize: '13px',
    messageMaxLength: 400,
    buttonFontSize: '14px',
    buttonMaxLength: 34,
    cssAnimation: true,
    cssAnimationDuration: 360,
    cssAnimationStyle: 'fade', // 'fade' - 'zoom'

    success: {
        svgColor: '#00b462',
        titleColor: '#1e1e1e',
        messageColor: '#242424',
        buttonBackground: '#00b462',
        buttonColor: '#fff',
    },

    failure: {
        svgColor: '#f44336',
        titleColor: '#1e1e1e',
        messageColor: '#242424',
        buttonBackground: '#f44336',
        buttonColor: '#fff',
    },

    warning: {
        svgColor: '#f2bd1d',
        titleColor: '#1e1e1e',
        messageColor: '#242424',
        buttonBackground: '#f2bd1d',
        buttonColor: '#fff',
    },

    info: {
        svgColor: '#00bcd4',
        titleColor: '#1e1e1e',
        messageColor: '#242424',
        buttonBackground: '#00bcd4',
        buttonColor: '#fff',
    },

};
// Notiflix: Report Default Settings off

// Notiflix: Confirm Default Settings on
let confirmSettings = {
    ID: 'NotiflixConfirmWrap', // can not customizable
    className: 'notiflix-confirm',
    width: '280px',
    zindex: 4003,
    position: 'center', // 'center' - 'center-top' -  'right-top' - 'right-bottom' - 'left-top' - 'left-bottom'
    distance: '10px',
    backgroundColor: '#f8f8f8',
    borderRadius: '25px',
    backOverlay: true,
    backOverlayColor: 'rgba(0,0,0,0.5)',
    rtl: false,
    useGoogleFont: true,
    fontFamily: 'Quicksand',
    cssAnimation: true,
    cssAnimationStyle: 'fade', // 'zoom' - 'fade'
    cssAnimationDuration: 300,

    titleColor: '#00b462',
    titleFontSize: '16px',
    titleMaxLength: 34,

    messageColor: '#1e1e1e',
    messageFontSize: '14px',
    messageMaxLength: 110,

    buttonsFontSize: '15px',
    buttonsMaxLength: 34,
    okButtonColor: '#f8f8f8',
    okButtonBackground: '#00b462',
    cancelButtonColor: '#f8f8f8',
    cancelButtonBackground: '#a9a9a9',

    plainText: true,
}
// Notiflix: Confirm Default Settings off

// Notiflix: Loading Default Settings on
let loadingSettings = {
    ID: 'NotiflixLoadingWrap', // can not customizable
    className: 'notiflix-loading',
    zindex: 4000,
    backgroundColor: 'rgba(0,0,0,0.8)',
    rtl: false,
    useGoogleFont: true,
    fontFamily: 'Quicksand',
    cssAnimation: true,
    cssAnimationDuration: 400,
    clickToClose: false,
    customSvgUrl: null,
    svgSize: '80px',
    svgColor: '#00b462',
    messageID: 'NotiflixLoadingMessage',
    messageFontSize: '15px',
    messageMaxLength: 34,
    messageColor: '#dcdcdc',
};
// Notiflix: Loading Default Settings off

// Notiflix: NX React on
let newNotifySettings;
let newReportSettings;
let newConfirmSettings;
let newLoadingSettings;
const Notiflix = {

    // Notify on
    Notify: {

        // Init
        Init: function (userNotifyOpt) {
            newNotifySettings = extendNotiflix(true, notifySettings, userNotifyOpt);

            // use GoogleFonts if "Quicksand" on
            if (newNotifySettings.useGoogleFont && newNotifySettings.fontFamily === 'Quicksand') {
                notiflixGoogleFont();
            }
            // use GoogleFonts if "Quicksand" off

            // add css codes on
            notiflixInternalCSS();
            // add css codes off
        },

        // Merge First Init
        Merge: function (userNotifyExtend) {

            if (newNotifySettings) { // if initialized already
                newNotifySettings = extendNotiflix(true, newNotifySettings, userNotifyExtend);
            } else { // error
                notiflixConsoleError('Notiflix Error', 'You have to initialize the Notify module before call Merge function.');
                return;
            }
        },

        // Display Notification: Success
        Success: function (message, callback) {

            // if not initialized pretend like init
            if (!newNotifySettings) {
                Notiflix.Notify.Init({});
            }

            if (!callback) {
                callback = undefined;
            }

            const theType = newNotifySettings.success;
            NotiflixNotify(message, callback, theType, 'Success');
        },

        // Display Notification: Failure
        Failure: function (message, callback) {

            // if not initialized pretend like init
            if (!newNotifySettings) {
                Notiflix.Notify.Init({});
            }

            if (!callback) {
                callback = undefined;
            }

            const theType = newNotifySettings.failure;
            NotiflixNotify(message, callback, theType, 'Failure');

        },

        // Display Notification: Warning
        Warning: function (message, callback) {

            // if not initialized pretend like init
            if (!newNotifySettings) {
                Notiflix.Notify.Init({});
            }

            if (!callback) {
                callback = undefined;
            }

            const theType = newNotifySettings.warning;
            NotiflixNotify(message, callback, theType, 'Warning');

        },

        // Display Notification: Info
        Info: function (message, callback) {

            // if not initialized pretend like init
            if (!newNotifySettings) {
                Notiflix.Notify.Init({});
            }

            if (!callback) {
                callback = undefined;
            }

            const theType = newNotifySettings.info;
            NotiflixNotify(message, callback, theType, 'Info');

        },

    },
    // Notify off

    // Report on
    Report: {

        // Init
        Init: function (userReportOpt) {
            newReportSettings = extendNotiflix(true, reportSettings, userReportOpt);

            // use GoogleFonts if "Quicksand" on
            if (newReportSettings.useGoogleFont && newReportSettings.fontFamily === 'Quicksand') {
                notiflixGoogleFont();
            }
            // use GoogleFonts if "Quicksand" off

            // add css codes on
            notiflixInternalCSS();
            // add css codes off
        },

        // Merge First Init
        Merge: function (userReportExtend) {

            if (newReportSettings) { // if initialized already
                newReportSettings = extendNotiflix(true, newReportSettings, userReportExtend);
            } else { // error
                notiflixConsoleError('Notiflix Error', 'You have to initialize the Report module before call Merge function.');
                return;
            }
        },

        // Display Report: Success
        Success: function (title, message, buttonText, buttonCallback) {

            // if not initialized pretend like init
            if (!newReportSettings) {
                Notiflix.Report.Init({});
            }

            if (arguments.length > 4) { // More parameters than allowed
                notiflixConsoleError('Notiflix Error', 'More parameters than allowed.');
                return;
            }

            if (arguments[0] === undefined || arguments[0].length <= 0) { // title
                arguments[0] = 'Notiflix Success';
            }

            if (arguments[1] === undefined || arguments[1].length <= 0) { // message
                arguments[1] = `"Do not try to become a person of success but try to become a person of value." <br><br>- Albert Einstein`;
            }

            if (arguments[2] === undefined || arguments[2].length <= 0) { // buttonText
                arguments[2] = 'Okay';
            }

            if (arguments[3] === undefined) { // buttonCallback
                arguments[3] = undefined;
            }

            const theType = newReportSettings.success;
            NotiflixReport(arguments[0], arguments[1], arguments[2], arguments[3], theType, 'success');
        },

        // Display Report: Failure
        Failure: function (title, message, buttonText, buttonCallback) {

            // if not initialized pretend like init
            if (!newReportSettings) {
                Notiflix.Report.Init({});
            }

            if (arguments.length > 4) { // More parameters than allowed
                notiflixConsoleError('Notiflix Error', 'More parameters than allowed.');
                return;
            }

            if (arguments[0] === undefined || arguments[0].length <= 0) { // title
                arguments[0] = 'Notiflix Failure';
            }

            if (arguments[1] === undefined || arguments[1].length <= 0) { // message
                arguments[1] = `"Failure is simply the opportunity to begin again, this time more intelligently." <br><br>- Henry Ford`;
            }

            if (arguments[2] === undefined || arguments[2].length <= 0) { // buttonText
                arguments[2] = 'Okay';
            }

            if (arguments[3] === undefined) { // buttonCallback
                arguments[3] = undefined;
            }

            const theType = newReportSettings.failure;
            NotiflixReport(arguments[0], arguments[1], arguments[2], arguments[3], theType, 'failure');

        },

        // Display Report: Warning
        Warning: function (title, message, buttonText, buttonCallback) {

            // if not initialized pretend like init
            if (!newReportSettings) {
                Notiflix.Report.Init({});
            }

            if (arguments.length > 4) { // More parameters than allowed
                notiflixConsoleError('Notiflix Error', 'More parameters than allowed.');
                return;
            }

            if (arguments[0] === undefined || arguments[0].length <= 0) { // title
                arguments[0] = 'Notiflix Warning';
            }

            if (arguments[1] === undefined || arguments[1].length <= 0) { // message
                arguments[1] = `"The peoples who want to live comfortably without producing and fatigue; they are doomed to lose their dignity, then liberty, and then independence and destiny." <br><br>- Mustafa Kemal Ataturk`;
            }

            if (arguments[2] === undefined || arguments[2].length <= 0) { // buttonText
                arguments[2] = 'Okay';
            }

            if (arguments[3] === undefined) { // buttonCallback
                arguments[3] = undefined;
            }

            const theType = newReportSettings.warning;
            NotiflixReport(arguments[0], arguments[1], arguments[2], arguments[3], theType, 'warning');

        },

        // Display Report: Info
        Info: function (title, message, buttonText, buttonCallback) {

            // if not initialized pretend like init
            if (!newReportSettings) {
                Notiflix.Report.Init({});
            }

            if (arguments.length > 4) { // More parameters than allowed
                notiflixConsoleError('Notiflix Error', 'More parameters than allowed.');
                return;
            }

            if (arguments[0] === undefined || arguments[0].length <= 0) { // title
                arguments[0] = 'Notiflix Info';
            }

            if (arguments[1] === undefined || arguments[1].length <= 0) { // message
                arguments[1] = `"Knowledge rests not upon truth alone, but upon error also." <br><br>- Carl Gustav Jung`;
            }

            if (arguments[2] === undefined || arguments[2].length <= 0) { // buttonText
                arguments[2] = 'Okay';
            }

            if (arguments[3] === undefined) { // buttonCallback
                arguments[3] = undefined;
            }

            const theType = newReportSettings.info;
            NotiflixReport(arguments[0], arguments[1], arguments[2], arguments[3], theType, 'info');
        },

    },
    // Report off

    // Confirm on
    Confirm: {

        // Init
        Init: function (userConfirmOpt) {
            newConfirmSettings = extendNotiflix(true, confirmSettings, userConfirmOpt);

            // use GoogleFonts if "Quicksand" on
            if (newConfirmSettings.useGoogleFont && newConfirmSettings.fontFamily === 'Quicksand') {
                notiflixGoogleFont();
            }
            // use GoogleFonts if "Quicksand" off

            // add css codes on
            notiflixInternalCSS();
            // add css codes off
        },

        // Merge First Init
        Merge: function (userConfirmExtend) {

            if (newConfirmSettings) { // if initialized already
                newConfirmSettings = extendNotiflix(true, newConfirmSettings, userConfirmExtend);
            } else { // error
                notiflixConsoleError('Notiflix Error', 'You have to initialize the Confirm module before call Merge function.');
                return;
            }
        },

        // Display Confirm: Show
        Show: function (title, message, okText, cancelText, okCallback, cancelCallback) {

            // if not initialized pretend like init
            if (!newConfirmSettings) {
                Notiflix.Confirm.Init({});
            }

            if (arguments.length > 6) { // More parameters than allowed
                notiflixConsoleError('Notiflix Error', 'More parameters than allowed.');
                return;
            }

            if (arguments[0] === undefined || arguments[0].length <= 0) { // title
                arguments[0] = 'Notiflix Confirm';
            }

            if (arguments[1] === undefined || arguments[1].length <= 0) { // message
                arguments[1] = 'Do you agree with me?';
            }

            if (arguments[2] === undefined || arguments[2].length <= 0) { // ok buttonText
                arguments[2] = 'Yes';
            }

            if (arguments[3] === undefined || arguments[3].length <= 0) { // cancel buttonText
                arguments[3] = 'No';
            }

            if (arguments[4] === undefined) { // okCallback
                arguments[4] = undefined;
            }

            if (arguments[5] === undefined) { // cancelCallback
                arguments[5] = undefined;
            }

            NotiflixConfirm(arguments[0], arguments[1], arguments[2], arguments[3], arguments[4], arguments[5]);
        },
    },
    // Confirm off

    // Loading on
    Loading: {

        // Init
        Init: function (userLoadingOpt) {
            newLoadingSettings = extendNotiflix(true, loadingSettings, userLoadingOpt);

            // use GoogleFonts if "Quicksand" on
            if (newLoadingSettings.useGoogleFont && newLoadingSettings.fontFamily === 'Quicksand') {
                notiflixGoogleFont();
            }
            // use GoogleFonts if "Quicksand" off

            // add css codes on
            notiflixInternalCSS();
            // add css codes off
        },

        // Merge First Init
        Merge: function (userLoadingExtend) {

            if (newLoadingSettings) { // if initialized already
                newLoadingSettings = extendNotiflix(true, newLoadingSettings, userLoadingExtend);
            } else { // error
                notiflixConsoleError('Notiflix Error', 'You have to initialize the Loading module before call Merge function.');
                return;
            }
        },

        // Display Loading: Standard
        Standard: function (message) {

            // if not initialized pretend like init
            if (!newLoadingSettings) {
                Notiflix.Loading.Init({});
            }

            if (arguments.length > 1) { // More parameters than allowed
                notiflixConsoleError('Notiflix Error', 'More parameters than allowed.');
                return;
            }

            if (!message) {
                message = '';
            }

            NotiflixLoading(message, 'standard', true, 0); // true = display

        },

        // Display Loading: Hourglass
        Hourglass: function (message) {

            // if not initialized pretend like init
            if (!newLoadingSettings) {
                Notiflix.Loading.Init({});
            }

            if (arguments.length > 1) { // More parameters than allowed
                notiflixConsoleError('Notiflix Error', 'More parameters than allowed.');
                return;
            }

            if (!message) {
                message = '';
            }

            NotiflixLoading(message, 'hourglass', true, 0); // true = display

        },

        // Display Loading: Circle
        Circle: function (message) {

            // if not initialized pretend like init
            if (!newLoadingSettings) {
                Notiflix.Loading.Init({});
            }

            if (arguments.length > 1) { // More parameters than allowed
                notiflixConsoleError('Notiflix Error', 'More parameters than allowed.');
                return;
            }

            if (!message) {
                message = '';
            }

            NotiflixLoading(message, 'circle', true, 0); // true = display

        },

        // Display Loading: Arrows
        Arrows: function (message) {

            // if not initialized pretend like init
            if (!newLoadingSettings) {
                Notiflix.Loading.Init({});
            }

            if (arguments.length > 1) { // More parameters than allowed
                notiflixConsoleError('Notiflix Error', 'More parameters than allowed.');
                return;
            }

            if (!message) {
                message = '';
            }

            NotiflixLoading(message, 'arrows', true, 0); // true = display

        },

        // Display Loading: Dots
        Dots: function (message) {

            // if not initialized pretend like init
            if (!newLoadingSettings) {
                Notiflix.Loading.Init({});
            }

            if (arguments.length > 1) { // More parameters than allowed
                notiflixConsoleError('Notiflix Error', 'More parameters than allowed.');
                return;
            }

            if (!message) {
                message = '';
            }

            NotiflixLoading(message, 'dots', true, 0); // true = display

        },

        // Display Loading: Pulse
        Pulse: function (message) {

            // if not initialized pretend like init
            if (!newLoadingSettings) {
                Notiflix.Loading.Init({});
            }

            if (arguments.length > 1) { // More parameters than allowed
                notiflixConsoleError('Notiflix Error', 'More parameters than allowed.');
                return;
            }

            if (!message) {
                message = '';
            }

            NotiflixLoading(message, 'pulse', true, 0); // true = display

        },

        // Display Loading: Custom
        Custom: function (message) {

            // if not initialized pretend like init
            if (!newLoadingSettings) {
                Notiflix.Loading.Init({});
            }

            if (arguments.length > 1) { // More parameters than allowed
                notiflixConsoleError('Notiflix Error', 'More parameters than allowed.');
                return;
            }

            if (!message) {
                message = '';
            }

            NotiflixLoading(message, 'custom', true, 0); // true = display

        },

        // Display Loading: Notiflix
        Notiflix: function (message) {

            // if not initialized pretend like init
            if (!newLoadingSettings) {
                Notiflix.Loading.Init({});
            }

            if (arguments.length > 1) { // More parameters than allowed
                notiflixConsoleError('Notiflix Error', 'More parameters than allowed.');
                return;
            }

            if (!message) {
                message = '';
            }

            NotiflixLoading(message, 'notiflix', true, 0); // true = display

        },

        // Remove Loading
        Remove: function (theDelay) {

            // if not initialized pretend like init
            if (!newLoadingSettings) {
                Notiflix.Loading.Init({});
            }

            if (arguments.length > 1) { // More parameters than allowed
                notiflixConsoleError('Notiflix Error', 'More parameters than allowed.');
                return;
            }

            if (!theDelay) {
                theDelay = 0;
            }

            NotiflixLoading(false, false, false, theDelay); // false = Remove
        },

        // Change The Message
        Change: function (newMessage) {

            // if not initialized pretend like init
            if (!newLoadingSettings) {
                Notiflix.Loading.Init({});
            }

            if (arguments.length > 1) { // More parameters than allowed
                notiflixConsoleError('Notiflix Error', 'More parameters than allowed.');
                return;
            }

            if (!newMessage) {
                newMessage = '';
            }

            NotiflixLoadingChange(newMessage);
        },

    },
    // Loading off

}
/* harmony default export */ __webpack_exports__["default"] = (Notiflix);
// Notiflix: NX React off


// Notiflix: Notify Single on
let notiflixNotifyCount = 0;
const NotiflixNotify = function (message, callback, theType, staticType) {

    if (arguments !== undefined && arguments.length === 4) {

        // no message on
        if (!message) {
            message = `Notiflix ${staticType}`;
        }
        // no message off

        // FontAwesome if Shadow on
        if (newNotifySettings.fontAwesomeIconStyle === 'shadow') {
            theType.fontAwesomeIconColor = theType.background;
        }
        // FontAwesome if Shadow off

        // if plainText true = HTML tags not allowed on      
        if (newNotifySettings.plainText) {
            message = notiflixPlaintext(message); // message plain text
        }
        // if plainText true = HTML tags not allowed off

        // if plainText false but the message length more than messageMaxLength = HTML tags error on
        if (!newNotifySettings.plainText && message.length > newNotifySettings.messageMaxLength) {
            Notiflix.Notify.Merge({ closeButton: true, });
            message = `<b>HTML Tags Error:</b> Your content length is more than "messageMaxLength" option.`; // message html error
        }
        // if plainText false but the message length more than messageMaxLength = HTML tags error off


        if (message.length > newNotifySettings.messageMaxLength) {
            message = `${message.substring(0, newNotifySettings.messageMaxLength)}...`;
        }

        // notify counter on
        notiflixNotifyCount++;
        // notify counter off

        // if cssAnimaion false -> duration on
        if (!newNotifySettings.cssAnimation) {
            newNotifySettings.cssAnimationDuration = 0;
        }
        // if cssAnimaion false -> duration off

        // notify wrap on
        let docBody = document.body;

        let ntflxNotifyWrap = document.createElement('div');
        ntflxNotifyWrap.id = notifySettings.wrapID;
        ntflxNotifyWrap.style.width = newNotifySettings.width;
        ntflxNotifyWrap.style.zIndex = newNotifySettings.zindex;
        ntflxNotifyWrap.style.opacity = newNotifySettings.opacity;

        // wrap position on
        if (newNotifySettings.position === 'right-bottom') {

            ntflxNotifyWrap.style.right = newNotifySettings.distance;
            ntflxNotifyWrap.style.bottom = newNotifySettings.distance;
            ntflxNotifyWrap.style.top = 'auto';
            ntflxNotifyWrap.style.left = 'auto';

        } else if (newNotifySettings.position === 'left-top') {

            ntflxNotifyWrap.style.left = newNotifySettings.distance;
            ntflxNotifyWrap.style.top = newNotifySettings.distance;
            ntflxNotifyWrap.style.right = 'auto';
            ntflxNotifyWrap.style.bottom = 'auto';

        } else if (newNotifySettings.position === 'left-bottom') {

            ntflxNotifyWrap.style.left = newNotifySettings.distance;
            ntflxNotifyWrap.style.bottom = newNotifySettings.distance;
            ntflxNotifyWrap.style.top = 'auto';
            ntflxNotifyWrap.style.right = 'auto';

        } else { // 'right-top' or else

            ntflxNotifyWrap.style.right = newNotifySettings.distance;
            ntflxNotifyWrap.style.top = newNotifySettings.distance;
            ntflxNotifyWrap.style.left = 'auto';
            ntflxNotifyWrap.style.bottom = 'auto';
        }
        // wrap position off

        // if background overlay true on
        let notifyOverlay;
        if (newNotifySettings.backOverlay) {

            notifyOverlay = document.createElement('div');
            notifyOverlay.id = `${newNotifySettings.ID}Overlay`;
            notifyOverlay.style.width = '100%';
            notifyOverlay.style.height = '100%';
            notifyOverlay.style.position = 'fixed';
            notifyOverlay.style.zIndex = newNotifySettings.zindex;
            notifyOverlay.style.left = 0;
            notifyOverlay.style.top = 0;
            notifyOverlay.style.right = 0;
            notifyOverlay.style.bottom = 0;
            notifyOverlay.style.background = newNotifySettings.backOverlayColor;
            notifyOverlay.className = (newNotifySettings.cssAnimation ? 'with-animation' : '');
            notifyOverlay.style.animationDuration = (newNotifySettings.cssAnimation) ? `${newNotifySettings.cssAnimationDuration}ms` : '';

            if (!document.getElementById(notifyOverlay.id)) {
                docBody.appendChild(notifyOverlay);
            }

        }
        // if background overlay true off

        if (!document.getElementById(ntflxNotifyWrap.id)) {
            docBody.appendChild(ntflxNotifyWrap);
        }
        // notify wrap off


        // notify content on
        let ntflxNotify = document.createElement('div');
        ntflxNotify.id = `${newNotifySettings.ID}-${notiflixNotifyCount}`;
        ntflxNotify.className = `${newNotifySettings.className} ${theType.childClassName} ${(newNotifySettings.cssAnimation ? 'with-animation' : '')} ${(newNotifySettings.useIcon ? 'with-icon' : '')} nx-${newNotifySettings.cssAnimationStyle} ${(newNotifySettings.closeButton && !callback ? 'with-close' : '')} ${(callback ? 'with-callback' : '')}`;
        ntflxNotify.style.fontSize = newNotifySettings.fontSize;
        ntflxNotify.style.color = theType.textColor;
        ntflxNotify.style.background = theType.background;
        ntflxNotify.style.borderRadius = newNotifySettings.borderRadius;

        // rtl on
        if (newNotifySettings.rtl) {
            ntflxNotify.setAttribute('dir', 'rtl');
            ntflxNotify.classList.add('rtl-on');
        }
        // rtl off

        // font-family on
        ntflxNotify.style.fontFamily = `"${newNotifySettings.fontFamily}", sans-serif`;
        // font-family off

        // use css animation on
        if (newNotifySettings.cssAnimation) {
            ntflxNotify.style.animationDuration = `${newNotifySettings.cssAnimationDuration}ms`;
        }
        // use css animation off

        // click to close on
        let closeButtonHTML = '';
        if (newNotifySettings.closeButton && !callback) {
            closeButtonHTML = `<span class="click-to-close"><svg class="clck2cls" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="20px" height="20px" version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"viewBox="0 0 20 20"xmlns:xlink="http://www.w3.org/1999/xlink"><defs><style type="text/css">.click2close{fill:${theType.notiflixIconColor};}</style></defs><g><path class="click2close" d="M0.38 2.19l7.8 7.81 -7.8 7.81c-0.51,0.5 -0.51,1.31 -0.01,1.81 0.25,0.25 0.57,0.38 0.91,0.38 0.34,0 0.67,-0.14 0.91,-0.38l7.81 -7.81 7.81 7.81c0.24,0.24 0.57,0.38 0.91,0.38 0.34,0 0.66,-0.14 0.9,-0.38 0.51,-0.5 0.51,-1.31 0,-1.81l-7.81 -7.81 7.81 -7.81c0.51,-0.5 0.51,-1.31 0,-1.82 -0.5,-0.5 -1.31,-0.5 -1.81,0l-7.81 7.81 -7.81 -7.81c-0.5,-0.5 -1.31,-0.5 -1.81,0 -0.51,0.51 -0.51,1.32 0,1.82z"/></g></svg></span>`;
        }
        // click to close off

        // use icon on
        if (newNotifySettings.useIcon) {

            if (newNotifySettings.useFontAwesome) { // use font awesome

                ntflxNotify.innerHTML = `<i style="color:${theType.fontAwesomeIconColor}; font-size:${newNotifySettings.fontAwesomeIconSize};" class="nmi wfa ${theType.fontAwesomeClassName} ${(newNotifySettings.fontAwesomeIconStyle === 'shadow' ? 'shadow' : 'basic')}"></i><span class="the-message with-icon">${message}</span>${(newNotifySettings.closeButton ? closeButtonHTML : '')}`;

            } else { // use notiflix icon

                let svgIcon = '';

                if (staticType === 'Success') {  // success

                    svgIcon = `<svg class="nmi" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="40px" height="40px" version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"viewBox="0 0 40 40"xmlns:xlink="http://www.w3.org/1999/xlink"><defs><style type="text/css">#Notiflix-Icon-Success{fill:${theType.notiflixIconColor};}</style></defs><g><path id="Notiflix-Icon-Success" class="fil0" d="M20 0c11.03,0 20,8.97 20,20 0,11.03 -8.97,20 -20,20 -11.03,0 -20,-8.97 -20,-20 0,-11.03 8.97,-20 20,-20zm0 37.98c9.92,0 17.98,-8.06 17.98,-17.98 0,-9.92 -8.06,-17.98 -17.98,-17.98 -9.92,0 -17.98,8.06 -17.98,17.98 0,9.92 8.06,17.98 17.98,17.98zm-2.4 -13.29l11.52 -12.96c0.37,-0.41 1.01,-0.45 1.42,-0.08 0.42,0.37 0.46,1 0.09,1.42l-12.16 13.67c-0.19,0.22 -0.46,0.34 -0.75,0.34 -0.23,0 -0.45,-0.07 -0.63,-0.22l-7.6 -6.07c-0.43,-0.35 -0.5,-0.99 -0.16,-1.42 0.35,-0.43 0.99,-0.5 1.42,-0.16l6.85 5.48z"/></g></svg>`;

                } else if (staticType === 'Failure') { // failure

                    svgIcon = `<svg class="nmi" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="40px" height="40px" version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"viewBox="0 0 40 40"xmlns:xlink="http://www.w3.org/1999/xlink"><defs><style type="text/css">#Notiflix-Icon-Failure{fill:${theType.notiflixIconColor};}</style></defs><g><path id="Notiflix-Icon-Failure" class="fil0" d="M20 0c11.03,0 20,8.97 20,20 0,11.03 -8.97,20 -20,20 -11.03,0 -20,-8.97 -20,-20 0,-11.03 8.97,-20 20,-20zm0 37.98c9.92,0 17.98,-8.06 17.98,-17.98 0,-9.92 -8.06,-17.98 -17.98,-17.98 -9.92,0 -17.98,8.06 -17.98,17.98 0,9.92 8.06,17.98 17.98,17.98zm1.42 -17.98l6.13 6.12c0.39,0.4 0.39,1.04 0,1.43 -0.19,0.19 -0.45,0.29 -0.71,0.29 -0.27,0 -0.53,-0.1 -0.72,-0.29l-6.12 -6.13 -6.13 6.13c-0.19,0.19 -0.44,0.29 -0.71,0.29 -0.27,0 -0.52,-0.1 -0.71,-0.29 -0.39,-0.39 -0.39,-1.03 0,-1.43l6.13 -6.12 -6.13 -6.13c-0.39,-0.39 -0.39,-1.03 0,-1.42 0.39,-0.39 1.03,-0.39 1.42,0l6.13 6.12 6.12 -6.12c0.4,-0.39 1.04,-0.39 1.43,0 0.39,0.39 0.39,1.03 0,1.42l-6.13 6.13z"/></g></svg>`;

                } else if (staticType === 'Warning') { // warning

                    svgIcon = `<svg class="nmi" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="40px" height="40px" version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"viewBox="0 0 40 40"xmlns:xlink="http://www.w3.org/1999/xlink"><defs><style type="text/css">#Notiflix-Icon-Warning{fill:${theType.notiflixIconColor};}</style></defs><g><path id="Notiflix-Icon-Warning" class="fil0" d="M21.91 3.48l17.8 30.89c0.84,1.46 -0.23,3.25 -1.91,3.25l-35.6 0c-1.68,0 -2.75,-1.79 -1.91,-3.25l17.8 -30.89c0.85,-1.47 2.97,-1.47 3.82,0zm16.15 31.84l-17.8 -30.89c-0.11,-0.2 -0.41,-0.2 -0.52,0l-17.8 30.89c-0.12,0.2 0.05,0.4 0.26,0.4l35.6 0c0.21,0 0.38,-0.2 0.26,-0.4zm-19.01 -4.12l0 -1.05c0,-0.53 0.42,-0.95 0.95,-0.95 0.53,0 0.95,0.42 0.95,0.95l0 1.05c0,0.53 -0.42,0.95 -0.95,0.95 -0.53,0 -0.95,-0.42 -0.95,-0.95zm0 -4.66l0 -13.39c0,-0.52 0.42,-0.95 0.95,-0.95 0.53,0 0.95,0.43 0.95,0.95l0 13.39c0,0.53 -0.42,0.96 -0.95,0.96 -0.53,0 -0.95,-0.43 -0.95,-0.96z"/></g></svg>`;

                } else if (staticType === 'Info') { // info

                    svgIcon = `<svg class="nmi" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="40px" height="40px" version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"viewBox="0 0 40 40"xmlns:xlink="http://www.w3.org/1999/xlink"><defs><style type="text/css">#Notiflix-Icon-Info{fill:${theType.notiflixIconColor};}</style></defs><g><path id="Notiflix-Icon-Info" class="fil0" d="M20 0c11.03,0 20,8.97 20,20 0,11.03 -8.97,20 -20,20 -11.03,0 -20,-8.97 -20,-20 0,-11.03 8.97,-20 20,-20zm0 37.98c9.92,0 17.98,-8.06 17.98,-17.98 0,-9.92 -8.06,-17.98 -17.98,-17.98 -9.92,0 -17.98,8.06 -17.98,17.98 0,9.92 8.06,17.98 17.98,17.98zm-0.99 -23.3c0,-0.54 0.44,-0.98 0.99,-0.98 0.55,0 0.99,0.44 0.99,0.98l0 15.86c0,0.55 -0.44,0.99 -0.99,0.99 -0.55,0 -0.99,-0.44 -0.99,-0.99l0 -15.86zm0 -5.22c0,-0.55 0.44,-0.99 0.99,-0.99 0.55,0 0.99,0.44 0.99,0.99l0 1.09c0,0.54 -0.44,0.99 -0.99,0.99 -0.55,0 -0.99,-0.45 -0.99,-0.99l0 -1.09z"/></g></svg>`;

                }

                ntflxNotify.innerHTML = `${svgIcon}<span class="the-message with-icon">${message}</span>${(newNotifySettings.closeButton ? closeButtonHTML : '')}`;

            }

        } else { // without icon

            ntflxNotify.innerHTML = `<span class="the-message">${message}</span> ${(newNotifySettings.closeButton ? closeButtonHTML : '')}`;
        }
        // use icon off
        // notify content off


        // notify append or prepend on
        const notifyWrapElement = document.getElementById(ntflxNotifyWrap.id);
        if (newNotifySettings.position === 'left-bottom' || newNotifySettings.position === 'right-bottom') { // the new one will be first

            notifyWrapElement.insertBefore(ntflxNotify, notifyWrapElement.firstChild);
        } else {

            notifyWrapElement.appendChild(ntflxNotify);
        }

        if (newNotifySettings.useIcon) { // if useIcon, dynamically vertical align the contents

            let messageIcon = document.getElementById(ntflxNotify.id).querySelectorAll('.nmi')[0];
            let messageIconH = 40;

            if (newNotifySettings.useFontAwesome) { // if font awesome

                messageIconH = Math.round(parseInt(messageIcon.offsetHeight));

            } else { // if notiflix SVG

                let SvgBBox = messageIcon.getBBox();
                messageIconH = Math.round(parseInt(SvgBBox.width));

            }

            let messageText = document.getElementById(ntflxNotify.id).querySelectorAll('span')[0];
            let messageTextH = Math.round(messageText.offsetHeight);

            if (messageTextH <= messageIconH) {
                let paddingVal = `${parseInt((messageIconH - messageTextH) / 2).toString()}px`;
                messageText.style.paddingTop = paddingVal;
                messageText.style.paddingBottom = paddingVal;
            }

        }
        // notify append or prepend off


        // remove by timeout or click on
        if (document.getElementById(ntflxNotify.id)) {

            // set elements on
            let removeDiv = document.getElementById(ntflxNotify.id);
            let removeWrap = document.getElementById(ntflxNotifyWrap.id);
            let removeOverlay;
            if (newNotifySettings.backOverlay) {
                removeOverlay = document.getElementById(notifyOverlay.id);
            }
            // set elements on

            // timeout vars on
            let timeoutAddClass;
            let timeoutRemove;
            // timeout vars off

            // timeout if click to close false and callback undefined on
            if (!newNotifySettings.closeButton && !callback) {
                timeoutAddClass = setTimeout(function () {

                    removeDiv.classList.add('remove');

                    if (newNotifySettings.backOverlay && removeWrap.childElementCount <= 0) {
                        removeOverlay.classList.add('remove');
                    }

                }, newNotifySettings.timeout);

                timeoutRemove = setTimeout(function () {
                    removeDiv.parentNode.removeChild(removeDiv);
                    if (removeWrap.childElementCount <= 0) { // if childs count === 0 remove wrap
                        removeWrap.parentNode.removeChild(removeWrap);

                        if (newNotifySettings.backOverlay) {
                            removeOverlay.parentNode.removeChild(removeOverlay);
                        }
                    }
                }, newNotifySettings.timeout + newNotifySettings.cssAnimationDuration);
            }
            // timeout if click to close false and callback undefined off

            // if click to close on            
            if (newNotifySettings.closeButton && !callback) {

                let closeButtonElm = document.getElementById(ntflxNotify.id).querySelectorAll('span.click-to-close')[0];

                closeButtonElm.addEventListener('click', function () {

                    removeDiv.classList.add('remove');
                    clearTimeout(timeoutAddClass);

                    if (newNotifySettings.backOverlay && removeWrap.childElementCount <= 1) { // if last child - addClass Remove to Overlay
                        removeOverlay.classList.add('remove');
                    }

                    setTimeout(function () {
                        removeDiv.parentNode.removeChild(removeDiv);
                        clearTimeout(timeoutRemove);

                        if (removeWrap.childElementCount <= 0) { // if childs count === 0 remove wrap
                            removeWrap.parentNode.removeChild(removeWrap); // remove wrap

                            if (newNotifySettings.backOverlay) {
                                removeOverlay.parentNode.removeChild(removeOverlay);
                            }
                        }

                    }, newNotifySettings.cssAnimationDuration);

                });

            }
            // if click to close off


            // callback on
            if (callback && typeof callback === 'function') {

                removeDiv.addEventListener('click', function (e) {

                    callback(); // callback

                    // remove element on
                    removeDiv.classList.add('remove');

                    if (newNotifySettings.backOverlay && removeWrap.childElementCount <= 0) {
                        removeOverlay.classList.add('remove');
                    }

                    clearTimeout(timeoutAddClass);

                    setTimeout(function () {

                        removeDiv.parentNode.removeChild(removeDiv);

                        if (removeWrap.childElementCount <= 0) { // if childs count === 0 remove wrap
                            removeWrap.parentNode.removeChild(removeWrap);

                            if (newNotifySettings.backOverlay) {
                                removeOverlay.parentNode.removeChild(removeOverlay);
                            }
                        }
                        clearTimeout(timeoutRemove);
                    }, newNotifySettings.cssAnimationDuration);
                    // remove element off

                });

            }
            // callback off

        }
        // remove by timeout or click off

    } else {
        notiflixConsoleError('Notiflix Error', 'Where is the arguments?');
    }

}
// Notiflix: Notify Single off


// Notiflix: Report Single on
const NotiflixReport = function (title, message, buttonText, buttonCallback, theType, staticType) {

    // if plainText true = HTML tags not allowed on
    if (newReportSettings.plainText) {
        title = notiflixPlaintext(title);
        message = notiflixPlaintext(message);
        buttonText = notiflixPlaintext(buttonText);
    }
    // if plainText true = HTML tags not allowed off

    // if plainText false but the contents length more than *MaxLength = HTML tags error on
    if (!newReportSettings.plainText) {
        if (title.length > newReportSettings.titleMaxLength) {
            title = 'HTML Tags Error'; // title html error
            message = 'Your Title content length is more than "titleMaxLength" option.'; // message html error
            buttonText = 'Okay'; // button html error
        }

        if (message.length > newReportSettings.messageMaxLength) {
            title = 'HTML Tags Error'; // title html error
            message = 'Your Message content length is more than "messageMaxLength" option.'; // message html error
            buttonText = 'Okay'; // button html error
        }

        if (buttonText.length > newReportSettings.buttonMaxLength) {
            title = 'HTML Tags Error'; // title html error
            message = 'Your Button content length is more than "buttonMaxLength" option.'; // message html error
            buttonText = 'Okay'; // button html error
        }
    }
    // if plainText false but the contents length more than *MaxLength = HTML tags error off


    // max length on
    if (title.length > newReportSettings.titleMaxLength) {
        title = `${title.substring(0, newReportSettings.titleMaxLength)}...`;
    }

    if (message.length > newReportSettings.messageMaxLength) {
        message = `${message.substring(0, newReportSettings.messageMaxLength)}...`;
    }

    if (buttonText.length > newReportSettings.buttonMaxLength) {
        buttonText = `${buttonText.substring(0, newReportSettings.buttonMaxLength)}...`;
    }
    // max length off

    // if cssAnimaion false -> duration on
    if (!newReportSettings.cssAnimation) {
        newReportSettings.cssAnimationDuration = 0;
    }
    // if cssAnimaion false -> duration off

    // report wrap on
    const docBody = document.body;

    const ntflxReportWrap = document.createElement('div');
    ntflxReportWrap.id = reportSettings.ID;
    ntflxReportWrap.className = newReportSettings.className;
    ntflxReportWrap.style.width = newReportSettings.width;
    ntflxReportWrap.style.zIndex = newReportSettings.zindex;
    ntflxReportWrap.style.borderRadius = newReportSettings.borderRadius;

    // font-family on
    ntflxReportWrap.style.fontFamily = `"${newReportSettings.fontFamily}", sans-serif`;
    // font-family off

    // rtl on
    if (newReportSettings.rtl) {
        ntflxReportWrap.setAttribute('dir', 'rtl');
        ntflxReportWrap.classList.add('rtl-on');
    }
    // rtl off

    // overlay on
    let reportOverlay = '';
    if (newReportSettings.backOverlay) {
        reportOverlay = `<div class="${newReportSettings.className}-overlay ${(newReportSettings.cssAnimation ? 'with-animation' : '')}" style="background:${newReportSettings.backOverlayColor}; animation-duration:${newReportSettings.cssAnimationDuration}ms;"></div>`;
    }
    // overlay off


    // svg icon on
    let svgIcon = '';
    if (staticType === 'success') {
        svgIcon = notiflixReportSvgSuccess(newReportSettings.svgSize, theType.svgColor);
    } else if (staticType === 'failure') {
        svgIcon = notiflixReportSvgFailure(newReportSettings.svgSize, theType.svgColor);
    } else if (staticType === 'warning') {
        svgIcon = notiflixReportSvgWarning(newReportSettings.svgSize, theType.svgColor);
    } else if (staticType === 'info') {
        svgIcon = notiflixReportSvgInfo(newReportSettings.svgSize, theType.svgColor);
    }
    // svg icon off

    // report html on
    ntflxReportWrap.innerHTML = `${reportOverlay}
    <div class="${newReportSettings.className}-content ${(newReportSettings.cssAnimation ? 'with-animation' : '')} nx-${newReportSettings.cssAnimationStyle}" style="background:${newReportSettings.backgroundColor}; animation-duration:${newReportSettings.cssAnimationDuration}ms;">
    <div style="width:${newReportSettings.svgSize}; height:${newReportSettings.svgSize};" class="${newReportSettings.className}-icon">${svgIcon}</div>
    <h5 class="${newReportSettings.className}-title" style="font-weight:500; font-size:${newReportSettings.titleFontSize}; color:${theType.titleColor};">${title}</h5>
    <p class="${newReportSettings.className}-message" style="font-size:${newReportSettings.messageFontSize}; color:${theType.messageColor};">${message}</p>
    <a id="NXReportButton" class="${newReportSettings.className}-button" style="font-weight:500; font-size:${newReportSettings.buttonFontSize}; background:${theType.buttonBackground}; color:${theType.buttonColor};">${buttonText}</a>
    </div>`;
    // report html off

    if (!document.getElementById(ntflxReportWrap.id)) { // if no report

        docBody.appendChild(ntflxReportWrap); // append

        // vertical align on            
        let windowH = Math.round(window.innerHeight);
        let reportH = Math.round(document.getElementById(ntflxReportWrap.id).offsetHeight);
        ntflxReportWrap.style.top = `${parseInt((windowH - reportH) / 2).toString()}px`;
        // vertical align off

        // callback on
        let getReportWrap = document.getElementById(ntflxReportWrap.id);
        let reportButton = document.getElementById('NXReportButton');
        reportButton.addEventListener('click', function () {

            // if callback on
            if (buttonCallback && typeof buttonCallback === 'function') {
                buttonCallback();
            }
            // if callback off

            // remove element on
            getReportWrap.classList.add('remove');

            setTimeout(function () {
                getReportWrap.parentNode.removeChild(getReportWrap);
            }, newReportSettings.cssAnimationDuration);
            // remove element off

        });
        // callback off

    }
    // report wrap off

}
// Notiflix: Report Single off

// Notiflix: Report SVG Success on
const notiflixReportSvgSuccess = function (width, color) {

    if (!width) { width = '110px'; }
    if (!color) { color = '#00b462'; }

    const reportSvgSuccess = `<svg id="NXReportSuccess" fill="${color}" width="${width}" height="${width}" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd" viewBox="0 0 120 120" xmlns:xlink="http://www.w3.org/1999/xlink"><style>@-webkit-keyframes NXReportSuccess5-animation{0%{-webkit-transform: translate(60px, 57.7px) scale(0.5, 0.5) translate(-60px, -57.7px);transform: translate(60px, 57.7px) scale(0.5, 0.5) translate(-60px, -57.7px);}50%{-webkit-transform: translate(60px, 57.7px) scale(1, 1) translate(-60px, -57.7px);transform: translate(60px, 57.7px) scale(1, 1) translate(-60px, -57.7px);}60%{-webkit-transform: translate(60px, 57.7px) scale(0.95, 0.95) translate(-60px, -57.7px);transform: translate(60px, 57.7px) scale(0.95, 0.95) translate(-60px, -57.7px);}100%{-webkit-transform: translate(60px, 57.7px) scale(1, 1) translate(-60px, -57.7px);transform: translate(60px, 57.7px) scale(1, 1) translate(-60px, -57.7px);}}@keyframes NXReportSuccess5-animation{0%{-webkit-transform: translate(60px, 57.7px) scale(0.5, 0.5) translate(-60px, -57.7px);transform: translate(60px, 57.7px) scale(0.5, 0.5) translate(-60px, -57.7px);}50%{-webkit-transform: translate(60px, 57.7px) scale(1, 1) translate(-60px, -57.7px);transform: translate(60px, 57.7px) scale(1, 1) translate(-60px, -57.7px);}60%{-webkit-transform: translate(60px, 57.7px) scale(0.95, 0.95) translate(-60px, -57.7px);transform: translate(60px, 57.7px) scale(0.95, 0.95) translate(-60px, -57.7px);}100%{-webkit-transform: translate(60px, 57.7px) scale(1, 1) translate(-60px, -57.7px);transform: translate(60px, 57.7px) scale(1, 1) translate(-60px, -57.7px);}}@-webkit-keyframes NXReportSuccess6-animation{0%{opacity: 0;}50%{opacity: 1;}100%{opacity: 1;}}@keyframes NXReportSuccess6-animation{0%{opacity: 0;}50%{opacity: 1;}100%{opacity: 1;}}@-webkit-keyframes NXReportSuccess4-animation{0%{opacity: 0;}40%{opacity: 1;}100%{opacity: 1;}}@keyframes NXReportSuccess4-animation{0%{opacity: 0;}40%{opacity: 1;}100%{opacity: 1;}}@-webkit-keyframes NXReportSuccess3-animation{0%{-webkit-transform: translate(60px, 60px) scale(0.5, 0.5) translate(-60px, -60px);transform: translate(60px, 60px) scale(0.5, 0.5) translate(-60px, -60px);}40%{-webkit-transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);}60%{-webkit-transform: translate(60px, 60px) scale(0.95, 0.95) translate(-60px, -60px);transform: translate(60px, 60px) scale(0.95, 0.95) translate(-60px, -60px);}100%{-webkit-transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);}}@keyframes NXReportSuccess3-animation{0%{-webkit-transform: translate(60px, 60px) scale(0.5, 0.5) translate(-60px, -60px);transform: translate(60px, 60px) scale(0.5, 0.5) translate(-60px, -60px);}40%{-webkit-transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);}60%{-webkit-transform: translate(60px, 60px) scale(0.95, 0.95) translate(-60px, -60px);transform: translate(60px, 60px) scale(0.95, 0.95) translate(-60px, -60px);}100%{-webkit-transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);}}#NXReportSuccess *{-webkit-animation-duration: 1.2s;animation-duration: 1.2s;-webkit-animation-timing-function: cubic-bezier(0, 0, 1, 1);animation-timing-function: cubic-bezier(0, 0, 1, 1);}#NXReportSuccess4{fill: inherit;-webkit-animation-name: NXReportSuccess4-animation;animation-name: NXReportSuccess4-animation;-webkit-animation-timing-function: cubic-bezier(0.42, 0, 0.58, 1);animation-timing-function: cubic-bezier(0.42, 0, 0.58, 1);opacity: 1;}#NXReportSuccess6{fill: inherit;-webkit-animation-name: NXReportSuccess6-animation;animation-name: NXReportSuccess6-animation;opacity: 1;-webkit-animation-timing-function: cubic-bezier(0.42, 0, 0.58, 1);animation-timing-function: cubic-bezier(0.42, 0, 0.58, 1);}#NXReportSuccess3{-webkit-animation-name: NXReportSuccess3-animation;animation-name: NXReportSuccess3-animation;-webkit-transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);-webkit-animation-timing-function: cubic-bezier(0.42, 0, 0.58, 1);animation-timing-function: cubic-bezier(0.42, 0, 0.58, 1);}#NXReportSuccess5{-webkit-animation-name: NXReportSuccess5-animation;animation-name: NXReportSuccess5-animation;-webkit-transform: translate(60px, 57.7px) scale(1, 1) translate(-60px, -57.7px);transform: translate(60px, 57.7px) scale(1, 1) translate(-60px, -57.7px);-webkit-animation-timing-function: cubic-bezier(0.42, 0, 0.58, 1);animation-timing-function: cubic-bezier(0.42, 0, 0.58, 1);}</style><g id="NXReportSuccess1"><g id="NXReportSuccess2"><g id="NXReportSuccess3" data-animator-group="true" data-animator-type="2"><path d="M60 115.38c-30.54,0 -55.38,-24.84 -55.38,-55.38 0,-30.54 24.84,-55.38 55.38,-55.38 30.54,0 55.38,24.84 55.38,55.38 0,30.54 -24.84,55.38 -55.38,55.38zm0 -115.38c-33.08,0 -60,26.92 -60,60 0,33.08 26.92,60 60,60 33.08,0 60,-26.92 60,-60 0,-33.08 -26.92,-60 -60,-60z" id="NXReportSuccess4"/></g><g id="NXReportSuccess5" data-animator-group="true" data-animator-type="2"><path d="M88.27 35.39l-35.47 39.9 -21.37 -17.09c-0.98,-0.81 -2.44,-0.63 -3.24,0.36 -0.79,0.99 -0.63,2.44 0.36,3.24l23.08 18.46c0.43,0.34 0.93,0.51 1.44,0.51 0.64,0 1.27,-0.26 1.74,-0.78l36.91 -41.53c0.86,-0.96 0.76,-2.42 -0.19,-3.26 -0.95,-0.86 -2.41,-0.77 -3.26,0.19z" id="NXReportSuccess6"/></g></g></g></svg>`;

    return reportSvgSuccess;

}
// Notiflix: Report SVG Success off

// Notiflix: Report SVG Failure on
const notiflixReportSvgFailure = function (width, color) {

    if (!width) { width = '110px'; }
    if (!color) { color = '#f44336'; }

    const reportSvgFailure = `<svg id="NXReportFailure" fill="${color}" width="${width}" height="${width}" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd" viewBox="0 0 120 120" xmlns:xlink="http://www.w3.org/1999/xlink"><style>@-webkit-keyframes NXReportFailure4-animation{0%{opacity: 0;}40%{opacity: 1;}100%{opacity: 1;}}@keyframes NXReportFailure4-animation{0%{opacity: 0;}40%{opacity: 1;}100%{opacity: 1;}}@-webkit-keyframes NXReportFailure3-animation{0%{-webkit-transform: translate(60px, 60px) scale(0.5, 0.5) translate(-60px, -60px);transform: translate(60px, 60px) scale(0.5, 0.5) translate(-60px, -60px);}40%{-webkit-transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);}60%{-webkit-transform: translate(60px, 60px) scale(0.95, 0.95) translate(-60px, -60px);transform: translate(60px, 60px) scale(0.95, 0.95) translate(-60px, -60px);}100%{-webkit-transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);}}@keyframes NXReportFailure3-animation{0%{-webkit-transform: translate(60px, 60px) scale(0.5, 0.5) translate(-60px, -60px);transform: translate(60px, 60px) scale(0.5, 0.5) translate(-60px, -60px);}40%{-webkit-transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);}60%{-webkit-transform: translate(60px, 60px) scale(0.95, 0.95) translate(-60px, -60px);transform: translate(60px, 60px) scale(0.95, 0.95) translate(-60px, -60px);}100%{-webkit-transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);}}@-webkit-keyframes NXReportFailure5-animation{0%{-webkit-transform: translate(60px, 60px) scale(0.5, 0.5) translate(-60px, -60px);transform: translate(60px, 60px) scale(0.5, 0.5) translate(-60px, -60px);}50%{-webkit-transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);}60%{-webkit-transform: translate(60px, 60px) scale(0.95, 0.95) translate(-60px, -60px);transform: translate(60px, 60px) scale(0.95, 0.95) translate(-60px, -60px);}100%{-webkit-transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);}}@keyframes NXReportFailure5-animation{0%{-webkit-transform: translate(60px, 60px) scale(0.5, 0.5) translate(-60px, -60px);transform: translate(60px, 60px) scale(0.5, 0.5) translate(-60px, -60px);}50%{-webkit-transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);}60%{-webkit-transform: translate(60px, 60px) scale(0.95, 0.95) translate(-60px, -60px);transform: translate(60px, 60px) scale(0.95, 0.95) translate(-60px, -60px);}100%{-webkit-transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);}}@-webkit-keyframes NXReportFailure6-animation{0%{opacity: 0;}50%{opacity: 1;}100%{opacity: 1;}}@keyframes NXReportFailure6-animation{0%{opacity: 0;}50%{opacity: 1;}100%{opacity: 1;}}#NXReportFailure *{-webkit-animation-duration: 1.2s;animation-duration: 1.2s;-webkit-animation-timing-function: cubic-bezier(0, 0, 1, 1);animation-timing-function: cubic-bezier(0, 0, 1, 1);}#NXReportFailure6{fill:inherit;-webkit-animation-name: NXReportFailure6-animation;animation-name: NXReportFailure6-animation;-webkit-animation-timing-function: cubic-bezier(0.42, 0, 0.58, 1);animation-timing-function: cubic-bezier(0.42, 0, 0.58, 1);opacity: 1;}#NXReportFailure5{-webkit-animation-name: NXReportFailure5-animation;animation-name: NXReportFailure5-animation;-webkit-animation-timing-function: cubic-bezier(0.42, 0, 0.58, 1);animation-timing-function: cubic-bezier(0.42, 0, 0.58, 1);-webkit-transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);}#NXReportFailure3{-webkit-animation-name: NXReportFailure3-animation;animation-name: NXReportFailure3-animation;-webkit-animation-timing-function: cubic-bezier(0.42, 0, 0.58, 1);animation-timing-function: cubic-bezier(0.42, 0, 0.58, 1);-webkit-transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);}#NXReportFailure4{fill:inherit;-webkit-animation-name: NXReportFailure4-animation;animation-name: NXReportFailure4-animation;-webkit-animation-timing-function: cubic-bezier(0.42, 0, 0.58, 1);animation-timing-function: cubic-bezier(0.42, 0, 0.58, 1);opacity: 1;}</style><g id="NXReportFailure1"><g id="NXReportFailure2"><g id="NXReportFailure3" data-animator-group="true" data-animator-type="2"><path d="M4.35 34.95c0,-16.82 13.78,-30.6 30.6,-30.6l50.1 0c16.82,0 30.6,13.78 30.6,30.6l0 50.1c0,16.82 -13.78,30.6 -30.6,30.6l-50.1 0c-16.82,0 -30.6,-13.78 -30.6,-30.6l0 -50.1zm30.6 85.05l50.1 0c19.22,0 34.95,-15.73 34.95,-34.95l0 -50.1c0,-19.22 -15.73,-34.95 -34.95,-34.95l-50.1 0c-19.22,0 -34.95,15.73 -34.95,34.95l0 50.1c0,19.22 15.73,34.95 34.95,34.95z" id="NXReportFailure4"/></g><g id="NXReportFailure5" data-animator-group="true" data-animator-type="2"><path d="M82.4 37.6c-0.9,-0.9 -2.37,-0.9 -3.27,0l-19.13 19.13 -19.14 -19.13c-0.9,-0.9 -2.36,-0.9 -3.26,0 -0.9,0.9 -0.9,2.35 0,3.26l19.13 19.14 -19.13 19.13c-0.9,0.9 -0.9,2.37 0,3.27 0.45,0.45 1.04,0.68 1.63,0.68 0.59,0 1.18,-0.23 1.63,-0.68l19.14 -19.14 19.13 19.14c0.45,0.45 1.05,0.68 1.64,0.68 0.58,0 1.18,-0.23 1.63,-0.68 0.9,-0.9 0.9,-2.37 0,-3.27l-19.14 -19.13 19.14 -19.14c0.9,-0.91 0.9,-2.36 0,-3.26z" id="NXReportFailure6"/></g></g></g></svg>`;

    return reportSvgFailure;
}
// Notiflix: Report SVG Failure off

// Notiflix: Report SVG Warning on
const notiflixReportSvgWarning = function (width, color) {

    if (!width) { width = '110px'; }
    if (!color) { color = '#f2bd1d'; }

    const reportSvgWarning = `<svg id="NXReportWarning" fill="${color}" width="${width}" height="${width}" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd" viewBox="0 0 120 120" xmlns:xlink="http://www.w3.org/1999/xlink"><style>@-webkit-keyframes NXReportWarning3-animation{0%{opacity: 0;}40%{opacity: 1;}100%{opacity: 1;}}@keyframes NXReportWarning3-animation{0%{opacity: 0;}40%{opacity: 1;}100%{opacity: 1;}}@-webkit-keyframes NXReportWarning2-animation{0%{-webkit-transform: translate(60px, 60px) scale(0.5, 0.5) translate(-60px, -60px);transform: translate(60px, 60px) scale(0.5, 0.5) translate(-60px, -60px);}40%{-webkit-transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);}60%{-webkit-transform: translate(60px, 60px) scale(0.95, 0.95) translate(-60px, -60px);transform: translate(60px, 60px) scale(0.95, 0.95) translate(-60px, -60px);}100%{-webkit-transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);}}@keyframes NXReportWarning2-animation{0%{-webkit-transform: translate(60px, 60px) scale(0.5, 0.5) translate(-60px, -60px);transform: translate(60px, 60px) scale(0.5, 0.5) translate(-60px, -60px);}40%{-webkit-transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);}60%{-webkit-transform: translate(60px, 60px) scale(0.95, 0.95) translate(-60px, -60px);transform: translate(60px, 60px) scale(0.95, 0.95) translate(-60px, -60px);}100%{-webkit-transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);}}@-webkit-keyframes NXReportWarning4-animation{0%{-webkit-transform: translate(60px, 66.6px) scale(0.5, 0.5) translate(-60px, -66.6px);transform: translate(60px, 66.6px) scale(0.5, 0.5) translate(-60px, -66.6px);}50%{-webkit-transform: translate(60px, 66.6px) scale(1, 1) translate(-60px, -66.6px);transform: translate(60px, 66.6px) scale(1, 1) translate(-60px, -66.6px);}60%{-webkit-transform: translate(60px, 66.6px) scale(0.95, 0.95) translate(-60px, -66.6px);transform: translate(60px, 66.6px) scale(0.95, 0.95) translate(-60px, -66.6px);}100%{-webkit-transform: translate(60px, 66.6px) scale(1, 1) translate(-60px, -66.6px);transform: translate(60px, 66.6px) scale(1, 1) translate(-60px, -66.6px);}}@keyframes NXReportWarning4-animation{0%{-webkit-transform: translate(60px, 66.6px) scale(0.5, 0.5) translate(-60px, -66.6px);transform: translate(60px, 66.6px) scale(0.5, 0.5) translate(-60px, -66.6px);}50%{-webkit-transform: translate(60px, 66.6px) scale(1, 1) translate(-60px, -66.6px);transform: translate(60px, 66.6px) scale(1, 1) translate(-60px, -66.6px);}60%{-webkit-transform: translate(60px, 66.6px) scale(0.95, 0.95) translate(-60px, -66.6px);transform: translate(60px, 66.6px) scale(0.95, 0.95) translate(-60px, -66.6px);}100%{-webkit-transform: translate(60px, 66.6px) scale(1, 1) translate(-60px, -66.6px);transform: translate(60px, 66.6px) scale(1, 1) translate(-60px, -66.6px);}}@-webkit-keyframes NXReportWarning5-animation{0%{opacity: 0;}50%{opacity: 1;}100%{opacity: 1;}}@keyframes NXReportWarning5-animation{0%{opacity: 0;}50%{opacity: 1;}100%{opacity: 1;}}#NXReportWarning *{-webkit-animation-duration: 1.2s;animation-duration: 1.2s;-webkit-animation-timing-function: cubic-bezier(0, 0, 1, 1);animation-timing-function: cubic-bezier(0, 0, 1, 1);}#NXReportWarning3{fill: inherit;-webkit-animation-name: NXReportWarning3-animation;animation-name: NXReportWarning3-animation;-webkit-animation-timing-function: cubic-bezier(0.42, 0, 0.58, 1);animation-timing-function: cubic-bezier(0.42, 0, 0.58, 1);opacity: 1;}#NXReportWarning5{fill: inherit;-webkit-animation-name: NXReportWarning5-animation;animation-name: NXReportWarning5-animation;-webkit-animation-timing-function: cubic-bezier(0.42, 0, 0.58, 1);animation-timing-function: cubic-bezier(0.42, 0, 0.58, 1);opacity: 1;}#NXReportWarning4{-webkit-animation-name: NXReportWarning4-animation;animation-name: NXReportWarning4-animation;-webkit-animation-timing-function: cubic-bezier(0.42, 0, 0.58, 1);animation-timing-function: cubic-bezier(0.42, 0, 0.58, 1);-webkit-transform: translate(60px, 66.6px) scale(1, 1) translate(-60px, -66.6px);transform: translate(60px, 66.6px) scale(1, 1) translate(-60px, -66.6px);}#NXReportWarning2{-webkit-animation-name: NXReportWarning2-animation;animation-name: NXReportWarning2-animation;-webkit-animation-timing-function: cubic-bezier(0.42, 0, 0.58, 1);animation-timing-function: cubic-bezier(0.42, 0, 0.58, 1);-webkit-transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);}</style><g id="NXReportWarning1"><g id="NXReportWarning2" data-animator-group="true" data-animator-type="2"><path d="M115.46 106.15l-54.04 -93.8c-0.61,-1.06 -2.23,-1.06 -2.84,0l-54.04 93.8c-0.62,1.07 0.21,2.29 1.42,2.29l108.08 0c1.21,0 2.04,-1.22 1.42,-2.29zm-50.29 -95.95l54.04 93.8c2.28,3.96 -0.65,8.78 -5.17,8.78l-108.08 0c-4.52,0 -7.45,-4.82 -5.17,-8.78l54.04 -93.8c2.28,-3.95 8.03,-4 10.34,0z" id="NXReportWarning3"/></g><g id="NXReportWarning4" data-animator-group="true" data-animator-type="2"><path d="M57.83 94.01c0,1.2 0.97,2.17 2.17,2.17 1.2,0 2.17,-0.97 2.17,-2.17l0 -3.2c0,-1.2 -0.97,-2.17 -2.17,-2.17 -1.2,0 -2.17,0.97 -2.17,2.17l0 3.2zm0 -14.15c0,1.2 0.97,2.17 2.17,2.17 1.2,0 2.17,-0.97 2.17,-2.17l0 -40.65c0,-1.2 -0.97,-2.17 -2.17,-2.17 -1.2,0 -2.17,0.97 -2.17,2.17l0 40.65z" id="NXReportWarning5"/></g></g></svg>`;

    return reportSvgWarning;
}
// Notiflix: Report SVG Warning off

// Notiflix: Report SVG Info on
const notiflixReportSvgInfo = function (width, color) {

    if (!width) { width = '110px'; }
    if (!color) { color = '#00bcd4'; }

    const reportSvgInfo = `<svg id="NXReportInfo" fill="${color}" width="${width}" height="${width}" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd" viewBox="0 0 120 120" xmlns:xlink="http://www.w3.org/1999/xlink"><style>@-webkit-keyframes NXReportInfo5-animation{0%{opacity: 0;}50%{opacity: 1;}100%{opacity: 1;}}@keyframes NXReportInfo5-animation{0%{opacity: 0;}50%{opacity: 1;}100%{opacity: 1;}}@-webkit-keyframes NXReportInfo4-animation{0%{-webkit-transform: translate(60px, 60px) scale(0.5, 0.5) translate(-60px, -60px);transform: translate(60px, 60px) scale(0.5, 0.5) translate(-60px, -60px);}50%{-webkit-transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);}60%{-webkit-transform: translate(60px, 60px) scale(0.95, 0.95) translate(-60px, -60px);transform: translate(60px, 60px) scale(0.95, 0.95) translate(-60px, -60px);}100%{-webkit-transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);}}@keyframes NXReportInfo4-animation{0%{-webkit-transform: translate(60px, 60px) scale(0.5, 0.5) translate(-60px, -60px);transform: translate(60px, 60px) scale(0.5, 0.5) translate(-60px, -60px);}50%{-webkit-transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);}60%{-webkit-transform: translate(60px, 60px) scale(0.95, 0.95) translate(-60px, -60px);transform: translate(60px, 60px) scale(0.95, 0.95) translate(-60px, -60px);}100%{-webkit-transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);}}@-webkit-keyframes NXReportInfo3-animation{0%{opacity: 0;}40%{opacity: 1;}100%{opacity: 1;}}@keyframes NXReportInfo3-animation{0%{opacity: 0;}40%{opacity: 1;}100%{opacity: 1;}}@-webkit-keyframes NXReportInfo2-animation{0%{-webkit-transform: translate(60px, 60px) scale(0.5, 0.5) translate(-60px, -60px);transform: translate(60px, 60px) scale(0.5, 0.5) translate(-60px, -60px);}40%{-webkit-transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);}60%{-webkit-transform: translate(60px, 60px) scale(0.95, 0.95) translate(-60px, -60px);transform: translate(60px, 60px) scale(0.95, 0.95) translate(-60px, -60px);}100%{-webkit-transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);}}@keyframes NXReportInfo2-animation{0%{-webkit-transform: translate(60px, 60px) scale(0.5, 0.5) translate(-60px, -60px);transform: translate(60px, 60px) scale(0.5, 0.5) translate(-60px, -60px);}40%{-webkit-transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);}60%{-webkit-transform: translate(60px, 60px) scale(0.95, 0.95) translate(-60px, -60px);transform: translate(60px, 60px) scale(0.95, 0.95) translate(-60px, -60px);}100%{-webkit-transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);}}#NXReportInfo *{-webkit-animation-duration: 1.2s;animation-duration: 1.2s;-webkit-animation-timing-function: cubic-bezier(0, 0, 1, 1);animation-timing-function: cubic-bezier(0, 0, 1, 1);}#NXReportInfo3{fill:inherit;-webkit-animation-name: NXReportInfo3-animation;animation-name: NXReportInfo3-animation;-webkit-animation-timing-function: cubic-bezier(0.42, 0, 0.58, 1);animation-timing-function: cubic-bezier(0.42, 0, 0.58, 1);opacity: 1;}#NXReportInfo5{fill:inherit;-webkit-animation-name: NXReportInfo5-animation;animation-name: NXReportInfo5-animation;-webkit-animation-timing-function: cubic-bezier(0.42, 0, 0.58, 1);animation-timing-function: cubic-bezier(0.42, 0, 0.58, 1);opacity: 1;}#NXReportInfo2{-webkit-animation-name: NXReportInfo2-animation;animation-name: NXReportInfo2-animation;-webkit-animation-timing-function: cubic-bezier(0.42, 0, 0.58, 1);animation-timing-function: cubic-bezier(0.42, 0, 0.58, 1);-webkit-transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);}#NXReportInfo4{-webkit-animation-name: NXReportInfo4-animation;animation-name: NXReportInfo4-animation;-webkit-animation-timing-function: cubic-bezier(0.42, 0, 0.58, 1);animation-timing-function: cubic-bezier(0.42, 0, 0.58, 1);-webkit-transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);transform: translate(60px, 60px) scale(1, 1) translate(-60px, -60px);}</style><g id="NXReportInfo1"><g id="NXReportInfo2" data-animator-group="true" data-animator-type="2"><path d="M60 115.38c-30.54,0 -55.38,-24.84 -55.38,-55.38 0,-30.54 24.84,-55.38 55.38,-55.38 30.54,0 55.38,24.84 55.38,55.38 0,30.54 -24.84,55.38 -55.38,55.38zm0 -115.38c-33.08,0 -60,26.92 -60,60 0,33.08 26.92,60 60,60 33.08,0 60,-26.92 60,-60 0,-33.08 -26.92,-60 -60,-60z" id="NXReportInfo3"/></g><g id="NXReportInfo4" data-animator-group="true" data-animator-type="2"><path d="M57.75 43.85c0,-1.24 1.01,-2.25 2.25,-2.25 1.24,0 2.25,1.01 2.25,2.25l0 48.18c0,1.24 -1.01,2.25 -2.25,2.25 -1.24,0 -2.25,-1.01 -2.25,-2.25l0 -48.18zm0 -15.88c0,-1.24 1.01,-2.25 2.25,-2.25 1.24,0 2.25,1.01 2.25,2.25l0 3.32c0,1.25 -1.01,2.25 -2.25,2.25 -1.24,0 -2.25,-1 -2.25,-2.25l0 -3.32z" id="NXReportInfo5"/></g></g></svg>`;

    return reportSvgInfo;

}
// Notiflix: Report SVG Info off


// Notiflix: Confirm Single on
const NotiflixConfirm = function (title, message, okButtonText, cancelButtonText, okButtonCallback, cancelButtonCallback) {

    // if plainText true = HTML tags not allowed on
    if (newConfirmSettings.plainText) {
        title = notiflixPlaintext(title);
        message = notiflixPlaintext(message);
        okButtonText = notiflixPlaintext(okButtonText);
        cancelButtonText = notiflixPlaintext(cancelButtonText);
    }
    // if plainText true = HTML tags not allowed off

    // if plainText false but the contents length more than *MaxLength = HTML tags error on
    if (!newConfirmSettings.plainText) {
        if (title.length > newConfirmSettings.titleMaxLength) {
            title = 'HTML Tags Error'; // title html error
            message = 'Your Title content length is more than "titleMaxLength" option.'; // message html error
            okButtonText = 'Okay'; // button html error
            cancelButtonText = '...'; // button html error
        }

        if (message.length > newConfirmSettings.messageMaxLength) {
            title = 'HTML Tags Error'; // title html error
            message = 'Your Message content length is more than "messageMaxLength" option.'; // message html error
            okButtonText = 'Okay'; // button html error
            cancelButtonText = '...'; // button html error
        }

        if ((okButtonText.length || cancelButtonText.length) > newConfirmSettings.buttonsMaxLength) {
            title = 'HTML Tags Error'; // title html error
            message = 'Your Buttons contents length is more than "buttonsMaxLength" option.'; // message html error
            okButtonText = 'Okay'; // button html error
            cancelButtonText = '...'; // button html error
        }
    }
    // if plainText false but the contents length more than *MaxLength = HTML tags error off


    // max length on
    if (title.length > newConfirmSettings.titleMaxLength) {
        title = `${title.substring(0, newConfirmSettings.titleMaxLength)}...`;
    }

    if (message.length > newConfirmSettings.messageMaxLength) {
        message = `${message.substring(0, newConfirmSettings.messageMaxLength)}...`;
    }

    if (okButtonText.length > newConfirmSettings.buttonsMaxLength) {
        okButtonText = `${okButtonText.substring(0, newConfirmSettings.buttonsMaxLength)}...`;
    }

    if (cancelButtonText.length > newConfirmSettings.buttonsMaxLength) {
        cancelButtonText = `${cancelButtonText.substring(0, newConfirmSettings.buttonsMaxLength)}...`;
    }
    // max length off


    // if cssAnimaion false -> duration on
    if (!newConfirmSettings.cssAnimation) {
        newConfirmSettings.cssAnimationDuration = 0;
    }
    // if cssAnimaion false -> duration off


    // confirm wrap on
    const docBody = document.body;

    const ntflxConfirmWrap = document.createElement('div');
    ntflxConfirmWrap.id = confirmSettings.ID;
    ntflxConfirmWrap.className = `${newConfirmSettings.className} ${(newConfirmSettings.cssAnimation ? 'with-animation nx-' + newConfirmSettings.cssAnimationStyle : '')}`;
    ntflxConfirmWrap.style.width = newConfirmSettings.width;
    ntflxConfirmWrap.style.zIndex = newConfirmSettings.zindex;
    ntflxConfirmWrap.style.margin = 'auto';

    // rtl on
    if (newConfirmSettings.rtl) {
        ntflxConfirmWrap.setAttribute('dir', 'rtl');
        ntflxConfirmWrap.classList.add('rtl-on');
    }
    // rtl off

    // font-family on
    ntflxConfirmWrap.style.fontFamily = `"${newConfirmSettings.fontFamily}", sans-serif`;
    // font-family off

    // if background overlay true on
    let confirmOverlay = '';
    if (newConfirmSettings.backOverlay) {
        confirmOverlay = `<div class="${newConfirmSettings.className}-overlay ${(newConfirmSettings.cssAnimation ? 'with-animation' : '')}" style="background:${newConfirmSettings.backOverlayColor};animation-duration:${newConfirmSettings.cssAnimationDuration}ms;"></div>`;
    }
    // if background overlay true off


    // if have a callback - cancel button on
    let cancelButtonHTML = '';
    if (okButtonCallback) {
        cancelButtonHTML = `<a id="NXConfirmButtonCancel" class="confirm-button-cancel" style="color:${newConfirmSettings.cancelButtonColor};background:${newConfirmSettings.cancelButtonBackground};font-size:${newConfirmSettings.buttonsFontSize};">${cancelButtonText}</a>`;
    }
    // if have a callback - cancel button off

    ntflxConfirmWrap.innerHTML = `${confirmOverlay}
        <div class="${newConfirmSettings.className}-content" style="background:${newConfirmSettings.backgroundColor}; animation-duration:${newConfirmSettings.cssAnimationDuration}ms; border-radius:${newConfirmSettings.borderRadius};">
            <div class="${newConfirmSettings.className}-head">
                <h5 style="color:${newConfirmSettings.titleColor};font-size:${newConfirmSettings.titleFontSize};">${title}</h5>
                <p style="color:${newConfirmSettings.messageColor};font-size:${newConfirmSettings.messageFontSize};">${message}</p>
            </div>
            <div class="${newConfirmSettings.className}-buttons">
                <a id="NXConfirmButtonOk" class="confirm-button-ok ${(okButtonCallback ? '' : 'full')}" style="color:${newConfirmSettings.okButtonColor};background:${newConfirmSettings.okButtonBackground};font-size:${newConfirmSettings.buttonsFontSize};">${okButtonText}</a>
                ${cancelButtonHTML}
            </div>
        </div>`;
    // confirm wrap off

    // if there is no confirm box on
    if (!document.getElementById(ntflxConfirmWrap.id)) {
        docBody.appendChild(ntflxConfirmWrap);

        // position on                  
        if (newConfirmSettings.position === 'center') { // if center

            let windowH = Math.round(window.innerHeight);
            let confirmH = Math.round(document.getElementById(ntflxConfirmWrap.id).offsetHeight);

            ntflxConfirmWrap.style.top = `${parseInt((windowH - confirmH) / 2)}px`;
            ntflxConfirmWrap.style.left = newConfirmSettings.distance;
            ntflxConfirmWrap.style.right = newConfirmSettings.distance;
            ntflxConfirmWrap.style.bottom = 'auto';

        } else if (newConfirmSettings.position === 'right-top') { // if right-top

            ntflxConfirmWrap.style.right = newConfirmSettings.distance;
            ntflxConfirmWrap.style.top = newConfirmSettings.distance;
            ntflxConfirmWrap.style.bottom = 'auto';
            ntflxConfirmWrap.style.left = 'auto';

        } else if (newConfirmSettings.position === 'right-bottom') { // if right-bottom

            ntflxConfirmWrap.style.right = newConfirmSettings.distance;
            ntflxConfirmWrap.style.bottom = newConfirmSettings.distance;
            ntflxConfirmWrap.style.top = 'auto';
            ntflxConfirmWrap.style.left = 'auto';

        } else if (newConfirmSettings.position === 'left-top') { // if left-top

            ntflxConfirmWrap.style.left = newConfirmSettings.distance;
            ntflxConfirmWrap.style.top = newConfirmSettings.distance;
            ntflxConfirmWrap.style.right = 'auto';
            ntflxConfirmWrap.style.bottom = 'auto';

        } else if (newConfirmSettings.position === 'left-bottom') { // if left-bottom

            ntflxConfirmWrap.style.left = newConfirmSettings.distance;
            ntflxConfirmWrap.style.bottom = newConfirmSettings.distance;
            ntflxConfirmWrap.style.top = 'auto';
            ntflxConfirmWrap.style.right = 'auto';

        } else { // if center-top or else

            ntflxConfirmWrap.style.top = newConfirmSettings.distance;
            ntflxConfirmWrap.style.left = 0;
            ntflxConfirmWrap.style.right = 0;
            ntflxConfirmWrap.style.bottom = 'auto';
        }
        // position off

        // buttons listener on
        const confirmCloseWrap = document.getElementById(ntflxConfirmWrap.id);
        const okButton = document.getElementById('NXConfirmButtonOk');

        // ok button listener on
        okButton.addEventListener('click', function () {

            // if ok callback && if ok callback is a function
            if (okButtonCallback && typeof okButtonCallback === 'function') {
                okButtonCallback();
            }

            confirmCloseWrap.classList.add('remove');

            setTimeout(function () {
                confirmCloseWrap.parentNode.removeChild(confirmCloseWrap);
            }, newConfirmSettings.cssAnimationDuration);

        });
        // ok button listener off

        // if ok callback && if ok callback a function => add Cancel Button listener on
        if (okButtonCallback && typeof okButtonCallback === 'function') {

            // cancel button listener on
            const cancelButton = document.getElementById('NXConfirmButtonCancel');
            cancelButton.addEventListener('click', function () {

                // if cancel callback && if cancel callback a function
                if (cancelButtonCallback && typeof cancelButtonCallback === 'function') {
                    cancelButtonCallback();
                }

                confirmCloseWrap.classList.add('remove');

                setTimeout(function () {
                    confirmCloseWrap.parentNode.removeChild(confirmCloseWrap);
                }, newConfirmSettings.cssAnimationDuration);

            });
            // cancel button listener off

        }
        // if ok callback && if ok callback a function => add Cancel Button listener off
        // buttons listener off

    }
    // if there is no confirm box off

}
// Notiflix: Confirm Single off


// Notiflix: Loading Single on
const NotiflixLoading = function (message, iconType, display, theDelay) {

    if (display) { // show it

        // if message settings on
        if (message.toString().length > newLoadingSettings.messageMaxLength) {
            message = `${notiflixPlaintext(message).toString().substring(0, newLoadingSettings.messageMaxLength)}...`;
        } else {
            message = `${notiflixPlaintext(message).toString()}`;
        }

        let intSvgSize = parseInt(newLoadingSettings.svgSize.slice(0, -2));
        let messageHTML = '';
        if (message.length > 0) {

            let messagePosTop = `${parseInt(Math.round(intSvgSize - (intSvgSize / 4))).toString()}px`;
            let messageHeight = `${(parseInt(newLoadingSettings.messageFontSize.slice(0, 2)) * 1.2).toString()}px`;

            messageHTML = `<p id="${newLoadingSettings.messageID}" class="loading-message" style="color:${newLoadingSettings.messageColor};font-size:${newLoadingSettings.messageFontSize};height:${messageHeight}; top:${messagePosTop};">${message}</p>`;

        }
        // if message settings off

        // if cssAnimaion false -> duration on
        if (!newLoadingSettings.cssAnimation) {
            newLoadingSettings.cssAnimationDuration = 0;
        }
        // if cssAnimaion false -> duration off

        // svgIcon on
        let svgIcon = '';
        if (iconType === 'standard') {
            svgIcon = notiflixLoadingSvgStandard(newLoadingSettings.svgSize, newLoadingSettings.svgColor);
        } else if (iconType === 'hourglass') {
            svgIcon = notiflixLoadingSvgHourglass(newLoadingSettings.svgSize, newLoadingSettings.svgColor);
        } else if (iconType === 'circle') {
            svgIcon = notiflixLoadingSvgCircle(newLoadingSettings.svgSize, newLoadingSettings.svgColor);
        } else if (iconType === 'arrows') {
            svgIcon = notiflixLoadingSvgArrows(newLoadingSettings.svgSize, newLoadingSettings.svgColor);
        } else if (iconType === 'dots') {
            svgIcon = notiflixLoadingSvgDots(newLoadingSettings.svgSize, newLoadingSettings.svgColor);
        } else if (iconType === 'pulse') {
            svgIcon = notiflixLoadingSvgPulse(newLoadingSettings.svgSize, newLoadingSettings.svgColor);
        } else if (iconType === 'custom' && newLoadingSettings.customSvgUrl !== null) {
            svgIcon = `<img class="custom-loading-icon" width="${newLoadingSettings.svgSize}" height="${newLoadingSettings.svgSize}" src="${newLoadingSettings.customSvgUrl}" alt="Notiflix">`;
        } else if (iconType === 'custom' && newLoadingSettings.customSvgUrl == null) {
            notiflixConsoleError('Notiflix Error', 'You have to set a static SVG url to "customSvgUrl" option to use Loading Custom.');
            return false;
        } else if (iconType === 'notiflix') {
            svgIcon = notiflixLoadingSvgNotiflix(newLoadingSettings.svgSize, '#f8f8f8', '#00b462');
        }

        let svgPosTop = 0;
        if (message.length > 0) {
            svgPosTop = `-${parseInt(Math.round(intSvgSize - (intSvgSize / 4))).toString()}px`;
        }

        let svgIconHTML = `<div style="top:${svgPosTop}; width:${newLoadingSettings.svgSize}; height:${newLoadingSettings.svgSize};" class="${newLoadingSettings.className}-icon ${(message.length > 0 ? 'with-message' : '')}">${svgIcon}</div>`;
        // svgIcon off


        // loading wrap on
        const docBody = document.body;

        const ntflxLoadingWrap = document.createElement('div');
        ntflxLoadingWrap.id = loadingSettings.ID;
        ntflxLoadingWrap.className = `${newLoadingSettings.className} ${(newLoadingSettings.cssAnimation ? 'with-animation' : '')} ${(newLoadingSettings.clickToClose ? 'click-to-close' : '')}`;
        ntflxLoadingWrap.style.zIndex = newLoadingSettings.zindex;
        ntflxLoadingWrap.style.background = newLoadingSettings.backgroundColor;
        ntflxLoadingWrap.style.animationDuration = `${newLoadingSettings.cssAnimationDuration}ms`;

        // font-family on
        ntflxLoadingWrap.style.fontFamily = `"${newLoadingSettings.fontFamily}", sans-serif`;
        // font-family off

        // rtl on
        if (newLoadingSettings.rtl) {
            ntflxLoadingWrap.setAttribute('dir', 'rtl');
            ntflxLoadingWrap.classList.add('rtl-on');
        }
        // rtl off

        // append on
        ntflxLoadingWrap.innerHTML = `${svgIconHTML} ${messageHTML}`; // inner html

        if (!document.getElementById(ntflxLoadingWrap.id)) { // if not loading

            docBody.appendChild(ntflxLoadingWrap); // append

            // if click to close on            
            if (newLoadingSettings.clickToClose) {

                const loadingWrapElm = document.getElementById(ntflxLoadingWrap.id);

                loadingWrapElm.addEventListener('click', function () {

                    ntflxLoadingWrap.classList.add('remove');

                    setTimeout(function () {
                        ntflxLoadingWrap.parentNode.removeChild(ntflxLoadingWrap);
                    }, newLoadingSettings.cssAnimationDuration);

                });

            }
            // if click to close off
        }
        // append off

    } else { // Remove

        if (document.getElementById(loadingSettings.ID)) { // if has any loading

            const loadingElm = document.getElementById(loadingSettings.ID);

            setTimeout(function () {

                loadingElm.classList.add('remove');

                setTimeout(function () {
                    loadingElm.parentNode.removeChild(loadingElm);
                }, newLoadingSettings.cssAnimationDuration);

            }, theDelay);

        }

    }

}
// Notiflix: Loading Single off

// Notiflix: Loading Change Message on
const NotiflixLoadingChange = function (newMessage) {

    if (document.getElementById(loadingSettings.ID)) { // if has any loading

        if (newMessage.toString().length > newLoadingSettings.messageMaxLength) {
            newMessage = `${notiflixPlaintext(newMessage).toString().substring(0, newLoadingSettings.messageMaxLength)}...`;
        } else {
            newMessage = notiflixPlaintext(newMessage).toString();
        }

        if (newMessage.length > 0) { // if has any message

            let oldMessageElm = document.getElementById(loadingSettings.ID).getElementsByTagName('p')[0];

            if (oldMessageElm !== undefined) { // there is a message element

                oldMessageElm.innerHTML = newMessage; // change the message

            } else { // there is no message element

                // create a new message element on
                const newMessageHTML = document.createElement('p');
                newMessageHTML.id = newLoadingSettings.messageID;
                newMessageHTML.className = 'loading-message new';

                newMessageHTML.style.color = newLoadingSettings.messageColor;
                newMessageHTML.style.fontSize = newLoadingSettings.messageFontSize;

                const intSvgSize = parseInt(newLoadingSettings.svgSize.slice(0, -2));
                const messagePosTop = `${parseInt(Math.round(intSvgSize - (intSvgSize / 4))).toString()}px`;
                newMessageHTML.style.top = messagePosTop;

                const messageHeight = `${(parseInt(newLoadingSettings.messageFontSize.slice(0, 2)) * 1.2).toString()}px`;
                newMessageHTML.style.height = messageHeight;

                newMessageHTML.innerHTML = newMessage;

                const messageWrap = document.getElementById(loadingSettings.ID);
                messageWrap.appendChild(newMessageHTML);
                // create a new message element off

                // vertical align svg on
                const svgDivElm = document.getElementById(loadingSettings.ID).getElementsByTagName('div')[0];
                const svgNewPosTop = `-${parseInt(Math.round(intSvgSize - (intSvgSize / 4))).toString()}px`;
                svgDivElm.style.top = svgNewPosTop;
                // vertical align svg off

            }

        } else { // if no message
            notiflixConsoleError('Notiflix Error', 'Where is the new message?');
        }

    }

}
// Notiflix: Loading Change Message off


// Notiflix: Loading SVG standard on
const notiflixLoadingSvgStandard = (width, color) => {
    if (!width) { width = '60px'; }
    if (!color) { color = '#00b462'; }
    const standard = `<svg stroke="${color}" width="${width}" height="${width}" viewBox="0 0 38 38" style="transform:scale(0.8);" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g transform="translate(1 1)" stroke-width="2"><circle stroke-opacity=".25" cx="18" cy="18" r="18"/><path d="M36 18c0-9.94-8.06-18-18-18"><animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="1s" repeatCount="indefinite"/></path></g></g></svg>`;
    return standard;
}
// Notiflix: Loading SVG standard off

// Notiflix: Loading SVG hourglass on
const notiflixLoadingSvgHourglass = (width, color) => {
    if (!width) { width = '60px'; }
    if (!color) { color = '#00b462'; }
    const hourglass = `<svg id="NXLoadingHourglass" fill="${color}" width="${width}" height="${width}" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd" viewBox="0 0 200 200"><style>@-webkit-keyframes NXhourglass5-animation{0%{-webkit-transform: scale(1, 1);transform: scale(1, 1);}16.67%{-webkit-transform: scale(1, 0.8);transform: scale(1, 0.8);}33.33%{-webkit-transform: scale(0.88, 0.6);transform: scale(0.88, 0.6);}37.50%{-webkit-transform: scale(0.85, 0.55);transform: scale(0.85, 0.55);}41.67%{-webkit-transform: scale(0.8, 0.5);transform: scale(0.8, 0.5);}45.83%{-webkit-transform: scale(0.75, 0.45);transform: scale(0.75, 0.45);}50%{-webkit-transform: scale(0.7, 0.4);transform: scale(0.7, 0.4);}54.17%{-webkit-transform: scale(0.6, 0.35);transform: scale(0.6, 0.35);}58.33%{-webkit-transform: scale(0.5, 0.3);transform: scale(0.5, 0.3);}83.33%{-webkit-transform: scale(0.2, 0);transform: scale(0.2, 0);}100%{-webkit-transform: scale(0.2, 0);transform: scale(0.2, 0);}}@keyframes NXhourglass5-animation{0%{-webkit-transform: scale(1, 1);transform: scale(1, 1);}16.67%{-webkit-transform: scale(1, 0.8);transform: scale(1, 0.8);}33.33%{-webkit-transform: scale(0.88, 0.6);transform: scale(0.88, 0.6);}37.50%{-webkit-transform: scale(0.85, 0.55);transform: scale(0.85, 0.55);}41.67%{-webkit-transform: scale(0.8, 0.5);transform: scale(0.8, 0.5);}45.83%{-webkit-transform: scale(0.75, 0.45);transform: scale(0.75, 0.45);}50%{-webkit-transform: scale(0.7, 0.4);transform: scale(0.7, 0.4);}54.17%{-webkit-transform: scale(0.6, 0.35);transform: scale(0.6, 0.35);}58.33%{-webkit-transform: scale(0.5, 0.3);transform: scale(0.5, 0.3);}83.33%{-webkit-transform: scale(0.2, 0);transform: scale(0.2, 0);}100%{-webkit-transform: scale(0.2, 0);transform: scale(0.2, 0);}}@-webkit-keyframes NXhourglass3-animation{0%{-webkit-transform: scale(1, 0.02);transform: scale(1, 0.02);}79.17%{-webkit-transform: scale(1, 1);transform: scale(1, 1);}100%{-webkit-transform: scale(1, 1);transform: scale(1, 1);}}@keyframes NXhourglass3-animation{0%{-webkit-transform: scale(1, 0.02);transform: scale(1, 0.02);}79.17%{-webkit-transform: scale(1, 1);transform: scale(1, 1);}100%{-webkit-transform: scale(1, 1);transform: scale(1, 1);}}@-webkit-keyframes NXhourglass1-animation{0%{-webkit-transform: rotate(0deg);transform: rotate(0deg);}83.33%{-webkit-transform: rotate(0deg);transform: rotate(0deg);}100%{-webkit-transform: rotate(180deg);transform: rotate(180deg);}}@keyframes NXhourglass1-animation{0%{-webkit-transform: rotate(0deg);transform: rotate(0deg);}83.33%{-webkit-transform: rotate(0deg);transform: rotate(0deg);}100%{-webkit-transform: rotate(180deg);transform: rotate(180deg);}}#NXLoadingHourglass *{-webkit-animation-duration: 1.2s;animation-duration: 1.2s;-webkit-animation-iteration-count: infinite;animation-iteration-count: infinite;-webkit-animation-timing-function: cubic-bezier(0, 0, 1, 1);animation-timing-function: cubic-bezier(0, 0, 1, 1);}#NXhourglass7{fill: inherit;}#NXhourglass1{-webkit-animation-name: NXhourglass1-animation;animation-name: NXhourglass1-animation;-webkit-transform-origin: 50% 50%;transform-origin: 50% 50%;transform-box: fill-box;}#NXhourglass3{-webkit-animation-name: NXhourglass3-animation;animation-name: NXhourglass3-animation;-webkit-animation-timing-function: cubic-bezier(0.42, 0, 0.58, 1);animation-timing-function: cubic-bezier(0.42, 0, 0.58, 1);-webkit-transform-origin: 50% 100%;transform-origin: 50% 100%;transform-box: fill-box;}#NXhourglass5{-webkit-animation-name: NXhourglass5-animation;animation-name: NXhourglass5-animation;-webkit-transform-origin: 50% 100%;transform-origin: 50% 100%;transform-box: fill-box;}g#NXhourglass5,#NXhourglass3{fill: inherit;opacity: .4;}</style><g id="NXhourglass1" data-animator-group="true" data-animator-type="1"><g id="NXhourglass2"><g id="NXhourglass3" data-animator-group="true" data-animator-type="2"><polygon points="100,100 65.62,132.08 65.62,163.22 134.38,163.22 134.38,132.08 " id="NXhourglass4"/></g><g id="NXhourglass5" data-animator-group="true" data-animator-type="2"><polygon points="100,100 65.62,67.92 65.62,36.78 134.38,36.78 134.38,67.92" id="NXhourglass6"/></g> <path d="M51.14 38.89l8.33 0 0 14.93c0,15.1 8.29,28.99 23.34,39.1 1.88,1.25 3.04,3.97 3.04,7.08 0,3.11 -1.16,5.83 -3.04,7.09 -15.05,10.1 -23.34,23.99 -23.34,39.09l0 14.93 -8.33 0c-2.68,0 -4.86,2.18 -4.86,4.86 0,2.69 2.18,4.86 4.86,4.86l97.72 0c2.68,0 4.86,-2.17 4.86,-4.86 0,-2.68 -2.18,-4.86 -4.86,-4.86l-8.33 0 0 -14.93c0,-15.1 -8.29,-28.99 -23.34,-39.09 -1.88,-1.26 -3.04,-3.98 -3.04,-7.09 0,-3.11 1.16,-5.83 3.04,-7.08 15.05,-10.11 23.34,-24 23.34,-39.1l0 -14.93 8.33 0c2.68,0 4.86,-2.18 4.86,-4.86 0,-2.69 -2.18,-4.86 -4.86,-4.86l-97.72 0c-2.68,0 -4.86,2.17 -4.86,4.86 0,2.68 2.18,4.86 4.86,4.86zm79.67 14.93c0,15.87 -11.93,26.25 -19.04,31.03 -4.6,3.08 -7.34,8.75 -7.34,15.15 0,6.41 2.74,12.07 7.34,15.15 7.11,4.78 19.04,15.16 19.04,31.03l0 14.93 -61.62 0 0 -14.93c0,-15.87 11.93,-26.25 19.04,-31.02 4.6,-3.09 7.34,-8.75 7.34,-15.16 0,-6.4 -2.74,-12.07 -7.34,-15.15 -7.11,-4.78 -19.04,-15.16 -19.04,-31.03l0 -14.93 61.62 0 0 14.93z" id="NXhourglass7"/></g></g></svg>`;
    return hourglass;
}
// Notiflix: Loading SVG hourglass off

// Notiflix: Loading SVG circle on
const notiflixLoadingSvgCircle = (width, color) => {
    if (!width) { width = '60px'; }
    if (!color) { color = '#00b462'; }
    const circle = `<svg id="NXLoadingCircle" width="${width}" height="${width}" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="25 25 50 50" xml:space="preserve" version="1.1"><style>#NXLoadingCircle{-webkit-animation: rotate 2s linear infinite; animation: rotate 2s linear infinite; height: ${width}; -webkit-transform-origin: center center; -ms-transform-origin: center center; transform-origin: center center; width: ${width}; position: absolute; top: 0; left: 0; margin: auto;}.notiflix-loader-circle-path{stroke-dasharray: 150,200; stroke-dashoffset: -10; -webkit-animation: dash 1.5s ease-in-out infinite, color 1.5s ease-in-out infinite; animation: dash 1.5s ease-in-out infinite, color 1.5s ease-in-out infinite; stroke-linecap: round;}@-webkit-keyframes rotate{100%{-webkit-transform: rotate(360deg); transform: rotate(360deg);}}@keyframes rotate{100%{-webkit-transform: rotate(360deg); transform: rotate(360deg);}}@-webkit-keyframes dash{0%{stroke-dasharray: 1,200; stroke-dashoffset: 0;}50%{stroke-dasharray: 89,200; stroke-dashoffset: -35;}100%{stroke-dasharray: 89,200; stroke-dashoffset: -124;}}@keyframes dash{0%{stroke-dasharray: 1,200; stroke-dashoffset: 0;}50%{stroke-dasharray: 89,200; stroke-dashoffset: -35;}100%{stroke-dasharray: 89,200; stroke-dashoffset: -124;}}</style><circle class="notiflix-loader-circle-path" cx="50" cy="50" r="20" fill="none" stroke="${color}" stroke-width="2"/></svg>`;
    return circle;
}
// Notiflix: Loading SVG circle off

// Notiflix: Loading SVG arrows on
const notiflixLoadingSvgArrows = (width, color) => {
    if (!width) { width = '60px'; }
    if (!color) { color = '#00b462'; }
    const arrows = `<svg id="NXLoadingArrows" fill="${color}" width="${width}" height="${width}" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" viewBox="0 0 128 128" xml:space="preserve"><g><path fill="inherit" fill-opacity="1" d="M109.25 55.5h-36l12-12a29.54 29.54 0 0 0-49.53 12H18.75A46.04 46.04 0 0 1 96.9 31.84l12.35-12.34v36zm-90.5 17h36l-12 12a29.54 29.54 0 0 0 49.53-12h16.97A46.04 46.04 0 0 1 31.1 96.16L18.74 108.5v-36z" /><animateTransform attributeName="transform" type="rotate" from="0 64 64" to="360 64 64" dur="1.5s" repeatCount="indefinite"></animateTransform></g></svg>`;
    return arrows;
}
// Notiflix: Loading SVG arrows off

// Notiflix: Loading SVG dots on
const notiflixLoadingSvgDots = (width, color) => {
    if (!width) { width = '60px'; }
    if (!color) { color = '#00b462'; }
    const dots = `<svg id="NXLoadingDots" fill="${color}" width="${width}" height="${width}" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><g transform="translate(25 50)"><circle cx="0" cy="0" r="9" fill="inherit" transform="scale(0.239 0.239)"><animateTransform attributeName="transform" type="scale" begin="-0.266s" calcMode="spline" keySplines="0.3 0 0.7 1;0.3 0 0.7 1" values="0;1;0" keyTimes="0;0.5;1" dur="0.8s" repeatCount="indefinite"/></circle></g><g transform="translate(50 50)"> <circle cx="0" cy="0" r="9" fill="inherit" transform="scale(0.00152 0.00152)"><animateTransform attributeName="transform" type="scale" begin="-0.133s" calcMode="spline" keySplines="0.3 0 0.7 1;0.3 0 0.7 1" values="0;1;0" keyTimes="0;0.5;1" dur="0.8s" repeatCount="indefinite"/></circle></g><g transform="translate(75 50)"><circle cx="0" cy="0" r="9" fill="inherit" transform="scale(0.299 0.299)"><animateTransform attributeName="transform" type="scale" begin="0s" calcMode="spline" keySplines="0.3 0 0.7 1;0.3 0 0.7 1" values="0;1;0" keyTimes="0;0.5;1" dur="0.8s" repeatCount="indefinite"/></circle></g></svg>`;
    return dots;
}
// Notiflix: Loading SVG dots off

// Notiflix: Loading SVG pulse on
const notiflixLoadingSvgPulse = (width, color) => {
    if (!width) { width = '60px'; }
    if (!color) { color = '#00b462'; }
    const pulse = `<svg stroke="${color}" width="${width}" height="${width}" viewBox="0 0 44 44" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd" stroke-width="2"><circle cx="22" cy="22" r="1"><animate attributeName="r" begin="0s" dur="1.8s" values="1; 20" calcMode="spline" keyTimes="0; 1" keySplines="0.165, 0.84, 0.44, 1" repeatCount="indefinite"/><animate attributeName="stroke-opacity" begin="0s" dur="1.8s" values="1; 0" calcMode="spline" keyTimes="0; 1" keySplines="0.3, 0.61, 0.355, 1" repeatCount="indefinite"/></circle><circle cx="22" cy="22" r="1"><animate attributeName="r" begin="-0.9s" dur="1.8s" values="1; 20" calcMode="spline" keyTimes="0; 1" keySplines="0.165, 0.84, 0.44, 1" repeatCount="indefinite"/><animate attributeName="stroke-opacity" begin="-0.9s" dur="1.8s" values="1; 0" calcMode="spline" keyTimes="0; 1" keySplines="0.3, 0.61, 0.355, 1" repeatCount="indefinite"/></circle></g></svg>`;
    return pulse;
}
// Notiflix: Loading SVG pulse off

// Notiflix: Loading SVG notiflix on
const notiflixLoadingSvgNotiflix = (width, white, green) => {
    if (!width) { width = '60px'; }
    if (!white) { white = '#f8f8f8'; }
    if (!green) { green = '#00b462'; }
    const notiflixIcon = `<svg id="NXLoadingNotiflixLib" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="${width}" height="${width}" version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd" viewBox="0 0 200 200" xmlns:xlink="http://www.w3.org/1999/xlink"><defs><style type="text/css">.line{stroke:${white};stroke-width:12;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:22;}.line{fill:none;}.dot{fill:${green};stroke:${green};stroke-width:12;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:22;}.n{stroke-dasharray: 500;stroke-dashoffset: 0;animation-name: notiflix-n;animation-timing-function: linear;animation-duration: 2.5s;animation-delay:0s;animation-iteration-count: infinite;animation-direction: normal;}@keyframes notiflix-n{0%{stroke-dashoffset: 1000;}100%{stroke-dashoffset: 0;}}.x2,.x1{stroke-dasharray: 500;stroke-dashoffset: 0;animation-name: notiflix-x;animation-timing-function: linear;animation-duration: 2.5s;animation-delay:.2s;animation-iteration-count: infinite;animation-direction: normal;}@keyframes notiflix-x{0%{stroke-dashoffset: 1000;}100%{stroke-dashoffset: 0;}}.dot{animation-name: notiflix-dot;animation-timing-function: ease-in-out;animation-duration: 1.25s;animation-iteration-count: infinite;animation-direction: normal;}@keyframes notiflix-dot{0%{stroke-width: 0;}50%{stroke-width: 12;}100%{stroke-width: 0;}}</style></defs><g><path class="dot" d="M47.97 135.05c3.59,0 6.5,2.91 6.5,6.5 0,3.59 -2.91,6.5 -6.5,6.5 -3.59,0 -6.5,-2.91 -6.5,-6.5 0,-3.59 2.91,-6.5 6.5,-6.5z"/><path class="line n" d="M10.14 144.76l0 -0.22 0 -0.96 0 -56.03c0,-5.68 -4.54,-41.36 37.83,-41.36 42.36,0 37.82,35.68 37.82,41.36l0 57.21"/><path class="line x1" d="M115.06 144.49c24.98,-32.68 49.96,-65.35 74.94,-98.03"/><path class="line x2" d="M114.89 46.6c25.09,32.58 50.19,65.17 75.29,97.75"/></g></svg>`;
    return notiflixIcon;
}
// Notiflix: Loading SVG notiflix off

/***/ }),

/***/ "./resources/assets/js/login.js":
/*!**************************************!*\
  !*** ./resources/assets/js/login.js ***!
  \**************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var notiflix_react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! notiflix-react */ "./node_modules/notiflix-react/dist/notiflix-react-aio-1.4.0.js");



notiflix_react__WEBPACK_IMPORTED_MODULE_0__["default"].Notify.Init({
  timeout: 5000
});
$(function () {
  var author = "<div class=\"author\">\n    By <a href=\"https://arthurmonney.com\">@monneyarthur</a> &nbsp;&bull;&nbsp; \n    <a href=\"https://github.com/laravel-shopper/framework\" target=\"_blank\">See documentation</a>\n  </div>";
  $('body').append(author);
  $("input[type='password'][data-eye]").each(function (i) {
    var $this = $(this),
        id = 'eye-password-' + i,
        el = $('#' + id);
    $this.wrap($("<div/>", {
      style: 'position:relative',
      id: id
    }));
    $this.css({
      paddingRight: 60
    });
    $this.after($('<div/>', {
      html: 'Show',
      "class": 'btn btn-primary btn-sm',
      id: 'passeye-toggle-' + i
    }).css({
      position: 'absolute',
      right: 10,
      top: $this.outerHeight() / 2 - 12,
      padding: '2px 7px',
      fontSize: 12,
      cursor: 'pointer'
    }));
    $this.after($('<input/>', {
      type: 'hidden',
      id: 'passeye-' + i
    }));
    var invalid_feedback = $this.parent().parent().find('.invalid-feedback');

    if (invalid_feedback.length) {
      $this.after(invalid_feedback.clone());
    }

    $this.on('keyup paste', function () {
      $('#passeye-' + i).val($(this).val());
    });
    $('#passeye-toggle-' + i).on('click', function () {
      if ($this.hasClass('show')) {
        $this.attr('type', 'password');
        $this.removeClass('show');
        $(this).removeClass('btn-outline-primary');
      } else {
        $this.attr('type', 'text');
        $this.val($('#passeye-' + i).val());
        $this.addClass('show');
        $(this).addClass('btn-outline-primary');
      }
    });
  });
  $('.my-login-validation').submit(function (event) {
    event.preventDefault();
    event.stopPropagation();
    var form = $(this),
        button = $('#btn-login');
    button.attr('disabled', true).html("<span class='loader12'></span>");

    if (form[0].checkValidity() === false) {
      button.removeAttr('disabled').html('Login');
      form.addClass('was-validated');
    }

    axios.post(form.attr('action'), JSON.stringify(form.serialize())).then(function (response) {
      button.removeAttr('disabled').html('Login');
      localStorage.setItem('token', response.data.token);
      localStorage.setItem('user', JSON.stringify(response.data.user));

      if (response.data.status === 'success') {
        notiflix_react__WEBPACK_IMPORTED_MODULE_0__["default"].Notify.Success(response.data.message);
        setInterval(function () {
          window.location.href = response.data.redirect_url;
        }, 2000);
      }
    })["catch"](function (error) {
      var data = error.response.data;

      if (data && data.errors) {
        notiflix_react__WEBPACK_IMPORTED_MODULE_0__["default"].Notify.Failure(error.response.data.errors.email[0]);
        button.removeAttr('disabled').html('Login');
      }
    });
  });
});

/***/ }),

/***/ 2:
/*!********************************************!*\
  !*** multi ./resources/assets/js/login.js ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/mckenzie/Sites/packages/laravel-shopper/packages/shopper/framework/resources/assets/js/login.js */"./resources/assets/js/login.js");


/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vbm9kZV9tb2R1bGVzL25vdGlmbGl4LXJlYWN0L2Rpc3Qvbm90aWZsaXgtcmVhY3QtYWlvLTEuNC4wLmpzIiwid2VicGFjazovLy8uL3Jlc291cmNlcy9hc3NldHMvanMvbG9naW4uanMiXSwibmFtZXMiOlsiTm90aWZsaXgiLCJOb3RpZnkiLCJJbml0IiwidGltZW91dCIsIiQiLCJhdXRob3IiLCJhcHBlbmQiLCJlYWNoIiwiaSIsIiR0aGlzIiwiaWQiLCJlbCIsIndyYXAiLCJzdHlsZSIsImNzcyIsInBhZGRpbmdSaWdodCIsImFmdGVyIiwiaHRtbCIsInBvc2l0aW9uIiwicmlnaHQiLCJ0b3AiLCJvdXRlckhlaWdodCIsInBhZGRpbmciLCJmb250U2l6ZSIsImN1cnNvciIsInR5cGUiLCJpbnZhbGlkX2ZlZWRiYWNrIiwicGFyZW50IiwiZmluZCIsImxlbmd0aCIsImNsb25lIiwib24iLCJ2YWwiLCJoYXNDbGFzcyIsImF0dHIiLCJyZW1vdmVDbGFzcyIsImFkZENsYXNzIiwic3VibWl0IiwiZXZlbnQiLCJwcmV2ZW50RGVmYXVsdCIsInN0b3BQcm9wYWdhdGlvbiIsImZvcm0iLCJidXR0b24iLCJjaGVja1ZhbGlkaXR5IiwicmVtb3ZlQXR0ciIsImF4aW9zIiwicG9zdCIsIkpTT04iLCJzdHJpbmdpZnkiLCJzZXJpYWxpemUiLCJ0aGVuIiwicmVzcG9uc2UiLCJsb2NhbFN0b3JhZ2UiLCJzZXRJdGVtIiwiZGF0YSIsInRva2VuIiwidXNlciIsInN0YXR1cyIsIlN1Y2Nlc3MiLCJtZXNzYWdlIiwic2V0SW50ZXJ2YWwiLCJ3aW5kb3ciLCJsb2NhdGlvbiIsImhyZWYiLCJyZWRpcmVjdF91cmwiLCJlcnJvciIsImVycm9ycyIsIkZhaWx1cmUiLCJlbWFpbCJdLCJtYXBwaW5ncyI6IjtRQUFBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7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O0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7O0FBRUw7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLOztBQUVMO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsU0FBUzs7QUFFVDtBQUNBOztBQUVBLG9DQUFvQztBQUNwQztBQUNBLGFBQWEsT0FBTztBQUNwQjtBQUNBO0FBQ0E7QUFDQSxTQUFTOztBQUVUO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLHVDQUF1QztBQUN2Qzs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLFNBQVM7O0FBRVQ7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsdUNBQXVDO0FBQ3ZDOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBLFNBQVM7O0FBRVQ7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsdUNBQXVDO0FBQ3ZDOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBLFNBQVM7O0FBRVQ7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsdUNBQXVDO0FBQ3ZDOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBLFNBQVM7O0FBRVQsS0FBSztBQUNMOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsU0FBUzs7QUFFVDtBQUNBOztBQUVBLG9DQUFvQztBQUNwQztBQUNBLGFBQWEsT0FBTztBQUNwQjtBQUNBO0FBQ0E7QUFDQSxTQUFTOztBQUVUO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLHVDQUF1QztBQUN2Qzs7QUFFQSx1Q0FBdUM7QUFDdkM7QUFDQTtBQUNBOztBQUVBLHlFQUF5RTtBQUN6RTtBQUNBOztBQUVBLHlFQUF5RTtBQUN6RTtBQUNBOztBQUVBLHlFQUF5RTtBQUN6RTtBQUNBOztBQUVBLDZDQUE2QztBQUM3QztBQUNBOztBQUVBO0FBQ0E7QUFDQSxTQUFTOztBQUVUO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLHVDQUF1QztBQUN2Qzs7QUFFQSx1Q0FBdUM7QUFDdkM7QUFDQTtBQUNBOztBQUVBLHlFQUF5RTtBQUN6RTtBQUNBOztBQUVBLHlFQUF5RTtBQUN6RTtBQUNBOztBQUVBLHlFQUF5RTtBQUN6RTtBQUNBOztBQUVBLDZDQUE2QztBQUM3QztBQUNBOztBQUVBO0FBQ0E7O0FBRUEsU0FBUzs7QUFFVDtBQUNBOztBQUVBO0FBQ0E7QUFDQSx1Q0FBdUM7QUFDdkM7O0FBRUEsdUNBQXVDO0FBQ3ZDO0FBQ0E7QUFDQTs7QUFFQSx5RUFBeUU7QUFDekU7QUFDQTs7QUFFQSx5RUFBeUU7QUFDekUsd0dBQXdHO0FBQ3hHOztBQUVBLHlFQUF5RTtBQUN6RTtBQUNBOztBQUVBLDZDQUE2QztBQUM3QztBQUNBOztBQUVBO0FBQ0E7O0FBRUEsU0FBUzs7QUFFVDtBQUNBOztBQUVBO0FBQ0E7QUFDQSx1Q0FBdUM7QUFDdkM7O0FBRUEsdUNBQXVDO0FBQ3ZDO0FBQ0E7QUFDQTs7QUFFQSx5RUFBeUU7QUFDekU7QUFDQTs7QUFFQSx5RUFBeUU7QUFDekU7QUFDQTs7QUFFQSx5RUFBeUU7QUFDekU7QUFDQTs7QUFFQSw2Q0FBNkM7QUFDN0M7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsU0FBUzs7QUFFVCxLQUFLO0FBQ0w7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxTQUFTOztBQUVUO0FBQ0E7O0FBRUEscUNBQXFDO0FBQ3JDO0FBQ0EsYUFBYSxPQUFPO0FBQ3BCO0FBQ0E7QUFDQTtBQUNBLFNBQVM7O0FBRVQ7QUFDQTs7QUFFQTtBQUNBO0FBQ0Esd0NBQXdDO0FBQ3hDOztBQUVBLHVDQUF1QztBQUN2QztBQUNBO0FBQ0E7O0FBRUEseUVBQXlFO0FBQ3pFO0FBQ0E7O0FBRUEseUVBQXlFO0FBQ3pFO0FBQ0E7O0FBRUEseUVBQXlFO0FBQ3pFO0FBQ0E7O0FBRUEseUVBQXlFO0FBQ3pFO0FBQ0E7O0FBRUEsNkNBQTZDO0FBQzdDO0FBQ0E7O0FBRUEsNkNBQTZDO0FBQzdDO0FBQ0E7O0FBRUE7QUFDQSxTQUFTO0FBQ1QsS0FBSztBQUNMOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsU0FBUzs7QUFFVDtBQUNBOztBQUVBLHFDQUFxQztBQUNyQztBQUNBLGFBQWEsT0FBTztBQUNwQjtBQUNBO0FBQ0E7QUFDQSxTQUFTOztBQUVUO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLHdDQUF3QztBQUN4Qzs7QUFFQSx1Q0FBdUM7QUFDdkM7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQSwwREFBMEQ7O0FBRTFELFNBQVM7O0FBRVQ7QUFDQTs7QUFFQTtBQUNBO0FBQ0Esd0NBQXdDO0FBQ3hDOztBQUVBLHVDQUF1QztBQUN2QztBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBLDJEQUEyRDs7QUFFM0QsU0FBUzs7QUFFVDtBQUNBOztBQUVBO0FBQ0E7QUFDQSx3Q0FBd0M7QUFDeEM7O0FBRUEsdUNBQXVDO0FBQ3ZDO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUEsd0RBQXdEOztBQUV4RCxTQUFTOztBQUVUO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLHdDQUF3QztBQUN4Qzs7QUFFQSx1Q0FBdUM7QUFDdkM7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQSx3REFBd0Q7O0FBRXhELFNBQVM7O0FBRVQ7QUFDQTs7QUFFQTtBQUNBO0FBQ0Esd0NBQXdDO0FBQ3hDOztBQUVBLHVDQUF1QztBQUN2QztBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBLHNEQUFzRDs7QUFFdEQsU0FBUzs7QUFFVDtBQUNBOztBQUVBO0FBQ0E7QUFDQSx3Q0FBd0M7QUFDeEM7O0FBRUEsdUNBQXVDO0FBQ3ZDO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUEsdURBQXVEOztBQUV2RCxTQUFTOztBQUVUO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLHdDQUF3QztBQUN4Qzs7QUFFQSx1Q0FBdUM7QUFDdkM7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQSx3REFBd0Q7O0FBRXhELFNBQVM7O0FBRVQ7QUFDQTs7QUFFQTtBQUNBO0FBQ0Esd0NBQXdDO0FBQ3hDOztBQUVBLHVDQUF1QztBQUN2QztBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBLDBEQUEwRDs7QUFFMUQsU0FBUzs7QUFFVDtBQUNBOztBQUVBO0FBQ0E7QUFDQSx3Q0FBd0M7QUFDeEM7O0FBRUEsdUNBQXVDO0FBQ3ZDO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUEsMkRBQTJEO0FBQzNELFNBQVM7O0FBRVQ7QUFDQTs7QUFFQTtBQUNBO0FBQ0Esd0NBQXdDO0FBQ3hDOztBQUVBLHVDQUF1QztBQUN2QztBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0EsU0FBUzs7QUFFVCxLQUFLO0FBQ0w7O0FBRUE7QUFDZSx1RUFBUSxFQUFDO0FBQ3hCOzs7QUFHQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBLGtDQUFrQyxXQUFXO0FBQzdDO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7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dXJjZXNDb250ZW50IjpbIiBcdC8vIFRoZSBtb2R1bGUgY2FjaGVcbiBcdHZhciBpbnN0YWxsZWRNb2R1bGVzID0ge307XG5cbiBcdC8vIFRoZSByZXF1aXJlIGZ1bmN0aW9uXG4gXHRmdW5jdGlvbiBfX3dlYnBhY2tfcmVxdWlyZV9fKG1vZHVsZUlkKSB7XG5cbiBcdFx0Ly8gQ2hlY2sgaWYgbW9kdWxlIGlzIGluIGNhY2hlXG4gXHRcdGlmKGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdKSB7XG4gXHRcdFx0cmV0dXJuIGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdLmV4cG9ydHM7XG4gXHRcdH1cbiBcdFx0Ly8gQ3JlYXRlIGEgbmV3IG1vZHVsZSAoYW5kIHB1dCBpdCBpbnRvIHRoZSBjYWNoZSlcbiBcdFx0dmFyIG1vZHVsZSA9IGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdID0ge1xuIFx0XHRcdGk6IG1vZHVsZUlkLFxuIFx0XHRcdGw6IGZhbHNlLFxuIFx0XHRcdGV4cG9ydHM6IHt9XG4gXHRcdH07XG5cbiBcdFx0Ly8gRXhlY3V0ZSB0aGUgbW9kdWxlIGZ1bmN0aW9uXG4gXHRcdG1vZHVsZXNbbW9kdWxlSWRdLmNhbGwobW9kdWxlLmV4cG9ydHMsIG1vZHVsZSwgbW9kdWxlLmV4cG9ydHMsIF9fd2VicGFja19yZXF1aXJlX18pO1xuXG4gXHRcdC8vIEZsYWcgdGhlIG1vZHVsZSBhcyBsb2FkZWRcbiBcdFx0bW9kdWxlLmwgPSB0cnVlO1xuXG4gXHRcdC8vIFJldHVybiB0aGUgZXhwb3J0cyBvZiB0aGUgbW9kdWxlXG4gXHRcdHJldHVybiBtb2R1bGUuZXhwb3J0cztcbiBcdH1cblxuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZXMgb2JqZWN0IChfX3dlYnBhY2tfbW9kdWxlc19fKVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5tID0gbW9kdWxlcztcblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGUgY2FjaGVcbiBcdF9fd2VicGFja19yZXF1aXJlX18uYyA9IGluc3RhbGxlZE1vZHVsZXM7XG5cbiBcdC8vIGRlZmluZSBnZXR0ZXIgZnVuY3Rpb24gZm9yIGhhcm1vbnkgZXhwb3J0c1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kID0gZnVuY3Rpb24oZXhwb3J0cywgbmFtZSwgZ2V0dGVyKSB7XG4gXHRcdGlmKCFfX3dlYnBhY2tfcmVxdWlyZV9fLm8oZXhwb3J0cywgbmFtZSkpIHtcbiBcdFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgbmFtZSwgeyBlbnVtZXJhYmxlOiB0cnVlLCBnZXQ6IGdldHRlciB9KTtcbiBcdFx0fVxuIFx0fTtcblxuIFx0Ly8gZGVmaW5lIF9fZXNNb2R1bGUgb24gZXhwb3J0c1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5yID0gZnVuY3Rpb24oZXhwb3J0cykge1xuIFx0XHRpZih0eXBlb2YgU3ltYm9sICE9PSAndW5kZWZpbmVkJyAmJiBTeW1ib2wudG9TdHJpbmdUYWcpIHtcbiBcdFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgU3ltYm9sLnRvU3RyaW5nVGFnLCB7IHZhbHVlOiAnTW9kdWxlJyB9KTtcbiBcdFx0fVxuIFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgJ19fZXNNb2R1bGUnLCB7IHZhbHVlOiB0cnVlIH0pO1xuIFx0fTtcblxuIFx0Ly8gY3JlYXRlIGEgZmFrZSBuYW1lc3BhY2Ugb2JqZWN0XG4gXHQvLyBtb2RlICYgMTogdmFsdWUgaXMgYSBtb2R1bGUgaWQsIHJlcXVpcmUgaXRcbiBcdC8vIG1vZGUgJiAyOiBtZXJnZSBhbGwgcHJvcGVydGllcyBvZiB2YWx1ZSBpbnRvIHRoZSBuc1xuIFx0Ly8gbW9kZSAmIDQ6IHJldHVybiB2YWx1ZSB3aGVuIGFscmVhZHkgbnMgb2JqZWN0XG4gXHQvLyBtb2RlICYgOHwxOiBiZWhhdmUgbGlrZSByZXF1aXJlXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnQgPSBmdW5jdGlvbih2YWx1ZSwgbW9kZSkge1xuIFx0XHRpZihtb2RlICYgMSkgdmFsdWUgPSBfX3dlYnBhY2tfcmVxdWlyZV9fKHZhbHVlKTtcbiBcdFx0aWYobW9kZSAmIDgpIHJldHVybiB2YWx1ZTtcbiBcdFx0aWYoKG1vZGUgJiA0KSAmJiB0eXBlb2YgdmFsdWUgPT09ICdvYmplY3QnICYmIHZhbHVlICYmIHZhbHVlLl9fZXNNb2R1bGUpIHJldHVybiB2YWx1ZTtcbiBcdFx0dmFyIG5zID0gT2JqZWN0LmNyZWF0ZShudWxsKTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5yKG5zKTtcbiBcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KG5zLCAnZGVmYXVsdCcsIHsgZW51bWVyYWJsZTogdHJ1ZSwgdmFsdWU6IHZhbHVlIH0pO1xuIFx0XHRpZihtb2RlICYgMiAmJiB0eXBlb2YgdmFsdWUgIT0gJ3N0cmluZycpIGZvcih2YXIga2V5IGluIHZhbHVlKSBfX3dlYnBhY2tfcmVxdWlyZV9fLmQobnMsIGtleSwgZnVuY3Rpb24oa2V5KSB7IHJldHVybiB2YWx1ZVtrZXldOyB9LmJpbmQobnVsbCwga2V5KSk7XG4gXHRcdHJldHVybiBucztcbiBcdH07XG5cbiBcdC8vIGdldERlZmF1bHRFeHBvcnQgZnVuY3Rpb24gZm9yIGNvbXBhdGliaWxpdHkgd2l0aCBub24taGFybW9ueSBtb2R1bGVzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm4gPSBmdW5jdGlvbihtb2R1bGUpIHtcbiBcdFx0dmFyIGdldHRlciA9IG1vZHVsZSAmJiBtb2R1bGUuX19lc01vZHVsZSA/XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0RGVmYXVsdCgpIHsgcmV0dXJuIG1vZHVsZVsnZGVmYXVsdCddOyB9IDpcbiBcdFx0XHRmdW5jdGlvbiBnZXRNb2R1bGVFeHBvcnRzKCkgeyByZXR1cm4gbW9kdWxlOyB9O1xuIFx0XHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQoZ2V0dGVyLCAnYScsIGdldHRlcik7XG4gXHRcdHJldHVybiBnZXR0ZXI7XG4gXHR9O1xuXG4gXHQvLyBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGxcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubyA9IGZ1bmN0aW9uKG9iamVjdCwgcHJvcGVydHkpIHsgcmV0dXJuIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbChvYmplY3QsIHByb3BlcnR5KTsgfTtcblxuIFx0Ly8gX193ZWJwYWNrX3B1YmxpY19wYXRoX19cbiBcdF9fd2VicGFja19yZXF1aXJlX18ucCA9IFwiL1wiO1xuXG5cbiBcdC8vIExvYWQgZW50cnkgbW9kdWxlIGFuZCByZXR1cm4gZXhwb3J0c1xuIFx0cmV0dXJuIF9fd2VicGFja19yZXF1aXJlX18oX193ZWJwYWNrX3JlcXVpcmVfXy5zID0gMik7XG4iLCIvKiFcclxuKiBOb3RpZmxpeCBSZWFjdCAoJ2h0dHBzOi8vd3d3Lm5vdGlmbGl4LmNvbS9yZWFjdCcpXHJcbiogVmVyc2lvbjogMS40LjBcclxuKiBBdXRob3I6IEZ1cmthbiBNVCAoJ2h0dHBzOi8vZ2l0aHViLmNvbS9mdXJjYW4nKVxyXG4qIENvcHlyaWdodCAyMDE5IE5vdGlmbGl4LCBNSVQgTGljZW5jZSAoJ2h0dHBzOi8vb3BlbnNvdXJjZS5vcmcvbGljZW5zZXMvTUlUJylcclxuKi9cclxuXHJcbi8vIEludGVybmFsIENTUyBDb2RlcyBvblxyXG5jb25zdCBub3RpZmxpeEludGVybmFsQ1NTQ29kZXMgPSAoKSA9PiB7XHJcblxyXG4gICAgY29uc3QgY3NzID0gYFtpZF49Tm90aWZsaXhOb3RpZnlXcmFwXXtwb3NpdGlvbjpmaXhlZDt6LWluZGV4OjEwMDE7b3BhY2l0eToxO3JpZ2h0OjEwcHg7dG9wOjEwcHg7d2lkdGg6MjgwcHg7bWF4LXdpZHRoOjk2JTtib3gtc2l6aW5nOmJvcmRlci1ib3g7YmFja2dyb3VuZDowIDB9W2lkXj1Ob3RpZmxpeE5vdGlmeVdyYXBdICp7Ym94LXNpemluZzpib3JkZXItYm94fVtpZF49Tm90aWZsaXhOb3RpZnlXcmFwXT5kaXZ7LXdlYmtpdC11c2VyLXNlbGVjdDpub25lOy1tb3otdXNlci1zZWxlY3Q6bm9uZTstbXMtdXNlci1zZWxlY3Q6bm9uZTt1c2VyLXNlbGVjdDpub25lO2ZvbnQtZmFtaWx5OlF1aWNrc2FuZCxzYW5zLXNlcmlmO3dpZHRoOjEwMCU7ZGlzcGxheTppbmxpbmUtYmxvY2s7cG9zaXRpb246cmVsYXRpdmU7bWFyZ2luOjAgMCAxMHB4O2JvcmRlci1yYWRpdXM6NXB4O2JhY2tncm91bmQ6IzFlMWUxZTtjb2xvcjojZmZmO3BhZGRpbmc6MTBweCAxMnB4O2ZvbnQtc2l6ZToxNHB4O2xpbmUtaGVpZ2h0OjEuNH1baWRePU5vdGlmbGl4Tm90aWZ5V3JhcF0+ZGl2Omxhc3QtY2hpbGR7bWFyZ2luOjB9W2lkXj1Ob3RpZmxpeE5vdGlmeVdyYXBdPmRpdi53aXRoLWNhbGxiYWNre2N1cnNvcjpwb2ludGVyfVtpZF49Tm90aWZsaXhOb3RpZnlXcmFwXSA6OnNlbGVjdGlvbntiYWNrZ3JvdW5kOmluaGVyaXR9W2lkXj1Ob3RpZmxpeE5vdGlmeVdyYXBdPmRpdi53aXRoLWljb257cGFkZGluZzo4cHh9W2lkXj1Ob3RpZmxpeE5vdGlmeVdyYXBdPmRpdi53aXRoLWNsb3Nle3BhZGRpbmc6MTBweCAzMHB4IDEwcHggMTJweH1baWRePU5vdGlmbGl4Tm90aWZ5V3JhcF0+ZGl2LndpdGgtaWNvbi53aXRoLWNsb3Nle3BhZGRpbmc6NnB4IDMwcHggNnB4IDZweH1baWRePU5vdGlmbGl4Tm90aWZ5V3JhcF0+ZGl2PnNwYW4udGhlLW1lc3NhZ2V7Zm9udC13ZWlnaHQ6NTAwO2ZvbnQtZmFtaWx5OmluaGVyaXQhaW1wb3J0YW50O3dvcmQtYnJlYWs6YnJlYWstYWxsO3dvcmQtYnJlYWs6YnJlYWstd29yZH1baWRePU5vdGlmbGl4Tm90aWZ5V3JhcF0+ZGl2PnNwYW4uY2xpY2stdG8tY2xvc2V7Y3Vyc29yOnBvaW50ZXI7dHJhbnNpdGlvbjphbGwgLjJzIGVhc2UtaW4tb3V0O3Bvc2l0aW9uOmFic29sdXRlO3JpZ2h0OjhweDt0b3A6MDtib3R0b206MDttYXJnaW46YXV0bztjb2xvcjppbmhlcml0O3dpZHRoOjE2cHg7aGVpZ2h0OjE2cHh9W2lkXj1Ob3RpZmxpeE5vdGlmeVdyYXBdPmRpdj5zcGFuLmNsaWNrLXRvLWNsb3NlOmhvdmVye3RyYW5zZm9ybTpyb3RhdGUoOTBkZWcpfVtpZF49Tm90aWZsaXhOb3RpZnlXcmFwXT5kaXY+c3Bhbi5jbGljay10by1jbG9zZT5zdmd7cG9zaXRpb246YWJzb2x1dGU7d2lkdGg6MTZweDtoZWlnaHQ6MTZweDtyaWdodDowO3RvcDowfVtpZF49Tm90aWZsaXhOb3RpZnlXcmFwXT5kaXY+Lm5taXtwb3NpdGlvbjphYnNvbHV0ZTt3aWR0aDo0MHB4O2hlaWdodDo0MHB4O2ZvbnQtc2l6ZTozMHB4O2xpbmUtaGVpZ2h0OjQwcHg7dGV4dC1hbGlnbjpjZW50ZXI7bGVmdDo4cHg7dG9wOjA7Ym90dG9tOjA7bWFyZ2luOmF1dG87Ym9yZGVyLXJhZGl1czppbmhlcml0fVtpZF49Tm90aWZsaXhOb3RpZnlXcmFwXT5kaXY+LndmYS5zaGFkb3d7Y29sb3I6aW5oZXJpdDtiYWNrZ3JvdW5kOnJnYmEoMCwwLDAsLjE1KTtib3gtc2hhZG93Omluc2V0IDAgMCAzNHB4IHJnYmEoMCwwLDAsLjIpO3RleHQtc2hhZG93OjAgMCAxMHB4IHJnYmEoMCwwLDAsLjMpfVtpZF49Tm90aWZsaXhOb3RpZnlXcmFwXT5kaXY+c3Bhbi53aXRoLWljb257cG9zaXRpb246cmVsYXRpdmU7ZmxvYXQ6bGVmdDt3aWR0aDpjYWxjKDEwMCUgLSA0MHB4KTttYXJnaW46MCAwIDAgNDBweDtwYWRkaW5nOjAgMCAwIDEwcHg7Ym94LXNpemluZzpib3JkZXItYm94fVtpZF49Tm90aWZsaXhOb3RpZnlXcmFwXT5kaXYucnRsLW9uPi5ubWl7bGVmdDphdXRvO3JpZ2h0OjhweH1baWRePU5vdGlmbGl4Tm90aWZ5V3JhcF0+ZGl2LnJ0bC1vbj5zcGFuLndpdGgtaWNvbntwYWRkaW5nOjAgMTBweCAwIDA7bWFyZ2luOjAgNDBweCAwIDB9W2lkXj1Ob3RpZmxpeE5vdGlmeVdyYXBdPmRpdi5ydGwtb24+c3Bhbi5jbGljay10by1jbG9zZXtyaWdodDphdXRvO2xlZnQ6OHB4fVtpZF49Tm90aWZsaXhOb3RpZnlXcmFwXT5kaXYud2l0aC1pY29uLndpdGgtY2xvc2UucnRsLW9ue3BhZGRpbmc6NnB4IDZweCA2cHggMzBweH1baWRePU5vdGlmbGl4Tm90aWZ5V3JhcF0+ZGl2LndpdGgtY2xvc2UucnRsLW9ue3BhZGRpbmc6MTBweCAxMnB4IDEwcHggMzBweH1baWRePU5vdGlmbGl4Tm90aWZ5T3ZlcmxheV0ud2l0aC1hbmltYXRpb24sW2lkXj1Ob3RpZmxpeE5vdGlmeVdyYXBdPmRpdi53aXRoLWFuaW1hdGlvbi5ueC1mYWRle2FuaW1hdGlvbjpub3RpZnktYW5pbWF0aW9uLWZhZGUgLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbDstd2Via2l0LWFuaW1hdGlvbjpub3RpZnktYW5pbWF0aW9uLWZhZGUgLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbH1Aa2V5ZnJhbWVzIG5vdGlmeS1hbmltYXRpb24tZmFkZXswJXtvcGFjaXR5OjB9MTAwJXtvcGFjaXR5OjF9fUAtd2Via2l0LWtleWZyYW1lcyBub3RpZnktYW5pbWF0aW9uLWZhZGV7MCV7b3BhY2l0eTowfTEwMCV7b3BhY2l0eToxfX1baWRePU5vdGlmbGl4Tm90aWZ5V3JhcF0+ZGl2LndpdGgtYW5pbWF0aW9uLm54LXpvb217YW5pbWF0aW9uOm5vdGlmeS1hbmltYXRpb24tem9vbSAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsOy13ZWJraXQtYW5pbWF0aW9uOm5vdGlmeS1hbmltYXRpb24tem9vbSAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsfUBrZXlmcmFtZXMgbm90aWZ5LWFuaW1hdGlvbi16b29tezAle3RyYW5zZm9ybTpzY2FsZSgwKX01MCV7dHJhbnNmb3JtOnNjYWxlKDEuMDUpfTEwMCV7dHJhbnNmb3JtOnNjYWxlKDEpfX1ALXdlYmtpdC1rZXlmcmFtZXMgbm90aWZ5LWFuaW1hdGlvbi16b29tezAle3RyYW5zZm9ybTpzY2FsZSgwKX01MCV7dHJhbnNmb3JtOnNjYWxlKDEuMDUpfTEwMCV7dHJhbnNmb3JtOnNjYWxlKDEpfX1baWRePU5vdGlmbGl4Tm90aWZ5V3JhcF0+ZGl2LndpdGgtYW5pbWF0aW9uLm54LWZyb20tcmlnaHR7YW5pbWF0aW9uOm5vdGlmeS1hbmltYXRpb24tZnJvbS1yaWdodCAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsOy13ZWJraXQtYW5pbWF0aW9uOm5vdGlmeS1hbmltYXRpb24tZnJvbS1yaWdodCAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsfUBrZXlmcmFtZXMgbm90aWZ5LWFuaW1hdGlvbi1mcm9tLXJpZ2h0ezAle3JpZ2h0Oi0zMDBweDtvcGFjaXR5OjB9NTAle3JpZ2h0OjhweDtvcGFjaXR5OjF9MTAwJXtyaWdodDowO29wYWNpdHk6MX19QC13ZWJraXQta2V5ZnJhbWVzIG5vdGlmeS1hbmltYXRpb24tZnJvbS1yaWdodHswJXtyaWdodDotMzAwcHg7b3BhY2l0eTowfTUwJXtyaWdodDo4cHg7b3BhY2l0eToxfTEwMCV7cmlnaHQ6MDtvcGFjaXR5OjF9fVtpZF49Tm90aWZsaXhOb3RpZnlXcmFwXT5kaXYud2l0aC1hbmltYXRpb24ubngtZnJvbS1sZWZ0e2FuaW1hdGlvbjpub3RpZnktYW5pbWF0aW9uLWZyb20tbGVmdCAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsOy13ZWJraXQtYW5pbWF0aW9uOm5vdGlmeS1hbmltYXRpb24tZnJvbS1sZWZ0IC4zcyBlYXNlLWluLW91dCAwcyBub3JtYWx9QGtleWZyYW1lcyBub3RpZnktYW5pbWF0aW9uLWZyb20tbGVmdHswJXtsZWZ0Oi0zMDBweDtvcGFjaXR5OjB9NTAle2xlZnQ6OHB4O29wYWNpdHk6MX0xMDAle2xlZnQ6MDtvcGFjaXR5OjF9fUAtd2Via2l0LWtleWZyYW1lcyBub3RpZnktYW5pbWF0aW9uLWZyb20tbGVmdHswJXtsZWZ0Oi0zMDBweDtvcGFjaXR5OjB9NTAle2xlZnQ6OHB4O29wYWNpdHk6MX0xMDAle2xlZnQ6MDtvcGFjaXR5OjF9fVtpZF49Tm90aWZsaXhOb3RpZnlXcmFwXT5kaXYud2l0aC1hbmltYXRpb24ubngtZnJvbS10b3B7YW5pbWF0aW9uOm5vdGlmeS1hbmltYXRpb24tZnJvbS10b3AgLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbDstd2Via2l0LWFuaW1hdGlvbjpub3RpZnktYW5pbWF0aW9uLWZyb20tdG9wIC4zcyBlYXNlLWluLW91dCAwcyBub3JtYWx9QGtleWZyYW1lcyBub3RpZnktYW5pbWF0aW9uLWZyb20tdG9wezAle3RvcDotNTBweDtvcGFjaXR5OjB9NTAle3RvcDo4cHg7b3BhY2l0eToxfTEwMCV7dG9wOjA7b3BhY2l0eToxfX1ALXdlYmtpdC1rZXlmcmFtZXMgbm90aWZ5LWFuaW1hdGlvbi1mcm9tLXRvcHswJXt0b3A6LTUwcHg7b3BhY2l0eTowfTUwJXt0b3A6OHB4O29wYWNpdHk6MX0xMDAle3RvcDowO29wYWNpdHk6MX19W2lkXj1Ob3RpZmxpeE5vdGlmeVdyYXBdPmRpdi53aXRoLWFuaW1hdGlvbi5ueC1mcm9tLWJvdHRvbXthbmltYXRpb246bm90aWZ5LWFuaW1hdGlvbi1mcm9tLWJvdHRvbSAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsOy13ZWJraXQtYW5pbWF0aW9uOm5vdGlmeS1hbmltYXRpb24tZnJvbS1ib3R0b20gLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbH1Aa2V5ZnJhbWVzIG5vdGlmeS1hbmltYXRpb24tZnJvbS1ib3R0b217MCV7Ym90dG9tOi01MHB4O29wYWNpdHk6MH01MCV7Ym90dG9tOjhweDtvcGFjaXR5OjF9MTAwJXtib3R0b206MDtvcGFjaXR5OjF9fUAtd2Via2l0LWtleWZyYW1lcyBub3RpZnktYW5pbWF0aW9uLWZyb20tYm90dG9tezAle2JvdHRvbTotNTBweDtvcGFjaXR5OjB9NTAle2JvdHRvbTo4cHg7b3BhY2l0eToxfTEwMCV7Ym90dG9tOjA7b3BhY2l0eToxfX1baWRePU5vdGlmbGl4Tm90aWZ5T3ZlcmxheV0ud2l0aC1hbmltYXRpb24ucmVtb3ZlLFtpZF49Tm90aWZsaXhOb3RpZnlXcmFwXT5kaXYud2l0aC1hbmltYXRpb24ubngtZmFkZS5yZW1vdmV7b3BhY2l0eTowO2FuaW1hdGlvbjpub3RpZnktcmVtb3ZlLWZhZGUgLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbDstd2Via2l0LWFuaW1hdGlvbjpub3RpZnktcmVtb3ZlLWZhZGUgLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbH1Aa2V5ZnJhbWVzIG5vdGlmeS1yZW1vdmUtZmFkZXswJXtvcGFjaXR5OjF9MTAwJXtvcGFjaXR5OjB9fUAtd2Via2l0LWtleWZyYW1lcyBub3RpZnktcmVtb3ZlLWZhZGV7MCV7b3BhY2l0eToxfTEwMCV7b3BhY2l0eTowfX1baWRePU5vdGlmbGl4Tm90aWZ5V3JhcF0+ZGl2LndpdGgtYW5pbWF0aW9uLm54LXpvb20ucmVtb3Zle3RyYW5zZm9ybTpzY2FsZSgwKTthbmltYXRpb246bm90aWZ5LXJlbW92ZS16b29tIC4zcyBlYXNlLWluLW91dCAwcyBub3JtYWw7LXdlYmtpdC1hbmltYXRpb246bm90aWZ5LXJlbW92ZS16b29tIC4zcyBlYXNlLWluLW91dCAwcyBub3JtYWx9QGtleWZyYW1lcyBub3RpZnktcmVtb3ZlLXpvb217MCV7dHJhbnNmb3JtOnNjYWxlKDEpfTUwJXt0cmFuc2Zvcm06c2NhbGUoMS4wNSl9MTAwJXt0cmFuc2Zvcm06c2NhbGUoMCl9fUAtd2Via2l0LWtleWZyYW1lcyBub3RpZnktcmVtb3ZlLXpvb217MCV7dHJhbnNmb3JtOnNjYWxlKDEpfTUwJXt0cmFuc2Zvcm06c2NhbGUoMS4wNSl9MTAwJXt0cmFuc2Zvcm06c2NhbGUoMCl9fVtpZF49Tm90aWZsaXhOb3RpZnlXcmFwXT5kaXYud2l0aC1hbmltYXRpb24ubngtZnJvbS10b3AucmVtb3Zle29wYWNpdHk6MDthbmltYXRpb246bm90aWZ5LXJlbW92ZS10by10b3AgLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbDstd2Via2l0LWFuaW1hdGlvbjpub3RpZnktcmVtb3ZlLXRvLXRvcCAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsfUBrZXlmcmFtZXMgbm90aWZ5LXJlbW92ZS10by10b3B7MCV7dG9wOjA7b3BhY2l0eToxfTUwJXt0b3A6OHB4O29wYWNpdHk6MX0xMDAle3RvcDotNTBweDtvcGFjaXR5OjB9fUAtd2Via2l0LWtleWZyYW1lcyBub3RpZnktcmVtb3ZlLXRvLXRvcHswJXt0b3A6MDtvcGFjaXR5OjF9NTAle3RvcDo4cHg7b3BhY2l0eToxfTEwMCV7dG9wOi01MHB4O29wYWNpdHk6MH19W2lkXj1Ob3RpZmxpeE5vdGlmeVdyYXBdPmRpdi53aXRoLWFuaW1hdGlvbi5ueC1mcm9tLXJpZ2h0LnJlbW92ZXtvcGFjaXR5OjA7YW5pbWF0aW9uOm5vdGlmeS1yZW1vdmUtdG8tcmlnaHQgLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbDstd2Via2l0LWFuaW1hdGlvbjpub3RpZnktcmVtb3ZlLXRvLXJpZ2h0IC4zcyBlYXNlLWluLW91dCAwcyBub3JtYWx9QGtleWZyYW1lcyBub3RpZnktcmVtb3ZlLXRvLXJpZ2h0ezAle3JpZ2h0OjA7b3BhY2l0eToxfTUwJXtyaWdodDo4cHg7b3BhY2l0eToxfTEwMCV7cmlnaHQ6LTMwMHB4O29wYWNpdHk6MH19QC13ZWJraXQta2V5ZnJhbWVzIG5vdGlmeS1yZW1vdmUtdG8tcmlnaHR7MCV7cmlnaHQ6MDtvcGFjaXR5OjF9NTAle3JpZ2h0OjhweDtvcGFjaXR5OjF9MTAwJXtyaWdodDotMzAwcHg7b3BhY2l0eTowfX1baWRePU5vdGlmbGl4Tm90aWZ5V3JhcF0+ZGl2LndpdGgtYW5pbWF0aW9uLm54LWZyb20tYm90dG9tLnJlbW92ZXtvcGFjaXR5OjA7YW5pbWF0aW9uOm5vdGlmeS1yZW1vdmUtdG8tYm90dG9tIC4zcyBlYXNlLWluLW91dCAwcyBub3JtYWw7LXdlYmtpdC1hbmltYXRpb246bm90aWZ5LXJlbW92ZS10by1ib3R0b20gLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbH1Aa2V5ZnJhbWVzIG5vdGlmeS1yZW1vdmUtdG8tYm90dG9tezAle2JvdHRvbTowO29wYWNpdHk6MX01MCV7Ym90dG9tOjhweDtvcGFjaXR5OjF9MTAwJXtib3R0b206LTUwcHg7b3BhY2l0eTowfX1ALXdlYmtpdC1rZXlmcmFtZXMgbm90aWZ5LXJlbW92ZS10by1ib3R0b217MCV7Ym90dG9tOjA7b3BhY2l0eToxfTUwJXtib3R0b206OHB4O29wYWNpdHk6MX0xMDAle2JvdHRvbTotNTBweDtvcGFjaXR5OjB9fVtpZF49Tm90aWZsaXhOb3RpZnlXcmFwXT5kaXYud2l0aC1hbmltYXRpb24ubngtZnJvbS1sZWZ0LnJlbW92ZXtvcGFjaXR5OjA7YW5pbWF0aW9uOm5vdGlmeS1yZW1vdmUtdG8tbGVmdCAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsOy13ZWJraXQtYW5pbWF0aW9uOm5vdGlmeS1yZW1vdmUtdG8tbGVmdCAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsfUBrZXlmcmFtZXMgbm90aWZ5LXJlbW92ZS10by1sZWZ0ezAle2xlZnQ6MDtvcGFjaXR5OjF9NTAle2xlZnQ6OHB4O29wYWNpdHk6MX0xMDAle2xlZnQ6LTMwMHB4O29wYWNpdHk6MH19QC13ZWJraXQta2V5ZnJhbWVzIG5vdGlmeS1yZW1vdmUtdG8tbGVmdHswJXtsZWZ0OjA7b3BhY2l0eToxfTUwJXtsZWZ0OjhweDtvcGFjaXR5OjF9MTAwJXtsZWZ0Oi0zMDBweDtvcGFjaXR5OjB9fVtpZF49Tm90aWZsaXhSZXBvcnRXcmFwXXtwb3NpdGlvbjpmaXhlZDt6LWluZGV4OjEwMDA7d2lkdGg6MzIwcHg7bWF4LXdpZHRoOjk2JTtib3gtc2l6aW5nOmJvcmRlci1ib3g7Zm9udC1mYW1pbHk6UXVpY2tzYW5kLHNhbnMtc2VyaWY7bGVmdDowO3JpZ2h0OjA7dG9wOjIwcHg7Y29sb3I6IzFlMWUxZTtib3JkZXItcmFkaXVzOjI1cHg7YmFja2dyb3VuZDowIDA7bWFyZ2luOmF1dG99W2lkXj1Ob3RpZmxpeFJlcG9ydFdyYXBdICp7Ym94LXNpemluZzpib3JkZXItYm94fVtpZF49Tm90aWZsaXhSZXBvcnRXcmFwXT5kaXZbY2xhc3MqPVwiLW92ZXJsYXlcIl17d2lkdGg6MTAwJTtoZWlnaHQ6MTAwJTtsZWZ0OjA7dG9wOjA7YmFja2dyb3VuZDpyZ2JhKDI1NSwyNTUsMjU1LC41KTtwb3NpdGlvbjpmaXhlZDt6LWluZGV4OjB9W2lkXj1Ob3RpZmxpeFJlcG9ydFdyYXBdPmRpdltjbGFzcyo9XCItY29udGVudFwiXXt3aWR0aDoxMDAlO2Zsb2F0OmxlZnQ7Ym9yZGVyLXJhZGl1czppbmhlcml0O3BhZGRpbmc6MTBweDtmaWx0ZXI6ZHJvcC1zaGFkb3coMCAwIDVweCByZ2JhKDAsMCwwLC4xKSk7Ym9yZGVyOjFweCBzb2xpZCByZ2JhKDAsMCwwLC4wMyk7YmFja2dyb3VuZDojZjhmOGY4O3Bvc2l0aW9uOnJlbGF0aXZlO3otaW5kZXg6MX1baWRePU5vdGlmbGl4UmVwb3J0V3JhcF0+ZGl2W2NsYXNzKj1cIi1jb250ZW50XCJdPmRpdltjbGFzcyQ9XCItaWNvblwiXXstd2Via2l0LXVzZXItc2VsZWN0Om5vbmU7LW1vei11c2VyLXNlbGVjdDpub25lOy1tcy11c2VyLXNlbGVjdDpub25lO3VzZXItc2VsZWN0Om5vbmU7d2lkdGg6MTEwcHg7aGVpZ2h0OjExMHB4O2Rpc3BsYXk6YmxvY2s7bWFyZ2luOjZweCBhdXRvIDEycHh9W2lkXj1Ob3RpZmxpeFJlcG9ydFdyYXBdPmRpdltjbGFzcyo9XCItY29udGVudFwiXT5kaXZbY2xhc3MkPVwiLWljb25cIl0gc3Zne21pbi13aWR0aDoxMDAlO21heC13aWR0aDoxMDAlO2hlaWdodDphdXRvfVtpZF49Tm90aWZsaXhSZXBvcnRXcmFwXT4qPmg1e3dvcmQtYnJlYWs6YnJlYWstYWxsO3dvcmQtYnJlYWs6YnJlYWstd29yZDtmb250LWZhbWlseTppbmhlcml0IWltcG9ydGFudDtmb250LXNpemU6MTZweDtmb250LXdlaWdodDo1MDA7bGluZS1oZWlnaHQ6MS40O21hcmdpbjowIDAgMTBweDtwYWRkaW5nOjAgMCAxMHB4O2JvcmRlci1ib3R0b206MXB4IHNvbGlkIHJnYmEoMCwwLDAsLjEpO2Zsb2F0OmxlZnQ7d2lkdGg6MTAwJTt0ZXh0LWFsaWduOmNlbnRlcn1baWRePU5vdGlmbGl4UmVwb3J0V3JhcF0+Kj5we3dvcmQtYnJlYWs6YnJlYWstYWxsO3dvcmQtYnJlYWs6YnJlYWstd29yZDtmb250LWZhbWlseTppbmhlcml0IWltcG9ydGFudDtmb250LXNpemU6MTNweDtsaW5lLWhlaWdodDoxLjQ7ZmxvYXQ6bGVmdDt3aWR0aDoxMDAlO3BhZGRpbmc6MCAxMHB4O21hcmdpbjowIDAgMTBweH1baWRePU5vdGlmbGl4UmVwb3J0V3JhcF0gYSNOWFJlcG9ydEJ1dHRvbnt3b3JkLWJyZWFrOmJyZWFrLWFsbDt3b3JkLWJyZWFrOmJyZWFrLXdvcmQ7LXdlYmtpdC11c2VyLXNlbGVjdDpub25lOy1tb3otdXNlci1zZWxlY3Q6bm9uZTstbXMtdXNlci1zZWxlY3Q6bm9uZTt1c2VyLXNlbGVjdDpub25lO2ZvbnQtZmFtaWx5OmluaGVyaXQhaW1wb3J0YW50O3RyYW5zaXRpb246YWxsIC4yNXMgZWFzZS1pbi1vdXQ7Y3Vyc29yOnBvaW50ZXI7ZmxvYXQ6cmlnaHQ7cGFkZGluZzo3cHggMTdweDtiYWNrZ3JvdW5kOiMwMGI0NjI7Zm9udC1zaXplOjE0cHg7bGluZS1oZWlnaHQ6MS40O2ZvbnQtd2VpZ2h0OjUwMDtib3JkZXItcmFkaXVzOmluaGVyaXQhaW1wb3J0YW50O2NvbG9yOiNmZmZ9W2lkXj1Ob3RpZmxpeFJlcG9ydFdyYXBdIGEjTlhSZXBvcnRCdXR0b246aG92ZXJ7cGFkZGluZzo3cHggMjBweH1baWRePU5vdGlmbGl4UmVwb3J0V3JhcF0ucnRsLW9uIGEjTlhSZXBvcnRCdXR0b257ZmxvYXQ6bGVmdH1baWRePU5vdGlmbGl4UmVwb3J0V3JhcF0+ZGl2W2NsYXNzKj1cIi1vdmVybGF5XCJdLndpdGgtYW5pbWF0aW9ue2FuaW1hdGlvbjpyZXBvcnQtb3ZlcmxheS1hbmltYXRpb24gLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbDstd2Via2l0LWFuaW1hdGlvbjpyZXBvcnQtb3ZlcmxheS1hbmltYXRpb24gLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbH1Aa2V5ZnJhbWVzIHJlcG9ydC1vdmVybGF5LWFuaW1hdGlvbnswJXtvcGFjaXR5OjB9MTAwJXtvcGFjaXR5OjF9fUAtd2Via2l0LWtleWZyYW1lcyByZXBvcnQtb3ZlcmxheS1hbmltYXRpb257MCV7b3BhY2l0eTowfTEwMCV7b3BhY2l0eToxfX1baWRePU5vdGlmbGl4UmVwb3J0V3JhcF0+ZGl2W2NsYXNzKj1cIi1jb250ZW50XCJdLndpdGgtYW5pbWF0aW9uLm54LWZhZGV7YW5pbWF0aW9uOnJlcG9ydC1hbmltYXRpb24tZmFkZSAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsOy13ZWJraXQtYW5pbWF0aW9uOnJlcG9ydC1hbmltYXRpb24tZmFkZSAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsfUBrZXlmcmFtZXMgcmVwb3J0LWFuaW1hdGlvbi1mYWRlezAle29wYWNpdHk6MH0xMDAle29wYWNpdHk6MX19QC13ZWJraXQta2V5ZnJhbWVzIHJlcG9ydC1hbmltYXRpb24tZmFkZXswJXtvcGFjaXR5OjB9MTAwJXtvcGFjaXR5OjF9fVtpZF49Tm90aWZsaXhSZXBvcnRXcmFwXT5kaXZbY2xhc3MqPVwiLWNvbnRlbnRcIl0ud2l0aC1hbmltYXRpb24ubngtem9vbXthbmltYXRpb246cmVwb3J0LWFuaW1hdGlvbi16b29tIC4zcyBlYXNlLWluLW91dCAwcyBub3JtYWw7LXdlYmtpdC1hbmltYXRpb246cmVwb3J0LWFuaW1hdGlvbi16b29tIC4zcyBlYXNlLWluLW91dCAwcyBub3JtYWx9QGtleWZyYW1lcyByZXBvcnQtYW5pbWF0aW9uLXpvb217MCV7b3BhY2l0eTowO3RyYW5zZm9ybTpzY2FsZSguNSl9NTAle29wYWNpdHk6MTt0cmFuc2Zvcm06c2NhbGUoMS4wNSl9MTAwJXtvcGFjaXR5OjE7dHJhbnNmb3JtOnNjYWxlKDEpfX1ALXdlYmtpdC1rZXlmcmFtZXMgcmVwb3J0LWFuaW1hdGlvbi16b29tezAle29wYWNpdHk6MDt0cmFuc2Zvcm06c2NhbGUoLjUpfTUwJXtvcGFjaXR5OjE7dHJhbnNmb3JtOnNjYWxlKDEuMDUpfTEwMCV7b3BhY2l0eToxO3RyYW5zZm9ybTpzY2FsZSgxKX19W2lkXj1Ob3RpZmxpeFJlcG9ydFdyYXBdLnJlbW92ZT5kaXZbY2xhc3MqPVwiLW92ZXJsYXlcIl0ud2l0aC1hbmltYXRpb257b3BhY2l0eTowO2FuaW1hdGlvbjpyZXBvcnQtb3ZlcmxheS1hbmltYXRpb24tcmVtb3ZlIC4zcyBlYXNlLWluLW91dCAwcyBub3JtYWw7LXdlYmtpdC1hbmltYXRpb246cmVwb3J0LW92ZXJsYXktYW5pbWF0aW9uLXJlbW92ZSAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsfUBrZXlmcmFtZXMgcmVwb3J0LW92ZXJsYXktYW5pbWF0aW9uLXJlbW92ZXswJXtvcGFjaXR5OjF9MTAwJXtvcGFjaXR5OjB9fUAtd2Via2l0LWtleWZyYW1lcyByZXBvcnQtb3ZlcmxheS1hbmltYXRpb24tcmVtb3ZlezAle29wYWNpdHk6MX0xMDAle29wYWNpdHk6MH19W2lkXj1Ob3RpZmxpeFJlcG9ydFdyYXBdLnJlbW92ZT5kaXZbY2xhc3MqPVwiLWNvbnRlbnRcIl0ud2l0aC1hbmltYXRpb24ubngtZmFkZXtvcGFjaXR5OjA7YW5pbWF0aW9uOnJlcG9ydC1hbmltYXRpb24tZmFkZS1yZW1vdmUgLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbDstd2Via2l0LWFuaW1hdGlvbjpyZXBvcnQtYW5pbWF0aW9uLWZhZGUtcmVtb3ZlIC4zcyBlYXNlLWluLW91dCAwcyBub3JtYWx9QGtleWZyYW1lcyByZXBvcnQtYW5pbWF0aW9uLWZhZGUtcmVtb3ZlezAle29wYWNpdHk6MX0xMDAle29wYWNpdHk6MH19QC13ZWJraXQta2V5ZnJhbWVzIHJlcG9ydC1hbmltYXRpb24tZmFkZS1yZW1vdmV7MCV7b3BhY2l0eToxfTEwMCV7b3BhY2l0eTowfX1baWRePU5vdGlmbGl4UmVwb3J0V3JhcF0ucmVtb3ZlPmRpdltjbGFzcyo9XCItY29udGVudFwiXS53aXRoLWFuaW1hdGlvbi5ueC16b29te29wYWNpdHk6MDthbmltYXRpb246cmVwb3J0LWFuaW1hdGlvbi16b29tLXJlbW92ZSAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsOy13ZWJraXQtYW5pbWF0aW9uOnJlcG9ydC1hbmltYXRpb24tem9vbS1yZW1vdmUgLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbH1Aa2V5ZnJhbWVzIHJlcG9ydC1hbmltYXRpb24tem9vbS1yZW1vdmV7MCV7b3BhY2l0eToxO3RyYW5zZm9ybTpzY2FsZSgxKX01MCV7b3BhY2l0eTouNTt0cmFuc2Zvcm06c2NhbGUoMS4wNSl9MTAwJXtvcGFjaXR5OjA7dHJhbnNmb3JtOnNjYWxlKDApfX1ALXdlYmtpdC1rZXlmcmFtZXMgcmVwb3J0LWFuaW1hdGlvbi16b29tLXJlbW92ZXswJXtvcGFjaXR5OjE7dHJhbnNmb3JtOnNjYWxlKDEpfTUwJXtvcGFjaXR5Oi41O3RyYW5zZm9ybTpzY2FsZSgxLjA1KX0xMDAle29wYWNpdHk6MDt0cmFuc2Zvcm06c2NhbGUoMCl9fVtpZF49Tm90aWZsaXhMb2FkaW5nV3JhcF17LXdlYmtpdC11c2VyLXNlbGVjdDpub25lOy1tb3otdXNlci1zZWxlY3Q6bm9uZTstbXMtdXNlci1zZWxlY3Q6bm9uZTt1c2VyLXNlbGVjdDpub25lO3Bvc2l0aW9uOmZpeGVkO3otaW5kZXg6MTAwNDt3aWR0aDoxMDAlO2hlaWdodDoxMDAlO2xlZnQ6MDt0b3A6MDtyaWdodDowO2JvdHRvbTowO21hcmdpbjphdXRvO3RleHQtYWxpZ246Y2VudGVyO2JveC1zaXppbmc6Ym9yZGVyLWJveDtiYWNrZ3JvdW5kOnJnYmEoMCwwLDAsLjgpO2ZvbnQtZmFtaWx5OlF1aWNrc2FuZCxzYW5zLXNlcmlmfVtpZF49Tm90aWZsaXhMb2FkaW5nV3JhcF0gKntib3gtc2l6aW5nOmJvcmRlci1ib3h9W2lkXj1Ob3RpZmxpeExvYWRpbmdXcmFwXS5jbGljay10by1jbG9zZXtjdXJzb3I6cG9pbnRlcn1baWRePU5vdGlmbGl4TG9hZGluZ1dyYXBdPmRpdltjbGFzcyo9XCItaWNvblwiXXt3aWR0aDo2MHB4O2hlaWdodDo2MHB4O3Bvc2l0aW9uOmZpeGVkO3RyYW5zaXRpb246dG9wIC4ycyBlYXNlLWluLW91dDtsZWZ0OjA7dG9wOjA7cmlnaHQ6MDtib3R0b206MDttYXJnaW46YXV0b31baWRePU5vdGlmbGl4TG9hZGluZ1dyYXBdPmRpdltjbGFzcyo9XCItaWNvblwiXSBpbWcsW2lkXj1Ob3RpZmxpeExvYWRpbmdXcmFwXT5kaXZbY2xhc3MqPVwiLWljb25cIl0gc3Zne21heC13aWR0aDp1bnNldDttYXgtaGVpZ2h0OnVuc2V0O3dpZHRoOjEwMCU7aGVpZ2h0OjEwMCU7cG9zaXRpb246YWJzb2x1dGU7bGVmdDowO3RvcDowfVtpZF49Tm90aWZsaXhMb2FkaW5nV3JhcF0+ZGl2W2NsYXNzKj1cIi1pY29uXCJdLndpdGgtbWVzc2FnZXt0b3A6LTQycHh9W2lkXj1Ob3RpZmxpeExvYWRpbmdXcmFwXT5we3Bvc2l0aW9uOmZpeGVkO2xlZnQ6MDtyaWdodDowO3RvcDo0MnB4O2JvdHRvbTowO21hcmdpbjphdXRvO2ZvbnQtZmFtaWx5OmluaGVyaXQhaW1wb3J0YW50O2ZvbnQtd2VpZ2h0OjUwMDtsaW5lLWhlaWdodDoxLjQ7cGFkZGluZzowIDEwcHg7d2lkdGg6MTAwJTtmb250LXNpemU6MTVweDtoZWlnaHQ6MThweH1baWRePU5vdGlmbGl4TG9hZGluZ1dyYXBdLndpdGgtYW5pbWF0aW9ue2FuaW1hdGlvbjpsb2FkaW5nLWFuaW1hdGlvbi1mYWRlIC4zcyBlYXNlLWluLW91dCAwcyBub3JtYWw7LXdlYmtpdC1hbmltYXRpb246bG9hZGluZy1hbmltYXRpb24tZmFkZSAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsfUBrZXlmcmFtZXMgbG9hZGluZy1hbmltYXRpb24tZmFkZXswJXtvcGFjaXR5OjB9MTAwJXtvcGFjaXR5OjF9fUAtd2Via2l0LWtleWZyYW1lcyBsb2FkaW5nLWFuaW1hdGlvbi1mYWRlezAle29wYWNpdHk6MH0xMDAle29wYWNpdHk6MX19W2lkXj1Ob3RpZmxpeExvYWRpbmdXcmFwXS53aXRoLWFuaW1hdGlvbi5yZW1vdmV7b3BhY2l0eTowO2FuaW1hdGlvbjpsb2FkaW5nLWFuaW1hdGlvbi1mYWRlLXJlbW92ZSAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsOy13ZWJraXQtYW5pbWF0aW9uOmxvYWRpbmctYW5pbWF0aW9uLWZhZGUtcmVtb3ZlIC4zcyBlYXNlLWluLW91dCAwcyBub3JtYWx9QGtleWZyYW1lcyBsb2FkaW5nLWFuaW1hdGlvbi1mYWRlLXJlbW92ZXswJXtvcGFjaXR5OjF9MTAwJXtvcGFjaXR5OjB9fUAtd2Via2l0LWtleWZyYW1lcyBsb2FkaW5nLWFuaW1hdGlvbi1mYWRlLXJlbW92ZXswJXtvcGFjaXR5OjF9MTAwJXtvcGFjaXR5OjB9fVtpZF49Tm90aWZsaXhMb2FkaW5nV3JhcF0+cC5uZXd7YW5pbWF0aW9uOmxvYWRpbmctbmV3LW1lc3NhZ2UtZmFkZSAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsOy13ZWJraXQtYW5pbWF0aW9uOmxvYWRpbmctbmV3LW1lc3NhZ2UtZmFkZSAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsfUBrZXlmcmFtZXMgbG9hZGluZy1uZXctbWVzc2FnZS1mYWRlezAle29wYWNpdHk6MH0xMDAle29wYWNpdHk6MX19QC13ZWJraXQta2V5ZnJhbWVzIGxvYWRpbmctbmV3LW1lc3NhZ2UtZmFkZXswJXtvcGFjaXR5OjB9MTAwJXtvcGFjaXR5OjF9fVtpZF49Tm90aWZsaXhDb25maXJtV3JhcF17cG9zaXRpb246Zml4ZWQ7ei1pbmRleDoxMDAzO3dpZHRoOjI4MHB4O21heC13aWR0aDo5OCU7bGVmdDoxMHB4O3JpZ2h0OjEwcHg7dG9wOjEwcHg7bWFyZ2luOmF1dG87dGV4dC1hbGlnbjpjZW50ZXI7Ym94LXNpemluZzpib3JkZXItYm94O2JhY2tncm91bmQ6MCAwO2ZvbnQtZmFtaWx5OlF1aWNrc2FuZCxzYW5zLXNlcmlmfVtpZF49Tm90aWZsaXhDb25maXJtV3JhcF0gKntib3gtc2l6aW5nOmJvcmRlci1ib3h9W2lkXj1Ob3RpZmxpeENvbmZpcm1XcmFwXT5kaXZbY2xhc3MqPVwiLW92ZXJsYXlcIl17d2lkdGg6MTAwJTtoZWlnaHQ6MTAwJTtsZWZ0OjA7dG9wOjA7YmFja2dyb3VuZDpyZ2JhKDI1NSwyNTUsMjU1LC41KTtwb3NpdGlvbjpmaXhlZDt6LWluZGV4OjB9W2lkXj1Ob3RpZmxpeENvbmZpcm1XcmFwXT5kaXZbY2xhc3MqPVwiLWNvbnRlbnRcIl17d2lkdGg6MTAwJTtmbG9hdDpsZWZ0O2JvcmRlci1yYWRpdXM6MjVweDtwYWRkaW5nOjEwcHg7bWFyZ2luOjA7ZmlsdGVyOmRyb3Atc2hhZG93KDAgMCA1cHggcmdiYSgwLDAsMCwuMSkpO2JhY2tncm91bmQ6I2Y4ZjhmODtjb2xvcjojMWUxZTFlO3Bvc2l0aW9uOnJlbGF0aXZlO3otaW5kZXg6MX1baWRePU5vdGlmbGl4Q29uZmlybVdyYXBdPmRpdltjbGFzcyo9XCItY29udGVudFwiXT5kaXZbY2xhc3MqPVwiLWhlYWRcIl17ZmxvYXQ6bGVmdDt3aWR0aDoxMDAlfVtpZF49Tm90aWZsaXhDb25maXJtV3JhcF0+ZGl2W2NsYXNzKj1cIi1jb250ZW50XCJdPmRpdltjbGFzcyo9XCItaGVhZFwiXT5oNXtmbG9hdDpsZWZ0O3dpZHRoOjEwMCU7bWFyZ2luOjA7cGFkZGluZzowIDAgMTBweDtib3JkZXItYm90dG9tOjFweCBzb2xpZCByZ2JhKDAsMCwwLC4xKTtjb2xvcjojMDBiNDYyO2ZvbnQtZmFtaWx5OmluaGVyaXQhaW1wb3J0YW50O2ZvbnQtc2l6ZToxNnB4O2xpbmUtaGVpZ2h0OjEuNDtmb250LXdlaWdodDo1MDB9W2lkXj1Ob3RpZmxpeENvbmZpcm1XcmFwXT5kaXZbY2xhc3MqPVwiLWNvbnRlbnRcIl0+ZGl2W2NsYXNzKj1cIi1oZWFkXCJdPnB7Zm9udC1mYW1pbHk6aW5oZXJpdCFpbXBvcnRhbnQ7bWFyZ2luOjE1cHggMCAyMHB4O3BhZGRpbmc6MCAxMHB4O2Zsb2F0OmxlZnQ7d2lkdGg6MTAwJTtmb250LXNpemU6MTRweDtsaW5lLWhlaWdodDoxLjQ7Y29sb3I6aW5oZXJpdH1baWRePU5vdGlmbGl4Q29uZmlybVdyYXBdPmRpdltjbGFzcyo9XCItY29udGVudFwiXT5kaXZbY2xhc3MqPVwiLWJ1dHRvbnNcIl17LXdlYmtpdC11c2VyLXNlbGVjdDpub25lOy1tb3otdXNlci1zZWxlY3Q6bm9uZTstbXMtdXNlci1zZWxlY3Q6bm9uZTt1c2VyLXNlbGVjdDpub25lO2JvcmRlci1yYWRpdXM6aW5oZXJpdDtmbG9hdDpsZWZ0O3dpZHRoOjEwMCV9W2lkXj1Ob3RpZmxpeENvbmZpcm1XcmFwXT5kaXZbY2xhc3MqPVwiLWNvbnRlbnRcIl0+ZGl2W2NsYXNzKj1cIi1idXR0b25zXCJdPmF7Y3Vyc29yOnBvaW50ZXI7Zm9udC1mYW1pbHk6aW5oZXJpdCFpbXBvcnRhbnQ7dHJhbnNpdGlvbjphbGwgLjI1cyBlYXNlLWluLW91dDtmbG9hdDpsZWZ0O3dpZHRoOjQ4JTtwYWRkaW5nOjlweCA1cHg7Ym9yZGVyLXJhZGl1czppbmhlcml0IWltcG9ydGFudDtmb250LXdlaWdodDo1MDA7Zm9udC1zaXplOjE1cHg7bGluZS1oZWlnaHQ6MS40O2NvbG9yOiNmOGY4Zjh9W2lkXj1Ob3RpZmxpeENvbmZpcm1XcmFwXT5kaXZbY2xhc3MqPVwiLWNvbnRlbnRcIl0+ZGl2W2NsYXNzKj1cIi1idXR0b25zXCJdPmEuY29uZmlybS1idXR0b24tb2t7bWFyZ2luOjAgMiUgMCAwO2JhY2tncm91bmQ6IzAwYjQ2Mn1baWRePU5vdGlmbGl4Q29uZmlybVdyYXBdPmRpdltjbGFzcyo9XCItY29udGVudFwiXT5kaXZbY2xhc3MqPVwiLWJ1dHRvbnNcIl0+YS5jb25maXJtLWJ1dHRvbi1jYW5jZWx7bWFyZ2luOjAgMCAwIDIlO2JhY2tncm91bmQ6I2E5YTlhOX1baWRePU5vdGlmbGl4Q29uZmlybVdyYXBdPmRpdltjbGFzcyo9XCItY29udGVudFwiXT5kaXZbY2xhc3MqPVwiLWJ1dHRvbnNcIl0+YS5mdWxse21hcmdpbjowO3dpZHRoOjEwMCV9W2lkXj1Ob3RpZmxpeENvbmZpcm1XcmFwXT5kaXZbY2xhc3MqPVwiLWNvbnRlbnRcIl0+ZGl2W2NsYXNzKj1cIi1idXR0b25zXCJdPmE6aG92ZXJ7Ym94LXNoYWRvdzppbnNldCAwIC02MHB4IDVweCAtNXB4IHJnYmEoMCwwLDAsLjIpfVtpZF49Tm90aWZsaXhDb25maXJtV3JhcF0ucnRsLW9uPmRpdltjbGFzcyo9XCItY29udGVudFwiXT5kaXZbY2xhc3MqPVwiLWJ1dHRvbnNcIl0sW2lkXj1Ob3RpZmxpeENvbmZpcm1XcmFwXS5ydGwtb24+ZGl2W2NsYXNzKj1cIi1jb250ZW50XCJdPmRpdltjbGFzcyo9XCItYnV0dG9uc1wiXT5he3RyYW5zZm9ybTpyb3RhdGVZKDE4MGRlZyl9W2lkXj1Ob3RpZmxpeENvbmZpcm1XcmFwXT5kaXZbY2xhc3MqPVwiLW92ZXJsYXlcIl0ud2l0aC1hbmltYXRpb257YW5pbWF0aW9uOmNvbmZpcm0tb3ZlcmxheS1hbmltYXRpb24gLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbDstd2Via2l0LWFuaW1hdGlvbjpjb25maXJtLW92ZXJsYXktYW5pbWF0aW9uIC4zcyBlYXNlLWluLW91dCAwcyBub3JtYWx9QGtleWZyYW1lcyBjb25maXJtLW92ZXJsYXktYW5pbWF0aW9uezAle29wYWNpdHk6MH0xMDAle29wYWNpdHk6MX19QC13ZWJraXQta2V5ZnJhbWVzIGNvbmZpcm0tb3ZlcmxheS1hbmltYXRpb257MCV7b3BhY2l0eTowfTEwMCV7b3BhY2l0eToxfX1baWRePU5vdGlmbGl4Q29uZmlybVdyYXBdLnJlbW92ZT5kaXZbY2xhc3MqPVwiLW92ZXJsYXlcIl0ud2l0aC1hbmltYXRpb257b3BhY2l0eTowO2FuaW1hdGlvbjpjb25maXJtLW92ZXJsYXktYW5pbWF0aW9uLXJlbW92ZSAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsOy13ZWJraXQtYW5pbWF0aW9uOmNvbmZpcm0tb3ZlcmxheS1hbmltYXRpb24tcmVtb3ZlIC4zcyBlYXNlLWluLW91dCAwcyBub3JtYWx9QGtleWZyYW1lcyBjb25maXJtLW92ZXJsYXktYW5pbWF0aW9uLXJlbW92ZXswJXtvcGFjaXR5OjF9MTAwJXtvcGFjaXR5OjB9fUAtd2Via2l0LWtleWZyYW1lcyBjb25maXJtLW92ZXJsYXktYW5pbWF0aW9uLXJlbW92ZXswJXtvcGFjaXR5OjF9MTAwJXtvcGFjaXR5OjB9fVtpZF49Tm90aWZsaXhDb25maXJtV3JhcF0ud2l0aC1hbmltYXRpb24ubngtZmFkZT5kaXZbY2xhc3MqPVwiLWNvbnRlbnRcIl17YW5pbWF0aW9uOmNvbmZpcm0tYW5pbWF0aW9uLWZhZGUgLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbDstd2Via2l0LWFuaW1hdGlvbjpjb25maXJtLWFuaW1hdGlvbi1mYWRlIC4zcyBlYXNlLWluLW91dCAwcyBub3JtYWx9QGtleWZyYW1lcyBjb25maXJtLWFuaW1hdGlvbi1mYWRlezAle29wYWNpdHk6MH0xMDAle29wYWNpdHk6MX19QC13ZWJraXQta2V5ZnJhbWVzIGNvbmZpcm0tYW5pbWF0aW9uLWZhZGV7MCV7b3BhY2l0eTowfTEwMCV7b3BhY2l0eToxfX1baWRePU5vdGlmbGl4Q29uZmlybVdyYXBdLndpdGgtYW5pbWF0aW9uLm54LXpvb20+ZGl2W2NsYXNzKj1cIi1jb250ZW50XCJde2FuaW1hdGlvbjpjb25maXJtLWFuaW1hdGlvbi16b29tIC4zcyBlYXNlLWluLW91dCAwcyBub3JtYWw7LXdlYmtpdC1hbmltYXRpb246Y29uZmlybS1hbmltYXRpb24tem9vbSAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsfUBrZXlmcmFtZXMgY29uZmlybS1hbmltYXRpb24tem9vbXswJXtvcGFjaXR5OjA7dHJhbnNmb3JtOnNjYWxlKC41KX01MCV7b3BhY2l0eToxO3RyYW5zZm9ybTpzY2FsZSgxLjA1KX0xMDAle29wYWNpdHk6MTt0cmFuc2Zvcm06c2NhbGUoMSl9fUAtd2Via2l0LWtleWZyYW1lcyBjb25maXJtLWFuaW1hdGlvbi16b29tezAle29wYWNpdHk6MDt0cmFuc2Zvcm06c2NhbGUoLjUpfTUwJXtvcGFjaXR5OjE7dHJhbnNmb3JtOnNjYWxlKDEuMDUpfTEwMCV7b3BhY2l0eToxO3RyYW5zZm9ybTpzY2FsZSgxKX19W2lkXj1Ob3RpZmxpeENvbmZpcm1XcmFwXS53aXRoLWFuaW1hdGlvbi5ueC1mYWRlLnJlbW92ZT5kaXZbY2xhc3MqPVwiLWNvbnRlbnRcIl17b3BhY2l0eTowO2FuaW1hdGlvbjpjb25maXJtLWFuaW1hdGlvbi1mYWRlLXJlbW92ZSAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsOy13ZWJraXQtYW5pbWF0aW9uOmNvbmZpcm0tYW5pbWF0aW9uLWZhZGUtcmVtb3ZlIC4zcyBlYXNlLWluLW91dCAwcyBub3JtYWx9QGtleWZyYW1lcyBjb25maXJtLWFuaW1hdGlvbi1mYWRlLXJlbW92ZXswJXtvcGFjaXR5OjF9MTAwJXtvcGFjaXR5OjB9fUAtd2Via2l0LWtleWZyYW1lcyBjb25maXJtLWFuaW1hdGlvbi1mYWRlLXJlbW92ZXswJXtvcGFjaXR5OjF9MTAwJXtvcGFjaXR5OjB9fVtpZF49Tm90aWZsaXhDb25maXJtV3JhcF0ud2l0aC1hbmltYXRpb24ubngtem9vbS5yZW1vdmU+ZGl2W2NsYXNzKj1cIi1jb250ZW50XCJde29wYWNpdHk6MDthbmltYXRpb246Y29uZmlybS1hbmltYXRpb24tem9vbS1yZW1vdmUgLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbDstd2Via2l0LWFuaW1hdGlvbjpjb25maXJtLWFuaW1hdGlvbi16b29tLXJlbW92ZSAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsfUBrZXlmcmFtZXMgY29uZmlybS1hbmltYXRpb24tem9vbS1yZW1vdmV7MCV7b3BhY2l0eToxO3RyYW5zZm9ybTpzY2FsZSgxKX01MCV7b3BhY2l0eTouNTt0cmFuc2Zvcm06c2NhbGUoMS4wNSl9MTAwJXtvcGFjaXR5OjA7dHJhbnNmb3JtOnNjYWxlKDApfX1ALXdlYmtpdC1rZXlmcmFtZXMgY29uZmlybS1hbmltYXRpb24tem9vbS1yZW1vdmV7MCV7b3BhY2l0eToxO3RyYW5zZm9ybTpzY2FsZSgxKX01MCV7b3BhY2l0eTouNTt0cmFuc2Zvcm06c2NhbGUoMS4wNSl9MTAwJXtvcGFjaXR5OjA7dHJhbnNmb3JtOnNjYWxlKDApfX1gO1xyXG5cclxuICAgIHJldHVybiBjc3M7XHJcbn1cclxuLy8gSW50ZXJuYWwgQ1NTIENvZGVzIG9mZlxyXG5cclxuLy8gSW50ZXJuYWwgQ1NTIEZ1bmMgb25cclxuY29uc3Qgbm90aWZsaXhJbnRlcm5hbENTUyA9ICgpID0+IHtcclxuICAgIGlmICghZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ05vdGlmbGl4SW50ZXJuYWxDU1MnKSkge1xyXG4gICAgICAgIGNvbnN0IGludGVybmFsQ1NTID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnc3R5bGUnKTtcclxuICAgICAgICBpbnRlcm5hbENTUy5pZCA9ICdOb3RpZmxpeEludGVybmFsQ1NTJztcclxuICAgICAgICAvLyBpbnRlcm5hbENTUy50eXBlID0gJ3RleHQvY3NzJzsgLy8gPT4gbm90IG5lY2Vzc2FyeVxyXG4gICAgICAgIGludGVybmFsQ1NTLmlubmVySFRNTCA9IG5vdGlmbGl4SW50ZXJuYWxDU1NDb2RlcygpO1xyXG4gICAgICAgIGRvY3VtZW50LmhlYWQuYXBwZW5kQ2hpbGQoaW50ZXJuYWxDU1MpO1xyXG4gICAgfVxyXG59XHJcbi8vIEludGVybmFsIENTUyBGdW5jIG9mZlxyXG5cclxuLy8gTm90aWZsaXg6IEV4dGVuZCBvblxyXG5jb25zdCBleHRlbmROb3RpZmxpeCA9IGZ1bmN0aW9uICgpIHtcclxuICAgIGxldCBleHRlbmRlZCA9IHt9O1xyXG4gICAgbGV0IGRlZXAgPSBmYWxzZTtcclxuICAgIGxldCBpID0gMDtcclxuICAgIGlmIChPYmplY3QucHJvdG90eXBlLnRvU3RyaW5nLmNhbGwoYXJndW1lbnRzWzBdKSA9PT0gJ1tvYmplY3QgQm9vbGVhbl0nKSB7XHJcbiAgICAgICAgZGVlcCA9IGFyZ3VtZW50c1swXTtcclxuICAgICAgICBpKys7XHJcbiAgICB9XHJcbiAgICBsZXQgbWVyZ2UgPSBmdW5jdGlvbiAob2JqKSB7XHJcbiAgICAgICAgZm9yIChsZXQgcHJvcCBpbiBvYmopIHtcclxuICAgICAgICAgICAgaWYgKG9iai5oYXNPd25Qcm9wZXJ0eShwcm9wKSkge1xyXG4gICAgICAgICAgICAgICAgLy8gSWYgcHJvcGVydHkgaXMgYW4gb2JqZWN0LCBtZXJnZSBwcm9wZXJ0aWVzXHJcbiAgICAgICAgICAgICAgICBpZiAoZGVlcCAmJiBPYmplY3QucHJvdG90eXBlLnRvU3RyaW5nLmNhbGwob2JqW3Byb3BdKSA9PT0gJ1tvYmplY3QgT2JqZWN0XScpIHtcclxuICAgICAgICAgICAgICAgICAgICBleHRlbmRlZFtwcm9wXSA9IGV4dGVuZE5vdGlmbGl4KGV4dGVuZGVkW3Byb3BdLCBvYmpbcHJvcF0pO1xyXG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICBleHRlbmRlZFtwcm9wXSA9IG9ialtwcm9wXTtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuICAgIH07XHJcbiAgICBmb3IgKDsgaSA8IGFyZ3VtZW50cy5sZW5ndGg7IGkrKykge1xyXG4gICAgICAgIG1lcmdlKGFyZ3VtZW50c1tpXSk7XHJcbiAgICB9XHJcbiAgICByZXR1cm4gZXh0ZW5kZWQ7XHJcbn07XHJcbi8vIE5vdGlmbGl4OiBFeHRlbmQgb2ZmXHJcblxyXG4vLyBOb3RpZmxpeDogUGxhaW50ZXh0IG9uXHJcbmNvbnN0IG5vdGlmbGl4UGxhaW50ZXh0ID0gZnVuY3Rpb24gKGh0bWwpIHtcclxuICAgIGNvbnN0IGh0bWxQb29sID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XHJcbiAgICBodG1sUG9vbC5pbm5lckhUTUwgPSBodG1sO1xyXG4gICAgcmV0dXJuIGh0bWxQb29sLnRleHRDb250ZW50IHx8IGh0bWxQb29sLmlubmVyVGV4dCB8fCAnJztcclxufVxyXG4vLyBOb3RpZmxpeDogUGxhaW50ZXh0IG9mZlxyXG5cclxuLy8gTm90aWZsaXg6IEdvb2dsZUZvbnQgb25cclxuY29uc3Qgbm90aWZsaXhHb29nbGVGb250ID0gZnVuY3Rpb24gKCkge1xyXG4gICAgaWYgKCFkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnTm90aWZsaXhRdWlja3NhbmQnKSkge1xyXG4gICAgICAgIC8vIGdvb2dsZSBmb250cyBkbnMgcHJlZmV0Y2ggb25cclxuICAgICAgICBjb25zdCBkbnMgPSAnPGxpbmsgaWQ9XCJOb3RpZmxpeEdvb2dsZUROU1wiIHJlbD1cImRucy1wcmVmZXRjaFwiIGhyZWY9XCIvL2ZvbnRzLmdvb2dsZWFwaXMuY29tXCI+JztcclxuICAgICAgICBjb25zdCBkbnNSYW5nZSA9IGRvY3VtZW50LmNyZWF0ZVJhbmdlKCk7XHJcbiAgICAgICAgZG5zUmFuZ2Uuc2VsZWN0Tm9kZShkb2N1bWVudC5oZWFkKTtcclxuICAgICAgICBjb25zdCBkbnNGcmFnbWVudCA9IGRuc1JhbmdlLmNyZWF0ZUNvbnRleHR1YWxGcmFnbWVudChkbnMpO1xyXG4gICAgICAgIGRvY3VtZW50LmhlYWQuYXBwZW5kQ2hpbGQoZG5zRnJhZ21lbnQpO1xyXG4gICAgICAgIC8vIGdvb2dsZSBmb250cyBkbnMgcHJlZmV0Y2ggb2ZmXHJcblxyXG4gICAgICAgIC8vIGdvb2dsZSBmb250cyBzdHlsZSBvblxyXG4gICAgICAgIGNvbnN0IGZvbnQgPSAnPGxpbmsgaWQ9XCJOb3RpZmxpeFF1aWNrc2FuZFwiIGhyZWY9XCJodHRwczovL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9UXVpY2tzYW5kOjMwMCw0MDAsNTAwLDcwMCZhbXA7c3Vic2V0PWxhdGluLWV4dFwiIHJlbD1cInN0eWxlc2hlZXRcIiAvPic7XHJcbiAgICAgICAgY29uc3QgZm9udFJhbmdlID0gZG9jdW1lbnQuY3JlYXRlUmFuZ2UoKTtcclxuICAgICAgICBmb250UmFuZ2Uuc2VsZWN0Tm9kZShkb2N1bWVudC5oZWFkKTtcclxuICAgICAgICBjb25zdCBmb250RnJhZ21lbnQgPSBmb250UmFuZ2UuY3JlYXRlQ29udGV4dHVhbEZyYWdtZW50KGZvbnQpO1xyXG4gICAgICAgIGRvY3VtZW50LmhlYWQuYXBwZW5kQ2hpbGQoZm9udEZyYWdtZW50KTtcclxuICAgICAgICAvLyBnb29nbGUgZm9udHMgc3R5bGUgb2ZmXHJcbiAgICB9XHJcbn1cclxuLy8gTm90aWZsaXg6IEdvb2dsZUZvbnQgb2ZmXHJcblxyXG4vLyBOb3RpZmxpeDogQ29uc29sZSBFcnJvciBvblxyXG5jb25zdCBub3RpZmxpeENvbnNvbGVFcnJvciA9IGZ1bmN0aW9uICh0aXRsZSwgbWVzc2FnZSkge1xyXG4gICAgcmV0dXJuIGNvbnNvbGUuZXJyb3IoJyVjICcgKyB0aXRsZSArICcgJywgJ3BhZGRpbmc6MnB4O2JvcmRlci1yYWRpdXM6MjBweDtjb2xvcjojZmZmO2JhY2tncm91bmQ6I2Y0NDMzNicsICdcXG4nICsgbWVzc2FnZSArICdcXG5WaXNpdCBkb2N1bWVudGF0aW9uIHBhZ2UgdG8gbGVhcm4gbW9yZTogaHR0cHM6Ly93d3cubm90aWZsaXguY29tL2RvY3VtZW50YXRpb24nKTtcclxufVxyXG4vLyBOb3RpZmxpeDogQ29uc29sZSBFcnJvciBvZmZcclxuXHJcblxyXG4vLyBOb3RpZmxpeDogTm90aWZ5IERlZmF1bHQgU2V0dGluZ3Mgb25cclxubGV0IG5vdGlmeVNldHRpbmdzID0ge1xyXG4gICAgd3JhcElEOiAnTm90aWZsaXhOb3RpZnlXcmFwJywgLy8gY2FuIG5vdCBjdXN0b21pemFibGVcclxuICAgIHdpZHRoOiAnMjgwcHgnLFxyXG4gICAgcG9zaXRpb246ICdyaWdodC10b3AnLCAvLyAncmlnaHQtdG9wJyAtICdyaWdodC1ib3R0b20nIC0gJ2xlZnQtdG9wJyAtICdsZWZ0LWJvdHRvbSdcclxuICAgIGRpc3RhbmNlOiAnMTBweCcsXHJcbiAgICBvcGFjaXR5OiAxLFxyXG4gICAgYm9yZGVyUmFkaXVzOiAnNXB4JyxcclxuICAgIHJ0bDogZmFsc2UsXHJcbiAgICB0aW1lb3V0OiAzMDAwLFxyXG4gICAgbWVzc2FnZU1heExlbmd0aDogMTEwLFxyXG4gICAgYmFja092ZXJsYXk6IGZhbHNlLFxyXG4gICAgYmFja092ZXJsYXlDb2xvcjogJ3JnYmEoMCwwLDAsMC41KScsXHJcblxyXG4gICAgSUQ6ICdOb3RpZmxpeE5vdGlmeScsXHJcbiAgICBjbGFzc05hbWU6ICdub3RpZmxpeC1ub3RpZnknLFxyXG4gICAgemluZGV4OiA0MDAxLFxyXG4gICAgdXNlR29vZ2xlRm9udDogdHJ1ZSxcclxuICAgIGZvbnRGYW1pbHk6ICdRdWlja3NhbmQnLFxyXG4gICAgZm9udFNpemU6ICcxM3B4JyxcclxuICAgIGNzc0FuaW1hdGlvbjogdHJ1ZSxcclxuICAgIGNzc0FuaW1hdGlvbkR1cmF0aW9uOiA0MDAsXHJcbiAgICBjc3NBbmltYXRpb25TdHlsZTogJ2ZhZGUnLCAvLyAnZmFkZScgLSAnem9vbScgLSAnZnJvbS1yaWdodCcgLSAnZnJvbS10b3AnIC0gJ2Zyb20tYm90dG9tJyAtICdmcm9tLWxlZnQnXHJcbiAgICBjbG9zZUJ1dHRvbjogZmFsc2UsXHJcbiAgICB1c2VJY29uOiB0cnVlLFxyXG4gICAgdXNlRm9udEF3ZXNvbWU6IGZhbHNlLFxyXG4gICAgZm9udEF3ZXNvbWVJY29uU3R5bGU6ICdiYXNpYycsIC8vICdiYXNpYycgLSAnc2hhZG93J1xyXG4gICAgZm9udEF3ZXNvbWVJY29uU2l6ZTogJzM0cHgnLFxyXG5cclxuICAgIHBsYWluVGV4dDogdHJ1ZSxcclxuXHJcbiAgICBzdWNjZXNzOiB7XHJcbiAgICAgICAgYmFja2dyb3VuZDogJyMwMGI0NjInLFxyXG4gICAgICAgIHRleHRDb2xvcjogJyNmZmYnLFxyXG4gICAgICAgIGNoaWxkQ2xhc3NOYW1lOiAnc3VjY2VzcycsXHJcbiAgICAgICAgbm90aWZsaXhJY29uQ29sb3I6ICdyZ2JhKDAsIDAsIDAsIDAuMjUpJyxcclxuICAgICAgICBmb250QXdlc29tZUNsYXNzTmFtZTogJ2ZhcyBmYS1jaGVjay1jaXJjbGUnLFxyXG4gICAgICAgIGZvbnRBd2Vzb21lSWNvbkNvbG9yOiAncmdiYSgwLCAwLCAwLCAwLjIpJyxcclxuICAgIH0sXHJcblxyXG4gICAgZmFpbHVyZToge1xyXG4gICAgICAgIGJhY2tncm91bmQ6ICcjZjQ0MzM2JyxcclxuICAgICAgICB0ZXh0Q29sb3I6ICcjZmZmJyxcclxuICAgICAgICBjaGlsZENsYXNzTmFtZTogJ2ZhaWx1cmUnLFxyXG4gICAgICAgIG5vdGlmbGl4SWNvbkNvbG9yOiAncmdiYSgwLCAwLCAwLCAwLjIpJyxcclxuICAgICAgICBmb250QXdlc29tZUNsYXNzTmFtZTogJ2ZhcyBmYS10aW1lcy1jaXJjbGUnLFxyXG4gICAgICAgIGZvbnRBd2Vzb21lSWNvbkNvbG9yOiAncmdiYSgwLCAwLCAwLCAwLjIpJyxcclxuICAgIH0sXHJcblxyXG4gICAgd2FybmluZzoge1xyXG4gICAgICAgIGJhY2tncm91bmQ6ICcjZjJiZDFkJyxcclxuICAgICAgICB0ZXh0Q29sb3I6ICcjZmZmJyxcclxuICAgICAgICBjaGlsZENsYXNzTmFtZTogJ3dhcm5pbmcnLFxyXG4gICAgICAgIG5vdGlmbGl4SWNvbkNvbG9yOiAncmdiYSgwLCAwLCAwLCAwLjIpJyxcclxuICAgICAgICBmb250QXdlc29tZUNsYXNzTmFtZTogJ2ZhcyBmYS1leGNsYW1hdGlvbi1jaXJjbGUnLFxyXG4gICAgICAgIGZvbnRBd2Vzb21lSWNvbkNvbG9yOiAncmdiYSgwLCAwLCAwLCAwLjIpJyxcclxuICAgIH0sXHJcblxyXG4gICAgaW5mbzoge1xyXG4gICAgICAgIGJhY2tncm91bmQ6ICcjMDBiY2Q0JyxcclxuICAgICAgICB0ZXh0Q29sb3I6ICcjZmZmJyxcclxuICAgICAgICBjaGlsZENsYXNzTmFtZTogJ2luZm8nLFxyXG4gICAgICAgIG5vdGlmbGl4SWNvbkNvbG9yOiAncmdiYSgwLCAwLCAwLCAwLjIpJyxcclxuICAgICAgICBmb250QXdlc29tZUNsYXNzTmFtZTogJ2ZhcyBmYS1pbmZvLWNpcmNsZScsXHJcbiAgICAgICAgZm9udEF3ZXNvbWVJY29uQ29sb3I6ICdyZ2JhKDAsIDAsIDAsIDAuMiknLFxyXG4gICAgfSxcclxufTtcclxuLy8gTm90aWZsaXg6IE5vdGlmeSBEZWZhdWx0IFNldHRpbmdzIG9mZlxyXG5cclxuLy8gTm90aWZsaXg6IFJlcG9ydCBEZWZhdWx0IFNldHRpbmdzIG9uXHJcbmxldCByZXBvcnRTZXR0aW5ncyA9IHtcclxuICAgIElEOiAnTm90aWZsaXhSZXBvcnRXcmFwJywgLy8gY2FuIG5vdCBjdXN0b21pemFibGVcclxuICAgIGNsYXNzTmFtZTogJ25vdGlmbGl4LXJlcG9ydCcsXHJcbiAgICB3aWR0aDogJzMyMHB4JyxcclxuICAgIGJhY2tncm91bmRDb2xvcjogJyNmOGY4ZjgnLFxyXG4gICAgYm9yZGVyUmFkaXVzOiAnMjVweCcsXHJcbiAgICBydGw6IGZhbHNlLFxyXG4gICAgemluZGV4OiA0MDAyLFxyXG4gICAgYmFja092ZXJsYXk6IHRydWUsXHJcbiAgICBiYWNrT3ZlcmxheUNvbG9yOiAncmdiYSgwLCAwLCAwLCAwLjUpJyxcclxuICAgIHVzZUdvb2dsZUZvbnQ6IHRydWUsXHJcbiAgICBmb250RmFtaWx5OiAnUXVpY2tzYW5kJyxcclxuICAgIHN2Z1NpemU6ICcxMTBweCcsXHJcbiAgICBwbGFpblRleHQ6IHRydWUsXHJcbiAgICB0aXRsZUZvbnRTaXplOiAnMTZweCcsXHJcbiAgICB0aXRsZU1heExlbmd0aDogMzQsXHJcbiAgICBtZXNzYWdlRm9udFNpemU6ICcxM3B4JyxcclxuICAgIG1lc3NhZ2VNYXhMZW5ndGg6IDQwMCxcclxuICAgIGJ1dHRvbkZvbnRTaXplOiAnMTRweCcsXHJcbiAgICBidXR0b25NYXhMZW5ndGg6IDM0LFxyXG4gICAgY3NzQW5pbWF0aW9uOiB0cnVlLFxyXG4gICAgY3NzQW5pbWF0aW9uRHVyYXRpb246IDM2MCxcclxuICAgIGNzc0FuaW1hdGlvblN0eWxlOiAnZmFkZScsIC8vICdmYWRlJyAtICd6b29tJ1xyXG5cclxuICAgIHN1Y2Nlc3M6IHtcclxuICAgICAgICBzdmdDb2xvcjogJyMwMGI0NjInLFxyXG4gICAgICAgIHRpdGxlQ29sb3I6ICcjMWUxZTFlJyxcclxuICAgICAgICBtZXNzYWdlQ29sb3I6ICcjMjQyNDI0JyxcclxuICAgICAgICBidXR0b25CYWNrZ3JvdW5kOiAnIzAwYjQ2MicsXHJcbiAgICAgICAgYnV0dG9uQ29sb3I6ICcjZmZmJyxcclxuICAgIH0sXHJcblxyXG4gICAgZmFpbHVyZToge1xyXG4gICAgICAgIHN2Z0NvbG9yOiAnI2Y0NDMzNicsXHJcbiAgICAgICAgdGl0bGVDb2xvcjogJyMxZTFlMWUnLFxyXG4gICAgICAgIG1lc3NhZ2VDb2xvcjogJyMyNDI0MjQnLFxyXG4gICAgICAgIGJ1dHRvbkJhY2tncm91bmQ6ICcjZjQ0MzM2JyxcclxuICAgICAgICBidXR0b25Db2xvcjogJyNmZmYnLFxyXG4gICAgfSxcclxuXHJcbiAgICB3YXJuaW5nOiB7XHJcbiAgICAgICAgc3ZnQ29sb3I6ICcjZjJiZDFkJyxcclxuICAgICAgICB0aXRsZUNvbG9yOiAnIzFlMWUxZScsXHJcbiAgICAgICAgbWVzc2FnZUNvbG9yOiAnIzI0MjQyNCcsXHJcbiAgICAgICAgYnV0dG9uQmFja2dyb3VuZDogJyNmMmJkMWQnLFxyXG4gICAgICAgIGJ1dHRvbkNvbG9yOiAnI2ZmZicsXHJcbiAgICB9LFxyXG5cclxuICAgIGluZm86IHtcclxuICAgICAgICBzdmdDb2xvcjogJyMwMGJjZDQnLFxyXG4gICAgICAgIHRpdGxlQ29sb3I6ICcjMWUxZTFlJyxcclxuICAgICAgICBtZXNzYWdlQ29sb3I6ICcjMjQyNDI0JyxcclxuICAgICAgICBidXR0b25CYWNrZ3JvdW5kOiAnIzAwYmNkNCcsXHJcbiAgICAgICAgYnV0dG9uQ29sb3I6ICcjZmZmJyxcclxuICAgIH0sXHJcblxyXG59O1xyXG4vLyBOb3RpZmxpeDogUmVwb3J0IERlZmF1bHQgU2V0dGluZ3Mgb2ZmXHJcblxyXG4vLyBOb3RpZmxpeDogQ29uZmlybSBEZWZhdWx0IFNldHRpbmdzIG9uXHJcbmxldCBjb25maXJtU2V0dGluZ3MgPSB7XHJcbiAgICBJRDogJ05vdGlmbGl4Q29uZmlybVdyYXAnLCAvLyBjYW4gbm90IGN1c3RvbWl6YWJsZVxyXG4gICAgY2xhc3NOYW1lOiAnbm90aWZsaXgtY29uZmlybScsXHJcbiAgICB3aWR0aDogJzI4MHB4JyxcclxuICAgIHppbmRleDogNDAwMyxcclxuICAgIHBvc2l0aW9uOiAnY2VudGVyJywgLy8gJ2NlbnRlcicgLSAnY2VudGVyLXRvcCcgLSAgJ3JpZ2h0LXRvcCcgLSAncmlnaHQtYm90dG9tJyAtICdsZWZ0LXRvcCcgLSAnbGVmdC1ib3R0b20nXHJcbiAgICBkaXN0YW5jZTogJzEwcHgnLFxyXG4gICAgYmFja2dyb3VuZENvbG9yOiAnI2Y4ZjhmOCcsXHJcbiAgICBib3JkZXJSYWRpdXM6ICcyNXB4JyxcclxuICAgIGJhY2tPdmVybGF5OiB0cnVlLFxyXG4gICAgYmFja092ZXJsYXlDb2xvcjogJ3JnYmEoMCwwLDAsMC41KScsXHJcbiAgICBydGw6IGZhbHNlLFxyXG4gICAgdXNlR29vZ2xlRm9udDogdHJ1ZSxcclxuICAgIGZvbnRGYW1pbHk6ICdRdWlja3NhbmQnLFxyXG4gICAgY3NzQW5pbWF0aW9uOiB0cnVlLFxyXG4gICAgY3NzQW5pbWF0aW9uU3R5bGU6ICdmYWRlJywgLy8gJ3pvb20nIC0gJ2ZhZGUnXHJcbiAgICBjc3NBbmltYXRpb25EdXJhdGlvbjogMzAwLFxyXG5cclxuICAgIHRpdGxlQ29sb3I6ICcjMDBiNDYyJyxcclxuICAgIHRpdGxlRm9udFNpemU6ICcxNnB4JyxcclxuICAgIHRpdGxlTWF4TGVuZ3RoOiAzNCxcclxuXHJcbiAgICBtZXNzYWdlQ29sb3I6ICcjMWUxZTFlJyxcclxuICAgIG1lc3NhZ2VGb250U2l6ZTogJzE0cHgnLFxyXG4gICAgbWVzc2FnZU1heExlbmd0aDogMTEwLFxyXG5cclxuICAgIGJ1dHRvbnNGb250U2l6ZTogJzE1cHgnLFxyXG4gICAgYnV0dG9uc01heExlbmd0aDogMzQsXHJcbiAgICBva0J1dHRvbkNvbG9yOiAnI2Y4ZjhmOCcsXHJcbiAgICBva0J1dHRvbkJhY2tncm91bmQ6ICcjMDBiNDYyJyxcclxuICAgIGNhbmNlbEJ1dHRvbkNvbG9yOiAnI2Y4ZjhmOCcsXHJcbiAgICBjYW5jZWxCdXR0b25CYWNrZ3JvdW5kOiAnI2E5YTlhOScsXHJcblxyXG4gICAgcGxhaW5UZXh0OiB0cnVlLFxyXG59XHJcbi8vIE5vdGlmbGl4OiBDb25maXJtIERlZmF1bHQgU2V0dGluZ3Mgb2ZmXHJcblxyXG4vLyBOb3RpZmxpeDogTG9hZGluZyBEZWZhdWx0IFNldHRpbmdzIG9uXHJcbmxldCBsb2FkaW5nU2V0dGluZ3MgPSB7XHJcbiAgICBJRDogJ05vdGlmbGl4TG9hZGluZ1dyYXAnLCAvLyBjYW4gbm90IGN1c3RvbWl6YWJsZVxyXG4gICAgY2xhc3NOYW1lOiAnbm90aWZsaXgtbG9hZGluZycsXHJcbiAgICB6aW5kZXg6IDQwMDAsXHJcbiAgICBiYWNrZ3JvdW5kQ29sb3I6ICdyZ2JhKDAsMCwwLDAuOCknLFxyXG4gICAgcnRsOiBmYWxzZSxcclxuICAgIHVzZUdvb2dsZUZvbnQ6IHRydWUsXHJcbiAgICBmb250RmFtaWx5OiAnUXVpY2tzYW5kJyxcclxuICAgIGNzc0FuaW1hdGlvbjogdHJ1ZSxcclxuICAgIGNzc0FuaW1hdGlvbkR1cmF0aW9uOiA0MDAsXHJcbiAgICBjbGlja1RvQ2xvc2U6IGZhbHNlLFxyXG4gICAgY3VzdG9tU3ZnVXJsOiBudWxsLFxyXG4gICAgc3ZnU2l6ZTogJzgwcHgnLFxyXG4gICAgc3ZnQ29sb3I6ICcjMDBiNDYyJyxcclxuICAgIG1lc3NhZ2VJRDogJ05vdGlmbGl4TG9hZGluZ01lc3NhZ2UnLFxyXG4gICAgbWVzc2FnZUZvbnRTaXplOiAnMTVweCcsXHJcbiAgICBtZXNzYWdlTWF4TGVuZ3RoOiAzNCxcclxuICAgIG1lc3NhZ2VDb2xvcjogJyNkY2RjZGMnLFxyXG59O1xyXG4vLyBOb3RpZmxpeDogTG9hZGluZyBEZWZhdWx0IFNldHRpbmdzIG9mZlxyXG5cclxuLy8gTm90aWZsaXg6IE5YIFJlYWN0IG9uXHJcbmxldCBuZXdOb3RpZnlTZXR0aW5ncztcclxubGV0IG5ld1JlcG9ydFNldHRpbmdzO1xyXG5sZXQgbmV3Q29uZmlybVNldHRpbmdzO1xyXG5sZXQgbmV3TG9hZGluZ1NldHRpbmdzO1xyXG5jb25zdCBOb3RpZmxpeCA9IHtcclxuXHJcbiAgICAvLyBOb3RpZnkgb25cclxuICAgIE5vdGlmeToge1xyXG5cclxuICAgICAgICAvLyBJbml0XHJcbiAgICAgICAgSW5pdDogZnVuY3Rpb24gKHVzZXJOb3RpZnlPcHQpIHtcclxuICAgICAgICAgICAgbmV3Tm90aWZ5U2V0dGluZ3MgPSBleHRlbmROb3RpZmxpeCh0cnVlLCBub3RpZnlTZXR0aW5ncywgdXNlck5vdGlmeU9wdCk7XHJcblxyXG4gICAgICAgICAgICAvLyB1c2UgR29vZ2xlRm9udHMgaWYgXCJRdWlja3NhbmRcIiBvblxyXG4gICAgICAgICAgICBpZiAobmV3Tm90aWZ5U2V0dGluZ3MudXNlR29vZ2xlRm9udCAmJiBuZXdOb3RpZnlTZXR0aW5ncy5mb250RmFtaWx5ID09PSAnUXVpY2tzYW5kJykge1xyXG4gICAgICAgICAgICAgICAgbm90aWZsaXhHb29nbGVGb250KCk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgLy8gdXNlIEdvb2dsZUZvbnRzIGlmIFwiUXVpY2tzYW5kXCIgb2ZmXHJcblxyXG4gICAgICAgICAgICAvLyBhZGQgY3NzIGNvZGVzIG9uXHJcbiAgICAgICAgICAgIG5vdGlmbGl4SW50ZXJuYWxDU1MoKTtcclxuICAgICAgICAgICAgLy8gYWRkIGNzcyBjb2RlcyBvZmZcclxuICAgICAgICB9LFxyXG5cclxuICAgICAgICAvLyBNZXJnZSBGaXJzdCBJbml0XHJcbiAgICAgICAgTWVyZ2U6IGZ1bmN0aW9uICh1c2VyTm90aWZ5RXh0ZW5kKSB7XHJcblxyXG4gICAgICAgICAgICBpZiAobmV3Tm90aWZ5U2V0dGluZ3MpIHsgLy8gaWYgaW5pdGlhbGl6ZWQgYWxyZWFkeVxyXG4gICAgICAgICAgICAgICAgbmV3Tm90aWZ5U2V0dGluZ3MgPSBleHRlbmROb3RpZmxpeCh0cnVlLCBuZXdOb3RpZnlTZXR0aW5ncywgdXNlck5vdGlmeUV4dGVuZCk7XHJcbiAgICAgICAgICAgIH0gZWxzZSB7IC8vIGVycm9yXHJcbiAgICAgICAgICAgICAgICBub3RpZmxpeENvbnNvbGVFcnJvcignTm90aWZsaXggRXJyb3InLCAnWW91IGhhdmUgdG8gaW5pdGlhbGl6ZSB0aGUgTm90aWZ5IG1vZHVsZSBiZWZvcmUgY2FsbCBNZXJnZSBmdW5jdGlvbi4nKTtcclxuICAgICAgICAgICAgICAgIHJldHVybjtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH0sXHJcblxyXG4gICAgICAgIC8vIERpc3BsYXkgTm90aWZpY2F0aW9uOiBTdWNjZXNzXHJcbiAgICAgICAgU3VjY2VzczogZnVuY3Rpb24gKG1lc3NhZ2UsIGNhbGxiYWNrKSB7XHJcblxyXG4gICAgICAgICAgICAvLyBpZiBub3QgaW5pdGlhbGl6ZWQgcHJldGVuZCBsaWtlIGluaXRcclxuICAgICAgICAgICAgaWYgKCFuZXdOb3RpZnlTZXR0aW5ncykge1xyXG4gICAgICAgICAgICAgICAgTm90aWZsaXguTm90aWZ5LkluaXQoe30pO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBpZiAoIWNhbGxiYWNrKSB7XHJcbiAgICAgICAgICAgICAgICBjYWxsYmFjayA9IHVuZGVmaW5lZDtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgY29uc3QgdGhlVHlwZSA9IG5ld05vdGlmeVNldHRpbmdzLnN1Y2Nlc3M7XHJcbiAgICAgICAgICAgIE5vdGlmbGl4Tm90aWZ5KG1lc3NhZ2UsIGNhbGxiYWNrLCB0aGVUeXBlLCAnU3VjY2VzcycpO1xyXG4gICAgICAgIH0sXHJcblxyXG4gICAgICAgIC8vIERpc3BsYXkgTm90aWZpY2F0aW9uOiBGYWlsdXJlXHJcbiAgICAgICAgRmFpbHVyZTogZnVuY3Rpb24gKG1lc3NhZ2UsIGNhbGxiYWNrKSB7XHJcblxyXG4gICAgICAgICAgICAvLyBpZiBub3QgaW5pdGlhbGl6ZWQgcHJldGVuZCBsaWtlIGluaXRcclxuICAgICAgICAgICAgaWYgKCFuZXdOb3RpZnlTZXR0aW5ncykge1xyXG4gICAgICAgICAgICAgICAgTm90aWZsaXguTm90aWZ5LkluaXQoe30pO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBpZiAoIWNhbGxiYWNrKSB7XHJcbiAgICAgICAgICAgICAgICBjYWxsYmFjayA9IHVuZGVmaW5lZDtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgY29uc3QgdGhlVHlwZSA9IG5ld05vdGlmeVNldHRpbmdzLmZhaWx1cmU7XHJcbiAgICAgICAgICAgIE5vdGlmbGl4Tm90aWZ5KG1lc3NhZ2UsIGNhbGxiYWNrLCB0aGVUeXBlLCAnRmFpbHVyZScpO1xyXG5cclxuICAgICAgICB9LFxyXG5cclxuICAgICAgICAvLyBEaXNwbGF5IE5vdGlmaWNhdGlvbjogV2FybmluZ1xyXG4gICAgICAgIFdhcm5pbmc6IGZ1bmN0aW9uIChtZXNzYWdlLCBjYWxsYmFjaykge1xyXG5cclxuICAgICAgICAgICAgLy8gaWYgbm90IGluaXRpYWxpemVkIHByZXRlbmQgbGlrZSBpbml0XHJcbiAgICAgICAgICAgIGlmICghbmV3Tm90aWZ5U2V0dGluZ3MpIHtcclxuICAgICAgICAgICAgICAgIE5vdGlmbGl4Lk5vdGlmeS5Jbml0KHt9KTtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgaWYgKCFjYWxsYmFjaykge1xyXG4gICAgICAgICAgICAgICAgY2FsbGJhY2sgPSB1bmRlZmluZWQ7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGNvbnN0IHRoZVR5cGUgPSBuZXdOb3RpZnlTZXR0aW5ncy53YXJuaW5nO1xyXG4gICAgICAgICAgICBOb3RpZmxpeE5vdGlmeShtZXNzYWdlLCBjYWxsYmFjaywgdGhlVHlwZSwgJ1dhcm5pbmcnKTtcclxuXHJcbiAgICAgICAgfSxcclxuXHJcbiAgICAgICAgLy8gRGlzcGxheSBOb3RpZmljYXRpb246IEluZm9cclxuICAgICAgICBJbmZvOiBmdW5jdGlvbiAobWVzc2FnZSwgY2FsbGJhY2spIHtcclxuXHJcbiAgICAgICAgICAgIC8vIGlmIG5vdCBpbml0aWFsaXplZCBwcmV0ZW5kIGxpa2UgaW5pdFxyXG4gICAgICAgICAgICBpZiAoIW5ld05vdGlmeVNldHRpbmdzKSB7XHJcbiAgICAgICAgICAgICAgICBOb3RpZmxpeC5Ob3RpZnkuSW5pdCh7fSk7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmICghY2FsbGJhY2spIHtcclxuICAgICAgICAgICAgICAgIGNhbGxiYWNrID0gdW5kZWZpbmVkO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBjb25zdCB0aGVUeXBlID0gbmV3Tm90aWZ5U2V0dGluZ3MuaW5mbztcclxuICAgICAgICAgICAgTm90aWZsaXhOb3RpZnkobWVzc2FnZSwgY2FsbGJhY2ssIHRoZVR5cGUsICdJbmZvJyk7XHJcblxyXG4gICAgICAgIH0sXHJcblxyXG4gICAgfSxcclxuICAgIC8vIE5vdGlmeSBvZmZcclxuXHJcbiAgICAvLyBSZXBvcnQgb25cclxuICAgIFJlcG9ydDoge1xyXG5cclxuICAgICAgICAvLyBJbml0XHJcbiAgICAgICAgSW5pdDogZnVuY3Rpb24gKHVzZXJSZXBvcnRPcHQpIHtcclxuICAgICAgICAgICAgbmV3UmVwb3J0U2V0dGluZ3MgPSBleHRlbmROb3RpZmxpeCh0cnVlLCByZXBvcnRTZXR0aW5ncywgdXNlclJlcG9ydE9wdCk7XHJcblxyXG4gICAgICAgICAgICAvLyB1c2UgR29vZ2xlRm9udHMgaWYgXCJRdWlja3NhbmRcIiBvblxyXG4gICAgICAgICAgICBpZiAobmV3UmVwb3J0U2V0dGluZ3MudXNlR29vZ2xlRm9udCAmJiBuZXdSZXBvcnRTZXR0aW5ncy5mb250RmFtaWx5ID09PSAnUXVpY2tzYW5kJykge1xyXG4gICAgICAgICAgICAgICAgbm90aWZsaXhHb29nbGVGb250KCk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgLy8gdXNlIEdvb2dsZUZvbnRzIGlmIFwiUXVpY2tzYW5kXCIgb2ZmXHJcblxyXG4gICAgICAgICAgICAvLyBhZGQgY3NzIGNvZGVzIG9uXHJcbiAgICAgICAgICAgIG5vdGlmbGl4SW50ZXJuYWxDU1MoKTtcclxuICAgICAgICAgICAgLy8gYWRkIGNzcyBjb2RlcyBvZmZcclxuICAgICAgICB9LFxyXG5cclxuICAgICAgICAvLyBNZXJnZSBGaXJzdCBJbml0XHJcbiAgICAgICAgTWVyZ2U6IGZ1bmN0aW9uICh1c2VyUmVwb3J0RXh0ZW5kKSB7XHJcblxyXG4gICAgICAgICAgICBpZiAobmV3UmVwb3J0U2V0dGluZ3MpIHsgLy8gaWYgaW5pdGlhbGl6ZWQgYWxyZWFkeVxyXG4gICAgICAgICAgICAgICAgbmV3UmVwb3J0U2V0dGluZ3MgPSBleHRlbmROb3RpZmxpeCh0cnVlLCBuZXdSZXBvcnRTZXR0aW5ncywgdXNlclJlcG9ydEV4dGVuZCk7XHJcbiAgICAgICAgICAgIH0gZWxzZSB7IC8vIGVycm9yXHJcbiAgICAgICAgICAgICAgICBub3RpZmxpeENvbnNvbGVFcnJvcignTm90aWZsaXggRXJyb3InLCAnWW91IGhhdmUgdG8gaW5pdGlhbGl6ZSB0aGUgUmVwb3J0IG1vZHVsZSBiZWZvcmUgY2FsbCBNZXJnZSBmdW5jdGlvbi4nKTtcclxuICAgICAgICAgICAgICAgIHJldHVybjtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH0sXHJcblxyXG4gICAgICAgIC8vIERpc3BsYXkgUmVwb3J0OiBTdWNjZXNzXHJcbiAgICAgICAgU3VjY2VzczogZnVuY3Rpb24gKHRpdGxlLCBtZXNzYWdlLCBidXR0b25UZXh0LCBidXR0b25DYWxsYmFjaykge1xyXG5cclxuICAgICAgICAgICAgLy8gaWYgbm90IGluaXRpYWxpemVkIHByZXRlbmQgbGlrZSBpbml0XHJcbiAgICAgICAgICAgIGlmICghbmV3UmVwb3J0U2V0dGluZ3MpIHtcclxuICAgICAgICAgICAgICAgIE5vdGlmbGl4LlJlcG9ydC5Jbml0KHt9KTtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgaWYgKGFyZ3VtZW50cy5sZW5ndGggPiA0KSB7IC8vIE1vcmUgcGFyYW1ldGVycyB0aGFuIGFsbG93ZWRcclxuICAgICAgICAgICAgICAgIG5vdGlmbGl4Q29uc29sZUVycm9yKCdOb3RpZmxpeCBFcnJvcicsICdNb3JlIHBhcmFtZXRlcnMgdGhhbiBhbGxvd2VkLicpO1xyXG4gICAgICAgICAgICAgICAgcmV0dXJuO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBpZiAoYXJndW1lbnRzWzBdID09PSB1bmRlZmluZWQgfHwgYXJndW1lbnRzWzBdLmxlbmd0aCA8PSAwKSB7IC8vIHRpdGxlXHJcbiAgICAgICAgICAgICAgICBhcmd1bWVudHNbMF0gPSAnTm90aWZsaXggU3VjY2Vzcyc7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmIChhcmd1bWVudHNbMV0gPT09IHVuZGVmaW5lZCB8fCBhcmd1bWVudHNbMV0ubGVuZ3RoIDw9IDApIHsgLy8gbWVzc2FnZVxyXG4gICAgICAgICAgICAgICAgYXJndW1lbnRzWzFdID0gYFwiRG8gbm90IHRyeSB0byBiZWNvbWUgYSBwZXJzb24gb2Ygc3VjY2VzcyBidXQgdHJ5IHRvIGJlY29tZSBhIHBlcnNvbiBvZiB2YWx1ZS5cIiA8YnI+PGJyPi0gQWxiZXJ0IEVpbnN0ZWluYDtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgaWYgKGFyZ3VtZW50c1syXSA9PT0gdW5kZWZpbmVkIHx8IGFyZ3VtZW50c1syXS5sZW5ndGggPD0gMCkgeyAvLyBidXR0b25UZXh0XHJcbiAgICAgICAgICAgICAgICBhcmd1bWVudHNbMl0gPSAnT2theSc7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmIChhcmd1bWVudHNbM10gPT09IHVuZGVmaW5lZCkgeyAvLyBidXR0b25DYWxsYmFja1xyXG4gICAgICAgICAgICAgICAgYXJndW1lbnRzWzNdID0gdW5kZWZpbmVkO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBjb25zdCB0aGVUeXBlID0gbmV3UmVwb3J0U2V0dGluZ3Muc3VjY2VzcztcclxuICAgICAgICAgICAgTm90aWZsaXhSZXBvcnQoYXJndW1lbnRzWzBdLCBhcmd1bWVudHNbMV0sIGFyZ3VtZW50c1syXSwgYXJndW1lbnRzWzNdLCB0aGVUeXBlLCAnc3VjY2VzcycpO1xyXG4gICAgICAgIH0sXHJcblxyXG4gICAgICAgIC8vIERpc3BsYXkgUmVwb3J0OiBGYWlsdXJlXHJcbiAgICAgICAgRmFpbHVyZTogZnVuY3Rpb24gKHRpdGxlLCBtZXNzYWdlLCBidXR0b25UZXh0LCBidXR0b25DYWxsYmFjaykge1xyXG5cclxuICAgICAgICAgICAgLy8gaWYgbm90IGluaXRpYWxpemVkIHByZXRlbmQgbGlrZSBpbml0XHJcbiAgICAgICAgICAgIGlmICghbmV3UmVwb3J0U2V0dGluZ3MpIHtcclxuICAgICAgICAgICAgICAgIE5vdGlmbGl4LlJlcG9ydC5Jbml0KHt9KTtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgaWYgKGFyZ3VtZW50cy5sZW5ndGggPiA0KSB7IC8vIE1vcmUgcGFyYW1ldGVycyB0aGFuIGFsbG93ZWRcclxuICAgICAgICAgICAgICAgIG5vdGlmbGl4Q29uc29sZUVycm9yKCdOb3RpZmxpeCBFcnJvcicsICdNb3JlIHBhcmFtZXRlcnMgdGhhbiBhbGxvd2VkLicpO1xyXG4gICAgICAgICAgICAgICAgcmV0dXJuO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBpZiAoYXJndW1lbnRzWzBdID09PSB1bmRlZmluZWQgfHwgYXJndW1lbnRzWzBdLmxlbmd0aCA8PSAwKSB7IC8vIHRpdGxlXHJcbiAgICAgICAgICAgICAgICBhcmd1bWVudHNbMF0gPSAnTm90aWZsaXggRmFpbHVyZSc7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmIChhcmd1bWVudHNbMV0gPT09IHVuZGVmaW5lZCB8fCBhcmd1bWVudHNbMV0ubGVuZ3RoIDw9IDApIHsgLy8gbWVzc2FnZVxyXG4gICAgICAgICAgICAgICAgYXJndW1lbnRzWzFdID0gYFwiRmFpbHVyZSBpcyBzaW1wbHkgdGhlIG9wcG9ydHVuaXR5IHRvIGJlZ2luIGFnYWluLCB0aGlzIHRpbWUgbW9yZSBpbnRlbGxpZ2VudGx5LlwiIDxicj48YnI+LSBIZW5yeSBGb3JkYDtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgaWYgKGFyZ3VtZW50c1syXSA9PT0gdW5kZWZpbmVkIHx8IGFyZ3VtZW50c1syXS5sZW5ndGggPD0gMCkgeyAvLyBidXR0b25UZXh0XHJcbiAgICAgICAgICAgICAgICBhcmd1bWVudHNbMl0gPSAnT2theSc7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmIChhcmd1bWVudHNbM10gPT09IHVuZGVmaW5lZCkgeyAvLyBidXR0b25DYWxsYmFja1xyXG4gICAgICAgICAgICAgICAgYXJndW1lbnRzWzNdID0gdW5kZWZpbmVkO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBjb25zdCB0aGVUeXBlID0gbmV3UmVwb3J0U2V0dGluZ3MuZmFpbHVyZTtcclxuICAgICAgICAgICAgTm90aWZsaXhSZXBvcnQoYXJndW1lbnRzWzBdLCBhcmd1bWVudHNbMV0sIGFyZ3VtZW50c1syXSwgYXJndW1lbnRzWzNdLCB0aGVUeXBlLCAnZmFpbHVyZScpO1xyXG5cclxuICAgICAgICB9LFxyXG5cclxuICAgICAgICAvLyBEaXNwbGF5IFJlcG9ydDogV2FybmluZ1xyXG4gICAgICAgIFdhcm5pbmc6IGZ1bmN0aW9uICh0aXRsZSwgbWVzc2FnZSwgYnV0dG9uVGV4dCwgYnV0dG9uQ2FsbGJhY2spIHtcclxuXHJcbiAgICAgICAgICAgIC8vIGlmIG5vdCBpbml0aWFsaXplZCBwcmV0ZW5kIGxpa2UgaW5pdFxyXG4gICAgICAgICAgICBpZiAoIW5ld1JlcG9ydFNldHRpbmdzKSB7XHJcbiAgICAgICAgICAgICAgICBOb3RpZmxpeC5SZXBvcnQuSW5pdCh7fSk7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmIChhcmd1bWVudHMubGVuZ3RoID4gNCkgeyAvLyBNb3JlIHBhcmFtZXRlcnMgdGhhbiBhbGxvd2VkXHJcbiAgICAgICAgICAgICAgICBub3RpZmxpeENvbnNvbGVFcnJvcignTm90aWZsaXggRXJyb3InLCAnTW9yZSBwYXJhbWV0ZXJzIHRoYW4gYWxsb3dlZC4nKTtcclxuICAgICAgICAgICAgICAgIHJldHVybjtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgaWYgKGFyZ3VtZW50c1swXSA9PT0gdW5kZWZpbmVkIHx8IGFyZ3VtZW50c1swXS5sZW5ndGggPD0gMCkgeyAvLyB0aXRsZVxyXG4gICAgICAgICAgICAgICAgYXJndW1lbnRzWzBdID0gJ05vdGlmbGl4IFdhcm5pbmcnO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBpZiAoYXJndW1lbnRzWzFdID09PSB1bmRlZmluZWQgfHwgYXJndW1lbnRzWzFdLmxlbmd0aCA8PSAwKSB7IC8vIG1lc3NhZ2VcclxuICAgICAgICAgICAgICAgIGFyZ3VtZW50c1sxXSA9IGBcIlRoZSBwZW9wbGVzIHdobyB3YW50IHRvIGxpdmUgY29tZm9ydGFibHkgd2l0aG91dCBwcm9kdWNpbmcgYW5kIGZhdGlndWU7IHRoZXkgYXJlIGRvb21lZCB0byBsb3NlIHRoZWlyIGRpZ25pdHksIHRoZW4gbGliZXJ0eSwgYW5kIHRoZW4gaW5kZXBlbmRlbmNlIGFuZCBkZXN0aW55LlwiIDxicj48YnI+LSBNdXN0YWZhIEtlbWFsIEF0YXR1cmtgO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBpZiAoYXJndW1lbnRzWzJdID09PSB1bmRlZmluZWQgfHwgYXJndW1lbnRzWzJdLmxlbmd0aCA8PSAwKSB7IC8vIGJ1dHRvblRleHRcclxuICAgICAgICAgICAgICAgIGFyZ3VtZW50c1syXSA9ICdPa2F5JztcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgaWYgKGFyZ3VtZW50c1szXSA9PT0gdW5kZWZpbmVkKSB7IC8vIGJ1dHRvbkNhbGxiYWNrXHJcbiAgICAgICAgICAgICAgICBhcmd1bWVudHNbM10gPSB1bmRlZmluZWQ7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGNvbnN0IHRoZVR5cGUgPSBuZXdSZXBvcnRTZXR0aW5ncy53YXJuaW5nO1xyXG4gICAgICAgICAgICBOb3RpZmxpeFJlcG9ydChhcmd1bWVudHNbMF0sIGFyZ3VtZW50c1sxXSwgYXJndW1lbnRzWzJdLCBhcmd1bWVudHNbM10sIHRoZVR5cGUsICd3YXJuaW5nJyk7XHJcblxyXG4gICAgICAgIH0sXHJcblxyXG4gICAgICAgIC8vIERpc3BsYXkgUmVwb3J0OiBJbmZvXHJcbiAgICAgICAgSW5mbzogZnVuY3Rpb24gKHRpdGxlLCBtZXNzYWdlLCBidXR0b25UZXh0LCBidXR0b25DYWxsYmFjaykge1xyXG5cclxuICAgICAgICAgICAgLy8gaWYgbm90IGluaXRpYWxpemVkIHByZXRlbmQgbGlrZSBpbml0XHJcbiAgICAgICAgICAgIGlmICghbmV3UmVwb3J0U2V0dGluZ3MpIHtcclxuICAgICAgICAgICAgICAgIE5vdGlmbGl4LlJlcG9ydC5Jbml0KHt9KTtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgaWYgKGFyZ3VtZW50cy5sZW5ndGggPiA0KSB7IC8vIE1vcmUgcGFyYW1ldGVycyB0aGFuIGFsbG93ZWRcclxuICAgICAgICAgICAgICAgIG5vdGlmbGl4Q29uc29sZUVycm9yKCdOb3RpZmxpeCBFcnJvcicsICdNb3JlIHBhcmFtZXRlcnMgdGhhbiBhbGxvd2VkLicpO1xyXG4gICAgICAgICAgICAgICAgcmV0dXJuO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBpZiAoYXJndW1lbnRzWzBdID09PSB1bmRlZmluZWQgfHwgYXJndW1lbnRzWzBdLmxlbmd0aCA8PSAwKSB7IC8vIHRpdGxlXHJcbiAgICAgICAgICAgICAgICBhcmd1bWVudHNbMF0gPSAnTm90aWZsaXggSW5mbyc7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmIChhcmd1bWVudHNbMV0gPT09IHVuZGVmaW5lZCB8fCBhcmd1bWVudHNbMV0ubGVuZ3RoIDw9IDApIHsgLy8gbWVzc2FnZVxyXG4gICAgICAgICAgICAgICAgYXJndW1lbnRzWzFdID0gYFwiS25vd2xlZGdlIHJlc3RzIG5vdCB1cG9uIHRydXRoIGFsb25lLCBidXQgdXBvbiBlcnJvciBhbHNvLlwiIDxicj48YnI+LSBDYXJsIEd1c3RhdiBKdW5nYDtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgaWYgKGFyZ3VtZW50c1syXSA9PT0gdW5kZWZpbmVkIHx8IGFyZ3VtZW50c1syXS5sZW5ndGggPD0gMCkgeyAvLyBidXR0b25UZXh0XHJcbiAgICAgICAgICAgICAgICBhcmd1bWVudHNbMl0gPSAnT2theSc7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmIChhcmd1bWVudHNbM10gPT09IHVuZGVmaW5lZCkgeyAvLyBidXR0b25DYWxsYmFja1xyXG4gICAgICAgICAgICAgICAgYXJndW1lbnRzWzNdID0gdW5kZWZpbmVkO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBjb25zdCB0aGVUeXBlID0gbmV3UmVwb3J0U2V0dGluZ3MuaW5mbztcclxuICAgICAgICAgICAgTm90aWZsaXhSZXBvcnQoYXJndW1lbnRzWzBdLCBhcmd1bWVudHNbMV0sIGFyZ3VtZW50c1syXSwgYXJndW1lbnRzWzNdLCB0aGVUeXBlLCAnaW5mbycpO1xyXG4gICAgICAgIH0sXHJcblxyXG4gICAgfSxcclxuICAgIC8vIFJlcG9ydCBvZmZcclxuXHJcbiAgICAvLyBDb25maXJtIG9uXHJcbiAgICBDb25maXJtOiB7XHJcblxyXG4gICAgICAgIC8vIEluaXRcclxuICAgICAgICBJbml0OiBmdW5jdGlvbiAodXNlckNvbmZpcm1PcHQpIHtcclxuICAgICAgICAgICAgbmV3Q29uZmlybVNldHRpbmdzID0gZXh0ZW5kTm90aWZsaXgodHJ1ZSwgY29uZmlybVNldHRpbmdzLCB1c2VyQ29uZmlybU9wdCk7XHJcblxyXG4gICAgICAgICAgICAvLyB1c2UgR29vZ2xlRm9udHMgaWYgXCJRdWlja3NhbmRcIiBvblxyXG4gICAgICAgICAgICBpZiAobmV3Q29uZmlybVNldHRpbmdzLnVzZUdvb2dsZUZvbnQgJiYgbmV3Q29uZmlybVNldHRpbmdzLmZvbnRGYW1pbHkgPT09ICdRdWlja3NhbmQnKSB7XHJcbiAgICAgICAgICAgICAgICBub3RpZmxpeEdvb2dsZUZvbnQoKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAvLyB1c2UgR29vZ2xlRm9udHMgaWYgXCJRdWlja3NhbmRcIiBvZmZcclxuXHJcbiAgICAgICAgICAgIC8vIGFkZCBjc3MgY29kZXMgb25cclxuICAgICAgICAgICAgbm90aWZsaXhJbnRlcm5hbENTUygpO1xyXG4gICAgICAgICAgICAvLyBhZGQgY3NzIGNvZGVzIG9mZlxyXG4gICAgICAgIH0sXHJcblxyXG4gICAgICAgIC8vIE1lcmdlIEZpcnN0IEluaXRcclxuICAgICAgICBNZXJnZTogZnVuY3Rpb24gKHVzZXJDb25maXJtRXh0ZW5kKSB7XHJcblxyXG4gICAgICAgICAgICBpZiAobmV3Q29uZmlybVNldHRpbmdzKSB7IC8vIGlmIGluaXRpYWxpemVkIGFscmVhZHlcclxuICAgICAgICAgICAgICAgIG5ld0NvbmZpcm1TZXR0aW5ncyA9IGV4dGVuZE5vdGlmbGl4KHRydWUsIG5ld0NvbmZpcm1TZXR0aW5ncywgdXNlckNvbmZpcm1FeHRlbmQpO1xyXG4gICAgICAgICAgICB9IGVsc2UgeyAvLyBlcnJvclxyXG4gICAgICAgICAgICAgICAgbm90aWZsaXhDb25zb2xlRXJyb3IoJ05vdGlmbGl4IEVycm9yJywgJ1lvdSBoYXZlIHRvIGluaXRpYWxpemUgdGhlIENvbmZpcm0gbW9kdWxlIGJlZm9yZSBjYWxsIE1lcmdlIGZ1bmN0aW9uLicpO1xyXG4gICAgICAgICAgICAgICAgcmV0dXJuO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfSxcclxuXHJcbiAgICAgICAgLy8gRGlzcGxheSBDb25maXJtOiBTaG93XHJcbiAgICAgICAgU2hvdzogZnVuY3Rpb24gKHRpdGxlLCBtZXNzYWdlLCBva1RleHQsIGNhbmNlbFRleHQsIG9rQ2FsbGJhY2ssIGNhbmNlbENhbGxiYWNrKSB7XHJcblxyXG4gICAgICAgICAgICAvLyBpZiBub3QgaW5pdGlhbGl6ZWQgcHJldGVuZCBsaWtlIGluaXRcclxuICAgICAgICAgICAgaWYgKCFuZXdDb25maXJtU2V0dGluZ3MpIHtcclxuICAgICAgICAgICAgICAgIE5vdGlmbGl4LkNvbmZpcm0uSW5pdCh7fSk7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmIChhcmd1bWVudHMubGVuZ3RoID4gNikgeyAvLyBNb3JlIHBhcmFtZXRlcnMgdGhhbiBhbGxvd2VkXHJcbiAgICAgICAgICAgICAgICBub3RpZmxpeENvbnNvbGVFcnJvcignTm90aWZsaXggRXJyb3InLCAnTW9yZSBwYXJhbWV0ZXJzIHRoYW4gYWxsb3dlZC4nKTtcclxuICAgICAgICAgICAgICAgIHJldHVybjtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgaWYgKGFyZ3VtZW50c1swXSA9PT0gdW5kZWZpbmVkIHx8IGFyZ3VtZW50c1swXS5sZW5ndGggPD0gMCkgeyAvLyB0aXRsZVxyXG4gICAgICAgICAgICAgICAgYXJndW1lbnRzWzBdID0gJ05vdGlmbGl4IENvbmZpcm0nO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBpZiAoYXJndW1lbnRzWzFdID09PSB1bmRlZmluZWQgfHwgYXJndW1lbnRzWzFdLmxlbmd0aCA8PSAwKSB7IC8vIG1lc3NhZ2VcclxuICAgICAgICAgICAgICAgIGFyZ3VtZW50c1sxXSA9ICdEbyB5b3UgYWdyZWUgd2l0aCBtZT8nO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBpZiAoYXJndW1lbnRzWzJdID09PSB1bmRlZmluZWQgfHwgYXJndW1lbnRzWzJdLmxlbmd0aCA8PSAwKSB7IC8vIG9rIGJ1dHRvblRleHRcclxuICAgICAgICAgICAgICAgIGFyZ3VtZW50c1syXSA9ICdZZXMnO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBpZiAoYXJndW1lbnRzWzNdID09PSB1bmRlZmluZWQgfHwgYXJndW1lbnRzWzNdLmxlbmd0aCA8PSAwKSB7IC8vIGNhbmNlbCBidXR0b25UZXh0XHJcbiAgICAgICAgICAgICAgICBhcmd1bWVudHNbM10gPSAnTm8nO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBpZiAoYXJndW1lbnRzWzRdID09PSB1bmRlZmluZWQpIHsgLy8gb2tDYWxsYmFja1xyXG4gICAgICAgICAgICAgICAgYXJndW1lbnRzWzRdID0gdW5kZWZpbmVkO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBpZiAoYXJndW1lbnRzWzVdID09PSB1bmRlZmluZWQpIHsgLy8gY2FuY2VsQ2FsbGJhY2tcclxuICAgICAgICAgICAgICAgIGFyZ3VtZW50c1s1XSA9IHVuZGVmaW5lZDtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgTm90aWZsaXhDb25maXJtKGFyZ3VtZW50c1swXSwgYXJndW1lbnRzWzFdLCBhcmd1bWVudHNbMl0sIGFyZ3VtZW50c1szXSwgYXJndW1lbnRzWzRdLCBhcmd1bWVudHNbNV0pO1xyXG4gICAgICAgIH0sXHJcbiAgICB9LFxyXG4gICAgLy8gQ29uZmlybSBvZmZcclxuXHJcbiAgICAvLyBMb2FkaW5nIG9uXHJcbiAgICBMb2FkaW5nOiB7XHJcblxyXG4gICAgICAgIC8vIEluaXRcclxuICAgICAgICBJbml0OiBmdW5jdGlvbiAodXNlckxvYWRpbmdPcHQpIHtcclxuICAgICAgICAgICAgbmV3TG9hZGluZ1NldHRpbmdzID0gZXh0ZW5kTm90aWZsaXgodHJ1ZSwgbG9hZGluZ1NldHRpbmdzLCB1c2VyTG9hZGluZ09wdCk7XHJcblxyXG4gICAgICAgICAgICAvLyB1c2UgR29vZ2xlRm9udHMgaWYgXCJRdWlja3NhbmRcIiBvblxyXG4gICAgICAgICAgICBpZiAobmV3TG9hZGluZ1NldHRpbmdzLnVzZUdvb2dsZUZvbnQgJiYgbmV3TG9hZGluZ1NldHRpbmdzLmZvbnRGYW1pbHkgPT09ICdRdWlja3NhbmQnKSB7XHJcbiAgICAgICAgICAgICAgICBub3RpZmxpeEdvb2dsZUZvbnQoKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAvLyB1c2UgR29vZ2xlRm9udHMgaWYgXCJRdWlja3NhbmRcIiBvZmZcclxuXHJcbiAgICAgICAgICAgIC8vIGFkZCBjc3MgY29kZXMgb25cclxuICAgICAgICAgICAgbm90aWZsaXhJbnRlcm5hbENTUygpO1xyXG4gICAgICAgICAgICAvLyBhZGQgY3NzIGNvZGVzIG9mZlxyXG4gICAgICAgIH0sXHJcblxyXG4gICAgICAgIC8vIE1lcmdlIEZpcnN0IEluaXRcclxuICAgICAgICBNZXJnZTogZnVuY3Rpb24gKHVzZXJMb2FkaW5nRXh0ZW5kKSB7XHJcblxyXG4gICAgICAgICAgICBpZiAobmV3TG9hZGluZ1NldHRpbmdzKSB7IC8vIGlmIGluaXRpYWxpemVkIGFscmVhZHlcclxuICAgICAgICAgICAgICAgIG5ld0xvYWRpbmdTZXR0aW5ncyA9IGV4dGVuZE5vdGlmbGl4KHRydWUsIG5ld0xvYWRpbmdTZXR0aW5ncywgdXNlckxvYWRpbmdFeHRlbmQpO1xyXG4gICAgICAgICAgICB9IGVsc2UgeyAvLyBlcnJvclxyXG4gICAgICAgICAgICAgICAgbm90aWZsaXhDb25zb2xlRXJyb3IoJ05vdGlmbGl4IEVycm9yJywgJ1lvdSBoYXZlIHRvIGluaXRpYWxpemUgdGhlIExvYWRpbmcgbW9kdWxlIGJlZm9yZSBjYWxsIE1lcmdlIGZ1bmN0aW9uLicpO1xyXG4gICAgICAgICAgICAgICAgcmV0dXJuO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfSxcclxuXHJcbiAgICAgICAgLy8gRGlzcGxheSBMb2FkaW5nOiBTdGFuZGFyZFxyXG4gICAgICAgIFN0YW5kYXJkOiBmdW5jdGlvbiAobWVzc2FnZSkge1xyXG5cclxuICAgICAgICAgICAgLy8gaWYgbm90IGluaXRpYWxpemVkIHByZXRlbmQgbGlrZSBpbml0XHJcbiAgICAgICAgICAgIGlmICghbmV3TG9hZGluZ1NldHRpbmdzKSB7XHJcbiAgICAgICAgICAgICAgICBOb3RpZmxpeC5Mb2FkaW5nLkluaXQoe30pO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBpZiAoYXJndW1lbnRzLmxlbmd0aCA+IDEpIHsgLy8gTW9yZSBwYXJhbWV0ZXJzIHRoYW4gYWxsb3dlZFxyXG4gICAgICAgICAgICAgICAgbm90aWZsaXhDb25zb2xlRXJyb3IoJ05vdGlmbGl4IEVycm9yJywgJ01vcmUgcGFyYW1ldGVycyB0aGFuIGFsbG93ZWQuJyk7XHJcbiAgICAgICAgICAgICAgICByZXR1cm47XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmICghbWVzc2FnZSkge1xyXG4gICAgICAgICAgICAgICAgbWVzc2FnZSA9ICcnO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBOb3RpZmxpeExvYWRpbmcobWVzc2FnZSwgJ3N0YW5kYXJkJywgdHJ1ZSwgMCk7IC8vIHRydWUgPSBkaXNwbGF5XHJcblxyXG4gICAgICAgIH0sXHJcblxyXG4gICAgICAgIC8vIERpc3BsYXkgTG9hZGluZzogSG91cmdsYXNzXHJcbiAgICAgICAgSG91cmdsYXNzOiBmdW5jdGlvbiAobWVzc2FnZSkge1xyXG5cclxuICAgICAgICAgICAgLy8gaWYgbm90IGluaXRpYWxpemVkIHByZXRlbmQgbGlrZSBpbml0XHJcbiAgICAgICAgICAgIGlmICghbmV3TG9hZGluZ1NldHRpbmdzKSB7XHJcbiAgICAgICAgICAgICAgICBOb3RpZmxpeC5Mb2FkaW5nLkluaXQoe30pO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBpZiAoYXJndW1lbnRzLmxlbmd0aCA+IDEpIHsgLy8gTW9yZSBwYXJhbWV0ZXJzIHRoYW4gYWxsb3dlZFxyXG4gICAgICAgICAgICAgICAgbm90aWZsaXhDb25zb2xlRXJyb3IoJ05vdGlmbGl4IEVycm9yJywgJ01vcmUgcGFyYW1ldGVycyB0aGFuIGFsbG93ZWQuJyk7XHJcbiAgICAgICAgICAgICAgICByZXR1cm47XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmICghbWVzc2FnZSkge1xyXG4gICAgICAgICAgICAgICAgbWVzc2FnZSA9ICcnO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBOb3RpZmxpeExvYWRpbmcobWVzc2FnZSwgJ2hvdXJnbGFzcycsIHRydWUsIDApOyAvLyB0cnVlID0gZGlzcGxheVxyXG5cclxuICAgICAgICB9LFxyXG5cclxuICAgICAgICAvLyBEaXNwbGF5IExvYWRpbmc6IENpcmNsZVxyXG4gICAgICAgIENpcmNsZTogZnVuY3Rpb24gKG1lc3NhZ2UpIHtcclxuXHJcbiAgICAgICAgICAgIC8vIGlmIG5vdCBpbml0aWFsaXplZCBwcmV0ZW5kIGxpa2UgaW5pdFxyXG4gICAgICAgICAgICBpZiAoIW5ld0xvYWRpbmdTZXR0aW5ncykge1xyXG4gICAgICAgICAgICAgICAgTm90aWZsaXguTG9hZGluZy5Jbml0KHt9KTtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgaWYgKGFyZ3VtZW50cy5sZW5ndGggPiAxKSB7IC8vIE1vcmUgcGFyYW1ldGVycyB0aGFuIGFsbG93ZWRcclxuICAgICAgICAgICAgICAgIG5vdGlmbGl4Q29uc29sZUVycm9yKCdOb3RpZmxpeCBFcnJvcicsICdNb3JlIHBhcmFtZXRlcnMgdGhhbiBhbGxvd2VkLicpO1xyXG4gICAgICAgICAgICAgICAgcmV0dXJuO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBpZiAoIW1lc3NhZ2UpIHtcclxuICAgICAgICAgICAgICAgIG1lc3NhZ2UgPSAnJztcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgTm90aWZsaXhMb2FkaW5nKG1lc3NhZ2UsICdjaXJjbGUnLCB0cnVlLCAwKTsgLy8gdHJ1ZSA9IGRpc3BsYXlcclxuXHJcbiAgICAgICAgfSxcclxuXHJcbiAgICAgICAgLy8gRGlzcGxheSBMb2FkaW5nOiBBcnJvd3NcclxuICAgICAgICBBcnJvd3M6IGZ1bmN0aW9uIChtZXNzYWdlKSB7XHJcblxyXG4gICAgICAgICAgICAvLyBpZiBub3QgaW5pdGlhbGl6ZWQgcHJldGVuZCBsaWtlIGluaXRcclxuICAgICAgICAgICAgaWYgKCFuZXdMb2FkaW5nU2V0dGluZ3MpIHtcclxuICAgICAgICAgICAgICAgIE5vdGlmbGl4LkxvYWRpbmcuSW5pdCh7fSk7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmIChhcmd1bWVudHMubGVuZ3RoID4gMSkgeyAvLyBNb3JlIHBhcmFtZXRlcnMgdGhhbiBhbGxvd2VkXHJcbiAgICAgICAgICAgICAgICBub3RpZmxpeENvbnNvbGVFcnJvcignTm90aWZsaXggRXJyb3InLCAnTW9yZSBwYXJhbWV0ZXJzIHRoYW4gYWxsb3dlZC4nKTtcclxuICAgICAgICAgICAgICAgIHJldHVybjtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgaWYgKCFtZXNzYWdlKSB7XHJcbiAgICAgICAgICAgICAgICBtZXNzYWdlID0gJyc7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIE5vdGlmbGl4TG9hZGluZyhtZXNzYWdlLCAnYXJyb3dzJywgdHJ1ZSwgMCk7IC8vIHRydWUgPSBkaXNwbGF5XHJcblxyXG4gICAgICAgIH0sXHJcblxyXG4gICAgICAgIC8vIERpc3BsYXkgTG9hZGluZzogRG90c1xyXG4gICAgICAgIERvdHM6IGZ1bmN0aW9uIChtZXNzYWdlKSB7XHJcblxyXG4gICAgICAgICAgICAvLyBpZiBub3QgaW5pdGlhbGl6ZWQgcHJldGVuZCBsaWtlIGluaXRcclxuICAgICAgICAgICAgaWYgKCFuZXdMb2FkaW5nU2V0dGluZ3MpIHtcclxuICAgICAgICAgICAgICAgIE5vdGlmbGl4LkxvYWRpbmcuSW5pdCh7fSk7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmIChhcmd1bWVudHMubGVuZ3RoID4gMSkgeyAvLyBNb3JlIHBhcmFtZXRlcnMgdGhhbiBhbGxvd2VkXHJcbiAgICAgICAgICAgICAgICBub3RpZmxpeENvbnNvbGVFcnJvcignTm90aWZsaXggRXJyb3InLCAnTW9yZSBwYXJhbWV0ZXJzIHRoYW4gYWxsb3dlZC4nKTtcclxuICAgICAgICAgICAgICAgIHJldHVybjtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgaWYgKCFtZXNzYWdlKSB7XHJcbiAgICAgICAgICAgICAgICBtZXNzYWdlID0gJyc7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIE5vdGlmbGl4TG9hZGluZyhtZXNzYWdlLCAnZG90cycsIHRydWUsIDApOyAvLyB0cnVlID0gZGlzcGxheVxyXG5cclxuICAgICAgICB9LFxyXG5cclxuICAgICAgICAvLyBEaXNwbGF5IExvYWRpbmc6IFB1bHNlXHJcbiAgICAgICAgUHVsc2U6IGZ1bmN0aW9uIChtZXNzYWdlKSB7XHJcblxyXG4gICAgICAgICAgICAvLyBpZiBub3QgaW5pdGlhbGl6ZWQgcHJldGVuZCBsaWtlIGluaXRcclxuICAgICAgICAgICAgaWYgKCFuZXdMb2FkaW5nU2V0dGluZ3MpIHtcclxuICAgICAgICAgICAgICAgIE5vdGlmbGl4LkxvYWRpbmcuSW5pdCh7fSk7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmIChhcmd1bWVudHMubGVuZ3RoID4gMSkgeyAvLyBNb3JlIHBhcmFtZXRlcnMgdGhhbiBhbGxvd2VkXHJcbiAgICAgICAgICAgICAgICBub3RpZmxpeENvbnNvbGVFcnJvcignTm90aWZsaXggRXJyb3InLCAnTW9yZSBwYXJhbWV0ZXJzIHRoYW4gYWxsb3dlZC4nKTtcclxuICAgICAgICAgICAgICAgIHJldHVybjtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgaWYgKCFtZXNzYWdlKSB7XHJcbiAgICAgICAgICAgICAgICBtZXNzYWdlID0gJyc7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIE5vdGlmbGl4TG9hZGluZyhtZXNzYWdlLCAncHVsc2UnLCB0cnVlLCAwKTsgLy8gdHJ1ZSA9IGRpc3BsYXlcclxuXHJcbiAgICAgICAgfSxcclxuXHJcbiAgICAgICAgLy8gRGlzcGxheSBMb2FkaW5nOiBDdXN0b21cclxuICAgICAgICBDdXN0b206IGZ1bmN0aW9uIChtZXNzYWdlKSB7XHJcblxyXG4gICAgICAgICAgICAvLyBpZiBub3QgaW5pdGlhbGl6ZWQgcHJldGVuZCBsaWtlIGluaXRcclxuICAgICAgICAgICAgaWYgKCFuZXdMb2FkaW5nU2V0dGluZ3MpIHtcclxuICAgICAgICAgICAgICAgIE5vdGlmbGl4LkxvYWRpbmcuSW5pdCh7fSk7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmIChhcmd1bWVudHMubGVuZ3RoID4gMSkgeyAvLyBNb3JlIHBhcmFtZXRlcnMgdGhhbiBhbGxvd2VkXHJcbiAgICAgICAgICAgICAgICBub3RpZmxpeENvbnNvbGVFcnJvcignTm90aWZsaXggRXJyb3InLCAnTW9yZSBwYXJhbWV0ZXJzIHRoYW4gYWxsb3dlZC4nKTtcclxuICAgICAgICAgICAgICAgIHJldHVybjtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgaWYgKCFtZXNzYWdlKSB7XHJcbiAgICAgICAgICAgICAgICBtZXNzYWdlID0gJyc7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIE5vdGlmbGl4TG9hZGluZyhtZXNzYWdlLCAnY3VzdG9tJywgdHJ1ZSwgMCk7IC8vIHRydWUgPSBkaXNwbGF5XHJcblxyXG4gICAgICAgIH0sXHJcblxyXG4gICAgICAgIC8vIERpc3BsYXkgTG9hZGluZzogTm90aWZsaXhcclxuICAgICAgICBOb3RpZmxpeDogZnVuY3Rpb24gKG1lc3NhZ2UpIHtcclxuXHJcbiAgICAgICAgICAgIC8vIGlmIG5vdCBpbml0aWFsaXplZCBwcmV0ZW5kIGxpa2UgaW5pdFxyXG4gICAgICAgICAgICBpZiAoIW5ld0xvYWRpbmdTZXR0aW5ncykge1xyXG4gICAgICAgICAgICAgICAgTm90aWZsaXguTG9hZGluZy5Jbml0KHt9KTtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgaWYgKGFyZ3VtZW50cy5sZW5ndGggPiAxKSB7IC8vIE1vcmUgcGFyYW1ldGVycyB0aGFuIGFsbG93ZWRcclxuICAgICAgICAgICAgICAgIG5vdGlmbGl4Q29uc29sZUVycm9yKCdOb3RpZmxpeCBFcnJvcicsICdNb3JlIHBhcmFtZXRlcnMgdGhhbiBhbGxvd2VkLicpO1xyXG4gICAgICAgICAgICAgICAgcmV0dXJuO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBpZiAoIW1lc3NhZ2UpIHtcclxuICAgICAgICAgICAgICAgIG1lc3NhZ2UgPSAnJztcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgTm90aWZsaXhMb2FkaW5nKG1lc3NhZ2UsICdub3RpZmxpeCcsIHRydWUsIDApOyAvLyB0cnVlID0gZGlzcGxheVxyXG5cclxuICAgICAgICB9LFxyXG5cclxuICAgICAgICAvLyBSZW1vdmUgTG9hZGluZ1xyXG4gICAgICAgIFJlbW92ZTogZnVuY3Rpb24gKHRoZURlbGF5KSB7XHJcblxyXG4gICAgICAgICAgICAvLyBpZiBub3QgaW5pdGlhbGl6ZWQgcHJldGVuZCBsaWtlIGluaXRcclxuICAgICAgICAgICAgaWYgKCFuZXdMb2FkaW5nU2V0dGluZ3MpIHtcclxuICAgICAgICAgICAgICAgIE5vdGlmbGl4LkxvYWRpbmcuSW5pdCh7fSk7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmIChhcmd1bWVudHMubGVuZ3RoID4gMSkgeyAvLyBNb3JlIHBhcmFtZXRlcnMgdGhhbiBhbGxvd2VkXHJcbiAgICAgICAgICAgICAgICBub3RpZmxpeENvbnNvbGVFcnJvcignTm90aWZsaXggRXJyb3InLCAnTW9yZSBwYXJhbWV0ZXJzIHRoYW4gYWxsb3dlZC4nKTtcclxuICAgICAgICAgICAgICAgIHJldHVybjtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgaWYgKCF0aGVEZWxheSkge1xyXG4gICAgICAgICAgICAgICAgdGhlRGVsYXkgPSAwO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBOb3RpZmxpeExvYWRpbmcoZmFsc2UsIGZhbHNlLCBmYWxzZSwgdGhlRGVsYXkpOyAvLyBmYWxzZSA9IFJlbW92ZVxyXG4gICAgICAgIH0sXHJcblxyXG4gICAgICAgIC8vIENoYW5nZSBUaGUgTWVzc2FnZVxyXG4gICAgICAgIENoYW5nZTogZnVuY3Rpb24gKG5ld01lc3NhZ2UpIHtcclxuXHJcbiAgICAgICAgICAgIC8vIGlmIG5vdCBpbml0aWFsaXplZCBwcmV0ZW5kIGxpa2UgaW5pdFxyXG4gICAgICAgICAgICBpZiAoIW5ld0xvYWRpbmdTZXR0aW5ncykge1xyXG4gICAgICAgICAgICAgICAgTm90aWZsaXguTG9hZGluZy5Jbml0KHt9KTtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgaWYgKGFyZ3VtZW50cy5sZW5ndGggPiAxKSB7IC8vIE1vcmUgcGFyYW1ldGVycyB0aGFuIGFsbG93ZWRcclxuICAgICAgICAgICAgICAgIG5vdGlmbGl4Q29uc29sZUVycm9yKCdOb3RpZmxpeCBFcnJvcicsICdNb3JlIHBhcmFtZXRlcnMgdGhhbiBhbGxvd2VkLicpO1xyXG4gICAgICAgICAgICAgICAgcmV0dXJuO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBpZiAoIW5ld01lc3NhZ2UpIHtcclxuICAgICAgICAgICAgICAgIG5ld01lc3NhZ2UgPSAnJztcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgTm90aWZsaXhMb2FkaW5nQ2hhbmdlKG5ld01lc3NhZ2UpO1xyXG4gICAgICAgIH0sXHJcblxyXG4gICAgfSxcclxuICAgIC8vIExvYWRpbmcgb2ZmXHJcblxyXG59XHJcbmV4cG9ydCBkZWZhdWx0IE5vdGlmbGl4O1xyXG4vLyBOb3RpZmxpeDogTlggUmVhY3Qgb2ZmXHJcblxyXG5cclxuLy8gTm90aWZsaXg6IE5vdGlmeSBTaW5nbGUgb25cclxubGV0IG5vdGlmbGl4Tm90aWZ5Q291bnQgPSAwO1xyXG5jb25zdCBOb3RpZmxpeE5vdGlmeSA9IGZ1bmN0aW9uIChtZXNzYWdlLCBjYWxsYmFjaywgdGhlVHlwZSwgc3RhdGljVHlwZSkge1xyXG5cclxuICAgIGlmIChhcmd1bWVudHMgIT09IHVuZGVmaW5lZCAmJiBhcmd1bWVudHMubGVuZ3RoID09PSA0KSB7XHJcblxyXG4gICAgICAgIC8vIG5vIG1lc3NhZ2Ugb25cclxuICAgICAgICBpZiAoIW1lc3NhZ2UpIHtcclxuICAgICAgICAgICAgbWVzc2FnZSA9IGBOb3RpZmxpeCAke3N0YXRpY1R5cGV9YDtcclxuICAgICAgICB9XHJcbiAgICAgICAgLy8gbm8gbWVzc2FnZSBvZmZcclxuXHJcbiAgICAgICAgLy8gRm9udEF3ZXNvbWUgaWYgU2hhZG93IG9uXHJcbiAgICAgICAgaWYgKG5ld05vdGlmeVNldHRpbmdzLmZvbnRBd2Vzb21lSWNvblN0eWxlID09PSAnc2hhZG93Jykge1xyXG4gICAgICAgICAgICB0aGVUeXBlLmZvbnRBd2Vzb21lSWNvbkNvbG9yID0gdGhlVHlwZS5iYWNrZ3JvdW5kO1xyXG4gICAgICAgIH1cclxuICAgICAgICAvLyBGb250QXdlc29tZSBpZiBTaGFkb3cgb2ZmXHJcblxyXG4gICAgICAgIC8vIGlmIHBsYWluVGV4dCB0cnVlID0gSFRNTCB0YWdzIG5vdCBhbGxvd2VkIG9uICAgICAgXHJcbiAgICAgICAgaWYgKG5ld05vdGlmeVNldHRpbmdzLnBsYWluVGV4dCkge1xyXG4gICAgICAgICAgICBtZXNzYWdlID0gbm90aWZsaXhQbGFpbnRleHQobWVzc2FnZSk7IC8vIG1lc3NhZ2UgcGxhaW4gdGV4dFxyXG4gICAgICAgIH1cclxuICAgICAgICAvLyBpZiBwbGFpblRleHQgdHJ1ZSA9IEhUTUwgdGFncyBub3QgYWxsb3dlZCBvZmZcclxuXHJcbiAgICAgICAgLy8gaWYgcGxhaW5UZXh0IGZhbHNlIGJ1dCB0aGUgbWVzc2FnZSBsZW5ndGggbW9yZSB0aGFuIG1lc3NhZ2VNYXhMZW5ndGggPSBIVE1MIHRhZ3MgZXJyb3Igb25cclxuICAgICAgICBpZiAoIW5ld05vdGlmeVNldHRpbmdzLnBsYWluVGV4dCAmJiBtZXNzYWdlLmxlbmd0aCA+IG5ld05vdGlmeVNldHRpbmdzLm1lc3NhZ2VNYXhMZW5ndGgpIHtcclxuICAgICAgICAgICAgTm90aWZsaXguTm90aWZ5Lk1lcmdlKHsgY2xvc2VCdXR0b246IHRydWUsIH0pO1xyXG4gICAgICAgICAgICBtZXNzYWdlID0gYDxiPkhUTUwgVGFncyBFcnJvcjo8L2I+IFlvdXIgY29udGVudCBsZW5ndGggaXMgbW9yZSB0aGFuIFwibWVzc2FnZU1heExlbmd0aFwiIG9wdGlvbi5gOyAvLyBtZXNzYWdlIGh0bWwgZXJyb3JcclxuICAgICAgICB9XHJcbiAgICAgICAgLy8gaWYgcGxhaW5UZXh0IGZhbHNlIGJ1dCB0aGUgbWVzc2FnZSBsZW5ndGggbW9yZSB0aGFuIG1lc3NhZ2VNYXhMZW5ndGggPSBIVE1MIHRhZ3MgZXJyb3Igb2ZmXHJcblxyXG5cclxuICAgICAgICBpZiAobWVzc2FnZS5sZW5ndGggPiBuZXdOb3RpZnlTZXR0aW5ncy5tZXNzYWdlTWF4TGVuZ3RoKSB7XHJcbiAgICAgICAgICAgIG1lc3NhZ2UgPSBgJHttZXNzYWdlLnN1YnN0cmluZygwLCBuZXdOb3RpZnlTZXR0aW5ncy5tZXNzYWdlTWF4TGVuZ3RoKX0uLi5gO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgLy8gbm90aWZ5IGNvdW50ZXIgb25cclxuICAgICAgICBub3RpZmxpeE5vdGlmeUNvdW50Kys7XHJcbiAgICAgICAgLy8gbm90aWZ5IGNvdW50ZXIgb2ZmXHJcblxyXG4gICAgICAgIC8vIGlmIGNzc0FuaW1haW9uIGZhbHNlIC0+IGR1cmF0aW9uIG9uXHJcbiAgICAgICAgaWYgKCFuZXdOb3RpZnlTZXR0aW5ncy5jc3NBbmltYXRpb24pIHtcclxuICAgICAgICAgICAgbmV3Tm90aWZ5U2V0dGluZ3MuY3NzQW5pbWF0aW9uRHVyYXRpb24gPSAwO1xyXG4gICAgICAgIH1cclxuICAgICAgICAvLyBpZiBjc3NBbmltYWlvbiBmYWxzZSAtPiBkdXJhdGlvbiBvZmZcclxuXHJcbiAgICAgICAgLy8gbm90aWZ5IHdyYXAgb25cclxuICAgICAgICBsZXQgZG9jQm9keSA9IGRvY3VtZW50LmJvZHk7XHJcblxyXG4gICAgICAgIGxldCBudGZseE5vdGlmeVdyYXAgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcclxuICAgICAgICBudGZseE5vdGlmeVdyYXAuaWQgPSBub3RpZnlTZXR0aW5ncy53cmFwSUQ7XHJcbiAgICAgICAgbnRmbHhOb3RpZnlXcmFwLnN0eWxlLndpZHRoID0gbmV3Tm90aWZ5U2V0dGluZ3Mud2lkdGg7XHJcbiAgICAgICAgbnRmbHhOb3RpZnlXcmFwLnN0eWxlLnpJbmRleCA9IG5ld05vdGlmeVNldHRpbmdzLnppbmRleDtcclxuICAgICAgICBudGZseE5vdGlmeVdyYXAuc3R5bGUub3BhY2l0eSA9IG5ld05vdGlmeVNldHRpbmdzLm9wYWNpdHk7XHJcblxyXG4gICAgICAgIC8vIHdyYXAgcG9zaXRpb24gb25cclxuICAgICAgICBpZiAobmV3Tm90aWZ5U2V0dGluZ3MucG9zaXRpb24gPT09ICdyaWdodC1ib3R0b20nKSB7XHJcblxyXG4gICAgICAgICAgICBudGZseE5vdGlmeVdyYXAuc3R5bGUucmlnaHQgPSBuZXdOb3RpZnlTZXR0aW5ncy5kaXN0YW5jZTtcclxuICAgICAgICAgICAgbnRmbHhOb3RpZnlXcmFwLnN0eWxlLmJvdHRvbSA9IG5ld05vdGlmeVNldHRpbmdzLmRpc3RhbmNlO1xyXG4gICAgICAgICAgICBudGZseE5vdGlmeVdyYXAuc3R5bGUudG9wID0gJ2F1dG8nO1xyXG4gICAgICAgICAgICBudGZseE5vdGlmeVdyYXAuc3R5bGUubGVmdCA9ICdhdXRvJztcclxuXHJcbiAgICAgICAgfSBlbHNlIGlmIChuZXdOb3RpZnlTZXR0aW5ncy5wb3NpdGlvbiA9PT0gJ2xlZnQtdG9wJykge1xyXG5cclxuICAgICAgICAgICAgbnRmbHhOb3RpZnlXcmFwLnN0eWxlLmxlZnQgPSBuZXdOb3RpZnlTZXR0aW5ncy5kaXN0YW5jZTtcclxuICAgICAgICAgICAgbnRmbHhOb3RpZnlXcmFwLnN0eWxlLnRvcCA9IG5ld05vdGlmeVNldHRpbmdzLmRpc3RhbmNlO1xyXG4gICAgICAgICAgICBudGZseE5vdGlmeVdyYXAuc3R5bGUucmlnaHQgPSAnYXV0byc7XHJcbiAgICAgICAgICAgIG50Zmx4Tm90aWZ5V3JhcC5zdHlsZS5ib3R0b20gPSAnYXV0byc7XHJcblxyXG4gICAgICAgIH0gZWxzZSBpZiAobmV3Tm90aWZ5U2V0dGluZ3MucG9zaXRpb24gPT09ICdsZWZ0LWJvdHRvbScpIHtcclxuXHJcbiAgICAgICAgICAgIG50Zmx4Tm90aWZ5V3JhcC5zdHlsZS5sZWZ0ID0gbmV3Tm90aWZ5U2V0dGluZ3MuZGlzdGFuY2U7XHJcbiAgICAgICAgICAgIG50Zmx4Tm90aWZ5V3JhcC5zdHlsZS5ib3R0b20gPSBuZXdOb3RpZnlTZXR0aW5ncy5kaXN0YW5jZTtcclxuICAgICAgICAgICAgbnRmbHhOb3RpZnlXcmFwLnN0eWxlLnRvcCA9ICdhdXRvJztcclxuICAgICAgICAgICAgbnRmbHhOb3RpZnlXcmFwLnN0eWxlLnJpZ2h0ID0gJ2F1dG8nO1xyXG5cclxuICAgICAgICB9IGVsc2UgeyAvLyAncmlnaHQtdG9wJyBvciBlbHNlXHJcblxyXG4gICAgICAgICAgICBudGZseE5vdGlmeVdyYXAuc3R5bGUucmlnaHQgPSBuZXdOb3RpZnlTZXR0aW5ncy5kaXN0YW5jZTtcclxuICAgICAgICAgICAgbnRmbHhOb3RpZnlXcmFwLnN0eWxlLnRvcCA9IG5ld05vdGlmeVNldHRpbmdzLmRpc3RhbmNlO1xyXG4gICAgICAgICAgICBudGZseE5vdGlmeVdyYXAuc3R5bGUubGVmdCA9ICdhdXRvJztcclxuICAgICAgICAgICAgbnRmbHhOb3RpZnlXcmFwLnN0eWxlLmJvdHRvbSA9ICdhdXRvJztcclxuICAgICAgICB9XHJcbiAgICAgICAgLy8gd3JhcCBwb3NpdGlvbiBvZmZcclxuXHJcbiAgICAgICAgLy8gaWYgYmFja2dyb3VuZCBvdmVybGF5IHRydWUgb25cclxuICAgICAgICBsZXQgbm90aWZ5T3ZlcmxheTtcclxuICAgICAgICBpZiAobmV3Tm90aWZ5U2V0dGluZ3MuYmFja092ZXJsYXkpIHtcclxuXHJcbiAgICAgICAgICAgIG5vdGlmeU92ZXJsYXkgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcclxuICAgICAgICAgICAgbm90aWZ5T3ZlcmxheS5pZCA9IGAke25ld05vdGlmeVNldHRpbmdzLklEfU92ZXJsYXlgO1xyXG4gICAgICAgICAgICBub3RpZnlPdmVybGF5LnN0eWxlLndpZHRoID0gJzEwMCUnO1xyXG4gICAgICAgICAgICBub3RpZnlPdmVybGF5LnN0eWxlLmhlaWdodCA9ICcxMDAlJztcclxuICAgICAgICAgICAgbm90aWZ5T3ZlcmxheS5zdHlsZS5wb3NpdGlvbiA9ICdmaXhlZCc7XHJcbiAgICAgICAgICAgIG5vdGlmeU92ZXJsYXkuc3R5bGUuekluZGV4ID0gbmV3Tm90aWZ5U2V0dGluZ3MuemluZGV4O1xyXG4gICAgICAgICAgICBub3RpZnlPdmVybGF5LnN0eWxlLmxlZnQgPSAwO1xyXG4gICAgICAgICAgICBub3RpZnlPdmVybGF5LnN0eWxlLnRvcCA9IDA7XHJcbiAgICAgICAgICAgIG5vdGlmeU92ZXJsYXkuc3R5bGUucmlnaHQgPSAwO1xyXG4gICAgICAgICAgICBub3RpZnlPdmVybGF5LnN0eWxlLmJvdHRvbSA9IDA7XHJcbiAgICAgICAgICAgIG5vdGlmeU92ZXJsYXkuc3R5bGUuYmFja2dyb3VuZCA9IG5ld05vdGlmeVNldHRpbmdzLmJhY2tPdmVybGF5Q29sb3I7XHJcbiAgICAgICAgICAgIG5vdGlmeU92ZXJsYXkuY2xhc3NOYW1lID0gKG5ld05vdGlmeVNldHRpbmdzLmNzc0FuaW1hdGlvbiA/ICd3aXRoLWFuaW1hdGlvbicgOiAnJyk7XHJcbiAgICAgICAgICAgIG5vdGlmeU92ZXJsYXkuc3R5bGUuYW5pbWF0aW9uRHVyYXRpb24gPSAobmV3Tm90aWZ5U2V0dGluZ3MuY3NzQW5pbWF0aW9uKSA/IGAke25ld05vdGlmeVNldHRpbmdzLmNzc0FuaW1hdGlvbkR1cmF0aW9ufW1zYCA6ICcnO1xyXG5cclxuICAgICAgICAgICAgaWYgKCFkb2N1bWVudC5nZXRFbGVtZW50QnlJZChub3RpZnlPdmVybGF5LmlkKSkge1xyXG4gICAgICAgICAgICAgICAgZG9jQm9keS5hcHBlbmRDaGlsZChub3RpZnlPdmVybGF5KTtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICB9XHJcbiAgICAgICAgLy8gaWYgYmFja2dyb3VuZCBvdmVybGF5IHRydWUgb2ZmXHJcblxyXG4gICAgICAgIGlmICghZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQobnRmbHhOb3RpZnlXcmFwLmlkKSkge1xyXG4gICAgICAgICAgICBkb2NCb2R5LmFwcGVuZENoaWxkKG50Zmx4Tm90aWZ5V3JhcCk7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIC8vIG5vdGlmeSB3cmFwIG9mZlxyXG5cclxuXHJcbiAgICAgICAgLy8gbm90aWZ5IGNvbnRlbnQgb25cclxuICAgICAgICBsZXQgbnRmbHhOb3RpZnkgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcclxuICAgICAgICBudGZseE5vdGlmeS5pZCA9IGAke25ld05vdGlmeVNldHRpbmdzLklEfS0ke25vdGlmbGl4Tm90aWZ5Q291bnR9YDtcclxuICAgICAgICBudGZseE5vdGlmeS5jbGFzc05hbWUgPSBgJHtuZXdOb3RpZnlTZXR0aW5ncy5jbGFzc05hbWV9ICR7dGhlVHlwZS5jaGlsZENsYXNzTmFtZX0gJHsobmV3Tm90aWZ5U2V0dGluZ3MuY3NzQW5pbWF0aW9uID8gJ3dpdGgtYW5pbWF0aW9uJyA6ICcnKX0gJHsobmV3Tm90aWZ5U2V0dGluZ3MudXNlSWNvbiA/ICd3aXRoLWljb24nIDogJycpfSBueC0ke25ld05vdGlmeVNldHRpbmdzLmNzc0FuaW1hdGlvblN0eWxlfSAkeyhuZXdOb3RpZnlTZXR0aW5ncy5jbG9zZUJ1dHRvbiAmJiAhY2FsbGJhY2sgPyAnd2l0aC1jbG9zZScgOiAnJyl9ICR7KGNhbGxiYWNrID8gJ3dpdGgtY2FsbGJhY2snIDogJycpfWA7XHJcbiAgICAgICAgbnRmbHhOb3RpZnkuc3R5bGUuZm9udFNpemUgPSBuZXdOb3RpZnlTZXR0aW5ncy5mb250U2l6ZTtcclxuICAgICAgICBudGZseE5vdGlmeS5zdHlsZS5jb2xvciA9IHRoZVR5cGUudGV4dENvbG9yO1xyXG4gICAgICAgIG50Zmx4Tm90aWZ5LnN0eWxlLmJhY2tncm91bmQgPSB0aGVUeXBlLmJhY2tncm91bmQ7XHJcbiAgICAgICAgbnRmbHhOb3RpZnkuc3R5bGUuYm9yZGVyUmFkaXVzID0gbmV3Tm90aWZ5U2V0dGluZ3MuYm9yZGVyUmFkaXVzO1xyXG5cclxuICAgICAgICAvLyBydGwgb25cclxuICAgICAgICBpZiAobmV3Tm90aWZ5U2V0dGluZ3MucnRsKSB7XHJcbiAgICAgICAgICAgIG50Zmx4Tm90aWZ5LnNldEF0dHJpYnV0ZSgnZGlyJywgJ3J0bCcpO1xyXG4gICAgICAgICAgICBudGZseE5vdGlmeS5jbGFzc0xpc3QuYWRkKCdydGwtb24nKTtcclxuICAgICAgICB9XHJcbiAgICAgICAgLy8gcnRsIG9mZlxyXG5cclxuICAgICAgICAvLyBmb250LWZhbWlseSBvblxyXG4gICAgICAgIG50Zmx4Tm90aWZ5LnN0eWxlLmZvbnRGYW1pbHkgPSBgXCIke25ld05vdGlmeVNldHRpbmdzLmZvbnRGYW1pbHl9XCIsIHNhbnMtc2VyaWZgO1xyXG4gICAgICAgIC8vIGZvbnQtZmFtaWx5IG9mZlxyXG5cclxuICAgICAgICAvLyB1c2UgY3NzIGFuaW1hdGlvbiBvblxyXG4gICAgICAgIGlmIChuZXdOb3RpZnlTZXR0aW5ncy5jc3NBbmltYXRpb24pIHtcclxuICAgICAgICAgICAgbnRmbHhOb3RpZnkuc3R5bGUuYW5pbWF0aW9uRHVyYXRpb24gPSBgJHtuZXdOb3RpZnlTZXR0aW5ncy5jc3NBbmltYXRpb25EdXJhdGlvbn1tc2A7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIC8vIHVzZSBjc3MgYW5pbWF0aW9uIG9mZlxyXG5cclxuICAgICAgICAvLyBjbGljayB0byBjbG9zZSBvblxyXG4gICAgICAgIGxldCBjbG9zZUJ1dHRvbkhUTUwgPSAnJztcclxuICAgICAgICBpZiAobmV3Tm90aWZ5U2V0dGluZ3MuY2xvc2VCdXR0b24gJiYgIWNhbGxiYWNrKSB7XHJcbiAgICAgICAgICAgIGNsb3NlQnV0dG9uSFRNTCA9IGA8c3BhbiBjbGFzcz1cImNsaWNrLXRvLWNsb3NlXCI+PHN2ZyBjbGFzcz1cImNsY2syY2xzXCIgeG1sbnM9XCJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2Z1wiIHhtbDpzcGFjZT1cInByZXNlcnZlXCIgd2lkdGg9XCIyMHB4XCIgaGVpZ2h0PVwiMjBweFwiIHZlcnNpb249XCIxLjFcIiBzdHlsZT1cInNoYXBlLXJlbmRlcmluZzpnZW9tZXRyaWNQcmVjaXNpb247IHRleHQtcmVuZGVyaW5nOmdlb21ldHJpY1ByZWNpc2lvbjsgaW1hZ2UtcmVuZGVyaW5nOm9wdGltaXplUXVhbGl0eTsgZmlsbC1ydWxlOmV2ZW5vZGQ7IGNsaXAtcnVsZTpldmVub2RkXCJ2aWV3Qm94PVwiMCAwIDIwIDIwXCJ4bWxuczp4bGluaz1cImh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmtcIj48ZGVmcz48c3R5bGUgdHlwZT1cInRleHQvY3NzXCI+LmNsaWNrMmNsb3Nle2ZpbGw6JHt0aGVUeXBlLm5vdGlmbGl4SWNvbkNvbG9yfTt9PC9zdHlsZT48L2RlZnM+PGc+PHBhdGggY2xhc3M9XCJjbGljazJjbG9zZVwiIGQ9XCJNMC4zOCAyLjE5bDcuOCA3LjgxIC03LjggNy44MWMtMC41MSwwLjUgLTAuNTEsMS4zMSAtMC4wMSwxLjgxIDAuMjUsMC4yNSAwLjU3LDAuMzggMC45MSwwLjM4IDAuMzQsMCAwLjY3LC0wLjE0IDAuOTEsLTAuMzhsNy44MSAtNy44MSA3LjgxIDcuODFjMC4yNCwwLjI0IDAuNTcsMC4zOCAwLjkxLDAuMzggMC4zNCwwIDAuNjYsLTAuMTQgMC45LC0wLjM4IDAuNTEsLTAuNSAwLjUxLC0xLjMxIDAsLTEuODFsLTcuODEgLTcuODEgNy44MSAtNy44MWMwLjUxLC0wLjUgMC41MSwtMS4zMSAwLC0xLjgyIC0wLjUsLTAuNSAtMS4zMSwtMC41IC0xLjgxLDBsLTcuODEgNy44MSAtNy44MSAtNy44MWMtMC41LC0wLjUgLTEuMzEsLTAuNSAtMS44MSwwIC0wLjUxLDAuNTEgLTAuNTEsMS4zMiAwLDEuODJ6XCIvPjwvZz48L3N2Zz48L3NwYW4+YDtcclxuICAgICAgICB9XHJcbiAgICAgICAgLy8gY2xpY2sgdG8gY2xvc2Ugb2ZmXHJcblxyXG4gICAgICAgIC8vIHVzZSBpY29uIG9uXHJcbiAgICAgICAgaWYgKG5ld05vdGlmeVNldHRpbmdzLnVzZUljb24pIHtcclxuXHJcbiAgICAgICAgICAgIGlmIChuZXdOb3RpZnlTZXR0aW5ncy51c2VGb250QXdlc29tZSkgeyAvLyB1c2UgZm9udCBhd2Vzb21lXHJcblxyXG4gICAgICAgICAgICAgICAgbnRmbHhOb3RpZnkuaW5uZXJIVE1MID0gYDxpIHN0eWxlPVwiY29sb3I6JHt0aGVUeXBlLmZvbnRBd2Vzb21lSWNvbkNvbG9yfTsgZm9udC1zaXplOiR7bmV3Tm90aWZ5U2V0dGluZ3MuZm9udEF3ZXNvbWVJY29uU2l6ZX07XCIgY2xhc3M9XCJubWkgd2ZhICR7dGhlVHlwZS5mb250QXdlc29tZUNsYXNzTmFtZX0gJHsobmV3Tm90aWZ5U2V0dGluZ3MuZm9udEF3ZXNvbWVJY29uU3R5bGUgPT09ICdzaGFkb3cnID8gJ3NoYWRvdycgOiAnYmFzaWMnKX1cIj48L2k+PHNwYW4gY2xhc3M9XCJ0aGUtbWVzc2FnZSB3aXRoLWljb25cIj4ke21lc3NhZ2V9PC9zcGFuPiR7KG5ld05vdGlmeVNldHRpbmdzLmNsb3NlQnV0dG9uID8gY2xvc2VCdXR0b25IVE1MIDogJycpfWA7XHJcblxyXG4gICAgICAgICAgICB9IGVsc2UgeyAvLyB1c2Ugbm90aWZsaXggaWNvblxyXG5cclxuICAgICAgICAgICAgICAgIGxldCBzdmdJY29uID0gJyc7XHJcblxyXG4gICAgICAgICAgICAgICAgaWYgKHN0YXRpY1R5cGUgPT09ICdTdWNjZXNzJykgeyAgLy8gc3VjY2Vzc1xyXG5cclxuICAgICAgICAgICAgICAgICAgICBzdmdJY29uID0gYDxzdmcgY2xhc3M9XCJubWlcIiB4bWxucz1cImh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnXCIgeG1sOnNwYWNlPVwicHJlc2VydmVcIiB3aWR0aD1cIjQwcHhcIiBoZWlnaHQ9XCI0MHB4XCIgdmVyc2lvbj1cIjEuMVwiIHN0eWxlPVwic2hhcGUtcmVuZGVyaW5nOmdlb21ldHJpY1ByZWNpc2lvbjsgdGV4dC1yZW5kZXJpbmc6Z2VvbWV0cmljUHJlY2lzaW9uOyBpbWFnZS1yZW5kZXJpbmc6b3B0aW1pemVRdWFsaXR5OyBmaWxsLXJ1bGU6ZXZlbm9kZDsgY2xpcC1ydWxlOmV2ZW5vZGRcInZpZXdCb3g9XCIwIDAgNDAgNDBcInhtbG5zOnhsaW5rPVwiaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGlua1wiPjxkZWZzPjxzdHlsZSB0eXBlPVwidGV4dC9jc3NcIj4jTm90aWZsaXgtSWNvbi1TdWNjZXNze2ZpbGw6JHt0aGVUeXBlLm5vdGlmbGl4SWNvbkNvbG9yfTt9PC9zdHlsZT48L2RlZnM+PGc+PHBhdGggaWQ9XCJOb3RpZmxpeC1JY29uLVN1Y2Nlc3NcIiBjbGFzcz1cImZpbDBcIiBkPVwiTTIwIDBjMTEuMDMsMCAyMCw4Ljk3IDIwLDIwIDAsMTEuMDMgLTguOTcsMjAgLTIwLDIwIC0xMS4wMywwIC0yMCwtOC45NyAtMjAsLTIwIDAsLTExLjAzIDguOTcsLTIwIDIwLC0yMHptMCAzNy45OGM5LjkyLDAgMTcuOTgsLTguMDYgMTcuOTgsLTE3Ljk4IDAsLTkuOTIgLTguMDYsLTE3Ljk4IC0xNy45OCwtMTcuOTggLTkuOTIsMCAtMTcuOTgsOC4wNiAtMTcuOTgsMTcuOTggMCw5LjkyIDguMDYsMTcuOTggMTcuOTgsMTcuOTh6bS0yLjQgLTEzLjI5bDExLjUyIC0xMi45NmMwLjM3LC0wLjQxIDEuMDEsLTAuNDUgMS40MiwtMC4wOCAwLjQyLDAuMzcgMC40NiwxIDAuMDksMS40MmwtMTIuMTYgMTMuNjdjLTAuMTksMC4yMiAtMC40NiwwLjM0IC0wLjc1LDAuMzQgLTAuMjMsMCAtMC40NSwtMC4wNyAtMC42MywtMC4yMmwtNy42IC02LjA3Yy0wLjQzLC0wLjM1IC0wLjUsLTAuOTkgLTAuMTYsLTEuNDIgMC4zNSwtMC40MyAwLjk5LC0wLjUgMS40MiwtMC4xNmw2Ljg1IDUuNDh6XCIvPjwvZz48L3N2Zz5gO1xyXG5cclxuICAgICAgICAgICAgICAgIH0gZWxzZSBpZiAoc3RhdGljVHlwZSA9PT0gJ0ZhaWx1cmUnKSB7IC8vIGZhaWx1cmVcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgc3ZnSWNvbiA9IGA8c3ZnIGNsYXNzPVwibm1pXCIgeG1sbnM9XCJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2Z1wiIHhtbDpzcGFjZT1cInByZXNlcnZlXCIgd2lkdGg9XCI0MHB4XCIgaGVpZ2h0PVwiNDBweFwiIHZlcnNpb249XCIxLjFcIiBzdHlsZT1cInNoYXBlLXJlbmRlcmluZzpnZW9tZXRyaWNQcmVjaXNpb247IHRleHQtcmVuZGVyaW5nOmdlb21ldHJpY1ByZWNpc2lvbjsgaW1hZ2UtcmVuZGVyaW5nOm9wdGltaXplUXVhbGl0eTsgZmlsbC1ydWxlOmV2ZW5vZGQ7IGNsaXAtcnVsZTpldmVub2RkXCJ2aWV3Qm94PVwiMCAwIDQwIDQwXCJ4bWxuczp4bGluaz1cImh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmtcIj48ZGVmcz48c3R5bGUgdHlwZT1cInRleHQvY3NzXCI+I05vdGlmbGl4LUljb24tRmFpbHVyZXtmaWxsOiR7dGhlVHlwZS5ub3RpZmxpeEljb25Db2xvcn07fTwvc3R5bGU+PC9kZWZzPjxnPjxwYXRoIGlkPVwiTm90aWZsaXgtSWNvbi1GYWlsdXJlXCIgY2xhc3M9XCJmaWwwXCIgZD1cIk0yMCAwYzExLjAzLDAgMjAsOC45NyAyMCwyMCAwLDExLjAzIC04Ljk3LDIwIC0yMCwyMCAtMTEuMDMsMCAtMjAsLTguOTcgLTIwLC0yMCAwLC0xMS4wMyA4Ljk3LC0yMCAyMCwtMjB6bTAgMzcuOThjOS45MiwwIDE3Ljk4LC04LjA2IDE3Ljk4LC0xNy45OCAwLC05LjkyIC04LjA2LC0xNy45OCAtMTcuOTgsLTE3Ljk4IC05LjkyLDAgLTE3Ljk4LDguMDYgLTE3Ljk4LDE3Ljk4IDAsOS45MiA4LjA2LDE3Ljk4IDE3Ljk4LDE3Ljk4em0xLjQyIC0xNy45OGw2LjEzIDYuMTJjMC4zOSwwLjQgMC4zOSwxLjA0IDAsMS40MyAtMC4xOSwwLjE5IC0wLjQ1LDAuMjkgLTAuNzEsMC4yOSAtMC4yNywwIC0wLjUzLC0wLjEgLTAuNzIsLTAuMjlsLTYuMTIgLTYuMTMgLTYuMTMgNi4xM2MtMC4xOSwwLjE5IC0wLjQ0LDAuMjkgLTAuNzEsMC4yOSAtMC4yNywwIC0wLjUyLC0wLjEgLTAuNzEsLTAuMjkgLTAuMzksLTAuMzkgLTAuMzksLTEuMDMgMCwtMS40M2w2LjEzIC02LjEyIC02LjEzIC02LjEzYy0wLjM5LC0wLjM5IC0wLjM5LC0xLjAzIDAsLTEuNDIgMC4zOSwtMC4zOSAxLjAzLC0wLjM5IDEuNDIsMGw2LjEzIDYuMTIgNi4xMiAtNi4xMmMwLjQsLTAuMzkgMS4wNCwtMC4zOSAxLjQzLDAgMC4zOSwwLjM5IDAuMzksMS4wMyAwLDEuNDJsLTYuMTMgNi4xM3pcIi8+PC9nPjwvc3ZnPmA7XHJcblxyXG4gICAgICAgICAgICAgICAgfSBlbHNlIGlmIChzdGF0aWNUeXBlID09PSAnV2FybmluZycpIHsgLy8gd2FybmluZ1xyXG5cclxuICAgICAgICAgICAgICAgICAgICBzdmdJY29uID0gYDxzdmcgY2xhc3M9XCJubWlcIiB4bWxucz1cImh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnXCIgeG1sOnNwYWNlPVwicHJlc2VydmVcIiB3aWR0aD1cIjQwcHhcIiBoZWlnaHQ9XCI0MHB4XCIgdmVyc2lvbj1cIjEuMVwiIHN0eWxlPVwic2hhcGUtcmVuZGVyaW5nOmdlb21ldHJpY1ByZWNpc2lvbjsgdGV4dC1yZW5kZXJpbmc6Z2VvbWV0cmljUHJlY2lzaW9uOyBpbWFnZS1yZW5kZXJpbmc6b3B0aW1pemVRdWFsaXR5OyBmaWxsLXJ1bGU6ZXZlbm9kZDsgY2xpcC1ydWxlOmV2ZW5vZGRcInZpZXdCb3g9XCIwIDAgNDAgNDBcInhtbG5zOnhsaW5rPVwiaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGlua1wiPjxkZWZzPjxzdHlsZSB0eXBlPVwidGV4dC9jc3NcIj4jTm90aWZsaXgtSWNvbi1XYXJuaW5ne2ZpbGw6JHt0aGVUeXBlLm5vdGlmbGl4SWNvbkNvbG9yfTt9PC9zdHlsZT48L2RlZnM+PGc+PHBhdGggaWQ9XCJOb3RpZmxpeC1JY29uLVdhcm5pbmdcIiBjbGFzcz1cImZpbDBcIiBkPVwiTTIxLjkxIDMuNDhsMTcuOCAzMC44OWMwLjg0LDEuNDYgLTAuMjMsMy4yNSAtMS45MSwzLjI1bC0zNS42IDBjLTEuNjgsMCAtMi43NSwtMS43OSAtMS45MSwtMy4yNWwxNy44IC0zMC44OWMwLjg1LC0xLjQ3IDIuOTcsLTEuNDcgMy44Miwwem0xNi4xNSAzMS44NGwtMTcuOCAtMzAuODljLTAuMTEsLTAuMiAtMC40MSwtMC4yIC0wLjUyLDBsLTE3LjggMzAuODljLTAuMTIsMC4yIDAuMDUsMC40IDAuMjYsMC40bDM1LjYgMGMwLjIxLDAgMC4zOCwtMC4yIDAuMjYsLTAuNHptLTE5LjAxIC00LjEybDAgLTEuMDVjMCwtMC41MyAwLjQyLC0wLjk1IDAuOTUsLTAuOTUgMC41MywwIDAuOTUsMC40MiAwLjk1LDAuOTVsMCAxLjA1YzAsMC41MyAtMC40MiwwLjk1IC0wLjk1LDAuOTUgLTAuNTMsMCAtMC45NSwtMC40MiAtMC45NSwtMC45NXptMCAtNC42NmwwIC0xMy4zOWMwLC0wLjUyIDAuNDIsLTAuOTUgMC45NSwtMC45NSAwLjUzLDAgMC45NSwwLjQzIDAuOTUsMC45NWwwIDEzLjM5YzAsMC41MyAtMC40MiwwLjk2IC0wLjk1LDAuOTYgLTAuNTMsMCAtMC45NSwtMC40MyAtMC45NSwtMC45NnpcIi8+PC9nPjwvc3ZnPmA7XHJcblxyXG4gICAgICAgICAgICAgICAgfSBlbHNlIGlmIChzdGF0aWNUeXBlID09PSAnSW5mbycpIHsgLy8gaW5mb1xyXG5cclxuICAgICAgICAgICAgICAgICAgICBzdmdJY29uID0gYDxzdmcgY2xhc3M9XCJubWlcIiB4bWxucz1cImh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnXCIgeG1sOnNwYWNlPVwicHJlc2VydmVcIiB3aWR0aD1cIjQwcHhcIiBoZWlnaHQ9XCI0MHB4XCIgdmVyc2lvbj1cIjEuMVwiIHN0eWxlPVwic2hhcGUtcmVuZGVyaW5nOmdlb21ldHJpY1ByZWNpc2lvbjsgdGV4dC1yZW5kZXJpbmc6Z2VvbWV0cmljUHJlY2lzaW9uOyBpbWFnZS1yZW5kZXJpbmc6b3B0aW1pemVRdWFsaXR5OyBmaWxsLXJ1bGU6ZXZlbm9kZDsgY2xpcC1ydWxlOmV2ZW5vZGRcInZpZXdCb3g9XCIwIDAgNDAgNDBcInhtbG5zOnhsaW5rPVwiaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGlua1wiPjxkZWZzPjxzdHlsZSB0eXBlPVwidGV4dC9jc3NcIj4jTm90aWZsaXgtSWNvbi1JbmZve2ZpbGw6JHt0aGVUeXBlLm5vdGlmbGl4SWNvbkNvbG9yfTt9PC9zdHlsZT48L2RlZnM+PGc+PHBhdGggaWQ9XCJOb3RpZmxpeC1JY29uLUluZm9cIiBjbGFzcz1cImZpbDBcIiBkPVwiTTIwIDBjMTEuMDMsMCAyMCw4Ljk3IDIwLDIwIDAsMTEuMDMgLTguOTcsMjAgLTIwLDIwIC0xMS4wMywwIC0yMCwtOC45NyAtMjAsLTIwIDAsLTExLjAzIDguOTcsLTIwIDIwLC0yMHptMCAzNy45OGM5LjkyLDAgMTcuOTgsLTguMDYgMTcuOTgsLTE3Ljk4IDAsLTkuOTIgLTguMDYsLTE3Ljk4IC0xNy45OCwtMTcuOTggLTkuOTIsMCAtMTcuOTgsOC4wNiAtMTcuOTgsMTcuOTggMCw5LjkyIDguMDYsMTcuOTggMTcuOTgsMTcuOTh6bS0wLjk5IC0yMy4zYzAsLTAuNTQgMC40NCwtMC45OCAwLjk5LC0wLjk4IDAuNTUsMCAwLjk5LDAuNDQgMC45OSwwLjk4bDAgMTUuODZjMCwwLjU1IC0wLjQ0LDAuOTkgLTAuOTksMC45OSAtMC41NSwwIC0wLjk5LC0wLjQ0IC0wLjk5LC0wLjk5bDAgLTE1Ljg2em0wIC01LjIyYzAsLTAuNTUgMC40NCwtMC45OSAwLjk5LC0wLjk5IDAuNTUsMCAwLjk5LDAuNDQgMC45OSwwLjk5bDAgMS4wOWMwLDAuNTQgLTAuNDQsMC45OSAtMC45OSwwLjk5IC0wLjU1LDAgLTAuOTksLTAuNDUgLTAuOTksLTAuOTlsMCAtMS4wOXpcIi8+PC9nPjwvc3ZnPmA7XHJcblxyXG4gICAgICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgICAgIG50Zmx4Tm90aWZ5LmlubmVySFRNTCA9IGAke3N2Z0ljb259PHNwYW4gY2xhc3M9XCJ0aGUtbWVzc2FnZSB3aXRoLWljb25cIj4ke21lc3NhZ2V9PC9zcGFuPiR7KG5ld05vdGlmeVNldHRpbmdzLmNsb3NlQnV0dG9uID8gY2xvc2VCdXR0b25IVE1MIDogJycpfWA7XHJcblxyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgIH0gZWxzZSB7IC8vIHdpdGhvdXQgaWNvblxyXG5cclxuICAgICAgICAgICAgbnRmbHhOb3RpZnkuaW5uZXJIVE1MID0gYDxzcGFuIGNsYXNzPVwidGhlLW1lc3NhZ2VcIj4ke21lc3NhZ2V9PC9zcGFuPiAkeyhuZXdOb3RpZnlTZXR0aW5ncy5jbG9zZUJ1dHRvbiA/IGNsb3NlQnV0dG9uSFRNTCA6ICcnKX1gO1xyXG4gICAgICAgIH1cclxuICAgICAgICAvLyB1c2UgaWNvbiBvZmZcclxuICAgICAgICAvLyBub3RpZnkgY29udGVudCBvZmZcclxuXHJcblxyXG4gICAgICAgIC8vIG5vdGlmeSBhcHBlbmQgb3IgcHJlcGVuZCBvblxyXG4gICAgICAgIGNvbnN0IG5vdGlmeVdyYXBFbGVtZW50ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQobnRmbHhOb3RpZnlXcmFwLmlkKTtcclxuICAgICAgICBpZiAobmV3Tm90aWZ5U2V0dGluZ3MucG9zaXRpb24gPT09ICdsZWZ0LWJvdHRvbScgfHwgbmV3Tm90aWZ5U2V0dGluZ3MucG9zaXRpb24gPT09ICdyaWdodC1ib3R0b20nKSB7IC8vIHRoZSBuZXcgb25lIHdpbGwgYmUgZmlyc3RcclxuXHJcbiAgICAgICAgICAgIG5vdGlmeVdyYXBFbGVtZW50Lmluc2VydEJlZm9yZShudGZseE5vdGlmeSwgbm90aWZ5V3JhcEVsZW1lbnQuZmlyc3RDaGlsZCk7XHJcbiAgICAgICAgfSBlbHNlIHtcclxuXHJcbiAgICAgICAgICAgIG5vdGlmeVdyYXBFbGVtZW50LmFwcGVuZENoaWxkKG50Zmx4Tm90aWZ5KTtcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIGlmIChuZXdOb3RpZnlTZXR0aW5ncy51c2VJY29uKSB7IC8vIGlmIHVzZUljb24sIGR5bmFtaWNhbGx5IHZlcnRpY2FsIGFsaWduIHRoZSBjb250ZW50c1xyXG5cclxuICAgICAgICAgICAgbGV0IG1lc3NhZ2VJY29uID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQobnRmbHhOb3RpZnkuaWQpLnF1ZXJ5U2VsZWN0b3JBbGwoJy5ubWknKVswXTtcclxuICAgICAgICAgICAgbGV0IG1lc3NhZ2VJY29uSCA9IDQwO1xyXG5cclxuICAgICAgICAgICAgaWYgKG5ld05vdGlmeVNldHRpbmdzLnVzZUZvbnRBd2Vzb21lKSB7IC8vIGlmIGZvbnQgYXdlc29tZVxyXG5cclxuICAgICAgICAgICAgICAgIG1lc3NhZ2VJY29uSCA9IE1hdGgucm91bmQocGFyc2VJbnQobWVzc2FnZUljb24ub2Zmc2V0SGVpZ2h0KSk7XHJcblxyXG4gICAgICAgICAgICB9IGVsc2UgeyAvLyBpZiBub3RpZmxpeCBTVkdcclxuXHJcbiAgICAgICAgICAgICAgICBsZXQgU3ZnQkJveCA9IG1lc3NhZ2VJY29uLmdldEJCb3goKTtcclxuICAgICAgICAgICAgICAgIG1lc3NhZ2VJY29uSCA9IE1hdGgucm91bmQocGFyc2VJbnQoU3ZnQkJveC53aWR0aCkpO1xyXG5cclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgbGV0IG1lc3NhZ2VUZXh0ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQobnRmbHhOb3RpZnkuaWQpLnF1ZXJ5U2VsZWN0b3JBbGwoJ3NwYW4nKVswXTtcclxuICAgICAgICAgICAgbGV0IG1lc3NhZ2VUZXh0SCA9IE1hdGgucm91bmQobWVzc2FnZVRleHQub2Zmc2V0SGVpZ2h0KTtcclxuXHJcbiAgICAgICAgICAgIGlmIChtZXNzYWdlVGV4dEggPD0gbWVzc2FnZUljb25IKSB7XHJcbiAgICAgICAgICAgICAgICBsZXQgcGFkZGluZ1ZhbCA9IGAke3BhcnNlSW50KChtZXNzYWdlSWNvbkggLSBtZXNzYWdlVGV4dEgpIC8gMikudG9TdHJpbmcoKX1weGA7XHJcbiAgICAgICAgICAgICAgICBtZXNzYWdlVGV4dC5zdHlsZS5wYWRkaW5nVG9wID0gcGFkZGluZ1ZhbDtcclxuICAgICAgICAgICAgICAgIG1lc3NhZ2VUZXh0LnN0eWxlLnBhZGRpbmdCb3R0b20gPSBwYWRkaW5nVmFsO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgIH1cclxuICAgICAgICAvLyBub3RpZnkgYXBwZW5kIG9yIHByZXBlbmQgb2ZmXHJcblxyXG5cclxuICAgICAgICAvLyByZW1vdmUgYnkgdGltZW91dCBvciBjbGljayBvblxyXG4gICAgICAgIGlmIChkb2N1bWVudC5nZXRFbGVtZW50QnlJZChudGZseE5vdGlmeS5pZCkpIHtcclxuXHJcbiAgICAgICAgICAgIC8vIHNldCBlbGVtZW50cyBvblxyXG4gICAgICAgICAgICBsZXQgcmVtb3ZlRGl2ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQobnRmbHhOb3RpZnkuaWQpO1xyXG4gICAgICAgICAgICBsZXQgcmVtb3ZlV3JhcCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKG50Zmx4Tm90aWZ5V3JhcC5pZCk7XHJcbiAgICAgICAgICAgIGxldCByZW1vdmVPdmVybGF5O1xyXG4gICAgICAgICAgICBpZiAobmV3Tm90aWZ5U2V0dGluZ3MuYmFja092ZXJsYXkpIHtcclxuICAgICAgICAgICAgICAgIHJlbW92ZU92ZXJsYXkgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZChub3RpZnlPdmVybGF5LmlkKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAvLyBzZXQgZWxlbWVudHMgb25cclxuXHJcbiAgICAgICAgICAgIC8vIHRpbWVvdXQgdmFycyBvblxyXG4gICAgICAgICAgICBsZXQgdGltZW91dEFkZENsYXNzO1xyXG4gICAgICAgICAgICBsZXQgdGltZW91dFJlbW92ZTtcclxuICAgICAgICAgICAgLy8gdGltZW91dCB2YXJzIG9mZlxyXG5cclxuICAgICAgICAgICAgLy8gdGltZW91dCBpZiBjbGljayB0byBjbG9zZSBmYWxzZSBhbmQgY2FsbGJhY2sgdW5kZWZpbmVkIG9uXHJcbiAgICAgICAgICAgIGlmICghbmV3Tm90aWZ5U2V0dGluZ3MuY2xvc2VCdXR0b24gJiYgIWNhbGxiYWNrKSB7XHJcbiAgICAgICAgICAgICAgICB0aW1lb3V0QWRkQ2xhc3MgPSBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgcmVtb3ZlRGl2LmNsYXNzTGlzdC5hZGQoJ3JlbW92ZScpO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICBpZiAobmV3Tm90aWZ5U2V0dGluZ3MuYmFja092ZXJsYXkgJiYgcmVtb3ZlV3JhcC5jaGlsZEVsZW1lbnRDb3VudCA8PSAwKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHJlbW92ZU92ZXJsYXkuY2xhc3NMaXN0LmFkZCgncmVtb3ZlJyk7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgICAgIH0sIG5ld05vdGlmeVNldHRpbmdzLnRpbWVvdXQpO1xyXG5cclxuICAgICAgICAgICAgICAgIHRpbWVvdXRSZW1vdmUgPSBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgICAgICAgICByZW1vdmVEaXYucGFyZW50Tm9kZS5yZW1vdmVDaGlsZChyZW1vdmVEaXYpO1xyXG4gICAgICAgICAgICAgICAgICAgIGlmIChyZW1vdmVXcmFwLmNoaWxkRWxlbWVudENvdW50IDw9IDApIHsgLy8gaWYgY2hpbGRzIGNvdW50ID09PSAwIHJlbW92ZSB3cmFwXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHJlbW92ZVdyYXAucGFyZW50Tm9kZS5yZW1vdmVDaGlsZChyZW1vdmVXcmFwKTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmIChuZXdOb3RpZnlTZXR0aW5ncy5iYWNrT3ZlcmxheSkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgcmVtb3ZlT3ZlcmxheS5wYXJlbnROb2RlLnJlbW92ZUNoaWxkKHJlbW92ZU92ZXJsYXkpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgfSwgbmV3Tm90aWZ5U2V0dGluZ3MudGltZW91dCArIG5ld05vdGlmeVNldHRpbmdzLmNzc0FuaW1hdGlvbkR1cmF0aW9uKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAvLyB0aW1lb3V0IGlmIGNsaWNrIHRvIGNsb3NlIGZhbHNlIGFuZCBjYWxsYmFjayB1bmRlZmluZWQgb2ZmXHJcblxyXG4gICAgICAgICAgICAvLyBpZiBjbGljayB0byBjbG9zZSBvbiAgICAgICAgICAgIFxyXG4gICAgICAgICAgICBpZiAobmV3Tm90aWZ5U2V0dGluZ3MuY2xvc2VCdXR0b24gJiYgIWNhbGxiYWNrKSB7XHJcblxyXG4gICAgICAgICAgICAgICAgbGV0IGNsb3NlQnV0dG9uRWxtID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQobnRmbHhOb3RpZnkuaWQpLnF1ZXJ5U2VsZWN0b3JBbGwoJ3NwYW4uY2xpY2stdG8tY2xvc2UnKVswXTtcclxuXHJcbiAgICAgICAgICAgICAgICBjbG9zZUJ1dHRvbkVsbS5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGZ1bmN0aW9uICgpIHtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgcmVtb3ZlRGl2LmNsYXNzTGlzdC5hZGQoJ3JlbW92ZScpO1xyXG4gICAgICAgICAgICAgICAgICAgIGNsZWFyVGltZW91dCh0aW1lb3V0QWRkQ2xhc3MpO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICBpZiAobmV3Tm90aWZ5U2V0dGluZ3MuYmFja092ZXJsYXkgJiYgcmVtb3ZlV3JhcC5jaGlsZEVsZW1lbnRDb3VudCA8PSAxKSB7IC8vIGlmIGxhc3QgY2hpbGQgLSBhZGRDbGFzcyBSZW1vdmUgdG8gT3ZlcmxheVxyXG4gICAgICAgICAgICAgICAgICAgICAgICByZW1vdmVPdmVybGF5LmNsYXNzTGlzdC5hZGQoJ3JlbW92ZScpO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHJlbW92ZURpdi5wYXJlbnROb2RlLnJlbW92ZUNoaWxkKHJlbW92ZURpdik7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGNsZWFyVGltZW91dCh0aW1lb3V0UmVtb3ZlKTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmIChyZW1vdmVXcmFwLmNoaWxkRWxlbWVudENvdW50IDw9IDApIHsgLy8gaWYgY2hpbGRzIGNvdW50ID09PSAwIHJlbW92ZSB3cmFwXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICByZW1vdmVXcmFwLnBhcmVudE5vZGUucmVtb3ZlQ2hpbGQocmVtb3ZlV3JhcCk7IC8vIHJlbW92ZSB3cmFwXHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgaWYgKG5ld05vdGlmeVNldHRpbmdzLmJhY2tPdmVybGF5KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgcmVtb3ZlT3ZlcmxheS5wYXJlbnROb2RlLnJlbW92ZUNoaWxkKHJlbW92ZU92ZXJsYXkpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIH0sIG5ld05vdGlmeVNldHRpbmdzLmNzc0FuaW1hdGlvbkR1cmF0aW9uKTtcclxuXHJcbiAgICAgICAgICAgICAgICB9KTtcclxuXHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgLy8gaWYgY2xpY2sgdG8gY2xvc2Ugb2ZmXHJcblxyXG5cclxuICAgICAgICAgICAgLy8gY2FsbGJhY2sgb25cclxuICAgICAgICAgICAgaWYgKGNhbGxiYWNrICYmIHR5cGVvZiBjYWxsYmFjayA9PT0gJ2Z1bmN0aW9uJykge1xyXG5cclxuICAgICAgICAgICAgICAgIHJlbW92ZURpdi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGZ1bmN0aW9uIChlKSB7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIGNhbGxiYWNrKCk7IC8vIGNhbGxiYWNrXHJcblxyXG4gICAgICAgICAgICAgICAgICAgIC8vIHJlbW92ZSBlbGVtZW50IG9uXHJcbiAgICAgICAgICAgICAgICAgICAgcmVtb3ZlRGl2LmNsYXNzTGlzdC5hZGQoJ3JlbW92ZScpO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICBpZiAobmV3Tm90aWZ5U2V0dGluZ3MuYmFja092ZXJsYXkgJiYgcmVtb3ZlV3JhcC5jaGlsZEVsZW1lbnRDb3VudCA8PSAwKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHJlbW92ZU92ZXJsYXkuY2xhc3NMaXN0LmFkZCgncmVtb3ZlJyk7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgICAgICAgICBjbGVhclRpbWVvdXQodGltZW91dEFkZENsYXNzKTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICByZW1vdmVEaXYucGFyZW50Tm9kZS5yZW1vdmVDaGlsZChyZW1vdmVEaXYpO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKHJlbW92ZVdyYXAuY2hpbGRFbGVtZW50Q291bnQgPD0gMCkgeyAvLyBpZiBjaGlsZHMgY291bnQgPT09IDAgcmVtb3ZlIHdyYXBcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHJlbW92ZVdyYXAucGFyZW50Tm9kZS5yZW1vdmVDaGlsZChyZW1vdmVXcmFwKTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiAobmV3Tm90aWZ5U2V0dGluZ3MuYmFja092ZXJsYXkpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICByZW1vdmVPdmVybGF5LnBhcmVudE5vZGUucmVtb3ZlQ2hpbGQocmVtb3ZlT3ZlcmxheSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgICAgICAgICAgY2xlYXJUaW1lb3V0KHRpbWVvdXRSZW1vdmUpO1xyXG4gICAgICAgICAgICAgICAgICAgIH0sIG5ld05vdGlmeVNldHRpbmdzLmNzc0FuaW1hdGlvbkR1cmF0aW9uKTtcclxuICAgICAgICAgICAgICAgICAgICAvLyByZW1vdmUgZWxlbWVudCBvZmZcclxuXHJcbiAgICAgICAgICAgICAgICB9KTtcclxuXHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgLy8gY2FsbGJhY2sgb2ZmXHJcblxyXG4gICAgICAgIH1cclxuICAgICAgICAvLyByZW1vdmUgYnkgdGltZW91dCBvciBjbGljayBvZmZcclxuXHJcbiAgICB9IGVsc2Uge1xyXG4gICAgICAgIG5vdGlmbGl4Q29uc29sZUVycm9yKCdOb3RpZmxpeCBFcnJvcicsICdXaGVyZSBpcyB0aGUgYXJndW1lbnRzPycpO1xyXG4gICAgfVxyXG5cclxufVxyXG4vLyBOb3RpZmxpeDogTm90aWZ5IFNpbmdsZSBvZmZcclxuXHJcblxyXG4vLyBOb3RpZmxpeDogUmVwb3J0IFNpbmdsZSBvblxyXG5jb25zdCBOb3RpZmxpeFJlcG9ydCA9IGZ1bmN0aW9uICh0aXRsZSwgbWVzc2FnZSwgYnV0dG9uVGV4dCwgYnV0dG9uQ2FsbGJhY2ssIHRoZVR5cGUsIHN0YXRpY1R5cGUpIHtcclxuXHJcbiAgICAvLyBpZiBwbGFpblRleHQgdHJ1ZSA9IEhUTUwgdGFncyBub3QgYWxsb3dlZCBvblxyXG4gICAgaWYgKG5ld1JlcG9ydFNldHRpbmdzLnBsYWluVGV4dCkge1xyXG4gICAgICAgIHRpdGxlID0gbm90aWZsaXhQbGFpbnRleHQodGl0bGUpO1xyXG4gICAgICAgIG1lc3NhZ2UgPSBub3RpZmxpeFBsYWludGV4dChtZXNzYWdlKTtcclxuICAgICAgICBidXR0b25UZXh0ID0gbm90aWZsaXhQbGFpbnRleHQoYnV0dG9uVGV4dCk7XHJcbiAgICB9XHJcbiAgICAvLyBpZiBwbGFpblRleHQgdHJ1ZSA9IEhUTUwgdGFncyBub3QgYWxsb3dlZCBvZmZcclxuXHJcbiAgICAvLyBpZiBwbGFpblRleHQgZmFsc2UgYnV0IHRoZSBjb250ZW50cyBsZW5ndGggbW9yZSB0aGFuICpNYXhMZW5ndGggPSBIVE1MIHRhZ3MgZXJyb3Igb25cclxuICAgIGlmICghbmV3UmVwb3J0U2V0dGluZ3MucGxhaW5UZXh0KSB7XHJcbiAgICAgICAgaWYgKHRpdGxlLmxlbmd0aCA+IG5ld1JlcG9ydFNldHRpbmdzLnRpdGxlTWF4TGVuZ3RoKSB7XHJcbiAgICAgICAgICAgIHRpdGxlID0gJ0hUTUwgVGFncyBFcnJvcic7IC8vIHRpdGxlIGh0bWwgZXJyb3JcclxuICAgICAgICAgICAgbWVzc2FnZSA9ICdZb3VyIFRpdGxlIGNvbnRlbnQgbGVuZ3RoIGlzIG1vcmUgdGhhbiBcInRpdGxlTWF4TGVuZ3RoXCIgb3B0aW9uLic7IC8vIG1lc3NhZ2UgaHRtbCBlcnJvclxyXG4gICAgICAgICAgICBidXR0b25UZXh0ID0gJ09rYXknOyAvLyBidXR0b24gaHRtbCBlcnJvclxyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgaWYgKG1lc3NhZ2UubGVuZ3RoID4gbmV3UmVwb3J0U2V0dGluZ3MubWVzc2FnZU1heExlbmd0aCkge1xyXG4gICAgICAgICAgICB0aXRsZSA9ICdIVE1MIFRhZ3MgRXJyb3InOyAvLyB0aXRsZSBodG1sIGVycm9yXHJcbiAgICAgICAgICAgIG1lc3NhZ2UgPSAnWW91ciBNZXNzYWdlIGNvbnRlbnQgbGVuZ3RoIGlzIG1vcmUgdGhhbiBcIm1lc3NhZ2VNYXhMZW5ndGhcIiBvcHRpb24uJzsgLy8gbWVzc2FnZSBodG1sIGVycm9yXHJcbiAgICAgICAgICAgIGJ1dHRvblRleHQgPSAnT2theSc7IC8vIGJ1dHRvbiBodG1sIGVycm9yXHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICBpZiAoYnV0dG9uVGV4dC5sZW5ndGggPiBuZXdSZXBvcnRTZXR0aW5ncy5idXR0b25NYXhMZW5ndGgpIHtcclxuICAgICAgICAgICAgdGl0bGUgPSAnSFRNTCBUYWdzIEVycm9yJzsgLy8gdGl0bGUgaHRtbCBlcnJvclxyXG4gICAgICAgICAgICBtZXNzYWdlID0gJ1lvdXIgQnV0dG9uIGNvbnRlbnQgbGVuZ3RoIGlzIG1vcmUgdGhhbiBcImJ1dHRvbk1heExlbmd0aFwiIG9wdGlvbi4nOyAvLyBtZXNzYWdlIGh0bWwgZXJyb3JcclxuICAgICAgICAgICAgYnV0dG9uVGV4dCA9ICdPa2F5JzsgLy8gYnV0dG9uIGh0bWwgZXJyb3JcclxuICAgICAgICB9XHJcbiAgICB9XHJcbiAgICAvLyBpZiBwbGFpblRleHQgZmFsc2UgYnV0IHRoZSBjb250ZW50cyBsZW5ndGggbW9yZSB0aGFuICpNYXhMZW5ndGggPSBIVE1MIHRhZ3MgZXJyb3Igb2ZmXHJcblxyXG5cclxuICAgIC8vIG1heCBsZW5ndGggb25cclxuICAgIGlmICh0aXRsZS5sZW5ndGggPiBuZXdSZXBvcnRTZXR0aW5ncy50aXRsZU1heExlbmd0aCkge1xyXG4gICAgICAgIHRpdGxlID0gYCR7dGl0bGUuc3Vic3RyaW5nKDAsIG5ld1JlcG9ydFNldHRpbmdzLnRpdGxlTWF4TGVuZ3RoKX0uLi5gO1xyXG4gICAgfVxyXG5cclxuICAgIGlmIChtZXNzYWdlLmxlbmd0aCA+IG5ld1JlcG9ydFNldHRpbmdzLm1lc3NhZ2VNYXhMZW5ndGgpIHtcclxuICAgICAgICBtZXNzYWdlID0gYCR7bWVzc2FnZS5zdWJzdHJpbmcoMCwgbmV3UmVwb3J0U2V0dGluZ3MubWVzc2FnZU1heExlbmd0aCl9Li4uYDtcclxuICAgIH1cclxuXHJcbiAgICBpZiAoYnV0dG9uVGV4dC5sZW5ndGggPiBuZXdSZXBvcnRTZXR0aW5ncy5idXR0b25NYXhMZW5ndGgpIHtcclxuICAgICAgICBidXR0b25UZXh0ID0gYCR7YnV0dG9uVGV4dC5zdWJzdHJpbmcoMCwgbmV3UmVwb3J0U2V0dGluZ3MuYnV0dG9uTWF4TGVuZ3RoKX0uLi5gO1xyXG4gICAgfVxyXG4gICAgLy8gbWF4IGxlbmd0aCBvZmZcclxuXHJcbiAgICAvLyBpZiBjc3NBbmltYWlvbiBmYWxzZSAtPiBkdXJhdGlvbiBvblxyXG4gICAgaWYgKCFuZXdSZXBvcnRTZXR0aW5ncy5jc3NBbmltYXRpb24pIHtcclxuICAgICAgICBuZXdSZXBvcnRTZXR0aW5ncy5jc3NBbmltYXRpb25EdXJhdGlvbiA9IDA7XHJcbiAgICB9XHJcbiAgICAvLyBpZiBjc3NBbmltYWlvbiBmYWxzZSAtPiBkdXJhdGlvbiBvZmZcclxuXHJcbiAgICAvLyByZXBvcnQgd3JhcCBvblxyXG4gICAgY29uc3QgZG9jQm9keSA9IGRvY3VtZW50LmJvZHk7XHJcblxyXG4gICAgY29uc3QgbnRmbHhSZXBvcnRXcmFwID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XHJcbiAgICBudGZseFJlcG9ydFdyYXAuaWQgPSByZXBvcnRTZXR0aW5ncy5JRDtcclxuICAgIG50Zmx4UmVwb3J0V3JhcC5jbGFzc05hbWUgPSBuZXdSZXBvcnRTZXR0aW5ncy5jbGFzc05hbWU7XHJcbiAgICBudGZseFJlcG9ydFdyYXAuc3R5bGUud2lkdGggPSBuZXdSZXBvcnRTZXR0aW5ncy53aWR0aDtcclxuICAgIG50Zmx4UmVwb3J0V3JhcC5zdHlsZS56SW5kZXggPSBuZXdSZXBvcnRTZXR0aW5ncy56aW5kZXg7XHJcbiAgICBudGZseFJlcG9ydFdyYXAuc3R5bGUuYm9yZGVyUmFkaXVzID0gbmV3UmVwb3J0U2V0dGluZ3MuYm9yZGVyUmFkaXVzO1xyXG5cclxuICAgIC8vIGZvbnQtZmFtaWx5IG9uXHJcbiAgICBudGZseFJlcG9ydFdyYXAuc3R5bGUuZm9udEZhbWlseSA9IGBcIiR7bmV3UmVwb3J0U2V0dGluZ3MuZm9udEZhbWlseX1cIiwgc2Fucy1zZXJpZmA7XHJcbiAgICAvLyBmb250LWZhbWlseSBvZmZcclxuXHJcbiAgICAvLyBydGwgb25cclxuICAgIGlmIChuZXdSZXBvcnRTZXR0aW5ncy5ydGwpIHtcclxuICAgICAgICBudGZseFJlcG9ydFdyYXAuc2V0QXR0cmlidXRlKCdkaXInLCAncnRsJyk7XHJcbiAgICAgICAgbnRmbHhSZXBvcnRXcmFwLmNsYXNzTGlzdC5hZGQoJ3J0bC1vbicpO1xyXG4gICAgfVxyXG4gICAgLy8gcnRsIG9mZlxyXG5cclxuICAgIC8vIG92ZXJsYXkgb25cclxuICAgIGxldCByZXBvcnRPdmVybGF5ID0gJyc7XHJcbiAgICBpZiAobmV3UmVwb3J0U2V0dGluZ3MuYmFja092ZXJsYXkpIHtcclxuICAgICAgICByZXBvcnRPdmVybGF5ID0gYDxkaXYgY2xhc3M9XCIke25ld1JlcG9ydFNldHRpbmdzLmNsYXNzTmFtZX0tb3ZlcmxheSAkeyhuZXdSZXBvcnRTZXR0aW5ncy5jc3NBbmltYXRpb24gPyAnd2l0aC1hbmltYXRpb24nIDogJycpfVwiIHN0eWxlPVwiYmFja2dyb3VuZDoke25ld1JlcG9ydFNldHRpbmdzLmJhY2tPdmVybGF5Q29sb3J9OyBhbmltYXRpb24tZHVyYXRpb246JHtuZXdSZXBvcnRTZXR0aW5ncy5jc3NBbmltYXRpb25EdXJhdGlvbn1tcztcIj48L2Rpdj5gO1xyXG4gICAgfVxyXG4gICAgLy8gb3ZlcmxheSBvZmZcclxuXHJcblxyXG4gICAgLy8gc3ZnIGljb24gb25cclxuICAgIGxldCBzdmdJY29uID0gJyc7XHJcbiAgICBpZiAoc3RhdGljVHlwZSA9PT0gJ3N1Y2Nlc3MnKSB7XHJcbiAgICAgICAgc3ZnSWNvbiA9IG5vdGlmbGl4UmVwb3J0U3ZnU3VjY2VzcyhuZXdSZXBvcnRTZXR0aW5ncy5zdmdTaXplLCB0aGVUeXBlLnN2Z0NvbG9yKTtcclxuICAgIH0gZWxzZSBpZiAoc3RhdGljVHlwZSA9PT0gJ2ZhaWx1cmUnKSB7XHJcbiAgICAgICAgc3ZnSWNvbiA9IG5vdGlmbGl4UmVwb3J0U3ZnRmFpbHVyZShuZXdSZXBvcnRTZXR0aW5ncy5zdmdTaXplLCB0aGVUeXBlLnN2Z0NvbG9yKTtcclxuICAgIH0gZWxzZSBpZiAoc3RhdGljVHlwZSA9PT0gJ3dhcm5pbmcnKSB7XHJcbiAgICAgICAgc3ZnSWNvbiA9IG5vdGlmbGl4UmVwb3J0U3ZnV2FybmluZyhuZXdSZXBvcnRTZXR0aW5ncy5zdmdTaXplLCB0aGVUeXBlLnN2Z0NvbG9yKTtcclxuICAgIH0gZWxzZSBpZiAoc3RhdGljVHlwZSA9PT0gJ2luZm8nKSB7XHJcbiAgICAgICAgc3ZnSWNvbiA9IG5vdGlmbGl4UmVwb3J0U3ZnSW5mbyhuZXdSZXBvcnRTZXR0aW5ncy5zdmdTaXplLCB0aGVUeXBlLnN2Z0NvbG9yKTtcclxuICAgIH1cclxuICAgIC8vIHN2ZyBpY29uIG9mZlxyXG5cclxuICAgIC8vIHJlcG9ydCBodG1sIG9uXHJcbiAgICBudGZseFJlcG9ydFdyYXAuaW5uZXJIVE1MID0gYCR7cmVwb3J0T3ZlcmxheX1cclxuICAgIDxkaXYgY2xhc3M9XCIke25ld1JlcG9ydFNldHRpbmdzLmNsYXNzTmFtZX0tY29udGVudCAkeyhuZXdSZXBvcnRTZXR0aW5ncy5jc3NBbmltYXRpb24gPyAnd2l0aC1hbmltYXRpb24nIDogJycpfSBueC0ke25ld1JlcG9ydFNldHRpbmdzLmNzc0FuaW1hdGlvblN0eWxlfVwiIHN0eWxlPVwiYmFja2dyb3VuZDoke25ld1JlcG9ydFNldHRpbmdzLmJhY2tncm91bmRDb2xvcn07IGFuaW1hdGlvbi1kdXJhdGlvbjoke25ld1JlcG9ydFNldHRpbmdzLmNzc0FuaW1hdGlvbkR1cmF0aW9ufW1zO1wiPlxyXG4gICAgPGRpdiBzdHlsZT1cIndpZHRoOiR7bmV3UmVwb3J0U2V0dGluZ3Muc3ZnU2l6ZX07IGhlaWdodDoke25ld1JlcG9ydFNldHRpbmdzLnN2Z1NpemV9O1wiIGNsYXNzPVwiJHtuZXdSZXBvcnRTZXR0aW5ncy5jbGFzc05hbWV9LWljb25cIj4ke3N2Z0ljb259PC9kaXY+XHJcbiAgICA8aDUgY2xhc3M9XCIke25ld1JlcG9ydFNldHRpbmdzLmNsYXNzTmFtZX0tdGl0bGVcIiBzdHlsZT1cImZvbnQtd2VpZ2h0OjUwMDsgZm9udC1zaXplOiR7bmV3UmVwb3J0U2V0dGluZ3MudGl0bGVGb250U2l6ZX07IGNvbG9yOiR7dGhlVHlwZS50aXRsZUNvbG9yfTtcIj4ke3RpdGxlfTwvaDU+XHJcbiAgICA8cCBjbGFzcz1cIiR7bmV3UmVwb3J0U2V0dGluZ3MuY2xhc3NOYW1lfS1tZXNzYWdlXCIgc3R5bGU9XCJmb250LXNpemU6JHtuZXdSZXBvcnRTZXR0aW5ncy5tZXNzYWdlRm9udFNpemV9OyBjb2xvcjoke3RoZVR5cGUubWVzc2FnZUNvbG9yfTtcIj4ke21lc3NhZ2V9PC9wPlxyXG4gICAgPGEgaWQ9XCJOWFJlcG9ydEJ1dHRvblwiIGNsYXNzPVwiJHtuZXdSZXBvcnRTZXR0aW5ncy5jbGFzc05hbWV9LWJ1dHRvblwiIHN0eWxlPVwiZm9udC13ZWlnaHQ6NTAwOyBmb250LXNpemU6JHtuZXdSZXBvcnRTZXR0aW5ncy5idXR0b25Gb250U2l6ZX07IGJhY2tncm91bmQ6JHt0aGVUeXBlLmJ1dHRvbkJhY2tncm91bmR9OyBjb2xvcjoke3RoZVR5cGUuYnV0dG9uQ29sb3J9O1wiPiR7YnV0dG9uVGV4dH08L2E+XHJcbiAgICA8L2Rpdj5gO1xyXG4gICAgLy8gcmVwb3J0IGh0bWwgb2ZmXHJcblxyXG4gICAgaWYgKCFkb2N1bWVudC5nZXRFbGVtZW50QnlJZChudGZseFJlcG9ydFdyYXAuaWQpKSB7IC8vIGlmIG5vIHJlcG9ydFxyXG5cclxuICAgICAgICBkb2NCb2R5LmFwcGVuZENoaWxkKG50Zmx4UmVwb3J0V3JhcCk7IC8vIGFwcGVuZFxyXG5cclxuICAgICAgICAvLyB2ZXJ0aWNhbCBhbGlnbiBvbiAgICAgICAgICAgIFxyXG4gICAgICAgIGxldCB3aW5kb3dIID0gTWF0aC5yb3VuZCh3aW5kb3cuaW5uZXJIZWlnaHQpO1xyXG4gICAgICAgIGxldCByZXBvcnRIID0gTWF0aC5yb3VuZChkb2N1bWVudC5nZXRFbGVtZW50QnlJZChudGZseFJlcG9ydFdyYXAuaWQpLm9mZnNldEhlaWdodCk7XHJcbiAgICAgICAgbnRmbHhSZXBvcnRXcmFwLnN0eWxlLnRvcCA9IGAke3BhcnNlSW50KCh3aW5kb3dIIC0gcmVwb3J0SCkgLyAyKS50b1N0cmluZygpfXB4YDtcclxuICAgICAgICAvLyB2ZXJ0aWNhbCBhbGlnbiBvZmZcclxuXHJcbiAgICAgICAgLy8gY2FsbGJhY2sgb25cclxuICAgICAgICBsZXQgZ2V0UmVwb3J0V3JhcCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKG50Zmx4UmVwb3J0V3JhcC5pZCk7XHJcbiAgICAgICAgbGV0IHJlcG9ydEJ1dHRvbiA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdOWFJlcG9ydEJ1dHRvbicpO1xyXG4gICAgICAgIHJlcG9ydEJ1dHRvbi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGZ1bmN0aW9uICgpIHtcclxuXHJcbiAgICAgICAgICAgIC8vIGlmIGNhbGxiYWNrIG9uXHJcbiAgICAgICAgICAgIGlmIChidXR0b25DYWxsYmFjayAmJiB0eXBlb2YgYnV0dG9uQ2FsbGJhY2sgPT09ICdmdW5jdGlvbicpIHtcclxuICAgICAgICAgICAgICAgIGJ1dHRvbkNhbGxiYWNrKCk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgLy8gaWYgY2FsbGJhY2sgb2ZmXHJcblxyXG4gICAgICAgICAgICAvLyByZW1vdmUgZWxlbWVudCBvblxyXG4gICAgICAgICAgICBnZXRSZXBvcnRXcmFwLmNsYXNzTGlzdC5hZGQoJ3JlbW92ZScpO1xyXG5cclxuICAgICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICBnZXRSZXBvcnRXcmFwLnBhcmVudE5vZGUucmVtb3ZlQ2hpbGQoZ2V0UmVwb3J0V3JhcCk7XHJcbiAgICAgICAgICAgIH0sIG5ld1JlcG9ydFNldHRpbmdzLmNzc0FuaW1hdGlvbkR1cmF0aW9uKTtcclxuICAgICAgICAgICAgLy8gcmVtb3ZlIGVsZW1lbnQgb2ZmXHJcblxyXG4gICAgICAgIH0pO1xyXG4gICAgICAgIC8vIGNhbGxiYWNrIG9mZlxyXG5cclxuICAgIH1cclxuICAgIC8vIHJlcG9ydCB3cmFwIG9mZlxyXG5cclxufVxyXG4vLyBOb3RpZmxpeDogUmVwb3J0IFNpbmdsZSBvZmZcclxuXHJcbi8vIE5vdGlmbGl4OiBSZXBvcnQgU1ZHIFN1Y2Nlc3Mgb25cclxuY29uc3Qgbm90aWZsaXhSZXBvcnRTdmdTdWNjZXNzID0gZnVuY3Rpb24gKHdpZHRoLCBjb2xvcikge1xyXG5cclxuICAgIGlmICghd2lkdGgpIHsgd2lkdGggPSAnMTEwcHgnOyB9XHJcbiAgICBpZiAoIWNvbG9yKSB7IGNvbG9yID0gJyMwMGI0NjInOyB9XHJcblxyXG4gICAgY29uc3QgcmVwb3J0U3ZnU3VjY2VzcyA9IGA8c3ZnIGlkPVwiTlhSZXBvcnRTdWNjZXNzXCIgZmlsbD1cIiR7Y29sb3J9XCIgd2lkdGg9XCIke3dpZHRofVwiIGhlaWdodD1cIiR7d2lkdGh9XCIgeG1sbnM9XCJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2Z1wiIHhtbDpzcGFjZT1cInByZXNlcnZlXCIgdmVyc2lvbj1cIjEuMVwiIHN0eWxlPVwic2hhcGUtcmVuZGVyaW5nOmdlb21ldHJpY1ByZWNpc2lvbjsgdGV4dC1yZW5kZXJpbmc6Z2VvbWV0cmljUHJlY2lzaW9uOyBpbWFnZS1yZW5kZXJpbmc6b3B0aW1pemVRdWFsaXR5OyBmaWxsLXJ1bGU6ZXZlbm9kZDsgY2xpcC1ydWxlOmV2ZW5vZGRcIiB2aWV3Qm94PVwiMCAwIDEyMCAxMjBcIiB4bWxuczp4bGluaz1cImh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmtcIj48c3R5bGU+QC13ZWJraXQta2V5ZnJhbWVzIE5YUmVwb3J0U3VjY2VzczUtYW5pbWF0aW9uezAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNTcuN3B4KSBzY2FsZSgwLjUsIDAuNSkgdHJhbnNsYXRlKC02MHB4LCAtNTcuN3B4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA1Ny43cHgpIHNjYWxlKDAuNSwgMC41KSB0cmFuc2xhdGUoLTYwcHgsIC01Ny43cHgpO301MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA1Ny43cHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTU3LjdweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNTcuN3B4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC01Ny43cHgpO302MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA1Ny43cHgpIHNjYWxlKDAuOTUsIDAuOTUpIHRyYW5zbGF0ZSgtNjBweCwgLTU3LjdweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNTcuN3B4KSBzY2FsZSgwLjk1LCAwLjk1KSB0cmFuc2xhdGUoLTYwcHgsIC01Ny43cHgpO30xMDAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNTcuN3B4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC01Ny43cHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDU3LjdweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNTcuN3B4KTt9fUBrZXlmcmFtZXMgTlhSZXBvcnRTdWNjZXNzNS1hbmltYXRpb257MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA1Ny43cHgpIHNjYWxlKDAuNSwgMC41KSB0cmFuc2xhdGUoLTYwcHgsIC01Ny43cHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDU3LjdweCkgc2NhbGUoMC41LCAwLjUpIHRyYW5zbGF0ZSgtNjBweCwgLTU3LjdweCk7fTUwJXstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDU3LjdweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNTcuN3B4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA1Ny43cHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTU3LjdweCk7fTYwJXstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDU3LjdweCkgc2NhbGUoMC45NSwgMC45NSkgdHJhbnNsYXRlKC02MHB4LCAtNTcuN3B4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA1Ny43cHgpIHNjYWxlKDAuOTUsIDAuOTUpIHRyYW5zbGF0ZSgtNjBweCwgLTU3LjdweCk7fTEwMCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA1Ny43cHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTU3LjdweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNTcuN3B4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC01Ny43cHgpO319QC13ZWJraXQta2V5ZnJhbWVzIE5YUmVwb3J0U3VjY2VzczYtYW5pbWF0aW9uezAle29wYWNpdHk6IDA7fTUwJXtvcGFjaXR5OiAxO30xMDAle29wYWNpdHk6IDE7fX1Aa2V5ZnJhbWVzIE5YUmVwb3J0U3VjY2VzczYtYW5pbWF0aW9uezAle29wYWNpdHk6IDA7fTUwJXtvcGFjaXR5OiAxO30xMDAle29wYWNpdHk6IDE7fX1ALXdlYmtpdC1rZXlmcmFtZXMgTlhSZXBvcnRTdWNjZXNzNC1hbmltYXRpb257MCV7b3BhY2l0eTogMDt9NDAle29wYWNpdHk6IDE7fTEwMCV7b3BhY2l0eTogMTt9fUBrZXlmcmFtZXMgTlhSZXBvcnRTdWNjZXNzNC1hbmltYXRpb257MCV7b3BhY2l0eTogMDt9NDAle29wYWNpdHk6IDE7fTEwMCV7b3BhY2l0eTogMTt9fUAtd2Via2l0LWtleWZyYW1lcyBOWFJlcG9ydFN1Y2Nlc3MzLWFuaW1hdGlvbnswJXstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDAuNSwgMC41KSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgwLjUsIDAuNSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7fTQwJXstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO302MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgwLjk1LCAwLjk1KSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgwLjk1LCAwLjk1KSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9MTAwJXstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO319QGtleWZyYW1lcyBOWFJlcG9ydFN1Y2Nlc3MzLWFuaW1hdGlvbnswJXstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDAuNSwgMC41KSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgwLjUsIDAuNSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7fTQwJXstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO302MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgwLjk1LCAwLjk1KSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgwLjk1LCAwLjk1KSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9MTAwJXstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO319I05YUmVwb3J0U3VjY2VzcyAqey13ZWJraXQtYW5pbWF0aW9uLWR1cmF0aW9uOiAxLjJzO2FuaW1hdGlvbi1kdXJhdGlvbjogMS4yczstd2Via2l0LWFuaW1hdGlvbi10aW1pbmctZnVuY3Rpb246IGN1YmljLWJlemllcigwLCAwLCAxLCAxKTthbmltYXRpb24tdGltaW5nLWZ1bmN0aW9uOiBjdWJpYy1iZXppZXIoMCwgMCwgMSwgMSk7fSNOWFJlcG9ydFN1Y2Nlc3M0e2ZpbGw6IGluaGVyaXQ7LXdlYmtpdC1hbmltYXRpb24tbmFtZTogTlhSZXBvcnRTdWNjZXNzNC1hbmltYXRpb247YW5pbWF0aW9uLW5hbWU6IE5YUmVwb3J0U3VjY2VzczQtYW5pbWF0aW9uOy13ZWJraXQtYW5pbWF0aW9uLXRpbWluZy1mdW5jdGlvbjogY3ViaWMtYmV6aWVyKDAuNDIsIDAsIDAuNTgsIDEpO2FuaW1hdGlvbi10aW1pbmctZnVuY3Rpb246IGN1YmljLWJlemllcigwLjQyLCAwLCAwLjU4LCAxKTtvcGFjaXR5OiAxO30jTlhSZXBvcnRTdWNjZXNzNntmaWxsOiBpbmhlcml0Oy13ZWJraXQtYW5pbWF0aW9uLW5hbWU6IE5YUmVwb3J0U3VjY2VzczYtYW5pbWF0aW9uO2FuaW1hdGlvbi1uYW1lOiBOWFJlcG9ydFN1Y2Nlc3M2LWFuaW1hdGlvbjtvcGFjaXR5OiAxOy13ZWJraXQtYW5pbWF0aW9uLXRpbWluZy1mdW5jdGlvbjogY3ViaWMtYmV6aWVyKDAuNDIsIDAsIDAuNTgsIDEpO2FuaW1hdGlvbi10aW1pbmctZnVuY3Rpb246IGN1YmljLWJlemllcigwLjQyLCAwLCAwLjU4LCAxKTt9I05YUmVwb3J0U3VjY2VzczN7LXdlYmtpdC1hbmltYXRpb24tbmFtZTogTlhSZXBvcnRTdWNjZXNzMy1hbmltYXRpb247YW5pbWF0aW9uLW5hbWU6IE5YUmVwb3J0U3VjY2VzczMtYW5pbWF0aW9uOy13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7LXdlYmtpdC1hbmltYXRpb24tdGltaW5nLWZ1bmN0aW9uOiBjdWJpYy1iZXppZXIoMC40MiwgMCwgMC41OCwgMSk7YW5pbWF0aW9uLXRpbWluZy1mdW5jdGlvbjogY3ViaWMtYmV6aWVyKDAuNDIsIDAsIDAuNTgsIDEpO30jTlhSZXBvcnRTdWNjZXNzNXstd2Via2l0LWFuaW1hdGlvbi1uYW1lOiBOWFJlcG9ydFN1Y2Nlc3M1LWFuaW1hdGlvbjthbmltYXRpb24tbmFtZTogTlhSZXBvcnRTdWNjZXNzNS1hbmltYXRpb247LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA1Ny43cHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTU3LjdweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNTcuN3B4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC01Ny43cHgpOy13ZWJraXQtYW5pbWF0aW9uLXRpbWluZy1mdW5jdGlvbjogY3ViaWMtYmV6aWVyKDAuNDIsIDAsIDAuNTgsIDEpO2FuaW1hdGlvbi10aW1pbmctZnVuY3Rpb246IGN1YmljLWJlemllcigwLjQyLCAwLCAwLjU4LCAxKTt9PC9zdHlsZT48ZyBpZD1cIk5YUmVwb3J0U3VjY2VzczFcIj48ZyBpZD1cIk5YUmVwb3J0U3VjY2VzczJcIj48ZyBpZD1cIk5YUmVwb3J0U3VjY2VzczNcIiBkYXRhLWFuaW1hdG9yLWdyb3VwPVwidHJ1ZVwiIGRhdGEtYW5pbWF0b3ItdHlwZT1cIjJcIj48cGF0aCBkPVwiTTYwIDExNS4zOGMtMzAuNTQsMCAtNTUuMzgsLTI0Ljg0IC01NS4zOCwtNTUuMzggMCwtMzAuNTQgMjQuODQsLTU1LjM4IDU1LjM4LC01NS4zOCAzMC41NCwwIDU1LjM4LDI0Ljg0IDU1LjM4LDU1LjM4IDAsMzAuNTQgLTI0Ljg0LDU1LjM4IC01NS4zOCw1NS4zOHptMCAtMTE1LjM4Yy0zMy4wOCwwIC02MCwyNi45MiAtNjAsNjAgMCwzMy4wOCAyNi45Miw2MCA2MCw2MCAzMy4wOCwwIDYwLC0yNi45MiA2MCwtNjAgMCwtMzMuMDggLTI2LjkyLC02MCAtNjAsLTYwelwiIGlkPVwiTlhSZXBvcnRTdWNjZXNzNFwiLz48L2c+PGcgaWQ9XCJOWFJlcG9ydFN1Y2Nlc3M1XCIgZGF0YS1hbmltYXRvci1ncm91cD1cInRydWVcIiBkYXRhLWFuaW1hdG9yLXR5cGU9XCIyXCI+PHBhdGggZD1cIk04OC4yNyAzNS4zOWwtMzUuNDcgMzkuOSAtMjEuMzcgLTE3LjA5Yy0wLjk4LC0wLjgxIC0yLjQ0LC0wLjYzIC0zLjI0LDAuMzYgLTAuNzksMC45OSAtMC42MywyLjQ0IDAuMzYsMy4yNGwyMy4wOCAxOC40NmMwLjQzLDAuMzQgMC45MywwLjUxIDEuNDQsMC41MSAwLjY0LDAgMS4yNywtMC4yNiAxLjc0LC0wLjc4bDM2LjkxIC00MS41M2MwLjg2LC0wLjk2IDAuNzYsLTIuNDIgLTAuMTksLTMuMjYgLTAuOTUsLTAuODYgLTIuNDEsLTAuNzcgLTMuMjYsMC4xOXpcIiBpZD1cIk5YUmVwb3J0U3VjY2VzczZcIi8+PC9nPjwvZz48L2c+PC9zdmc+YDtcclxuXHJcbiAgICByZXR1cm4gcmVwb3J0U3ZnU3VjY2VzcztcclxuXHJcbn1cclxuLy8gTm90aWZsaXg6IFJlcG9ydCBTVkcgU3VjY2VzcyBvZmZcclxuXHJcbi8vIE5vdGlmbGl4OiBSZXBvcnQgU1ZHIEZhaWx1cmUgb25cclxuY29uc3Qgbm90aWZsaXhSZXBvcnRTdmdGYWlsdXJlID0gZnVuY3Rpb24gKHdpZHRoLCBjb2xvcikge1xyXG5cclxuICAgIGlmICghd2lkdGgpIHsgd2lkdGggPSAnMTEwcHgnOyB9XHJcbiAgICBpZiAoIWNvbG9yKSB7IGNvbG9yID0gJyNmNDQzMzYnOyB9XHJcblxyXG4gICAgY29uc3QgcmVwb3J0U3ZnRmFpbHVyZSA9IGA8c3ZnIGlkPVwiTlhSZXBvcnRGYWlsdXJlXCIgZmlsbD1cIiR7Y29sb3J9XCIgd2lkdGg9XCIke3dpZHRofVwiIGhlaWdodD1cIiR7d2lkdGh9XCIgeG1sbnM9XCJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2Z1wiIHhtbDpzcGFjZT1cInByZXNlcnZlXCIgdmVyc2lvbj1cIjEuMVwiIHN0eWxlPVwic2hhcGUtcmVuZGVyaW5nOmdlb21ldHJpY1ByZWNpc2lvbjsgdGV4dC1yZW5kZXJpbmc6Z2VvbWV0cmljUHJlY2lzaW9uOyBpbWFnZS1yZW5kZXJpbmc6b3B0aW1pemVRdWFsaXR5OyBmaWxsLXJ1bGU6ZXZlbm9kZDsgY2xpcC1ydWxlOmV2ZW5vZGRcIiB2aWV3Qm94PVwiMCAwIDEyMCAxMjBcIiB4bWxuczp4bGluaz1cImh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmtcIj48c3R5bGU+QC13ZWJraXQta2V5ZnJhbWVzIE5YUmVwb3J0RmFpbHVyZTQtYW5pbWF0aW9uezAle29wYWNpdHk6IDA7fTQwJXtvcGFjaXR5OiAxO30xMDAle29wYWNpdHk6IDE7fX1Aa2V5ZnJhbWVzIE5YUmVwb3J0RmFpbHVyZTQtYW5pbWF0aW9uezAle29wYWNpdHk6IDA7fTQwJXtvcGFjaXR5OiAxO30xMDAle29wYWNpdHk6IDE7fX1ALXdlYmtpdC1rZXlmcmFtZXMgTlhSZXBvcnRGYWlsdXJlMy1hbmltYXRpb257MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgwLjUsIDAuNSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC41LCAwLjUpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO300MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9NjAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC45NSwgMC45NSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC45NSwgMC45NSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7fTEwMCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9fUBrZXlmcmFtZXMgTlhSZXBvcnRGYWlsdXJlMy1hbmltYXRpb257MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgwLjUsIDAuNSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC41LCAwLjUpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO300MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9NjAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC45NSwgMC45NSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC45NSwgMC45NSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7fTEwMCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9fUAtd2Via2l0LWtleWZyYW1lcyBOWFJlcG9ydEZhaWx1cmU1LWFuaW1hdGlvbnswJXstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDAuNSwgMC41KSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgwLjUsIDAuNSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7fTUwJXstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO302MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgwLjk1LCAwLjk1KSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgwLjk1LCAwLjk1KSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9MTAwJXstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO319QGtleWZyYW1lcyBOWFJlcG9ydEZhaWx1cmU1LWFuaW1hdGlvbnswJXstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDAuNSwgMC41KSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgwLjUsIDAuNSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7fTUwJXstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO302MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgwLjk1LCAwLjk1KSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgwLjk1LCAwLjk1KSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9MTAwJXstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO319QC13ZWJraXQta2V5ZnJhbWVzIE5YUmVwb3J0RmFpbHVyZTYtYW5pbWF0aW9uezAle29wYWNpdHk6IDA7fTUwJXtvcGFjaXR5OiAxO30xMDAle29wYWNpdHk6IDE7fX1Aa2V5ZnJhbWVzIE5YUmVwb3J0RmFpbHVyZTYtYW5pbWF0aW9uezAle29wYWNpdHk6IDA7fTUwJXtvcGFjaXR5OiAxO30xMDAle29wYWNpdHk6IDE7fX0jTlhSZXBvcnRGYWlsdXJlICp7LXdlYmtpdC1hbmltYXRpb24tZHVyYXRpb246IDEuMnM7YW5pbWF0aW9uLWR1cmF0aW9uOiAxLjJzOy13ZWJraXQtYW5pbWF0aW9uLXRpbWluZy1mdW5jdGlvbjogY3ViaWMtYmV6aWVyKDAsIDAsIDEsIDEpO2FuaW1hdGlvbi10aW1pbmctZnVuY3Rpb246IGN1YmljLWJlemllcigwLCAwLCAxLCAxKTt9I05YUmVwb3J0RmFpbHVyZTZ7ZmlsbDppbmhlcml0Oy13ZWJraXQtYW5pbWF0aW9uLW5hbWU6IE5YUmVwb3J0RmFpbHVyZTYtYW5pbWF0aW9uO2FuaW1hdGlvbi1uYW1lOiBOWFJlcG9ydEZhaWx1cmU2LWFuaW1hdGlvbjstd2Via2l0LWFuaW1hdGlvbi10aW1pbmctZnVuY3Rpb246IGN1YmljLWJlemllcigwLjQyLCAwLCAwLjU4LCAxKTthbmltYXRpb24tdGltaW5nLWZ1bmN0aW9uOiBjdWJpYy1iZXppZXIoMC40MiwgMCwgMC41OCwgMSk7b3BhY2l0eTogMTt9I05YUmVwb3J0RmFpbHVyZTV7LXdlYmtpdC1hbmltYXRpb24tbmFtZTogTlhSZXBvcnRGYWlsdXJlNS1hbmltYXRpb247YW5pbWF0aW9uLW5hbWU6IE5YUmVwb3J0RmFpbHVyZTUtYW5pbWF0aW9uOy13ZWJraXQtYW5pbWF0aW9uLXRpbWluZy1mdW5jdGlvbjogY3ViaWMtYmV6aWVyKDAuNDIsIDAsIDAuNTgsIDEpO2FuaW1hdGlvbi10aW1pbmctZnVuY3Rpb246IGN1YmljLWJlemllcigwLjQyLCAwLCAwLjU4LCAxKTstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO30jTlhSZXBvcnRGYWlsdXJlM3std2Via2l0LWFuaW1hdGlvbi1uYW1lOiBOWFJlcG9ydEZhaWx1cmUzLWFuaW1hdGlvbjthbmltYXRpb24tbmFtZTogTlhSZXBvcnRGYWlsdXJlMy1hbmltYXRpb247LXdlYmtpdC1hbmltYXRpb24tdGltaW5nLWZ1bmN0aW9uOiBjdWJpYy1iZXppZXIoMC40MiwgMCwgMC41OCwgMSk7YW5pbWF0aW9uLXRpbWluZy1mdW5jdGlvbjogY3ViaWMtYmV6aWVyKDAuNDIsIDAsIDAuNTgsIDEpOy13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7fSNOWFJlcG9ydEZhaWx1cmU0e2ZpbGw6aW5oZXJpdDstd2Via2l0LWFuaW1hdGlvbi1uYW1lOiBOWFJlcG9ydEZhaWx1cmU0LWFuaW1hdGlvbjthbmltYXRpb24tbmFtZTogTlhSZXBvcnRGYWlsdXJlNC1hbmltYXRpb247LXdlYmtpdC1hbmltYXRpb24tdGltaW5nLWZ1bmN0aW9uOiBjdWJpYy1iZXppZXIoMC40MiwgMCwgMC41OCwgMSk7YW5pbWF0aW9uLXRpbWluZy1mdW5jdGlvbjogY3ViaWMtYmV6aWVyKDAuNDIsIDAsIDAuNTgsIDEpO29wYWNpdHk6IDE7fTwvc3R5bGU+PGcgaWQ9XCJOWFJlcG9ydEZhaWx1cmUxXCI+PGcgaWQ9XCJOWFJlcG9ydEZhaWx1cmUyXCI+PGcgaWQ9XCJOWFJlcG9ydEZhaWx1cmUzXCIgZGF0YS1hbmltYXRvci1ncm91cD1cInRydWVcIiBkYXRhLWFuaW1hdG9yLXR5cGU9XCIyXCI+PHBhdGggZD1cIk00LjM1IDM0Ljk1YzAsLTE2LjgyIDEzLjc4LC0zMC42IDMwLjYsLTMwLjZsNTAuMSAwYzE2LjgyLDAgMzAuNiwxMy43OCAzMC42LDMwLjZsMCA1MC4xYzAsMTYuODIgLTEzLjc4LDMwLjYgLTMwLjYsMzAuNmwtNTAuMSAwYy0xNi44MiwwIC0zMC42LC0xMy43OCAtMzAuNiwtMzAuNmwwIC01MC4xem0zMC42IDg1LjA1bDUwLjEgMGMxOS4yMiwwIDM0Ljk1LC0xNS43MyAzNC45NSwtMzQuOTVsMCAtNTAuMWMwLC0xOS4yMiAtMTUuNzMsLTM0Ljk1IC0zNC45NSwtMzQuOTVsLTUwLjEgMGMtMTkuMjIsMCAtMzQuOTUsMTUuNzMgLTM0Ljk1LDM0Ljk1bDAgNTAuMWMwLDE5LjIyIDE1LjczLDM0Ljk1IDM0Ljk1LDM0Ljk1elwiIGlkPVwiTlhSZXBvcnRGYWlsdXJlNFwiLz48L2c+PGcgaWQ9XCJOWFJlcG9ydEZhaWx1cmU1XCIgZGF0YS1hbmltYXRvci1ncm91cD1cInRydWVcIiBkYXRhLWFuaW1hdG9yLXR5cGU9XCIyXCI+PHBhdGggZD1cIk04Mi40IDM3LjZjLTAuOSwtMC45IC0yLjM3LC0wLjkgLTMuMjcsMGwtMTkuMTMgMTkuMTMgLTE5LjE0IC0xOS4xM2MtMC45LC0wLjkgLTIuMzYsLTAuOSAtMy4yNiwwIC0wLjksMC45IC0wLjksMi4zNSAwLDMuMjZsMTkuMTMgMTkuMTQgLTE5LjEzIDE5LjEzYy0wLjksMC45IC0wLjksMi4zNyAwLDMuMjcgMC40NSwwLjQ1IDEuMDQsMC42OCAxLjYzLDAuNjggMC41OSwwIDEuMTgsLTAuMjMgMS42MywtMC42OGwxOS4xNCAtMTkuMTQgMTkuMTMgMTkuMTRjMC40NSwwLjQ1IDEuMDUsMC42OCAxLjY0LDAuNjggMC41OCwwIDEuMTgsLTAuMjMgMS42MywtMC42OCAwLjksLTAuOSAwLjksLTIuMzcgMCwtMy4yN2wtMTkuMTQgLTE5LjEzIDE5LjE0IC0xOS4xNGMwLjksLTAuOTEgMC45LC0yLjM2IDAsLTMuMjZ6XCIgaWQ9XCJOWFJlcG9ydEZhaWx1cmU2XCIvPjwvZz48L2c+PC9nPjwvc3ZnPmA7XHJcblxyXG4gICAgcmV0dXJuIHJlcG9ydFN2Z0ZhaWx1cmU7XHJcbn1cclxuLy8gTm90aWZsaXg6IFJlcG9ydCBTVkcgRmFpbHVyZSBvZmZcclxuXHJcbi8vIE5vdGlmbGl4OiBSZXBvcnQgU1ZHIFdhcm5pbmcgb25cclxuY29uc3Qgbm90aWZsaXhSZXBvcnRTdmdXYXJuaW5nID0gZnVuY3Rpb24gKHdpZHRoLCBjb2xvcikge1xyXG5cclxuICAgIGlmICghd2lkdGgpIHsgd2lkdGggPSAnMTEwcHgnOyB9XHJcbiAgICBpZiAoIWNvbG9yKSB7IGNvbG9yID0gJyNmMmJkMWQnOyB9XHJcblxyXG4gICAgY29uc3QgcmVwb3J0U3ZnV2FybmluZyA9IGA8c3ZnIGlkPVwiTlhSZXBvcnRXYXJuaW5nXCIgZmlsbD1cIiR7Y29sb3J9XCIgd2lkdGg9XCIke3dpZHRofVwiIGhlaWdodD1cIiR7d2lkdGh9XCIgeG1sbnM9XCJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2Z1wiIHhtbDpzcGFjZT1cInByZXNlcnZlXCIgdmVyc2lvbj1cIjEuMVwiIHN0eWxlPVwic2hhcGUtcmVuZGVyaW5nOmdlb21ldHJpY1ByZWNpc2lvbjsgdGV4dC1yZW5kZXJpbmc6Z2VvbWV0cmljUHJlY2lzaW9uOyBpbWFnZS1yZW5kZXJpbmc6b3B0aW1pemVRdWFsaXR5OyBmaWxsLXJ1bGU6ZXZlbm9kZDsgY2xpcC1ydWxlOmV2ZW5vZGRcIiB2aWV3Qm94PVwiMCAwIDEyMCAxMjBcIiB4bWxuczp4bGluaz1cImh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmtcIj48c3R5bGU+QC13ZWJraXQta2V5ZnJhbWVzIE5YUmVwb3J0V2FybmluZzMtYW5pbWF0aW9uezAle29wYWNpdHk6IDA7fTQwJXtvcGFjaXR5OiAxO30xMDAle29wYWNpdHk6IDE7fX1Aa2V5ZnJhbWVzIE5YUmVwb3J0V2FybmluZzMtYW5pbWF0aW9uezAle29wYWNpdHk6IDA7fTQwJXtvcGFjaXR5OiAxO30xMDAle29wYWNpdHk6IDE7fX1ALXdlYmtpdC1rZXlmcmFtZXMgTlhSZXBvcnRXYXJuaW5nMi1hbmltYXRpb257MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgwLjUsIDAuNSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC41LCAwLjUpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO300MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9NjAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC45NSwgMC45NSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC45NSwgMC45NSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7fTEwMCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9fUBrZXlmcmFtZXMgTlhSZXBvcnRXYXJuaW5nMi1hbmltYXRpb257MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgwLjUsIDAuNSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC41LCAwLjUpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO300MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9NjAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC45NSwgMC45NSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC45NSwgMC45NSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7fTEwMCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9fUAtd2Via2l0LWtleWZyYW1lcyBOWFJlcG9ydFdhcm5pbmc0LWFuaW1hdGlvbnswJXstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDY2LjZweCkgc2NhbGUoMC41LCAwLjUpIHRyYW5zbGF0ZSgtNjBweCwgLTY2LjZweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjYuNnB4KSBzY2FsZSgwLjUsIDAuNSkgdHJhbnNsYXRlKC02MHB4LCAtNjYuNnB4KTt9NTAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjYuNnB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02Ni42cHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDY2LjZweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjYuNnB4KTt9NjAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjYuNnB4KSBzY2FsZSgwLjk1LCAwLjk1KSB0cmFuc2xhdGUoLTYwcHgsIC02Ni42cHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDY2LjZweCkgc2NhbGUoMC45NSwgMC45NSkgdHJhbnNsYXRlKC02MHB4LCAtNjYuNnB4KTt9MTAwJXstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDY2LjZweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjYuNnB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2Ni42cHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTY2LjZweCk7fX1Aa2V5ZnJhbWVzIE5YUmVwb3J0V2FybmluZzQtYW5pbWF0aW9uezAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjYuNnB4KSBzY2FsZSgwLjUsIDAuNSkgdHJhbnNsYXRlKC02MHB4LCAtNjYuNnB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2Ni42cHgpIHNjYWxlKDAuNSwgMC41KSB0cmFuc2xhdGUoLTYwcHgsIC02Ni42cHgpO301MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2Ni42cHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTY2LjZweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjYuNnB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02Ni42cHgpO302MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2Ni42cHgpIHNjYWxlKDAuOTUsIDAuOTUpIHRyYW5zbGF0ZSgtNjBweCwgLTY2LjZweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjYuNnB4KSBzY2FsZSgwLjk1LCAwLjk1KSB0cmFuc2xhdGUoLTYwcHgsIC02Ni42cHgpO30xMDAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjYuNnB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02Ni42cHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDY2LjZweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjYuNnB4KTt9fUAtd2Via2l0LWtleWZyYW1lcyBOWFJlcG9ydFdhcm5pbmc1LWFuaW1hdGlvbnswJXtvcGFjaXR5OiAwO301MCV7b3BhY2l0eTogMTt9MTAwJXtvcGFjaXR5OiAxO319QGtleWZyYW1lcyBOWFJlcG9ydFdhcm5pbmc1LWFuaW1hdGlvbnswJXtvcGFjaXR5OiAwO301MCV7b3BhY2l0eTogMTt9MTAwJXtvcGFjaXR5OiAxO319I05YUmVwb3J0V2FybmluZyAqey13ZWJraXQtYW5pbWF0aW9uLWR1cmF0aW9uOiAxLjJzO2FuaW1hdGlvbi1kdXJhdGlvbjogMS4yczstd2Via2l0LWFuaW1hdGlvbi10aW1pbmctZnVuY3Rpb246IGN1YmljLWJlemllcigwLCAwLCAxLCAxKTthbmltYXRpb24tdGltaW5nLWZ1bmN0aW9uOiBjdWJpYy1iZXppZXIoMCwgMCwgMSwgMSk7fSNOWFJlcG9ydFdhcm5pbmcze2ZpbGw6IGluaGVyaXQ7LXdlYmtpdC1hbmltYXRpb24tbmFtZTogTlhSZXBvcnRXYXJuaW5nMy1hbmltYXRpb247YW5pbWF0aW9uLW5hbWU6IE5YUmVwb3J0V2FybmluZzMtYW5pbWF0aW9uOy13ZWJraXQtYW5pbWF0aW9uLXRpbWluZy1mdW5jdGlvbjogY3ViaWMtYmV6aWVyKDAuNDIsIDAsIDAuNTgsIDEpO2FuaW1hdGlvbi10aW1pbmctZnVuY3Rpb246IGN1YmljLWJlemllcigwLjQyLCAwLCAwLjU4LCAxKTtvcGFjaXR5OiAxO30jTlhSZXBvcnRXYXJuaW5nNXtmaWxsOiBpbmhlcml0Oy13ZWJraXQtYW5pbWF0aW9uLW5hbWU6IE5YUmVwb3J0V2FybmluZzUtYW5pbWF0aW9uO2FuaW1hdGlvbi1uYW1lOiBOWFJlcG9ydFdhcm5pbmc1LWFuaW1hdGlvbjstd2Via2l0LWFuaW1hdGlvbi10aW1pbmctZnVuY3Rpb246IGN1YmljLWJlemllcigwLjQyLCAwLCAwLjU4LCAxKTthbmltYXRpb24tdGltaW5nLWZ1bmN0aW9uOiBjdWJpYy1iZXppZXIoMC40MiwgMCwgMC41OCwgMSk7b3BhY2l0eTogMTt9I05YUmVwb3J0V2FybmluZzR7LXdlYmtpdC1hbmltYXRpb24tbmFtZTogTlhSZXBvcnRXYXJuaW5nNC1hbmltYXRpb247YW5pbWF0aW9uLW5hbWU6IE5YUmVwb3J0V2FybmluZzQtYW5pbWF0aW9uOy13ZWJraXQtYW5pbWF0aW9uLXRpbWluZy1mdW5jdGlvbjogY3ViaWMtYmV6aWVyKDAuNDIsIDAsIDAuNTgsIDEpO2FuaW1hdGlvbi10aW1pbmctZnVuY3Rpb246IGN1YmljLWJlemllcigwLjQyLCAwLCAwLjU4LCAxKTstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDY2LjZweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjYuNnB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2Ni42cHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTY2LjZweCk7fSNOWFJlcG9ydFdhcm5pbmcyey13ZWJraXQtYW5pbWF0aW9uLW5hbWU6IE5YUmVwb3J0V2FybmluZzItYW5pbWF0aW9uO2FuaW1hdGlvbi1uYW1lOiBOWFJlcG9ydFdhcm5pbmcyLWFuaW1hdGlvbjstd2Via2l0LWFuaW1hdGlvbi10aW1pbmctZnVuY3Rpb246IGN1YmljLWJlemllcigwLjQyLCAwLCAwLjU4LCAxKTthbmltYXRpb24tdGltaW5nLWZ1bmN0aW9uOiBjdWJpYy1iZXppZXIoMC40MiwgMCwgMC41OCwgMSk7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9PC9zdHlsZT48ZyBpZD1cIk5YUmVwb3J0V2FybmluZzFcIj48ZyBpZD1cIk5YUmVwb3J0V2FybmluZzJcIiBkYXRhLWFuaW1hdG9yLWdyb3VwPVwidHJ1ZVwiIGRhdGEtYW5pbWF0b3ItdHlwZT1cIjJcIj48cGF0aCBkPVwiTTExNS40NiAxMDYuMTVsLTU0LjA0IC05My44Yy0wLjYxLC0xLjA2IC0yLjIzLC0xLjA2IC0yLjg0LDBsLTU0LjA0IDkzLjhjLTAuNjIsMS4wNyAwLjIxLDIuMjkgMS40MiwyLjI5bDEwOC4wOCAwYzEuMjEsMCAyLjA0LC0xLjIyIDEuNDIsLTIuMjl6bS01MC4yOSAtOTUuOTVsNTQuMDQgOTMuOGMyLjI4LDMuOTYgLTAuNjUsOC43OCAtNS4xNyw4Ljc4bC0xMDguMDggMGMtNC41MiwwIC03LjQ1LC00LjgyIC01LjE3LC04Ljc4bDU0LjA0IC05My44YzIuMjgsLTMuOTUgOC4wMywtNCAxMC4zNCwwelwiIGlkPVwiTlhSZXBvcnRXYXJuaW5nM1wiLz48L2c+PGcgaWQ9XCJOWFJlcG9ydFdhcm5pbmc0XCIgZGF0YS1hbmltYXRvci1ncm91cD1cInRydWVcIiBkYXRhLWFuaW1hdG9yLXR5cGU9XCIyXCI+PHBhdGggZD1cIk01Ny44MyA5NC4wMWMwLDEuMiAwLjk3LDIuMTcgMi4xNywyLjE3IDEuMiwwIDIuMTcsLTAuOTcgMi4xNywtMi4xN2wwIC0zLjJjMCwtMS4yIC0wLjk3LC0yLjE3IC0yLjE3LC0yLjE3IC0xLjIsMCAtMi4xNywwLjk3IC0yLjE3LDIuMTdsMCAzLjJ6bTAgLTE0LjE1YzAsMS4yIDAuOTcsMi4xNyAyLjE3LDIuMTcgMS4yLDAgMi4xNywtMC45NyAyLjE3LC0yLjE3bDAgLTQwLjY1YzAsLTEuMiAtMC45NywtMi4xNyAtMi4xNywtMi4xNyAtMS4yLDAgLTIuMTcsMC45NyAtMi4xNywyLjE3bDAgNDAuNjV6XCIgaWQ9XCJOWFJlcG9ydFdhcm5pbmc1XCIvPjwvZz48L2c+PC9zdmc+YDtcclxuXHJcbiAgICByZXR1cm4gcmVwb3J0U3ZnV2FybmluZztcclxufVxyXG4vLyBOb3RpZmxpeDogUmVwb3J0IFNWRyBXYXJuaW5nIG9mZlxyXG5cclxuLy8gTm90aWZsaXg6IFJlcG9ydCBTVkcgSW5mbyBvblxyXG5jb25zdCBub3RpZmxpeFJlcG9ydFN2Z0luZm8gPSBmdW5jdGlvbiAod2lkdGgsIGNvbG9yKSB7XHJcblxyXG4gICAgaWYgKCF3aWR0aCkgeyB3aWR0aCA9ICcxMTBweCc7IH1cclxuICAgIGlmICghY29sb3IpIHsgY29sb3IgPSAnIzAwYmNkNCc7IH1cclxuXHJcbiAgICBjb25zdCByZXBvcnRTdmdJbmZvID0gYDxzdmcgaWQ9XCJOWFJlcG9ydEluZm9cIiBmaWxsPVwiJHtjb2xvcn1cIiB3aWR0aD1cIiR7d2lkdGh9XCIgaGVpZ2h0PVwiJHt3aWR0aH1cIiB4bWxucz1cImh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnXCIgeG1sOnNwYWNlPVwicHJlc2VydmVcIiB2ZXJzaW9uPVwiMS4xXCIgc3R5bGU9XCJzaGFwZS1yZW5kZXJpbmc6Z2VvbWV0cmljUHJlY2lzaW9uOyB0ZXh0LXJlbmRlcmluZzpnZW9tZXRyaWNQcmVjaXNpb247IGltYWdlLXJlbmRlcmluZzpvcHRpbWl6ZVF1YWxpdHk7IGZpbGwtcnVsZTpldmVub2RkOyBjbGlwLXJ1bGU6ZXZlbm9kZFwiIHZpZXdCb3g9XCIwIDAgMTIwIDEyMFwiIHhtbG5zOnhsaW5rPVwiaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGlua1wiPjxzdHlsZT5ALXdlYmtpdC1rZXlmcmFtZXMgTlhSZXBvcnRJbmZvNS1hbmltYXRpb257MCV7b3BhY2l0eTogMDt9NTAle29wYWNpdHk6IDE7fTEwMCV7b3BhY2l0eTogMTt9fUBrZXlmcmFtZXMgTlhSZXBvcnRJbmZvNS1hbmltYXRpb257MCV7b3BhY2l0eTogMDt9NTAle29wYWNpdHk6IDE7fTEwMCV7b3BhY2l0eTogMTt9fUAtd2Via2l0LWtleWZyYW1lcyBOWFJlcG9ydEluZm80LWFuaW1hdGlvbnswJXstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDAuNSwgMC41KSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgwLjUsIDAuNSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7fTUwJXstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO302MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgwLjk1LCAwLjk1KSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgwLjk1LCAwLjk1KSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9MTAwJXstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO319QGtleWZyYW1lcyBOWFJlcG9ydEluZm80LWFuaW1hdGlvbnswJXstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDAuNSwgMC41KSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgwLjUsIDAuNSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7fTUwJXstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO302MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgwLjk1LCAwLjk1KSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgwLjk1LCAwLjk1KSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9MTAwJXstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO319QC13ZWJraXQta2V5ZnJhbWVzIE5YUmVwb3J0SW5mbzMtYW5pbWF0aW9uezAle29wYWNpdHk6IDA7fTQwJXtvcGFjaXR5OiAxO30xMDAle29wYWNpdHk6IDE7fX1Aa2V5ZnJhbWVzIE5YUmVwb3J0SW5mbzMtYW5pbWF0aW9uezAle29wYWNpdHk6IDA7fTQwJXtvcGFjaXR5OiAxO30xMDAle29wYWNpdHk6IDE7fX1ALXdlYmtpdC1rZXlmcmFtZXMgTlhSZXBvcnRJbmZvMi1hbmltYXRpb257MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgwLjUsIDAuNSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC41LCAwLjUpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO300MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9NjAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC45NSwgMC45NSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC45NSwgMC45NSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7fTEwMCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9fUBrZXlmcmFtZXMgTlhSZXBvcnRJbmZvMi1hbmltYXRpb257MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgwLjUsIDAuNSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC41LCAwLjUpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO300MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9NjAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC45NSwgMC45NSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC45NSwgMC45NSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7fTEwMCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9fSNOWFJlcG9ydEluZm8gKnstd2Via2l0LWFuaW1hdGlvbi1kdXJhdGlvbjogMS4yczthbmltYXRpb24tZHVyYXRpb246IDEuMnM7LXdlYmtpdC1hbmltYXRpb24tdGltaW5nLWZ1bmN0aW9uOiBjdWJpYy1iZXppZXIoMCwgMCwgMSwgMSk7YW5pbWF0aW9uLXRpbWluZy1mdW5jdGlvbjogY3ViaWMtYmV6aWVyKDAsIDAsIDEsIDEpO30jTlhSZXBvcnRJbmZvM3tmaWxsOmluaGVyaXQ7LXdlYmtpdC1hbmltYXRpb24tbmFtZTogTlhSZXBvcnRJbmZvMy1hbmltYXRpb247YW5pbWF0aW9uLW5hbWU6IE5YUmVwb3J0SW5mbzMtYW5pbWF0aW9uOy13ZWJraXQtYW5pbWF0aW9uLXRpbWluZy1mdW5jdGlvbjogY3ViaWMtYmV6aWVyKDAuNDIsIDAsIDAuNTgsIDEpO2FuaW1hdGlvbi10aW1pbmctZnVuY3Rpb246IGN1YmljLWJlemllcigwLjQyLCAwLCAwLjU4LCAxKTtvcGFjaXR5OiAxO30jTlhSZXBvcnRJbmZvNXtmaWxsOmluaGVyaXQ7LXdlYmtpdC1hbmltYXRpb24tbmFtZTogTlhSZXBvcnRJbmZvNS1hbmltYXRpb247YW5pbWF0aW9uLW5hbWU6IE5YUmVwb3J0SW5mbzUtYW5pbWF0aW9uOy13ZWJraXQtYW5pbWF0aW9uLXRpbWluZy1mdW5jdGlvbjogY3ViaWMtYmV6aWVyKDAuNDIsIDAsIDAuNTgsIDEpO2FuaW1hdGlvbi10aW1pbmctZnVuY3Rpb246IGN1YmljLWJlemllcigwLjQyLCAwLCAwLjU4LCAxKTtvcGFjaXR5OiAxO30jTlhSZXBvcnRJbmZvMnstd2Via2l0LWFuaW1hdGlvbi1uYW1lOiBOWFJlcG9ydEluZm8yLWFuaW1hdGlvbjthbmltYXRpb24tbmFtZTogTlhSZXBvcnRJbmZvMi1hbmltYXRpb247LXdlYmtpdC1hbmltYXRpb24tdGltaW5nLWZ1bmN0aW9uOiBjdWJpYy1iZXppZXIoMC40MiwgMCwgMC41OCwgMSk7YW5pbWF0aW9uLXRpbWluZy1mdW5jdGlvbjogY3ViaWMtYmV6aWVyKDAuNDIsIDAsIDAuNTgsIDEpOy13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7fSNOWFJlcG9ydEluZm80ey13ZWJraXQtYW5pbWF0aW9uLW5hbWU6IE5YUmVwb3J0SW5mbzQtYW5pbWF0aW9uO2FuaW1hdGlvbi1uYW1lOiBOWFJlcG9ydEluZm80LWFuaW1hdGlvbjstd2Via2l0LWFuaW1hdGlvbi10aW1pbmctZnVuY3Rpb246IGN1YmljLWJlemllcigwLjQyLCAwLCAwLjU4LCAxKTthbmltYXRpb24tdGltaW5nLWZ1bmN0aW9uOiBjdWJpYy1iZXppZXIoMC40MiwgMCwgMC41OCwgMSk7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9PC9zdHlsZT48ZyBpZD1cIk5YUmVwb3J0SW5mbzFcIj48ZyBpZD1cIk5YUmVwb3J0SW5mbzJcIiBkYXRhLWFuaW1hdG9yLWdyb3VwPVwidHJ1ZVwiIGRhdGEtYW5pbWF0b3ItdHlwZT1cIjJcIj48cGF0aCBkPVwiTTYwIDExNS4zOGMtMzAuNTQsMCAtNTUuMzgsLTI0Ljg0IC01NS4zOCwtNTUuMzggMCwtMzAuNTQgMjQuODQsLTU1LjM4IDU1LjM4LC01NS4zOCAzMC41NCwwIDU1LjM4LDI0Ljg0IDU1LjM4LDU1LjM4IDAsMzAuNTQgLTI0Ljg0LDU1LjM4IC01NS4zOCw1NS4zOHptMCAtMTE1LjM4Yy0zMy4wOCwwIC02MCwyNi45MiAtNjAsNjAgMCwzMy4wOCAyNi45Miw2MCA2MCw2MCAzMy4wOCwwIDYwLC0yNi45MiA2MCwtNjAgMCwtMzMuMDggLTI2LjkyLC02MCAtNjAsLTYwelwiIGlkPVwiTlhSZXBvcnRJbmZvM1wiLz48L2c+PGcgaWQ9XCJOWFJlcG9ydEluZm80XCIgZGF0YS1hbmltYXRvci1ncm91cD1cInRydWVcIiBkYXRhLWFuaW1hdG9yLXR5cGU9XCIyXCI+PHBhdGggZD1cIk01Ny43NSA0My44NWMwLC0xLjI0IDEuMDEsLTIuMjUgMi4yNSwtMi4yNSAxLjI0LDAgMi4yNSwxLjAxIDIuMjUsMi4yNWwwIDQ4LjE4YzAsMS4yNCAtMS4wMSwyLjI1IC0yLjI1LDIuMjUgLTEuMjQsMCAtMi4yNSwtMS4wMSAtMi4yNSwtMi4yNWwwIC00OC4xOHptMCAtMTUuODhjMCwtMS4yNCAxLjAxLC0yLjI1IDIuMjUsLTIuMjUgMS4yNCwwIDIuMjUsMS4wMSAyLjI1LDIuMjVsMCAzLjMyYzAsMS4yNSAtMS4wMSwyLjI1IC0yLjI1LDIuMjUgLTEuMjQsMCAtMi4yNSwtMSAtMi4yNSwtMi4yNWwwIC0zLjMyelwiIGlkPVwiTlhSZXBvcnRJbmZvNVwiLz48L2c+PC9nPjwvc3ZnPmA7XHJcblxyXG4gICAgcmV0dXJuIHJlcG9ydFN2Z0luZm87XHJcblxyXG59XHJcbi8vIE5vdGlmbGl4OiBSZXBvcnQgU1ZHIEluZm8gb2ZmXHJcblxyXG5cclxuLy8gTm90aWZsaXg6IENvbmZpcm0gU2luZ2xlIG9uXHJcbmNvbnN0IE5vdGlmbGl4Q29uZmlybSA9IGZ1bmN0aW9uICh0aXRsZSwgbWVzc2FnZSwgb2tCdXR0b25UZXh0LCBjYW5jZWxCdXR0b25UZXh0LCBva0J1dHRvbkNhbGxiYWNrLCBjYW5jZWxCdXR0b25DYWxsYmFjaykge1xyXG5cclxuICAgIC8vIGlmIHBsYWluVGV4dCB0cnVlID0gSFRNTCB0YWdzIG5vdCBhbGxvd2VkIG9uXHJcbiAgICBpZiAobmV3Q29uZmlybVNldHRpbmdzLnBsYWluVGV4dCkge1xyXG4gICAgICAgIHRpdGxlID0gbm90aWZsaXhQbGFpbnRleHQodGl0bGUpO1xyXG4gICAgICAgIG1lc3NhZ2UgPSBub3RpZmxpeFBsYWludGV4dChtZXNzYWdlKTtcclxuICAgICAgICBva0J1dHRvblRleHQgPSBub3RpZmxpeFBsYWludGV4dChva0J1dHRvblRleHQpO1xyXG4gICAgICAgIGNhbmNlbEJ1dHRvblRleHQgPSBub3RpZmxpeFBsYWludGV4dChjYW5jZWxCdXR0b25UZXh0KTtcclxuICAgIH1cclxuICAgIC8vIGlmIHBsYWluVGV4dCB0cnVlID0gSFRNTCB0YWdzIG5vdCBhbGxvd2VkIG9mZlxyXG5cclxuICAgIC8vIGlmIHBsYWluVGV4dCBmYWxzZSBidXQgdGhlIGNvbnRlbnRzIGxlbmd0aCBtb3JlIHRoYW4gKk1heExlbmd0aCA9IEhUTUwgdGFncyBlcnJvciBvblxyXG4gICAgaWYgKCFuZXdDb25maXJtU2V0dGluZ3MucGxhaW5UZXh0KSB7XHJcbiAgICAgICAgaWYgKHRpdGxlLmxlbmd0aCA+IG5ld0NvbmZpcm1TZXR0aW5ncy50aXRsZU1heExlbmd0aCkge1xyXG4gICAgICAgICAgICB0aXRsZSA9ICdIVE1MIFRhZ3MgRXJyb3InOyAvLyB0aXRsZSBodG1sIGVycm9yXHJcbiAgICAgICAgICAgIG1lc3NhZ2UgPSAnWW91ciBUaXRsZSBjb250ZW50IGxlbmd0aCBpcyBtb3JlIHRoYW4gXCJ0aXRsZU1heExlbmd0aFwiIG9wdGlvbi4nOyAvLyBtZXNzYWdlIGh0bWwgZXJyb3JcclxuICAgICAgICAgICAgb2tCdXR0b25UZXh0ID0gJ09rYXknOyAvLyBidXR0b24gaHRtbCBlcnJvclxyXG4gICAgICAgICAgICBjYW5jZWxCdXR0b25UZXh0ID0gJy4uLic7IC8vIGJ1dHRvbiBodG1sIGVycm9yXHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICBpZiAobWVzc2FnZS5sZW5ndGggPiBuZXdDb25maXJtU2V0dGluZ3MubWVzc2FnZU1heExlbmd0aCkge1xyXG4gICAgICAgICAgICB0aXRsZSA9ICdIVE1MIFRhZ3MgRXJyb3InOyAvLyB0aXRsZSBodG1sIGVycm9yXHJcbiAgICAgICAgICAgIG1lc3NhZ2UgPSAnWW91ciBNZXNzYWdlIGNvbnRlbnQgbGVuZ3RoIGlzIG1vcmUgdGhhbiBcIm1lc3NhZ2VNYXhMZW5ndGhcIiBvcHRpb24uJzsgLy8gbWVzc2FnZSBodG1sIGVycm9yXHJcbiAgICAgICAgICAgIG9rQnV0dG9uVGV4dCA9ICdPa2F5JzsgLy8gYnV0dG9uIGh0bWwgZXJyb3JcclxuICAgICAgICAgICAgY2FuY2VsQnV0dG9uVGV4dCA9ICcuLi4nOyAvLyBidXR0b24gaHRtbCBlcnJvclxyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgaWYgKChva0J1dHRvblRleHQubGVuZ3RoIHx8IGNhbmNlbEJ1dHRvblRleHQubGVuZ3RoKSA+IG5ld0NvbmZpcm1TZXR0aW5ncy5idXR0b25zTWF4TGVuZ3RoKSB7XHJcbiAgICAgICAgICAgIHRpdGxlID0gJ0hUTUwgVGFncyBFcnJvcic7IC8vIHRpdGxlIGh0bWwgZXJyb3JcclxuICAgICAgICAgICAgbWVzc2FnZSA9ICdZb3VyIEJ1dHRvbnMgY29udGVudHMgbGVuZ3RoIGlzIG1vcmUgdGhhbiBcImJ1dHRvbnNNYXhMZW5ndGhcIiBvcHRpb24uJzsgLy8gbWVzc2FnZSBodG1sIGVycm9yXHJcbiAgICAgICAgICAgIG9rQnV0dG9uVGV4dCA9ICdPa2F5JzsgLy8gYnV0dG9uIGh0bWwgZXJyb3JcclxuICAgICAgICAgICAgY2FuY2VsQnV0dG9uVGV4dCA9ICcuLi4nOyAvLyBidXR0b24gaHRtbCBlcnJvclxyXG4gICAgICAgIH1cclxuICAgIH1cclxuICAgIC8vIGlmIHBsYWluVGV4dCBmYWxzZSBidXQgdGhlIGNvbnRlbnRzIGxlbmd0aCBtb3JlIHRoYW4gKk1heExlbmd0aCA9IEhUTUwgdGFncyBlcnJvciBvZmZcclxuXHJcblxyXG4gICAgLy8gbWF4IGxlbmd0aCBvblxyXG4gICAgaWYgKHRpdGxlLmxlbmd0aCA+IG5ld0NvbmZpcm1TZXR0aW5ncy50aXRsZU1heExlbmd0aCkge1xyXG4gICAgICAgIHRpdGxlID0gYCR7dGl0bGUuc3Vic3RyaW5nKDAsIG5ld0NvbmZpcm1TZXR0aW5ncy50aXRsZU1heExlbmd0aCl9Li4uYDtcclxuICAgIH1cclxuXHJcbiAgICBpZiAobWVzc2FnZS5sZW5ndGggPiBuZXdDb25maXJtU2V0dGluZ3MubWVzc2FnZU1heExlbmd0aCkge1xyXG4gICAgICAgIG1lc3NhZ2UgPSBgJHttZXNzYWdlLnN1YnN0cmluZygwLCBuZXdDb25maXJtU2V0dGluZ3MubWVzc2FnZU1heExlbmd0aCl9Li4uYDtcclxuICAgIH1cclxuXHJcbiAgICBpZiAob2tCdXR0b25UZXh0Lmxlbmd0aCA+IG5ld0NvbmZpcm1TZXR0aW5ncy5idXR0b25zTWF4TGVuZ3RoKSB7XHJcbiAgICAgICAgb2tCdXR0b25UZXh0ID0gYCR7b2tCdXR0b25UZXh0LnN1YnN0cmluZygwLCBuZXdDb25maXJtU2V0dGluZ3MuYnV0dG9uc01heExlbmd0aCl9Li4uYDtcclxuICAgIH1cclxuXHJcbiAgICBpZiAoY2FuY2VsQnV0dG9uVGV4dC5sZW5ndGggPiBuZXdDb25maXJtU2V0dGluZ3MuYnV0dG9uc01heExlbmd0aCkge1xyXG4gICAgICAgIGNhbmNlbEJ1dHRvblRleHQgPSBgJHtjYW5jZWxCdXR0b25UZXh0LnN1YnN0cmluZygwLCBuZXdDb25maXJtU2V0dGluZ3MuYnV0dG9uc01heExlbmd0aCl9Li4uYDtcclxuICAgIH1cclxuICAgIC8vIG1heCBsZW5ndGggb2ZmXHJcblxyXG5cclxuICAgIC8vIGlmIGNzc0FuaW1haW9uIGZhbHNlIC0+IGR1cmF0aW9uIG9uXHJcbiAgICBpZiAoIW5ld0NvbmZpcm1TZXR0aW5ncy5jc3NBbmltYXRpb24pIHtcclxuICAgICAgICBuZXdDb25maXJtU2V0dGluZ3MuY3NzQW5pbWF0aW9uRHVyYXRpb24gPSAwO1xyXG4gICAgfVxyXG4gICAgLy8gaWYgY3NzQW5pbWFpb24gZmFsc2UgLT4gZHVyYXRpb24gb2ZmXHJcblxyXG5cclxuICAgIC8vIGNvbmZpcm0gd3JhcCBvblxyXG4gICAgY29uc3QgZG9jQm9keSA9IGRvY3VtZW50LmJvZHk7XHJcblxyXG4gICAgY29uc3QgbnRmbHhDb25maXJtV3JhcCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xyXG4gICAgbnRmbHhDb25maXJtV3JhcC5pZCA9IGNvbmZpcm1TZXR0aW5ncy5JRDtcclxuICAgIG50Zmx4Q29uZmlybVdyYXAuY2xhc3NOYW1lID0gYCR7bmV3Q29uZmlybVNldHRpbmdzLmNsYXNzTmFtZX0gJHsobmV3Q29uZmlybVNldHRpbmdzLmNzc0FuaW1hdGlvbiA/ICd3aXRoLWFuaW1hdGlvbiBueC0nICsgbmV3Q29uZmlybVNldHRpbmdzLmNzc0FuaW1hdGlvblN0eWxlIDogJycpfWA7XHJcbiAgICBudGZseENvbmZpcm1XcmFwLnN0eWxlLndpZHRoID0gbmV3Q29uZmlybVNldHRpbmdzLndpZHRoO1xyXG4gICAgbnRmbHhDb25maXJtV3JhcC5zdHlsZS56SW5kZXggPSBuZXdDb25maXJtU2V0dGluZ3MuemluZGV4O1xyXG4gICAgbnRmbHhDb25maXJtV3JhcC5zdHlsZS5tYXJnaW4gPSAnYXV0byc7XHJcblxyXG4gICAgLy8gcnRsIG9uXHJcbiAgICBpZiAobmV3Q29uZmlybVNldHRpbmdzLnJ0bCkge1xyXG4gICAgICAgIG50Zmx4Q29uZmlybVdyYXAuc2V0QXR0cmlidXRlKCdkaXInLCAncnRsJyk7XHJcbiAgICAgICAgbnRmbHhDb25maXJtV3JhcC5jbGFzc0xpc3QuYWRkKCdydGwtb24nKTtcclxuICAgIH1cclxuICAgIC8vIHJ0bCBvZmZcclxuXHJcbiAgICAvLyBmb250LWZhbWlseSBvblxyXG4gICAgbnRmbHhDb25maXJtV3JhcC5zdHlsZS5mb250RmFtaWx5ID0gYFwiJHtuZXdDb25maXJtU2V0dGluZ3MuZm9udEZhbWlseX1cIiwgc2Fucy1zZXJpZmA7XHJcbiAgICAvLyBmb250LWZhbWlseSBvZmZcclxuXHJcbiAgICAvLyBpZiBiYWNrZ3JvdW5kIG92ZXJsYXkgdHJ1ZSBvblxyXG4gICAgbGV0IGNvbmZpcm1PdmVybGF5ID0gJyc7XHJcbiAgICBpZiAobmV3Q29uZmlybVNldHRpbmdzLmJhY2tPdmVybGF5KSB7XHJcbiAgICAgICAgY29uZmlybU92ZXJsYXkgPSBgPGRpdiBjbGFzcz1cIiR7bmV3Q29uZmlybVNldHRpbmdzLmNsYXNzTmFtZX0tb3ZlcmxheSAkeyhuZXdDb25maXJtU2V0dGluZ3MuY3NzQW5pbWF0aW9uID8gJ3dpdGgtYW5pbWF0aW9uJyA6ICcnKX1cIiBzdHlsZT1cImJhY2tncm91bmQ6JHtuZXdDb25maXJtU2V0dGluZ3MuYmFja092ZXJsYXlDb2xvcn07YW5pbWF0aW9uLWR1cmF0aW9uOiR7bmV3Q29uZmlybVNldHRpbmdzLmNzc0FuaW1hdGlvbkR1cmF0aW9ufW1zO1wiPjwvZGl2PmA7XHJcbiAgICB9XHJcbiAgICAvLyBpZiBiYWNrZ3JvdW5kIG92ZXJsYXkgdHJ1ZSBvZmZcclxuXHJcblxyXG4gICAgLy8gaWYgaGF2ZSBhIGNhbGxiYWNrIC0gY2FuY2VsIGJ1dHRvbiBvblxyXG4gICAgbGV0IGNhbmNlbEJ1dHRvbkhUTUwgPSAnJztcclxuICAgIGlmIChva0J1dHRvbkNhbGxiYWNrKSB7XHJcbiAgICAgICAgY2FuY2VsQnV0dG9uSFRNTCA9IGA8YSBpZD1cIk5YQ29uZmlybUJ1dHRvbkNhbmNlbFwiIGNsYXNzPVwiY29uZmlybS1idXR0b24tY2FuY2VsXCIgc3R5bGU9XCJjb2xvcjoke25ld0NvbmZpcm1TZXR0aW5ncy5jYW5jZWxCdXR0b25Db2xvcn07YmFja2dyb3VuZDoke25ld0NvbmZpcm1TZXR0aW5ncy5jYW5jZWxCdXR0b25CYWNrZ3JvdW5kfTtmb250LXNpemU6JHtuZXdDb25maXJtU2V0dGluZ3MuYnV0dG9uc0ZvbnRTaXplfTtcIj4ke2NhbmNlbEJ1dHRvblRleHR9PC9hPmA7XHJcbiAgICB9XHJcbiAgICAvLyBpZiBoYXZlIGEgY2FsbGJhY2sgLSBjYW5jZWwgYnV0dG9uIG9mZlxyXG5cclxuICAgIG50Zmx4Q29uZmlybVdyYXAuaW5uZXJIVE1MID0gYCR7Y29uZmlybU92ZXJsYXl9XHJcbiAgICAgICAgPGRpdiBjbGFzcz1cIiR7bmV3Q29uZmlybVNldHRpbmdzLmNsYXNzTmFtZX0tY29udGVudFwiIHN0eWxlPVwiYmFja2dyb3VuZDoke25ld0NvbmZpcm1TZXR0aW5ncy5iYWNrZ3JvdW5kQ29sb3J9OyBhbmltYXRpb24tZHVyYXRpb246JHtuZXdDb25maXJtU2V0dGluZ3MuY3NzQW5pbWF0aW9uRHVyYXRpb259bXM7IGJvcmRlci1yYWRpdXM6JHtuZXdDb25maXJtU2V0dGluZ3MuYm9yZGVyUmFkaXVzfTtcIj5cclxuICAgICAgICAgICAgPGRpdiBjbGFzcz1cIiR7bmV3Q29uZmlybVNldHRpbmdzLmNsYXNzTmFtZX0taGVhZFwiPlxyXG4gICAgICAgICAgICAgICAgPGg1IHN0eWxlPVwiY29sb3I6JHtuZXdDb25maXJtU2V0dGluZ3MudGl0bGVDb2xvcn07Zm9udC1zaXplOiR7bmV3Q29uZmlybVNldHRpbmdzLnRpdGxlRm9udFNpemV9O1wiPiR7dGl0bGV9PC9oNT5cclxuICAgICAgICAgICAgICAgIDxwIHN0eWxlPVwiY29sb3I6JHtuZXdDb25maXJtU2V0dGluZ3MubWVzc2FnZUNvbG9yfTtmb250LXNpemU6JHtuZXdDb25maXJtU2V0dGluZ3MubWVzc2FnZUZvbnRTaXplfTtcIj4ke21lc3NhZ2V9PC9wPlxyXG4gICAgICAgICAgICA8L2Rpdj5cclxuICAgICAgICAgICAgPGRpdiBjbGFzcz1cIiR7bmV3Q29uZmlybVNldHRpbmdzLmNsYXNzTmFtZX0tYnV0dG9uc1wiPlxyXG4gICAgICAgICAgICAgICAgPGEgaWQ9XCJOWENvbmZpcm1CdXR0b25Pa1wiIGNsYXNzPVwiY29uZmlybS1idXR0b24tb2sgJHsob2tCdXR0b25DYWxsYmFjayA/ICcnIDogJ2Z1bGwnKX1cIiBzdHlsZT1cImNvbG9yOiR7bmV3Q29uZmlybVNldHRpbmdzLm9rQnV0dG9uQ29sb3J9O2JhY2tncm91bmQ6JHtuZXdDb25maXJtU2V0dGluZ3Mub2tCdXR0b25CYWNrZ3JvdW5kfTtmb250LXNpemU6JHtuZXdDb25maXJtU2V0dGluZ3MuYnV0dG9uc0ZvbnRTaXplfTtcIj4ke29rQnV0dG9uVGV4dH08L2E+XHJcbiAgICAgICAgICAgICAgICAke2NhbmNlbEJ1dHRvbkhUTUx9XHJcbiAgICAgICAgICAgIDwvZGl2PlxyXG4gICAgICAgIDwvZGl2PmA7XHJcbiAgICAvLyBjb25maXJtIHdyYXAgb2ZmXHJcblxyXG4gICAgLy8gaWYgdGhlcmUgaXMgbm8gY29uZmlybSBib3ggb25cclxuICAgIGlmICghZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQobnRmbHhDb25maXJtV3JhcC5pZCkpIHtcclxuICAgICAgICBkb2NCb2R5LmFwcGVuZENoaWxkKG50Zmx4Q29uZmlybVdyYXApO1xyXG5cclxuICAgICAgICAvLyBwb3NpdGlvbiBvbiAgICAgICAgICAgICAgICAgIFxyXG4gICAgICAgIGlmIChuZXdDb25maXJtU2V0dGluZ3MucG9zaXRpb24gPT09ICdjZW50ZXInKSB7IC8vIGlmIGNlbnRlclxyXG5cclxuICAgICAgICAgICAgbGV0IHdpbmRvd0ggPSBNYXRoLnJvdW5kKHdpbmRvdy5pbm5lckhlaWdodCk7XHJcbiAgICAgICAgICAgIGxldCBjb25maXJtSCA9IE1hdGgucm91bmQoZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQobnRmbHhDb25maXJtV3JhcC5pZCkub2Zmc2V0SGVpZ2h0KTtcclxuXHJcbiAgICAgICAgICAgIG50Zmx4Q29uZmlybVdyYXAuc3R5bGUudG9wID0gYCR7cGFyc2VJbnQoKHdpbmRvd0ggLSBjb25maXJtSCkgLyAyKX1weGA7XHJcbiAgICAgICAgICAgIG50Zmx4Q29uZmlybVdyYXAuc3R5bGUubGVmdCA9IG5ld0NvbmZpcm1TZXR0aW5ncy5kaXN0YW5jZTtcclxuICAgICAgICAgICAgbnRmbHhDb25maXJtV3JhcC5zdHlsZS5yaWdodCA9IG5ld0NvbmZpcm1TZXR0aW5ncy5kaXN0YW5jZTtcclxuICAgICAgICAgICAgbnRmbHhDb25maXJtV3JhcC5zdHlsZS5ib3R0b20gPSAnYXV0byc7XHJcblxyXG4gICAgICAgIH0gZWxzZSBpZiAobmV3Q29uZmlybVNldHRpbmdzLnBvc2l0aW9uID09PSAncmlnaHQtdG9wJykgeyAvLyBpZiByaWdodC10b3BcclxuXHJcbiAgICAgICAgICAgIG50Zmx4Q29uZmlybVdyYXAuc3R5bGUucmlnaHQgPSBuZXdDb25maXJtU2V0dGluZ3MuZGlzdGFuY2U7XHJcbiAgICAgICAgICAgIG50Zmx4Q29uZmlybVdyYXAuc3R5bGUudG9wID0gbmV3Q29uZmlybVNldHRpbmdzLmRpc3RhbmNlO1xyXG4gICAgICAgICAgICBudGZseENvbmZpcm1XcmFwLnN0eWxlLmJvdHRvbSA9ICdhdXRvJztcclxuICAgICAgICAgICAgbnRmbHhDb25maXJtV3JhcC5zdHlsZS5sZWZ0ID0gJ2F1dG8nO1xyXG5cclxuICAgICAgICB9IGVsc2UgaWYgKG5ld0NvbmZpcm1TZXR0aW5ncy5wb3NpdGlvbiA9PT0gJ3JpZ2h0LWJvdHRvbScpIHsgLy8gaWYgcmlnaHQtYm90dG9tXHJcblxyXG4gICAgICAgICAgICBudGZseENvbmZpcm1XcmFwLnN0eWxlLnJpZ2h0ID0gbmV3Q29uZmlybVNldHRpbmdzLmRpc3RhbmNlO1xyXG4gICAgICAgICAgICBudGZseENvbmZpcm1XcmFwLnN0eWxlLmJvdHRvbSA9IG5ld0NvbmZpcm1TZXR0aW5ncy5kaXN0YW5jZTtcclxuICAgICAgICAgICAgbnRmbHhDb25maXJtV3JhcC5zdHlsZS50b3AgPSAnYXV0byc7XHJcbiAgICAgICAgICAgIG50Zmx4Q29uZmlybVdyYXAuc3R5bGUubGVmdCA9ICdhdXRvJztcclxuXHJcbiAgICAgICAgfSBlbHNlIGlmIChuZXdDb25maXJtU2V0dGluZ3MucG9zaXRpb24gPT09ICdsZWZ0LXRvcCcpIHsgLy8gaWYgbGVmdC10b3BcclxuXHJcbiAgICAgICAgICAgIG50Zmx4Q29uZmlybVdyYXAuc3R5bGUubGVmdCA9IG5ld0NvbmZpcm1TZXR0aW5ncy5kaXN0YW5jZTtcclxuICAgICAgICAgICAgbnRmbHhDb25maXJtV3JhcC5zdHlsZS50b3AgPSBuZXdDb25maXJtU2V0dGluZ3MuZGlzdGFuY2U7XHJcbiAgICAgICAgICAgIG50Zmx4Q29uZmlybVdyYXAuc3R5bGUucmlnaHQgPSAnYXV0byc7XHJcbiAgICAgICAgICAgIG50Zmx4Q29uZmlybVdyYXAuc3R5bGUuYm90dG9tID0gJ2F1dG8nO1xyXG5cclxuICAgICAgICB9IGVsc2UgaWYgKG5ld0NvbmZpcm1TZXR0aW5ncy5wb3NpdGlvbiA9PT0gJ2xlZnQtYm90dG9tJykgeyAvLyBpZiBsZWZ0LWJvdHRvbVxyXG5cclxuICAgICAgICAgICAgbnRmbHhDb25maXJtV3JhcC5zdHlsZS5sZWZ0ID0gbmV3Q29uZmlybVNldHRpbmdzLmRpc3RhbmNlO1xyXG4gICAgICAgICAgICBudGZseENvbmZpcm1XcmFwLnN0eWxlLmJvdHRvbSA9IG5ld0NvbmZpcm1TZXR0aW5ncy5kaXN0YW5jZTtcclxuICAgICAgICAgICAgbnRmbHhDb25maXJtV3JhcC5zdHlsZS50b3AgPSAnYXV0byc7XHJcbiAgICAgICAgICAgIG50Zmx4Q29uZmlybVdyYXAuc3R5bGUucmlnaHQgPSAnYXV0byc7XHJcblxyXG4gICAgICAgIH0gZWxzZSB7IC8vIGlmIGNlbnRlci10b3Agb3IgZWxzZVxyXG5cclxuICAgICAgICAgICAgbnRmbHhDb25maXJtV3JhcC5zdHlsZS50b3AgPSBuZXdDb25maXJtU2V0dGluZ3MuZGlzdGFuY2U7XHJcbiAgICAgICAgICAgIG50Zmx4Q29uZmlybVdyYXAuc3R5bGUubGVmdCA9IDA7XHJcbiAgICAgICAgICAgIG50Zmx4Q29uZmlybVdyYXAuc3R5bGUucmlnaHQgPSAwO1xyXG4gICAgICAgICAgICBudGZseENvbmZpcm1XcmFwLnN0eWxlLmJvdHRvbSA9ICdhdXRvJztcclxuICAgICAgICB9XHJcbiAgICAgICAgLy8gcG9zaXRpb24gb2ZmXHJcblxyXG4gICAgICAgIC8vIGJ1dHRvbnMgbGlzdGVuZXIgb25cclxuICAgICAgICBjb25zdCBjb25maXJtQ2xvc2VXcmFwID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQobnRmbHhDb25maXJtV3JhcC5pZCk7XHJcbiAgICAgICAgY29uc3Qgb2tCdXR0b24gPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnTlhDb25maXJtQnV0dG9uT2snKTtcclxuXHJcbiAgICAgICAgLy8gb2sgYnV0dG9uIGxpc3RlbmVyIG9uXHJcbiAgICAgICAgb2tCdXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBmdW5jdGlvbiAoKSB7XHJcblxyXG4gICAgICAgICAgICAvLyBpZiBvayBjYWxsYmFjayAmJiBpZiBvayBjYWxsYmFjayBpcyBhIGZ1bmN0aW9uXHJcbiAgICAgICAgICAgIGlmIChva0J1dHRvbkNhbGxiYWNrICYmIHR5cGVvZiBva0J1dHRvbkNhbGxiYWNrID09PSAnZnVuY3Rpb24nKSB7XHJcbiAgICAgICAgICAgICAgICBva0J1dHRvbkNhbGxiYWNrKCk7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGNvbmZpcm1DbG9zZVdyYXAuY2xhc3NMaXN0LmFkZCgncmVtb3ZlJyk7XHJcblxyXG4gICAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgICAgIGNvbmZpcm1DbG9zZVdyYXAucGFyZW50Tm9kZS5yZW1vdmVDaGlsZChjb25maXJtQ2xvc2VXcmFwKTtcclxuICAgICAgICAgICAgfSwgbmV3Q29uZmlybVNldHRpbmdzLmNzc0FuaW1hdGlvbkR1cmF0aW9uKTtcclxuXHJcbiAgICAgICAgfSk7XHJcbiAgICAgICAgLy8gb2sgYnV0dG9uIGxpc3RlbmVyIG9mZlxyXG5cclxuICAgICAgICAvLyBpZiBvayBjYWxsYmFjayAmJiBpZiBvayBjYWxsYmFjayBhIGZ1bmN0aW9uID0+IGFkZCBDYW5jZWwgQnV0dG9uIGxpc3RlbmVyIG9uXHJcbiAgICAgICAgaWYgKG9rQnV0dG9uQ2FsbGJhY2sgJiYgdHlwZW9mIG9rQnV0dG9uQ2FsbGJhY2sgPT09ICdmdW5jdGlvbicpIHtcclxuXHJcbiAgICAgICAgICAgIC8vIGNhbmNlbCBidXR0b24gbGlzdGVuZXIgb25cclxuICAgICAgICAgICAgY29uc3QgY2FuY2VsQnV0dG9uID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ05YQ29uZmlybUJ1dHRvbkNhbmNlbCcpO1xyXG4gICAgICAgICAgICBjYW5jZWxCdXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBmdW5jdGlvbiAoKSB7XHJcblxyXG4gICAgICAgICAgICAgICAgLy8gaWYgY2FuY2VsIGNhbGxiYWNrICYmIGlmIGNhbmNlbCBjYWxsYmFjayBhIGZ1bmN0aW9uXHJcbiAgICAgICAgICAgICAgICBpZiAoY2FuY2VsQnV0dG9uQ2FsbGJhY2sgJiYgdHlwZW9mIGNhbmNlbEJ1dHRvbkNhbGxiYWNrID09PSAnZnVuY3Rpb24nKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgY2FuY2VsQnV0dG9uQ2FsbGJhY2soKTtcclxuICAgICAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgICAgICBjb25maXJtQ2xvc2VXcmFwLmNsYXNzTGlzdC5hZGQoJ3JlbW92ZScpO1xyXG5cclxuICAgICAgICAgICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAgICAgICAgIGNvbmZpcm1DbG9zZVdyYXAucGFyZW50Tm9kZS5yZW1vdmVDaGlsZChjb25maXJtQ2xvc2VXcmFwKTtcclxuICAgICAgICAgICAgICAgIH0sIG5ld0NvbmZpcm1TZXR0aW5ncy5jc3NBbmltYXRpb25EdXJhdGlvbik7XHJcblxyXG4gICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgLy8gY2FuY2VsIGJ1dHRvbiBsaXN0ZW5lciBvZmZcclxuXHJcbiAgICAgICAgfVxyXG4gICAgICAgIC8vIGlmIG9rIGNhbGxiYWNrICYmIGlmIG9rIGNhbGxiYWNrIGEgZnVuY3Rpb24gPT4gYWRkIENhbmNlbCBCdXR0b24gbGlzdGVuZXIgb2ZmXHJcbiAgICAgICAgLy8gYnV0dG9ucyBsaXN0ZW5lciBvZmZcclxuXHJcbiAgICB9XHJcbiAgICAvLyBpZiB0aGVyZSBpcyBubyBjb25maXJtIGJveCBvZmZcclxuXHJcbn1cclxuLy8gTm90aWZsaXg6IENvbmZpcm0gU2luZ2xlIG9mZlxyXG5cclxuXHJcbi8vIE5vdGlmbGl4OiBMb2FkaW5nIFNpbmdsZSBvblxyXG5jb25zdCBOb3RpZmxpeExvYWRpbmcgPSBmdW5jdGlvbiAobWVzc2FnZSwgaWNvblR5cGUsIGRpc3BsYXksIHRoZURlbGF5KSB7XHJcblxyXG4gICAgaWYgKGRpc3BsYXkpIHsgLy8gc2hvdyBpdFxyXG5cclxuICAgICAgICAvLyBpZiBtZXNzYWdlIHNldHRpbmdzIG9uXHJcbiAgICAgICAgaWYgKG1lc3NhZ2UudG9TdHJpbmcoKS5sZW5ndGggPiBuZXdMb2FkaW5nU2V0dGluZ3MubWVzc2FnZU1heExlbmd0aCkge1xyXG4gICAgICAgICAgICBtZXNzYWdlID0gYCR7bm90aWZsaXhQbGFpbnRleHQobWVzc2FnZSkudG9TdHJpbmcoKS5zdWJzdHJpbmcoMCwgbmV3TG9hZGluZ1NldHRpbmdzLm1lc3NhZ2VNYXhMZW5ndGgpfS4uLmA7XHJcbiAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgbWVzc2FnZSA9IGAke25vdGlmbGl4UGxhaW50ZXh0KG1lc3NhZ2UpLnRvU3RyaW5nKCl9YDtcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIGxldCBpbnRTdmdTaXplID0gcGFyc2VJbnQobmV3TG9hZGluZ1NldHRpbmdzLnN2Z1NpemUuc2xpY2UoMCwgLTIpKTtcclxuICAgICAgICBsZXQgbWVzc2FnZUhUTUwgPSAnJztcclxuICAgICAgICBpZiAobWVzc2FnZS5sZW5ndGggPiAwKSB7XHJcblxyXG4gICAgICAgICAgICBsZXQgbWVzc2FnZVBvc1RvcCA9IGAke3BhcnNlSW50KE1hdGgucm91bmQoaW50U3ZnU2l6ZSAtIChpbnRTdmdTaXplIC8gNCkpKS50b1N0cmluZygpfXB4YDtcclxuICAgICAgICAgICAgbGV0IG1lc3NhZ2VIZWlnaHQgPSBgJHsocGFyc2VJbnQobmV3TG9hZGluZ1NldHRpbmdzLm1lc3NhZ2VGb250U2l6ZS5zbGljZSgwLCAyKSkgKiAxLjIpLnRvU3RyaW5nKCl9cHhgO1xyXG5cclxuICAgICAgICAgICAgbWVzc2FnZUhUTUwgPSBgPHAgaWQ9XCIke25ld0xvYWRpbmdTZXR0aW5ncy5tZXNzYWdlSUR9XCIgY2xhc3M9XCJsb2FkaW5nLW1lc3NhZ2VcIiBzdHlsZT1cImNvbG9yOiR7bmV3TG9hZGluZ1NldHRpbmdzLm1lc3NhZ2VDb2xvcn07Zm9udC1zaXplOiR7bmV3TG9hZGluZ1NldHRpbmdzLm1lc3NhZ2VGb250U2l6ZX07aGVpZ2h0OiR7bWVzc2FnZUhlaWdodH07IHRvcDoke21lc3NhZ2VQb3NUb3B9O1wiPiR7bWVzc2FnZX08L3A+YDtcclxuXHJcbiAgICAgICAgfVxyXG4gICAgICAgIC8vIGlmIG1lc3NhZ2Ugc2V0dGluZ3Mgb2ZmXHJcblxyXG4gICAgICAgIC8vIGlmIGNzc0FuaW1haW9uIGZhbHNlIC0+IGR1cmF0aW9uIG9uXHJcbiAgICAgICAgaWYgKCFuZXdMb2FkaW5nU2V0dGluZ3MuY3NzQW5pbWF0aW9uKSB7XHJcbiAgICAgICAgICAgIG5ld0xvYWRpbmdTZXR0aW5ncy5jc3NBbmltYXRpb25EdXJhdGlvbiA9IDA7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIC8vIGlmIGNzc0FuaW1haW9uIGZhbHNlIC0+IGR1cmF0aW9uIG9mZlxyXG5cclxuICAgICAgICAvLyBzdmdJY29uIG9uXHJcbiAgICAgICAgbGV0IHN2Z0ljb24gPSAnJztcclxuICAgICAgICBpZiAoaWNvblR5cGUgPT09ICdzdGFuZGFyZCcpIHtcclxuICAgICAgICAgICAgc3ZnSWNvbiA9IG5vdGlmbGl4TG9hZGluZ1N2Z1N0YW5kYXJkKG5ld0xvYWRpbmdTZXR0aW5ncy5zdmdTaXplLCBuZXdMb2FkaW5nU2V0dGluZ3Muc3ZnQ29sb3IpO1xyXG4gICAgICAgIH0gZWxzZSBpZiAoaWNvblR5cGUgPT09ICdob3VyZ2xhc3MnKSB7XHJcbiAgICAgICAgICAgIHN2Z0ljb24gPSBub3RpZmxpeExvYWRpbmdTdmdIb3VyZ2xhc3MobmV3TG9hZGluZ1NldHRpbmdzLnN2Z1NpemUsIG5ld0xvYWRpbmdTZXR0aW5ncy5zdmdDb2xvcik7XHJcbiAgICAgICAgfSBlbHNlIGlmIChpY29uVHlwZSA9PT0gJ2NpcmNsZScpIHtcclxuICAgICAgICAgICAgc3ZnSWNvbiA9IG5vdGlmbGl4TG9hZGluZ1N2Z0NpcmNsZShuZXdMb2FkaW5nU2V0dGluZ3Muc3ZnU2l6ZSwgbmV3TG9hZGluZ1NldHRpbmdzLnN2Z0NvbG9yKTtcclxuICAgICAgICB9IGVsc2UgaWYgKGljb25UeXBlID09PSAnYXJyb3dzJykge1xyXG4gICAgICAgICAgICBzdmdJY29uID0gbm90aWZsaXhMb2FkaW5nU3ZnQXJyb3dzKG5ld0xvYWRpbmdTZXR0aW5ncy5zdmdTaXplLCBuZXdMb2FkaW5nU2V0dGluZ3Muc3ZnQ29sb3IpO1xyXG4gICAgICAgIH0gZWxzZSBpZiAoaWNvblR5cGUgPT09ICdkb3RzJykge1xyXG4gICAgICAgICAgICBzdmdJY29uID0gbm90aWZsaXhMb2FkaW5nU3ZnRG90cyhuZXdMb2FkaW5nU2V0dGluZ3Muc3ZnU2l6ZSwgbmV3TG9hZGluZ1NldHRpbmdzLnN2Z0NvbG9yKTtcclxuICAgICAgICB9IGVsc2UgaWYgKGljb25UeXBlID09PSAncHVsc2UnKSB7XHJcbiAgICAgICAgICAgIHN2Z0ljb24gPSBub3RpZmxpeExvYWRpbmdTdmdQdWxzZShuZXdMb2FkaW5nU2V0dGluZ3Muc3ZnU2l6ZSwgbmV3TG9hZGluZ1NldHRpbmdzLnN2Z0NvbG9yKTtcclxuICAgICAgICB9IGVsc2UgaWYgKGljb25UeXBlID09PSAnY3VzdG9tJyAmJiBuZXdMb2FkaW5nU2V0dGluZ3MuY3VzdG9tU3ZnVXJsICE9PSBudWxsKSB7XHJcbiAgICAgICAgICAgIHN2Z0ljb24gPSBgPGltZyBjbGFzcz1cImN1c3RvbS1sb2FkaW5nLWljb25cIiB3aWR0aD1cIiR7bmV3TG9hZGluZ1NldHRpbmdzLnN2Z1NpemV9XCIgaGVpZ2h0PVwiJHtuZXdMb2FkaW5nU2V0dGluZ3Muc3ZnU2l6ZX1cIiBzcmM9XCIke25ld0xvYWRpbmdTZXR0aW5ncy5jdXN0b21TdmdVcmx9XCIgYWx0PVwiTm90aWZsaXhcIj5gO1xyXG4gICAgICAgIH0gZWxzZSBpZiAoaWNvblR5cGUgPT09ICdjdXN0b20nICYmIG5ld0xvYWRpbmdTZXR0aW5ncy5jdXN0b21TdmdVcmwgPT0gbnVsbCkge1xyXG4gICAgICAgICAgICBub3RpZmxpeENvbnNvbGVFcnJvcignTm90aWZsaXggRXJyb3InLCAnWW91IGhhdmUgdG8gc2V0IGEgc3RhdGljIFNWRyB1cmwgdG8gXCJjdXN0b21TdmdVcmxcIiBvcHRpb24gdG8gdXNlIExvYWRpbmcgQ3VzdG9tLicpO1xyXG4gICAgICAgICAgICByZXR1cm4gZmFsc2U7XHJcbiAgICAgICAgfSBlbHNlIGlmIChpY29uVHlwZSA9PT0gJ25vdGlmbGl4Jykge1xyXG4gICAgICAgICAgICBzdmdJY29uID0gbm90aWZsaXhMb2FkaW5nU3ZnTm90aWZsaXgobmV3TG9hZGluZ1NldHRpbmdzLnN2Z1NpemUsICcjZjhmOGY4JywgJyMwMGI0NjInKTtcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIGxldCBzdmdQb3NUb3AgPSAwO1xyXG4gICAgICAgIGlmIChtZXNzYWdlLmxlbmd0aCA+IDApIHtcclxuICAgICAgICAgICAgc3ZnUG9zVG9wID0gYC0ke3BhcnNlSW50KE1hdGgucm91bmQoaW50U3ZnU2l6ZSAtIChpbnRTdmdTaXplIC8gNCkpKS50b1N0cmluZygpfXB4YDtcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIGxldCBzdmdJY29uSFRNTCA9IGA8ZGl2IHN0eWxlPVwidG9wOiR7c3ZnUG9zVG9wfTsgd2lkdGg6JHtuZXdMb2FkaW5nU2V0dGluZ3Muc3ZnU2l6ZX07IGhlaWdodDoke25ld0xvYWRpbmdTZXR0aW5ncy5zdmdTaXplfTtcIiBjbGFzcz1cIiR7bmV3TG9hZGluZ1NldHRpbmdzLmNsYXNzTmFtZX0taWNvbiAkeyhtZXNzYWdlLmxlbmd0aCA+IDAgPyAnd2l0aC1tZXNzYWdlJyA6ICcnKX1cIj4ke3N2Z0ljb259PC9kaXY+YDtcclxuICAgICAgICAvLyBzdmdJY29uIG9mZlxyXG5cclxuXHJcbiAgICAgICAgLy8gbG9hZGluZyB3cmFwIG9uXHJcbiAgICAgICAgY29uc3QgZG9jQm9keSA9IGRvY3VtZW50LmJvZHk7XHJcblxyXG4gICAgICAgIGNvbnN0IG50Zmx4TG9hZGluZ1dyYXAgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcclxuICAgICAgICBudGZseExvYWRpbmdXcmFwLmlkID0gbG9hZGluZ1NldHRpbmdzLklEO1xyXG4gICAgICAgIG50Zmx4TG9hZGluZ1dyYXAuY2xhc3NOYW1lID0gYCR7bmV3TG9hZGluZ1NldHRpbmdzLmNsYXNzTmFtZX0gJHsobmV3TG9hZGluZ1NldHRpbmdzLmNzc0FuaW1hdGlvbiA/ICd3aXRoLWFuaW1hdGlvbicgOiAnJyl9ICR7KG5ld0xvYWRpbmdTZXR0aW5ncy5jbGlja1RvQ2xvc2UgPyAnY2xpY2stdG8tY2xvc2UnIDogJycpfWA7XHJcbiAgICAgICAgbnRmbHhMb2FkaW5nV3JhcC5zdHlsZS56SW5kZXggPSBuZXdMb2FkaW5nU2V0dGluZ3MuemluZGV4O1xyXG4gICAgICAgIG50Zmx4TG9hZGluZ1dyYXAuc3R5bGUuYmFja2dyb3VuZCA9IG5ld0xvYWRpbmdTZXR0aW5ncy5iYWNrZ3JvdW5kQ29sb3I7XHJcbiAgICAgICAgbnRmbHhMb2FkaW5nV3JhcC5zdHlsZS5hbmltYXRpb25EdXJhdGlvbiA9IGAke25ld0xvYWRpbmdTZXR0aW5ncy5jc3NBbmltYXRpb25EdXJhdGlvbn1tc2A7XHJcblxyXG4gICAgICAgIC8vIGZvbnQtZmFtaWx5IG9uXHJcbiAgICAgICAgbnRmbHhMb2FkaW5nV3JhcC5zdHlsZS5mb250RmFtaWx5ID0gYFwiJHtuZXdMb2FkaW5nU2V0dGluZ3MuZm9udEZhbWlseX1cIiwgc2Fucy1zZXJpZmA7XHJcbiAgICAgICAgLy8gZm9udC1mYW1pbHkgb2ZmXHJcblxyXG4gICAgICAgIC8vIHJ0bCBvblxyXG4gICAgICAgIGlmIChuZXdMb2FkaW5nU2V0dGluZ3MucnRsKSB7XHJcbiAgICAgICAgICAgIG50Zmx4TG9hZGluZ1dyYXAuc2V0QXR0cmlidXRlKCdkaXInLCAncnRsJyk7XHJcbiAgICAgICAgICAgIG50Zmx4TG9hZGluZ1dyYXAuY2xhc3NMaXN0LmFkZCgncnRsLW9uJyk7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIC8vIHJ0bCBvZmZcclxuXHJcbiAgICAgICAgLy8gYXBwZW5kIG9uXHJcbiAgICAgICAgbnRmbHhMb2FkaW5nV3JhcC5pbm5lckhUTUwgPSBgJHtzdmdJY29uSFRNTH0gJHttZXNzYWdlSFRNTH1gOyAvLyBpbm5lciBodG1sXHJcblxyXG4gICAgICAgIGlmICghZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQobnRmbHhMb2FkaW5nV3JhcC5pZCkpIHsgLy8gaWYgbm90IGxvYWRpbmdcclxuXHJcbiAgICAgICAgICAgIGRvY0JvZHkuYXBwZW5kQ2hpbGQobnRmbHhMb2FkaW5nV3JhcCk7IC8vIGFwcGVuZFxyXG5cclxuICAgICAgICAgICAgLy8gaWYgY2xpY2sgdG8gY2xvc2Ugb24gICAgICAgICAgICBcclxuICAgICAgICAgICAgaWYgKG5ld0xvYWRpbmdTZXR0aW5ncy5jbGlja1RvQ2xvc2UpIHtcclxuXHJcbiAgICAgICAgICAgICAgICBjb25zdCBsb2FkaW5nV3JhcEVsbSA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKG50Zmx4TG9hZGluZ1dyYXAuaWQpO1xyXG5cclxuICAgICAgICAgICAgICAgIGxvYWRpbmdXcmFwRWxtLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgZnVuY3Rpb24gKCkge1xyXG5cclxuICAgICAgICAgICAgICAgICAgICBudGZseExvYWRpbmdXcmFwLmNsYXNzTGlzdC5hZGQoJ3JlbW92ZScpO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgbnRmbHhMb2FkaW5nV3JhcC5wYXJlbnROb2RlLnJlbW92ZUNoaWxkKG50Zmx4TG9hZGluZ1dyYXApO1xyXG4gICAgICAgICAgICAgICAgICAgIH0sIG5ld0xvYWRpbmdTZXR0aW5ncy5jc3NBbmltYXRpb25EdXJhdGlvbik7XHJcblxyXG4gICAgICAgICAgICAgICAgfSk7XHJcblxyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIC8vIGlmIGNsaWNrIHRvIGNsb3NlIG9mZlxyXG4gICAgICAgIH1cclxuICAgICAgICAvLyBhcHBlbmQgb2ZmXHJcblxyXG4gICAgfSBlbHNlIHsgLy8gUmVtb3ZlXHJcblxyXG4gICAgICAgIGlmIChkb2N1bWVudC5nZXRFbGVtZW50QnlJZChsb2FkaW5nU2V0dGluZ3MuSUQpKSB7IC8vIGlmIGhhcyBhbnkgbG9hZGluZ1xyXG5cclxuICAgICAgICAgICAgY29uc3QgbG9hZGluZ0VsbSA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKGxvYWRpbmdTZXR0aW5ncy5JRCk7XHJcblxyXG4gICAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcclxuXHJcbiAgICAgICAgICAgICAgICBsb2FkaW5nRWxtLmNsYXNzTGlzdC5hZGQoJ3JlbW92ZScpO1xyXG5cclxuICAgICAgICAgICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAgICAgICAgIGxvYWRpbmdFbG0ucGFyZW50Tm9kZS5yZW1vdmVDaGlsZChsb2FkaW5nRWxtKTtcclxuICAgICAgICAgICAgICAgIH0sIG5ld0xvYWRpbmdTZXR0aW5ncy5jc3NBbmltYXRpb25EdXJhdGlvbik7XHJcblxyXG4gICAgICAgICAgICB9LCB0aGVEZWxheSk7XHJcblxyXG4gICAgICAgIH1cclxuXHJcbiAgICB9XHJcblxyXG59XHJcbi8vIE5vdGlmbGl4OiBMb2FkaW5nIFNpbmdsZSBvZmZcclxuXHJcbi8vIE5vdGlmbGl4OiBMb2FkaW5nIENoYW5nZSBNZXNzYWdlIG9uXHJcbmNvbnN0IE5vdGlmbGl4TG9hZGluZ0NoYW5nZSA9IGZ1bmN0aW9uIChuZXdNZXNzYWdlKSB7XHJcblxyXG4gICAgaWYgKGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKGxvYWRpbmdTZXR0aW5ncy5JRCkpIHsgLy8gaWYgaGFzIGFueSBsb2FkaW5nXHJcblxyXG4gICAgICAgIGlmIChuZXdNZXNzYWdlLnRvU3RyaW5nKCkubGVuZ3RoID4gbmV3TG9hZGluZ1NldHRpbmdzLm1lc3NhZ2VNYXhMZW5ndGgpIHtcclxuICAgICAgICAgICAgbmV3TWVzc2FnZSA9IGAke25vdGlmbGl4UGxhaW50ZXh0KG5ld01lc3NhZ2UpLnRvU3RyaW5nKCkuc3Vic3RyaW5nKDAsIG5ld0xvYWRpbmdTZXR0aW5ncy5tZXNzYWdlTWF4TGVuZ3RoKX0uLi5gO1xyXG4gICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgIG5ld01lc3NhZ2UgPSBub3RpZmxpeFBsYWludGV4dChuZXdNZXNzYWdlKS50b1N0cmluZygpO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgaWYgKG5ld01lc3NhZ2UubGVuZ3RoID4gMCkgeyAvLyBpZiBoYXMgYW55IG1lc3NhZ2VcclxuXHJcbiAgICAgICAgICAgIGxldCBvbGRNZXNzYWdlRWxtID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQobG9hZGluZ1NldHRpbmdzLklEKS5nZXRFbGVtZW50c0J5VGFnTmFtZSgncCcpWzBdO1xyXG5cclxuICAgICAgICAgICAgaWYgKG9sZE1lc3NhZ2VFbG0gIT09IHVuZGVmaW5lZCkgeyAvLyB0aGVyZSBpcyBhIG1lc3NhZ2UgZWxlbWVudFxyXG5cclxuICAgICAgICAgICAgICAgIG9sZE1lc3NhZ2VFbG0uaW5uZXJIVE1MID0gbmV3TWVzc2FnZTsgLy8gY2hhbmdlIHRoZSBtZXNzYWdlXHJcblxyXG4gICAgICAgICAgICB9IGVsc2UgeyAvLyB0aGVyZSBpcyBubyBtZXNzYWdlIGVsZW1lbnRcclxuXHJcbiAgICAgICAgICAgICAgICAvLyBjcmVhdGUgYSBuZXcgbWVzc2FnZSBlbGVtZW50IG9uXHJcbiAgICAgICAgICAgICAgICBjb25zdCBuZXdNZXNzYWdlSFRNTCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ3AnKTtcclxuICAgICAgICAgICAgICAgIG5ld01lc3NhZ2VIVE1MLmlkID0gbmV3TG9hZGluZ1NldHRpbmdzLm1lc3NhZ2VJRDtcclxuICAgICAgICAgICAgICAgIG5ld01lc3NhZ2VIVE1MLmNsYXNzTmFtZSA9ICdsb2FkaW5nLW1lc3NhZ2UgbmV3JztcclxuXHJcbiAgICAgICAgICAgICAgICBuZXdNZXNzYWdlSFRNTC5zdHlsZS5jb2xvciA9IG5ld0xvYWRpbmdTZXR0aW5ncy5tZXNzYWdlQ29sb3I7XHJcbiAgICAgICAgICAgICAgICBuZXdNZXNzYWdlSFRNTC5zdHlsZS5mb250U2l6ZSA9IG5ld0xvYWRpbmdTZXR0aW5ncy5tZXNzYWdlRm9udFNpemU7XHJcblxyXG4gICAgICAgICAgICAgICAgY29uc3QgaW50U3ZnU2l6ZSA9IHBhcnNlSW50KG5ld0xvYWRpbmdTZXR0aW5ncy5zdmdTaXplLnNsaWNlKDAsIC0yKSk7XHJcbiAgICAgICAgICAgICAgICBjb25zdCBtZXNzYWdlUG9zVG9wID0gYCR7cGFyc2VJbnQoTWF0aC5yb3VuZChpbnRTdmdTaXplIC0gKGludFN2Z1NpemUgLyA0KSkpLnRvU3RyaW5nKCl9cHhgO1xyXG4gICAgICAgICAgICAgICAgbmV3TWVzc2FnZUhUTUwuc3R5bGUudG9wID0gbWVzc2FnZVBvc1RvcDtcclxuXHJcbiAgICAgICAgICAgICAgICBjb25zdCBtZXNzYWdlSGVpZ2h0ID0gYCR7KHBhcnNlSW50KG5ld0xvYWRpbmdTZXR0aW5ncy5tZXNzYWdlRm9udFNpemUuc2xpY2UoMCwgMikpICogMS4yKS50b1N0cmluZygpfXB4YDtcclxuICAgICAgICAgICAgICAgIG5ld01lc3NhZ2VIVE1MLnN0eWxlLmhlaWdodCA9IG1lc3NhZ2VIZWlnaHQ7XHJcblxyXG4gICAgICAgICAgICAgICAgbmV3TWVzc2FnZUhUTUwuaW5uZXJIVE1MID0gbmV3TWVzc2FnZTtcclxuXHJcbiAgICAgICAgICAgICAgICBjb25zdCBtZXNzYWdlV3JhcCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKGxvYWRpbmdTZXR0aW5ncy5JRCk7XHJcbiAgICAgICAgICAgICAgICBtZXNzYWdlV3JhcC5hcHBlbmRDaGlsZChuZXdNZXNzYWdlSFRNTCk7XHJcbiAgICAgICAgICAgICAgICAvLyBjcmVhdGUgYSBuZXcgbWVzc2FnZSBlbGVtZW50IG9mZlxyXG5cclxuICAgICAgICAgICAgICAgIC8vIHZlcnRpY2FsIGFsaWduIHN2ZyBvblxyXG4gICAgICAgICAgICAgICAgY29uc3Qgc3ZnRGl2RWxtID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQobG9hZGluZ1NldHRpbmdzLklEKS5nZXRFbGVtZW50c0J5VGFnTmFtZSgnZGl2JylbMF07XHJcbiAgICAgICAgICAgICAgICBjb25zdCBzdmdOZXdQb3NUb3AgPSBgLSR7cGFyc2VJbnQoTWF0aC5yb3VuZChpbnRTdmdTaXplIC0gKGludFN2Z1NpemUgLyA0KSkpLnRvU3RyaW5nKCl9cHhgO1xyXG4gICAgICAgICAgICAgICAgc3ZnRGl2RWxtLnN0eWxlLnRvcCA9IHN2Z05ld1Bvc1RvcDtcclxuICAgICAgICAgICAgICAgIC8vIHZlcnRpY2FsIGFsaWduIHN2ZyBvZmZcclxuXHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgfSBlbHNlIHsgLy8gaWYgbm8gbWVzc2FnZVxyXG4gICAgICAgICAgICBub3RpZmxpeENvbnNvbGVFcnJvcignTm90aWZsaXggRXJyb3InLCAnV2hlcmUgaXMgdGhlIG5ldyBtZXNzYWdlPycpO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICB9XHJcblxyXG59XHJcbi8vIE5vdGlmbGl4OiBMb2FkaW5nIENoYW5nZSBNZXNzYWdlIG9mZlxyXG5cclxuXHJcbi8vIE5vdGlmbGl4OiBMb2FkaW5nIFNWRyBzdGFuZGFyZCBvblxyXG5jb25zdCBub3RpZmxpeExvYWRpbmdTdmdTdGFuZGFyZCA9ICh3aWR0aCwgY29sb3IpID0+IHtcclxuICAgIGlmICghd2lkdGgpIHsgd2lkdGggPSAnNjBweCc7IH1cclxuICAgIGlmICghY29sb3IpIHsgY29sb3IgPSAnIzAwYjQ2Mic7IH1cclxuICAgIGNvbnN0IHN0YW5kYXJkID0gYDxzdmcgc3Ryb2tlPVwiJHtjb2xvcn1cIiB3aWR0aD1cIiR7d2lkdGh9XCIgaGVpZ2h0PVwiJHt3aWR0aH1cIiB2aWV3Qm94PVwiMCAwIDM4IDM4XCIgc3R5bGU9XCJ0cmFuc2Zvcm06c2NhbGUoMC44KTtcIiB4bWxucz1cImh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnXCI+PGcgZmlsbD1cIm5vbmVcIiBmaWxsLXJ1bGU9XCJldmVub2RkXCI+PGcgdHJhbnNmb3JtPVwidHJhbnNsYXRlKDEgMSlcIiBzdHJva2Utd2lkdGg9XCIyXCI+PGNpcmNsZSBzdHJva2Utb3BhY2l0eT1cIi4yNVwiIGN4PVwiMThcIiBjeT1cIjE4XCIgcj1cIjE4XCIvPjxwYXRoIGQ9XCJNMzYgMThjMC05Ljk0LTguMDYtMTgtMTgtMThcIj48YW5pbWF0ZVRyYW5zZm9ybSBhdHRyaWJ1dGVOYW1lPVwidHJhbnNmb3JtXCIgdHlwZT1cInJvdGF0ZVwiIGZyb209XCIwIDE4IDE4XCIgdG89XCIzNjAgMTggMThcIiBkdXI9XCIxc1wiIHJlcGVhdENvdW50PVwiaW5kZWZpbml0ZVwiLz48L3BhdGg+PC9nPjwvZz48L3N2Zz5gO1xyXG4gICAgcmV0dXJuIHN0YW5kYXJkO1xyXG59XHJcbi8vIE5vdGlmbGl4OiBMb2FkaW5nIFNWRyBzdGFuZGFyZCBvZmZcclxuXHJcbi8vIE5vdGlmbGl4OiBMb2FkaW5nIFNWRyBob3VyZ2xhc3Mgb25cclxuY29uc3Qgbm90aWZsaXhMb2FkaW5nU3ZnSG91cmdsYXNzID0gKHdpZHRoLCBjb2xvcikgPT4ge1xyXG4gICAgaWYgKCF3aWR0aCkgeyB3aWR0aCA9ICc2MHB4JzsgfVxyXG4gICAgaWYgKCFjb2xvcikgeyBjb2xvciA9ICcjMDBiNDYyJzsgfVxyXG4gICAgY29uc3QgaG91cmdsYXNzID0gYDxzdmcgaWQ9XCJOWExvYWRpbmdIb3VyZ2xhc3NcIiBmaWxsPVwiJHtjb2xvcn1cIiB3aWR0aD1cIiR7d2lkdGh9XCIgaGVpZ2h0PVwiJHt3aWR0aH1cIiB4bWxucz1cImh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnXCIgeG1sbnM6eGxpbms9XCJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rXCIgeG1sOnNwYWNlPVwicHJlc2VydmVcIiB2ZXJzaW9uPVwiMS4xXCIgc3R5bGU9XCJzaGFwZS1yZW5kZXJpbmc6Z2VvbWV0cmljUHJlY2lzaW9uOyB0ZXh0LXJlbmRlcmluZzpnZW9tZXRyaWNQcmVjaXNpb247IGltYWdlLXJlbmRlcmluZzpvcHRpbWl6ZVF1YWxpdHk7IGZpbGwtcnVsZTpldmVub2RkOyBjbGlwLXJ1bGU6ZXZlbm9kZFwiIHZpZXdCb3g9XCIwIDAgMjAwIDIwMFwiPjxzdHlsZT5ALXdlYmtpdC1rZXlmcmFtZXMgTlhob3VyZ2xhc3M1LWFuaW1hdGlvbnswJXstd2Via2l0LXRyYW5zZm9ybTogc2NhbGUoMSwgMSk7dHJhbnNmb3JtOiBzY2FsZSgxLCAxKTt9MTYuNjcley13ZWJraXQtdHJhbnNmb3JtOiBzY2FsZSgxLCAwLjgpO3RyYW5zZm9ybTogc2NhbGUoMSwgMC44KTt9MzMuMzMley13ZWJraXQtdHJhbnNmb3JtOiBzY2FsZSgwLjg4LCAwLjYpO3RyYW5zZm9ybTogc2NhbGUoMC44OCwgMC42KTt9MzcuNTAley13ZWJraXQtdHJhbnNmb3JtOiBzY2FsZSgwLjg1LCAwLjU1KTt0cmFuc2Zvcm06IHNjYWxlKDAuODUsIDAuNTUpO300MS42NyV7LXdlYmtpdC10cmFuc2Zvcm06IHNjYWxlKDAuOCwgMC41KTt0cmFuc2Zvcm06IHNjYWxlKDAuOCwgMC41KTt9NDUuODMley13ZWJraXQtdHJhbnNmb3JtOiBzY2FsZSgwLjc1LCAwLjQ1KTt0cmFuc2Zvcm06IHNjYWxlKDAuNzUsIDAuNDUpO301MCV7LXdlYmtpdC10cmFuc2Zvcm06IHNjYWxlKDAuNywgMC40KTt0cmFuc2Zvcm06IHNjYWxlKDAuNywgMC40KTt9NTQuMTcley13ZWJraXQtdHJhbnNmb3JtOiBzY2FsZSgwLjYsIDAuMzUpO3RyYW5zZm9ybTogc2NhbGUoMC42LCAwLjM1KTt9NTguMzMley13ZWJraXQtdHJhbnNmb3JtOiBzY2FsZSgwLjUsIDAuMyk7dHJhbnNmb3JtOiBzY2FsZSgwLjUsIDAuMyk7fTgzLjMzJXstd2Via2l0LXRyYW5zZm9ybTogc2NhbGUoMC4yLCAwKTt0cmFuc2Zvcm06IHNjYWxlKDAuMiwgMCk7fTEwMCV7LXdlYmtpdC10cmFuc2Zvcm06IHNjYWxlKDAuMiwgMCk7dHJhbnNmb3JtOiBzY2FsZSgwLjIsIDApO319QGtleWZyYW1lcyBOWGhvdXJnbGFzczUtYW5pbWF0aW9uezAley13ZWJraXQtdHJhbnNmb3JtOiBzY2FsZSgxLCAxKTt0cmFuc2Zvcm06IHNjYWxlKDEsIDEpO30xNi42NyV7LXdlYmtpdC10cmFuc2Zvcm06IHNjYWxlKDEsIDAuOCk7dHJhbnNmb3JtOiBzY2FsZSgxLCAwLjgpO30zMy4zMyV7LXdlYmtpdC10cmFuc2Zvcm06IHNjYWxlKDAuODgsIDAuNik7dHJhbnNmb3JtOiBzY2FsZSgwLjg4LCAwLjYpO30zNy41MCV7LXdlYmtpdC10cmFuc2Zvcm06IHNjYWxlKDAuODUsIDAuNTUpO3RyYW5zZm9ybTogc2NhbGUoMC44NSwgMC41NSk7fTQxLjY3JXstd2Via2l0LXRyYW5zZm9ybTogc2NhbGUoMC44LCAwLjUpO3RyYW5zZm9ybTogc2NhbGUoMC44LCAwLjUpO300NS44MyV7LXdlYmtpdC10cmFuc2Zvcm06IHNjYWxlKDAuNzUsIDAuNDUpO3RyYW5zZm9ybTogc2NhbGUoMC43NSwgMC40NSk7fTUwJXstd2Via2l0LXRyYW5zZm9ybTogc2NhbGUoMC43LCAwLjQpO3RyYW5zZm9ybTogc2NhbGUoMC43LCAwLjQpO301NC4xNyV7LXdlYmtpdC10cmFuc2Zvcm06IHNjYWxlKDAuNiwgMC4zNSk7dHJhbnNmb3JtOiBzY2FsZSgwLjYsIDAuMzUpO301OC4zMyV7LXdlYmtpdC10cmFuc2Zvcm06IHNjYWxlKDAuNSwgMC4zKTt0cmFuc2Zvcm06IHNjYWxlKDAuNSwgMC4zKTt9ODMuMzMley13ZWJraXQtdHJhbnNmb3JtOiBzY2FsZSgwLjIsIDApO3RyYW5zZm9ybTogc2NhbGUoMC4yLCAwKTt9MTAwJXstd2Via2l0LXRyYW5zZm9ybTogc2NhbGUoMC4yLCAwKTt0cmFuc2Zvcm06IHNjYWxlKDAuMiwgMCk7fX1ALXdlYmtpdC1rZXlmcmFtZXMgTlhob3VyZ2xhc3MzLWFuaW1hdGlvbnswJXstd2Via2l0LXRyYW5zZm9ybTogc2NhbGUoMSwgMC4wMik7dHJhbnNmb3JtOiBzY2FsZSgxLCAwLjAyKTt9NzkuMTcley13ZWJraXQtdHJhbnNmb3JtOiBzY2FsZSgxLCAxKTt0cmFuc2Zvcm06IHNjYWxlKDEsIDEpO30xMDAley13ZWJraXQtdHJhbnNmb3JtOiBzY2FsZSgxLCAxKTt0cmFuc2Zvcm06IHNjYWxlKDEsIDEpO319QGtleWZyYW1lcyBOWGhvdXJnbGFzczMtYW5pbWF0aW9uezAley13ZWJraXQtdHJhbnNmb3JtOiBzY2FsZSgxLCAwLjAyKTt0cmFuc2Zvcm06IHNjYWxlKDEsIDAuMDIpO303OS4xNyV7LXdlYmtpdC10cmFuc2Zvcm06IHNjYWxlKDEsIDEpO3RyYW5zZm9ybTogc2NhbGUoMSwgMSk7fTEwMCV7LXdlYmtpdC10cmFuc2Zvcm06IHNjYWxlKDEsIDEpO3RyYW5zZm9ybTogc2NhbGUoMSwgMSk7fX1ALXdlYmtpdC1rZXlmcmFtZXMgTlhob3VyZ2xhc3MxLWFuaW1hdGlvbnswJXstd2Via2l0LXRyYW5zZm9ybTogcm90YXRlKDBkZWcpO3RyYW5zZm9ybTogcm90YXRlKDBkZWcpO304My4zMyV7LXdlYmtpdC10cmFuc2Zvcm06IHJvdGF0ZSgwZGVnKTt0cmFuc2Zvcm06IHJvdGF0ZSgwZGVnKTt9MTAwJXstd2Via2l0LXRyYW5zZm9ybTogcm90YXRlKDE4MGRlZyk7dHJhbnNmb3JtOiByb3RhdGUoMTgwZGVnKTt9fUBrZXlmcmFtZXMgTlhob3VyZ2xhc3MxLWFuaW1hdGlvbnswJXstd2Via2l0LXRyYW5zZm9ybTogcm90YXRlKDBkZWcpO3RyYW5zZm9ybTogcm90YXRlKDBkZWcpO304My4zMyV7LXdlYmtpdC10cmFuc2Zvcm06IHJvdGF0ZSgwZGVnKTt0cmFuc2Zvcm06IHJvdGF0ZSgwZGVnKTt9MTAwJXstd2Via2l0LXRyYW5zZm9ybTogcm90YXRlKDE4MGRlZyk7dHJhbnNmb3JtOiByb3RhdGUoMTgwZGVnKTt9fSNOWExvYWRpbmdIb3VyZ2xhc3MgKnstd2Via2l0LWFuaW1hdGlvbi1kdXJhdGlvbjogMS4yczthbmltYXRpb24tZHVyYXRpb246IDEuMnM7LXdlYmtpdC1hbmltYXRpb24taXRlcmF0aW9uLWNvdW50OiBpbmZpbml0ZTthbmltYXRpb24taXRlcmF0aW9uLWNvdW50OiBpbmZpbml0ZTstd2Via2l0LWFuaW1hdGlvbi10aW1pbmctZnVuY3Rpb246IGN1YmljLWJlemllcigwLCAwLCAxLCAxKTthbmltYXRpb24tdGltaW5nLWZ1bmN0aW9uOiBjdWJpYy1iZXppZXIoMCwgMCwgMSwgMSk7fSNOWGhvdXJnbGFzczd7ZmlsbDogaW5oZXJpdDt9I05YaG91cmdsYXNzMXstd2Via2l0LWFuaW1hdGlvbi1uYW1lOiBOWGhvdXJnbGFzczEtYW5pbWF0aW9uO2FuaW1hdGlvbi1uYW1lOiBOWGhvdXJnbGFzczEtYW5pbWF0aW9uOy13ZWJraXQtdHJhbnNmb3JtLW9yaWdpbjogNTAlIDUwJTt0cmFuc2Zvcm0tb3JpZ2luOiA1MCUgNTAlO3RyYW5zZm9ybS1ib3g6IGZpbGwtYm94O30jTlhob3VyZ2xhc3Mzey13ZWJraXQtYW5pbWF0aW9uLW5hbWU6IE5YaG91cmdsYXNzMy1hbmltYXRpb247YW5pbWF0aW9uLW5hbWU6IE5YaG91cmdsYXNzMy1hbmltYXRpb247LXdlYmtpdC1hbmltYXRpb24tdGltaW5nLWZ1bmN0aW9uOiBjdWJpYy1iZXppZXIoMC40MiwgMCwgMC41OCwgMSk7YW5pbWF0aW9uLXRpbWluZy1mdW5jdGlvbjogY3ViaWMtYmV6aWVyKDAuNDIsIDAsIDAuNTgsIDEpOy13ZWJraXQtdHJhbnNmb3JtLW9yaWdpbjogNTAlIDEwMCU7dHJhbnNmb3JtLW9yaWdpbjogNTAlIDEwMCU7dHJhbnNmb3JtLWJveDogZmlsbC1ib3g7fSNOWGhvdXJnbGFzczV7LXdlYmtpdC1hbmltYXRpb24tbmFtZTogTlhob3VyZ2xhc3M1LWFuaW1hdGlvbjthbmltYXRpb24tbmFtZTogTlhob3VyZ2xhc3M1LWFuaW1hdGlvbjstd2Via2l0LXRyYW5zZm9ybS1vcmlnaW46IDUwJSAxMDAlO3RyYW5zZm9ybS1vcmlnaW46IDUwJSAxMDAlO3RyYW5zZm9ybS1ib3g6IGZpbGwtYm94O31nI05YaG91cmdsYXNzNSwjTlhob3VyZ2xhc3Mze2ZpbGw6IGluaGVyaXQ7b3BhY2l0eTogLjQ7fTwvc3R5bGU+PGcgaWQ9XCJOWGhvdXJnbGFzczFcIiBkYXRhLWFuaW1hdG9yLWdyb3VwPVwidHJ1ZVwiIGRhdGEtYW5pbWF0b3ItdHlwZT1cIjFcIj48ZyBpZD1cIk5YaG91cmdsYXNzMlwiPjxnIGlkPVwiTlhob3VyZ2xhc3MzXCIgZGF0YS1hbmltYXRvci1ncm91cD1cInRydWVcIiBkYXRhLWFuaW1hdG9yLXR5cGU9XCIyXCI+PHBvbHlnb24gcG9pbnRzPVwiMTAwLDEwMCA2NS42MiwxMzIuMDggNjUuNjIsMTYzLjIyIDEzNC4zOCwxNjMuMjIgMTM0LjM4LDEzMi4wOCBcIiBpZD1cIk5YaG91cmdsYXNzNFwiLz48L2c+PGcgaWQ9XCJOWGhvdXJnbGFzczVcIiBkYXRhLWFuaW1hdG9yLWdyb3VwPVwidHJ1ZVwiIGRhdGEtYW5pbWF0b3ItdHlwZT1cIjJcIj48cG9seWdvbiBwb2ludHM9XCIxMDAsMTAwIDY1LjYyLDY3LjkyIDY1LjYyLDM2Ljc4IDEzNC4zOCwzNi43OCAxMzQuMzgsNjcuOTJcIiBpZD1cIk5YaG91cmdsYXNzNlwiLz48L2c+IDxwYXRoIGQ9XCJNNTEuMTQgMzguODlsOC4zMyAwIDAgMTQuOTNjMCwxNS4xIDguMjksMjguOTkgMjMuMzQsMzkuMSAxLjg4LDEuMjUgMy4wNCwzLjk3IDMuMDQsNy4wOCAwLDMuMTEgLTEuMTYsNS44MyAtMy4wNCw3LjA5IC0xNS4wNSwxMC4xIC0yMy4zNCwyMy45OSAtMjMuMzQsMzkuMDlsMCAxNC45MyAtOC4zMyAwYy0yLjY4LDAgLTQuODYsMi4xOCAtNC44Niw0Ljg2IDAsMi42OSAyLjE4LDQuODYgNC44Niw0Ljg2bDk3LjcyIDBjMi42OCwwIDQuODYsLTIuMTcgNC44NiwtNC44NiAwLC0yLjY4IC0yLjE4LC00Ljg2IC00Ljg2LC00Ljg2bC04LjMzIDAgMCAtMTQuOTNjMCwtMTUuMSAtOC4yOSwtMjguOTkgLTIzLjM0LC0zOS4wOSAtMS44OCwtMS4yNiAtMy4wNCwtMy45OCAtMy4wNCwtNy4wOSAwLC0zLjExIDEuMTYsLTUuODMgMy4wNCwtNy4wOCAxNS4wNSwtMTAuMTEgMjMuMzQsLTI0IDIzLjM0LC0zOS4xbDAgLTE0LjkzIDguMzMgMGMyLjY4LDAgNC44NiwtMi4xOCA0Ljg2LC00Ljg2IDAsLTIuNjkgLTIuMTgsLTQuODYgLTQuODYsLTQuODZsLTk3LjcyIDBjLTIuNjgsMCAtNC44NiwyLjE3IC00Ljg2LDQuODYgMCwyLjY4IDIuMTgsNC44NiA0Ljg2LDQuODZ6bTc5LjY3IDE0LjkzYzAsMTUuODcgLTExLjkzLDI2LjI1IC0xOS4wNCwzMS4wMyAtNC42LDMuMDggLTcuMzQsOC43NSAtNy4zNCwxNS4xNSAwLDYuNDEgMi43NCwxMi4wNyA3LjM0LDE1LjE1IDcuMTEsNC43OCAxOS4wNCwxNS4xNiAxOS4wNCwzMS4wM2wwIDE0LjkzIC02MS42MiAwIDAgLTE0LjkzYzAsLTE1Ljg3IDExLjkzLC0yNi4yNSAxOS4wNCwtMzEuMDIgNC42LC0zLjA5IDcuMzQsLTguNzUgNy4zNCwtMTUuMTYgMCwtNi40IC0yLjc0LC0xMi4wNyAtNy4zNCwtMTUuMTUgLTcuMTEsLTQuNzggLTE5LjA0LC0xNS4xNiAtMTkuMDQsLTMxLjAzbDAgLTE0LjkzIDYxLjYyIDAgMCAxNC45M3pcIiBpZD1cIk5YaG91cmdsYXNzN1wiLz48L2c+PC9nPjwvc3ZnPmA7XHJcbiAgICByZXR1cm4gaG91cmdsYXNzO1xyXG59XHJcbi8vIE5vdGlmbGl4OiBMb2FkaW5nIFNWRyBob3VyZ2xhc3Mgb2ZmXHJcblxyXG4vLyBOb3RpZmxpeDogTG9hZGluZyBTVkcgY2lyY2xlIG9uXHJcbmNvbnN0IG5vdGlmbGl4TG9hZGluZ1N2Z0NpcmNsZSA9ICh3aWR0aCwgY29sb3IpID0+IHtcclxuICAgIGlmICghd2lkdGgpIHsgd2lkdGggPSAnNjBweCc7IH1cclxuICAgIGlmICghY29sb3IpIHsgY29sb3IgPSAnIzAwYjQ2Mic7IH1cclxuICAgIGNvbnN0IGNpcmNsZSA9IGA8c3ZnIGlkPVwiTlhMb2FkaW5nQ2lyY2xlXCIgd2lkdGg9XCIke3dpZHRofVwiIGhlaWdodD1cIiR7d2lkdGh9XCIgeG1sbnM9XCJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2Z1wiIHhtbG5zOnhsaW5rPVwiaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGlua1wiIHZpZXdCb3g9XCIyNSAyNSA1MCA1MFwiIHhtbDpzcGFjZT1cInByZXNlcnZlXCIgdmVyc2lvbj1cIjEuMVwiPjxzdHlsZT4jTlhMb2FkaW5nQ2lyY2xley13ZWJraXQtYW5pbWF0aW9uOiByb3RhdGUgMnMgbGluZWFyIGluZmluaXRlOyBhbmltYXRpb246IHJvdGF0ZSAycyBsaW5lYXIgaW5maW5pdGU7IGhlaWdodDogJHt3aWR0aH07IC13ZWJraXQtdHJhbnNmb3JtLW9yaWdpbjogY2VudGVyIGNlbnRlcjsgLW1zLXRyYW5zZm9ybS1vcmlnaW46IGNlbnRlciBjZW50ZXI7IHRyYW5zZm9ybS1vcmlnaW46IGNlbnRlciBjZW50ZXI7IHdpZHRoOiAke3dpZHRofTsgcG9zaXRpb246IGFic29sdXRlOyB0b3A6IDA7IGxlZnQ6IDA7IG1hcmdpbjogYXV0bzt9Lm5vdGlmbGl4LWxvYWRlci1jaXJjbGUtcGF0aHtzdHJva2UtZGFzaGFycmF5OiAxNTAsMjAwOyBzdHJva2UtZGFzaG9mZnNldDogLTEwOyAtd2Via2l0LWFuaW1hdGlvbjogZGFzaCAxLjVzIGVhc2UtaW4tb3V0IGluZmluaXRlLCBjb2xvciAxLjVzIGVhc2UtaW4tb3V0IGluZmluaXRlOyBhbmltYXRpb246IGRhc2ggMS41cyBlYXNlLWluLW91dCBpbmZpbml0ZSwgY29sb3IgMS41cyBlYXNlLWluLW91dCBpbmZpbml0ZTsgc3Ryb2tlLWxpbmVjYXA6IHJvdW5kO31ALXdlYmtpdC1rZXlmcmFtZXMgcm90YXRlezEwMCV7LXdlYmtpdC10cmFuc2Zvcm06IHJvdGF0ZSgzNjBkZWcpOyB0cmFuc2Zvcm06IHJvdGF0ZSgzNjBkZWcpO319QGtleWZyYW1lcyByb3RhdGV7MTAwJXstd2Via2l0LXRyYW5zZm9ybTogcm90YXRlKDM2MGRlZyk7IHRyYW5zZm9ybTogcm90YXRlKDM2MGRlZyk7fX1ALXdlYmtpdC1rZXlmcmFtZXMgZGFzaHswJXtzdHJva2UtZGFzaGFycmF5OiAxLDIwMDsgc3Ryb2tlLWRhc2hvZmZzZXQ6IDA7fTUwJXtzdHJva2UtZGFzaGFycmF5OiA4OSwyMDA7IHN0cm9rZS1kYXNob2Zmc2V0OiAtMzU7fTEwMCV7c3Ryb2tlLWRhc2hhcnJheTogODksMjAwOyBzdHJva2UtZGFzaG9mZnNldDogLTEyNDt9fUBrZXlmcmFtZXMgZGFzaHswJXtzdHJva2UtZGFzaGFycmF5OiAxLDIwMDsgc3Ryb2tlLWRhc2hvZmZzZXQ6IDA7fTUwJXtzdHJva2UtZGFzaGFycmF5OiA4OSwyMDA7IHN0cm9rZS1kYXNob2Zmc2V0OiAtMzU7fTEwMCV7c3Ryb2tlLWRhc2hhcnJheTogODksMjAwOyBzdHJva2UtZGFzaG9mZnNldDogLTEyNDt9fTwvc3R5bGU+PGNpcmNsZSBjbGFzcz1cIm5vdGlmbGl4LWxvYWRlci1jaXJjbGUtcGF0aFwiIGN4PVwiNTBcIiBjeT1cIjUwXCIgcj1cIjIwXCIgZmlsbD1cIm5vbmVcIiBzdHJva2U9XCIke2NvbG9yfVwiIHN0cm9rZS13aWR0aD1cIjJcIi8+PC9zdmc+YDtcclxuICAgIHJldHVybiBjaXJjbGU7XHJcbn1cclxuLy8gTm90aWZsaXg6IExvYWRpbmcgU1ZHIGNpcmNsZSBvZmZcclxuXHJcbi8vIE5vdGlmbGl4OiBMb2FkaW5nIFNWRyBhcnJvd3Mgb25cclxuY29uc3Qgbm90aWZsaXhMb2FkaW5nU3ZnQXJyb3dzID0gKHdpZHRoLCBjb2xvcikgPT4ge1xyXG4gICAgaWYgKCF3aWR0aCkgeyB3aWR0aCA9ICc2MHB4JzsgfVxyXG4gICAgaWYgKCFjb2xvcikgeyBjb2xvciA9ICcjMDBiNDYyJzsgfVxyXG4gICAgY29uc3QgYXJyb3dzID0gYDxzdmcgaWQ9XCJOWExvYWRpbmdBcnJvd3NcIiBmaWxsPVwiJHtjb2xvcn1cIiB3aWR0aD1cIiR7d2lkdGh9XCIgaGVpZ2h0PVwiJHt3aWR0aH1cIiB4bWxucz1cImh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnXCIgeG1sbnM6eGxpbms9XCJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rXCIgdmVyc2lvbj1cIjEuMVwiIHZpZXdCb3g9XCIwIDAgMTI4IDEyOFwiIHhtbDpzcGFjZT1cInByZXNlcnZlXCI+PGc+PHBhdGggZmlsbD1cImluaGVyaXRcIiBmaWxsLW9wYWNpdHk9XCIxXCIgZD1cIk0xMDkuMjUgNTUuNWgtMzZsMTItMTJhMjkuNTQgMjkuNTQgMCAwIDAtNDkuNTMgMTJIMTguNzVBNDYuMDQgNDYuMDQgMCAwIDEgOTYuOSAzMS44NGwxMi4zNS0xMi4zNHYzNnptLTkwLjUgMTdoMzZsLTEyIDEyYTI5LjU0IDI5LjU0IDAgMCAwIDQ5LjUzLTEyaDE2Ljk3QTQ2LjA0IDQ2LjA0IDAgMCAxIDMxLjEgOTYuMTZMMTguNzQgMTA4LjV2LTM2elwiIC8+PGFuaW1hdGVUcmFuc2Zvcm0gYXR0cmlidXRlTmFtZT1cInRyYW5zZm9ybVwiIHR5cGU9XCJyb3RhdGVcIiBmcm9tPVwiMCA2NCA2NFwiIHRvPVwiMzYwIDY0IDY0XCIgZHVyPVwiMS41c1wiIHJlcGVhdENvdW50PVwiaW5kZWZpbml0ZVwiPjwvYW5pbWF0ZVRyYW5zZm9ybT48L2c+PC9zdmc+YDtcclxuICAgIHJldHVybiBhcnJvd3M7XHJcbn1cclxuLy8gTm90aWZsaXg6IExvYWRpbmcgU1ZHIGFycm93cyBvZmZcclxuXHJcbi8vIE5vdGlmbGl4OiBMb2FkaW5nIFNWRyBkb3RzIG9uXHJcbmNvbnN0IG5vdGlmbGl4TG9hZGluZ1N2Z0RvdHMgPSAod2lkdGgsIGNvbG9yKSA9PiB7XHJcbiAgICBpZiAoIXdpZHRoKSB7IHdpZHRoID0gJzYwcHgnOyB9XHJcbiAgICBpZiAoIWNvbG9yKSB7IGNvbG9yID0gJyMwMGI0NjInOyB9XHJcbiAgICBjb25zdCBkb3RzID0gYDxzdmcgaWQ9XCJOWExvYWRpbmdEb3RzXCIgZmlsbD1cIiR7Y29sb3J9XCIgd2lkdGg9XCIke3dpZHRofVwiIGhlaWdodD1cIiR7d2lkdGh9XCIgeG1sbnM9XCJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2Z1wiIHhtbG5zOnhsaW5rPVwiaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGlua1wiIHZpZXdCb3g9XCIwIDAgMTAwIDEwMFwiIHByZXNlcnZlQXNwZWN0UmF0aW89XCJ4TWlkWU1pZFwiPjxnIHRyYW5zZm9ybT1cInRyYW5zbGF0ZSgyNSA1MClcIj48Y2lyY2xlIGN4PVwiMFwiIGN5PVwiMFwiIHI9XCI5XCIgZmlsbD1cImluaGVyaXRcIiB0cmFuc2Zvcm09XCJzY2FsZSgwLjIzOSAwLjIzOSlcIj48YW5pbWF0ZVRyYW5zZm9ybSBhdHRyaWJ1dGVOYW1lPVwidHJhbnNmb3JtXCIgdHlwZT1cInNjYWxlXCIgYmVnaW49XCItMC4yNjZzXCIgY2FsY01vZGU9XCJzcGxpbmVcIiBrZXlTcGxpbmVzPVwiMC4zIDAgMC43IDE7MC4zIDAgMC43IDFcIiB2YWx1ZXM9XCIwOzE7MFwiIGtleVRpbWVzPVwiMDswLjU7MVwiIGR1cj1cIjAuOHNcIiByZXBlYXRDb3VudD1cImluZGVmaW5pdGVcIi8+PC9jaXJjbGU+PC9nPjxnIHRyYW5zZm9ybT1cInRyYW5zbGF0ZSg1MCA1MClcIj4gPGNpcmNsZSBjeD1cIjBcIiBjeT1cIjBcIiByPVwiOVwiIGZpbGw9XCJpbmhlcml0XCIgdHJhbnNmb3JtPVwic2NhbGUoMC4wMDE1MiAwLjAwMTUyKVwiPjxhbmltYXRlVHJhbnNmb3JtIGF0dHJpYnV0ZU5hbWU9XCJ0cmFuc2Zvcm1cIiB0eXBlPVwic2NhbGVcIiBiZWdpbj1cIi0wLjEzM3NcIiBjYWxjTW9kZT1cInNwbGluZVwiIGtleVNwbGluZXM9XCIwLjMgMCAwLjcgMTswLjMgMCAwLjcgMVwiIHZhbHVlcz1cIjA7MTswXCIga2V5VGltZXM9XCIwOzAuNTsxXCIgZHVyPVwiMC44c1wiIHJlcGVhdENvdW50PVwiaW5kZWZpbml0ZVwiLz48L2NpcmNsZT48L2c+PGcgdHJhbnNmb3JtPVwidHJhbnNsYXRlKDc1IDUwKVwiPjxjaXJjbGUgY3g9XCIwXCIgY3k9XCIwXCIgcj1cIjlcIiBmaWxsPVwiaW5oZXJpdFwiIHRyYW5zZm9ybT1cInNjYWxlKDAuMjk5IDAuMjk5KVwiPjxhbmltYXRlVHJhbnNmb3JtIGF0dHJpYnV0ZU5hbWU9XCJ0cmFuc2Zvcm1cIiB0eXBlPVwic2NhbGVcIiBiZWdpbj1cIjBzXCIgY2FsY01vZGU9XCJzcGxpbmVcIiBrZXlTcGxpbmVzPVwiMC4zIDAgMC43IDE7MC4zIDAgMC43IDFcIiB2YWx1ZXM9XCIwOzE7MFwiIGtleVRpbWVzPVwiMDswLjU7MVwiIGR1cj1cIjAuOHNcIiByZXBlYXRDb3VudD1cImluZGVmaW5pdGVcIi8+PC9jaXJjbGU+PC9nPjwvc3ZnPmA7XHJcbiAgICByZXR1cm4gZG90cztcclxufVxyXG4vLyBOb3RpZmxpeDogTG9hZGluZyBTVkcgZG90cyBvZmZcclxuXHJcbi8vIE5vdGlmbGl4OiBMb2FkaW5nIFNWRyBwdWxzZSBvblxyXG5jb25zdCBub3RpZmxpeExvYWRpbmdTdmdQdWxzZSA9ICh3aWR0aCwgY29sb3IpID0+IHtcclxuICAgIGlmICghd2lkdGgpIHsgd2lkdGggPSAnNjBweCc7IH1cclxuICAgIGlmICghY29sb3IpIHsgY29sb3IgPSAnIzAwYjQ2Mic7IH1cclxuICAgIGNvbnN0IHB1bHNlID0gYDxzdmcgc3Ryb2tlPVwiJHtjb2xvcn1cIiB3aWR0aD1cIiR7d2lkdGh9XCIgaGVpZ2h0PVwiJHt3aWR0aH1cIiB2aWV3Qm94PVwiMCAwIDQ0IDQ0XCIgeG1sbnM9XCJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2Z1wiPjxnIGZpbGw9XCJub25lXCIgZmlsbC1ydWxlPVwiZXZlbm9kZFwiIHN0cm9rZS13aWR0aD1cIjJcIj48Y2lyY2xlIGN4PVwiMjJcIiBjeT1cIjIyXCIgcj1cIjFcIj48YW5pbWF0ZSBhdHRyaWJ1dGVOYW1lPVwiclwiIGJlZ2luPVwiMHNcIiBkdXI9XCIxLjhzXCIgdmFsdWVzPVwiMTsgMjBcIiBjYWxjTW9kZT1cInNwbGluZVwiIGtleVRpbWVzPVwiMDsgMVwiIGtleVNwbGluZXM9XCIwLjE2NSwgMC44NCwgMC40NCwgMVwiIHJlcGVhdENvdW50PVwiaW5kZWZpbml0ZVwiLz48YW5pbWF0ZSBhdHRyaWJ1dGVOYW1lPVwic3Ryb2tlLW9wYWNpdHlcIiBiZWdpbj1cIjBzXCIgZHVyPVwiMS44c1wiIHZhbHVlcz1cIjE7IDBcIiBjYWxjTW9kZT1cInNwbGluZVwiIGtleVRpbWVzPVwiMDsgMVwiIGtleVNwbGluZXM9XCIwLjMsIDAuNjEsIDAuMzU1LCAxXCIgcmVwZWF0Q291bnQ9XCJpbmRlZmluaXRlXCIvPjwvY2lyY2xlPjxjaXJjbGUgY3g9XCIyMlwiIGN5PVwiMjJcIiByPVwiMVwiPjxhbmltYXRlIGF0dHJpYnV0ZU5hbWU9XCJyXCIgYmVnaW49XCItMC45c1wiIGR1cj1cIjEuOHNcIiB2YWx1ZXM9XCIxOyAyMFwiIGNhbGNNb2RlPVwic3BsaW5lXCIga2V5VGltZXM9XCIwOyAxXCIga2V5U3BsaW5lcz1cIjAuMTY1LCAwLjg0LCAwLjQ0LCAxXCIgcmVwZWF0Q291bnQ9XCJpbmRlZmluaXRlXCIvPjxhbmltYXRlIGF0dHJpYnV0ZU5hbWU9XCJzdHJva2Utb3BhY2l0eVwiIGJlZ2luPVwiLTAuOXNcIiBkdXI9XCIxLjhzXCIgdmFsdWVzPVwiMTsgMFwiIGNhbGNNb2RlPVwic3BsaW5lXCIga2V5VGltZXM9XCIwOyAxXCIga2V5U3BsaW5lcz1cIjAuMywgMC42MSwgMC4zNTUsIDFcIiByZXBlYXRDb3VudD1cImluZGVmaW5pdGVcIi8+PC9jaXJjbGU+PC9nPjwvc3ZnPmA7XHJcbiAgICByZXR1cm4gcHVsc2U7XHJcbn1cclxuLy8gTm90aWZsaXg6IExvYWRpbmcgU1ZHIHB1bHNlIG9mZlxyXG5cclxuLy8gTm90aWZsaXg6IExvYWRpbmcgU1ZHIG5vdGlmbGl4IG9uXHJcbmNvbnN0IG5vdGlmbGl4TG9hZGluZ1N2Z05vdGlmbGl4ID0gKHdpZHRoLCB3aGl0ZSwgZ3JlZW4pID0+IHtcclxuICAgIGlmICghd2lkdGgpIHsgd2lkdGggPSAnNjBweCc7IH1cclxuICAgIGlmICghd2hpdGUpIHsgd2hpdGUgPSAnI2Y4ZjhmOCc7IH1cclxuICAgIGlmICghZ3JlZW4pIHsgZ3JlZW4gPSAnIzAwYjQ2Mic7IH1cclxuICAgIGNvbnN0IG5vdGlmbGl4SWNvbiA9IGA8c3ZnIGlkPVwiTlhMb2FkaW5nTm90aWZsaXhMaWJcIiB4bWxucz1cImh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnXCIgeG1sOnNwYWNlPVwicHJlc2VydmVcIiB3aWR0aD1cIiR7d2lkdGh9XCIgaGVpZ2h0PVwiJHt3aWR0aH1cIiB2ZXJzaW9uPVwiMS4xXCIgc3R5bGU9XCJzaGFwZS1yZW5kZXJpbmc6Z2VvbWV0cmljUHJlY2lzaW9uOyB0ZXh0LXJlbmRlcmluZzpnZW9tZXRyaWNQcmVjaXNpb247IGltYWdlLXJlbmRlcmluZzpvcHRpbWl6ZVF1YWxpdHk7IGZpbGwtcnVsZTpldmVub2RkOyBjbGlwLXJ1bGU6ZXZlbm9kZFwiIHZpZXdCb3g9XCIwIDAgMjAwIDIwMFwiIHhtbG5zOnhsaW5rPVwiaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGlua1wiPjxkZWZzPjxzdHlsZSB0eXBlPVwidGV4dC9jc3NcIj4ubGluZXtzdHJva2U6JHt3aGl0ZX07c3Ryb2tlLXdpZHRoOjEyO3N0cm9rZS1saW5lY2FwOnJvdW5kO3N0cm9rZS1saW5lam9pbjpyb3VuZDtzdHJva2UtbWl0ZXJsaW1pdDoyMjt9LmxpbmV7ZmlsbDpub25lO30uZG90e2ZpbGw6JHtncmVlbn07c3Ryb2tlOiR7Z3JlZW59O3N0cm9rZS13aWR0aDoxMjtzdHJva2UtbGluZWNhcDpyb3VuZDtzdHJva2UtbGluZWpvaW46cm91bmQ7c3Ryb2tlLW1pdGVybGltaXQ6MjI7fS5ue3N0cm9rZS1kYXNoYXJyYXk6IDUwMDtzdHJva2UtZGFzaG9mZnNldDogMDthbmltYXRpb24tbmFtZTogbm90aWZsaXgtbjthbmltYXRpb24tdGltaW5nLWZ1bmN0aW9uOiBsaW5lYXI7YW5pbWF0aW9uLWR1cmF0aW9uOiAyLjVzO2FuaW1hdGlvbi1kZWxheTowczthbmltYXRpb24taXRlcmF0aW9uLWNvdW50OiBpbmZpbml0ZTthbmltYXRpb24tZGlyZWN0aW9uOiBub3JtYWw7fUBrZXlmcmFtZXMgbm90aWZsaXgtbnswJXtzdHJva2UtZGFzaG9mZnNldDogMTAwMDt9MTAwJXtzdHJva2UtZGFzaG9mZnNldDogMDt9fS54MiwueDF7c3Ryb2tlLWRhc2hhcnJheTogNTAwO3N0cm9rZS1kYXNob2Zmc2V0OiAwO2FuaW1hdGlvbi1uYW1lOiBub3RpZmxpeC14O2FuaW1hdGlvbi10aW1pbmctZnVuY3Rpb246IGxpbmVhcjthbmltYXRpb24tZHVyYXRpb246IDIuNXM7YW5pbWF0aW9uLWRlbGF5Oi4yczthbmltYXRpb24taXRlcmF0aW9uLWNvdW50OiBpbmZpbml0ZTthbmltYXRpb24tZGlyZWN0aW9uOiBub3JtYWw7fUBrZXlmcmFtZXMgbm90aWZsaXgteHswJXtzdHJva2UtZGFzaG9mZnNldDogMTAwMDt9MTAwJXtzdHJva2UtZGFzaG9mZnNldDogMDt9fS5kb3R7YW5pbWF0aW9uLW5hbWU6IG5vdGlmbGl4LWRvdDthbmltYXRpb24tdGltaW5nLWZ1bmN0aW9uOiBlYXNlLWluLW91dDthbmltYXRpb24tZHVyYXRpb246IDEuMjVzO2FuaW1hdGlvbi1pdGVyYXRpb24tY291bnQ6IGluZmluaXRlO2FuaW1hdGlvbi1kaXJlY3Rpb246IG5vcm1hbDt9QGtleWZyYW1lcyBub3RpZmxpeC1kb3R7MCV7c3Ryb2tlLXdpZHRoOiAwO301MCV7c3Ryb2tlLXdpZHRoOiAxMjt9MTAwJXtzdHJva2Utd2lkdGg6IDA7fX08L3N0eWxlPjwvZGVmcz48Zz48cGF0aCBjbGFzcz1cImRvdFwiIGQ9XCJNNDcuOTcgMTM1LjA1YzMuNTksMCA2LjUsMi45MSA2LjUsNi41IDAsMy41OSAtMi45MSw2LjUgLTYuNSw2LjUgLTMuNTksMCAtNi41LC0yLjkxIC02LjUsLTYuNSAwLC0zLjU5IDIuOTEsLTYuNSA2LjUsLTYuNXpcIi8+PHBhdGggY2xhc3M9XCJsaW5lIG5cIiBkPVwiTTEwLjE0IDE0NC43NmwwIC0wLjIyIDAgLTAuOTYgMCAtNTYuMDNjMCwtNS42OCAtNC41NCwtNDEuMzYgMzcuODMsLTQxLjM2IDQyLjM2LDAgMzcuODIsMzUuNjggMzcuODIsNDEuMzZsMCA1Ny4yMVwiLz48cGF0aCBjbGFzcz1cImxpbmUgeDFcIiBkPVwiTTExNS4wNiAxNDQuNDljMjQuOTgsLTMyLjY4IDQ5Ljk2LC02NS4zNSA3NC45NCwtOTguMDNcIi8+PHBhdGggY2xhc3M9XCJsaW5lIHgyXCIgZD1cIk0xMTQuODkgNDYuNmMyNS4wOSwzMi41OCA1MC4xOSw2NS4xNyA3NS4yOSw5Ny43NVwiLz48L2c+PC9zdmc+YDtcclxuICAgIHJldHVybiBub3RpZmxpeEljb247XHJcbn1cclxuLy8gTm90aWZsaXg6IExvYWRpbmcgU1ZHIG5vdGlmbGl4IG9mZiIsIid1c2Ugc3RyaWN0JztcblxuaW1wb3J0IE5vdGlmbGl4IGZyb20gXCJub3RpZmxpeC1yZWFjdFwiO1xuXG5Ob3RpZmxpeC5Ob3RpZnkuSW5pdCh7XG4gIHRpbWVvdXQ6IDUwMDBcbn0pO1xuXG4kKGZ1bmN0aW9uKCkge1xuXG4gIGxldCBhdXRob3IgPSBgPGRpdiBjbGFzcz1cImF1dGhvclwiPlxuICAgIEJ5IDxhIGhyZWY9XCJodHRwczovL2FydGh1cm1vbm5leS5jb21cIj5AbW9ubmV5YXJ0aHVyPC9hPiAmbmJzcDsmYnVsbDsmbmJzcDsgXG4gICAgPGEgaHJlZj1cImh0dHBzOi8vZ2l0aHViLmNvbS9sYXJhdmVsLXNob3BwZXIvZnJhbWV3b3JrXCIgdGFyZ2V0PVwiX2JsYW5rXCI+U2VlIGRvY3VtZW50YXRpb248L2E+XG4gIDwvZGl2PmA7XG4gICQoJ2JvZHknKS5hcHBlbmQoYXV0aG9yKTtcblxuICAkKGBpbnB1dFt0eXBlPSdwYXNzd29yZCddW2RhdGEtZXllXWApLmVhY2goZnVuY3Rpb24oaSkge1xuICAgIGxldCAkdGhpcyA9ICQodGhpcyksIGlkID0gJ2V5ZS1wYXNzd29yZC0nICsgaSwgZWwgPSAkKCcjJyArIGlkKTtcblxuICAgICR0aGlzLndyYXAoJChcIjxkaXYvPlwiLCB7IHN0eWxlOiAncG9zaXRpb246cmVsYXRpdmUnLCBpZDogaWQgfSkpO1xuICAgICR0aGlzLmNzcyh7IHBhZGRpbmdSaWdodDogNjAgfSk7XG5cbiAgICAkdGhpcy5hZnRlcigkKCc8ZGl2Lz4nLCB7XG4gICAgICBodG1sOiAnU2hvdycsXG4gICAgICBjbGFzczogJ2J0biBidG4tcHJpbWFyeSBidG4tc20nLFxuICAgICAgaWQ6ICdwYXNzZXllLXRvZ2dsZS0nK2ksXG4gICAgfSkuY3NzKHtcbiAgICAgIHBvc2l0aW9uOiAnYWJzb2x1dGUnLFxuICAgICAgcmlnaHQ6IDEwLFxuICAgICAgdG9wOiAoJHRoaXMub3V0ZXJIZWlnaHQoKSAvIDIpIC0gMTIsXG4gICAgICBwYWRkaW5nOiAnMnB4IDdweCcsXG4gICAgICBmb250U2l6ZTogMTIsXG4gICAgICBjdXJzb3I6ICdwb2ludGVyJyxcbiAgICB9KSk7XG5cbiAgICAkdGhpcy5hZnRlcigkKCc8aW5wdXQvPicsIHtcbiAgICAgIHR5cGU6ICdoaWRkZW4nLFxuICAgICAgaWQ6ICdwYXNzZXllLScgKyBpXG4gICAgfSkpO1xuXG4gICAgbGV0IGludmFsaWRfZmVlZGJhY2sgPSAkdGhpcy5wYXJlbnQoKS5wYXJlbnQoKS5maW5kKCcuaW52YWxpZC1mZWVkYmFjaycpO1xuXG4gICAgaWYoaW52YWxpZF9mZWVkYmFjay5sZW5ndGgpIHtcbiAgICAgICR0aGlzLmFmdGVyKGludmFsaWRfZmVlZGJhY2suY2xvbmUoKSk7XG4gICAgfVxuXG4gICAgJHRoaXMub24oJ2tleXVwIHBhc3RlJywgZnVuY3Rpb24oKSB7XG4gICAgICAkKCcjcGFzc2V5ZS0nK2kpLnZhbCgkKHRoaXMpLnZhbCgpKTtcbiAgICB9KVxuICAgICQoJyNwYXNzZXllLXRvZ2dsZS0nK2kpLm9uKCdjbGljaycsIGZ1bmN0aW9uKCkge1xuICAgICAgaWYoJHRoaXMuaGFzQ2xhc3MoJ3Nob3cnKSkge1xuICAgICAgICAkdGhpcy5hdHRyKCd0eXBlJywgJ3Bhc3N3b3JkJyk7XG4gICAgICAgICR0aGlzLnJlbW92ZUNsYXNzKCdzaG93Jyk7XG4gICAgICAgICQodGhpcykucmVtb3ZlQ2xhc3MoJ2J0bi1vdXRsaW5lLXByaW1hcnknKTtcbiAgICAgIH0gZWxzZSB7XG4gICAgICAgICR0aGlzLmF0dHIoJ3R5cGUnLCAndGV4dCcpO1xuICAgICAgICAkdGhpcy52YWwoJCgnI3Bhc3NleWUtJytpKS52YWwoKSk7XG4gICAgICAgICR0aGlzLmFkZENsYXNzKCdzaG93Jyk7XG4gICAgICAgICQodGhpcykuYWRkQ2xhc3MoJ2J0bi1vdXRsaW5lLXByaW1hcnknKTtcbiAgICAgIH1cbiAgICB9KTtcbiAgfSk7XG5cbiAgJCgnLm15LWxvZ2luLXZhbGlkYXRpb24nKS5zdWJtaXQoZnVuY3Rpb24oZXZlbnQpIHtcbiAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuICAgIGV2ZW50LnN0b3BQcm9wYWdhdGlvbigpO1xuXG4gICAgbGV0IGZvcm0gPSAkKHRoaXMpLCBidXR0b24gPSAkKCcjYnRuLWxvZ2luJyk7XG5cbiAgICBidXR0b24uYXR0cignZGlzYWJsZWQnLCB0cnVlKVxuICAgICAgLmh0bWwoYDxzcGFuIGNsYXNzPSdsb2FkZXIxMic+PC9zcGFuPmApO1xuXG4gICAgaWYgKGZvcm1bMF0uY2hlY2tWYWxpZGl0eSgpID09PSBmYWxzZSkge1xuICAgICAgYnV0dG9uLnJlbW92ZUF0dHIoJ2Rpc2FibGVkJykuaHRtbCgnTG9naW4nKTtcbiAgICAgIGZvcm0uYWRkQ2xhc3MoJ3dhcy12YWxpZGF0ZWQnKTtcbiAgICB9XG5cbiAgICBheGlvc1xuICAgICAgLnBvc3QoZm9ybS5hdHRyKCdhY3Rpb24nKSwgSlNPTi5zdHJpbmdpZnkoZm9ybS5zZXJpYWxpemUoKSkpXG4gICAgICAudGhlbigocmVzcG9uc2UpID0+IHtcbiAgICAgICAgYnV0dG9uLnJlbW92ZUF0dHIoJ2Rpc2FibGVkJykuaHRtbCgnTG9naW4nKTtcbiAgICAgICAgbG9jYWxTdG9yYWdlLnNldEl0ZW0oJ3Rva2VuJywgcmVzcG9uc2UuZGF0YS50b2tlbik7XG4gICAgICAgIGxvY2FsU3RvcmFnZS5zZXRJdGVtKCd1c2VyJywgSlNPTi5zdHJpbmdpZnkocmVzcG9uc2UuZGF0YS51c2VyKSk7XG5cbiAgICAgICAgaWYgKHJlc3BvbnNlLmRhdGEuc3RhdHVzID09PSAnc3VjY2VzcycpIHtcbiAgICAgICAgICBOb3RpZmxpeC5Ob3RpZnkuU3VjY2VzcyhyZXNwb25zZS5kYXRhLm1lc3NhZ2UpO1xuXG4gICAgICAgICAgc2V0SW50ZXJ2YWwoZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgd2luZG93LmxvY2F0aW9uLmhyZWYgPSByZXNwb25zZS5kYXRhLnJlZGlyZWN0X3VybDtcbiAgICAgICAgICB9LCAyMDAwKTtcbiAgICAgICAgfVxuICAgICAgfSlcbiAgICAgIC5jYXRjaCgoZXJyb3IpID0+IHtcbiAgICAgICAgY29uc3QgZGF0YSA9IGVycm9yLnJlc3BvbnNlLmRhdGE7XG5cbiAgICAgICAgaWYgKGRhdGEgJiYgZGF0YS5lcnJvcnMpIHtcbiAgICAgICAgICBOb3RpZmxpeC5Ob3RpZnkuRmFpbHVyZShlcnJvci5yZXNwb25zZS5kYXRhLmVycm9ycy5lbWFpbFswXSk7XG4gICAgICAgICAgYnV0dG9uLnJlbW92ZUF0dHIoJ2Rpc2FibGVkJykuaHRtbCgnTG9naW4nKVxuICAgICAgICB9XG4gICAgICB9KTtcbiAgfSk7XG59KTtcbiJdLCJzb3VyY2VSb290IjoiIn0=