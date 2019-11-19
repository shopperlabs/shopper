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
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
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
  var author = '<div style="position: fixed;bottom: 0;right: 20px;background-color: #fff;box-shadow: 0 4px 8px rgba(0,0,0,.05);border-radius: 3px 3px 0 0;font-size: 12px;padding: 5px 10px;">By <a href="https://arthurmonney.com">@monneyarthur</a> &nbsp;&bull;&nbsp; <a href="https://github.com/laravel-shopper/framework" target="_blank">See documentation</a></div>';
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
      notiflix_react__WEBPACK_IMPORTED_MODULE_0__["default"].Notify.Success('Your are successfull Logged In');
      setInterval(function () {
        window.location.href = 'https://arthurmonney.com';
      }, 2000);
    })["catch"](function (error) {
      var errors = error.response.data.errors;

      if (errors) {
        notiflix_react__WEBPACK_IMPORTED_MODULE_0__["default"].Notify.Failure(error.response.data.errors.email[0]);
        button.removeAttr('disabled').html('Login');
      }
    });
  });
});

/***/ }),

/***/ 1:
/*!********************************************!*\
  !*** multi ./resources/assets/js/login.js ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/mckenzie/Sites/packages/laravel-shopper-v6/packages/shopper/framework/resources/assets/js/login.js */"./resources/assets/js/login.js");


/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vbm9kZV9tb2R1bGVzL25vdGlmbGl4LXJlYWN0L2Rpc3Qvbm90aWZsaXgtcmVhY3QtYWlvLTEuNC4wLmpzIiwid2VicGFjazovLy8uL3Jlc291cmNlcy9hc3NldHMvanMvbG9naW4uanMiXSwibmFtZXMiOlsiTm90aWZsaXgiLCJOb3RpZnkiLCJJbml0IiwidGltZW91dCIsIiQiLCJhdXRob3IiLCJhcHBlbmQiLCJlYWNoIiwiaSIsIiR0aGlzIiwiaWQiLCJlbCIsIndyYXAiLCJzdHlsZSIsImNzcyIsInBhZGRpbmdSaWdodCIsImFmdGVyIiwiaHRtbCIsInBvc2l0aW9uIiwicmlnaHQiLCJ0b3AiLCJvdXRlckhlaWdodCIsInBhZGRpbmciLCJmb250U2l6ZSIsImN1cnNvciIsInR5cGUiLCJpbnZhbGlkX2ZlZWRiYWNrIiwicGFyZW50IiwiZmluZCIsImxlbmd0aCIsImNsb25lIiwib24iLCJ2YWwiLCJoYXNDbGFzcyIsImF0dHIiLCJyZW1vdmVDbGFzcyIsImFkZENsYXNzIiwic3VibWl0IiwiZXZlbnQiLCJwcmV2ZW50RGVmYXVsdCIsInN0b3BQcm9wYWdhdGlvbiIsImZvcm0iLCJidXR0b24iLCJjaGVja1ZhbGlkaXR5IiwicmVtb3ZlQXR0ciIsImF4aW9zIiwicG9zdCIsIkpTT04iLCJzdHJpbmdpZnkiLCJzZXJpYWxpemUiLCJ0aGVuIiwicmVzcG9uc2UiLCJTdWNjZXNzIiwic2V0SW50ZXJ2YWwiLCJ3aW5kb3ciLCJsb2NhdGlvbiIsImhyZWYiLCJlcnJvciIsImVycm9ycyIsImRhdGEiLCJGYWlsdXJlIiwiZW1haWwiXSwibWFwcGluZ3MiOiI7UUFBQTtRQUNBOztRQUVBO1FBQ0E7O1FBRUE7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBOztRQUVBO1FBQ0E7UUFDQTs7O1FBR0E7UUFDQTs7UUFF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Q0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFVBQVUsc0JBQXNCO0FBQ2hDO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQSwrSEFBK0g7QUFDL0g7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsMkRBQTJELG1CQUFtQixXQUFXO0FBQ3pGO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLOztBQUVMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7O0FBRUw7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLOztBQUVMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7O0FBRUw7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7O0FBRVQ7QUFDQTs7QUFFQSxvQ0FBb0M7QUFDcEM7QUFDQSxhQUFhLE9BQU87QUFDcEI7QUFDQTtBQUNBO0FBQ0EsU0FBUzs7QUFFVDtBQUNBOztBQUVBO0FBQ0E7QUFDQSx1Q0FBdUM7QUFDdkM7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQSxTQUFTOztBQUVUO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLHVDQUF1QztBQUN2Qzs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQSxTQUFTOztBQUVUO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLHVDQUF1QztBQUN2Qzs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQSxTQUFTOztBQUVUO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLHVDQUF1QztBQUN2Qzs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQSxTQUFTOztBQUVULEtBQUs7QUFDTDs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FB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QixFQUE4QjNCLElBQTlCLENBQW1DLE9BQW5DO0FBQ0FqQiw0REFBUSxDQUFDQyxNQUFULENBQWdCbUQsT0FBaEIsQ0FBd0IsZ0NBQXhCO0FBQ0FDLGlCQUFXLENBQUMsWUFBWTtBQUN0QkMsY0FBTSxDQUFDQyxRQUFQLENBQWdCQyxJQUFoQixHQUF1QiwwQkFBdkI7QUFDRCxPQUZVLEVBRVIsSUFGUSxDQUFYO0FBR0QsS0FSSCxXQVNTLFVBQUNDLEtBQUQsRUFBVztBQUNoQixVQUFNQyxNQUFNLEdBQUdELEtBQUssQ0FBQ04sUUFBTixDQUFlUSxJQUFmLENBQW9CRCxNQUFuQzs7QUFFQSxVQUFJQSxNQUFKLEVBQVk7QUFDVjFELDhEQUFRLENBQUNDLE1BQVQsQ0FBZ0IyRCxPQUFoQixDQUF3QkgsS0FBSyxDQUFDTixRQUFOLENBQWVRLElBQWYsQ0FBb0JELE1BQXBCLENBQTJCRyxLQUEzQixDQUFpQyxDQUFqQyxDQUF4QjtBQUNBbkIsY0FBTSxDQUFDRSxVQUFQLENBQWtCLFVBQWxCLEVBQThCM0IsSUFBOUIsQ0FBbUMsT0FBbkM7QUFDRDtBQUNGLEtBaEJIO0FBaUJELEdBL0JEO0FBZ0NELENBcEZBLENBQUQsQyIsImZpbGUiOiIvanMvbG9naW4uanMiLCJzb3VyY2VzQ29udGVudCI6WyIgXHQvLyBUaGUgbW9kdWxlIGNhY2hlXG4gXHR2YXIgaW5zdGFsbGVkTW9kdWxlcyA9IHt9O1xuXG4gXHQvLyBUaGUgcmVxdWlyZSBmdW5jdGlvblxuIFx0ZnVuY3Rpb24gX193ZWJwYWNrX3JlcXVpcmVfXyhtb2R1bGVJZCkge1xuXG4gXHRcdC8vIENoZWNrIGlmIG1vZHVsZSBpcyBpbiBjYWNoZVxuIFx0XHRpZihpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSkge1xuIFx0XHRcdHJldHVybiBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXS5leHBvcnRzO1xuIFx0XHR9XG4gXHRcdC8vIENyZWF0ZSBhIG5ldyBtb2R1bGUgKGFuZCBwdXQgaXQgaW50byB0aGUgY2FjaGUpXG4gXHRcdHZhciBtb2R1bGUgPSBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSA9IHtcbiBcdFx0XHRpOiBtb2R1bGVJZCxcbiBcdFx0XHRsOiBmYWxzZSxcbiBcdFx0XHRleHBvcnRzOiB7fVxuIFx0XHR9O1xuXG4gXHRcdC8vIEV4ZWN1dGUgdGhlIG1vZHVsZSBmdW5jdGlvblxuIFx0XHRtb2R1bGVzW21vZHVsZUlkXS5jYWxsKG1vZHVsZS5leHBvcnRzLCBtb2R1bGUsIG1vZHVsZS5leHBvcnRzLCBfX3dlYnBhY2tfcmVxdWlyZV9fKTtcblxuIFx0XHQvLyBGbGFnIHRoZSBtb2R1bGUgYXMgbG9hZGVkXG4gXHRcdG1vZHVsZS5sID0gdHJ1ZTtcblxuIFx0XHQvLyBSZXR1cm4gdGhlIGV4cG9ydHMgb2YgdGhlIG1vZHVsZVxuIFx0XHRyZXR1cm4gbW9kdWxlLmV4cG9ydHM7XG4gXHR9XG5cblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGVzIG9iamVjdCAoX193ZWJwYWNrX21vZHVsZXNfXylcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubSA9IG1vZHVsZXM7XG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlIGNhY2hlXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmMgPSBpbnN0YWxsZWRNb2R1bGVzO1xuXG4gXHQvLyBkZWZpbmUgZ2V0dGVyIGZ1bmN0aW9uIGZvciBoYXJtb255IGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uZCA9IGZ1bmN0aW9uKGV4cG9ydHMsIG5hbWUsIGdldHRlcikge1xuIFx0XHRpZighX193ZWJwYWNrX3JlcXVpcmVfXy5vKGV4cG9ydHMsIG5hbWUpKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIG5hbWUsIHsgZW51bWVyYWJsZTogdHJ1ZSwgZ2V0OiBnZXR0ZXIgfSk7XG4gXHRcdH1cbiBcdH07XG5cbiBcdC8vIGRlZmluZSBfX2VzTW9kdWxlIG9uIGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uciA9IGZ1bmN0aW9uKGV4cG9ydHMpIHtcbiBcdFx0aWYodHlwZW9mIFN5bWJvbCAhPT0gJ3VuZGVmaW5lZCcgJiYgU3ltYm9sLnRvU3RyaW5nVGFnKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIFN5bWJvbC50b1N0cmluZ1RhZywgeyB2YWx1ZTogJ01vZHVsZScgfSk7XG4gXHRcdH1cbiBcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsICdfX2VzTW9kdWxlJywgeyB2YWx1ZTogdHJ1ZSB9KTtcbiBcdH07XG5cbiBcdC8vIGNyZWF0ZSBhIGZha2UgbmFtZXNwYWNlIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDE6IHZhbHVlIGlzIGEgbW9kdWxlIGlkLCByZXF1aXJlIGl0XG4gXHQvLyBtb2RlICYgMjogbWVyZ2UgYWxsIHByb3BlcnRpZXMgb2YgdmFsdWUgaW50byB0aGUgbnNcbiBcdC8vIG1vZGUgJiA0OiByZXR1cm4gdmFsdWUgd2hlbiBhbHJlYWR5IG5zIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDh8MTogYmVoYXZlIGxpa2UgcmVxdWlyZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy50ID0gZnVuY3Rpb24odmFsdWUsIG1vZGUpIHtcbiBcdFx0aWYobW9kZSAmIDEpIHZhbHVlID0gX193ZWJwYWNrX3JlcXVpcmVfXyh2YWx1ZSk7XG4gXHRcdGlmKG1vZGUgJiA4KSByZXR1cm4gdmFsdWU7XG4gXHRcdGlmKChtb2RlICYgNCkgJiYgdHlwZW9mIHZhbHVlID09PSAnb2JqZWN0JyAmJiB2YWx1ZSAmJiB2YWx1ZS5fX2VzTW9kdWxlKSByZXR1cm4gdmFsdWU7XG4gXHRcdHZhciBucyA9IE9iamVjdC5jcmVhdGUobnVsbCk7XG4gXHRcdF9fd2VicGFja19yZXF1aXJlX18ucihucyk7XG4gXHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShucywgJ2RlZmF1bHQnLCB7IGVudW1lcmFibGU6IHRydWUsIHZhbHVlOiB2YWx1ZSB9KTtcbiBcdFx0aWYobW9kZSAmIDIgJiYgdHlwZW9mIHZhbHVlICE9ICdzdHJpbmcnKSBmb3IodmFyIGtleSBpbiB2YWx1ZSkgX193ZWJwYWNrX3JlcXVpcmVfXy5kKG5zLCBrZXksIGZ1bmN0aW9uKGtleSkgeyByZXR1cm4gdmFsdWVba2V5XTsgfS5iaW5kKG51bGwsIGtleSkpO1xuIFx0XHRyZXR1cm4gbnM7XG4gXHR9O1xuXG4gXHQvLyBnZXREZWZhdWx0RXhwb3J0IGZ1bmN0aW9uIGZvciBjb21wYXRpYmlsaXR5IHdpdGggbm9uLWhhcm1vbnkgbW9kdWxlc1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5uID0gZnVuY3Rpb24obW9kdWxlKSB7XG4gXHRcdHZhciBnZXR0ZXIgPSBtb2R1bGUgJiYgbW9kdWxlLl9fZXNNb2R1bGUgP1xuIFx0XHRcdGZ1bmN0aW9uIGdldERlZmF1bHQoKSB7IHJldHVybiBtb2R1bGVbJ2RlZmF1bHQnXTsgfSA6XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0TW9kdWxlRXhwb3J0cygpIHsgcmV0dXJuIG1vZHVsZTsgfTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kKGdldHRlciwgJ2EnLCBnZXR0ZXIpO1xuIFx0XHRyZXR1cm4gZ2V0dGVyO1xuIFx0fTtcblxuIFx0Ly8gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm8gPSBmdW5jdGlvbihvYmplY3QsIHByb3BlcnR5KSB7IHJldHVybiBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGwob2JqZWN0LCBwcm9wZXJ0eSk7IH07XG5cbiBcdC8vIF9fd2VicGFja19wdWJsaWNfcGF0aF9fXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnAgPSBcIi9cIjtcblxuXG4gXHQvLyBMb2FkIGVudHJ5IG1vZHVsZSBhbmQgcmV0dXJuIGV4cG9ydHNcbiBcdHJldHVybiBfX3dlYnBhY2tfcmVxdWlyZV9fKF9fd2VicGFja19yZXF1aXJlX18ucyA9IDEpO1xuIiwiLyohXHJcbiogTm90aWZsaXggUmVhY3QgKCdodHRwczovL3d3dy5ub3RpZmxpeC5jb20vcmVhY3QnKVxyXG4qIFZlcnNpb246IDEuNC4wXHJcbiogQXV0aG9yOiBGdXJrYW4gTVQgKCdodHRwczovL2dpdGh1Yi5jb20vZnVyY2FuJylcclxuKiBDb3B5cmlnaHQgMjAxOSBOb3RpZmxpeCwgTUlUIExpY2VuY2UgKCdodHRwczovL29wZW5zb3VyY2Uub3JnL2xpY2Vuc2VzL01JVCcpXHJcbiovXHJcblxyXG4vLyBJbnRlcm5hbCBDU1MgQ29kZXMgb25cclxuY29uc3Qgbm90aWZsaXhJbnRlcm5hbENTU0NvZGVzID0gKCkgPT4ge1xyXG5cclxuICAgIGNvbnN0IGNzcyA9IGBbaWRePU5vdGlmbGl4Tm90aWZ5V3JhcF17cG9zaXRpb246Zml4ZWQ7ei1pbmRleDoxMDAxO29wYWNpdHk6MTtyaWdodDoxMHB4O3RvcDoxMHB4O3dpZHRoOjI4MHB4O21heC13aWR0aDo5NiU7Ym94LXNpemluZzpib3JkZXItYm94O2JhY2tncm91bmQ6MCAwfVtpZF49Tm90aWZsaXhOb3RpZnlXcmFwXSAqe2JveC1zaXppbmc6Ym9yZGVyLWJveH1baWRePU5vdGlmbGl4Tm90aWZ5V3JhcF0+ZGl2ey13ZWJraXQtdXNlci1zZWxlY3Q6bm9uZTstbW96LXVzZXItc2VsZWN0Om5vbmU7LW1zLXVzZXItc2VsZWN0Om5vbmU7dXNlci1zZWxlY3Q6bm9uZTtmb250LWZhbWlseTpRdWlja3NhbmQsc2Fucy1zZXJpZjt3aWR0aDoxMDAlO2Rpc3BsYXk6aW5saW5lLWJsb2NrO3Bvc2l0aW9uOnJlbGF0aXZlO21hcmdpbjowIDAgMTBweDtib3JkZXItcmFkaXVzOjVweDtiYWNrZ3JvdW5kOiMxZTFlMWU7Y29sb3I6I2ZmZjtwYWRkaW5nOjEwcHggMTJweDtmb250LXNpemU6MTRweDtsaW5lLWhlaWdodDoxLjR9W2lkXj1Ob3RpZmxpeE5vdGlmeVdyYXBdPmRpdjpsYXN0LWNoaWxke21hcmdpbjowfVtpZF49Tm90aWZsaXhOb3RpZnlXcmFwXT5kaXYud2l0aC1jYWxsYmFja3tjdXJzb3I6cG9pbnRlcn1baWRePU5vdGlmbGl4Tm90aWZ5V3JhcF0gOjpzZWxlY3Rpb257YmFja2dyb3VuZDppbmhlcml0fVtpZF49Tm90aWZsaXhOb3RpZnlXcmFwXT5kaXYud2l0aC1pY29ue3BhZGRpbmc6OHB4fVtpZF49Tm90aWZsaXhOb3RpZnlXcmFwXT5kaXYud2l0aC1jbG9zZXtwYWRkaW5nOjEwcHggMzBweCAxMHB4IDEycHh9W2lkXj1Ob3RpZmxpeE5vdGlmeVdyYXBdPmRpdi53aXRoLWljb24ud2l0aC1jbG9zZXtwYWRkaW5nOjZweCAzMHB4IDZweCA2cHh9W2lkXj1Ob3RpZmxpeE5vdGlmeVdyYXBdPmRpdj5zcGFuLnRoZS1tZXNzYWdle2ZvbnQtd2VpZ2h0OjUwMDtmb250LWZhbWlseTppbmhlcml0IWltcG9ydGFudDt3b3JkLWJyZWFrOmJyZWFrLWFsbDt3b3JkLWJyZWFrOmJyZWFrLXdvcmR9W2lkXj1Ob3RpZmxpeE5vdGlmeVdyYXBdPmRpdj5zcGFuLmNsaWNrLXRvLWNsb3Nle2N1cnNvcjpwb2ludGVyO3RyYW5zaXRpb246YWxsIC4ycyBlYXNlLWluLW91dDtwb3NpdGlvbjphYnNvbHV0ZTtyaWdodDo4cHg7dG9wOjA7Ym90dG9tOjA7bWFyZ2luOmF1dG87Y29sb3I6aW5oZXJpdDt3aWR0aDoxNnB4O2hlaWdodDoxNnB4fVtpZF49Tm90aWZsaXhOb3RpZnlXcmFwXT5kaXY+c3Bhbi5jbGljay10by1jbG9zZTpob3Zlcnt0cmFuc2Zvcm06cm90YXRlKDkwZGVnKX1baWRePU5vdGlmbGl4Tm90aWZ5V3JhcF0+ZGl2PnNwYW4uY2xpY2stdG8tY2xvc2U+c3Zne3Bvc2l0aW9uOmFic29sdXRlO3dpZHRoOjE2cHg7aGVpZ2h0OjE2cHg7cmlnaHQ6MDt0b3A6MH1baWRePU5vdGlmbGl4Tm90aWZ5V3JhcF0+ZGl2Pi5ubWl7cG9zaXRpb246YWJzb2x1dGU7d2lkdGg6NDBweDtoZWlnaHQ6NDBweDtmb250LXNpemU6MzBweDtsaW5lLWhlaWdodDo0MHB4O3RleHQtYWxpZ246Y2VudGVyO2xlZnQ6OHB4O3RvcDowO2JvdHRvbTowO21hcmdpbjphdXRvO2JvcmRlci1yYWRpdXM6aW5oZXJpdH1baWRePU5vdGlmbGl4Tm90aWZ5V3JhcF0+ZGl2Pi53ZmEuc2hhZG93e2NvbG9yOmluaGVyaXQ7YmFja2dyb3VuZDpyZ2JhKDAsMCwwLC4xNSk7Ym94LXNoYWRvdzppbnNldCAwIDAgMzRweCByZ2JhKDAsMCwwLC4yKTt0ZXh0LXNoYWRvdzowIDAgMTBweCByZ2JhKDAsMCwwLC4zKX1baWRePU5vdGlmbGl4Tm90aWZ5V3JhcF0+ZGl2PnNwYW4ud2l0aC1pY29ue3Bvc2l0aW9uOnJlbGF0aXZlO2Zsb2F0OmxlZnQ7d2lkdGg6Y2FsYygxMDAlIC0gNDBweCk7bWFyZ2luOjAgMCAwIDQwcHg7cGFkZGluZzowIDAgMCAxMHB4O2JveC1zaXppbmc6Ym9yZGVyLWJveH1baWRePU5vdGlmbGl4Tm90aWZ5V3JhcF0+ZGl2LnJ0bC1vbj4ubm1pe2xlZnQ6YXV0bztyaWdodDo4cHh9W2lkXj1Ob3RpZmxpeE5vdGlmeVdyYXBdPmRpdi5ydGwtb24+c3Bhbi53aXRoLWljb257cGFkZGluZzowIDEwcHggMCAwO21hcmdpbjowIDQwcHggMCAwfVtpZF49Tm90aWZsaXhOb3RpZnlXcmFwXT5kaXYucnRsLW9uPnNwYW4uY2xpY2stdG8tY2xvc2V7cmlnaHQ6YXV0bztsZWZ0OjhweH1baWRePU5vdGlmbGl4Tm90aWZ5V3JhcF0+ZGl2LndpdGgtaWNvbi53aXRoLWNsb3NlLnJ0bC1vbntwYWRkaW5nOjZweCA2cHggNnB4IDMwcHh9W2lkXj1Ob3RpZmxpeE5vdGlmeVdyYXBdPmRpdi53aXRoLWNsb3NlLnJ0bC1vbntwYWRkaW5nOjEwcHggMTJweCAxMHB4IDMwcHh9W2lkXj1Ob3RpZmxpeE5vdGlmeU92ZXJsYXldLndpdGgtYW5pbWF0aW9uLFtpZF49Tm90aWZsaXhOb3RpZnlXcmFwXT5kaXYud2l0aC1hbmltYXRpb24ubngtZmFkZXthbmltYXRpb246bm90aWZ5LWFuaW1hdGlvbi1mYWRlIC4zcyBlYXNlLWluLW91dCAwcyBub3JtYWw7LXdlYmtpdC1hbmltYXRpb246bm90aWZ5LWFuaW1hdGlvbi1mYWRlIC4zcyBlYXNlLWluLW91dCAwcyBub3JtYWx9QGtleWZyYW1lcyBub3RpZnktYW5pbWF0aW9uLWZhZGV7MCV7b3BhY2l0eTowfTEwMCV7b3BhY2l0eToxfX1ALXdlYmtpdC1rZXlmcmFtZXMgbm90aWZ5LWFuaW1hdGlvbi1mYWRlezAle29wYWNpdHk6MH0xMDAle29wYWNpdHk6MX19W2lkXj1Ob3RpZmxpeE5vdGlmeVdyYXBdPmRpdi53aXRoLWFuaW1hdGlvbi5ueC16b29te2FuaW1hdGlvbjpub3RpZnktYW5pbWF0aW9uLXpvb20gLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbDstd2Via2l0LWFuaW1hdGlvbjpub3RpZnktYW5pbWF0aW9uLXpvb20gLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbH1Aa2V5ZnJhbWVzIG5vdGlmeS1hbmltYXRpb24tem9vbXswJXt0cmFuc2Zvcm06c2NhbGUoMCl9NTAle3RyYW5zZm9ybTpzY2FsZSgxLjA1KX0xMDAle3RyYW5zZm9ybTpzY2FsZSgxKX19QC13ZWJraXQta2V5ZnJhbWVzIG5vdGlmeS1hbmltYXRpb24tem9vbXswJXt0cmFuc2Zvcm06c2NhbGUoMCl9NTAle3RyYW5zZm9ybTpzY2FsZSgxLjA1KX0xMDAle3RyYW5zZm9ybTpzY2FsZSgxKX19W2lkXj1Ob3RpZmxpeE5vdGlmeVdyYXBdPmRpdi53aXRoLWFuaW1hdGlvbi5ueC1mcm9tLXJpZ2h0e2FuaW1hdGlvbjpub3RpZnktYW5pbWF0aW9uLWZyb20tcmlnaHQgLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbDstd2Via2l0LWFuaW1hdGlvbjpub3RpZnktYW5pbWF0aW9uLWZyb20tcmlnaHQgLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbH1Aa2V5ZnJhbWVzIG5vdGlmeS1hbmltYXRpb24tZnJvbS1yaWdodHswJXtyaWdodDotMzAwcHg7b3BhY2l0eTowfTUwJXtyaWdodDo4cHg7b3BhY2l0eToxfTEwMCV7cmlnaHQ6MDtvcGFjaXR5OjF9fUAtd2Via2l0LWtleWZyYW1lcyBub3RpZnktYW5pbWF0aW9uLWZyb20tcmlnaHR7MCV7cmlnaHQ6LTMwMHB4O29wYWNpdHk6MH01MCV7cmlnaHQ6OHB4O29wYWNpdHk6MX0xMDAle3JpZ2h0OjA7b3BhY2l0eToxfX1baWRePU5vdGlmbGl4Tm90aWZ5V3JhcF0+ZGl2LndpdGgtYW5pbWF0aW9uLm54LWZyb20tbGVmdHthbmltYXRpb246bm90aWZ5LWFuaW1hdGlvbi1mcm9tLWxlZnQgLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbDstd2Via2l0LWFuaW1hdGlvbjpub3RpZnktYW5pbWF0aW9uLWZyb20tbGVmdCAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsfUBrZXlmcmFtZXMgbm90aWZ5LWFuaW1hdGlvbi1mcm9tLWxlZnR7MCV7bGVmdDotMzAwcHg7b3BhY2l0eTowfTUwJXtsZWZ0OjhweDtvcGFjaXR5OjF9MTAwJXtsZWZ0OjA7b3BhY2l0eToxfX1ALXdlYmtpdC1rZXlmcmFtZXMgbm90aWZ5LWFuaW1hdGlvbi1mcm9tLWxlZnR7MCV7bGVmdDotMzAwcHg7b3BhY2l0eTowfTUwJXtsZWZ0OjhweDtvcGFjaXR5OjF9MTAwJXtsZWZ0OjA7b3BhY2l0eToxfX1baWRePU5vdGlmbGl4Tm90aWZ5V3JhcF0+ZGl2LndpdGgtYW5pbWF0aW9uLm54LWZyb20tdG9we2FuaW1hdGlvbjpub3RpZnktYW5pbWF0aW9uLWZyb20tdG9wIC4zcyBlYXNlLWluLW91dCAwcyBub3JtYWw7LXdlYmtpdC1hbmltYXRpb246bm90aWZ5LWFuaW1hdGlvbi1mcm9tLXRvcCAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsfUBrZXlmcmFtZXMgbm90aWZ5LWFuaW1hdGlvbi1mcm9tLXRvcHswJXt0b3A6LTUwcHg7b3BhY2l0eTowfTUwJXt0b3A6OHB4O29wYWNpdHk6MX0xMDAle3RvcDowO29wYWNpdHk6MX19QC13ZWJraXQta2V5ZnJhbWVzIG5vdGlmeS1hbmltYXRpb24tZnJvbS10b3B7MCV7dG9wOi01MHB4O29wYWNpdHk6MH01MCV7dG9wOjhweDtvcGFjaXR5OjF9MTAwJXt0b3A6MDtvcGFjaXR5OjF9fVtpZF49Tm90aWZsaXhOb3RpZnlXcmFwXT5kaXYud2l0aC1hbmltYXRpb24ubngtZnJvbS1ib3R0b217YW5pbWF0aW9uOm5vdGlmeS1hbmltYXRpb24tZnJvbS1ib3R0b20gLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbDstd2Via2l0LWFuaW1hdGlvbjpub3RpZnktYW5pbWF0aW9uLWZyb20tYm90dG9tIC4zcyBlYXNlLWluLW91dCAwcyBub3JtYWx9QGtleWZyYW1lcyBub3RpZnktYW5pbWF0aW9uLWZyb20tYm90dG9tezAle2JvdHRvbTotNTBweDtvcGFjaXR5OjB9NTAle2JvdHRvbTo4cHg7b3BhY2l0eToxfTEwMCV7Ym90dG9tOjA7b3BhY2l0eToxfX1ALXdlYmtpdC1rZXlmcmFtZXMgbm90aWZ5LWFuaW1hdGlvbi1mcm9tLWJvdHRvbXswJXtib3R0b206LTUwcHg7b3BhY2l0eTowfTUwJXtib3R0b206OHB4O29wYWNpdHk6MX0xMDAle2JvdHRvbTowO29wYWNpdHk6MX19W2lkXj1Ob3RpZmxpeE5vdGlmeU92ZXJsYXldLndpdGgtYW5pbWF0aW9uLnJlbW92ZSxbaWRePU5vdGlmbGl4Tm90aWZ5V3JhcF0+ZGl2LndpdGgtYW5pbWF0aW9uLm54LWZhZGUucmVtb3Zle29wYWNpdHk6MDthbmltYXRpb246bm90aWZ5LXJlbW92ZS1mYWRlIC4zcyBlYXNlLWluLW91dCAwcyBub3JtYWw7LXdlYmtpdC1hbmltYXRpb246bm90aWZ5LXJlbW92ZS1mYWRlIC4zcyBlYXNlLWluLW91dCAwcyBub3JtYWx9QGtleWZyYW1lcyBub3RpZnktcmVtb3ZlLWZhZGV7MCV7b3BhY2l0eToxfTEwMCV7b3BhY2l0eTowfX1ALXdlYmtpdC1rZXlmcmFtZXMgbm90aWZ5LXJlbW92ZS1mYWRlezAle29wYWNpdHk6MX0xMDAle29wYWNpdHk6MH19W2lkXj1Ob3RpZmxpeE5vdGlmeVdyYXBdPmRpdi53aXRoLWFuaW1hdGlvbi5ueC16b29tLnJlbW92ZXt0cmFuc2Zvcm06c2NhbGUoMCk7YW5pbWF0aW9uOm5vdGlmeS1yZW1vdmUtem9vbSAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsOy13ZWJraXQtYW5pbWF0aW9uOm5vdGlmeS1yZW1vdmUtem9vbSAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsfUBrZXlmcmFtZXMgbm90aWZ5LXJlbW92ZS16b29tezAle3RyYW5zZm9ybTpzY2FsZSgxKX01MCV7dHJhbnNmb3JtOnNjYWxlKDEuMDUpfTEwMCV7dHJhbnNmb3JtOnNjYWxlKDApfX1ALXdlYmtpdC1rZXlmcmFtZXMgbm90aWZ5LXJlbW92ZS16b29tezAle3RyYW5zZm9ybTpzY2FsZSgxKX01MCV7dHJhbnNmb3JtOnNjYWxlKDEuMDUpfTEwMCV7dHJhbnNmb3JtOnNjYWxlKDApfX1baWRePU5vdGlmbGl4Tm90aWZ5V3JhcF0+ZGl2LndpdGgtYW5pbWF0aW9uLm54LWZyb20tdG9wLnJlbW92ZXtvcGFjaXR5OjA7YW5pbWF0aW9uOm5vdGlmeS1yZW1vdmUtdG8tdG9wIC4zcyBlYXNlLWluLW91dCAwcyBub3JtYWw7LXdlYmtpdC1hbmltYXRpb246bm90aWZ5LXJlbW92ZS10by10b3AgLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbH1Aa2V5ZnJhbWVzIG5vdGlmeS1yZW1vdmUtdG8tdG9wezAle3RvcDowO29wYWNpdHk6MX01MCV7dG9wOjhweDtvcGFjaXR5OjF9MTAwJXt0b3A6LTUwcHg7b3BhY2l0eTowfX1ALXdlYmtpdC1rZXlmcmFtZXMgbm90aWZ5LXJlbW92ZS10by10b3B7MCV7dG9wOjA7b3BhY2l0eToxfTUwJXt0b3A6OHB4O29wYWNpdHk6MX0xMDAle3RvcDotNTBweDtvcGFjaXR5OjB9fVtpZF49Tm90aWZsaXhOb3RpZnlXcmFwXT5kaXYud2l0aC1hbmltYXRpb24ubngtZnJvbS1yaWdodC5yZW1vdmV7b3BhY2l0eTowO2FuaW1hdGlvbjpub3RpZnktcmVtb3ZlLXRvLXJpZ2h0IC4zcyBlYXNlLWluLW91dCAwcyBub3JtYWw7LXdlYmtpdC1hbmltYXRpb246bm90aWZ5LXJlbW92ZS10by1yaWdodCAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsfUBrZXlmcmFtZXMgbm90aWZ5LXJlbW92ZS10by1yaWdodHswJXtyaWdodDowO29wYWNpdHk6MX01MCV7cmlnaHQ6OHB4O29wYWNpdHk6MX0xMDAle3JpZ2h0Oi0zMDBweDtvcGFjaXR5OjB9fUAtd2Via2l0LWtleWZyYW1lcyBub3RpZnktcmVtb3ZlLXRvLXJpZ2h0ezAle3JpZ2h0OjA7b3BhY2l0eToxfTUwJXtyaWdodDo4cHg7b3BhY2l0eToxfTEwMCV7cmlnaHQ6LTMwMHB4O29wYWNpdHk6MH19W2lkXj1Ob3RpZmxpeE5vdGlmeVdyYXBdPmRpdi53aXRoLWFuaW1hdGlvbi5ueC1mcm9tLWJvdHRvbS5yZW1vdmV7b3BhY2l0eTowO2FuaW1hdGlvbjpub3RpZnktcmVtb3ZlLXRvLWJvdHRvbSAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsOy13ZWJraXQtYW5pbWF0aW9uOm5vdGlmeS1yZW1vdmUtdG8tYm90dG9tIC4zcyBlYXNlLWluLW91dCAwcyBub3JtYWx9QGtleWZyYW1lcyBub3RpZnktcmVtb3ZlLXRvLWJvdHRvbXswJXtib3R0b206MDtvcGFjaXR5OjF9NTAle2JvdHRvbTo4cHg7b3BhY2l0eToxfTEwMCV7Ym90dG9tOi01MHB4O29wYWNpdHk6MH19QC13ZWJraXQta2V5ZnJhbWVzIG5vdGlmeS1yZW1vdmUtdG8tYm90dG9tezAle2JvdHRvbTowO29wYWNpdHk6MX01MCV7Ym90dG9tOjhweDtvcGFjaXR5OjF9MTAwJXtib3R0b206LTUwcHg7b3BhY2l0eTowfX1baWRePU5vdGlmbGl4Tm90aWZ5V3JhcF0+ZGl2LndpdGgtYW5pbWF0aW9uLm54LWZyb20tbGVmdC5yZW1vdmV7b3BhY2l0eTowO2FuaW1hdGlvbjpub3RpZnktcmVtb3ZlLXRvLWxlZnQgLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbDstd2Via2l0LWFuaW1hdGlvbjpub3RpZnktcmVtb3ZlLXRvLWxlZnQgLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbH1Aa2V5ZnJhbWVzIG5vdGlmeS1yZW1vdmUtdG8tbGVmdHswJXtsZWZ0OjA7b3BhY2l0eToxfTUwJXtsZWZ0OjhweDtvcGFjaXR5OjF9MTAwJXtsZWZ0Oi0zMDBweDtvcGFjaXR5OjB9fUAtd2Via2l0LWtleWZyYW1lcyBub3RpZnktcmVtb3ZlLXRvLWxlZnR7MCV7bGVmdDowO29wYWNpdHk6MX01MCV7bGVmdDo4cHg7b3BhY2l0eToxfTEwMCV7bGVmdDotMzAwcHg7b3BhY2l0eTowfX1baWRePU5vdGlmbGl4UmVwb3J0V3JhcF17cG9zaXRpb246Zml4ZWQ7ei1pbmRleDoxMDAwO3dpZHRoOjMyMHB4O21heC13aWR0aDo5NiU7Ym94LXNpemluZzpib3JkZXItYm94O2ZvbnQtZmFtaWx5OlF1aWNrc2FuZCxzYW5zLXNlcmlmO2xlZnQ6MDtyaWdodDowO3RvcDoyMHB4O2NvbG9yOiMxZTFlMWU7Ym9yZGVyLXJhZGl1czoyNXB4O2JhY2tncm91bmQ6MCAwO21hcmdpbjphdXRvfVtpZF49Tm90aWZsaXhSZXBvcnRXcmFwXSAqe2JveC1zaXppbmc6Ym9yZGVyLWJveH1baWRePU5vdGlmbGl4UmVwb3J0V3JhcF0+ZGl2W2NsYXNzKj1cIi1vdmVybGF5XCJde3dpZHRoOjEwMCU7aGVpZ2h0OjEwMCU7bGVmdDowO3RvcDowO2JhY2tncm91bmQ6cmdiYSgyNTUsMjU1LDI1NSwuNSk7cG9zaXRpb246Zml4ZWQ7ei1pbmRleDowfVtpZF49Tm90aWZsaXhSZXBvcnRXcmFwXT5kaXZbY2xhc3MqPVwiLWNvbnRlbnRcIl17d2lkdGg6MTAwJTtmbG9hdDpsZWZ0O2JvcmRlci1yYWRpdXM6aW5oZXJpdDtwYWRkaW5nOjEwcHg7ZmlsdGVyOmRyb3Atc2hhZG93KDAgMCA1cHggcmdiYSgwLDAsMCwuMSkpO2JvcmRlcjoxcHggc29saWQgcmdiYSgwLDAsMCwuMDMpO2JhY2tncm91bmQ6I2Y4ZjhmODtwb3NpdGlvbjpyZWxhdGl2ZTt6LWluZGV4OjF9W2lkXj1Ob3RpZmxpeFJlcG9ydFdyYXBdPmRpdltjbGFzcyo9XCItY29udGVudFwiXT5kaXZbY2xhc3MkPVwiLWljb25cIl17LXdlYmtpdC11c2VyLXNlbGVjdDpub25lOy1tb3otdXNlci1zZWxlY3Q6bm9uZTstbXMtdXNlci1zZWxlY3Q6bm9uZTt1c2VyLXNlbGVjdDpub25lO3dpZHRoOjExMHB4O2hlaWdodDoxMTBweDtkaXNwbGF5OmJsb2NrO21hcmdpbjo2cHggYXV0byAxMnB4fVtpZF49Tm90aWZsaXhSZXBvcnRXcmFwXT5kaXZbY2xhc3MqPVwiLWNvbnRlbnRcIl0+ZGl2W2NsYXNzJD1cIi1pY29uXCJdIHN2Z3ttaW4td2lkdGg6MTAwJTttYXgtd2lkdGg6MTAwJTtoZWlnaHQ6YXV0b31baWRePU5vdGlmbGl4UmVwb3J0V3JhcF0+Kj5oNXt3b3JkLWJyZWFrOmJyZWFrLWFsbDt3b3JkLWJyZWFrOmJyZWFrLXdvcmQ7Zm9udC1mYW1pbHk6aW5oZXJpdCFpbXBvcnRhbnQ7Zm9udC1zaXplOjE2cHg7Zm9udC13ZWlnaHQ6NTAwO2xpbmUtaGVpZ2h0OjEuNDttYXJnaW46MCAwIDEwcHg7cGFkZGluZzowIDAgMTBweDtib3JkZXItYm90dG9tOjFweCBzb2xpZCByZ2JhKDAsMCwwLC4xKTtmbG9hdDpsZWZ0O3dpZHRoOjEwMCU7dGV4dC1hbGlnbjpjZW50ZXJ9W2lkXj1Ob3RpZmxpeFJlcG9ydFdyYXBdPio+cHt3b3JkLWJyZWFrOmJyZWFrLWFsbDt3b3JkLWJyZWFrOmJyZWFrLXdvcmQ7Zm9udC1mYW1pbHk6aW5oZXJpdCFpbXBvcnRhbnQ7Zm9udC1zaXplOjEzcHg7bGluZS1oZWlnaHQ6MS40O2Zsb2F0OmxlZnQ7d2lkdGg6MTAwJTtwYWRkaW5nOjAgMTBweDttYXJnaW46MCAwIDEwcHh9W2lkXj1Ob3RpZmxpeFJlcG9ydFdyYXBdIGEjTlhSZXBvcnRCdXR0b257d29yZC1icmVhazpicmVhay1hbGw7d29yZC1icmVhazpicmVhay13b3JkOy13ZWJraXQtdXNlci1zZWxlY3Q6bm9uZTstbW96LXVzZXItc2VsZWN0Om5vbmU7LW1zLXVzZXItc2VsZWN0Om5vbmU7dXNlci1zZWxlY3Q6bm9uZTtmb250LWZhbWlseTppbmhlcml0IWltcG9ydGFudDt0cmFuc2l0aW9uOmFsbCAuMjVzIGVhc2UtaW4tb3V0O2N1cnNvcjpwb2ludGVyO2Zsb2F0OnJpZ2h0O3BhZGRpbmc6N3B4IDE3cHg7YmFja2dyb3VuZDojMDBiNDYyO2ZvbnQtc2l6ZToxNHB4O2xpbmUtaGVpZ2h0OjEuNDtmb250LXdlaWdodDo1MDA7Ym9yZGVyLXJhZGl1czppbmhlcml0IWltcG9ydGFudDtjb2xvcjojZmZmfVtpZF49Tm90aWZsaXhSZXBvcnRXcmFwXSBhI05YUmVwb3J0QnV0dG9uOmhvdmVye3BhZGRpbmc6N3B4IDIwcHh9W2lkXj1Ob3RpZmxpeFJlcG9ydFdyYXBdLnJ0bC1vbiBhI05YUmVwb3J0QnV0dG9ue2Zsb2F0OmxlZnR9W2lkXj1Ob3RpZmxpeFJlcG9ydFdyYXBdPmRpdltjbGFzcyo9XCItb3ZlcmxheVwiXS53aXRoLWFuaW1hdGlvbnthbmltYXRpb246cmVwb3J0LW92ZXJsYXktYW5pbWF0aW9uIC4zcyBlYXNlLWluLW91dCAwcyBub3JtYWw7LXdlYmtpdC1hbmltYXRpb246cmVwb3J0LW92ZXJsYXktYW5pbWF0aW9uIC4zcyBlYXNlLWluLW91dCAwcyBub3JtYWx9QGtleWZyYW1lcyByZXBvcnQtb3ZlcmxheS1hbmltYXRpb257MCV7b3BhY2l0eTowfTEwMCV7b3BhY2l0eToxfX1ALXdlYmtpdC1rZXlmcmFtZXMgcmVwb3J0LW92ZXJsYXktYW5pbWF0aW9uezAle29wYWNpdHk6MH0xMDAle29wYWNpdHk6MX19W2lkXj1Ob3RpZmxpeFJlcG9ydFdyYXBdPmRpdltjbGFzcyo9XCItY29udGVudFwiXS53aXRoLWFuaW1hdGlvbi5ueC1mYWRle2FuaW1hdGlvbjpyZXBvcnQtYW5pbWF0aW9uLWZhZGUgLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbDstd2Via2l0LWFuaW1hdGlvbjpyZXBvcnQtYW5pbWF0aW9uLWZhZGUgLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbH1Aa2V5ZnJhbWVzIHJlcG9ydC1hbmltYXRpb24tZmFkZXswJXtvcGFjaXR5OjB9MTAwJXtvcGFjaXR5OjF9fUAtd2Via2l0LWtleWZyYW1lcyByZXBvcnQtYW5pbWF0aW9uLWZhZGV7MCV7b3BhY2l0eTowfTEwMCV7b3BhY2l0eToxfX1baWRePU5vdGlmbGl4UmVwb3J0V3JhcF0+ZGl2W2NsYXNzKj1cIi1jb250ZW50XCJdLndpdGgtYW5pbWF0aW9uLm54LXpvb217YW5pbWF0aW9uOnJlcG9ydC1hbmltYXRpb24tem9vbSAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsOy13ZWJraXQtYW5pbWF0aW9uOnJlcG9ydC1hbmltYXRpb24tem9vbSAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsfUBrZXlmcmFtZXMgcmVwb3J0LWFuaW1hdGlvbi16b29tezAle29wYWNpdHk6MDt0cmFuc2Zvcm06c2NhbGUoLjUpfTUwJXtvcGFjaXR5OjE7dHJhbnNmb3JtOnNjYWxlKDEuMDUpfTEwMCV7b3BhY2l0eToxO3RyYW5zZm9ybTpzY2FsZSgxKX19QC13ZWJraXQta2V5ZnJhbWVzIHJlcG9ydC1hbmltYXRpb24tem9vbXswJXtvcGFjaXR5OjA7dHJhbnNmb3JtOnNjYWxlKC41KX01MCV7b3BhY2l0eToxO3RyYW5zZm9ybTpzY2FsZSgxLjA1KX0xMDAle29wYWNpdHk6MTt0cmFuc2Zvcm06c2NhbGUoMSl9fVtpZF49Tm90aWZsaXhSZXBvcnRXcmFwXS5yZW1vdmU+ZGl2W2NsYXNzKj1cIi1vdmVybGF5XCJdLndpdGgtYW5pbWF0aW9ue29wYWNpdHk6MDthbmltYXRpb246cmVwb3J0LW92ZXJsYXktYW5pbWF0aW9uLXJlbW92ZSAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsOy13ZWJraXQtYW5pbWF0aW9uOnJlcG9ydC1vdmVybGF5LWFuaW1hdGlvbi1yZW1vdmUgLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbH1Aa2V5ZnJhbWVzIHJlcG9ydC1vdmVybGF5LWFuaW1hdGlvbi1yZW1vdmV7MCV7b3BhY2l0eToxfTEwMCV7b3BhY2l0eTowfX1ALXdlYmtpdC1rZXlmcmFtZXMgcmVwb3J0LW92ZXJsYXktYW5pbWF0aW9uLXJlbW92ZXswJXtvcGFjaXR5OjF9MTAwJXtvcGFjaXR5OjB9fVtpZF49Tm90aWZsaXhSZXBvcnRXcmFwXS5yZW1vdmU+ZGl2W2NsYXNzKj1cIi1jb250ZW50XCJdLndpdGgtYW5pbWF0aW9uLm54LWZhZGV7b3BhY2l0eTowO2FuaW1hdGlvbjpyZXBvcnQtYW5pbWF0aW9uLWZhZGUtcmVtb3ZlIC4zcyBlYXNlLWluLW91dCAwcyBub3JtYWw7LXdlYmtpdC1hbmltYXRpb246cmVwb3J0LWFuaW1hdGlvbi1mYWRlLXJlbW92ZSAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsfUBrZXlmcmFtZXMgcmVwb3J0LWFuaW1hdGlvbi1mYWRlLXJlbW92ZXswJXtvcGFjaXR5OjF9MTAwJXtvcGFjaXR5OjB9fUAtd2Via2l0LWtleWZyYW1lcyByZXBvcnQtYW5pbWF0aW9uLWZhZGUtcmVtb3ZlezAle29wYWNpdHk6MX0xMDAle29wYWNpdHk6MH19W2lkXj1Ob3RpZmxpeFJlcG9ydFdyYXBdLnJlbW92ZT5kaXZbY2xhc3MqPVwiLWNvbnRlbnRcIl0ud2l0aC1hbmltYXRpb24ubngtem9vbXtvcGFjaXR5OjA7YW5pbWF0aW9uOnJlcG9ydC1hbmltYXRpb24tem9vbS1yZW1vdmUgLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbDstd2Via2l0LWFuaW1hdGlvbjpyZXBvcnQtYW5pbWF0aW9uLXpvb20tcmVtb3ZlIC4zcyBlYXNlLWluLW91dCAwcyBub3JtYWx9QGtleWZyYW1lcyByZXBvcnQtYW5pbWF0aW9uLXpvb20tcmVtb3ZlezAle29wYWNpdHk6MTt0cmFuc2Zvcm06c2NhbGUoMSl9NTAle29wYWNpdHk6LjU7dHJhbnNmb3JtOnNjYWxlKDEuMDUpfTEwMCV7b3BhY2l0eTowO3RyYW5zZm9ybTpzY2FsZSgwKX19QC13ZWJraXQta2V5ZnJhbWVzIHJlcG9ydC1hbmltYXRpb24tem9vbS1yZW1vdmV7MCV7b3BhY2l0eToxO3RyYW5zZm9ybTpzY2FsZSgxKX01MCV7b3BhY2l0eTouNTt0cmFuc2Zvcm06c2NhbGUoMS4wNSl9MTAwJXtvcGFjaXR5OjA7dHJhbnNmb3JtOnNjYWxlKDApfX1baWRePU5vdGlmbGl4TG9hZGluZ1dyYXBdey13ZWJraXQtdXNlci1zZWxlY3Q6bm9uZTstbW96LXVzZXItc2VsZWN0Om5vbmU7LW1zLXVzZXItc2VsZWN0Om5vbmU7dXNlci1zZWxlY3Q6bm9uZTtwb3NpdGlvbjpmaXhlZDt6LWluZGV4OjEwMDQ7d2lkdGg6MTAwJTtoZWlnaHQ6MTAwJTtsZWZ0OjA7dG9wOjA7cmlnaHQ6MDtib3R0b206MDttYXJnaW46YXV0bzt0ZXh0LWFsaWduOmNlbnRlcjtib3gtc2l6aW5nOmJvcmRlci1ib3g7YmFja2dyb3VuZDpyZ2JhKDAsMCwwLC44KTtmb250LWZhbWlseTpRdWlja3NhbmQsc2Fucy1zZXJpZn1baWRePU5vdGlmbGl4TG9hZGluZ1dyYXBdICp7Ym94LXNpemluZzpib3JkZXItYm94fVtpZF49Tm90aWZsaXhMb2FkaW5nV3JhcF0uY2xpY2stdG8tY2xvc2V7Y3Vyc29yOnBvaW50ZXJ9W2lkXj1Ob3RpZmxpeExvYWRpbmdXcmFwXT5kaXZbY2xhc3MqPVwiLWljb25cIl17d2lkdGg6NjBweDtoZWlnaHQ6NjBweDtwb3NpdGlvbjpmaXhlZDt0cmFuc2l0aW9uOnRvcCAuMnMgZWFzZS1pbi1vdXQ7bGVmdDowO3RvcDowO3JpZ2h0OjA7Ym90dG9tOjA7bWFyZ2luOmF1dG99W2lkXj1Ob3RpZmxpeExvYWRpbmdXcmFwXT5kaXZbY2xhc3MqPVwiLWljb25cIl0gaW1nLFtpZF49Tm90aWZsaXhMb2FkaW5nV3JhcF0+ZGl2W2NsYXNzKj1cIi1pY29uXCJdIHN2Z3ttYXgtd2lkdGg6dW5zZXQ7bWF4LWhlaWdodDp1bnNldDt3aWR0aDoxMDAlO2hlaWdodDoxMDAlO3Bvc2l0aW9uOmFic29sdXRlO2xlZnQ6MDt0b3A6MH1baWRePU5vdGlmbGl4TG9hZGluZ1dyYXBdPmRpdltjbGFzcyo9XCItaWNvblwiXS53aXRoLW1lc3NhZ2V7dG9wOi00MnB4fVtpZF49Tm90aWZsaXhMb2FkaW5nV3JhcF0+cHtwb3NpdGlvbjpmaXhlZDtsZWZ0OjA7cmlnaHQ6MDt0b3A6NDJweDtib3R0b206MDttYXJnaW46YXV0bztmb250LWZhbWlseTppbmhlcml0IWltcG9ydGFudDtmb250LXdlaWdodDo1MDA7bGluZS1oZWlnaHQ6MS40O3BhZGRpbmc6MCAxMHB4O3dpZHRoOjEwMCU7Zm9udC1zaXplOjE1cHg7aGVpZ2h0OjE4cHh9W2lkXj1Ob3RpZmxpeExvYWRpbmdXcmFwXS53aXRoLWFuaW1hdGlvbnthbmltYXRpb246bG9hZGluZy1hbmltYXRpb24tZmFkZSAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsOy13ZWJraXQtYW5pbWF0aW9uOmxvYWRpbmctYW5pbWF0aW9uLWZhZGUgLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbH1Aa2V5ZnJhbWVzIGxvYWRpbmctYW5pbWF0aW9uLWZhZGV7MCV7b3BhY2l0eTowfTEwMCV7b3BhY2l0eToxfX1ALXdlYmtpdC1rZXlmcmFtZXMgbG9hZGluZy1hbmltYXRpb24tZmFkZXswJXtvcGFjaXR5OjB9MTAwJXtvcGFjaXR5OjF9fVtpZF49Tm90aWZsaXhMb2FkaW5nV3JhcF0ud2l0aC1hbmltYXRpb24ucmVtb3Zle29wYWNpdHk6MDthbmltYXRpb246bG9hZGluZy1hbmltYXRpb24tZmFkZS1yZW1vdmUgLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbDstd2Via2l0LWFuaW1hdGlvbjpsb2FkaW5nLWFuaW1hdGlvbi1mYWRlLXJlbW92ZSAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsfUBrZXlmcmFtZXMgbG9hZGluZy1hbmltYXRpb24tZmFkZS1yZW1vdmV7MCV7b3BhY2l0eToxfTEwMCV7b3BhY2l0eTowfX1ALXdlYmtpdC1rZXlmcmFtZXMgbG9hZGluZy1hbmltYXRpb24tZmFkZS1yZW1vdmV7MCV7b3BhY2l0eToxfTEwMCV7b3BhY2l0eTowfX1baWRePU5vdGlmbGl4TG9hZGluZ1dyYXBdPnAubmV3e2FuaW1hdGlvbjpsb2FkaW5nLW5ldy1tZXNzYWdlLWZhZGUgLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbDstd2Via2l0LWFuaW1hdGlvbjpsb2FkaW5nLW5ldy1tZXNzYWdlLWZhZGUgLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbH1Aa2V5ZnJhbWVzIGxvYWRpbmctbmV3LW1lc3NhZ2UtZmFkZXswJXtvcGFjaXR5OjB9MTAwJXtvcGFjaXR5OjF9fUAtd2Via2l0LWtleWZyYW1lcyBsb2FkaW5nLW5ldy1tZXNzYWdlLWZhZGV7MCV7b3BhY2l0eTowfTEwMCV7b3BhY2l0eToxfX1baWRePU5vdGlmbGl4Q29uZmlybVdyYXBde3Bvc2l0aW9uOmZpeGVkO3otaW5kZXg6MTAwMzt3aWR0aDoyODBweDttYXgtd2lkdGg6OTglO2xlZnQ6MTBweDtyaWdodDoxMHB4O3RvcDoxMHB4O21hcmdpbjphdXRvO3RleHQtYWxpZ246Y2VudGVyO2JveC1zaXppbmc6Ym9yZGVyLWJveDtiYWNrZ3JvdW5kOjAgMDtmb250LWZhbWlseTpRdWlja3NhbmQsc2Fucy1zZXJpZn1baWRePU5vdGlmbGl4Q29uZmlybVdyYXBdICp7Ym94LXNpemluZzpib3JkZXItYm94fVtpZF49Tm90aWZsaXhDb25maXJtV3JhcF0+ZGl2W2NsYXNzKj1cIi1vdmVybGF5XCJde3dpZHRoOjEwMCU7aGVpZ2h0OjEwMCU7bGVmdDowO3RvcDowO2JhY2tncm91bmQ6cmdiYSgyNTUsMjU1LDI1NSwuNSk7cG9zaXRpb246Zml4ZWQ7ei1pbmRleDowfVtpZF49Tm90aWZsaXhDb25maXJtV3JhcF0+ZGl2W2NsYXNzKj1cIi1jb250ZW50XCJde3dpZHRoOjEwMCU7ZmxvYXQ6bGVmdDtib3JkZXItcmFkaXVzOjI1cHg7cGFkZGluZzoxMHB4O21hcmdpbjowO2ZpbHRlcjpkcm9wLXNoYWRvdygwIDAgNXB4IHJnYmEoMCwwLDAsLjEpKTtiYWNrZ3JvdW5kOiNmOGY4Zjg7Y29sb3I6IzFlMWUxZTtwb3NpdGlvbjpyZWxhdGl2ZTt6LWluZGV4OjF9W2lkXj1Ob3RpZmxpeENvbmZpcm1XcmFwXT5kaXZbY2xhc3MqPVwiLWNvbnRlbnRcIl0+ZGl2W2NsYXNzKj1cIi1oZWFkXCJde2Zsb2F0OmxlZnQ7d2lkdGg6MTAwJX1baWRePU5vdGlmbGl4Q29uZmlybVdyYXBdPmRpdltjbGFzcyo9XCItY29udGVudFwiXT5kaXZbY2xhc3MqPVwiLWhlYWRcIl0+aDV7ZmxvYXQ6bGVmdDt3aWR0aDoxMDAlO21hcmdpbjowO3BhZGRpbmc6MCAwIDEwcHg7Ym9yZGVyLWJvdHRvbToxcHggc29saWQgcmdiYSgwLDAsMCwuMSk7Y29sb3I6IzAwYjQ2Mjtmb250LWZhbWlseTppbmhlcml0IWltcG9ydGFudDtmb250LXNpemU6MTZweDtsaW5lLWhlaWdodDoxLjQ7Zm9udC13ZWlnaHQ6NTAwfVtpZF49Tm90aWZsaXhDb25maXJtV3JhcF0+ZGl2W2NsYXNzKj1cIi1jb250ZW50XCJdPmRpdltjbGFzcyo9XCItaGVhZFwiXT5we2ZvbnQtZmFtaWx5OmluaGVyaXQhaW1wb3J0YW50O21hcmdpbjoxNXB4IDAgMjBweDtwYWRkaW5nOjAgMTBweDtmbG9hdDpsZWZ0O3dpZHRoOjEwMCU7Zm9udC1zaXplOjE0cHg7bGluZS1oZWlnaHQ6MS40O2NvbG9yOmluaGVyaXR9W2lkXj1Ob3RpZmxpeENvbmZpcm1XcmFwXT5kaXZbY2xhc3MqPVwiLWNvbnRlbnRcIl0+ZGl2W2NsYXNzKj1cIi1idXR0b25zXCJdey13ZWJraXQtdXNlci1zZWxlY3Q6bm9uZTstbW96LXVzZXItc2VsZWN0Om5vbmU7LW1zLXVzZXItc2VsZWN0Om5vbmU7dXNlci1zZWxlY3Q6bm9uZTtib3JkZXItcmFkaXVzOmluaGVyaXQ7ZmxvYXQ6bGVmdDt3aWR0aDoxMDAlfVtpZF49Tm90aWZsaXhDb25maXJtV3JhcF0+ZGl2W2NsYXNzKj1cIi1jb250ZW50XCJdPmRpdltjbGFzcyo9XCItYnV0dG9uc1wiXT5he2N1cnNvcjpwb2ludGVyO2ZvbnQtZmFtaWx5OmluaGVyaXQhaW1wb3J0YW50O3RyYW5zaXRpb246YWxsIC4yNXMgZWFzZS1pbi1vdXQ7ZmxvYXQ6bGVmdDt3aWR0aDo0OCU7cGFkZGluZzo5cHggNXB4O2JvcmRlci1yYWRpdXM6aW5oZXJpdCFpbXBvcnRhbnQ7Zm9udC13ZWlnaHQ6NTAwO2ZvbnQtc2l6ZToxNXB4O2xpbmUtaGVpZ2h0OjEuNDtjb2xvcjojZjhmOGY4fVtpZF49Tm90aWZsaXhDb25maXJtV3JhcF0+ZGl2W2NsYXNzKj1cIi1jb250ZW50XCJdPmRpdltjbGFzcyo9XCItYnV0dG9uc1wiXT5hLmNvbmZpcm0tYnV0dG9uLW9re21hcmdpbjowIDIlIDAgMDtiYWNrZ3JvdW5kOiMwMGI0NjJ9W2lkXj1Ob3RpZmxpeENvbmZpcm1XcmFwXT5kaXZbY2xhc3MqPVwiLWNvbnRlbnRcIl0+ZGl2W2NsYXNzKj1cIi1idXR0b25zXCJdPmEuY29uZmlybS1idXR0b24tY2FuY2Vse21hcmdpbjowIDAgMCAyJTtiYWNrZ3JvdW5kOiNhOWE5YTl9W2lkXj1Ob3RpZmxpeENvbmZpcm1XcmFwXT5kaXZbY2xhc3MqPVwiLWNvbnRlbnRcIl0+ZGl2W2NsYXNzKj1cIi1idXR0b25zXCJdPmEuZnVsbHttYXJnaW46MDt3aWR0aDoxMDAlfVtpZF49Tm90aWZsaXhDb25maXJtV3JhcF0+ZGl2W2NsYXNzKj1cIi1jb250ZW50XCJdPmRpdltjbGFzcyo9XCItYnV0dG9uc1wiXT5hOmhvdmVye2JveC1zaGFkb3c6aW5zZXQgMCAtNjBweCA1cHggLTVweCByZ2JhKDAsMCwwLC4yKX1baWRePU5vdGlmbGl4Q29uZmlybVdyYXBdLnJ0bC1vbj5kaXZbY2xhc3MqPVwiLWNvbnRlbnRcIl0+ZGl2W2NsYXNzKj1cIi1idXR0b25zXCJdLFtpZF49Tm90aWZsaXhDb25maXJtV3JhcF0ucnRsLW9uPmRpdltjbGFzcyo9XCItY29udGVudFwiXT5kaXZbY2xhc3MqPVwiLWJ1dHRvbnNcIl0+YXt0cmFuc2Zvcm06cm90YXRlWSgxODBkZWcpfVtpZF49Tm90aWZsaXhDb25maXJtV3JhcF0+ZGl2W2NsYXNzKj1cIi1vdmVybGF5XCJdLndpdGgtYW5pbWF0aW9ue2FuaW1hdGlvbjpjb25maXJtLW92ZXJsYXktYW5pbWF0aW9uIC4zcyBlYXNlLWluLW91dCAwcyBub3JtYWw7LXdlYmtpdC1hbmltYXRpb246Y29uZmlybS1vdmVybGF5LWFuaW1hdGlvbiAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsfUBrZXlmcmFtZXMgY29uZmlybS1vdmVybGF5LWFuaW1hdGlvbnswJXtvcGFjaXR5OjB9MTAwJXtvcGFjaXR5OjF9fUAtd2Via2l0LWtleWZyYW1lcyBjb25maXJtLW92ZXJsYXktYW5pbWF0aW9uezAle29wYWNpdHk6MH0xMDAle29wYWNpdHk6MX19W2lkXj1Ob3RpZmxpeENvbmZpcm1XcmFwXS5yZW1vdmU+ZGl2W2NsYXNzKj1cIi1vdmVybGF5XCJdLndpdGgtYW5pbWF0aW9ue29wYWNpdHk6MDthbmltYXRpb246Y29uZmlybS1vdmVybGF5LWFuaW1hdGlvbi1yZW1vdmUgLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbDstd2Via2l0LWFuaW1hdGlvbjpjb25maXJtLW92ZXJsYXktYW5pbWF0aW9uLXJlbW92ZSAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsfUBrZXlmcmFtZXMgY29uZmlybS1vdmVybGF5LWFuaW1hdGlvbi1yZW1vdmV7MCV7b3BhY2l0eToxfTEwMCV7b3BhY2l0eTowfX1ALXdlYmtpdC1rZXlmcmFtZXMgY29uZmlybS1vdmVybGF5LWFuaW1hdGlvbi1yZW1vdmV7MCV7b3BhY2l0eToxfTEwMCV7b3BhY2l0eTowfX1baWRePU5vdGlmbGl4Q29uZmlybVdyYXBdLndpdGgtYW5pbWF0aW9uLm54LWZhZGU+ZGl2W2NsYXNzKj1cIi1jb250ZW50XCJde2FuaW1hdGlvbjpjb25maXJtLWFuaW1hdGlvbi1mYWRlIC4zcyBlYXNlLWluLW91dCAwcyBub3JtYWw7LXdlYmtpdC1hbmltYXRpb246Y29uZmlybS1hbmltYXRpb24tZmFkZSAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsfUBrZXlmcmFtZXMgY29uZmlybS1hbmltYXRpb24tZmFkZXswJXtvcGFjaXR5OjB9MTAwJXtvcGFjaXR5OjF9fUAtd2Via2l0LWtleWZyYW1lcyBjb25maXJtLWFuaW1hdGlvbi1mYWRlezAle29wYWNpdHk6MH0xMDAle29wYWNpdHk6MX19W2lkXj1Ob3RpZmxpeENvbmZpcm1XcmFwXS53aXRoLWFuaW1hdGlvbi5ueC16b29tPmRpdltjbGFzcyo9XCItY29udGVudFwiXXthbmltYXRpb246Y29uZmlybS1hbmltYXRpb24tem9vbSAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsOy13ZWJraXQtYW5pbWF0aW9uOmNvbmZpcm0tYW5pbWF0aW9uLXpvb20gLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbH1Aa2V5ZnJhbWVzIGNvbmZpcm0tYW5pbWF0aW9uLXpvb217MCV7b3BhY2l0eTowO3RyYW5zZm9ybTpzY2FsZSguNSl9NTAle29wYWNpdHk6MTt0cmFuc2Zvcm06c2NhbGUoMS4wNSl9MTAwJXtvcGFjaXR5OjE7dHJhbnNmb3JtOnNjYWxlKDEpfX1ALXdlYmtpdC1rZXlmcmFtZXMgY29uZmlybS1hbmltYXRpb24tem9vbXswJXtvcGFjaXR5OjA7dHJhbnNmb3JtOnNjYWxlKC41KX01MCV7b3BhY2l0eToxO3RyYW5zZm9ybTpzY2FsZSgxLjA1KX0xMDAle29wYWNpdHk6MTt0cmFuc2Zvcm06c2NhbGUoMSl9fVtpZF49Tm90aWZsaXhDb25maXJtV3JhcF0ud2l0aC1hbmltYXRpb24ubngtZmFkZS5yZW1vdmU+ZGl2W2NsYXNzKj1cIi1jb250ZW50XCJde29wYWNpdHk6MDthbmltYXRpb246Y29uZmlybS1hbmltYXRpb24tZmFkZS1yZW1vdmUgLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbDstd2Via2l0LWFuaW1hdGlvbjpjb25maXJtLWFuaW1hdGlvbi1mYWRlLXJlbW92ZSAuM3MgZWFzZS1pbi1vdXQgMHMgbm9ybWFsfUBrZXlmcmFtZXMgY29uZmlybS1hbmltYXRpb24tZmFkZS1yZW1vdmV7MCV7b3BhY2l0eToxfTEwMCV7b3BhY2l0eTowfX1ALXdlYmtpdC1rZXlmcmFtZXMgY29uZmlybS1hbmltYXRpb24tZmFkZS1yZW1vdmV7MCV7b3BhY2l0eToxfTEwMCV7b3BhY2l0eTowfX1baWRePU5vdGlmbGl4Q29uZmlybVdyYXBdLndpdGgtYW5pbWF0aW9uLm54LXpvb20ucmVtb3ZlPmRpdltjbGFzcyo9XCItY29udGVudFwiXXtvcGFjaXR5OjA7YW5pbWF0aW9uOmNvbmZpcm0tYW5pbWF0aW9uLXpvb20tcmVtb3ZlIC4zcyBlYXNlLWluLW91dCAwcyBub3JtYWw7LXdlYmtpdC1hbmltYXRpb246Y29uZmlybS1hbmltYXRpb24tem9vbS1yZW1vdmUgLjNzIGVhc2UtaW4tb3V0IDBzIG5vcm1hbH1Aa2V5ZnJhbWVzIGNvbmZpcm0tYW5pbWF0aW9uLXpvb20tcmVtb3ZlezAle29wYWNpdHk6MTt0cmFuc2Zvcm06c2NhbGUoMSl9NTAle29wYWNpdHk6LjU7dHJhbnNmb3JtOnNjYWxlKDEuMDUpfTEwMCV7b3BhY2l0eTowO3RyYW5zZm9ybTpzY2FsZSgwKX19QC13ZWJraXQta2V5ZnJhbWVzIGNvbmZpcm0tYW5pbWF0aW9uLXpvb20tcmVtb3ZlezAle29wYWNpdHk6MTt0cmFuc2Zvcm06c2NhbGUoMSl9NTAle29wYWNpdHk6LjU7dHJhbnNmb3JtOnNjYWxlKDEuMDUpfTEwMCV7b3BhY2l0eTowO3RyYW5zZm9ybTpzY2FsZSgwKX19YDtcclxuXHJcbiAgICByZXR1cm4gY3NzO1xyXG59XHJcbi8vIEludGVybmFsIENTUyBDb2RlcyBvZmZcclxuXHJcbi8vIEludGVybmFsIENTUyBGdW5jIG9uXHJcbmNvbnN0IG5vdGlmbGl4SW50ZXJuYWxDU1MgPSAoKSA9PiB7XHJcbiAgICBpZiAoIWRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdOb3RpZmxpeEludGVybmFsQ1NTJykpIHtcclxuICAgICAgICBjb25zdCBpbnRlcm5hbENTUyA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ3N0eWxlJyk7XHJcbiAgICAgICAgaW50ZXJuYWxDU1MuaWQgPSAnTm90aWZsaXhJbnRlcm5hbENTUyc7XHJcbiAgICAgICAgLy8gaW50ZXJuYWxDU1MudHlwZSA9ICd0ZXh0L2Nzcyc7IC8vID0+IG5vdCBuZWNlc3NhcnlcclxuICAgICAgICBpbnRlcm5hbENTUy5pbm5lckhUTUwgPSBub3RpZmxpeEludGVybmFsQ1NTQ29kZXMoKTtcclxuICAgICAgICBkb2N1bWVudC5oZWFkLmFwcGVuZENoaWxkKGludGVybmFsQ1NTKTtcclxuICAgIH1cclxufVxyXG4vLyBJbnRlcm5hbCBDU1MgRnVuYyBvZmZcclxuXHJcbi8vIE5vdGlmbGl4OiBFeHRlbmQgb25cclxuY29uc3QgZXh0ZW5kTm90aWZsaXggPSBmdW5jdGlvbiAoKSB7XHJcbiAgICBsZXQgZXh0ZW5kZWQgPSB7fTtcclxuICAgIGxldCBkZWVwID0gZmFsc2U7XHJcbiAgICBsZXQgaSA9IDA7XHJcbiAgICBpZiAoT2JqZWN0LnByb3RvdHlwZS50b1N0cmluZy5jYWxsKGFyZ3VtZW50c1swXSkgPT09ICdbb2JqZWN0IEJvb2xlYW5dJykge1xyXG4gICAgICAgIGRlZXAgPSBhcmd1bWVudHNbMF07XHJcbiAgICAgICAgaSsrO1xyXG4gICAgfVxyXG4gICAgbGV0IG1lcmdlID0gZnVuY3Rpb24gKG9iaikge1xyXG4gICAgICAgIGZvciAobGV0IHByb3AgaW4gb2JqKSB7XHJcbiAgICAgICAgICAgIGlmIChvYmouaGFzT3duUHJvcGVydHkocHJvcCkpIHtcclxuICAgICAgICAgICAgICAgIC8vIElmIHByb3BlcnR5IGlzIGFuIG9iamVjdCwgbWVyZ2UgcHJvcGVydGllc1xyXG4gICAgICAgICAgICAgICAgaWYgKGRlZXAgJiYgT2JqZWN0LnByb3RvdHlwZS50b1N0cmluZy5jYWxsKG9ialtwcm9wXSkgPT09ICdbb2JqZWN0IE9iamVjdF0nKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgZXh0ZW5kZWRbcHJvcF0gPSBleHRlbmROb3RpZmxpeChleHRlbmRlZFtwcm9wXSwgb2JqW3Byb3BdKTtcclxuICAgICAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgZXh0ZW5kZWRbcHJvcF0gPSBvYmpbcHJvcF07XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcbiAgICB9O1xyXG4gICAgZm9yICg7IGkgPCBhcmd1bWVudHMubGVuZ3RoOyBpKyspIHtcclxuICAgICAgICBtZXJnZShhcmd1bWVudHNbaV0pO1xyXG4gICAgfVxyXG4gICAgcmV0dXJuIGV4dGVuZGVkO1xyXG59O1xyXG4vLyBOb3RpZmxpeDogRXh0ZW5kIG9mZlxyXG5cclxuLy8gTm90aWZsaXg6IFBsYWludGV4dCBvblxyXG5jb25zdCBub3RpZmxpeFBsYWludGV4dCA9IGZ1bmN0aW9uIChodG1sKSB7XHJcbiAgICBjb25zdCBodG1sUG9vbCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xyXG4gICAgaHRtbFBvb2wuaW5uZXJIVE1MID0gaHRtbDtcclxuICAgIHJldHVybiBodG1sUG9vbC50ZXh0Q29udGVudCB8fCBodG1sUG9vbC5pbm5lclRleHQgfHwgJyc7XHJcbn1cclxuLy8gTm90aWZsaXg6IFBsYWludGV4dCBvZmZcclxuXHJcbi8vIE5vdGlmbGl4OiBHb29nbGVGb250IG9uXHJcbmNvbnN0IG5vdGlmbGl4R29vZ2xlRm9udCA9IGZ1bmN0aW9uICgpIHtcclxuICAgIGlmICghZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ05vdGlmbGl4UXVpY2tzYW5kJykpIHtcclxuICAgICAgICAvLyBnb29nbGUgZm9udHMgZG5zIHByZWZldGNoIG9uXHJcbiAgICAgICAgY29uc3QgZG5zID0gJzxsaW5rIGlkPVwiTm90aWZsaXhHb29nbGVETlNcIiByZWw9XCJkbnMtcHJlZmV0Y2hcIiBocmVmPVwiLy9mb250cy5nb29nbGVhcGlzLmNvbVwiPic7XHJcbiAgICAgICAgY29uc3QgZG5zUmFuZ2UgPSBkb2N1bWVudC5jcmVhdGVSYW5nZSgpO1xyXG4gICAgICAgIGRuc1JhbmdlLnNlbGVjdE5vZGUoZG9jdW1lbnQuaGVhZCk7XHJcbiAgICAgICAgY29uc3QgZG5zRnJhZ21lbnQgPSBkbnNSYW5nZS5jcmVhdGVDb250ZXh0dWFsRnJhZ21lbnQoZG5zKTtcclxuICAgICAgICBkb2N1bWVudC5oZWFkLmFwcGVuZENoaWxkKGRuc0ZyYWdtZW50KTtcclxuICAgICAgICAvLyBnb29nbGUgZm9udHMgZG5zIHByZWZldGNoIG9mZlxyXG5cclxuICAgICAgICAvLyBnb29nbGUgZm9udHMgc3R5bGUgb25cclxuICAgICAgICBjb25zdCBmb250ID0gJzxsaW5rIGlkPVwiTm90aWZsaXhRdWlja3NhbmRcIiBocmVmPVwiaHR0cHM6Ly9mb250cy5nb29nbGVhcGlzLmNvbS9jc3M/ZmFtaWx5PVF1aWNrc2FuZDozMDAsNDAwLDUwMCw3MDAmYW1wO3N1YnNldD1sYXRpbi1leHRcIiByZWw9XCJzdHlsZXNoZWV0XCIgLz4nO1xyXG4gICAgICAgIGNvbnN0IGZvbnRSYW5nZSA9IGRvY3VtZW50LmNyZWF0ZVJhbmdlKCk7XHJcbiAgICAgICAgZm9udFJhbmdlLnNlbGVjdE5vZGUoZG9jdW1lbnQuaGVhZCk7XHJcbiAgICAgICAgY29uc3QgZm9udEZyYWdtZW50ID0gZm9udFJhbmdlLmNyZWF0ZUNvbnRleHR1YWxGcmFnbWVudChmb250KTtcclxuICAgICAgICBkb2N1bWVudC5oZWFkLmFwcGVuZENoaWxkKGZvbnRGcmFnbWVudCk7XHJcbiAgICAgICAgLy8gZ29vZ2xlIGZvbnRzIHN0eWxlIG9mZlxyXG4gICAgfVxyXG59XHJcbi8vIE5vdGlmbGl4OiBHb29nbGVGb250IG9mZlxyXG5cclxuLy8gTm90aWZsaXg6IENvbnNvbGUgRXJyb3Igb25cclxuY29uc3Qgbm90aWZsaXhDb25zb2xlRXJyb3IgPSBmdW5jdGlvbiAodGl0bGUsIG1lc3NhZ2UpIHtcclxuICAgIHJldHVybiBjb25zb2xlLmVycm9yKCclYyAnICsgdGl0bGUgKyAnICcsICdwYWRkaW5nOjJweDtib3JkZXItcmFkaXVzOjIwcHg7Y29sb3I6I2ZmZjtiYWNrZ3JvdW5kOiNmNDQzMzYnLCAnXFxuJyArIG1lc3NhZ2UgKyAnXFxuVmlzaXQgZG9jdW1lbnRhdGlvbiBwYWdlIHRvIGxlYXJuIG1vcmU6IGh0dHBzOi8vd3d3Lm5vdGlmbGl4LmNvbS9kb2N1bWVudGF0aW9uJyk7XHJcbn1cclxuLy8gTm90aWZsaXg6IENvbnNvbGUgRXJyb3Igb2ZmXHJcblxyXG5cclxuLy8gTm90aWZsaXg6IE5vdGlmeSBEZWZhdWx0IFNldHRpbmdzIG9uXHJcbmxldCBub3RpZnlTZXR0aW5ncyA9IHtcclxuICAgIHdyYXBJRDogJ05vdGlmbGl4Tm90aWZ5V3JhcCcsIC8vIGNhbiBub3QgY3VzdG9taXphYmxlXHJcbiAgICB3aWR0aDogJzI4MHB4JyxcclxuICAgIHBvc2l0aW9uOiAncmlnaHQtdG9wJywgLy8gJ3JpZ2h0LXRvcCcgLSAncmlnaHQtYm90dG9tJyAtICdsZWZ0LXRvcCcgLSAnbGVmdC1ib3R0b20nXHJcbiAgICBkaXN0YW5jZTogJzEwcHgnLFxyXG4gICAgb3BhY2l0eTogMSxcclxuICAgIGJvcmRlclJhZGl1czogJzVweCcsXHJcbiAgICBydGw6IGZhbHNlLFxyXG4gICAgdGltZW91dDogMzAwMCxcclxuICAgIG1lc3NhZ2VNYXhMZW5ndGg6IDExMCxcclxuICAgIGJhY2tPdmVybGF5OiBmYWxzZSxcclxuICAgIGJhY2tPdmVybGF5Q29sb3I6ICdyZ2JhKDAsMCwwLDAuNSknLFxyXG5cclxuICAgIElEOiAnTm90aWZsaXhOb3RpZnknLFxyXG4gICAgY2xhc3NOYW1lOiAnbm90aWZsaXgtbm90aWZ5JyxcclxuICAgIHppbmRleDogNDAwMSxcclxuICAgIHVzZUdvb2dsZUZvbnQ6IHRydWUsXHJcbiAgICBmb250RmFtaWx5OiAnUXVpY2tzYW5kJyxcclxuICAgIGZvbnRTaXplOiAnMTNweCcsXHJcbiAgICBjc3NBbmltYXRpb246IHRydWUsXHJcbiAgICBjc3NBbmltYXRpb25EdXJhdGlvbjogNDAwLFxyXG4gICAgY3NzQW5pbWF0aW9uU3R5bGU6ICdmYWRlJywgLy8gJ2ZhZGUnIC0gJ3pvb20nIC0gJ2Zyb20tcmlnaHQnIC0gJ2Zyb20tdG9wJyAtICdmcm9tLWJvdHRvbScgLSAnZnJvbS1sZWZ0J1xyXG4gICAgY2xvc2VCdXR0b246IGZhbHNlLFxyXG4gICAgdXNlSWNvbjogdHJ1ZSxcclxuICAgIHVzZUZvbnRBd2Vzb21lOiBmYWxzZSxcclxuICAgIGZvbnRBd2Vzb21lSWNvblN0eWxlOiAnYmFzaWMnLCAvLyAnYmFzaWMnIC0gJ3NoYWRvdydcclxuICAgIGZvbnRBd2Vzb21lSWNvblNpemU6ICczNHB4JyxcclxuXHJcbiAgICBwbGFpblRleHQ6IHRydWUsXHJcblxyXG4gICAgc3VjY2Vzczoge1xyXG4gICAgICAgIGJhY2tncm91bmQ6ICcjMDBiNDYyJyxcclxuICAgICAgICB0ZXh0Q29sb3I6ICcjZmZmJyxcclxuICAgICAgICBjaGlsZENsYXNzTmFtZTogJ3N1Y2Nlc3MnLFxyXG4gICAgICAgIG5vdGlmbGl4SWNvbkNvbG9yOiAncmdiYSgwLCAwLCAwLCAwLjI1KScsXHJcbiAgICAgICAgZm9udEF3ZXNvbWVDbGFzc05hbWU6ICdmYXMgZmEtY2hlY2stY2lyY2xlJyxcclxuICAgICAgICBmb250QXdlc29tZUljb25Db2xvcjogJ3JnYmEoMCwgMCwgMCwgMC4yKScsXHJcbiAgICB9LFxyXG5cclxuICAgIGZhaWx1cmU6IHtcclxuICAgICAgICBiYWNrZ3JvdW5kOiAnI2Y0NDMzNicsXHJcbiAgICAgICAgdGV4dENvbG9yOiAnI2ZmZicsXHJcbiAgICAgICAgY2hpbGRDbGFzc05hbWU6ICdmYWlsdXJlJyxcclxuICAgICAgICBub3RpZmxpeEljb25Db2xvcjogJ3JnYmEoMCwgMCwgMCwgMC4yKScsXHJcbiAgICAgICAgZm9udEF3ZXNvbWVDbGFzc05hbWU6ICdmYXMgZmEtdGltZXMtY2lyY2xlJyxcclxuICAgICAgICBmb250QXdlc29tZUljb25Db2xvcjogJ3JnYmEoMCwgMCwgMCwgMC4yKScsXHJcbiAgICB9LFxyXG5cclxuICAgIHdhcm5pbmc6IHtcclxuICAgICAgICBiYWNrZ3JvdW5kOiAnI2YyYmQxZCcsXHJcbiAgICAgICAgdGV4dENvbG9yOiAnI2ZmZicsXHJcbiAgICAgICAgY2hpbGRDbGFzc05hbWU6ICd3YXJuaW5nJyxcclxuICAgICAgICBub3RpZmxpeEljb25Db2xvcjogJ3JnYmEoMCwgMCwgMCwgMC4yKScsXHJcbiAgICAgICAgZm9udEF3ZXNvbWVDbGFzc05hbWU6ICdmYXMgZmEtZXhjbGFtYXRpb24tY2lyY2xlJyxcclxuICAgICAgICBmb250QXdlc29tZUljb25Db2xvcjogJ3JnYmEoMCwgMCwgMCwgMC4yKScsXHJcbiAgICB9LFxyXG5cclxuICAgIGluZm86IHtcclxuICAgICAgICBiYWNrZ3JvdW5kOiAnIzAwYmNkNCcsXHJcbiAgICAgICAgdGV4dENvbG9yOiAnI2ZmZicsXHJcbiAgICAgICAgY2hpbGRDbGFzc05hbWU6ICdpbmZvJyxcclxuICAgICAgICBub3RpZmxpeEljb25Db2xvcjogJ3JnYmEoMCwgMCwgMCwgMC4yKScsXHJcbiAgICAgICAgZm9udEF3ZXNvbWVDbGFzc05hbWU6ICdmYXMgZmEtaW5mby1jaXJjbGUnLFxyXG4gICAgICAgIGZvbnRBd2Vzb21lSWNvbkNvbG9yOiAncmdiYSgwLCAwLCAwLCAwLjIpJyxcclxuICAgIH0sXHJcbn07XHJcbi8vIE5vdGlmbGl4OiBOb3RpZnkgRGVmYXVsdCBTZXR0aW5ncyBvZmZcclxuXHJcbi8vIE5vdGlmbGl4OiBSZXBvcnQgRGVmYXVsdCBTZXR0aW5ncyBvblxyXG5sZXQgcmVwb3J0U2V0dGluZ3MgPSB7XHJcbiAgICBJRDogJ05vdGlmbGl4UmVwb3J0V3JhcCcsIC8vIGNhbiBub3QgY3VzdG9taXphYmxlXHJcbiAgICBjbGFzc05hbWU6ICdub3RpZmxpeC1yZXBvcnQnLFxyXG4gICAgd2lkdGg6ICczMjBweCcsXHJcbiAgICBiYWNrZ3JvdW5kQ29sb3I6ICcjZjhmOGY4JyxcclxuICAgIGJvcmRlclJhZGl1czogJzI1cHgnLFxyXG4gICAgcnRsOiBmYWxzZSxcclxuICAgIHppbmRleDogNDAwMixcclxuICAgIGJhY2tPdmVybGF5OiB0cnVlLFxyXG4gICAgYmFja092ZXJsYXlDb2xvcjogJ3JnYmEoMCwgMCwgMCwgMC41KScsXHJcbiAgICB1c2VHb29nbGVGb250OiB0cnVlLFxyXG4gICAgZm9udEZhbWlseTogJ1F1aWNrc2FuZCcsXHJcbiAgICBzdmdTaXplOiAnMTEwcHgnLFxyXG4gICAgcGxhaW5UZXh0OiB0cnVlLFxyXG4gICAgdGl0bGVGb250U2l6ZTogJzE2cHgnLFxyXG4gICAgdGl0bGVNYXhMZW5ndGg6IDM0LFxyXG4gICAgbWVzc2FnZUZvbnRTaXplOiAnMTNweCcsXHJcbiAgICBtZXNzYWdlTWF4TGVuZ3RoOiA0MDAsXHJcbiAgICBidXR0b25Gb250U2l6ZTogJzE0cHgnLFxyXG4gICAgYnV0dG9uTWF4TGVuZ3RoOiAzNCxcclxuICAgIGNzc0FuaW1hdGlvbjogdHJ1ZSxcclxuICAgIGNzc0FuaW1hdGlvbkR1cmF0aW9uOiAzNjAsXHJcbiAgICBjc3NBbmltYXRpb25TdHlsZTogJ2ZhZGUnLCAvLyAnZmFkZScgLSAnem9vbSdcclxuXHJcbiAgICBzdWNjZXNzOiB7XHJcbiAgICAgICAgc3ZnQ29sb3I6ICcjMDBiNDYyJyxcclxuICAgICAgICB0aXRsZUNvbG9yOiAnIzFlMWUxZScsXHJcbiAgICAgICAgbWVzc2FnZUNvbG9yOiAnIzI0MjQyNCcsXHJcbiAgICAgICAgYnV0dG9uQmFja2dyb3VuZDogJyMwMGI0NjInLFxyXG4gICAgICAgIGJ1dHRvbkNvbG9yOiAnI2ZmZicsXHJcbiAgICB9LFxyXG5cclxuICAgIGZhaWx1cmU6IHtcclxuICAgICAgICBzdmdDb2xvcjogJyNmNDQzMzYnLFxyXG4gICAgICAgIHRpdGxlQ29sb3I6ICcjMWUxZTFlJyxcclxuICAgICAgICBtZXNzYWdlQ29sb3I6ICcjMjQyNDI0JyxcclxuICAgICAgICBidXR0b25CYWNrZ3JvdW5kOiAnI2Y0NDMzNicsXHJcbiAgICAgICAgYnV0dG9uQ29sb3I6ICcjZmZmJyxcclxuICAgIH0sXHJcblxyXG4gICAgd2FybmluZzoge1xyXG4gICAgICAgIHN2Z0NvbG9yOiAnI2YyYmQxZCcsXHJcbiAgICAgICAgdGl0bGVDb2xvcjogJyMxZTFlMWUnLFxyXG4gICAgICAgIG1lc3NhZ2VDb2xvcjogJyMyNDI0MjQnLFxyXG4gICAgICAgIGJ1dHRvbkJhY2tncm91bmQ6ICcjZjJiZDFkJyxcclxuICAgICAgICBidXR0b25Db2xvcjogJyNmZmYnLFxyXG4gICAgfSxcclxuXHJcbiAgICBpbmZvOiB7XHJcbiAgICAgICAgc3ZnQ29sb3I6ICcjMDBiY2Q0JyxcclxuICAgICAgICB0aXRsZUNvbG9yOiAnIzFlMWUxZScsXHJcbiAgICAgICAgbWVzc2FnZUNvbG9yOiAnIzI0MjQyNCcsXHJcbiAgICAgICAgYnV0dG9uQmFja2dyb3VuZDogJyMwMGJjZDQnLFxyXG4gICAgICAgIGJ1dHRvbkNvbG9yOiAnI2ZmZicsXHJcbiAgICB9LFxyXG5cclxufTtcclxuLy8gTm90aWZsaXg6IFJlcG9ydCBEZWZhdWx0IFNldHRpbmdzIG9mZlxyXG5cclxuLy8gTm90aWZsaXg6IENvbmZpcm0gRGVmYXVsdCBTZXR0aW5ncyBvblxyXG5sZXQgY29uZmlybVNldHRpbmdzID0ge1xyXG4gICAgSUQ6ICdOb3RpZmxpeENvbmZpcm1XcmFwJywgLy8gY2FuIG5vdCBjdXN0b21pemFibGVcclxuICAgIGNsYXNzTmFtZTogJ25vdGlmbGl4LWNvbmZpcm0nLFxyXG4gICAgd2lkdGg6ICcyODBweCcsXHJcbiAgICB6aW5kZXg6IDQwMDMsXHJcbiAgICBwb3NpdGlvbjogJ2NlbnRlcicsIC8vICdjZW50ZXInIC0gJ2NlbnRlci10b3AnIC0gICdyaWdodC10b3AnIC0gJ3JpZ2h0LWJvdHRvbScgLSAnbGVmdC10b3AnIC0gJ2xlZnQtYm90dG9tJ1xyXG4gICAgZGlzdGFuY2U6ICcxMHB4JyxcclxuICAgIGJhY2tncm91bmRDb2xvcjogJyNmOGY4ZjgnLFxyXG4gICAgYm9yZGVyUmFkaXVzOiAnMjVweCcsXHJcbiAgICBiYWNrT3ZlcmxheTogdHJ1ZSxcclxuICAgIGJhY2tPdmVybGF5Q29sb3I6ICdyZ2JhKDAsMCwwLDAuNSknLFxyXG4gICAgcnRsOiBmYWxzZSxcclxuICAgIHVzZUdvb2dsZUZvbnQ6IHRydWUsXHJcbiAgICBmb250RmFtaWx5OiAnUXVpY2tzYW5kJyxcclxuICAgIGNzc0FuaW1hdGlvbjogdHJ1ZSxcclxuICAgIGNzc0FuaW1hdGlvblN0eWxlOiAnZmFkZScsIC8vICd6b29tJyAtICdmYWRlJ1xyXG4gICAgY3NzQW5pbWF0aW9uRHVyYXRpb246IDMwMCxcclxuXHJcbiAgICB0aXRsZUNvbG9yOiAnIzAwYjQ2MicsXHJcbiAgICB0aXRsZUZvbnRTaXplOiAnMTZweCcsXHJcbiAgICB0aXRsZU1heExlbmd0aDogMzQsXHJcblxyXG4gICAgbWVzc2FnZUNvbG9yOiAnIzFlMWUxZScsXHJcbiAgICBtZXNzYWdlRm9udFNpemU6ICcxNHB4JyxcclxuICAgIG1lc3NhZ2VNYXhMZW5ndGg6IDExMCxcclxuXHJcbiAgICBidXR0b25zRm9udFNpemU6ICcxNXB4JyxcclxuICAgIGJ1dHRvbnNNYXhMZW5ndGg6IDM0LFxyXG4gICAgb2tCdXR0b25Db2xvcjogJyNmOGY4ZjgnLFxyXG4gICAgb2tCdXR0b25CYWNrZ3JvdW5kOiAnIzAwYjQ2MicsXHJcbiAgICBjYW5jZWxCdXR0b25Db2xvcjogJyNmOGY4ZjgnLFxyXG4gICAgY2FuY2VsQnV0dG9uQmFja2dyb3VuZDogJyNhOWE5YTknLFxyXG5cclxuICAgIHBsYWluVGV4dDogdHJ1ZSxcclxufVxyXG4vLyBOb3RpZmxpeDogQ29uZmlybSBEZWZhdWx0IFNldHRpbmdzIG9mZlxyXG5cclxuLy8gTm90aWZsaXg6IExvYWRpbmcgRGVmYXVsdCBTZXR0aW5ncyBvblxyXG5sZXQgbG9hZGluZ1NldHRpbmdzID0ge1xyXG4gICAgSUQ6ICdOb3RpZmxpeExvYWRpbmdXcmFwJywgLy8gY2FuIG5vdCBjdXN0b21pemFibGVcclxuICAgIGNsYXNzTmFtZTogJ25vdGlmbGl4LWxvYWRpbmcnLFxyXG4gICAgemluZGV4OiA0MDAwLFxyXG4gICAgYmFja2dyb3VuZENvbG9yOiAncmdiYSgwLDAsMCwwLjgpJyxcclxuICAgIHJ0bDogZmFsc2UsXHJcbiAgICB1c2VHb29nbGVGb250OiB0cnVlLFxyXG4gICAgZm9udEZhbWlseTogJ1F1aWNrc2FuZCcsXHJcbiAgICBjc3NBbmltYXRpb246IHRydWUsXHJcbiAgICBjc3NBbmltYXRpb25EdXJhdGlvbjogNDAwLFxyXG4gICAgY2xpY2tUb0Nsb3NlOiBmYWxzZSxcclxuICAgIGN1c3RvbVN2Z1VybDogbnVsbCxcclxuICAgIHN2Z1NpemU6ICc4MHB4JyxcclxuICAgIHN2Z0NvbG9yOiAnIzAwYjQ2MicsXHJcbiAgICBtZXNzYWdlSUQ6ICdOb3RpZmxpeExvYWRpbmdNZXNzYWdlJyxcclxuICAgIG1lc3NhZ2VGb250U2l6ZTogJzE1cHgnLFxyXG4gICAgbWVzc2FnZU1heExlbmd0aDogMzQsXHJcbiAgICBtZXNzYWdlQ29sb3I6ICcjZGNkY2RjJyxcclxufTtcclxuLy8gTm90aWZsaXg6IExvYWRpbmcgRGVmYXVsdCBTZXR0aW5ncyBvZmZcclxuXHJcbi8vIE5vdGlmbGl4OiBOWCBSZWFjdCBvblxyXG5sZXQgbmV3Tm90aWZ5U2V0dGluZ3M7XHJcbmxldCBuZXdSZXBvcnRTZXR0aW5ncztcclxubGV0IG5ld0NvbmZpcm1TZXR0aW5ncztcclxubGV0IG5ld0xvYWRpbmdTZXR0aW5ncztcclxuY29uc3QgTm90aWZsaXggPSB7XHJcblxyXG4gICAgLy8gTm90aWZ5IG9uXHJcbiAgICBOb3RpZnk6IHtcclxuXHJcbiAgICAgICAgLy8gSW5pdFxyXG4gICAgICAgIEluaXQ6IGZ1bmN0aW9uICh1c2VyTm90aWZ5T3B0KSB7XHJcbiAgICAgICAgICAgIG5ld05vdGlmeVNldHRpbmdzID0gZXh0ZW5kTm90aWZsaXgodHJ1ZSwgbm90aWZ5U2V0dGluZ3MsIHVzZXJOb3RpZnlPcHQpO1xyXG5cclxuICAgICAgICAgICAgLy8gdXNlIEdvb2dsZUZvbnRzIGlmIFwiUXVpY2tzYW5kXCIgb25cclxuICAgICAgICAgICAgaWYgKG5ld05vdGlmeVNldHRpbmdzLnVzZUdvb2dsZUZvbnQgJiYgbmV3Tm90aWZ5U2V0dGluZ3MuZm9udEZhbWlseSA9PT0gJ1F1aWNrc2FuZCcpIHtcclxuICAgICAgICAgICAgICAgIG5vdGlmbGl4R29vZ2xlRm9udCgpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIC8vIHVzZSBHb29nbGVGb250cyBpZiBcIlF1aWNrc2FuZFwiIG9mZlxyXG5cclxuICAgICAgICAgICAgLy8gYWRkIGNzcyBjb2RlcyBvblxyXG4gICAgICAgICAgICBub3RpZmxpeEludGVybmFsQ1NTKCk7XHJcbiAgICAgICAgICAgIC8vIGFkZCBjc3MgY29kZXMgb2ZmXHJcbiAgICAgICAgfSxcclxuXHJcbiAgICAgICAgLy8gTWVyZ2UgRmlyc3QgSW5pdFxyXG4gICAgICAgIE1lcmdlOiBmdW5jdGlvbiAodXNlck5vdGlmeUV4dGVuZCkge1xyXG5cclxuICAgICAgICAgICAgaWYgKG5ld05vdGlmeVNldHRpbmdzKSB7IC8vIGlmIGluaXRpYWxpemVkIGFscmVhZHlcclxuICAgICAgICAgICAgICAgIG5ld05vdGlmeVNldHRpbmdzID0gZXh0ZW5kTm90aWZsaXgodHJ1ZSwgbmV3Tm90aWZ5U2V0dGluZ3MsIHVzZXJOb3RpZnlFeHRlbmQpO1xyXG4gICAgICAgICAgICB9IGVsc2UgeyAvLyBlcnJvclxyXG4gICAgICAgICAgICAgICAgbm90aWZsaXhDb25zb2xlRXJyb3IoJ05vdGlmbGl4IEVycm9yJywgJ1lvdSBoYXZlIHRvIGluaXRpYWxpemUgdGhlIE5vdGlmeSBtb2R1bGUgYmVmb3JlIGNhbGwgTWVyZ2UgZnVuY3Rpb24uJyk7XHJcbiAgICAgICAgICAgICAgICByZXR1cm47XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9LFxyXG5cclxuICAgICAgICAvLyBEaXNwbGF5IE5vdGlmaWNhdGlvbjogU3VjY2Vzc1xyXG4gICAgICAgIFN1Y2Nlc3M6IGZ1bmN0aW9uIChtZXNzYWdlLCBjYWxsYmFjaykge1xyXG5cclxuICAgICAgICAgICAgLy8gaWYgbm90IGluaXRpYWxpemVkIHByZXRlbmQgbGlrZSBpbml0XHJcbiAgICAgICAgICAgIGlmICghbmV3Tm90aWZ5U2V0dGluZ3MpIHtcclxuICAgICAgICAgICAgICAgIE5vdGlmbGl4Lk5vdGlmeS5Jbml0KHt9KTtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgaWYgKCFjYWxsYmFjaykge1xyXG4gICAgICAgICAgICAgICAgY2FsbGJhY2sgPSB1bmRlZmluZWQ7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGNvbnN0IHRoZVR5cGUgPSBuZXdOb3RpZnlTZXR0aW5ncy5zdWNjZXNzO1xyXG4gICAgICAgICAgICBOb3RpZmxpeE5vdGlmeShtZXNzYWdlLCBjYWxsYmFjaywgdGhlVHlwZSwgJ1N1Y2Nlc3MnKTtcclxuICAgICAgICB9LFxyXG5cclxuICAgICAgICAvLyBEaXNwbGF5IE5vdGlmaWNhdGlvbjogRmFpbHVyZVxyXG4gICAgICAgIEZhaWx1cmU6IGZ1bmN0aW9uIChtZXNzYWdlLCBjYWxsYmFjaykge1xyXG5cclxuICAgICAgICAgICAgLy8gaWYgbm90IGluaXRpYWxpemVkIHByZXRlbmQgbGlrZSBpbml0XHJcbiAgICAgICAgICAgIGlmICghbmV3Tm90aWZ5U2V0dGluZ3MpIHtcclxuICAgICAgICAgICAgICAgIE5vdGlmbGl4Lk5vdGlmeS5Jbml0KHt9KTtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgaWYgKCFjYWxsYmFjaykge1xyXG4gICAgICAgICAgICAgICAgY2FsbGJhY2sgPSB1bmRlZmluZWQ7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGNvbnN0IHRoZVR5cGUgPSBuZXdOb3RpZnlTZXR0aW5ncy5mYWlsdXJlO1xyXG4gICAgICAgICAgICBOb3RpZmxpeE5vdGlmeShtZXNzYWdlLCBjYWxsYmFjaywgdGhlVHlwZSwgJ0ZhaWx1cmUnKTtcclxuXHJcbiAgICAgICAgfSxcclxuXHJcbiAgICAgICAgLy8gRGlzcGxheSBOb3RpZmljYXRpb246IFdhcm5pbmdcclxuICAgICAgICBXYXJuaW5nOiBmdW5jdGlvbiAobWVzc2FnZSwgY2FsbGJhY2spIHtcclxuXHJcbiAgICAgICAgICAgIC8vIGlmIG5vdCBpbml0aWFsaXplZCBwcmV0ZW5kIGxpa2UgaW5pdFxyXG4gICAgICAgICAgICBpZiAoIW5ld05vdGlmeVNldHRpbmdzKSB7XHJcbiAgICAgICAgICAgICAgICBOb3RpZmxpeC5Ob3RpZnkuSW5pdCh7fSk7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmICghY2FsbGJhY2spIHtcclxuICAgICAgICAgICAgICAgIGNhbGxiYWNrID0gdW5kZWZpbmVkO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBjb25zdCB0aGVUeXBlID0gbmV3Tm90aWZ5U2V0dGluZ3Mud2FybmluZztcclxuICAgICAgICAgICAgTm90aWZsaXhOb3RpZnkobWVzc2FnZSwgY2FsbGJhY2ssIHRoZVR5cGUsICdXYXJuaW5nJyk7XHJcblxyXG4gICAgICAgIH0sXHJcblxyXG4gICAgICAgIC8vIERpc3BsYXkgTm90aWZpY2F0aW9uOiBJbmZvXHJcbiAgICAgICAgSW5mbzogZnVuY3Rpb24gKG1lc3NhZ2UsIGNhbGxiYWNrKSB7XHJcblxyXG4gICAgICAgICAgICAvLyBpZiBub3QgaW5pdGlhbGl6ZWQgcHJldGVuZCBsaWtlIGluaXRcclxuICAgICAgICAgICAgaWYgKCFuZXdOb3RpZnlTZXR0aW5ncykge1xyXG4gICAgICAgICAgICAgICAgTm90aWZsaXguTm90aWZ5LkluaXQoe30pO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBpZiAoIWNhbGxiYWNrKSB7XHJcbiAgICAgICAgICAgICAgICBjYWxsYmFjayA9IHVuZGVmaW5lZDtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgY29uc3QgdGhlVHlwZSA9IG5ld05vdGlmeVNldHRpbmdzLmluZm87XHJcbiAgICAgICAgICAgIE5vdGlmbGl4Tm90aWZ5KG1lc3NhZ2UsIGNhbGxiYWNrLCB0aGVUeXBlLCAnSW5mbycpO1xyXG5cclxuICAgICAgICB9LFxyXG5cclxuICAgIH0sXHJcbiAgICAvLyBOb3RpZnkgb2ZmXHJcblxyXG4gICAgLy8gUmVwb3J0IG9uXHJcbiAgICBSZXBvcnQ6IHtcclxuXHJcbiAgICAgICAgLy8gSW5pdFxyXG4gICAgICAgIEluaXQ6IGZ1bmN0aW9uICh1c2VyUmVwb3J0T3B0KSB7XHJcbiAgICAgICAgICAgIG5ld1JlcG9ydFNldHRpbmdzID0gZXh0ZW5kTm90aWZsaXgodHJ1ZSwgcmVwb3J0U2V0dGluZ3MsIHVzZXJSZXBvcnRPcHQpO1xyXG5cclxuICAgICAgICAgICAgLy8gdXNlIEdvb2dsZUZvbnRzIGlmIFwiUXVpY2tzYW5kXCIgb25cclxuICAgICAgICAgICAgaWYgKG5ld1JlcG9ydFNldHRpbmdzLnVzZUdvb2dsZUZvbnQgJiYgbmV3UmVwb3J0U2V0dGluZ3MuZm9udEZhbWlseSA9PT0gJ1F1aWNrc2FuZCcpIHtcclxuICAgICAgICAgICAgICAgIG5vdGlmbGl4R29vZ2xlRm9udCgpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIC8vIHVzZSBHb29nbGVGb250cyBpZiBcIlF1aWNrc2FuZFwiIG9mZlxyXG5cclxuICAgICAgICAgICAgLy8gYWRkIGNzcyBjb2RlcyBvblxyXG4gICAgICAgICAgICBub3RpZmxpeEludGVybmFsQ1NTKCk7XHJcbiAgICAgICAgICAgIC8vIGFkZCBjc3MgY29kZXMgb2ZmXHJcbiAgICAgICAgfSxcclxuXHJcbiAgICAgICAgLy8gTWVyZ2UgRmlyc3QgSW5pdFxyXG4gICAgICAgIE1lcmdlOiBmdW5jdGlvbiAodXNlclJlcG9ydEV4dGVuZCkge1xyXG5cclxuICAgICAgICAgICAgaWYgKG5ld1JlcG9ydFNldHRpbmdzKSB7IC8vIGlmIGluaXRpYWxpemVkIGFscmVhZHlcclxuICAgICAgICAgICAgICAgIG5ld1JlcG9ydFNldHRpbmdzID0gZXh0ZW5kTm90aWZsaXgodHJ1ZSwgbmV3UmVwb3J0U2V0dGluZ3MsIHVzZXJSZXBvcnRFeHRlbmQpO1xyXG4gICAgICAgICAgICB9IGVsc2UgeyAvLyBlcnJvclxyXG4gICAgICAgICAgICAgICAgbm90aWZsaXhDb25zb2xlRXJyb3IoJ05vdGlmbGl4IEVycm9yJywgJ1lvdSBoYXZlIHRvIGluaXRpYWxpemUgdGhlIFJlcG9ydCBtb2R1bGUgYmVmb3JlIGNhbGwgTWVyZ2UgZnVuY3Rpb24uJyk7XHJcbiAgICAgICAgICAgICAgICByZXR1cm47XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9LFxyXG5cclxuICAgICAgICAvLyBEaXNwbGF5IFJlcG9ydDogU3VjY2Vzc1xyXG4gICAgICAgIFN1Y2Nlc3M6IGZ1bmN0aW9uICh0aXRsZSwgbWVzc2FnZSwgYnV0dG9uVGV4dCwgYnV0dG9uQ2FsbGJhY2spIHtcclxuXHJcbiAgICAgICAgICAgIC8vIGlmIG5vdCBpbml0aWFsaXplZCBwcmV0ZW5kIGxpa2UgaW5pdFxyXG4gICAgICAgICAgICBpZiAoIW5ld1JlcG9ydFNldHRpbmdzKSB7XHJcbiAgICAgICAgICAgICAgICBOb3RpZmxpeC5SZXBvcnQuSW5pdCh7fSk7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmIChhcmd1bWVudHMubGVuZ3RoID4gNCkgeyAvLyBNb3JlIHBhcmFtZXRlcnMgdGhhbiBhbGxvd2VkXHJcbiAgICAgICAgICAgICAgICBub3RpZmxpeENvbnNvbGVFcnJvcignTm90aWZsaXggRXJyb3InLCAnTW9yZSBwYXJhbWV0ZXJzIHRoYW4gYWxsb3dlZC4nKTtcclxuICAgICAgICAgICAgICAgIHJldHVybjtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgaWYgKGFyZ3VtZW50c1swXSA9PT0gdW5kZWZpbmVkIHx8IGFyZ3VtZW50c1swXS5sZW5ndGggPD0gMCkgeyAvLyB0aXRsZVxyXG4gICAgICAgICAgICAgICAgYXJndW1lbnRzWzBdID0gJ05vdGlmbGl4IFN1Y2Nlc3MnO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBpZiAoYXJndW1lbnRzWzFdID09PSB1bmRlZmluZWQgfHwgYXJndW1lbnRzWzFdLmxlbmd0aCA8PSAwKSB7IC8vIG1lc3NhZ2VcclxuICAgICAgICAgICAgICAgIGFyZ3VtZW50c1sxXSA9IGBcIkRvIG5vdCB0cnkgdG8gYmVjb21lIGEgcGVyc29uIG9mIHN1Y2Nlc3MgYnV0IHRyeSB0byBiZWNvbWUgYSBwZXJzb24gb2YgdmFsdWUuXCIgPGJyPjxicj4tIEFsYmVydCBFaW5zdGVpbmA7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmIChhcmd1bWVudHNbMl0gPT09IHVuZGVmaW5lZCB8fCBhcmd1bWVudHNbMl0ubGVuZ3RoIDw9IDApIHsgLy8gYnV0dG9uVGV4dFxyXG4gICAgICAgICAgICAgICAgYXJndW1lbnRzWzJdID0gJ09rYXknO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBpZiAoYXJndW1lbnRzWzNdID09PSB1bmRlZmluZWQpIHsgLy8gYnV0dG9uQ2FsbGJhY2tcclxuICAgICAgICAgICAgICAgIGFyZ3VtZW50c1szXSA9IHVuZGVmaW5lZDtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgY29uc3QgdGhlVHlwZSA9IG5ld1JlcG9ydFNldHRpbmdzLnN1Y2Nlc3M7XHJcbiAgICAgICAgICAgIE5vdGlmbGl4UmVwb3J0KGFyZ3VtZW50c1swXSwgYXJndW1lbnRzWzFdLCBhcmd1bWVudHNbMl0sIGFyZ3VtZW50c1szXSwgdGhlVHlwZSwgJ3N1Y2Nlc3MnKTtcclxuICAgICAgICB9LFxyXG5cclxuICAgICAgICAvLyBEaXNwbGF5IFJlcG9ydDogRmFpbHVyZVxyXG4gICAgICAgIEZhaWx1cmU6IGZ1bmN0aW9uICh0aXRsZSwgbWVzc2FnZSwgYnV0dG9uVGV4dCwgYnV0dG9uQ2FsbGJhY2spIHtcclxuXHJcbiAgICAgICAgICAgIC8vIGlmIG5vdCBpbml0aWFsaXplZCBwcmV0ZW5kIGxpa2UgaW5pdFxyXG4gICAgICAgICAgICBpZiAoIW5ld1JlcG9ydFNldHRpbmdzKSB7XHJcbiAgICAgICAgICAgICAgICBOb3RpZmxpeC5SZXBvcnQuSW5pdCh7fSk7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmIChhcmd1bWVudHMubGVuZ3RoID4gNCkgeyAvLyBNb3JlIHBhcmFtZXRlcnMgdGhhbiBhbGxvd2VkXHJcbiAgICAgICAgICAgICAgICBub3RpZmxpeENvbnNvbGVFcnJvcignTm90aWZsaXggRXJyb3InLCAnTW9yZSBwYXJhbWV0ZXJzIHRoYW4gYWxsb3dlZC4nKTtcclxuICAgICAgICAgICAgICAgIHJldHVybjtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgaWYgKGFyZ3VtZW50c1swXSA9PT0gdW5kZWZpbmVkIHx8IGFyZ3VtZW50c1swXS5sZW5ndGggPD0gMCkgeyAvLyB0aXRsZVxyXG4gICAgICAgICAgICAgICAgYXJndW1lbnRzWzBdID0gJ05vdGlmbGl4IEZhaWx1cmUnO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBpZiAoYXJndW1lbnRzWzFdID09PSB1bmRlZmluZWQgfHwgYXJndW1lbnRzWzFdLmxlbmd0aCA8PSAwKSB7IC8vIG1lc3NhZ2VcclxuICAgICAgICAgICAgICAgIGFyZ3VtZW50c1sxXSA9IGBcIkZhaWx1cmUgaXMgc2ltcGx5IHRoZSBvcHBvcnR1bml0eSB0byBiZWdpbiBhZ2FpbiwgdGhpcyB0aW1lIG1vcmUgaW50ZWxsaWdlbnRseS5cIiA8YnI+PGJyPi0gSGVucnkgRm9yZGA7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmIChhcmd1bWVudHNbMl0gPT09IHVuZGVmaW5lZCB8fCBhcmd1bWVudHNbMl0ubGVuZ3RoIDw9IDApIHsgLy8gYnV0dG9uVGV4dFxyXG4gICAgICAgICAgICAgICAgYXJndW1lbnRzWzJdID0gJ09rYXknO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBpZiAoYXJndW1lbnRzWzNdID09PSB1bmRlZmluZWQpIHsgLy8gYnV0dG9uQ2FsbGJhY2tcclxuICAgICAgICAgICAgICAgIGFyZ3VtZW50c1szXSA9IHVuZGVmaW5lZDtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgY29uc3QgdGhlVHlwZSA9IG5ld1JlcG9ydFNldHRpbmdzLmZhaWx1cmU7XHJcbiAgICAgICAgICAgIE5vdGlmbGl4UmVwb3J0KGFyZ3VtZW50c1swXSwgYXJndW1lbnRzWzFdLCBhcmd1bWVudHNbMl0sIGFyZ3VtZW50c1szXSwgdGhlVHlwZSwgJ2ZhaWx1cmUnKTtcclxuXHJcbiAgICAgICAgfSxcclxuXHJcbiAgICAgICAgLy8gRGlzcGxheSBSZXBvcnQ6IFdhcm5pbmdcclxuICAgICAgICBXYXJuaW5nOiBmdW5jdGlvbiAodGl0bGUsIG1lc3NhZ2UsIGJ1dHRvblRleHQsIGJ1dHRvbkNhbGxiYWNrKSB7XHJcblxyXG4gICAgICAgICAgICAvLyBpZiBub3QgaW5pdGlhbGl6ZWQgcHJldGVuZCBsaWtlIGluaXRcclxuICAgICAgICAgICAgaWYgKCFuZXdSZXBvcnRTZXR0aW5ncykge1xyXG4gICAgICAgICAgICAgICAgTm90aWZsaXguUmVwb3J0LkluaXQoe30pO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBpZiAoYXJndW1lbnRzLmxlbmd0aCA+IDQpIHsgLy8gTW9yZSBwYXJhbWV0ZXJzIHRoYW4gYWxsb3dlZFxyXG4gICAgICAgICAgICAgICAgbm90aWZsaXhDb25zb2xlRXJyb3IoJ05vdGlmbGl4IEVycm9yJywgJ01vcmUgcGFyYW1ldGVycyB0aGFuIGFsbG93ZWQuJyk7XHJcbiAgICAgICAgICAgICAgICByZXR1cm47XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmIChhcmd1bWVudHNbMF0gPT09IHVuZGVmaW5lZCB8fCBhcmd1bWVudHNbMF0ubGVuZ3RoIDw9IDApIHsgLy8gdGl0bGVcclxuICAgICAgICAgICAgICAgIGFyZ3VtZW50c1swXSA9ICdOb3RpZmxpeCBXYXJuaW5nJztcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgaWYgKGFyZ3VtZW50c1sxXSA9PT0gdW5kZWZpbmVkIHx8IGFyZ3VtZW50c1sxXS5sZW5ndGggPD0gMCkgeyAvLyBtZXNzYWdlXHJcbiAgICAgICAgICAgICAgICBhcmd1bWVudHNbMV0gPSBgXCJUaGUgcGVvcGxlcyB3aG8gd2FudCB0byBsaXZlIGNvbWZvcnRhYmx5IHdpdGhvdXQgcHJvZHVjaW5nIGFuZCBmYXRpZ3VlOyB0aGV5IGFyZSBkb29tZWQgdG8gbG9zZSB0aGVpciBkaWduaXR5LCB0aGVuIGxpYmVydHksIGFuZCB0aGVuIGluZGVwZW5kZW5jZSBhbmQgZGVzdGlueS5cIiA8YnI+PGJyPi0gTXVzdGFmYSBLZW1hbCBBdGF0dXJrYDtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgaWYgKGFyZ3VtZW50c1syXSA9PT0gdW5kZWZpbmVkIHx8IGFyZ3VtZW50c1syXS5sZW5ndGggPD0gMCkgeyAvLyBidXR0b25UZXh0XHJcbiAgICAgICAgICAgICAgICBhcmd1bWVudHNbMl0gPSAnT2theSc7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmIChhcmd1bWVudHNbM10gPT09IHVuZGVmaW5lZCkgeyAvLyBidXR0b25DYWxsYmFja1xyXG4gICAgICAgICAgICAgICAgYXJndW1lbnRzWzNdID0gdW5kZWZpbmVkO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBjb25zdCB0aGVUeXBlID0gbmV3UmVwb3J0U2V0dGluZ3Mud2FybmluZztcclxuICAgICAgICAgICAgTm90aWZsaXhSZXBvcnQoYXJndW1lbnRzWzBdLCBhcmd1bWVudHNbMV0sIGFyZ3VtZW50c1syXSwgYXJndW1lbnRzWzNdLCB0aGVUeXBlLCAnd2FybmluZycpO1xyXG5cclxuICAgICAgICB9LFxyXG5cclxuICAgICAgICAvLyBEaXNwbGF5IFJlcG9ydDogSW5mb1xyXG4gICAgICAgIEluZm86IGZ1bmN0aW9uICh0aXRsZSwgbWVzc2FnZSwgYnV0dG9uVGV4dCwgYnV0dG9uQ2FsbGJhY2spIHtcclxuXHJcbiAgICAgICAgICAgIC8vIGlmIG5vdCBpbml0aWFsaXplZCBwcmV0ZW5kIGxpa2UgaW5pdFxyXG4gICAgICAgICAgICBpZiAoIW5ld1JlcG9ydFNldHRpbmdzKSB7XHJcbiAgICAgICAgICAgICAgICBOb3RpZmxpeC5SZXBvcnQuSW5pdCh7fSk7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmIChhcmd1bWVudHMubGVuZ3RoID4gNCkgeyAvLyBNb3JlIHBhcmFtZXRlcnMgdGhhbiBhbGxvd2VkXHJcbiAgICAgICAgICAgICAgICBub3RpZmxpeENvbnNvbGVFcnJvcignTm90aWZsaXggRXJyb3InLCAnTW9yZSBwYXJhbWV0ZXJzIHRoYW4gYWxsb3dlZC4nKTtcclxuICAgICAgICAgICAgICAgIHJldHVybjtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgaWYgKGFyZ3VtZW50c1swXSA9PT0gdW5kZWZpbmVkIHx8IGFyZ3VtZW50c1swXS5sZW5ndGggPD0gMCkgeyAvLyB0aXRsZVxyXG4gICAgICAgICAgICAgICAgYXJndW1lbnRzWzBdID0gJ05vdGlmbGl4IEluZm8nO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBpZiAoYXJndW1lbnRzWzFdID09PSB1bmRlZmluZWQgfHwgYXJndW1lbnRzWzFdLmxlbmd0aCA8PSAwKSB7IC8vIG1lc3NhZ2VcclxuICAgICAgICAgICAgICAgIGFyZ3VtZW50c1sxXSA9IGBcIktub3dsZWRnZSByZXN0cyBub3QgdXBvbiB0cnV0aCBhbG9uZSwgYnV0IHVwb24gZXJyb3IgYWxzby5cIiA8YnI+PGJyPi0gQ2FybCBHdXN0YXYgSnVuZ2A7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmIChhcmd1bWVudHNbMl0gPT09IHVuZGVmaW5lZCB8fCBhcmd1bWVudHNbMl0ubGVuZ3RoIDw9IDApIHsgLy8gYnV0dG9uVGV4dFxyXG4gICAgICAgICAgICAgICAgYXJndW1lbnRzWzJdID0gJ09rYXknO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBpZiAoYXJndW1lbnRzWzNdID09PSB1bmRlZmluZWQpIHsgLy8gYnV0dG9uQ2FsbGJhY2tcclxuICAgICAgICAgICAgICAgIGFyZ3VtZW50c1szXSA9IHVuZGVmaW5lZDtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgY29uc3QgdGhlVHlwZSA9IG5ld1JlcG9ydFNldHRpbmdzLmluZm87XHJcbiAgICAgICAgICAgIE5vdGlmbGl4UmVwb3J0KGFyZ3VtZW50c1swXSwgYXJndW1lbnRzWzFdLCBhcmd1bWVudHNbMl0sIGFyZ3VtZW50c1szXSwgdGhlVHlwZSwgJ2luZm8nKTtcclxuICAgICAgICB9LFxyXG5cclxuICAgIH0sXHJcbiAgICAvLyBSZXBvcnQgb2ZmXHJcblxyXG4gICAgLy8gQ29uZmlybSBvblxyXG4gICAgQ29uZmlybToge1xyXG5cclxuICAgICAgICAvLyBJbml0XHJcbiAgICAgICAgSW5pdDogZnVuY3Rpb24gKHVzZXJDb25maXJtT3B0KSB7XHJcbiAgICAgICAgICAgIG5ld0NvbmZpcm1TZXR0aW5ncyA9IGV4dGVuZE5vdGlmbGl4KHRydWUsIGNvbmZpcm1TZXR0aW5ncywgdXNlckNvbmZpcm1PcHQpO1xyXG5cclxuICAgICAgICAgICAgLy8gdXNlIEdvb2dsZUZvbnRzIGlmIFwiUXVpY2tzYW5kXCIgb25cclxuICAgICAgICAgICAgaWYgKG5ld0NvbmZpcm1TZXR0aW5ncy51c2VHb29nbGVGb250ICYmIG5ld0NvbmZpcm1TZXR0aW5ncy5mb250RmFtaWx5ID09PSAnUXVpY2tzYW5kJykge1xyXG4gICAgICAgICAgICAgICAgbm90aWZsaXhHb29nbGVGb250KCk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgLy8gdXNlIEdvb2dsZUZvbnRzIGlmIFwiUXVpY2tzYW5kXCIgb2ZmXHJcblxyXG4gICAgICAgICAgICAvLyBhZGQgY3NzIGNvZGVzIG9uXHJcbiAgICAgICAgICAgIG5vdGlmbGl4SW50ZXJuYWxDU1MoKTtcclxuICAgICAgICAgICAgLy8gYWRkIGNzcyBjb2RlcyBvZmZcclxuICAgICAgICB9LFxyXG5cclxuICAgICAgICAvLyBNZXJnZSBGaXJzdCBJbml0XHJcbiAgICAgICAgTWVyZ2U6IGZ1bmN0aW9uICh1c2VyQ29uZmlybUV4dGVuZCkge1xyXG5cclxuICAgICAgICAgICAgaWYgKG5ld0NvbmZpcm1TZXR0aW5ncykgeyAvLyBpZiBpbml0aWFsaXplZCBhbHJlYWR5XHJcbiAgICAgICAgICAgICAgICBuZXdDb25maXJtU2V0dGluZ3MgPSBleHRlbmROb3RpZmxpeCh0cnVlLCBuZXdDb25maXJtU2V0dGluZ3MsIHVzZXJDb25maXJtRXh0ZW5kKTtcclxuICAgICAgICAgICAgfSBlbHNlIHsgLy8gZXJyb3JcclxuICAgICAgICAgICAgICAgIG5vdGlmbGl4Q29uc29sZUVycm9yKCdOb3RpZmxpeCBFcnJvcicsICdZb3UgaGF2ZSB0byBpbml0aWFsaXplIHRoZSBDb25maXJtIG1vZHVsZSBiZWZvcmUgY2FsbCBNZXJnZSBmdW5jdGlvbi4nKTtcclxuICAgICAgICAgICAgICAgIHJldHVybjtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH0sXHJcblxyXG4gICAgICAgIC8vIERpc3BsYXkgQ29uZmlybTogU2hvd1xyXG4gICAgICAgIFNob3c6IGZ1bmN0aW9uICh0aXRsZSwgbWVzc2FnZSwgb2tUZXh0LCBjYW5jZWxUZXh0LCBva0NhbGxiYWNrLCBjYW5jZWxDYWxsYmFjaykge1xyXG5cclxuICAgICAgICAgICAgLy8gaWYgbm90IGluaXRpYWxpemVkIHByZXRlbmQgbGlrZSBpbml0XHJcbiAgICAgICAgICAgIGlmICghbmV3Q29uZmlybVNldHRpbmdzKSB7XHJcbiAgICAgICAgICAgICAgICBOb3RpZmxpeC5Db25maXJtLkluaXQoe30pO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBpZiAoYXJndW1lbnRzLmxlbmd0aCA+IDYpIHsgLy8gTW9yZSBwYXJhbWV0ZXJzIHRoYW4gYWxsb3dlZFxyXG4gICAgICAgICAgICAgICAgbm90aWZsaXhDb25zb2xlRXJyb3IoJ05vdGlmbGl4IEVycm9yJywgJ01vcmUgcGFyYW1ldGVycyB0aGFuIGFsbG93ZWQuJyk7XHJcbiAgICAgICAgICAgICAgICByZXR1cm47XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmIChhcmd1bWVudHNbMF0gPT09IHVuZGVmaW5lZCB8fCBhcmd1bWVudHNbMF0ubGVuZ3RoIDw9IDApIHsgLy8gdGl0bGVcclxuICAgICAgICAgICAgICAgIGFyZ3VtZW50c1swXSA9ICdOb3RpZmxpeCBDb25maXJtJztcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgaWYgKGFyZ3VtZW50c1sxXSA9PT0gdW5kZWZpbmVkIHx8IGFyZ3VtZW50c1sxXS5sZW5ndGggPD0gMCkgeyAvLyBtZXNzYWdlXHJcbiAgICAgICAgICAgICAgICBhcmd1bWVudHNbMV0gPSAnRG8geW91IGFncmVlIHdpdGggbWU/JztcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgaWYgKGFyZ3VtZW50c1syXSA9PT0gdW5kZWZpbmVkIHx8IGFyZ3VtZW50c1syXS5sZW5ndGggPD0gMCkgeyAvLyBvayBidXR0b25UZXh0XHJcbiAgICAgICAgICAgICAgICBhcmd1bWVudHNbMl0gPSAnWWVzJztcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgaWYgKGFyZ3VtZW50c1szXSA9PT0gdW5kZWZpbmVkIHx8IGFyZ3VtZW50c1szXS5sZW5ndGggPD0gMCkgeyAvLyBjYW5jZWwgYnV0dG9uVGV4dFxyXG4gICAgICAgICAgICAgICAgYXJndW1lbnRzWzNdID0gJ05vJztcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgaWYgKGFyZ3VtZW50c1s0XSA9PT0gdW5kZWZpbmVkKSB7IC8vIG9rQ2FsbGJhY2tcclxuICAgICAgICAgICAgICAgIGFyZ3VtZW50c1s0XSA9IHVuZGVmaW5lZDtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgaWYgKGFyZ3VtZW50c1s1XSA9PT0gdW5kZWZpbmVkKSB7IC8vIGNhbmNlbENhbGxiYWNrXHJcbiAgICAgICAgICAgICAgICBhcmd1bWVudHNbNV0gPSB1bmRlZmluZWQ7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIE5vdGlmbGl4Q29uZmlybShhcmd1bWVudHNbMF0sIGFyZ3VtZW50c1sxXSwgYXJndW1lbnRzWzJdLCBhcmd1bWVudHNbM10sIGFyZ3VtZW50c1s0XSwgYXJndW1lbnRzWzVdKTtcclxuICAgICAgICB9LFxyXG4gICAgfSxcclxuICAgIC8vIENvbmZpcm0gb2ZmXHJcblxyXG4gICAgLy8gTG9hZGluZyBvblxyXG4gICAgTG9hZGluZzoge1xyXG5cclxuICAgICAgICAvLyBJbml0XHJcbiAgICAgICAgSW5pdDogZnVuY3Rpb24gKHVzZXJMb2FkaW5nT3B0KSB7XHJcbiAgICAgICAgICAgIG5ld0xvYWRpbmdTZXR0aW5ncyA9IGV4dGVuZE5vdGlmbGl4KHRydWUsIGxvYWRpbmdTZXR0aW5ncywgdXNlckxvYWRpbmdPcHQpO1xyXG5cclxuICAgICAgICAgICAgLy8gdXNlIEdvb2dsZUZvbnRzIGlmIFwiUXVpY2tzYW5kXCIgb25cclxuICAgICAgICAgICAgaWYgKG5ld0xvYWRpbmdTZXR0aW5ncy51c2VHb29nbGVGb250ICYmIG5ld0xvYWRpbmdTZXR0aW5ncy5mb250RmFtaWx5ID09PSAnUXVpY2tzYW5kJykge1xyXG4gICAgICAgICAgICAgICAgbm90aWZsaXhHb29nbGVGb250KCk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgLy8gdXNlIEdvb2dsZUZvbnRzIGlmIFwiUXVpY2tzYW5kXCIgb2ZmXHJcblxyXG4gICAgICAgICAgICAvLyBhZGQgY3NzIGNvZGVzIG9uXHJcbiAgICAgICAgICAgIG5vdGlmbGl4SW50ZXJuYWxDU1MoKTtcclxuICAgICAgICAgICAgLy8gYWRkIGNzcyBjb2RlcyBvZmZcclxuICAgICAgICB9LFxyXG5cclxuICAgICAgICAvLyBNZXJnZSBGaXJzdCBJbml0XHJcbiAgICAgICAgTWVyZ2U6IGZ1bmN0aW9uICh1c2VyTG9hZGluZ0V4dGVuZCkge1xyXG5cclxuICAgICAgICAgICAgaWYgKG5ld0xvYWRpbmdTZXR0aW5ncykgeyAvLyBpZiBpbml0aWFsaXplZCBhbHJlYWR5XHJcbiAgICAgICAgICAgICAgICBuZXdMb2FkaW5nU2V0dGluZ3MgPSBleHRlbmROb3RpZmxpeCh0cnVlLCBuZXdMb2FkaW5nU2V0dGluZ3MsIHVzZXJMb2FkaW5nRXh0ZW5kKTtcclxuICAgICAgICAgICAgfSBlbHNlIHsgLy8gZXJyb3JcclxuICAgICAgICAgICAgICAgIG5vdGlmbGl4Q29uc29sZUVycm9yKCdOb3RpZmxpeCBFcnJvcicsICdZb3UgaGF2ZSB0byBpbml0aWFsaXplIHRoZSBMb2FkaW5nIG1vZHVsZSBiZWZvcmUgY2FsbCBNZXJnZSBmdW5jdGlvbi4nKTtcclxuICAgICAgICAgICAgICAgIHJldHVybjtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH0sXHJcblxyXG4gICAgICAgIC8vIERpc3BsYXkgTG9hZGluZzogU3RhbmRhcmRcclxuICAgICAgICBTdGFuZGFyZDogZnVuY3Rpb24gKG1lc3NhZ2UpIHtcclxuXHJcbiAgICAgICAgICAgIC8vIGlmIG5vdCBpbml0aWFsaXplZCBwcmV0ZW5kIGxpa2UgaW5pdFxyXG4gICAgICAgICAgICBpZiAoIW5ld0xvYWRpbmdTZXR0aW5ncykge1xyXG4gICAgICAgICAgICAgICAgTm90aWZsaXguTG9hZGluZy5Jbml0KHt9KTtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgaWYgKGFyZ3VtZW50cy5sZW5ndGggPiAxKSB7IC8vIE1vcmUgcGFyYW1ldGVycyB0aGFuIGFsbG93ZWRcclxuICAgICAgICAgICAgICAgIG5vdGlmbGl4Q29uc29sZUVycm9yKCdOb3RpZmxpeCBFcnJvcicsICdNb3JlIHBhcmFtZXRlcnMgdGhhbiBhbGxvd2VkLicpO1xyXG4gICAgICAgICAgICAgICAgcmV0dXJuO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBpZiAoIW1lc3NhZ2UpIHtcclxuICAgICAgICAgICAgICAgIG1lc3NhZ2UgPSAnJztcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgTm90aWZsaXhMb2FkaW5nKG1lc3NhZ2UsICdzdGFuZGFyZCcsIHRydWUsIDApOyAvLyB0cnVlID0gZGlzcGxheVxyXG5cclxuICAgICAgICB9LFxyXG5cclxuICAgICAgICAvLyBEaXNwbGF5IExvYWRpbmc6IEhvdXJnbGFzc1xyXG4gICAgICAgIEhvdXJnbGFzczogZnVuY3Rpb24gKG1lc3NhZ2UpIHtcclxuXHJcbiAgICAgICAgICAgIC8vIGlmIG5vdCBpbml0aWFsaXplZCBwcmV0ZW5kIGxpa2UgaW5pdFxyXG4gICAgICAgICAgICBpZiAoIW5ld0xvYWRpbmdTZXR0aW5ncykge1xyXG4gICAgICAgICAgICAgICAgTm90aWZsaXguTG9hZGluZy5Jbml0KHt9KTtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgaWYgKGFyZ3VtZW50cy5sZW5ndGggPiAxKSB7IC8vIE1vcmUgcGFyYW1ldGVycyB0aGFuIGFsbG93ZWRcclxuICAgICAgICAgICAgICAgIG5vdGlmbGl4Q29uc29sZUVycm9yKCdOb3RpZmxpeCBFcnJvcicsICdNb3JlIHBhcmFtZXRlcnMgdGhhbiBhbGxvd2VkLicpO1xyXG4gICAgICAgICAgICAgICAgcmV0dXJuO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBpZiAoIW1lc3NhZ2UpIHtcclxuICAgICAgICAgICAgICAgIG1lc3NhZ2UgPSAnJztcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgTm90aWZsaXhMb2FkaW5nKG1lc3NhZ2UsICdob3VyZ2xhc3MnLCB0cnVlLCAwKTsgLy8gdHJ1ZSA9IGRpc3BsYXlcclxuXHJcbiAgICAgICAgfSxcclxuXHJcbiAgICAgICAgLy8gRGlzcGxheSBMb2FkaW5nOiBDaXJjbGVcclxuICAgICAgICBDaXJjbGU6IGZ1bmN0aW9uIChtZXNzYWdlKSB7XHJcblxyXG4gICAgICAgICAgICAvLyBpZiBub3QgaW5pdGlhbGl6ZWQgcHJldGVuZCBsaWtlIGluaXRcclxuICAgICAgICAgICAgaWYgKCFuZXdMb2FkaW5nU2V0dGluZ3MpIHtcclxuICAgICAgICAgICAgICAgIE5vdGlmbGl4LkxvYWRpbmcuSW5pdCh7fSk7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmIChhcmd1bWVudHMubGVuZ3RoID4gMSkgeyAvLyBNb3JlIHBhcmFtZXRlcnMgdGhhbiBhbGxvd2VkXHJcbiAgICAgICAgICAgICAgICBub3RpZmxpeENvbnNvbGVFcnJvcignTm90aWZsaXggRXJyb3InLCAnTW9yZSBwYXJhbWV0ZXJzIHRoYW4gYWxsb3dlZC4nKTtcclxuICAgICAgICAgICAgICAgIHJldHVybjtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgaWYgKCFtZXNzYWdlKSB7XHJcbiAgICAgICAgICAgICAgICBtZXNzYWdlID0gJyc7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIE5vdGlmbGl4TG9hZGluZyhtZXNzYWdlLCAnY2lyY2xlJywgdHJ1ZSwgMCk7IC8vIHRydWUgPSBkaXNwbGF5XHJcblxyXG4gICAgICAgIH0sXHJcblxyXG4gICAgICAgIC8vIERpc3BsYXkgTG9hZGluZzogQXJyb3dzXHJcbiAgICAgICAgQXJyb3dzOiBmdW5jdGlvbiAobWVzc2FnZSkge1xyXG5cclxuICAgICAgICAgICAgLy8gaWYgbm90IGluaXRpYWxpemVkIHByZXRlbmQgbGlrZSBpbml0XHJcbiAgICAgICAgICAgIGlmICghbmV3TG9hZGluZ1NldHRpbmdzKSB7XHJcbiAgICAgICAgICAgICAgICBOb3RpZmxpeC5Mb2FkaW5nLkluaXQoe30pO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBpZiAoYXJndW1lbnRzLmxlbmd0aCA+IDEpIHsgLy8gTW9yZSBwYXJhbWV0ZXJzIHRoYW4gYWxsb3dlZFxyXG4gICAgICAgICAgICAgICAgbm90aWZsaXhDb25zb2xlRXJyb3IoJ05vdGlmbGl4IEVycm9yJywgJ01vcmUgcGFyYW1ldGVycyB0aGFuIGFsbG93ZWQuJyk7XHJcbiAgICAgICAgICAgICAgICByZXR1cm47XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmICghbWVzc2FnZSkge1xyXG4gICAgICAgICAgICAgICAgbWVzc2FnZSA9ICcnO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBOb3RpZmxpeExvYWRpbmcobWVzc2FnZSwgJ2Fycm93cycsIHRydWUsIDApOyAvLyB0cnVlID0gZGlzcGxheVxyXG5cclxuICAgICAgICB9LFxyXG5cclxuICAgICAgICAvLyBEaXNwbGF5IExvYWRpbmc6IERvdHNcclxuICAgICAgICBEb3RzOiBmdW5jdGlvbiAobWVzc2FnZSkge1xyXG5cclxuICAgICAgICAgICAgLy8gaWYgbm90IGluaXRpYWxpemVkIHByZXRlbmQgbGlrZSBpbml0XHJcbiAgICAgICAgICAgIGlmICghbmV3TG9hZGluZ1NldHRpbmdzKSB7XHJcbiAgICAgICAgICAgICAgICBOb3RpZmxpeC5Mb2FkaW5nLkluaXQoe30pO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBpZiAoYXJndW1lbnRzLmxlbmd0aCA+IDEpIHsgLy8gTW9yZSBwYXJhbWV0ZXJzIHRoYW4gYWxsb3dlZFxyXG4gICAgICAgICAgICAgICAgbm90aWZsaXhDb25zb2xlRXJyb3IoJ05vdGlmbGl4IEVycm9yJywgJ01vcmUgcGFyYW1ldGVycyB0aGFuIGFsbG93ZWQuJyk7XHJcbiAgICAgICAgICAgICAgICByZXR1cm47XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmICghbWVzc2FnZSkge1xyXG4gICAgICAgICAgICAgICAgbWVzc2FnZSA9ICcnO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBOb3RpZmxpeExvYWRpbmcobWVzc2FnZSwgJ2RvdHMnLCB0cnVlLCAwKTsgLy8gdHJ1ZSA9IGRpc3BsYXlcclxuXHJcbiAgICAgICAgfSxcclxuXHJcbiAgICAgICAgLy8gRGlzcGxheSBMb2FkaW5nOiBQdWxzZVxyXG4gICAgICAgIFB1bHNlOiBmdW5jdGlvbiAobWVzc2FnZSkge1xyXG5cclxuICAgICAgICAgICAgLy8gaWYgbm90IGluaXRpYWxpemVkIHByZXRlbmQgbGlrZSBpbml0XHJcbiAgICAgICAgICAgIGlmICghbmV3TG9hZGluZ1NldHRpbmdzKSB7XHJcbiAgICAgICAgICAgICAgICBOb3RpZmxpeC5Mb2FkaW5nLkluaXQoe30pO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBpZiAoYXJndW1lbnRzLmxlbmd0aCA+IDEpIHsgLy8gTW9yZSBwYXJhbWV0ZXJzIHRoYW4gYWxsb3dlZFxyXG4gICAgICAgICAgICAgICAgbm90aWZsaXhDb25zb2xlRXJyb3IoJ05vdGlmbGl4IEVycm9yJywgJ01vcmUgcGFyYW1ldGVycyB0aGFuIGFsbG93ZWQuJyk7XHJcbiAgICAgICAgICAgICAgICByZXR1cm47XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmICghbWVzc2FnZSkge1xyXG4gICAgICAgICAgICAgICAgbWVzc2FnZSA9ICcnO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBOb3RpZmxpeExvYWRpbmcobWVzc2FnZSwgJ3B1bHNlJywgdHJ1ZSwgMCk7IC8vIHRydWUgPSBkaXNwbGF5XHJcblxyXG4gICAgICAgIH0sXHJcblxyXG4gICAgICAgIC8vIERpc3BsYXkgTG9hZGluZzogQ3VzdG9tXHJcbiAgICAgICAgQ3VzdG9tOiBmdW5jdGlvbiAobWVzc2FnZSkge1xyXG5cclxuICAgICAgICAgICAgLy8gaWYgbm90IGluaXRpYWxpemVkIHByZXRlbmQgbGlrZSBpbml0XHJcbiAgICAgICAgICAgIGlmICghbmV3TG9hZGluZ1NldHRpbmdzKSB7XHJcbiAgICAgICAgICAgICAgICBOb3RpZmxpeC5Mb2FkaW5nLkluaXQoe30pO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBpZiAoYXJndW1lbnRzLmxlbmd0aCA+IDEpIHsgLy8gTW9yZSBwYXJhbWV0ZXJzIHRoYW4gYWxsb3dlZFxyXG4gICAgICAgICAgICAgICAgbm90aWZsaXhDb25zb2xlRXJyb3IoJ05vdGlmbGl4IEVycm9yJywgJ01vcmUgcGFyYW1ldGVycyB0aGFuIGFsbG93ZWQuJyk7XHJcbiAgICAgICAgICAgICAgICByZXR1cm47XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmICghbWVzc2FnZSkge1xyXG4gICAgICAgICAgICAgICAgbWVzc2FnZSA9ICcnO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBOb3RpZmxpeExvYWRpbmcobWVzc2FnZSwgJ2N1c3RvbScsIHRydWUsIDApOyAvLyB0cnVlID0gZGlzcGxheVxyXG5cclxuICAgICAgICB9LFxyXG5cclxuICAgICAgICAvLyBEaXNwbGF5IExvYWRpbmc6IE5vdGlmbGl4XHJcbiAgICAgICAgTm90aWZsaXg6IGZ1bmN0aW9uIChtZXNzYWdlKSB7XHJcblxyXG4gICAgICAgICAgICAvLyBpZiBub3QgaW5pdGlhbGl6ZWQgcHJldGVuZCBsaWtlIGluaXRcclxuICAgICAgICAgICAgaWYgKCFuZXdMb2FkaW5nU2V0dGluZ3MpIHtcclxuICAgICAgICAgICAgICAgIE5vdGlmbGl4LkxvYWRpbmcuSW5pdCh7fSk7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmIChhcmd1bWVudHMubGVuZ3RoID4gMSkgeyAvLyBNb3JlIHBhcmFtZXRlcnMgdGhhbiBhbGxvd2VkXHJcbiAgICAgICAgICAgICAgICBub3RpZmxpeENvbnNvbGVFcnJvcignTm90aWZsaXggRXJyb3InLCAnTW9yZSBwYXJhbWV0ZXJzIHRoYW4gYWxsb3dlZC4nKTtcclxuICAgICAgICAgICAgICAgIHJldHVybjtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgaWYgKCFtZXNzYWdlKSB7XHJcbiAgICAgICAgICAgICAgICBtZXNzYWdlID0gJyc7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIE5vdGlmbGl4TG9hZGluZyhtZXNzYWdlLCAnbm90aWZsaXgnLCB0cnVlLCAwKTsgLy8gdHJ1ZSA9IGRpc3BsYXlcclxuXHJcbiAgICAgICAgfSxcclxuXHJcbiAgICAgICAgLy8gUmVtb3ZlIExvYWRpbmdcclxuICAgICAgICBSZW1vdmU6IGZ1bmN0aW9uICh0aGVEZWxheSkge1xyXG5cclxuICAgICAgICAgICAgLy8gaWYgbm90IGluaXRpYWxpemVkIHByZXRlbmQgbGlrZSBpbml0XHJcbiAgICAgICAgICAgIGlmICghbmV3TG9hZGluZ1NldHRpbmdzKSB7XHJcbiAgICAgICAgICAgICAgICBOb3RpZmxpeC5Mb2FkaW5nLkluaXQoe30pO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBpZiAoYXJndW1lbnRzLmxlbmd0aCA+IDEpIHsgLy8gTW9yZSBwYXJhbWV0ZXJzIHRoYW4gYWxsb3dlZFxyXG4gICAgICAgICAgICAgICAgbm90aWZsaXhDb25zb2xlRXJyb3IoJ05vdGlmbGl4IEVycm9yJywgJ01vcmUgcGFyYW1ldGVycyB0aGFuIGFsbG93ZWQuJyk7XHJcbiAgICAgICAgICAgICAgICByZXR1cm47XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmICghdGhlRGVsYXkpIHtcclxuICAgICAgICAgICAgICAgIHRoZURlbGF5ID0gMDtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgTm90aWZsaXhMb2FkaW5nKGZhbHNlLCBmYWxzZSwgZmFsc2UsIHRoZURlbGF5KTsgLy8gZmFsc2UgPSBSZW1vdmVcclxuICAgICAgICB9LFxyXG5cclxuICAgICAgICAvLyBDaGFuZ2UgVGhlIE1lc3NhZ2VcclxuICAgICAgICBDaGFuZ2U6IGZ1bmN0aW9uIChuZXdNZXNzYWdlKSB7XHJcblxyXG4gICAgICAgICAgICAvLyBpZiBub3QgaW5pdGlhbGl6ZWQgcHJldGVuZCBsaWtlIGluaXRcclxuICAgICAgICAgICAgaWYgKCFuZXdMb2FkaW5nU2V0dGluZ3MpIHtcclxuICAgICAgICAgICAgICAgIE5vdGlmbGl4LkxvYWRpbmcuSW5pdCh7fSk7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmIChhcmd1bWVudHMubGVuZ3RoID4gMSkgeyAvLyBNb3JlIHBhcmFtZXRlcnMgdGhhbiBhbGxvd2VkXHJcbiAgICAgICAgICAgICAgICBub3RpZmxpeENvbnNvbGVFcnJvcignTm90aWZsaXggRXJyb3InLCAnTW9yZSBwYXJhbWV0ZXJzIHRoYW4gYWxsb3dlZC4nKTtcclxuICAgICAgICAgICAgICAgIHJldHVybjtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgaWYgKCFuZXdNZXNzYWdlKSB7XHJcbiAgICAgICAgICAgICAgICBuZXdNZXNzYWdlID0gJyc7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIE5vdGlmbGl4TG9hZGluZ0NoYW5nZShuZXdNZXNzYWdlKTtcclxuICAgICAgICB9LFxyXG5cclxuICAgIH0sXHJcbiAgICAvLyBMb2FkaW5nIG9mZlxyXG5cclxufVxyXG5leHBvcnQgZGVmYXVsdCBOb3RpZmxpeDtcclxuLy8gTm90aWZsaXg6IE5YIFJlYWN0IG9mZlxyXG5cclxuXHJcbi8vIE5vdGlmbGl4OiBOb3RpZnkgU2luZ2xlIG9uXHJcbmxldCBub3RpZmxpeE5vdGlmeUNvdW50ID0gMDtcclxuY29uc3QgTm90aWZsaXhOb3RpZnkgPSBmdW5jdGlvbiAobWVzc2FnZSwgY2FsbGJhY2ssIHRoZVR5cGUsIHN0YXRpY1R5cGUpIHtcclxuXHJcbiAgICBpZiAoYXJndW1lbnRzICE9PSB1bmRlZmluZWQgJiYgYXJndW1lbnRzLmxlbmd0aCA9PT0gNCkge1xyXG5cclxuICAgICAgICAvLyBubyBtZXNzYWdlIG9uXHJcbiAgICAgICAgaWYgKCFtZXNzYWdlKSB7XHJcbiAgICAgICAgICAgIG1lc3NhZ2UgPSBgTm90aWZsaXggJHtzdGF0aWNUeXBlfWA7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIC8vIG5vIG1lc3NhZ2Ugb2ZmXHJcblxyXG4gICAgICAgIC8vIEZvbnRBd2Vzb21lIGlmIFNoYWRvdyBvblxyXG4gICAgICAgIGlmIChuZXdOb3RpZnlTZXR0aW5ncy5mb250QXdlc29tZUljb25TdHlsZSA9PT0gJ3NoYWRvdycpIHtcclxuICAgICAgICAgICAgdGhlVHlwZS5mb250QXdlc29tZUljb25Db2xvciA9IHRoZVR5cGUuYmFja2dyb3VuZDtcclxuICAgICAgICB9XHJcbiAgICAgICAgLy8gRm9udEF3ZXNvbWUgaWYgU2hhZG93IG9mZlxyXG5cclxuICAgICAgICAvLyBpZiBwbGFpblRleHQgdHJ1ZSA9IEhUTUwgdGFncyBub3QgYWxsb3dlZCBvbiAgICAgIFxyXG4gICAgICAgIGlmIChuZXdOb3RpZnlTZXR0aW5ncy5wbGFpblRleHQpIHtcclxuICAgICAgICAgICAgbWVzc2FnZSA9IG5vdGlmbGl4UGxhaW50ZXh0KG1lc3NhZ2UpOyAvLyBtZXNzYWdlIHBsYWluIHRleHRcclxuICAgICAgICB9XHJcbiAgICAgICAgLy8gaWYgcGxhaW5UZXh0IHRydWUgPSBIVE1MIHRhZ3Mgbm90IGFsbG93ZWQgb2ZmXHJcblxyXG4gICAgICAgIC8vIGlmIHBsYWluVGV4dCBmYWxzZSBidXQgdGhlIG1lc3NhZ2UgbGVuZ3RoIG1vcmUgdGhhbiBtZXNzYWdlTWF4TGVuZ3RoID0gSFRNTCB0YWdzIGVycm9yIG9uXHJcbiAgICAgICAgaWYgKCFuZXdOb3RpZnlTZXR0aW5ncy5wbGFpblRleHQgJiYgbWVzc2FnZS5sZW5ndGggPiBuZXdOb3RpZnlTZXR0aW5ncy5tZXNzYWdlTWF4TGVuZ3RoKSB7XHJcbiAgICAgICAgICAgIE5vdGlmbGl4Lk5vdGlmeS5NZXJnZSh7IGNsb3NlQnV0dG9uOiB0cnVlLCB9KTtcclxuICAgICAgICAgICAgbWVzc2FnZSA9IGA8Yj5IVE1MIFRhZ3MgRXJyb3I6PC9iPiBZb3VyIGNvbnRlbnQgbGVuZ3RoIGlzIG1vcmUgdGhhbiBcIm1lc3NhZ2VNYXhMZW5ndGhcIiBvcHRpb24uYDsgLy8gbWVzc2FnZSBodG1sIGVycm9yXHJcbiAgICAgICAgfVxyXG4gICAgICAgIC8vIGlmIHBsYWluVGV4dCBmYWxzZSBidXQgdGhlIG1lc3NhZ2UgbGVuZ3RoIG1vcmUgdGhhbiBtZXNzYWdlTWF4TGVuZ3RoID0gSFRNTCB0YWdzIGVycm9yIG9mZlxyXG5cclxuXHJcbiAgICAgICAgaWYgKG1lc3NhZ2UubGVuZ3RoID4gbmV3Tm90aWZ5U2V0dGluZ3MubWVzc2FnZU1heExlbmd0aCkge1xyXG4gICAgICAgICAgICBtZXNzYWdlID0gYCR7bWVzc2FnZS5zdWJzdHJpbmcoMCwgbmV3Tm90aWZ5U2V0dGluZ3MubWVzc2FnZU1heExlbmd0aCl9Li4uYDtcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIC8vIG5vdGlmeSBjb3VudGVyIG9uXHJcbiAgICAgICAgbm90aWZsaXhOb3RpZnlDb3VudCsrO1xyXG4gICAgICAgIC8vIG5vdGlmeSBjb3VudGVyIG9mZlxyXG5cclxuICAgICAgICAvLyBpZiBjc3NBbmltYWlvbiBmYWxzZSAtPiBkdXJhdGlvbiBvblxyXG4gICAgICAgIGlmICghbmV3Tm90aWZ5U2V0dGluZ3MuY3NzQW5pbWF0aW9uKSB7XHJcbiAgICAgICAgICAgIG5ld05vdGlmeVNldHRpbmdzLmNzc0FuaW1hdGlvbkR1cmF0aW9uID0gMDtcclxuICAgICAgICB9XHJcbiAgICAgICAgLy8gaWYgY3NzQW5pbWFpb24gZmFsc2UgLT4gZHVyYXRpb24gb2ZmXHJcblxyXG4gICAgICAgIC8vIG5vdGlmeSB3cmFwIG9uXHJcbiAgICAgICAgbGV0IGRvY0JvZHkgPSBkb2N1bWVudC5ib2R5O1xyXG5cclxuICAgICAgICBsZXQgbnRmbHhOb3RpZnlXcmFwID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XHJcbiAgICAgICAgbnRmbHhOb3RpZnlXcmFwLmlkID0gbm90aWZ5U2V0dGluZ3Mud3JhcElEO1xyXG4gICAgICAgIG50Zmx4Tm90aWZ5V3JhcC5zdHlsZS53aWR0aCA9IG5ld05vdGlmeVNldHRpbmdzLndpZHRoO1xyXG4gICAgICAgIG50Zmx4Tm90aWZ5V3JhcC5zdHlsZS56SW5kZXggPSBuZXdOb3RpZnlTZXR0aW5ncy56aW5kZXg7XHJcbiAgICAgICAgbnRmbHhOb3RpZnlXcmFwLnN0eWxlLm9wYWNpdHkgPSBuZXdOb3RpZnlTZXR0aW5ncy5vcGFjaXR5O1xyXG5cclxuICAgICAgICAvLyB3cmFwIHBvc2l0aW9uIG9uXHJcbiAgICAgICAgaWYgKG5ld05vdGlmeVNldHRpbmdzLnBvc2l0aW9uID09PSAncmlnaHQtYm90dG9tJykge1xyXG5cclxuICAgICAgICAgICAgbnRmbHhOb3RpZnlXcmFwLnN0eWxlLnJpZ2h0ID0gbmV3Tm90aWZ5U2V0dGluZ3MuZGlzdGFuY2U7XHJcbiAgICAgICAgICAgIG50Zmx4Tm90aWZ5V3JhcC5zdHlsZS5ib3R0b20gPSBuZXdOb3RpZnlTZXR0aW5ncy5kaXN0YW5jZTtcclxuICAgICAgICAgICAgbnRmbHhOb3RpZnlXcmFwLnN0eWxlLnRvcCA9ICdhdXRvJztcclxuICAgICAgICAgICAgbnRmbHhOb3RpZnlXcmFwLnN0eWxlLmxlZnQgPSAnYXV0byc7XHJcblxyXG4gICAgICAgIH0gZWxzZSBpZiAobmV3Tm90aWZ5U2V0dGluZ3MucG9zaXRpb24gPT09ICdsZWZ0LXRvcCcpIHtcclxuXHJcbiAgICAgICAgICAgIG50Zmx4Tm90aWZ5V3JhcC5zdHlsZS5sZWZ0ID0gbmV3Tm90aWZ5U2V0dGluZ3MuZGlzdGFuY2U7XHJcbiAgICAgICAgICAgIG50Zmx4Tm90aWZ5V3JhcC5zdHlsZS50b3AgPSBuZXdOb3RpZnlTZXR0aW5ncy5kaXN0YW5jZTtcclxuICAgICAgICAgICAgbnRmbHhOb3RpZnlXcmFwLnN0eWxlLnJpZ2h0ID0gJ2F1dG8nO1xyXG4gICAgICAgICAgICBudGZseE5vdGlmeVdyYXAuc3R5bGUuYm90dG9tID0gJ2F1dG8nO1xyXG5cclxuICAgICAgICB9IGVsc2UgaWYgKG5ld05vdGlmeVNldHRpbmdzLnBvc2l0aW9uID09PSAnbGVmdC1ib3R0b20nKSB7XHJcblxyXG4gICAgICAgICAgICBudGZseE5vdGlmeVdyYXAuc3R5bGUubGVmdCA9IG5ld05vdGlmeVNldHRpbmdzLmRpc3RhbmNlO1xyXG4gICAgICAgICAgICBudGZseE5vdGlmeVdyYXAuc3R5bGUuYm90dG9tID0gbmV3Tm90aWZ5U2V0dGluZ3MuZGlzdGFuY2U7XHJcbiAgICAgICAgICAgIG50Zmx4Tm90aWZ5V3JhcC5zdHlsZS50b3AgPSAnYXV0byc7XHJcbiAgICAgICAgICAgIG50Zmx4Tm90aWZ5V3JhcC5zdHlsZS5yaWdodCA9ICdhdXRvJztcclxuXHJcbiAgICAgICAgfSBlbHNlIHsgLy8gJ3JpZ2h0LXRvcCcgb3IgZWxzZVxyXG5cclxuICAgICAgICAgICAgbnRmbHhOb3RpZnlXcmFwLnN0eWxlLnJpZ2h0ID0gbmV3Tm90aWZ5U2V0dGluZ3MuZGlzdGFuY2U7XHJcbiAgICAgICAgICAgIG50Zmx4Tm90aWZ5V3JhcC5zdHlsZS50b3AgPSBuZXdOb3RpZnlTZXR0aW5ncy5kaXN0YW5jZTtcclxuICAgICAgICAgICAgbnRmbHhOb3RpZnlXcmFwLnN0eWxlLmxlZnQgPSAnYXV0byc7XHJcbiAgICAgICAgICAgIG50Zmx4Tm90aWZ5V3JhcC5zdHlsZS5ib3R0b20gPSAnYXV0byc7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIC8vIHdyYXAgcG9zaXRpb24gb2ZmXHJcblxyXG4gICAgICAgIC8vIGlmIGJhY2tncm91bmQgb3ZlcmxheSB0cnVlIG9uXHJcbiAgICAgICAgbGV0IG5vdGlmeU92ZXJsYXk7XHJcbiAgICAgICAgaWYgKG5ld05vdGlmeVNldHRpbmdzLmJhY2tPdmVybGF5KSB7XHJcblxyXG4gICAgICAgICAgICBub3RpZnlPdmVybGF5ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XHJcbiAgICAgICAgICAgIG5vdGlmeU92ZXJsYXkuaWQgPSBgJHtuZXdOb3RpZnlTZXR0aW5ncy5JRH1PdmVybGF5YDtcclxuICAgICAgICAgICAgbm90aWZ5T3ZlcmxheS5zdHlsZS53aWR0aCA9ICcxMDAlJztcclxuICAgICAgICAgICAgbm90aWZ5T3ZlcmxheS5zdHlsZS5oZWlnaHQgPSAnMTAwJSc7XHJcbiAgICAgICAgICAgIG5vdGlmeU92ZXJsYXkuc3R5bGUucG9zaXRpb24gPSAnZml4ZWQnO1xyXG4gICAgICAgICAgICBub3RpZnlPdmVybGF5LnN0eWxlLnpJbmRleCA9IG5ld05vdGlmeVNldHRpbmdzLnppbmRleDtcclxuICAgICAgICAgICAgbm90aWZ5T3ZlcmxheS5zdHlsZS5sZWZ0ID0gMDtcclxuICAgICAgICAgICAgbm90aWZ5T3ZlcmxheS5zdHlsZS50b3AgPSAwO1xyXG4gICAgICAgICAgICBub3RpZnlPdmVybGF5LnN0eWxlLnJpZ2h0ID0gMDtcclxuICAgICAgICAgICAgbm90aWZ5T3ZlcmxheS5zdHlsZS5ib3R0b20gPSAwO1xyXG4gICAgICAgICAgICBub3RpZnlPdmVybGF5LnN0eWxlLmJhY2tncm91bmQgPSBuZXdOb3RpZnlTZXR0aW5ncy5iYWNrT3ZlcmxheUNvbG9yO1xyXG4gICAgICAgICAgICBub3RpZnlPdmVybGF5LmNsYXNzTmFtZSA9IChuZXdOb3RpZnlTZXR0aW5ncy5jc3NBbmltYXRpb24gPyAnd2l0aC1hbmltYXRpb24nIDogJycpO1xyXG4gICAgICAgICAgICBub3RpZnlPdmVybGF5LnN0eWxlLmFuaW1hdGlvbkR1cmF0aW9uID0gKG5ld05vdGlmeVNldHRpbmdzLmNzc0FuaW1hdGlvbikgPyBgJHtuZXdOb3RpZnlTZXR0aW5ncy5jc3NBbmltYXRpb25EdXJhdGlvbn1tc2AgOiAnJztcclxuXHJcbiAgICAgICAgICAgIGlmICghZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQobm90aWZ5T3ZlcmxheS5pZCkpIHtcclxuICAgICAgICAgICAgICAgIGRvY0JvZHkuYXBwZW5kQ2hpbGQobm90aWZ5T3ZlcmxheSk7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgfVxyXG4gICAgICAgIC8vIGlmIGJhY2tncm91bmQgb3ZlcmxheSB0cnVlIG9mZlxyXG5cclxuICAgICAgICBpZiAoIWRvY3VtZW50LmdldEVsZW1lbnRCeUlkKG50Zmx4Tm90aWZ5V3JhcC5pZCkpIHtcclxuICAgICAgICAgICAgZG9jQm9keS5hcHBlbmRDaGlsZChudGZseE5vdGlmeVdyYXApO1xyXG4gICAgICAgIH1cclxuICAgICAgICAvLyBub3RpZnkgd3JhcCBvZmZcclxuXHJcblxyXG4gICAgICAgIC8vIG5vdGlmeSBjb250ZW50IG9uXHJcbiAgICAgICAgbGV0IG50Zmx4Tm90aWZ5ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XHJcbiAgICAgICAgbnRmbHhOb3RpZnkuaWQgPSBgJHtuZXdOb3RpZnlTZXR0aW5ncy5JRH0tJHtub3RpZmxpeE5vdGlmeUNvdW50fWA7XHJcbiAgICAgICAgbnRmbHhOb3RpZnkuY2xhc3NOYW1lID0gYCR7bmV3Tm90aWZ5U2V0dGluZ3MuY2xhc3NOYW1lfSAke3RoZVR5cGUuY2hpbGRDbGFzc05hbWV9ICR7KG5ld05vdGlmeVNldHRpbmdzLmNzc0FuaW1hdGlvbiA/ICd3aXRoLWFuaW1hdGlvbicgOiAnJyl9ICR7KG5ld05vdGlmeVNldHRpbmdzLnVzZUljb24gPyAnd2l0aC1pY29uJyA6ICcnKX0gbngtJHtuZXdOb3RpZnlTZXR0aW5ncy5jc3NBbmltYXRpb25TdHlsZX0gJHsobmV3Tm90aWZ5U2V0dGluZ3MuY2xvc2VCdXR0b24gJiYgIWNhbGxiYWNrID8gJ3dpdGgtY2xvc2UnIDogJycpfSAkeyhjYWxsYmFjayA/ICd3aXRoLWNhbGxiYWNrJyA6ICcnKX1gO1xyXG4gICAgICAgIG50Zmx4Tm90aWZ5LnN0eWxlLmZvbnRTaXplID0gbmV3Tm90aWZ5U2V0dGluZ3MuZm9udFNpemU7XHJcbiAgICAgICAgbnRmbHhOb3RpZnkuc3R5bGUuY29sb3IgPSB0aGVUeXBlLnRleHRDb2xvcjtcclxuICAgICAgICBudGZseE5vdGlmeS5zdHlsZS5iYWNrZ3JvdW5kID0gdGhlVHlwZS5iYWNrZ3JvdW5kO1xyXG4gICAgICAgIG50Zmx4Tm90aWZ5LnN0eWxlLmJvcmRlclJhZGl1cyA9IG5ld05vdGlmeVNldHRpbmdzLmJvcmRlclJhZGl1cztcclxuXHJcbiAgICAgICAgLy8gcnRsIG9uXHJcbiAgICAgICAgaWYgKG5ld05vdGlmeVNldHRpbmdzLnJ0bCkge1xyXG4gICAgICAgICAgICBudGZseE5vdGlmeS5zZXRBdHRyaWJ1dGUoJ2RpcicsICdydGwnKTtcclxuICAgICAgICAgICAgbnRmbHhOb3RpZnkuY2xhc3NMaXN0LmFkZCgncnRsLW9uJyk7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIC8vIHJ0bCBvZmZcclxuXHJcbiAgICAgICAgLy8gZm9udC1mYW1pbHkgb25cclxuICAgICAgICBudGZseE5vdGlmeS5zdHlsZS5mb250RmFtaWx5ID0gYFwiJHtuZXdOb3RpZnlTZXR0aW5ncy5mb250RmFtaWx5fVwiLCBzYW5zLXNlcmlmYDtcclxuICAgICAgICAvLyBmb250LWZhbWlseSBvZmZcclxuXHJcbiAgICAgICAgLy8gdXNlIGNzcyBhbmltYXRpb24gb25cclxuICAgICAgICBpZiAobmV3Tm90aWZ5U2V0dGluZ3MuY3NzQW5pbWF0aW9uKSB7XHJcbiAgICAgICAgICAgIG50Zmx4Tm90aWZ5LnN0eWxlLmFuaW1hdGlvbkR1cmF0aW9uID0gYCR7bmV3Tm90aWZ5U2V0dGluZ3MuY3NzQW5pbWF0aW9uRHVyYXRpb259bXNgO1xyXG4gICAgICAgIH1cclxuICAgICAgICAvLyB1c2UgY3NzIGFuaW1hdGlvbiBvZmZcclxuXHJcbiAgICAgICAgLy8gY2xpY2sgdG8gY2xvc2Ugb25cclxuICAgICAgICBsZXQgY2xvc2VCdXR0b25IVE1MID0gJyc7XHJcbiAgICAgICAgaWYgKG5ld05vdGlmeVNldHRpbmdzLmNsb3NlQnV0dG9uICYmICFjYWxsYmFjaykge1xyXG4gICAgICAgICAgICBjbG9zZUJ1dHRvbkhUTUwgPSBgPHNwYW4gY2xhc3M9XCJjbGljay10by1jbG9zZVwiPjxzdmcgY2xhc3M9XCJjbGNrMmNsc1wiIHhtbG5zPVwiaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmdcIiB4bWw6c3BhY2U9XCJwcmVzZXJ2ZVwiIHdpZHRoPVwiMjBweFwiIGhlaWdodD1cIjIwcHhcIiB2ZXJzaW9uPVwiMS4xXCIgc3R5bGU9XCJzaGFwZS1yZW5kZXJpbmc6Z2VvbWV0cmljUHJlY2lzaW9uOyB0ZXh0LXJlbmRlcmluZzpnZW9tZXRyaWNQcmVjaXNpb247IGltYWdlLXJlbmRlcmluZzpvcHRpbWl6ZVF1YWxpdHk7IGZpbGwtcnVsZTpldmVub2RkOyBjbGlwLXJ1bGU6ZXZlbm9kZFwidmlld0JveD1cIjAgMCAyMCAyMFwieG1sbnM6eGxpbms9XCJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rXCI+PGRlZnM+PHN0eWxlIHR5cGU9XCJ0ZXh0L2Nzc1wiPi5jbGljazJjbG9zZXtmaWxsOiR7dGhlVHlwZS5ub3RpZmxpeEljb25Db2xvcn07fTwvc3R5bGU+PC9kZWZzPjxnPjxwYXRoIGNsYXNzPVwiY2xpY2syY2xvc2VcIiBkPVwiTTAuMzggMi4xOWw3LjggNy44MSAtNy44IDcuODFjLTAuNTEsMC41IC0wLjUxLDEuMzEgLTAuMDEsMS44MSAwLjI1LDAuMjUgMC41NywwLjM4IDAuOTEsMC4zOCAwLjM0LDAgMC42NywtMC4xNCAwLjkxLC0wLjM4bDcuODEgLTcuODEgNy44MSA3LjgxYzAuMjQsMC4yNCAwLjU3LDAuMzggMC45MSwwLjM4IDAuMzQsMCAwLjY2LC0wLjE0IDAuOSwtMC4zOCAwLjUxLC0wLjUgMC41MSwtMS4zMSAwLC0xLjgxbC03LjgxIC03LjgxIDcuODEgLTcuODFjMC41MSwtMC41IDAuNTEsLTEuMzEgMCwtMS44MiAtMC41LC0wLjUgLTEuMzEsLTAuNSAtMS44MSwwbC03LjgxIDcuODEgLTcuODEgLTcuODFjLTAuNSwtMC41IC0xLjMxLC0wLjUgLTEuODEsMCAtMC41MSwwLjUxIC0wLjUxLDEuMzIgMCwxLjgyelwiLz48L2c+PC9zdmc+PC9zcGFuPmA7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIC8vIGNsaWNrIHRvIGNsb3NlIG9mZlxyXG5cclxuICAgICAgICAvLyB1c2UgaWNvbiBvblxyXG4gICAgICAgIGlmIChuZXdOb3RpZnlTZXR0aW5ncy51c2VJY29uKSB7XHJcblxyXG4gICAgICAgICAgICBpZiAobmV3Tm90aWZ5U2V0dGluZ3MudXNlRm9udEF3ZXNvbWUpIHsgLy8gdXNlIGZvbnQgYXdlc29tZVxyXG5cclxuICAgICAgICAgICAgICAgIG50Zmx4Tm90aWZ5LmlubmVySFRNTCA9IGA8aSBzdHlsZT1cImNvbG9yOiR7dGhlVHlwZS5mb250QXdlc29tZUljb25Db2xvcn07IGZvbnQtc2l6ZToke25ld05vdGlmeVNldHRpbmdzLmZvbnRBd2Vzb21lSWNvblNpemV9O1wiIGNsYXNzPVwibm1pIHdmYSAke3RoZVR5cGUuZm9udEF3ZXNvbWVDbGFzc05hbWV9ICR7KG5ld05vdGlmeVNldHRpbmdzLmZvbnRBd2Vzb21lSWNvblN0eWxlID09PSAnc2hhZG93JyA/ICdzaGFkb3cnIDogJ2Jhc2ljJyl9XCI+PC9pPjxzcGFuIGNsYXNzPVwidGhlLW1lc3NhZ2Ugd2l0aC1pY29uXCI+JHttZXNzYWdlfTwvc3Bhbj4keyhuZXdOb3RpZnlTZXR0aW5ncy5jbG9zZUJ1dHRvbiA/IGNsb3NlQnV0dG9uSFRNTCA6ICcnKX1gO1xyXG5cclxuICAgICAgICAgICAgfSBlbHNlIHsgLy8gdXNlIG5vdGlmbGl4IGljb25cclxuXHJcbiAgICAgICAgICAgICAgICBsZXQgc3ZnSWNvbiA9ICcnO1xyXG5cclxuICAgICAgICAgICAgICAgIGlmIChzdGF0aWNUeXBlID09PSAnU3VjY2VzcycpIHsgIC8vIHN1Y2Nlc3NcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgc3ZnSWNvbiA9IGA8c3ZnIGNsYXNzPVwibm1pXCIgeG1sbnM9XCJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2Z1wiIHhtbDpzcGFjZT1cInByZXNlcnZlXCIgd2lkdGg9XCI0MHB4XCIgaGVpZ2h0PVwiNDBweFwiIHZlcnNpb249XCIxLjFcIiBzdHlsZT1cInNoYXBlLXJlbmRlcmluZzpnZW9tZXRyaWNQcmVjaXNpb247IHRleHQtcmVuZGVyaW5nOmdlb21ldHJpY1ByZWNpc2lvbjsgaW1hZ2UtcmVuZGVyaW5nOm9wdGltaXplUXVhbGl0eTsgZmlsbC1ydWxlOmV2ZW5vZGQ7IGNsaXAtcnVsZTpldmVub2RkXCJ2aWV3Qm94PVwiMCAwIDQwIDQwXCJ4bWxuczp4bGluaz1cImh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmtcIj48ZGVmcz48c3R5bGUgdHlwZT1cInRleHQvY3NzXCI+I05vdGlmbGl4LUljb24tU3VjY2Vzc3tmaWxsOiR7dGhlVHlwZS5ub3RpZmxpeEljb25Db2xvcn07fTwvc3R5bGU+PC9kZWZzPjxnPjxwYXRoIGlkPVwiTm90aWZsaXgtSWNvbi1TdWNjZXNzXCIgY2xhc3M9XCJmaWwwXCIgZD1cIk0yMCAwYzExLjAzLDAgMjAsOC45NyAyMCwyMCAwLDExLjAzIC04Ljk3LDIwIC0yMCwyMCAtMTEuMDMsMCAtMjAsLTguOTcgLTIwLC0yMCAwLC0xMS4wMyA4Ljk3LC0yMCAyMCwtMjB6bTAgMzcuOThjOS45MiwwIDE3Ljk4LC04LjA2IDE3Ljk4LC0xNy45OCAwLC05LjkyIC04LjA2LC0xNy45OCAtMTcuOTgsLTE3Ljk4IC05LjkyLDAgLTE3Ljk4LDguMDYgLTE3Ljk4LDE3Ljk4IDAsOS45MiA4LjA2LDE3Ljk4IDE3Ljk4LDE3Ljk4em0tMi40IC0xMy4yOWwxMS41MiAtMTIuOTZjMC4zNywtMC40MSAxLjAxLC0wLjQ1IDEuNDIsLTAuMDggMC40MiwwLjM3IDAuNDYsMSAwLjA5LDEuNDJsLTEyLjE2IDEzLjY3Yy0wLjE5LDAuMjIgLTAuNDYsMC4zNCAtMC43NSwwLjM0IC0wLjIzLDAgLTAuNDUsLTAuMDcgLTAuNjMsLTAuMjJsLTcuNiAtNi4wN2MtMC40MywtMC4zNSAtMC41LC0wLjk5IC0wLjE2LC0xLjQyIDAuMzUsLTAuNDMgMC45OSwtMC41IDEuNDIsLTAuMTZsNi44NSA1LjQ4elwiLz48L2c+PC9zdmc+YDtcclxuXHJcbiAgICAgICAgICAgICAgICB9IGVsc2UgaWYgKHN0YXRpY1R5cGUgPT09ICdGYWlsdXJlJykgeyAvLyBmYWlsdXJlXHJcblxyXG4gICAgICAgICAgICAgICAgICAgIHN2Z0ljb24gPSBgPHN2ZyBjbGFzcz1cIm5taVwiIHhtbG5zPVwiaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmdcIiB4bWw6c3BhY2U9XCJwcmVzZXJ2ZVwiIHdpZHRoPVwiNDBweFwiIGhlaWdodD1cIjQwcHhcIiB2ZXJzaW9uPVwiMS4xXCIgc3R5bGU9XCJzaGFwZS1yZW5kZXJpbmc6Z2VvbWV0cmljUHJlY2lzaW9uOyB0ZXh0LXJlbmRlcmluZzpnZW9tZXRyaWNQcmVjaXNpb247IGltYWdlLXJlbmRlcmluZzpvcHRpbWl6ZVF1YWxpdHk7IGZpbGwtcnVsZTpldmVub2RkOyBjbGlwLXJ1bGU6ZXZlbm9kZFwidmlld0JveD1cIjAgMCA0MCA0MFwieG1sbnM6eGxpbms9XCJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rXCI+PGRlZnM+PHN0eWxlIHR5cGU9XCJ0ZXh0L2Nzc1wiPiNOb3RpZmxpeC1JY29uLUZhaWx1cmV7ZmlsbDoke3RoZVR5cGUubm90aWZsaXhJY29uQ29sb3J9O308L3N0eWxlPjwvZGVmcz48Zz48cGF0aCBpZD1cIk5vdGlmbGl4LUljb24tRmFpbHVyZVwiIGNsYXNzPVwiZmlsMFwiIGQ9XCJNMjAgMGMxMS4wMywwIDIwLDguOTcgMjAsMjAgMCwxMS4wMyAtOC45NywyMCAtMjAsMjAgLTExLjAzLDAgLTIwLC04Ljk3IC0yMCwtMjAgMCwtMTEuMDMgOC45NywtMjAgMjAsLTIwem0wIDM3Ljk4YzkuOTIsMCAxNy45OCwtOC4wNiAxNy45OCwtMTcuOTggMCwtOS45MiAtOC4wNiwtMTcuOTggLTE3Ljk4LC0xNy45OCAtOS45MiwwIC0xNy45OCw4LjA2IC0xNy45OCwxNy45OCAwLDkuOTIgOC4wNiwxNy45OCAxNy45OCwxNy45OHptMS40MiAtMTcuOThsNi4xMyA2LjEyYzAuMzksMC40IDAuMzksMS4wNCAwLDEuNDMgLTAuMTksMC4xOSAtMC40NSwwLjI5IC0wLjcxLDAuMjkgLTAuMjcsMCAtMC41MywtMC4xIC0wLjcyLC0wLjI5bC02LjEyIC02LjEzIC02LjEzIDYuMTNjLTAuMTksMC4xOSAtMC40NCwwLjI5IC0wLjcxLDAuMjkgLTAuMjcsMCAtMC41MiwtMC4xIC0wLjcxLC0wLjI5IC0wLjM5LC0wLjM5IC0wLjM5LC0xLjAzIDAsLTEuNDNsNi4xMyAtNi4xMiAtNi4xMyAtNi4xM2MtMC4zOSwtMC4zOSAtMC4zOSwtMS4wMyAwLC0xLjQyIDAuMzksLTAuMzkgMS4wMywtMC4zOSAxLjQyLDBsNi4xMyA2LjEyIDYuMTIgLTYuMTJjMC40LC0wLjM5IDEuMDQsLTAuMzkgMS40MywwIDAuMzksMC4zOSAwLjM5LDEuMDMgMCwxLjQybC02LjEzIDYuMTN6XCIvPjwvZz48L3N2Zz5gO1xyXG5cclxuICAgICAgICAgICAgICAgIH0gZWxzZSBpZiAoc3RhdGljVHlwZSA9PT0gJ1dhcm5pbmcnKSB7IC8vIHdhcm5pbmdcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgc3ZnSWNvbiA9IGA8c3ZnIGNsYXNzPVwibm1pXCIgeG1sbnM9XCJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2Z1wiIHhtbDpzcGFjZT1cInByZXNlcnZlXCIgd2lkdGg9XCI0MHB4XCIgaGVpZ2h0PVwiNDBweFwiIHZlcnNpb249XCIxLjFcIiBzdHlsZT1cInNoYXBlLXJlbmRlcmluZzpnZW9tZXRyaWNQcmVjaXNpb247IHRleHQtcmVuZGVyaW5nOmdlb21ldHJpY1ByZWNpc2lvbjsgaW1hZ2UtcmVuZGVyaW5nOm9wdGltaXplUXVhbGl0eTsgZmlsbC1ydWxlOmV2ZW5vZGQ7IGNsaXAtcnVsZTpldmVub2RkXCJ2aWV3Qm94PVwiMCAwIDQwIDQwXCJ4bWxuczp4bGluaz1cImh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmtcIj48ZGVmcz48c3R5bGUgdHlwZT1cInRleHQvY3NzXCI+I05vdGlmbGl4LUljb24tV2FybmluZ3tmaWxsOiR7dGhlVHlwZS5ub3RpZmxpeEljb25Db2xvcn07fTwvc3R5bGU+PC9kZWZzPjxnPjxwYXRoIGlkPVwiTm90aWZsaXgtSWNvbi1XYXJuaW5nXCIgY2xhc3M9XCJmaWwwXCIgZD1cIk0yMS45MSAzLjQ4bDE3LjggMzAuODljMC44NCwxLjQ2IC0wLjIzLDMuMjUgLTEuOTEsMy4yNWwtMzUuNiAwYy0xLjY4LDAgLTIuNzUsLTEuNzkgLTEuOTEsLTMuMjVsMTcuOCAtMzAuODljMC44NSwtMS40NyAyLjk3LC0xLjQ3IDMuODIsMHptMTYuMTUgMzEuODRsLTE3LjggLTMwLjg5Yy0wLjExLC0wLjIgLTAuNDEsLTAuMiAtMC41MiwwbC0xNy44IDMwLjg5Yy0wLjEyLDAuMiAwLjA1LDAuNCAwLjI2LDAuNGwzNS42IDBjMC4yMSwwIDAuMzgsLTAuMiAwLjI2LC0wLjR6bS0xOS4wMSAtNC4xMmwwIC0xLjA1YzAsLTAuNTMgMC40MiwtMC45NSAwLjk1LC0wLjk1IDAuNTMsMCAwLjk1LDAuNDIgMC45NSwwLjk1bDAgMS4wNWMwLDAuNTMgLTAuNDIsMC45NSAtMC45NSwwLjk1IC0wLjUzLDAgLTAuOTUsLTAuNDIgLTAuOTUsLTAuOTV6bTAgLTQuNjZsMCAtMTMuMzljMCwtMC41MiAwLjQyLC0wLjk1IDAuOTUsLTAuOTUgMC41MywwIDAuOTUsMC40MyAwLjk1LDAuOTVsMCAxMy4zOWMwLDAuNTMgLTAuNDIsMC45NiAtMC45NSwwLjk2IC0wLjUzLDAgLTAuOTUsLTAuNDMgLTAuOTUsLTAuOTZ6XCIvPjwvZz48L3N2Zz5gO1xyXG5cclxuICAgICAgICAgICAgICAgIH0gZWxzZSBpZiAoc3RhdGljVHlwZSA9PT0gJ0luZm8nKSB7IC8vIGluZm9cclxuXHJcbiAgICAgICAgICAgICAgICAgICAgc3ZnSWNvbiA9IGA8c3ZnIGNsYXNzPVwibm1pXCIgeG1sbnM9XCJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2Z1wiIHhtbDpzcGFjZT1cInByZXNlcnZlXCIgd2lkdGg9XCI0MHB4XCIgaGVpZ2h0PVwiNDBweFwiIHZlcnNpb249XCIxLjFcIiBzdHlsZT1cInNoYXBlLXJlbmRlcmluZzpnZW9tZXRyaWNQcmVjaXNpb247IHRleHQtcmVuZGVyaW5nOmdlb21ldHJpY1ByZWNpc2lvbjsgaW1hZ2UtcmVuZGVyaW5nOm9wdGltaXplUXVhbGl0eTsgZmlsbC1ydWxlOmV2ZW5vZGQ7IGNsaXAtcnVsZTpldmVub2RkXCJ2aWV3Qm94PVwiMCAwIDQwIDQwXCJ4bWxuczp4bGluaz1cImh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmtcIj48ZGVmcz48c3R5bGUgdHlwZT1cInRleHQvY3NzXCI+I05vdGlmbGl4LUljb24tSW5mb3tmaWxsOiR7dGhlVHlwZS5ub3RpZmxpeEljb25Db2xvcn07fTwvc3R5bGU+PC9kZWZzPjxnPjxwYXRoIGlkPVwiTm90aWZsaXgtSWNvbi1JbmZvXCIgY2xhc3M9XCJmaWwwXCIgZD1cIk0yMCAwYzExLjAzLDAgMjAsOC45NyAyMCwyMCAwLDExLjAzIC04Ljk3LDIwIC0yMCwyMCAtMTEuMDMsMCAtMjAsLTguOTcgLTIwLC0yMCAwLC0xMS4wMyA4Ljk3LC0yMCAyMCwtMjB6bTAgMzcuOThjOS45MiwwIDE3Ljk4LC04LjA2IDE3Ljk4LC0xNy45OCAwLC05LjkyIC04LjA2LC0xNy45OCAtMTcuOTgsLTE3Ljk4IC05LjkyLDAgLTE3Ljk4LDguMDYgLTE3Ljk4LDE3Ljk4IDAsOS45MiA4LjA2LDE3Ljk4IDE3Ljk4LDE3Ljk4em0tMC45OSAtMjMuM2MwLC0wLjU0IDAuNDQsLTAuOTggMC45OSwtMC45OCAwLjU1LDAgMC45OSwwLjQ0IDAuOTksMC45OGwwIDE1Ljg2YzAsMC41NSAtMC40NCwwLjk5IC0wLjk5LDAuOTkgLTAuNTUsMCAtMC45OSwtMC40NCAtMC45OSwtMC45OWwwIC0xNS44NnptMCAtNS4yMmMwLC0wLjU1IDAuNDQsLTAuOTkgMC45OSwtMC45OSAwLjU1LDAgMC45OSwwLjQ0IDAuOTksMC45OWwwIDEuMDljMCwwLjU0IC0wLjQ0LDAuOTkgLTAuOTksMC45OSAtMC41NSwwIC0wLjk5LC0wLjQ1IC0wLjk5LC0wLjk5bDAgLTEuMDl6XCIvPjwvZz48L3N2Zz5gO1xyXG5cclxuICAgICAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgICAgICBudGZseE5vdGlmeS5pbm5lckhUTUwgPSBgJHtzdmdJY29ufTxzcGFuIGNsYXNzPVwidGhlLW1lc3NhZ2Ugd2l0aC1pY29uXCI+JHttZXNzYWdlfTwvc3Bhbj4keyhuZXdOb3RpZnlTZXR0aW5ncy5jbG9zZUJ1dHRvbiA/IGNsb3NlQnV0dG9uSFRNTCA6ICcnKX1gO1xyXG5cclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICB9IGVsc2UgeyAvLyB3aXRob3V0IGljb25cclxuXHJcbiAgICAgICAgICAgIG50Zmx4Tm90aWZ5LmlubmVySFRNTCA9IGA8c3BhbiBjbGFzcz1cInRoZS1tZXNzYWdlXCI+JHttZXNzYWdlfTwvc3Bhbj4gJHsobmV3Tm90aWZ5U2V0dGluZ3MuY2xvc2VCdXR0b24gPyBjbG9zZUJ1dHRvbkhUTUwgOiAnJyl9YDtcclxuICAgICAgICB9XHJcbiAgICAgICAgLy8gdXNlIGljb24gb2ZmXHJcbiAgICAgICAgLy8gbm90aWZ5IGNvbnRlbnQgb2ZmXHJcblxyXG5cclxuICAgICAgICAvLyBub3RpZnkgYXBwZW5kIG9yIHByZXBlbmQgb25cclxuICAgICAgICBjb25zdCBub3RpZnlXcmFwRWxlbWVudCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKG50Zmx4Tm90aWZ5V3JhcC5pZCk7XHJcbiAgICAgICAgaWYgKG5ld05vdGlmeVNldHRpbmdzLnBvc2l0aW9uID09PSAnbGVmdC1ib3R0b20nIHx8IG5ld05vdGlmeVNldHRpbmdzLnBvc2l0aW9uID09PSAncmlnaHQtYm90dG9tJykgeyAvLyB0aGUgbmV3IG9uZSB3aWxsIGJlIGZpcnN0XHJcblxyXG4gICAgICAgICAgICBub3RpZnlXcmFwRWxlbWVudC5pbnNlcnRCZWZvcmUobnRmbHhOb3RpZnksIG5vdGlmeVdyYXBFbGVtZW50LmZpcnN0Q2hpbGQpO1xyXG4gICAgICAgIH0gZWxzZSB7XHJcblxyXG4gICAgICAgICAgICBub3RpZnlXcmFwRWxlbWVudC5hcHBlbmRDaGlsZChudGZseE5vdGlmeSk7XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICBpZiAobmV3Tm90aWZ5U2V0dGluZ3MudXNlSWNvbikgeyAvLyBpZiB1c2VJY29uLCBkeW5hbWljYWxseSB2ZXJ0aWNhbCBhbGlnbiB0aGUgY29udGVudHNcclxuXHJcbiAgICAgICAgICAgIGxldCBtZXNzYWdlSWNvbiA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKG50Zmx4Tm90aWZ5LmlkKS5xdWVyeVNlbGVjdG9yQWxsKCcubm1pJylbMF07XHJcbiAgICAgICAgICAgIGxldCBtZXNzYWdlSWNvbkggPSA0MDtcclxuXHJcbiAgICAgICAgICAgIGlmIChuZXdOb3RpZnlTZXR0aW5ncy51c2VGb250QXdlc29tZSkgeyAvLyBpZiBmb250IGF3ZXNvbWVcclxuXHJcbiAgICAgICAgICAgICAgICBtZXNzYWdlSWNvbkggPSBNYXRoLnJvdW5kKHBhcnNlSW50KG1lc3NhZ2VJY29uLm9mZnNldEhlaWdodCkpO1xyXG5cclxuICAgICAgICAgICAgfSBlbHNlIHsgLy8gaWYgbm90aWZsaXggU1ZHXHJcblxyXG4gICAgICAgICAgICAgICAgbGV0IFN2Z0JCb3ggPSBtZXNzYWdlSWNvbi5nZXRCQm94KCk7XHJcbiAgICAgICAgICAgICAgICBtZXNzYWdlSWNvbkggPSBNYXRoLnJvdW5kKHBhcnNlSW50KFN2Z0JCb3gud2lkdGgpKTtcclxuXHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGxldCBtZXNzYWdlVGV4dCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKG50Zmx4Tm90aWZ5LmlkKS5xdWVyeVNlbGVjdG9yQWxsKCdzcGFuJylbMF07XHJcbiAgICAgICAgICAgIGxldCBtZXNzYWdlVGV4dEggPSBNYXRoLnJvdW5kKG1lc3NhZ2VUZXh0Lm9mZnNldEhlaWdodCk7XHJcblxyXG4gICAgICAgICAgICBpZiAobWVzc2FnZVRleHRIIDw9IG1lc3NhZ2VJY29uSCkge1xyXG4gICAgICAgICAgICAgICAgbGV0IHBhZGRpbmdWYWwgPSBgJHtwYXJzZUludCgobWVzc2FnZUljb25IIC0gbWVzc2FnZVRleHRIKSAvIDIpLnRvU3RyaW5nKCl9cHhgO1xyXG4gICAgICAgICAgICAgICAgbWVzc2FnZVRleHQuc3R5bGUucGFkZGluZ1RvcCA9IHBhZGRpbmdWYWw7XHJcbiAgICAgICAgICAgICAgICBtZXNzYWdlVGV4dC5zdHlsZS5wYWRkaW5nQm90dG9tID0gcGFkZGluZ1ZhbDtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICB9XHJcbiAgICAgICAgLy8gbm90aWZ5IGFwcGVuZCBvciBwcmVwZW5kIG9mZlxyXG5cclxuXHJcbiAgICAgICAgLy8gcmVtb3ZlIGJ5IHRpbWVvdXQgb3IgY2xpY2sgb25cclxuICAgICAgICBpZiAoZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQobnRmbHhOb3RpZnkuaWQpKSB7XHJcblxyXG4gICAgICAgICAgICAvLyBzZXQgZWxlbWVudHMgb25cclxuICAgICAgICAgICAgbGV0IHJlbW92ZURpdiA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKG50Zmx4Tm90aWZ5LmlkKTtcclxuICAgICAgICAgICAgbGV0IHJlbW92ZVdyYXAgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZChudGZseE5vdGlmeVdyYXAuaWQpO1xyXG4gICAgICAgICAgICBsZXQgcmVtb3ZlT3ZlcmxheTtcclxuICAgICAgICAgICAgaWYgKG5ld05vdGlmeVNldHRpbmdzLmJhY2tPdmVybGF5KSB7XHJcbiAgICAgICAgICAgICAgICByZW1vdmVPdmVybGF5ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQobm90aWZ5T3ZlcmxheS5pZCk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgLy8gc2V0IGVsZW1lbnRzIG9uXHJcblxyXG4gICAgICAgICAgICAvLyB0aW1lb3V0IHZhcnMgb25cclxuICAgICAgICAgICAgbGV0IHRpbWVvdXRBZGRDbGFzcztcclxuICAgICAgICAgICAgbGV0IHRpbWVvdXRSZW1vdmU7XHJcbiAgICAgICAgICAgIC8vIHRpbWVvdXQgdmFycyBvZmZcclxuXHJcbiAgICAgICAgICAgIC8vIHRpbWVvdXQgaWYgY2xpY2sgdG8gY2xvc2UgZmFsc2UgYW5kIGNhbGxiYWNrIHVuZGVmaW5lZCBvblxyXG4gICAgICAgICAgICBpZiAoIW5ld05vdGlmeVNldHRpbmdzLmNsb3NlQnV0dG9uICYmICFjYWxsYmFjaykge1xyXG4gICAgICAgICAgICAgICAgdGltZW91dEFkZENsYXNzID0gc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIHJlbW92ZURpdi5jbGFzc0xpc3QuYWRkKCdyZW1vdmUnKTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKG5ld05vdGlmeVNldHRpbmdzLmJhY2tPdmVybGF5ICYmIHJlbW92ZVdyYXAuY2hpbGRFbGVtZW50Q291bnQgPD0gMCkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICByZW1vdmVPdmVybGF5LmNsYXNzTGlzdC5hZGQoJ3JlbW92ZScpO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgICAgICB9LCBuZXdOb3RpZnlTZXR0aW5ncy50aW1lb3V0KTtcclxuXHJcbiAgICAgICAgICAgICAgICB0aW1lb3V0UmVtb3ZlID0gc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgcmVtb3ZlRGl2LnBhcmVudE5vZGUucmVtb3ZlQ2hpbGQocmVtb3ZlRGl2KTtcclxuICAgICAgICAgICAgICAgICAgICBpZiAocmVtb3ZlV3JhcC5jaGlsZEVsZW1lbnRDb3VudCA8PSAwKSB7IC8vIGlmIGNoaWxkcyBjb3VudCA9PT0gMCByZW1vdmUgd3JhcFxyXG4gICAgICAgICAgICAgICAgICAgICAgICByZW1vdmVXcmFwLnBhcmVudE5vZGUucmVtb3ZlQ2hpbGQocmVtb3ZlV3JhcCk7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICBpZiAobmV3Tm90aWZ5U2V0dGluZ3MuYmFja092ZXJsYXkpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHJlbW92ZU92ZXJsYXkucGFyZW50Tm9kZS5yZW1vdmVDaGlsZChyZW1vdmVPdmVybGF5KTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH0sIG5ld05vdGlmeVNldHRpbmdzLnRpbWVvdXQgKyBuZXdOb3RpZnlTZXR0aW5ncy5jc3NBbmltYXRpb25EdXJhdGlvbik7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgLy8gdGltZW91dCBpZiBjbGljayB0byBjbG9zZSBmYWxzZSBhbmQgY2FsbGJhY2sgdW5kZWZpbmVkIG9mZlxyXG5cclxuICAgICAgICAgICAgLy8gaWYgY2xpY2sgdG8gY2xvc2Ugb24gICAgICAgICAgICBcclxuICAgICAgICAgICAgaWYgKG5ld05vdGlmeVNldHRpbmdzLmNsb3NlQnV0dG9uICYmICFjYWxsYmFjaykge1xyXG5cclxuICAgICAgICAgICAgICAgIGxldCBjbG9zZUJ1dHRvbkVsbSA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKG50Zmx4Tm90aWZ5LmlkKS5xdWVyeVNlbGVjdG9yQWxsKCdzcGFuLmNsaWNrLXRvLWNsb3NlJylbMF07XHJcblxyXG4gICAgICAgICAgICAgICAgY2xvc2VCdXR0b25FbG0uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBmdW5jdGlvbiAoKSB7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIHJlbW92ZURpdi5jbGFzc0xpc3QuYWRkKCdyZW1vdmUnKTtcclxuICAgICAgICAgICAgICAgICAgICBjbGVhclRpbWVvdXQodGltZW91dEFkZENsYXNzKTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKG5ld05vdGlmeVNldHRpbmdzLmJhY2tPdmVybGF5ICYmIHJlbW92ZVdyYXAuY2hpbGRFbGVtZW50Q291bnQgPD0gMSkgeyAvLyBpZiBsYXN0IGNoaWxkIC0gYWRkQ2xhc3MgUmVtb3ZlIHRvIE92ZXJsYXlcclxuICAgICAgICAgICAgICAgICAgICAgICAgcmVtb3ZlT3ZlcmxheS5jbGFzc0xpc3QuYWRkKCdyZW1vdmUnKTtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICByZW1vdmVEaXYucGFyZW50Tm9kZS5yZW1vdmVDaGlsZChyZW1vdmVEaXYpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBjbGVhclRpbWVvdXQodGltZW91dFJlbW92ZSk7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICBpZiAocmVtb3ZlV3JhcC5jaGlsZEVsZW1lbnRDb3VudCA8PSAwKSB7IC8vIGlmIGNoaWxkcyBjb3VudCA9PT0gMCByZW1vdmUgd3JhcFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgcmVtb3ZlV3JhcC5wYXJlbnROb2RlLnJlbW92ZUNoaWxkKHJlbW92ZVdyYXApOyAvLyByZW1vdmUgd3JhcFxyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmIChuZXdOb3RpZnlTZXR0aW5ncy5iYWNrT3ZlcmxheSkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHJlbW92ZU92ZXJsYXkucGFyZW50Tm9kZS5yZW1vdmVDaGlsZChyZW1vdmVPdmVybGF5KTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgICAgICAgICB9LCBuZXdOb3RpZnlTZXR0aW5ncy5jc3NBbmltYXRpb25EdXJhdGlvbik7XHJcblxyXG4gICAgICAgICAgICAgICAgfSk7XHJcblxyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIC8vIGlmIGNsaWNrIHRvIGNsb3NlIG9mZlxyXG5cclxuXHJcbiAgICAgICAgICAgIC8vIGNhbGxiYWNrIG9uXHJcbiAgICAgICAgICAgIGlmIChjYWxsYmFjayAmJiB0eXBlb2YgY2FsbGJhY2sgPT09ICdmdW5jdGlvbicpIHtcclxuXHJcbiAgICAgICAgICAgICAgICByZW1vdmVEaXYuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBmdW5jdGlvbiAoZSkge1xyXG5cclxuICAgICAgICAgICAgICAgICAgICBjYWxsYmFjaygpOyAvLyBjYWxsYmFja1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAvLyByZW1vdmUgZWxlbWVudCBvblxyXG4gICAgICAgICAgICAgICAgICAgIHJlbW92ZURpdi5jbGFzc0xpc3QuYWRkKCdyZW1vdmUnKTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKG5ld05vdGlmeVNldHRpbmdzLmJhY2tPdmVybGF5ICYmIHJlbW92ZVdyYXAuY2hpbGRFbGVtZW50Q291bnQgPD0gMCkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICByZW1vdmVPdmVybGF5LmNsYXNzTGlzdC5hZGQoJ3JlbW92ZScpO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgICAgICAgICAgY2xlYXJUaW1lb3V0KHRpbWVvdXRBZGRDbGFzcyk7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgcmVtb3ZlRGl2LnBhcmVudE5vZGUucmVtb3ZlQ2hpbGQocmVtb3ZlRGl2KTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmIChyZW1vdmVXcmFwLmNoaWxkRWxlbWVudENvdW50IDw9IDApIHsgLy8gaWYgY2hpbGRzIGNvdW50ID09PSAwIHJlbW92ZSB3cmFwXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICByZW1vdmVXcmFwLnBhcmVudE5vZGUucmVtb3ZlQ2hpbGQocmVtb3ZlV3JhcCk7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgaWYgKG5ld05vdGlmeVNldHRpbmdzLmJhY2tPdmVybGF5KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgcmVtb3ZlT3ZlcmxheS5wYXJlbnROb2RlLnJlbW92ZUNoaWxkKHJlbW92ZU92ZXJsYXkpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGNsZWFyVGltZW91dCh0aW1lb3V0UmVtb3ZlKTtcclxuICAgICAgICAgICAgICAgICAgICB9LCBuZXdOb3RpZnlTZXR0aW5ncy5jc3NBbmltYXRpb25EdXJhdGlvbik7XHJcbiAgICAgICAgICAgICAgICAgICAgLy8gcmVtb3ZlIGVsZW1lbnQgb2ZmXHJcblxyXG4gICAgICAgICAgICAgICAgfSk7XHJcblxyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIC8vIGNhbGxiYWNrIG9mZlxyXG5cclxuICAgICAgICB9XHJcbiAgICAgICAgLy8gcmVtb3ZlIGJ5IHRpbWVvdXQgb3IgY2xpY2sgb2ZmXHJcblxyXG4gICAgfSBlbHNlIHtcclxuICAgICAgICBub3RpZmxpeENvbnNvbGVFcnJvcignTm90aWZsaXggRXJyb3InLCAnV2hlcmUgaXMgdGhlIGFyZ3VtZW50cz8nKTtcclxuICAgIH1cclxuXHJcbn1cclxuLy8gTm90aWZsaXg6IE5vdGlmeSBTaW5nbGUgb2ZmXHJcblxyXG5cclxuLy8gTm90aWZsaXg6IFJlcG9ydCBTaW5nbGUgb25cclxuY29uc3QgTm90aWZsaXhSZXBvcnQgPSBmdW5jdGlvbiAodGl0bGUsIG1lc3NhZ2UsIGJ1dHRvblRleHQsIGJ1dHRvbkNhbGxiYWNrLCB0aGVUeXBlLCBzdGF0aWNUeXBlKSB7XHJcblxyXG4gICAgLy8gaWYgcGxhaW5UZXh0IHRydWUgPSBIVE1MIHRhZ3Mgbm90IGFsbG93ZWQgb25cclxuICAgIGlmIChuZXdSZXBvcnRTZXR0aW5ncy5wbGFpblRleHQpIHtcclxuICAgICAgICB0aXRsZSA9IG5vdGlmbGl4UGxhaW50ZXh0KHRpdGxlKTtcclxuICAgICAgICBtZXNzYWdlID0gbm90aWZsaXhQbGFpbnRleHQobWVzc2FnZSk7XHJcbiAgICAgICAgYnV0dG9uVGV4dCA9IG5vdGlmbGl4UGxhaW50ZXh0KGJ1dHRvblRleHQpO1xyXG4gICAgfVxyXG4gICAgLy8gaWYgcGxhaW5UZXh0IHRydWUgPSBIVE1MIHRhZ3Mgbm90IGFsbG93ZWQgb2ZmXHJcblxyXG4gICAgLy8gaWYgcGxhaW5UZXh0IGZhbHNlIGJ1dCB0aGUgY29udGVudHMgbGVuZ3RoIG1vcmUgdGhhbiAqTWF4TGVuZ3RoID0gSFRNTCB0YWdzIGVycm9yIG9uXHJcbiAgICBpZiAoIW5ld1JlcG9ydFNldHRpbmdzLnBsYWluVGV4dCkge1xyXG4gICAgICAgIGlmICh0aXRsZS5sZW5ndGggPiBuZXdSZXBvcnRTZXR0aW5ncy50aXRsZU1heExlbmd0aCkge1xyXG4gICAgICAgICAgICB0aXRsZSA9ICdIVE1MIFRhZ3MgRXJyb3InOyAvLyB0aXRsZSBodG1sIGVycm9yXHJcbiAgICAgICAgICAgIG1lc3NhZ2UgPSAnWW91ciBUaXRsZSBjb250ZW50IGxlbmd0aCBpcyBtb3JlIHRoYW4gXCJ0aXRsZU1heExlbmd0aFwiIG9wdGlvbi4nOyAvLyBtZXNzYWdlIGh0bWwgZXJyb3JcclxuICAgICAgICAgICAgYnV0dG9uVGV4dCA9ICdPa2F5JzsgLy8gYnV0dG9uIGh0bWwgZXJyb3JcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIGlmIChtZXNzYWdlLmxlbmd0aCA+IG5ld1JlcG9ydFNldHRpbmdzLm1lc3NhZ2VNYXhMZW5ndGgpIHtcclxuICAgICAgICAgICAgdGl0bGUgPSAnSFRNTCBUYWdzIEVycm9yJzsgLy8gdGl0bGUgaHRtbCBlcnJvclxyXG4gICAgICAgICAgICBtZXNzYWdlID0gJ1lvdXIgTWVzc2FnZSBjb250ZW50IGxlbmd0aCBpcyBtb3JlIHRoYW4gXCJtZXNzYWdlTWF4TGVuZ3RoXCIgb3B0aW9uLic7IC8vIG1lc3NhZ2UgaHRtbCBlcnJvclxyXG4gICAgICAgICAgICBidXR0b25UZXh0ID0gJ09rYXknOyAvLyBidXR0b24gaHRtbCBlcnJvclxyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgaWYgKGJ1dHRvblRleHQubGVuZ3RoID4gbmV3UmVwb3J0U2V0dGluZ3MuYnV0dG9uTWF4TGVuZ3RoKSB7XHJcbiAgICAgICAgICAgIHRpdGxlID0gJ0hUTUwgVGFncyBFcnJvcic7IC8vIHRpdGxlIGh0bWwgZXJyb3JcclxuICAgICAgICAgICAgbWVzc2FnZSA9ICdZb3VyIEJ1dHRvbiBjb250ZW50IGxlbmd0aCBpcyBtb3JlIHRoYW4gXCJidXR0b25NYXhMZW5ndGhcIiBvcHRpb24uJzsgLy8gbWVzc2FnZSBodG1sIGVycm9yXHJcbiAgICAgICAgICAgIGJ1dHRvblRleHQgPSAnT2theSc7IC8vIGJ1dHRvbiBodG1sIGVycm9yXHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG4gICAgLy8gaWYgcGxhaW5UZXh0IGZhbHNlIGJ1dCB0aGUgY29udGVudHMgbGVuZ3RoIG1vcmUgdGhhbiAqTWF4TGVuZ3RoID0gSFRNTCB0YWdzIGVycm9yIG9mZlxyXG5cclxuXHJcbiAgICAvLyBtYXggbGVuZ3RoIG9uXHJcbiAgICBpZiAodGl0bGUubGVuZ3RoID4gbmV3UmVwb3J0U2V0dGluZ3MudGl0bGVNYXhMZW5ndGgpIHtcclxuICAgICAgICB0aXRsZSA9IGAke3RpdGxlLnN1YnN0cmluZygwLCBuZXdSZXBvcnRTZXR0aW5ncy50aXRsZU1heExlbmd0aCl9Li4uYDtcclxuICAgIH1cclxuXHJcbiAgICBpZiAobWVzc2FnZS5sZW5ndGggPiBuZXdSZXBvcnRTZXR0aW5ncy5tZXNzYWdlTWF4TGVuZ3RoKSB7XHJcbiAgICAgICAgbWVzc2FnZSA9IGAke21lc3NhZ2Uuc3Vic3RyaW5nKDAsIG5ld1JlcG9ydFNldHRpbmdzLm1lc3NhZ2VNYXhMZW5ndGgpfS4uLmA7XHJcbiAgICB9XHJcblxyXG4gICAgaWYgKGJ1dHRvblRleHQubGVuZ3RoID4gbmV3UmVwb3J0U2V0dGluZ3MuYnV0dG9uTWF4TGVuZ3RoKSB7XHJcbiAgICAgICAgYnV0dG9uVGV4dCA9IGAke2J1dHRvblRleHQuc3Vic3RyaW5nKDAsIG5ld1JlcG9ydFNldHRpbmdzLmJ1dHRvbk1heExlbmd0aCl9Li4uYDtcclxuICAgIH1cclxuICAgIC8vIG1heCBsZW5ndGggb2ZmXHJcblxyXG4gICAgLy8gaWYgY3NzQW5pbWFpb24gZmFsc2UgLT4gZHVyYXRpb24gb25cclxuICAgIGlmICghbmV3UmVwb3J0U2V0dGluZ3MuY3NzQW5pbWF0aW9uKSB7XHJcbiAgICAgICAgbmV3UmVwb3J0U2V0dGluZ3MuY3NzQW5pbWF0aW9uRHVyYXRpb24gPSAwO1xyXG4gICAgfVxyXG4gICAgLy8gaWYgY3NzQW5pbWFpb24gZmFsc2UgLT4gZHVyYXRpb24gb2ZmXHJcblxyXG4gICAgLy8gcmVwb3J0IHdyYXAgb25cclxuICAgIGNvbnN0IGRvY0JvZHkgPSBkb2N1bWVudC5ib2R5O1xyXG5cclxuICAgIGNvbnN0IG50Zmx4UmVwb3J0V3JhcCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xyXG4gICAgbnRmbHhSZXBvcnRXcmFwLmlkID0gcmVwb3J0U2V0dGluZ3MuSUQ7XHJcbiAgICBudGZseFJlcG9ydFdyYXAuY2xhc3NOYW1lID0gbmV3UmVwb3J0U2V0dGluZ3MuY2xhc3NOYW1lO1xyXG4gICAgbnRmbHhSZXBvcnRXcmFwLnN0eWxlLndpZHRoID0gbmV3UmVwb3J0U2V0dGluZ3Mud2lkdGg7XHJcbiAgICBudGZseFJlcG9ydFdyYXAuc3R5bGUuekluZGV4ID0gbmV3UmVwb3J0U2V0dGluZ3MuemluZGV4O1xyXG4gICAgbnRmbHhSZXBvcnRXcmFwLnN0eWxlLmJvcmRlclJhZGl1cyA9IG5ld1JlcG9ydFNldHRpbmdzLmJvcmRlclJhZGl1cztcclxuXHJcbiAgICAvLyBmb250LWZhbWlseSBvblxyXG4gICAgbnRmbHhSZXBvcnRXcmFwLnN0eWxlLmZvbnRGYW1pbHkgPSBgXCIke25ld1JlcG9ydFNldHRpbmdzLmZvbnRGYW1pbHl9XCIsIHNhbnMtc2VyaWZgO1xyXG4gICAgLy8gZm9udC1mYW1pbHkgb2ZmXHJcblxyXG4gICAgLy8gcnRsIG9uXHJcbiAgICBpZiAobmV3UmVwb3J0U2V0dGluZ3MucnRsKSB7XHJcbiAgICAgICAgbnRmbHhSZXBvcnRXcmFwLnNldEF0dHJpYnV0ZSgnZGlyJywgJ3J0bCcpO1xyXG4gICAgICAgIG50Zmx4UmVwb3J0V3JhcC5jbGFzc0xpc3QuYWRkKCdydGwtb24nKTtcclxuICAgIH1cclxuICAgIC8vIHJ0bCBvZmZcclxuXHJcbiAgICAvLyBvdmVybGF5IG9uXHJcbiAgICBsZXQgcmVwb3J0T3ZlcmxheSA9ICcnO1xyXG4gICAgaWYgKG5ld1JlcG9ydFNldHRpbmdzLmJhY2tPdmVybGF5KSB7XHJcbiAgICAgICAgcmVwb3J0T3ZlcmxheSA9IGA8ZGl2IGNsYXNzPVwiJHtuZXdSZXBvcnRTZXR0aW5ncy5jbGFzc05hbWV9LW92ZXJsYXkgJHsobmV3UmVwb3J0U2V0dGluZ3MuY3NzQW5pbWF0aW9uID8gJ3dpdGgtYW5pbWF0aW9uJyA6ICcnKX1cIiBzdHlsZT1cImJhY2tncm91bmQ6JHtuZXdSZXBvcnRTZXR0aW5ncy5iYWNrT3ZlcmxheUNvbG9yfTsgYW5pbWF0aW9uLWR1cmF0aW9uOiR7bmV3UmVwb3J0U2V0dGluZ3MuY3NzQW5pbWF0aW9uRHVyYXRpb259bXM7XCI+PC9kaXY+YDtcclxuICAgIH1cclxuICAgIC8vIG92ZXJsYXkgb2ZmXHJcblxyXG5cclxuICAgIC8vIHN2ZyBpY29uIG9uXHJcbiAgICBsZXQgc3ZnSWNvbiA9ICcnO1xyXG4gICAgaWYgKHN0YXRpY1R5cGUgPT09ICdzdWNjZXNzJykge1xyXG4gICAgICAgIHN2Z0ljb24gPSBub3RpZmxpeFJlcG9ydFN2Z1N1Y2Nlc3MobmV3UmVwb3J0U2V0dGluZ3Muc3ZnU2l6ZSwgdGhlVHlwZS5zdmdDb2xvcik7XHJcbiAgICB9IGVsc2UgaWYgKHN0YXRpY1R5cGUgPT09ICdmYWlsdXJlJykge1xyXG4gICAgICAgIHN2Z0ljb24gPSBub3RpZmxpeFJlcG9ydFN2Z0ZhaWx1cmUobmV3UmVwb3J0U2V0dGluZ3Muc3ZnU2l6ZSwgdGhlVHlwZS5zdmdDb2xvcik7XHJcbiAgICB9IGVsc2UgaWYgKHN0YXRpY1R5cGUgPT09ICd3YXJuaW5nJykge1xyXG4gICAgICAgIHN2Z0ljb24gPSBub3RpZmxpeFJlcG9ydFN2Z1dhcm5pbmcobmV3UmVwb3J0U2V0dGluZ3Muc3ZnU2l6ZSwgdGhlVHlwZS5zdmdDb2xvcik7XHJcbiAgICB9IGVsc2UgaWYgKHN0YXRpY1R5cGUgPT09ICdpbmZvJykge1xyXG4gICAgICAgIHN2Z0ljb24gPSBub3RpZmxpeFJlcG9ydFN2Z0luZm8obmV3UmVwb3J0U2V0dGluZ3Muc3ZnU2l6ZSwgdGhlVHlwZS5zdmdDb2xvcik7XHJcbiAgICB9XHJcbiAgICAvLyBzdmcgaWNvbiBvZmZcclxuXHJcbiAgICAvLyByZXBvcnQgaHRtbCBvblxyXG4gICAgbnRmbHhSZXBvcnRXcmFwLmlubmVySFRNTCA9IGAke3JlcG9ydE92ZXJsYXl9XHJcbiAgICA8ZGl2IGNsYXNzPVwiJHtuZXdSZXBvcnRTZXR0aW5ncy5jbGFzc05hbWV9LWNvbnRlbnQgJHsobmV3UmVwb3J0U2V0dGluZ3MuY3NzQW5pbWF0aW9uID8gJ3dpdGgtYW5pbWF0aW9uJyA6ICcnKX0gbngtJHtuZXdSZXBvcnRTZXR0aW5ncy5jc3NBbmltYXRpb25TdHlsZX1cIiBzdHlsZT1cImJhY2tncm91bmQ6JHtuZXdSZXBvcnRTZXR0aW5ncy5iYWNrZ3JvdW5kQ29sb3J9OyBhbmltYXRpb24tZHVyYXRpb246JHtuZXdSZXBvcnRTZXR0aW5ncy5jc3NBbmltYXRpb25EdXJhdGlvbn1tcztcIj5cclxuICAgIDxkaXYgc3R5bGU9XCJ3aWR0aDoke25ld1JlcG9ydFNldHRpbmdzLnN2Z1NpemV9OyBoZWlnaHQ6JHtuZXdSZXBvcnRTZXR0aW5ncy5zdmdTaXplfTtcIiBjbGFzcz1cIiR7bmV3UmVwb3J0U2V0dGluZ3MuY2xhc3NOYW1lfS1pY29uXCI+JHtzdmdJY29ufTwvZGl2PlxyXG4gICAgPGg1IGNsYXNzPVwiJHtuZXdSZXBvcnRTZXR0aW5ncy5jbGFzc05hbWV9LXRpdGxlXCIgc3R5bGU9XCJmb250LXdlaWdodDo1MDA7IGZvbnQtc2l6ZToke25ld1JlcG9ydFNldHRpbmdzLnRpdGxlRm9udFNpemV9OyBjb2xvcjoke3RoZVR5cGUudGl0bGVDb2xvcn07XCI+JHt0aXRsZX08L2g1PlxyXG4gICAgPHAgY2xhc3M9XCIke25ld1JlcG9ydFNldHRpbmdzLmNsYXNzTmFtZX0tbWVzc2FnZVwiIHN0eWxlPVwiZm9udC1zaXplOiR7bmV3UmVwb3J0U2V0dGluZ3MubWVzc2FnZUZvbnRTaXplfTsgY29sb3I6JHt0aGVUeXBlLm1lc3NhZ2VDb2xvcn07XCI+JHttZXNzYWdlfTwvcD5cclxuICAgIDxhIGlkPVwiTlhSZXBvcnRCdXR0b25cIiBjbGFzcz1cIiR7bmV3UmVwb3J0U2V0dGluZ3MuY2xhc3NOYW1lfS1idXR0b25cIiBzdHlsZT1cImZvbnQtd2VpZ2h0OjUwMDsgZm9udC1zaXplOiR7bmV3UmVwb3J0U2V0dGluZ3MuYnV0dG9uRm9udFNpemV9OyBiYWNrZ3JvdW5kOiR7dGhlVHlwZS5idXR0b25CYWNrZ3JvdW5kfTsgY29sb3I6JHt0aGVUeXBlLmJ1dHRvbkNvbG9yfTtcIj4ke2J1dHRvblRleHR9PC9hPlxyXG4gICAgPC9kaXY+YDtcclxuICAgIC8vIHJlcG9ydCBodG1sIG9mZlxyXG5cclxuICAgIGlmICghZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQobnRmbHhSZXBvcnRXcmFwLmlkKSkgeyAvLyBpZiBubyByZXBvcnRcclxuXHJcbiAgICAgICAgZG9jQm9keS5hcHBlbmRDaGlsZChudGZseFJlcG9ydFdyYXApOyAvLyBhcHBlbmRcclxuXHJcbiAgICAgICAgLy8gdmVydGljYWwgYWxpZ24gb24gICAgICAgICAgICBcclxuICAgICAgICBsZXQgd2luZG93SCA9IE1hdGgucm91bmQod2luZG93LmlubmVySGVpZ2h0KTtcclxuICAgICAgICBsZXQgcmVwb3J0SCA9IE1hdGgucm91bmQoZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQobnRmbHhSZXBvcnRXcmFwLmlkKS5vZmZzZXRIZWlnaHQpO1xyXG4gICAgICAgIG50Zmx4UmVwb3J0V3JhcC5zdHlsZS50b3AgPSBgJHtwYXJzZUludCgod2luZG93SCAtIHJlcG9ydEgpIC8gMikudG9TdHJpbmcoKX1weGA7XHJcbiAgICAgICAgLy8gdmVydGljYWwgYWxpZ24gb2ZmXHJcblxyXG4gICAgICAgIC8vIGNhbGxiYWNrIG9uXHJcbiAgICAgICAgbGV0IGdldFJlcG9ydFdyYXAgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZChudGZseFJlcG9ydFdyYXAuaWQpO1xyXG4gICAgICAgIGxldCByZXBvcnRCdXR0b24gPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnTlhSZXBvcnRCdXR0b24nKTtcclxuICAgICAgICByZXBvcnRCdXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBmdW5jdGlvbiAoKSB7XHJcblxyXG4gICAgICAgICAgICAvLyBpZiBjYWxsYmFjayBvblxyXG4gICAgICAgICAgICBpZiAoYnV0dG9uQ2FsbGJhY2sgJiYgdHlwZW9mIGJ1dHRvbkNhbGxiYWNrID09PSAnZnVuY3Rpb24nKSB7XHJcbiAgICAgICAgICAgICAgICBidXR0b25DYWxsYmFjaygpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIC8vIGlmIGNhbGxiYWNrIG9mZlxyXG5cclxuICAgICAgICAgICAgLy8gcmVtb3ZlIGVsZW1lbnQgb25cclxuICAgICAgICAgICAgZ2V0UmVwb3J0V3JhcC5jbGFzc0xpc3QuYWRkKCdyZW1vdmUnKTtcclxuXHJcbiAgICAgICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAgICAgZ2V0UmVwb3J0V3JhcC5wYXJlbnROb2RlLnJlbW92ZUNoaWxkKGdldFJlcG9ydFdyYXApO1xyXG4gICAgICAgICAgICB9LCBuZXdSZXBvcnRTZXR0aW5ncy5jc3NBbmltYXRpb25EdXJhdGlvbik7XHJcbiAgICAgICAgICAgIC8vIHJlbW92ZSBlbGVtZW50IG9mZlxyXG5cclxuICAgICAgICB9KTtcclxuICAgICAgICAvLyBjYWxsYmFjayBvZmZcclxuXHJcbiAgICB9XHJcbiAgICAvLyByZXBvcnQgd3JhcCBvZmZcclxuXHJcbn1cclxuLy8gTm90aWZsaXg6IFJlcG9ydCBTaW5nbGUgb2ZmXHJcblxyXG4vLyBOb3RpZmxpeDogUmVwb3J0IFNWRyBTdWNjZXNzIG9uXHJcbmNvbnN0IG5vdGlmbGl4UmVwb3J0U3ZnU3VjY2VzcyA9IGZ1bmN0aW9uICh3aWR0aCwgY29sb3IpIHtcclxuXHJcbiAgICBpZiAoIXdpZHRoKSB7IHdpZHRoID0gJzExMHB4JzsgfVxyXG4gICAgaWYgKCFjb2xvcikgeyBjb2xvciA9ICcjMDBiNDYyJzsgfVxyXG5cclxuICAgIGNvbnN0IHJlcG9ydFN2Z1N1Y2Nlc3MgPSBgPHN2ZyBpZD1cIk5YUmVwb3J0U3VjY2Vzc1wiIGZpbGw9XCIke2NvbG9yfVwiIHdpZHRoPVwiJHt3aWR0aH1cIiBoZWlnaHQ9XCIke3dpZHRofVwiIHhtbG5zPVwiaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmdcIiB4bWw6c3BhY2U9XCJwcmVzZXJ2ZVwiIHZlcnNpb249XCIxLjFcIiBzdHlsZT1cInNoYXBlLXJlbmRlcmluZzpnZW9tZXRyaWNQcmVjaXNpb247IHRleHQtcmVuZGVyaW5nOmdlb21ldHJpY1ByZWNpc2lvbjsgaW1hZ2UtcmVuZGVyaW5nOm9wdGltaXplUXVhbGl0eTsgZmlsbC1ydWxlOmV2ZW5vZGQ7IGNsaXAtcnVsZTpldmVub2RkXCIgdmlld0JveD1cIjAgMCAxMjAgMTIwXCIgeG1sbnM6eGxpbms9XCJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rXCI+PHN0eWxlPkAtd2Via2l0LWtleWZyYW1lcyBOWFJlcG9ydFN1Y2Nlc3M1LWFuaW1hdGlvbnswJXstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDU3LjdweCkgc2NhbGUoMC41LCAwLjUpIHRyYW5zbGF0ZSgtNjBweCwgLTU3LjdweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNTcuN3B4KSBzY2FsZSgwLjUsIDAuNSkgdHJhbnNsYXRlKC02MHB4LCAtNTcuN3B4KTt9NTAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNTcuN3B4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC01Ny43cHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDU3LjdweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNTcuN3B4KTt9NjAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNTcuN3B4KSBzY2FsZSgwLjk1LCAwLjk1KSB0cmFuc2xhdGUoLTYwcHgsIC01Ny43cHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDU3LjdweCkgc2NhbGUoMC45NSwgMC45NSkgdHJhbnNsYXRlKC02MHB4LCAtNTcuN3B4KTt9MTAwJXstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDU3LjdweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNTcuN3B4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA1Ny43cHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTU3LjdweCk7fX1Aa2V5ZnJhbWVzIE5YUmVwb3J0U3VjY2VzczUtYW5pbWF0aW9uezAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNTcuN3B4KSBzY2FsZSgwLjUsIDAuNSkgdHJhbnNsYXRlKC02MHB4LCAtNTcuN3B4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA1Ny43cHgpIHNjYWxlKDAuNSwgMC41KSB0cmFuc2xhdGUoLTYwcHgsIC01Ny43cHgpO301MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA1Ny43cHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTU3LjdweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNTcuN3B4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC01Ny43cHgpO302MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA1Ny43cHgpIHNjYWxlKDAuOTUsIDAuOTUpIHRyYW5zbGF0ZSgtNjBweCwgLTU3LjdweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNTcuN3B4KSBzY2FsZSgwLjk1LCAwLjk1KSB0cmFuc2xhdGUoLTYwcHgsIC01Ny43cHgpO30xMDAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNTcuN3B4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC01Ny43cHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDU3LjdweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNTcuN3B4KTt9fUAtd2Via2l0LWtleWZyYW1lcyBOWFJlcG9ydFN1Y2Nlc3M2LWFuaW1hdGlvbnswJXtvcGFjaXR5OiAwO301MCV7b3BhY2l0eTogMTt9MTAwJXtvcGFjaXR5OiAxO319QGtleWZyYW1lcyBOWFJlcG9ydFN1Y2Nlc3M2LWFuaW1hdGlvbnswJXtvcGFjaXR5OiAwO301MCV7b3BhY2l0eTogMTt9MTAwJXtvcGFjaXR5OiAxO319QC13ZWJraXQta2V5ZnJhbWVzIE5YUmVwb3J0U3VjY2VzczQtYW5pbWF0aW9uezAle29wYWNpdHk6IDA7fTQwJXtvcGFjaXR5OiAxO30xMDAle29wYWNpdHk6IDE7fX1Aa2V5ZnJhbWVzIE5YUmVwb3J0U3VjY2VzczQtYW5pbWF0aW9uezAle29wYWNpdHk6IDA7fTQwJXtvcGFjaXR5OiAxO30xMDAle29wYWNpdHk6IDE7fX1ALXdlYmtpdC1rZXlmcmFtZXMgTlhSZXBvcnRTdWNjZXNzMy1hbmltYXRpb257MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgwLjUsIDAuNSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC41LCAwLjUpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO300MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9NjAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC45NSwgMC45NSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC45NSwgMC45NSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7fTEwMCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9fUBrZXlmcmFtZXMgTlhSZXBvcnRTdWNjZXNzMy1hbmltYXRpb257MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgwLjUsIDAuNSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC41LCAwLjUpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO300MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9NjAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC45NSwgMC45NSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC45NSwgMC45NSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7fTEwMCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9fSNOWFJlcG9ydFN1Y2Nlc3MgKnstd2Via2l0LWFuaW1hdGlvbi1kdXJhdGlvbjogMS4yczthbmltYXRpb24tZHVyYXRpb246IDEuMnM7LXdlYmtpdC1hbmltYXRpb24tdGltaW5nLWZ1bmN0aW9uOiBjdWJpYy1iZXppZXIoMCwgMCwgMSwgMSk7YW5pbWF0aW9uLXRpbWluZy1mdW5jdGlvbjogY3ViaWMtYmV6aWVyKDAsIDAsIDEsIDEpO30jTlhSZXBvcnRTdWNjZXNzNHtmaWxsOiBpbmhlcml0Oy13ZWJraXQtYW5pbWF0aW9uLW5hbWU6IE5YUmVwb3J0U3VjY2VzczQtYW5pbWF0aW9uO2FuaW1hdGlvbi1uYW1lOiBOWFJlcG9ydFN1Y2Nlc3M0LWFuaW1hdGlvbjstd2Via2l0LWFuaW1hdGlvbi10aW1pbmctZnVuY3Rpb246IGN1YmljLWJlemllcigwLjQyLCAwLCAwLjU4LCAxKTthbmltYXRpb24tdGltaW5nLWZ1bmN0aW9uOiBjdWJpYy1iZXppZXIoMC40MiwgMCwgMC41OCwgMSk7b3BhY2l0eTogMTt9I05YUmVwb3J0U3VjY2VzczZ7ZmlsbDogaW5oZXJpdDstd2Via2l0LWFuaW1hdGlvbi1uYW1lOiBOWFJlcG9ydFN1Y2Nlc3M2LWFuaW1hdGlvbjthbmltYXRpb24tbmFtZTogTlhSZXBvcnRTdWNjZXNzNi1hbmltYXRpb247b3BhY2l0eTogMTstd2Via2l0LWFuaW1hdGlvbi10aW1pbmctZnVuY3Rpb246IGN1YmljLWJlemllcigwLjQyLCAwLCAwLjU4LCAxKTthbmltYXRpb24tdGltaW5nLWZ1bmN0aW9uOiBjdWJpYy1iZXppZXIoMC40MiwgMCwgMC41OCwgMSk7fSNOWFJlcG9ydFN1Y2Nlc3Mzey13ZWJraXQtYW5pbWF0aW9uLW5hbWU6IE5YUmVwb3J0U3VjY2VzczMtYW5pbWF0aW9uO2FuaW1hdGlvbi1uYW1lOiBOWFJlcG9ydFN1Y2Nlc3MzLWFuaW1hdGlvbjstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpOy13ZWJraXQtYW5pbWF0aW9uLXRpbWluZy1mdW5jdGlvbjogY3ViaWMtYmV6aWVyKDAuNDIsIDAsIDAuNTgsIDEpO2FuaW1hdGlvbi10aW1pbmctZnVuY3Rpb246IGN1YmljLWJlemllcigwLjQyLCAwLCAwLjU4LCAxKTt9I05YUmVwb3J0U3VjY2VzczV7LXdlYmtpdC1hbmltYXRpb24tbmFtZTogTlhSZXBvcnRTdWNjZXNzNS1hbmltYXRpb247YW5pbWF0aW9uLW5hbWU6IE5YUmVwb3J0U3VjY2VzczUtYW5pbWF0aW9uOy13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNTcuN3B4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC01Ny43cHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDU3LjdweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNTcuN3B4KTstd2Via2l0LWFuaW1hdGlvbi10aW1pbmctZnVuY3Rpb246IGN1YmljLWJlemllcigwLjQyLCAwLCAwLjU4LCAxKTthbmltYXRpb24tdGltaW5nLWZ1bmN0aW9uOiBjdWJpYy1iZXppZXIoMC40MiwgMCwgMC41OCwgMSk7fTwvc3R5bGU+PGcgaWQ9XCJOWFJlcG9ydFN1Y2Nlc3MxXCI+PGcgaWQ9XCJOWFJlcG9ydFN1Y2Nlc3MyXCI+PGcgaWQ9XCJOWFJlcG9ydFN1Y2Nlc3MzXCIgZGF0YS1hbmltYXRvci1ncm91cD1cInRydWVcIiBkYXRhLWFuaW1hdG9yLXR5cGU9XCIyXCI+PHBhdGggZD1cIk02MCAxMTUuMzhjLTMwLjU0LDAgLTU1LjM4LC0yNC44NCAtNTUuMzgsLTU1LjM4IDAsLTMwLjU0IDI0Ljg0LC01NS4zOCA1NS4zOCwtNTUuMzggMzAuNTQsMCA1NS4zOCwyNC44NCA1NS4zOCw1NS4zOCAwLDMwLjU0IC0yNC44NCw1NS4zOCAtNTUuMzgsNTUuMzh6bTAgLTExNS4zOGMtMzMuMDgsMCAtNjAsMjYuOTIgLTYwLDYwIDAsMzMuMDggMjYuOTIsNjAgNjAsNjAgMzMuMDgsMCA2MCwtMjYuOTIgNjAsLTYwIDAsLTMzLjA4IC0yNi45MiwtNjAgLTYwLC02MHpcIiBpZD1cIk5YUmVwb3J0U3VjY2VzczRcIi8+PC9nPjxnIGlkPVwiTlhSZXBvcnRTdWNjZXNzNVwiIGRhdGEtYW5pbWF0b3ItZ3JvdXA9XCJ0cnVlXCIgZGF0YS1hbmltYXRvci10eXBlPVwiMlwiPjxwYXRoIGQ9XCJNODguMjcgMzUuMzlsLTM1LjQ3IDM5LjkgLTIxLjM3IC0xNy4wOWMtMC45OCwtMC44MSAtMi40NCwtMC42MyAtMy4yNCwwLjM2IC0wLjc5LDAuOTkgLTAuNjMsMi40NCAwLjM2LDMuMjRsMjMuMDggMTguNDZjMC40MywwLjM0IDAuOTMsMC41MSAxLjQ0LDAuNTEgMC42NCwwIDEuMjcsLTAuMjYgMS43NCwtMC43OGwzNi45MSAtNDEuNTNjMC44NiwtMC45NiAwLjc2LC0yLjQyIC0wLjE5LC0zLjI2IC0wLjk1LC0wLjg2IC0yLjQxLC0wLjc3IC0zLjI2LDAuMTl6XCIgaWQ9XCJOWFJlcG9ydFN1Y2Nlc3M2XCIvPjwvZz48L2c+PC9nPjwvc3ZnPmA7XHJcblxyXG4gICAgcmV0dXJuIHJlcG9ydFN2Z1N1Y2Nlc3M7XHJcblxyXG59XHJcbi8vIE5vdGlmbGl4OiBSZXBvcnQgU1ZHIFN1Y2Nlc3Mgb2ZmXHJcblxyXG4vLyBOb3RpZmxpeDogUmVwb3J0IFNWRyBGYWlsdXJlIG9uXHJcbmNvbnN0IG5vdGlmbGl4UmVwb3J0U3ZnRmFpbHVyZSA9IGZ1bmN0aW9uICh3aWR0aCwgY29sb3IpIHtcclxuXHJcbiAgICBpZiAoIXdpZHRoKSB7IHdpZHRoID0gJzExMHB4JzsgfVxyXG4gICAgaWYgKCFjb2xvcikgeyBjb2xvciA9ICcjZjQ0MzM2JzsgfVxyXG5cclxuICAgIGNvbnN0IHJlcG9ydFN2Z0ZhaWx1cmUgPSBgPHN2ZyBpZD1cIk5YUmVwb3J0RmFpbHVyZVwiIGZpbGw9XCIke2NvbG9yfVwiIHdpZHRoPVwiJHt3aWR0aH1cIiBoZWlnaHQ9XCIke3dpZHRofVwiIHhtbG5zPVwiaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmdcIiB4bWw6c3BhY2U9XCJwcmVzZXJ2ZVwiIHZlcnNpb249XCIxLjFcIiBzdHlsZT1cInNoYXBlLXJlbmRlcmluZzpnZW9tZXRyaWNQcmVjaXNpb247IHRleHQtcmVuZGVyaW5nOmdlb21ldHJpY1ByZWNpc2lvbjsgaW1hZ2UtcmVuZGVyaW5nOm9wdGltaXplUXVhbGl0eTsgZmlsbC1ydWxlOmV2ZW5vZGQ7IGNsaXAtcnVsZTpldmVub2RkXCIgdmlld0JveD1cIjAgMCAxMjAgMTIwXCIgeG1sbnM6eGxpbms9XCJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rXCI+PHN0eWxlPkAtd2Via2l0LWtleWZyYW1lcyBOWFJlcG9ydEZhaWx1cmU0LWFuaW1hdGlvbnswJXtvcGFjaXR5OiAwO300MCV7b3BhY2l0eTogMTt9MTAwJXtvcGFjaXR5OiAxO319QGtleWZyYW1lcyBOWFJlcG9ydEZhaWx1cmU0LWFuaW1hdGlvbnswJXtvcGFjaXR5OiAwO300MCV7b3BhY2l0eTogMTt9MTAwJXtvcGFjaXR5OiAxO319QC13ZWJraXQta2V5ZnJhbWVzIE5YUmVwb3J0RmFpbHVyZTMtYW5pbWF0aW9uezAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC41LCAwLjUpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDAuNSwgMC41KSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9NDAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7fTYwJXstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDAuOTUsIDAuOTUpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDAuOTUsIDAuOTUpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO30xMDAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7fX1Aa2V5ZnJhbWVzIE5YUmVwb3J0RmFpbHVyZTMtYW5pbWF0aW9uezAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC41LCAwLjUpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDAuNSwgMC41KSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9NDAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7fTYwJXstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDAuOTUsIDAuOTUpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDAuOTUsIDAuOTUpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO30xMDAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7fX1ALXdlYmtpdC1rZXlmcmFtZXMgTlhSZXBvcnRGYWlsdXJlNS1hbmltYXRpb257MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgwLjUsIDAuNSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC41LCAwLjUpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO301MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9NjAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC45NSwgMC45NSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC45NSwgMC45NSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7fTEwMCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9fUBrZXlmcmFtZXMgTlhSZXBvcnRGYWlsdXJlNS1hbmltYXRpb257MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgwLjUsIDAuNSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC41LCAwLjUpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO301MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9NjAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC45NSwgMC45NSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC45NSwgMC45NSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7fTEwMCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9fUAtd2Via2l0LWtleWZyYW1lcyBOWFJlcG9ydEZhaWx1cmU2LWFuaW1hdGlvbnswJXtvcGFjaXR5OiAwO301MCV7b3BhY2l0eTogMTt9MTAwJXtvcGFjaXR5OiAxO319QGtleWZyYW1lcyBOWFJlcG9ydEZhaWx1cmU2LWFuaW1hdGlvbnswJXtvcGFjaXR5OiAwO301MCV7b3BhY2l0eTogMTt9MTAwJXtvcGFjaXR5OiAxO319I05YUmVwb3J0RmFpbHVyZSAqey13ZWJraXQtYW5pbWF0aW9uLWR1cmF0aW9uOiAxLjJzO2FuaW1hdGlvbi1kdXJhdGlvbjogMS4yczstd2Via2l0LWFuaW1hdGlvbi10aW1pbmctZnVuY3Rpb246IGN1YmljLWJlemllcigwLCAwLCAxLCAxKTthbmltYXRpb24tdGltaW5nLWZ1bmN0aW9uOiBjdWJpYy1iZXppZXIoMCwgMCwgMSwgMSk7fSNOWFJlcG9ydEZhaWx1cmU2e2ZpbGw6aW5oZXJpdDstd2Via2l0LWFuaW1hdGlvbi1uYW1lOiBOWFJlcG9ydEZhaWx1cmU2LWFuaW1hdGlvbjthbmltYXRpb24tbmFtZTogTlhSZXBvcnRGYWlsdXJlNi1hbmltYXRpb247LXdlYmtpdC1hbmltYXRpb24tdGltaW5nLWZ1bmN0aW9uOiBjdWJpYy1iZXppZXIoMC40MiwgMCwgMC41OCwgMSk7YW5pbWF0aW9uLXRpbWluZy1mdW5jdGlvbjogY3ViaWMtYmV6aWVyKDAuNDIsIDAsIDAuNTgsIDEpO29wYWNpdHk6IDE7fSNOWFJlcG9ydEZhaWx1cmU1ey13ZWJraXQtYW5pbWF0aW9uLW5hbWU6IE5YUmVwb3J0RmFpbHVyZTUtYW5pbWF0aW9uO2FuaW1hdGlvbi1uYW1lOiBOWFJlcG9ydEZhaWx1cmU1LWFuaW1hdGlvbjstd2Via2l0LWFuaW1hdGlvbi10aW1pbmctZnVuY3Rpb246IGN1YmljLWJlemllcigwLjQyLCAwLCAwLjU4LCAxKTthbmltYXRpb24tdGltaW5nLWZ1bmN0aW9uOiBjdWJpYy1iZXppZXIoMC40MiwgMCwgMC41OCwgMSk7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9I05YUmVwb3J0RmFpbHVyZTN7LXdlYmtpdC1hbmltYXRpb24tbmFtZTogTlhSZXBvcnRGYWlsdXJlMy1hbmltYXRpb247YW5pbWF0aW9uLW5hbWU6IE5YUmVwb3J0RmFpbHVyZTMtYW5pbWF0aW9uOy13ZWJraXQtYW5pbWF0aW9uLXRpbWluZy1mdW5jdGlvbjogY3ViaWMtYmV6aWVyKDAuNDIsIDAsIDAuNTgsIDEpO2FuaW1hdGlvbi10aW1pbmctZnVuY3Rpb246IGN1YmljLWJlemllcigwLjQyLCAwLCAwLjU4LCAxKTstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO30jTlhSZXBvcnRGYWlsdXJlNHtmaWxsOmluaGVyaXQ7LXdlYmtpdC1hbmltYXRpb24tbmFtZTogTlhSZXBvcnRGYWlsdXJlNC1hbmltYXRpb247YW5pbWF0aW9uLW5hbWU6IE5YUmVwb3J0RmFpbHVyZTQtYW5pbWF0aW9uOy13ZWJraXQtYW5pbWF0aW9uLXRpbWluZy1mdW5jdGlvbjogY3ViaWMtYmV6aWVyKDAuNDIsIDAsIDAuNTgsIDEpO2FuaW1hdGlvbi10aW1pbmctZnVuY3Rpb246IGN1YmljLWJlemllcigwLjQyLCAwLCAwLjU4LCAxKTtvcGFjaXR5OiAxO308L3N0eWxlPjxnIGlkPVwiTlhSZXBvcnRGYWlsdXJlMVwiPjxnIGlkPVwiTlhSZXBvcnRGYWlsdXJlMlwiPjxnIGlkPVwiTlhSZXBvcnRGYWlsdXJlM1wiIGRhdGEtYW5pbWF0b3ItZ3JvdXA9XCJ0cnVlXCIgZGF0YS1hbmltYXRvci10eXBlPVwiMlwiPjxwYXRoIGQ9XCJNNC4zNSAzNC45NWMwLC0xNi44MiAxMy43OCwtMzAuNiAzMC42LC0zMC42bDUwLjEgMGMxNi44MiwwIDMwLjYsMTMuNzggMzAuNiwzMC42bDAgNTAuMWMwLDE2LjgyIC0xMy43OCwzMC42IC0zMC42LDMwLjZsLTUwLjEgMGMtMTYuODIsMCAtMzAuNiwtMTMuNzggLTMwLjYsLTMwLjZsMCAtNTAuMXptMzAuNiA4NS4wNWw1MC4xIDBjMTkuMjIsMCAzNC45NSwtMTUuNzMgMzQuOTUsLTM0Ljk1bDAgLTUwLjFjMCwtMTkuMjIgLTE1LjczLC0zNC45NSAtMzQuOTUsLTM0Ljk1bC01MC4xIDBjLTE5LjIyLDAgLTM0Ljk1LDE1LjczIC0zNC45NSwzNC45NWwwIDUwLjFjMCwxOS4yMiAxNS43MywzNC45NSAzNC45NSwzNC45NXpcIiBpZD1cIk5YUmVwb3J0RmFpbHVyZTRcIi8+PC9nPjxnIGlkPVwiTlhSZXBvcnRGYWlsdXJlNVwiIGRhdGEtYW5pbWF0b3ItZ3JvdXA9XCJ0cnVlXCIgZGF0YS1hbmltYXRvci10eXBlPVwiMlwiPjxwYXRoIGQ9XCJNODIuNCAzNy42Yy0wLjksLTAuOSAtMi4zNywtMC45IC0zLjI3LDBsLTE5LjEzIDE5LjEzIC0xOS4xNCAtMTkuMTNjLTAuOSwtMC45IC0yLjM2LC0wLjkgLTMuMjYsMCAtMC45LDAuOSAtMC45LDIuMzUgMCwzLjI2bDE5LjEzIDE5LjE0IC0xOS4xMyAxOS4xM2MtMC45LDAuOSAtMC45LDIuMzcgMCwzLjI3IDAuNDUsMC40NSAxLjA0LDAuNjggMS42MywwLjY4IDAuNTksMCAxLjE4LC0wLjIzIDEuNjMsLTAuNjhsMTkuMTQgLTE5LjE0IDE5LjEzIDE5LjE0YzAuNDUsMC40NSAxLjA1LDAuNjggMS42NCwwLjY4IDAuNTgsMCAxLjE4LC0wLjIzIDEuNjMsLTAuNjggMC45LC0wLjkgMC45LC0yLjM3IDAsLTMuMjdsLTE5LjE0IC0xOS4xMyAxOS4xNCAtMTkuMTRjMC45LC0wLjkxIDAuOSwtMi4zNiAwLC0zLjI2elwiIGlkPVwiTlhSZXBvcnRGYWlsdXJlNlwiLz48L2c+PC9nPjwvZz48L3N2Zz5gO1xyXG5cclxuICAgIHJldHVybiByZXBvcnRTdmdGYWlsdXJlO1xyXG59XHJcbi8vIE5vdGlmbGl4OiBSZXBvcnQgU1ZHIEZhaWx1cmUgb2ZmXHJcblxyXG4vLyBOb3RpZmxpeDogUmVwb3J0IFNWRyBXYXJuaW5nIG9uXHJcbmNvbnN0IG5vdGlmbGl4UmVwb3J0U3ZnV2FybmluZyA9IGZ1bmN0aW9uICh3aWR0aCwgY29sb3IpIHtcclxuXHJcbiAgICBpZiAoIXdpZHRoKSB7IHdpZHRoID0gJzExMHB4JzsgfVxyXG4gICAgaWYgKCFjb2xvcikgeyBjb2xvciA9ICcjZjJiZDFkJzsgfVxyXG5cclxuICAgIGNvbnN0IHJlcG9ydFN2Z1dhcm5pbmcgPSBgPHN2ZyBpZD1cIk5YUmVwb3J0V2FybmluZ1wiIGZpbGw9XCIke2NvbG9yfVwiIHdpZHRoPVwiJHt3aWR0aH1cIiBoZWlnaHQ9XCIke3dpZHRofVwiIHhtbG5zPVwiaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmdcIiB4bWw6c3BhY2U9XCJwcmVzZXJ2ZVwiIHZlcnNpb249XCIxLjFcIiBzdHlsZT1cInNoYXBlLXJlbmRlcmluZzpnZW9tZXRyaWNQcmVjaXNpb247IHRleHQtcmVuZGVyaW5nOmdlb21ldHJpY1ByZWNpc2lvbjsgaW1hZ2UtcmVuZGVyaW5nOm9wdGltaXplUXVhbGl0eTsgZmlsbC1ydWxlOmV2ZW5vZGQ7IGNsaXAtcnVsZTpldmVub2RkXCIgdmlld0JveD1cIjAgMCAxMjAgMTIwXCIgeG1sbnM6eGxpbms9XCJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rXCI+PHN0eWxlPkAtd2Via2l0LWtleWZyYW1lcyBOWFJlcG9ydFdhcm5pbmczLWFuaW1hdGlvbnswJXtvcGFjaXR5OiAwO300MCV7b3BhY2l0eTogMTt9MTAwJXtvcGFjaXR5OiAxO319QGtleWZyYW1lcyBOWFJlcG9ydFdhcm5pbmczLWFuaW1hdGlvbnswJXtvcGFjaXR5OiAwO300MCV7b3BhY2l0eTogMTt9MTAwJXtvcGFjaXR5OiAxO319QC13ZWJraXQta2V5ZnJhbWVzIE5YUmVwb3J0V2FybmluZzItYW5pbWF0aW9uezAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC41LCAwLjUpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDAuNSwgMC41KSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9NDAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7fTYwJXstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDAuOTUsIDAuOTUpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDAuOTUsIDAuOTUpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO30xMDAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7fX1Aa2V5ZnJhbWVzIE5YUmVwb3J0V2FybmluZzItYW5pbWF0aW9uezAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC41LCAwLjUpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDAuNSwgMC41KSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9NDAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7fTYwJXstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDAuOTUsIDAuOTUpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDAuOTUsIDAuOTUpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO30xMDAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7fX1ALXdlYmtpdC1rZXlmcmFtZXMgTlhSZXBvcnRXYXJuaW5nNC1hbmltYXRpb257MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2Ni42cHgpIHNjYWxlKDAuNSwgMC41KSB0cmFuc2xhdGUoLTYwcHgsIC02Ni42cHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDY2LjZweCkgc2NhbGUoMC41LCAwLjUpIHRyYW5zbGF0ZSgtNjBweCwgLTY2LjZweCk7fTUwJXstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDY2LjZweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjYuNnB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2Ni42cHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTY2LjZweCk7fTYwJXstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDY2LjZweCkgc2NhbGUoMC45NSwgMC45NSkgdHJhbnNsYXRlKC02MHB4LCAtNjYuNnB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2Ni42cHgpIHNjYWxlKDAuOTUsIDAuOTUpIHRyYW5zbGF0ZSgtNjBweCwgLTY2LjZweCk7fTEwMCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2Ni42cHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTY2LjZweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjYuNnB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02Ni42cHgpO319QGtleWZyYW1lcyBOWFJlcG9ydFdhcm5pbmc0LWFuaW1hdGlvbnswJXstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDY2LjZweCkgc2NhbGUoMC41LCAwLjUpIHRyYW5zbGF0ZSgtNjBweCwgLTY2LjZweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjYuNnB4KSBzY2FsZSgwLjUsIDAuNSkgdHJhbnNsYXRlKC02MHB4LCAtNjYuNnB4KTt9NTAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjYuNnB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02Ni42cHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDY2LjZweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjYuNnB4KTt9NjAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjYuNnB4KSBzY2FsZSgwLjk1LCAwLjk1KSB0cmFuc2xhdGUoLTYwcHgsIC02Ni42cHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDY2LjZweCkgc2NhbGUoMC45NSwgMC45NSkgdHJhbnNsYXRlKC02MHB4LCAtNjYuNnB4KTt9MTAwJXstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDY2LjZweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjYuNnB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2Ni42cHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTY2LjZweCk7fX1ALXdlYmtpdC1rZXlmcmFtZXMgTlhSZXBvcnRXYXJuaW5nNS1hbmltYXRpb257MCV7b3BhY2l0eTogMDt9NTAle29wYWNpdHk6IDE7fTEwMCV7b3BhY2l0eTogMTt9fUBrZXlmcmFtZXMgTlhSZXBvcnRXYXJuaW5nNS1hbmltYXRpb257MCV7b3BhY2l0eTogMDt9NTAle29wYWNpdHk6IDE7fTEwMCV7b3BhY2l0eTogMTt9fSNOWFJlcG9ydFdhcm5pbmcgKnstd2Via2l0LWFuaW1hdGlvbi1kdXJhdGlvbjogMS4yczthbmltYXRpb24tZHVyYXRpb246IDEuMnM7LXdlYmtpdC1hbmltYXRpb24tdGltaW5nLWZ1bmN0aW9uOiBjdWJpYy1iZXppZXIoMCwgMCwgMSwgMSk7YW5pbWF0aW9uLXRpbWluZy1mdW5jdGlvbjogY3ViaWMtYmV6aWVyKDAsIDAsIDEsIDEpO30jTlhSZXBvcnRXYXJuaW5nM3tmaWxsOiBpbmhlcml0Oy13ZWJraXQtYW5pbWF0aW9uLW5hbWU6IE5YUmVwb3J0V2FybmluZzMtYW5pbWF0aW9uO2FuaW1hdGlvbi1uYW1lOiBOWFJlcG9ydFdhcm5pbmczLWFuaW1hdGlvbjstd2Via2l0LWFuaW1hdGlvbi10aW1pbmctZnVuY3Rpb246IGN1YmljLWJlemllcigwLjQyLCAwLCAwLjU4LCAxKTthbmltYXRpb24tdGltaW5nLWZ1bmN0aW9uOiBjdWJpYy1iZXppZXIoMC40MiwgMCwgMC41OCwgMSk7b3BhY2l0eTogMTt9I05YUmVwb3J0V2FybmluZzV7ZmlsbDogaW5oZXJpdDstd2Via2l0LWFuaW1hdGlvbi1uYW1lOiBOWFJlcG9ydFdhcm5pbmc1LWFuaW1hdGlvbjthbmltYXRpb24tbmFtZTogTlhSZXBvcnRXYXJuaW5nNS1hbmltYXRpb247LXdlYmtpdC1hbmltYXRpb24tdGltaW5nLWZ1bmN0aW9uOiBjdWJpYy1iZXppZXIoMC40MiwgMCwgMC41OCwgMSk7YW5pbWF0aW9uLXRpbWluZy1mdW5jdGlvbjogY3ViaWMtYmV6aWVyKDAuNDIsIDAsIDAuNTgsIDEpO29wYWNpdHk6IDE7fSNOWFJlcG9ydFdhcm5pbmc0ey13ZWJraXQtYW5pbWF0aW9uLW5hbWU6IE5YUmVwb3J0V2FybmluZzQtYW5pbWF0aW9uO2FuaW1hdGlvbi1uYW1lOiBOWFJlcG9ydFdhcm5pbmc0LWFuaW1hdGlvbjstd2Via2l0LWFuaW1hdGlvbi10aW1pbmctZnVuY3Rpb246IGN1YmljLWJlemllcigwLjQyLCAwLCAwLjU4LCAxKTthbmltYXRpb24tdGltaW5nLWZ1bmN0aW9uOiBjdWJpYy1iZXppZXIoMC40MiwgMCwgMC41OCwgMSk7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2Ni42cHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTY2LjZweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjYuNnB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02Ni42cHgpO30jTlhSZXBvcnRXYXJuaW5nMnstd2Via2l0LWFuaW1hdGlvbi1uYW1lOiBOWFJlcG9ydFdhcm5pbmcyLWFuaW1hdGlvbjthbmltYXRpb24tbmFtZTogTlhSZXBvcnRXYXJuaW5nMi1hbmltYXRpb247LXdlYmtpdC1hbmltYXRpb24tdGltaW5nLWZ1bmN0aW9uOiBjdWJpYy1iZXppZXIoMC40MiwgMCwgMC41OCwgMSk7YW5pbWF0aW9uLXRpbWluZy1mdW5jdGlvbjogY3ViaWMtYmV6aWVyKDAuNDIsIDAsIDAuNTgsIDEpOy13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7fTwvc3R5bGU+PGcgaWQ9XCJOWFJlcG9ydFdhcm5pbmcxXCI+PGcgaWQ9XCJOWFJlcG9ydFdhcm5pbmcyXCIgZGF0YS1hbmltYXRvci1ncm91cD1cInRydWVcIiBkYXRhLWFuaW1hdG9yLXR5cGU9XCIyXCI+PHBhdGggZD1cIk0xMTUuNDYgMTA2LjE1bC01NC4wNCAtOTMuOGMtMC42MSwtMS4wNiAtMi4yMywtMS4wNiAtMi44NCwwbC01NC4wNCA5My44Yy0wLjYyLDEuMDcgMC4yMSwyLjI5IDEuNDIsMi4yOWwxMDguMDggMGMxLjIxLDAgMi4wNCwtMS4yMiAxLjQyLC0yLjI5em0tNTAuMjkgLTk1Ljk1bDU0LjA0IDkzLjhjMi4yOCwzLjk2IC0wLjY1LDguNzggLTUuMTcsOC43OGwtMTA4LjA4IDBjLTQuNTIsMCAtNy40NSwtNC44MiAtNS4xNywtOC43OGw1NC4wNCAtOTMuOGMyLjI4LC0zLjk1IDguMDMsLTQgMTAuMzQsMHpcIiBpZD1cIk5YUmVwb3J0V2FybmluZzNcIi8+PC9nPjxnIGlkPVwiTlhSZXBvcnRXYXJuaW5nNFwiIGRhdGEtYW5pbWF0b3ItZ3JvdXA9XCJ0cnVlXCIgZGF0YS1hbmltYXRvci10eXBlPVwiMlwiPjxwYXRoIGQ9XCJNNTcuODMgOTQuMDFjMCwxLjIgMC45NywyLjE3IDIuMTcsMi4xNyAxLjIsMCAyLjE3LC0wLjk3IDIuMTcsLTIuMTdsMCAtMy4yYzAsLTEuMiAtMC45NywtMi4xNyAtMi4xNywtMi4xNyAtMS4yLDAgLTIuMTcsMC45NyAtMi4xNywyLjE3bDAgMy4yem0wIC0xNC4xNWMwLDEuMiAwLjk3LDIuMTcgMi4xNywyLjE3IDEuMiwwIDIuMTcsLTAuOTcgMi4xNywtMi4xN2wwIC00MC42NWMwLC0xLjIgLTAuOTcsLTIuMTcgLTIuMTcsLTIuMTcgLTEuMiwwIC0yLjE3LDAuOTcgLTIuMTcsMi4xN2wwIDQwLjY1elwiIGlkPVwiTlhSZXBvcnRXYXJuaW5nNVwiLz48L2c+PC9nPjwvc3ZnPmA7XHJcblxyXG4gICAgcmV0dXJuIHJlcG9ydFN2Z1dhcm5pbmc7XHJcbn1cclxuLy8gTm90aWZsaXg6IFJlcG9ydCBTVkcgV2FybmluZyBvZmZcclxuXHJcbi8vIE5vdGlmbGl4OiBSZXBvcnQgU1ZHIEluZm8gb25cclxuY29uc3Qgbm90aWZsaXhSZXBvcnRTdmdJbmZvID0gZnVuY3Rpb24gKHdpZHRoLCBjb2xvcikge1xyXG5cclxuICAgIGlmICghd2lkdGgpIHsgd2lkdGggPSAnMTEwcHgnOyB9XHJcbiAgICBpZiAoIWNvbG9yKSB7IGNvbG9yID0gJyMwMGJjZDQnOyB9XHJcblxyXG4gICAgY29uc3QgcmVwb3J0U3ZnSW5mbyA9IGA8c3ZnIGlkPVwiTlhSZXBvcnRJbmZvXCIgZmlsbD1cIiR7Y29sb3J9XCIgd2lkdGg9XCIke3dpZHRofVwiIGhlaWdodD1cIiR7d2lkdGh9XCIgeG1sbnM9XCJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2Z1wiIHhtbDpzcGFjZT1cInByZXNlcnZlXCIgdmVyc2lvbj1cIjEuMVwiIHN0eWxlPVwic2hhcGUtcmVuZGVyaW5nOmdlb21ldHJpY1ByZWNpc2lvbjsgdGV4dC1yZW5kZXJpbmc6Z2VvbWV0cmljUHJlY2lzaW9uOyBpbWFnZS1yZW5kZXJpbmc6b3B0aW1pemVRdWFsaXR5OyBmaWxsLXJ1bGU6ZXZlbm9kZDsgY2xpcC1ydWxlOmV2ZW5vZGRcIiB2aWV3Qm94PVwiMCAwIDEyMCAxMjBcIiB4bWxuczp4bGluaz1cImh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmtcIj48c3R5bGU+QC13ZWJraXQta2V5ZnJhbWVzIE5YUmVwb3J0SW5mbzUtYW5pbWF0aW9uezAle29wYWNpdHk6IDA7fTUwJXtvcGFjaXR5OiAxO30xMDAle29wYWNpdHk6IDE7fX1Aa2V5ZnJhbWVzIE5YUmVwb3J0SW5mbzUtYW5pbWF0aW9uezAle29wYWNpdHk6IDA7fTUwJXtvcGFjaXR5OiAxO30xMDAle29wYWNpdHk6IDE7fX1ALXdlYmtpdC1rZXlmcmFtZXMgTlhSZXBvcnRJbmZvNC1hbmltYXRpb257MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgwLjUsIDAuNSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC41LCAwLjUpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO301MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9NjAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC45NSwgMC45NSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC45NSwgMC45NSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7fTEwMCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9fUBrZXlmcmFtZXMgTlhSZXBvcnRJbmZvNC1hbmltYXRpb257MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgwLjUsIDAuNSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC41LCAwLjUpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO301MCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9NjAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC45NSwgMC45NSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC45NSwgMC45NSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7fTEwMCV7LXdlYmtpdC10cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt0cmFuc2Zvcm06IHRyYW5zbGF0ZSg2MHB4LCA2MHB4KSBzY2FsZSgxLCAxKSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9fUAtd2Via2l0LWtleWZyYW1lcyBOWFJlcG9ydEluZm8zLWFuaW1hdGlvbnswJXtvcGFjaXR5OiAwO300MCV7b3BhY2l0eTogMTt9MTAwJXtvcGFjaXR5OiAxO319QGtleWZyYW1lcyBOWFJlcG9ydEluZm8zLWFuaW1hdGlvbnswJXtvcGFjaXR5OiAwO300MCV7b3BhY2l0eTogMTt9MTAwJXtvcGFjaXR5OiAxO319QC13ZWJraXQta2V5ZnJhbWVzIE5YUmVwb3J0SW5mbzItYW5pbWF0aW9uezAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC41LCAwLjUpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDAuNSwgMC41KSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9NDAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7fTYwJXstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDAuOTUsIDAuOTUpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDAuOTUsIDAuOTUpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO30xMDAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7fX1Aa2V5ZnJhbWVzIE5YUmVwb3J0SW5mbzItYW5pbWF0aW9uezAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMC41LCAwLjUpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDAuNSwgMC41KSB0cmFuc2xhdGUoLTYwcHgsIC02MHB4KTt9NDAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7fTYwJXstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDAuOTUsIDAuOTUpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDAuOTUsIDAuOTUpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO30xMDAley13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7fX0jTlhSZXBvcnRJbmZvICp7LXdlYmtpdC1hbmltYXRpb24tZHVyYXRpb246IDEuMnM7YW5pbWF0aW9uLWR1cmF0aW9uOiAxLjJzOy13ZWJraXQtYW5pbWF0aW9uLXRpbWluZy1mdW5jdGlvbjogY3ViaWMtYmV6aWVyKDAsIDAsIDEsIDEpO2FuaW1hdGlvbi10aW1pbmctZnVuY3Rpb246IGN1YmljLWJlemllcigwLCAwLCAxLCAxKTt9I05YUmVwb3J0SW5mbzN7ZmlsbDppbmhlcml0Oy13ZWJraXQtYW5pbWF0aW9uLW5hbWU6IE5YUmVwb3J0SW5mbzMtYW5pbWF0aW9uO2FuaW1hdGlvbi1uYW1lOiBOWFJlcG9ydEluZm8zLWFuaW1hdGlvbjstd2Via2l0LWFuaW1hdGlvbi10aW1pbmctZnVuY3Rpb246IGN1YmljLWJlemllcigwLjQyLCAwLCAwLjU4LCAxKTthbmltYXRpb24tdGltaW5nLWZ1bmN0aW9uOiBjdWJpYy1iZXppZXIoMC40MiwgMCwgMC41OCwgMSk7b3BhY2l0eTogMTt9I05YUmVwb3J0SW5mbzV7ZmlsbDppbmhlcml0Oy13ZWJraXQtYW5pbWF0aW9uLW5hbWU6IE5YUmVwb3J0SW5mbzUtYW5pbWF0aW9uO2FuaW1hdGlvbi1uYW1lOiBOWFJlcG9ydEluZm81LWFuaW1hdGlvbjstd2Via2l0LWFuaW1hdGlvbi10aW1pbmctZnVuY3Rpb246IGN1YmljLWJlemllcigwLjQyLCAwLCAwLjU4LCAxKTthbmltYXRpb24tdGltaW5nLWZ1bmN0aW9uOiBjdWJpYy1iZXppZXIoMC40MiwgMCwgMC41OCwgMSk7b3BhY2l0eTogMTt9I05YUmVwb3J0SW5mbzJ7LXdlYmtpdC1hbmltYXRpb24tbmFtZTogTlhSZXBvcnRJbmZvMi1hbmltYXRpb247YW5pbWF0aW9uLW5hbWU6IE5YUmVwb3J0SW5mbzItYW5pbWF0aW9uOy13ZWJraXQtYW5pbWF0aW9uLXRpbWluZy1mdW5jdGlvbjogY3ViaWMtYmV6aWVyKDAuNDIsIDAsIDAuNTgsIDEpO2FuaW1hdGlvbi10aW1pbmctZnVuY3Rpb246IGN1YmljLWJlemllcigwLjQyLCAwLCAwLjU4LCAxKTstd2Via2l0LXRyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO3RyYW5zZm9ybTogdHJhbnNsYXRlKDYwcHgsIDYwcHgpIHNjYWxlKDEsIDEpIHRyYW5zbGF0ZSgtNjBweCwgLTYwcHgpO30jTlhSZXBvcnRJbmZvNHstd2Via2l0LWFuaW1hdGlvbi1uYW1lOiBOWFJlcG9ydEluZm80LWFuaW1hdGlvbjthbmltYXRpb24tbmFtZTogTlhSZXBvcnRJbmZvNC1hbmltYXRpb247LXdlYmtpdC1hbmltYXRpb24tdGltaW5nLWZ1bmN0aW9uOiBjdWJpYy1iZXppZXIoMC40MiwgMCwgMC41OCwgMSk7YW5pbWF0aW9uLXRpbWluZy1mdW5jdGlvbjogY3ViaWMtYmV6aWVyKDAuNDIsIDAsIDAuNTgsIDEpOy13ZWJraXQtdHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7dHJhbnNmb3JtOiB0cmFuc2xhdGUoNjBweCwgNjBweCkgc2NhbGUoMSwgMSkgdHJhbnNsYXRlKC02MHB4LCAtNjBweCk7fTwvc3R5bGU+PGcgaWQ9XCJOWFJlcG9ydEluZm8xXCI+PGcgaWQ9XCJOWFJlcG9ydEluZm8yXCIgZGF0YS1hbmltYXRvci1ncm91cD1cInRydWVcIiBkYXRhLWFuaW1hdG9yLXR5cGU9XCIyXCI+PHBhdGggZD1cIk02MCAxMTUuMzhjLTMwLjU0LDAgLTU1LjM4LC0yNC44NCAtNTUuMzgsLTU1LjM4IDAsLTMwLjU0IDI0Ljg0LC01NS4zOCA1NS4zOCwtNTUuMzggMzAuNTQsMCA1NS4zOCwyNC44NCA1NS4zOCw1NS4zOCAwLDMwLjU0IC0yNC44NCw1NS4zOCAtNTUuMzgsNTUuMzh6bTAgLTExNS4zOGMtMzMuMDgsMCAtNjAsMjYuOTIgLTYwLDYwIDAsMzMuMDggMjYuOTIsNjAgNjAsNjAgMzMuMDgsMCA2MCwtMjYuOTIgNjAsLTYwIDAsLTMzLjA4IC0yNi45MiwtNjAgLTYwLC02MHpcIiBpZD1cIk5YUmVwb3J0SW5mbzNcIi8+PC9nPjxnIGlkPVwiTlhSZXBvcnRJbmZvNFwiIGRhdGEtYW5pbWF0b3ItZ3JvdXA9XCJ0cnVlXCIgZGF0YS1hbmltYXRvci10eXBlPVwiMlwiPjxwYXRoIGQ9XCJNNTcuNzUgNDMuODVjMCwtMS4yNCAxLjAxLC0yLjI1IDIuMjUsLTIuMjUgMS4yNCwwIDIuMjUsMS4wMSAyLjI1LDIuMjVsMCA0OC4xOGMwLDEuMjQgLTEuMDEsMi4yNSAtMi4yNSwyLjI1IC0xLjI0LDAgLTIuMjUsLTEuMDEgLTIuMjUsLTIuMjVsMCAtNDguMTh6bTAgLTE1Ljg4YzAsLTEuMjQgMS4wMSwtMi4yNSAyLjI1LC0yLjI1IDEuMjQsMCAyLjI1LDEuMDEgMi4yNSwyLjI1bDAgMy4zMmMwLDEuMjUgLTEuMDEsMi4yNSAtMi4yNSwyLjI1IC0xLjI0LDAgLTIuMjUsLTEgLTIuMjUsLTIuMjVsMCAtMy4zMnpcIiBpZD1cIk5YUmVwb3J0SW5mbzVcIi8+PC9nPjwvZz48L3N2Zz5gO1xyXG5cclxuICAgIHJldHVybiByZXBvcnRTdmdJbmZvO1xyXG5cclxufVxyXG4vLyBOb3RpZmxpeDogUmVwb3J0IFNWRyBJbmZvIG9mZlxyXG5cclxuXHJcbi8vIE5vdGlmbGl4OiBDb25maXJtIFNpbmdsZSBvblxyXG5jb25zdCBOb3RpZmxpeENvbmZpcm0gPSBmdW5jdGlvbiAodGl0bGUsIG1lc3NhZ2UsIG9rQnV0dG9uVGV4dCwgY2FuY2VsQnV0dG9uVGV4dCwgb2tCdXR0b25DYWxsYmFjaywgY2FuY2VsQnV0dG9uQ2FsbGJhY2spIHtcclxuXHJcbiAgICAvLyBpZiBwbGFpblRleHQgdHJ1ZSA9IEhUTUwgdGFncyBub3QgYWxsb3dlZCBvblxyXG4gICAgaWYgKG5ld0NvbmZpcm1TZXR0aW5ncy5wbGFpblRleHQpIHtcclxuICAgICAgICB0aXRsZSA9IG5vdGlmbGl4UGxhaW50ZXh0KHRpdGxlKTtcclxuICAgICAgICBtZXNzYWdlID0gbm90aWZsaXhQbGFpbnRleHQobWVzc2FnZSk7XHJcbiAgICAgICAgb2tCdXR0b25UZXh0ID0gbm90aWZsaXhQbGFpbnRleHQob2tCdXR0b25UZXh0KTtcclxuICAgICAgICBjYW5jZWxCdXR0b25UZXh0ID0gbm90aWZsaXhQbGFpbnRleHQoY2FuY2VsQnV0dG9uVGV4dCk7XHJcbiAgICB9XHJcbiAgICAvLyBpZiBwbGFpblRleHQgdHJ1ZSA9IEhUTUwgdGFncyBub3QgYWxsb3dlZCBvZmZcclxuXHJcbiAgICAvLyBpZiBwbGFpblRleHQgZmFsc2UgYnV0IHRoZSBjb250ZW50cyBsZW5ndGggbW9yZSB0aGFuICpNYXhMZW5ndGggPSBIVE1MIHRhZ3MgZXJyb3Igb25cclxuICAgIGlmICghbmV3Q29uZmlybVNldHRpbmdzLnBsYWluVGV4dCkge1xyXG4gICAgICAgIGlmICh0aXRsZS5sZW5ndGggPiBuZXdDb25maXJtU2V0dGluZ3MudGl0bGVNYXhMZW5ndGgpIHtcclxuICAgICAgICAgICAgdGl0bGUgPSAnSFRNTCBUYWdzIEVycm9yJzsgLy8gdGl0bGUgaHRtbCBlcnJvclxyXG4gICAgICAgICAgICBtZXNzYWdlID0gJ1lvdXIgVGl0bGUgY29udGVudCBsZW5ndGggaXMgbW9yZSB0aGFuIFwidGl0bGVNYXhMZW5ndGhcIiBvcHRpb24uJzsgLy8gbWVzc2FnZSBodG1sIGVycm9yXHJcbiAgICAgICAgICAgIG9rQnV0dG9uVGV4dCA9ICdPa2F5JzsgLy8gYnV0dG9uIGh0bWwgZXJyb3JcclxuICAgICAgICAgICAgY2FuY2VsQnV0dG9uVGV4dCA9ICcuLi4nOyAvLyBidXR0b24gaHRtbCBlcnJvclxyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgaWYgKG1lc3NhZ2UubGVuZ3RoID4gbmV3Q29uZmlybVNldHRpbmdzLm1lc3NhZ2VNYXhMZW5ndGgpIHtcclxuICAgICAgICAgICAgdGl0bGUgPSAnSFRNTCBUYWdzIEVycm9yJzsgLy8gdGl0bGUgaHRtbCBlcnJvclxyXG4gICAgICAgICAgICBtZXNzYWdlID0gJ1lvdXIgTWVzc2FnZSBjb250ZW50IGxlbmd0aCBpcyBtb3JlIHRoYW4gXCJtZXNzYWdlTWF4TGVuZ3RoXCIgb3B0aW9uLic7IC8vIG1lc3NhZ2UgaHRtbCBlcnJvclxyXG4gICAgICAgICAgICBva0J1dHRvblRleHQgPSAnT2theSc7IC8vIGJ1dHRvbiBodG1sIGVycm9yXHJcbiAgICAgICAgICAgIGNhbmNlbEJ1dHRvblRleHQgPSAnLi4uJzsgLy8gYnV0dG9uIGh0bWwgZXJyb3JcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIGlmICgob2tCdXR0b25UZXh0Lmxlbmd0aCB8fCBjYW5jZWxCdXR0b25UZXh0Lmxlbmd0aCkgPiBuZXdDb25maXJtU2V0dGluZ3MuYnV0dG9uc01heExlbmd0aCkge1xyXG4gICAgICAgICAgICB0aXRsZSA9ICdIVE1MIFRhZ3MgRXJyb3InOyAvLyB0aXRsZSBodG1sIGVycm9yXHJcbiAgICAgICAgICAgIG1lc3NhZ2UgPSAnWW91ciBCdXR0b25zIGNvbnRlbnRzIGxlbmd0aCBpcyBtb3JlIHRoYW4gXCJidXR0b25zTWF4TGVuZ3RoXCIgb3B0aW9uLic7IC8vIG1lc3NhZ2UgaHRtbCBlcnJvclxyXG4gICAgICAgICAgICBva0J1dHRvblRleHQgPSAnT2theSc7IC8vIGJ1dHRvbiBodG1sIGVycm9yXHJcbiAgICAgICAgICAgIGNhbmNlbEJ1dHRvblRleHQgPSAnLi4uJzsgLy8gYnV0dG9uIGh0bWwgZXJyb3JcclxuICAgICAgICB9XHJcbiAgICB9XHJcbiAgICAvLyBpZiBwbGFpblRleHQgZmFsc2UgYnV0IHRoZSBjb250ZW50cyBsZW5ndGggbW9yZSB0aGFuICpNYXhMZW5ndGggPSBIVE1MIHRhZ3MgZXJyb3Igb2ZmXHJcblxyXG5cclxuICAgIC8vIG1heCBsZW5ndGggb25cclxuICAgIGlmICh0aXRsZS5sZW5ndGggPiBuZXdDb25maXJtU2V0dGluZ3MudGl0bGVNYXhMZW5ndGgpIHtcclxuICAgICAgICB0aXRsZSA9IGAke3RpdGxlLnN1YnN0cmluZygwLCBuZXdDb25maXJtU2V0dGluZ3MudGl0bGVNYXhMZW5ndGgpfS4uLmA7XHJcbiAgICB9XHJcblxyXG4gICAgaWYgKG1lc3NhZ2UubGVuZ3RoID4gbmV3Q29uZmlybVNldHRpbmdzLm1lc3NhZ2VNYXhMZW5ndGgpIHtcclxuICAgICAgICBtZXNzYWdlID0gYCR7bWVzc2FnZS5zdWJzdHJpbmcoMCwgbmV3Q29uZmlybVNldHRpbmdzLm1lc3NhZ2VNYXhMZW5ndGgpfS4uLmA7XHJcbiAgICB9XHJcblxyXG4gICAgaWYgKG9rQnV0dG9uVGV4dC5sZW5ndGggPiBuZXdDb25maXJtU2V0dGluZ3MuYnV0dG9uc01heExlbmd0aCkge1xyXG4gICAgICAgIG9rQnV0dG9uVGV4dCA9IGAke29rQnV0dG9uVGV4dC5zdWJzdHJpbmcoMCwgbmV3Q29uZmlybVNldHRpbmdzLmJ1dHRvbnNNYXhMZW5ndGgpfS4uLmA7XHJcbiAgICB9XHJcblxyXG4gICAgaWYgKGNhbmNlbEJ1dHRvblRleHQubGVuZ3RoID4gbmV3Q29uZmlybVNldHRpbmdzLmJ1dHRvbnNNYXhMZW5ndGgpIHtcclxuICAgICAgICBjYW5jZWxCdXR0b25UZXh0ID0gYCR7Y2FuY2VsQnV0dG9uVGV4dC5zdWJzdHJpbmcoMCwgbmV3Q29uZmlybVNldHRpbmdzLmJ1dHRvbnNNYXhMZW5ndGgpfS4uLmA7XHJcbiAgICB9XHJcbiAgICAvLyBtYXggbGVuZ3RoIG9mZlxyXG5cclxuXHJcbiAgICAvLyBpZiBjc3NBbmltYWlvbiBmYWxzZSAtPiBkdXJhdGlvbiBvblxyXG4gICAgaWYgKCFuZXdDb25maXJtU2V0dGluZ3MuY3NzQW5pbWF0aW9uKSB7XHJcbiAgICAgICAgbmV3Q29uZmlybVNldHRpbmdzLmNzc0FuaW1hdGlvbkR1cmF0aW9uID0gMDtcclxuICAgIH1cclxuICAgIC8vIGlmIGNzc0FuaW1haW9uIGZhbHNlIC0+IGR1cmF0aW9uIG9mZlxyXG5cclxuXHJcbiAgICAvLyBjb25maXJtIHdyYXAgb25cclxuICAgIGNvbnN0IGRvY0JvZHkgPSBkb2N1bWVudC5ib2R5O1xyXG5cclxuICAgIGNvbnN0IG50Zmx4Q29uZmlybVdyYXAgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcclxuICAgIG50Zmx4Q29uZmlybVdyYXAuaWQgPSBjb25maXJtU2V0dGluZ3MuSUQ7XHJcbiAgICBudGZseENvbmZpcm1XcmFwLmNsYXNzTmFtZSA9IGAke25ld0NvbmZpcm1TZXR0aW5ncy5jbGFzc05hbWV9ICR7KG5ld0NvbmZpcm1TZXR0aW5ncy5jc3NBbmltYXRpb24gPyAnd2l0aC1hbmltYXRpb24gbngtJyArIG5ld0NvbmZpcm1TZXR0aW5ncy5jc3NBbmltYXRpb25TdHlsZSA6ICcnKX1gO1xyXG4gICAgbnRmbHhDb25maXJtV3JhcC5zdHlsZS53aWR0aCA9IG5ld0NvbmZpcm1TZXR0aW5ncy53aWR0aDtcclxuICAgIG50Zmx4Q29uZmlybVdyYXAuc3R5bGUuekluZGV4ID0gbmV3Q29uZmlybVNldHRpbmdzLnppbmRleDtcclxuICAgIG50Zmx4Q29uZmlybVdyYXAuc3R5bGUubWFyZ2luID0gJ2F1dG8nO1xyXG5cclxuICAgIC8vIHJ0bCBvblxyXG4gICAgaWYgKG5ld0NvbmZpcm1TZXR0aW5ncy5ydGwpIHtcclxuICAgICAgICBudGZseENvbmZpcm1XcmFwLnNldEF0dHJpYnV0ZSgnZGlyJywgJ3J0bCcpO1xyXG4gICAgICAgIG50Zmx4Q29uZmlybVdyYXAuY2xhc3NMaXN0LmFkZCgncnRsLW9uJyk7XHJcbiAgICB9XHJcbiAgICAvLyBydGwgb2ZmXHJcblxyXG4gICAgLy8gZm9udC1mYW1pbHkgb25cclxuICAgIG50Zmx4Q29uZmlybVdyYXAuc3R5bGUuZm9udEZhbWlseSA9IGBcIiR7bmV3Q29uZmlybVNldHRpbmdzLmZvbnRGYW1pbHl9XCIsIHNhbnMtc2VyaWZgO1xyXG4gICAgLy8gZm9udC1mYW1pbHkgb2ZmXHJcblxyXG4gICAgLy8gaWYgYmFja2dyb3VuZCBvdmVybGF5IHRydWUgb25cclxuICAgIGxldCBjb25maXJtT3ZlcmxheSA9ICcnO1xyXG4gICAgaWYgKG5ld0NvbmZpcm1TZXR0aW5ncy5iYWNrT3ZlcmxheSkge1xyXG4gICAgICAgIGNvbmZpcm1PdmVybGF5ID0gYDxkaXYgY2xhc3M9XCIke25ld0NvbmZpcm1TZXR0aW5ncy5jbGFzc05hbWV9LW92ZXJsYXkgJHsobmV3Q29uZmlybVNldHRpbmdzLmNzc0FuaW1hdGlvbiA/ICd3aXRoLWFuaW1hdGlvbicgOiAnJyl9XCIgc3R5bGU9XCJiYWNrZ3JvdW5kOiR7bmV3Q29uZmlybVNldHRpbmdzLmJhY2tPdmVybGF5Q29sb3J9O2FuaW1hdGlvbi1kdXJhdGlvbjoke25ld0NvbmZpcm1TZXR0aW5ncy5jc3NBbmltYXRpb25EdXJhdGlvbn1tcztcIj48L2Rpdj5gO1xyXG4gICAgfVxyXG4gICAgLy8gaWYgYmFja2dyb3VuZCBvdmVybGF5IHRydWUgb2ZmXHJcblxyXG5cclxuICAgIC8vIGlmIGhhdmUgYSBjYWxsYmFjayAtIGNhbmNlbCBidXR0b24gb25cclxuICAgIGxldCBjYW5jZWxCdXR0b25IVE1MID0gJyc7XHJcbiAgICBpZiAob2tCdXR0b25DYWxsYmFjaykge1xyXG4gICAgICAgIGNhbmNlbEJ1dHRvbkhUTUwgPSBgPGEgaWQ9XCJOWENvbmZpcm1CdXR0b25DYW5jZWxcIiBjbGFzcz1cImNvbmZpcm0tYnV0dG9uLWNhbmNlbFwiIHN0eWxlPVwiY29sb3I6JHtuZXdDb25maXJtU2V0dGluZ3MuY2FuY2VsQnV0dG9uQ29sb3J9O2JhY2tncm91bmQ6JHtuZXdDb25maXJtU2V0dGluZ3MuY2FuY2VsQnV0dG9uQmFja2dyb3VuZH07Zm9udC1zaXplOiR7bmV3Q29uZmlybVNldHRpbmdzLmJ1dHRvbnNGb250U2l6ZX07XCI+JHtjYW5jZWxCdXR0b25UZXh0fTwvYT5gO1xyXG4gICAgfVxyXG4gICAgLy8gaWYgaGF2ZSBhIGNhbGxiYWNrIC0gY2FuY2VsIGJ1dHRvbiBvZmZcclxuXHJcbiAgICBudGZseENvbmZpcm1XcmFwLmlubmVySFRNTCA9IGAke2NvbmZpcm1PdmVybGF5fVxyXG4gICAgICAgIDxkaXYgY2xhc3M9XCIke25ld0NvbmZpcm1TZXR0aW5ncy5jbGFzc05hbWV9LWNvbnRlbnRcIiBzdHlsZT1cImJhY2tncm91bmQ6JHtuZXdDb25maXJtU2V0dGluZ3MuYmFja2dyb3VuZENvbG9yfTsgYW5pbWF0aW9uLWR1cmF0aW9uOiR7bmV3Q29uZmlybVNldHRpbmdzLmNzc0FuaW1hdGlvbkR1cmF0aW9ufW1zOyBib3JkZXItcmFkaXVzOiR7bmV3Q29uZmlybVNldHRpbmdzLmJvcmRlclJhZGl1c307XCI+XHJcbiAgICAgICAgICAgIDxkaXYgY2xhc3M9XCIke25ld0NvbmZpcm1TZXR0aW5ncy5jbGFzc05hbWV9LWhlYWRcIj5cclxuICAgICAgICAgICAgICAgIDxoNSBzdHlsZT1cImNvbG9yOiR7bmV3Q29uZmlybVNldHRpbmdzLnRpdGxlQ29sb3J9O2ZvbnQtc2l6ZToke25ld0NvbmZpcm1TZXR0aW5ncy50aXRsZUZvbnRTaXplfTtcIj4ke3RpdGxlfTwvaDU+XHJcbiAgICAgICAgICAgICAgICA8cCBzdHlsZT1cImNvbG9yOiR7bmV3Q29uZmlybVNldHRpbmdzLm1lc3NhZ2VDb2xvcn07Zm9udC1zaXplOiR7bmV3Q29uZmlybVNldHRpbmdzLm1lc3NhZ2VGb250U2l6ZX07XCI+JHttZXNzYWdlfTwvcD5cclxuICAgICAgICAgICAgPC9kaXY+XHJcbiAgICAgICAgICAgIDxkaXYgY2xhc3M9XCIke25ld0NvbmZpcm1TZXR0aW5ncy5jbGFzc05hbWV9LWJ1dHRvbnNcIj5cclxuICAgICAgICAgICAgICAgIDxhIGlkPVwiTlhDb25maXJtQnV0dG9uT2tcIiBjbGFzcz1cImNvbmZpcm0tYnV0dG9uLW9rICR7KG9rQnV0dG9uQ2FsbGJhY2sgPyAnJyA6ICdmdWxsJyl9XCIgc3R5bGU9XCJjb2xvcjoke25ld0NvbmZpcm1TZXR0aW5ncy5va0J1dHRvbkNvbG9yfTtiYWNrZ3JvdW5kOiR7bmV3Q29uZmlybVNldHRpbmdzLm9rQnV0dG9uQmFja2dyb3VuZH07Zm9udC1zaXplOiR7bmV3Q29uZmlybVNldHRpbmdzLmJ1dHRvbnNGb250U2l6ZX07XCI+JHtva0J1dHRvblRleHR9PC9hPlxyXG4gICAgICAgICAgICAgICAgJHtjYW5jZWxCdXR0b25IVE1MfVxyXG4gICAgICAgICAgICA8L2Rpdj5cclxuICAgICAgICA8L2Rpdj5gO1xyXG4gICAgLy8gY29uZmlybSB3cmFwIG9mZlxyXG5cclxuICAgIC8vIGlmIHRoZXJlIGlzIG5vIGNvbmZpcm0gYm94IG9uXHJcbiAgICBpZiAoIWRvY3VtZW50LmdldEVsZW1lbnRCeUlkKG50Zmx4Q29uZmlybVdyYXAuaWQpKSB7XHJcbiAgICAgICAgZG9jQm9keS5hcHBlbmRDaGlsZChudGZseENvbmZpcm1XcmFwKTtcclxuXHJcbiAgICAgICAgLy8gcG9zaXRpb24gb24gICAgICAgICAgICAgICAgICBcclxuICAgICAgICBpZiAobmV3Q29uZmlybVNldHRpbmdzLnBvc2l0aW9uID09PSAnY2VudGVyJykgeyAvLyBpZiBjZW50ZXJcclxuXHJcbiAgICAgICAgICAgIGxldCB3aW5kb3dIID0gTWF0aC5yb3VuZCh3aW5kb3cuaW5uZXJIZWlnaHQpO1xyXG4gICAgICAgICAgICBsZXQgY29uZmlybUggPSBNYXRoLnJvdW5kKGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKG50Zmx4Q29uZmlybVdyYXAuaWQpLm9mZnNldEhlaWdodCk7XHJcblxyXG4gICAgICAgICAgICBudGZseENvbmZpcm1XcmFwLnN0eWxlLnRvcCA9IGAke3BhcnNlSW50KCh3aW5kb3dIIC0gY29uZmlybUgpIC8gMil9cHhgO1xyXG4gICAgICAgICAgICBudGZseENvbmZpcm1XcmFwLnN0eWxlLmxlZnQgPSBuZXdDb25maXJtU2V0dGluZ3MuZGlzdGFuY2U7XHJcbiAgICAgICAgICAgIG50Zmx4Q29uZmlybVdyYXAuc3R5bGUucmlnaHQgPSBuZXdDb25maXJtU2V0dGluZ3MuZGlzdGFuY2U7XHJcbiAgICAgICAgICAgIG50Zmx4Q29uZmlybVdyYXAuc3R5bGUuYm90dG9tID0gJ2F1dG8nO1xyXG5cclxuICAgICAgICB9IGVsc2UgaWYgKG5ld0NvbmZpcm1TZXR0aW5ncy5wb3NpdGlvbiA9PT0gJ3JpZ2h0LXRvcCcpIHsgLy8gaWYgcmlnaHQtdG9wXHJcblxyXG4gICAgICAgICAgICBudGZseENvbmZpcm1XcmFwLnN0eWxlLnJpZ2h0ID0gbmV3Q29uZmlybVNldHRpbmdzLmRpc3RhbmNlO1xyXG4gICAgICAgICAgICBudGZseENvbmZpcm1XcmFwLnN0eWxlLnRvcCA9IG5ld0NvbmZpcm1TZXR0aW5ncy5kaXN0YW5jZTtcclxuICAgICAgICAgICAgbnRmbHhDb25maXJtV3JhcC5zdHlsZS5ib3R0b20gPSAnYXV0byc7XHJcbiAgICAgICAgICAgIG50Zmx4Q29uZmlybVdyYXAuc3R5bGUubGVmdCA9ICdhdXRvJztcclxuXHJcbiAgICAgICAgfSBlbHNlIGlmIChuZXdDb25maXJtU2V0dGluZ3MucG9zaXRpb24gPT09ICdyaWdodC1ib3R0b20nKSB7IC8vIGlmIHJpZ2h0LWJvdHRvbVxyXG5cclxuICAgICAgICAgICAgbnRmbHhDb25maXJtV3JhcC5zdHlsZS5yaWdodCA9IG5ld0NvbmZpcm1TZXR0aW5ncy5kaXN0YW5jZTtcclxuICAgICAgICAgICAgbnRmbHhDb25maXJtV3JhcC5zdHlsZS5ib3R0b20gPSBuZXdDb25maXJtU2V0dGluZ3MuZGlzdGFuY2U7XHJcbiAgICAgICAgICAgIG50Zmx4Q29uZmlybVdyYXAuc3R5bGUudG9wID0gJ2F1dG8nO1xyXG4gICAgICAgICAgICBudGZseENvbmZpcm1XcmFwLnN0eWxlLmxlZnQgPSAnYXV0byc7XHJcblxyXG4gICAgICAgIH0gZWxzZSBpZiAobmV3Q29uZmlybVNldHRpbmdzLnBvc2l0aW9uID09PSAnbGVmdC10b3AnKSB7IC8vIGlmIGxlZnQtdG9wXHJcblxyXG4gICAgICAgICAgICBudGZseENvbmZpcm1XcmFwLnN0eWxlLmxlZnQgPSBuZXdDb25maXJtU2V0dGluZ3MuZGlzdGFuY2U7XHJcbiAgICAgICAgICAgIG50Zmx4Q29uZmlybVdyYXAuc3R5bGUudG9wID0gbmV3Q29uZmlybVNldHRpbmdzLmRpc3RhbmNlO1xyXG4gICAgICAgICAgICBudGZseENvbmZpcm1XcmFwLnN0eWxlLnJpZ2h0ID0gJ2F1dG8nO1xyXG4gICAgICAgICAgICBudGZseENvbmZpcm1XcmFwLnN0eWxlLmJvdHRvbSA9ICdhdXRvJztcclxuXHJcbiAgICAgICAgfSBlbHNlIGlmIChuZXdDb25maXJtU2V0dGluZ3MucG9zaXRpb24gPT09ICdsZWZ0LWJvdHRvbScpIHsgLy8gaWYgbGVmdC1ib3R0b21cclxuXHJcbiAgICAgICAgICAgIG50Zmx4Q29uZmlybVdyYXAuc3R5bGUubGVmdCA9IG5ld0NvbmZpcm1TZXR0aW5ncy5kaXN0YW5jZTtcclxuICAgICAgICAgICAgbnRmbHhDb25maXJtV3JhcC5zdHlsZS5ib3R0b20gPSBuZXdDb25maXJtU2V0dGluZ3MuZGlzdGFuY2U7XHJcbiAgICAgICAgICAgIG50Zmx4Q29uZmlybVdyYXAuc3R5bGUudG9wID0gJ2F1dG8nO1xyXG4gICAgICAgICAgICBudGZseENvbmZpcm1XcmFwLnN0eWxlLnJpZ2h0ID0gJ2F1dG8nO1xyXG5cclxuICAgICAgICB9IGVsc2UgeyAvLyBpZiBjZW50ZXItdG9wIG9yIGVsc2VcclxuXHJcbiAgICAgICAgICAgIG50Zmx4Q29uZmlybVdyYXAuc3R5bGUudG9wID0gbmV3Q29uZmlybVNldHRpbmdzLmRpc3RhbmNlO1xyXG4gICAgICAgICAgICBudGZseENvbmZpcm1XcmFwLnN0eWxlLmxlZnQgPSAwO1xyXG4gICAgICAgICAgICBudGZseENvbmZpcm1XcmFwLnN0eWxlLnJpZ2h0ID0gMDtcclxuICAgICAgICAgICAgbnRmbHhDb25maXJtV3JhcC5zdHlsZS5ib3R0b20gPSAnYXV0byc7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIC8vIHBvc2l0aW9uIG9mZlxyXG5cclxuICAgICAgICAvLyBidXR0b25zIGxpc3RlbmVyIG9uXHJcbiAgICAgICAgY29uc3QgY29uZmlybUNsb3NlV3JhcCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKG50Zmx4Q29uZmlybVdyYXAuaWQpO1xyXG4gICAgICAgIGNvbnN0IG9rQnV0dG9uID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ05YQ29uZmlybUJ1dHRvbk9rJyk7XHJcblxyXG4gICAgICAgIC8vIG9rIGJ1dHRvbiBsaXN0ZW5lciBvblxyXG4gICAgICAgIG9rQnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgZnVuY3Rpb24gKCkge1xyXG5cclxuICAgICAgICAgICAgLy8gaWYgb2sgY2FsbGJhY2sgJiYgaWYgb2sgY2FsbGJhY2sgaXMgYSBmdW5jdGlvblxyXG4gICAgICAgICAgICBpZiAob2tCdXR0b25DYWxsYmFjayAmJiB0eXBlb2Ygb2tCdXR0b25DYWxsYmFjayA9PT0gJ2Z1bmN0aW9uJykge1xyXG4gICAgICAgICAgICAgICAgb2tCdXR0b25DYWxsYmFjaygpO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBjb25maXJtQ2xvc2VXcmFwLmNsYXNzTGlzdC5hZGQoJ3JlbW92ZScpO1xyXG5cclxuICAgICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICBjb25maXJtQ2xvc2VXcmFwLnBhcmVudE5vZGUucmVtb3ZlQ2hpbGQoY29uZmlybUNsb3NlV3JhcCk7XHJcbiAgICAgICAgICAgIH0sIG5ld0NvbmZpcm1TZXR0aW5ncy5jc3NBbmltYXRpb25EdXJhdGlvbik7XHJcblxyXG4gICAgICAgIH0pO1xyXG4gICAgICAgIC8vIG9rIGJ1dHRvbiBsaXN0ZW5lciBvZmZcclxuXHJcbiAgICAgICAgLy8gaWYgb2sgY2FsbGJhY2sgJiYgaWYgb2sgY2FsbGJhY2sgYSBmdW5jdGlvbiA9PiBhZGQgQ2FuY2VsIEJ1dHRvbiBsaXN0ZW5lciBvblxyXG4gICAgICAgIGlmIChva0J1dHRvbkNhbGxiYWNrICYmIHR5cGVvZiBva0J1dHRvbkNhbGxiYWNrID09PSAnZnVuY3Rpb24nKSB7XHJcblxyXG4gICAgICAgICAgICAvLyBjYW5jZWwgYnV0dG9uIGxpc3RlbmVyIG9uXHJcbiAgICAgICAgICAgIGNvbnN0IGNhbmNlbEJ1dHRvbiA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdOWENvbmZpcm1CdXR0b25DYW5jZWwnKTtcclxuICAgICAgICAgICAgY2FuY2VsQnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgZnVuY3Rpb24gKCkge1xyXG5cclxuICAgICAgICAgICAgICAgIC8vIGlmIGNhbmNlbCBjYWxsYmFjayAmJiBpZiBjYW5jZWwgY2FsbGJhY2sgYSBmdW5jdGlvblxyXG4gICAgICAgICAgICAgICAgaWYgKGNhbmNlbEJ1dHRvbkNhbGxiYWNrICYmIHR5cGVvZiBjYW5jZWxCdXR0b25DYWxsYmFjayA9PT0gJ2Z1bmN0aW9uJykge1xyXG4gICAgICAgICAgICAgICAgICAgIGNhbmNlbEJ1dHRvbkNhbGxiYWNrKCk7XHJcbiAgICAgICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICAgICAgY29uZmlybUNsb3NlV3JhcC5jbGFzc0xpc3QuYWRkKCdyZW1vdmUnKTtcclxuXHJcbiAgICAgICAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgICAgICAgICBjb25maXJtQ2xvc2VXcmFwLnBhcmVudE5vZGUucmVtb3ZlQ2hpbGQoY29uZmlybUNsb3NlV3JhcCk7XHJcbiAgICAgICAgICAgICAgICB9LCBuZXdDb25maXJtU2V0dGluZ3MuY3NzQW5pbWF0aW9uRHVyYXRpb24pO1xyXG5cclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgIC8vIGNhbmNlbCBidXR0b24gbGlzdGVuZXIgb2ZmXHJcblxyXG4gICAgICAgIH1cclxuICAgICAgICAvLyBpZiBvayBjYWxsYmFjayAmJiBpZiBvayBjYWxsYmFjayBhIGZ1bmN0aW9uID0+IGFkZCBDYW5jZWwgQnV0dG9uIGxpc3RlbmVyIG9mZlxyXG4gICAgICAgIC8vIGJ1dHRvbnMgbGlzdGVuZXIgb2ZmXHJcblxyXG4gICAgfVxyXG4gICAgLy8gaWYgdGhlcmUgaXMgbm8gY29uZmlybSBib3ggb2ZmXHJcblxyXG59XHJcbi8vIE5vdGlmbGl4OiBDb25maXJtIFNpbmdsZSBvZmZcclxuXHJcblxyXG4vLyBOb3RpZmxpeDogTG9hZGluZyBTaW5nbGUgb25cclxuY29uc3QgTm90aWZsaXhMb2FkaW5nID0gZnVuY3Rpb24gKG1lc3NhZ2UsIGljb25UeXBlLCBkaXNwbGF5LCB0aGVEZWxheSkge1xyXG5cclxuICAgIGlmIChkaXNwbGF5KSB7IC8vIHNob3cgaXRcclxuXHJcbiAgICAgICAgLy8gaWYgbWVzc2FnZSBzZXR0aW5ncyBvblxyXG4gICAgICAgIGlmIChtZXNzYWdlLnRvU3RyaW5nKCkubGVuZ3RoID4gbmV3TG9hZGluZ1NldHRpbmdzLm1lc3NhZ2VNYXhMZW5ndGgpIHtcclxuICAgICAgICAgICAgbWVzc2FnZSA9IGAke25vdGlmbGl4UGxhaW50ZXh0KG1lc3NhZ2UpLnRvU3RyaW5nKCkuc3Vic3RyaW5nKDAsIG5ld0xvYWRpbmdTZXR0aW5ncy5tZXNzYWdlTWF4TGVuZ3RoKX0uLi5gO1xyXG4gICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgIG1lc3NhZ2UgPSBgJHtub3RpZmxpeFBsYWludGV4dChtZXNzYWdlKS50b1N0cmluZygpfWA7XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICBsZXQgaW50U3ZnU2l6ZSA9IHBhcnNlSW50KG5ld0xvYWRpbmdTZXR0aW5ncy5zdmdTaXplLnNsaWNlKDAsIC0yKSk7XHJcbiAgICAgICAgbGV0IG1lc3NhZ2VIVE1MID0gJyc7XHJcbiAgICAgICAgaWYgKG1lc3NhZ2UubGVuZ3RoID4gMCkge1xyXG5cclxuICAgICAgICAgICAgbGV0IG1lc3NhZ2VQb3NUb3AgPSBgJHtwYXJzZUludChNYXRoLnJvdW5kKGludFN2Z1NpemUgLSAoaW50U3ZnU2l6ZSAvIDQpKSkudG9TdHJpbmcoKX1weGA7XHJcbiAgICAgICAgICAgIGxldCBtZXNzYWdlSGVpZ2h0ID0gYCR7KHBhcnNlSW50KG5ld0xvYWRpbmdTZXR0aW5ncy5tZXNzYWdlRm9udFNpemUuc2xpY2UoMCwgMikpICogMS4yKS50b1N0cmluZygpfXB4YDtcclxuXHJcbiAgICAgICAgICAgIG1lc3NhZ2VIVE1MID0gYDxwIGlkPVwiJHtuZXdMb2FkaW5nU2V0dGluZ3MubWVzc2FnZUlEfVwiIGNsYXNzPVwibG9hZGluZy1tZXNzYWdlXCIgc3R5bGU9XCJjb2xvcjoke25ld0xvYWRpbmdTZXR0aW5ncy5tZXNzYWdlQ29sb3J9O2ZvbnQtc2l6ZToke25ld0xvYWRpbmdTZXR0aW5ncy5tZXNzYWdlRm9udFNpemV9O2hlaWdodDoke21lc3NhZ2VIZWlnaHR9OyB0b3A6JHttZXNzYWdlUG9zVG9wfTtcIj4ke21lc3NhZ2V9PC9wPmA7XHJcblxyXG4gICAgICAgIH1cclxuICAgICAgICAvLyBpZiBtZXNzYWdlIHNldHRpbmdzIG9mZlxyXG5cclxuICAgICAgICAvLyBpZiBjc3NBbmltYWlvbiBmYWxzZSAtPiBkdXJhdGlvbiBvblxyXG4gICAgICAgIGlmICghbmV3TG9hZGluZ1NldHRpbmdzLmNzc0FuaW1hdGlvbikge1xyXG4gICAgICAgICAgICBuZXdMb2FkaW5nU2V0dGluZ3MuY3NzQW5pbWF0aW9uRHVyYXRpb24gPSAwO1xyXG4gICAgICAgIH1cclxuICAgICAgICAvLyBpZiBjc3NBbmltYWlvbiBmYWxzZSAtPiBkdXJhdGlvbiBvZmZcclxuXHJcbiAgICAgICAgLy8gc3ZnSWNvbiBvblxyXG4gICAgICAgIGxldCBzdmdJY29uID0gJyc7XHJcbiAgICAgICAgaWYgKGljb25UeXBlID09PSAnc3RhbmRhcmQnKSB7XHJcbiAgICAgICAgICAgIHN2Z0ljb24gPSBub3RpZmxpeExvYWRpbmdTdmdTdGFuZGFyZChuZXdMb2FkaW5nU2V0dGluZ3Muc3ZnU2l6ZSwgbmV3TG9hZGluZ1NldHRpbmdzLnN2Z0NvbG9yKTtcclxuICAgICAgICB9IGVsc2UgaWYgKGljb25UeXBlID09PSAnaG91cmdsYXNzJykge1xyXG4gICAgICAgICAgICBzdmdJY29uID0gbm90aWZsaXhMb2FkaW5nU3ZnSG91cmdsYXNzKG5ld0xvYWRpbmdTZXR0aW5ncy5zdmdTaXplLCBuZXdMb2FkaW5nU2V0dGluZ3Muc3ZnQ29sb3IpO1xyXG4gICAgICAgIH0gZWxzZSBpZiAoaWNvblR5cGUgPT09ICdjaXJjbGUnKSB7XHJcbiAgICAgICAgICAgIHN2Z0ljb24gPSBub3RpZmxpeExvYWRpbmdTdmdDaXJjbGUobmV3TG9hZGluZ1NldHRpbmdzLnN2Z1NpemUsIG5ld0xvYWRpbmdTZXR0aW5ncy5zdmdDb2xvcik7XHJcbiAgICAgICAgfSBlbHNlIGlmIChpY29uVHlwZSA9PT0gJ2Fycm93cycpIHtcclxuICAgICAgICAgICAgc3ZnSWNvbiA9IG5vdGlmbGl4TG9hZGluZ1N2Z0Fycm93cyhuZXdMb2FkaW5nU2V0dGluZ3Muc3ZnU2l6ZSwgbmV3TG9hZGluZ1NldHRpbmdzLnN2Z0NvbG9yKTtcclxuICAgICAgICB9IGVsc2UgaWYgKGljb25UeXBlID09PSAnZG90cycpIHtcclxuICAgICAgICAgICAgc3ZnSWNvbiA9IG5vdGlmbGl4TG9hZGluZ1N2Z0RvdHMobmV3TG9hZGluZ1NldHRpbmdzLnN2Z1NpemUsIG5ld0xvYWRpbmdTZXR0aW5ncy5zdmdDb2xvcik7XHJcbiAgICAgICAgfSBlbHNlIGlmIChpY29uVHlwZSA9PT0gJ3B1bHNlJykge1xyXG4gICAgICAgICAgICBzdmdJY29uID0gbm90aWZsaXhMb2FkaW5nU3ZnUHVsc2UobmV3TG9hZGluZ1NldHRpbmdzLnN2Z1NpemUsIG5ld0xvYWRpbmdTZXR0aW5ncy5zdmdDb2xvcik7XHJcbiAgICAgICAgfSBlbHNlIGlmIChpY29uVHlwZSA9PT0gJ2N1c3RvbScgJiYgbmV3TG9hZGluZ1NldHRpbmdzLmN1c3RvbVN2Z1VybCAhPT0gbnVsbCkge1xyXG4gICAgICAgICAgICBzdmdJY29uID0gYDxpbWcgY2xhc3M9XCJjdXN0b20tbG9hZGluZy1pY29uXCIgd2lkdGg9XCIke25ld0xvYWRpbmdTZXR0aW5ncy5zdmdTaXplfVwiIGhlaWdodD1cIiR7bmV3TG9hZGluZ1NldHRpbmdzLnN2Z1NpemV9XCIgc3JjPVwiJHtuZXdMb2FkaW5nU2V0dGluZ3MuY3VzdG9tU3ZnVXJsfVwiIGFsdD1cIk5vdGlmbGl4XCI+YDtcclxuICAgICAgICB9IGVsc2UgaWYgKGljb25UeXBlID09PSAnY3VzdG9tJyAmJiBuZXdMb2FkaW5nU2V0dGluZ3MuY3VzdG9tU3ZnVXJsID09IG51bGwpIHtcclxuICAgICAgICAgICAgbm90aWZsaXhDb25zb2xlRXJyb3IoJ05vdGlmbGl4IEVycm9yJywgJ1lvdSBoYXZlIHRvIHNldCBhIHN0YXRpYyBTVkcgdXJsIHRvIFwiY3VzdG9tU3ZnVXJsXCIgb3B0aW9uIHRvIHVzZSBMb2FkaW5nIEN1c3RvbS4nKTtcclxuICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xyXG4gICAgICAgIH0gZWxzZSBpZiAoaWNvblR5cGUgPT09ICdub3RpZmxpeCcpIHtcclxuICAgICAgICAgICAgc3ZnSWNvbiA9IG5vdGlmbGl4TG9hZGluZ1N2Z05vdGlmbGl4KG5ld0xvYWRpbmdTZXR0aW5ncy5zdmdTaXplLCAnI2Y4ZjhmOCcsICcjMDBiNDYyJyk7XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICBsZXQgc3ZnUG9zVG9wID0gMDtcclxuICAgICAgICBpZiAobWVzc2FnZS5sZW5ndGggPiAwKSB7XHJcbiAgICAgICAgICAgIHN2Z1Bvc1RvcCA9IGAtJHtwYXJzZUludChNYXRoLnJvdW5kKGludFN2Z1NpemUgLSAoaW50U3ZnU2l6ZSAvIDQpKSkudG9TdHJpbmcoKX1weGA7XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICBsZXQgc3ZnSWNvbkhUTUwgPSBgPGRpdiBzdHlsZT1cInRvcDoke3N2Z1Bvc1RvcH07IHdpZHRoOiR7bmV3TG9hZGluZ1NldHRpbmdzLnN2Z1NpemV9OyBoZWlnaHQ6JHtuZXdMb2FkaW5nU2V0dGluZ3Muc3ZnU2l6ZX07XCIgY2xhc3M9XCIke25ld0xvYWRpbmdTZXR0aW5ncy5jbGFzc05hbWV9LWljb24gJHsobWVzc2FnZS5sZW5ndGggPiAwID8gJ3dpdGgtbWVzc2FnZScgOiAnJyl9XCI+JHtzdmdJY29ufTwvZGl2PmA7XHJcbiAgICAgICAgLy8gc3ZnSWNvbiBvZmZcclxuXHJcblxyXG4gICAgICAgIC8vIGxvYWRpbmcgd3JhcCBvblxyXG4gICAgICAgIGNvbnN0IGRvY0JvZHkgPSBkb2N1bWVudC5ib2R5O1xyXG5cclxuICAgICAgICBjb25zdCBudGZseExvYWRpbmdXcmFwID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XHJcbiAgICAgICAgbnRmbHhMb2FkaW5nV3JhcC5pZCA9IGxvYWRpbmdTZXR0aW5ncy5JRDtcclxuICAgICAgICBudGZseExvYWRpbmdXcmFwLmNsYXNzTmFtZSA9IGAke25ld0xvYWRpbmdTZXR0aW5ncy5jbGFzc05hbWV9ICR7KG5ld0xvYWRpbmdTZXR0aW5ncy5jc3NBbmltYXRpb24gPyAnd2l0aC1hbmltYXRpb24nIDogJycpfSAkeyhuZXdMb2FkaW5nU2V0dGluZ3MuY2xpY2tUb0Nsb3NlID8gJ2NsaWNrLXRvLWNsb3NlJyA6ICcnKX1gO1xyXG4gICAgICAgIG50Zmx4TG9hZGluZ1dyYXAuc3R5bGUuekluZGV4ID0gbmV3TG9hZGluZ1NldHRpbmdzLnppbmRleDtcclxuICAgICAgICBudGZseExvYWRpbmdXcmFwLnN0eWxlLmJhY2tncm91bmQgPSBuZXdMb2FkaW5nU2V0dGluZ3MuYmFja2dyb3VuZENvbG9yO1xyXG4gICAgICAgIG50Zmx4TG9hZGluZ1dyYXAuc3R5bGUuYW5pbWF0aW9uRHVyYXRpb24gPSBgJHtuZXdMb2FkaW5nU2V0dGluZ3MuY3NzQW5pbWF0aW9uRHVyYXRpb259bXNgO1xyXG5cclxuICAgICAgICAvLyBmb250LWZhbWlseSBvblxyXG4gICAgICAgIG50Zmx4TG9hZGluZ1dyYXAuc3R5bGUuZm9udEZhbWlseSA9IGBcIiR7bmV3TG9hZGluZ1NldHRpbmdzLmZvbnRGYW1pbHl9XCIsIHNhbnMtc2VyaWZgO1xyXG4gICAgICAgIC8vIGZvbnQtZmFtaWx5IG9mZlxyXG5cclxuICAgICAgICAvLyBydGwgb25cclxuICAgICAgICBpZiAobmV3TG9hZGluZ1NldHRpbmdzLnJ0bCkge1xyXG4gICAgICAgICAgICBudGZseExvYWRpbmdXcmFwLnNldEF0dHJpYnV0ZSgnZGlyJywgJ3J0bCcpO1xyXG4gICAgICAgICAgICBudGZseExvYWRpbmdXcmFwLmNsYXNzTGlzdC5hZGQoJ3J0bC1vbicpO1xyXG4gICAgICAgIH1cclxuICAgICAgICAvLyBydGwgb2ZmXHJcblxyXG4gICAgICAgIC8vIGFwcGVuZCBvblxyXG4gICAgICAgIG50Zmx4TG9hZGluZ1dyYXAuaW5uZXJIVE1MID0gYCR7c3ZnSWNvbkhUTUx9ICR7bWVzc2FnZUhUTUx9YDsgLy8gaW5uZXIgaHRtbFxyXG5cclxuICAgICAgICBpZiAoIWRvY3VtZW50LmdldEVsZW1lbnRCeUlkKG50Zmx4TG9hZGluZ1dyYXAuaWQpKSB7IC8vIGlmIG5vdCBsb2FkaW5nXHJcblxyXG4gICAgICAgICAgICBkb2NCb2R5LmFwcGVuZENoaWxkKG50Zmx4TG9hZGluZ1dyYXApOyAvLyBhcHBlbmRcclxuXHJcbiAgICAgICAgICAgIC8vIGlmIGNsaWNrIHRvIGNsb3NlIG9uICAgICAgICAgICAgXHJcbiAgICAgICAgICAgIGlmIChuZXdMb2FkaW5nU2V0dGluZ3MuY2xpY2tUb0Nsb3NlKSB7XHJcblxyXG4gICAgICAgICAgICAgICAgY29uc3QgbG9hZGluZ1dyYXBFbG0gPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZChudGZseExvYWRpbmdXcmFwLmlkKTtcclxuXHJcbiAgICAgICAgICAgICAgICBsb2FkaW5nV3JhcEVsbS5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGZ1bmN0aW9uICgpIHtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgbnRmbHhMb2FkaW5nV3JhcC5jbGFzc0xpc3QuYWRkKCdyZW1vdmUnKTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIG50Zmx4TG9hZGluZ1dyYXAucGFyZW50Tm9kZS5yZW1vdmVDaGlsZChudGZseExvYWRpbmdXcmFwKTtcclxuICAgICAgICAgICAgICAgICAgICB9LCBuZXdMb2FkaW5nU2V0dGluZ3MuY3NzQW5pbWF0aW9uRHVyYXRpb24pO1xyXG5cclxuICAgICAgICAgICAgICAgIH0pO1xyXG5cclxuICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAvLyBpZiBjbGljayB0byBjbG9zZSBvZmZcclxuICAgICAgICB9XHJcbiAgICAgICAgLy8gYXBwZW5kIG9mZlxyXG5cclxuICAgIH0gZWxzZSB7IC8vIFJlbW92ZVxyXG5cclxuICAgICAgICBpZiAoZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQobG9hZGluZ1NldHRpbmdzLklEKSkgeyAvLyBpZiBoYXMgYW55IGxvYWRpbmdcclxuXHJcbiAgICAgICAgICAgIGNvbnN0IGxvYWRpbmdFbG0gPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZChsb2FkaW5nU2V0dGluZ3MuSUQpO1xyXG5cclxuICAgICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XHJcblxyXG4gICAgICAgICAgICAgICAgbG9hZGluZ0VsbS5jbGFzc0xpc3QuYWRkKCdyZW1vdmUnKTtcclxuXHJcbiAgICAgICAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgICAgICAgICBsb2FkaW5nRWxtLnBhcmVudE5vZGUucmVtb3ZlQ2hpbGQobG9hZGluZ0VsbSk7XHJcbiAgICAgICAgICAgICAgICB9LCBuZXdMb2FkaW5nU2V0dGluZ3MuY3NzQW5pbWF0aW9uRHVyYXRpb24pO1xyXG5cclxuICAgICAgICAgICAgfSwgdGhlRGVsYXkpO1xyXG5cclxuICAgICAgICB9XHJcblxyXG4gICAgfVxyXG5cclxufVxyXG4vLyBOb3RpZmxpeDogTG9hZGluZyBTaW5nbGUgb2ZmXHJcblxyXG4vLyBOb3RpZmxpeDogTG9hZGluZyBDaGFuZ2UgTWVzc2FnZSBvblxyXG5jb25zdCBOb3RpZmxpeExvYWRpbmdDaGFuZ2UgPSBmdW5jdGlvbiAobmV3TWVzc2FnZSkge1xyXG5cclxuICAgIGlmIChkb2N1bWVudC5nZXRFbGVtZW50QnlJZChsb2FkaW5nU2V0dGluZ3MuSUQpKSB7IC8vIGlmIGhhcyBhbnkgbG9hZGluZ1xyXG5cclxuICAgICAgICBpZiAobmV3TWVzc2FnZS50b1N0cmluZygpLmxlbmd0aCA+IG5ld0xvYWRpbmdTZXR0aW5ncy5tZXNzYWdlTWF4TGVuZ3RoKSB7XHJcbiAgICAgICAgICAgIG5ld01lc3NhZ2UgPSBgJHtub3RpZmxpeFBsYWludGV4dChuZXdNZXNzYWdlKS50b1N0cmluZygpLnN1YnN0cmluZygwLCBuZXdMb2FkaW5nU2V0dGluZ3MubWVzc2FnZU1heExlbmd0aCl9Li4uYDtcclxuICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICBuZXdNZXNzYWdlID0gbm90aWZsaXhQbGFpbnRleHQobmV3TWVzc2FnZSkudG9TdHJpbmcoKTtcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIGlmIChuZXdNZXNzYWdlLmxlbmd0aCA+IDApIHsgLy8gaWYgaGFzIGFueSBtZXNzYWdlXHJcblxyXG4gICAgICAgICAgICBsZXQgb2xkTWVzc2FnZUVsbSA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKGxvYWRpbmdTZXR0aW5ncy5JRCkuZ2V0RWxlbWVudHNCeVRhZ05hbWUoJ3AnKVswXTtcclxuXHJcbiAgICAgICAgICAgIGlmIChvbGRNZXNzYWdlRWxtICE9PSB1bmRlZmluZWQpIHsgLy8gdGhlcmUgaXMgYSBtZXNzYWdlIGVsZW1lbnRcclxuXHJcbiAgICAgICAgICAgICAgICBvbGRNZXNzYWdlRWxtLmlubmVySFRNTCA9IG5ld01lc3NhZ2U7IC8vIGNoYW5nZSB0aGUgbWVzc2FnZVxyXG5cclxuICAgICAgICAgICAgfSBlbHNlIHsgLy8gdGhlcmUgaXMgbm8gbWVzc2FnZSBlbGVtZW50XHJcblxyXG4gICAgICAgICAgICAgICAgLy8gY3JlYXRlIGEgbmV3IG1lc3NhZ2UgZWxlbWVudCBvblxyXG4gICAgICAgICAgICAgICAgY29uc3QgbmV3TWVzc2FnZUhUTUwgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdwJyk7XHJcbiAgICAgICAgICAgICAgICBuZXdNZXNzYWdlSFRNTC5pZCA9IG5ld0xvYWRpbmdTZXR0aW5ncy5tZXNzYWdlSUQ7XHJcbiAgICAgICAgICAgICAgICBuZXdNZXNzYWdlSFRNTC5jbGFzc05hbWUgPSAnbG9hZGluZy1tZXNzYWdlIG5ldyc7XHJcblxyXG4gICAgICAgICAgICAgICAgbmV3TWVzc2FnZUhUTUwuc3R5bGUuY29sb3IgPSBuZXdMb2FkaW5nU2V0dGluZ3MubWVzc2FnZUNvbG9yO1xyXG4gICAgICAgICAgICAgICAgbmV3TWVzc2FnZUhUTUwuc3R5bGUuZm9udFNpemUgPSBuZXdMb2FkaW5nU2V0dGluZ3MubWVzc2FnZUZvbnRTaXplO1xyXG5cclxuICAgICAgICAgICAgICAgIGNvbnN0IGludFN2Z1NpemUgPSBwYXJzZUludChuZXdMb2FkaW5nU2V0dGluZ3Muc3ZnU2l6ZS5zbGljZSgwLCAtMikpO1xyXG4gICAgICAgICAgICAgICAgY29uc3QgbWVzc2FnZVBvc1RvcCA9IGAke3BhcnNlSW50KE1hdGgucm91bmQoaW50U3ZnU2l6ZSAtIChpbnRTdmdTaXplIC8gNCkpKS50b1N0cmluZygpfXB4YDtcclxuICAgICAgICAgICAgICAgIG5ld01lc3NhZ2VIVE1MLnN0eWxlLnRvcCA9IG1lc3NhZ2VQb3NUb3A7XHJcblxyXG4gICAgICAgICAgICAgICAgY29uc3QgbWVzc2FnZUhlaWdodCA9IGAkeyhwYXJzZUludChuZXdMb2FkaW5nU2V0dGluZ3MubWVzc2FnZUZvbnRTaXplLnNsaWNlKDAsIDIpKSAqIDEuMikudG9TdHJpbmcoKX1weGA7XHJcbiAgICAgICAgICAgICAgICBuZXdNZXNzYWdlSFRNTC5zdHlsZS5oZWlnaHQgPSBtZXNzYWdlSGVpZ2h0O1xyXG5cclxuICAgICAgICAgICAgICAgIG5ld01lc3NhZ2VIVE1MLmlubmVySFRNTCA9IG5ld01lc3NhZ2U7XHJcblxyXG4gICAgICAgICAgICAgICAgY29uc3QgbWVzc2FnZVdyYXAgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZChsb2FkaW5nU2V0dGluZ3MuSUQpO1xyXG4gICAgICAgICAgICAgICAgbWVzc2FnZVdyYXAuYXBwZW5kQ2hpbGQobmV3TWVzc2FnZUhUTUwpO1xyXG4gICAgICAgICAgICAgICAgLy8gY3JlYXRlIGEgbmV3IG1lc3NhZ2UgZWxlbWVudCBvZmZcclxuXHJcbiAgICAgICAgICAgICAgICAvLyB2ZXJ0aWNhbCBhbGlnbiBzdmcgb25cclxuICAgICAgICAgICAgICAgIGNvbnN0IHN2Z0RpdkVsbSA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKGxvYWRpbmdTZXR0aW5ncy5JRCkuZ2V0RWxlbWVudHNCeVRhZ05hbWUoJ2RpdicpWzBdO1xyXG4gICAgICAgICAgICAgICAgY29uc3Qgc3ZnTmV3UG9zVG9wID0gYC0ke3BhcnNlSW50KE1hdGgucm91bmQoaW50U3ZnU2l6ZSAtIChpbnRTdmdTaXplIC8gNCkpKS50b1N0cmluZygpfXB4YDtcclxuICAgICAgICAgICAgICAgIHN2Z0RpdkVsbS5zdHlsZS50b3AgPSBzdmdOZXdQb3NUb3A7XHJcbiAgICAgICAgICAgICAgICAvLyB2ZXJ0aWNhbCBhbGlnbiBzdmcgb2ZmXHJcblxyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgIH0gZWxzZSB7IC8vIGlmIG5vIG1lc3NhZ2VcclxuICAgICAgICAgICAgbm90aWZsaXhDb25zb2xlRXJyb3IoJ05vdGlmbGl4IEVycm9yJywgJ1doZXJlIGlzIHRoZSBuZXcgbWVzc2FnZT8nKTtcclxuICAgICAgICB9XHJcblxyXG4gICAgfVxyXG5cclxufVxyXG4vLyBOb3RpZmxpeDogTG9hZGluZyBDaGFuZ2UgTWVzc2FnZSBvZmZcclxuXHJcblxyXG4vLyBOb3RpZmxpeDogTG9hZGluZyBTVkcgc3RhbmRhcmQgb25cclxuY29uc3Qgbm90aWZsaXhMb2FkaW5nU3ZnU3RhbmRhcmQgPSAod2lkdGgsIGNvbG9yKSA9PiB7XHJcbiAgICBpZiAoIXdpZHRoKSB7IHdpZHRoID0gJzYwcHgnOyB9XHJcbiAgICBpZiAoIWNvbG9yKSB7IGNvbG9yID0gJyMwMGI0NjInOyB9XHJcbiAgICBjb25zdCBzdGFuZGFyZCA9IGA8c3ZnIHN0cm9rZT1cIiR7Y29sb3J9XCIgd2lkdGg9XCIke3dpZHRofVwiIGhlaWdodD1cIiR7d2lkdGh9XCIgdmlld0JveD1cIjAgMCAzOCAzOFwiIHN0eWxlPVwidHJhbnNmb3JtOnNjYWxlKDAuOCk7XCIgeG1sbnM9XCJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2Z1wiPjxnIGZpbGw9XCJub25lXCIgZmlsbC1ydWxlPVwiZXZlbm9kZFwiPjxnIHRyYW5zZm9ybT1cInRyYW5zbGF0ZSgxIDEpXCIgc3Ryb2tlLXdpZHRoPVwiMlwiPjxjaXJjbGUgc3Ryb2tlLW9wYWNpdHk9XCIuMjVcIiBjeD1cIjE4XCIgY3k9XCIxOFwiIHI9XCIxOFwiLz48cGF0aCBkPVwiTTM2IDE4YzAtOS45NC04LjA2LTE4LTE4LTE4XCI+PGFuaW1hdGVUcmFuc2Zvcm0gYXR0cmlidXRlTmFtZT1cInRyYW5zZm9ybVwiIHR5cGU9XCJyb3RhdGVcIiBmcm9tPVwiMCAxOCAxOFwiIHRvPVwiMzYwIDE4IDE4XCIgZHVyPVwiMXNcIiByZXBlYXRDb3VudD1cImluZGVmaW5pdGVcIi8+PC9wYXRoPjwvZz48L2c+PC9zdmc+YDtcclxuICAgIHJldHVybiBzdGFuZGFyZDtcclxufVxyXG4vLyBOb3RpZmxpeDogTG9hZGluZyBTVkcgc3RhbmRhcmQgb2ZmXHJcblxyXG4vLyBOb3RpZmxpeDogTG9hZGluZyBTVkcgaG91cmdsYXNzIG9uXHJcbmNvbnN0IG5vdGlmbGl4TG9hZGluZ1N2Z0hvdXJnbGFzcyA9ICh3aWR0aCwgY29sb3IpID0+IHtcclxuICAgIGlmICghd2lkdGgpIHsgd2lkdGggPSAnNjBweCc7IH1cclxuICAgIGlmICghY29sb3IpIHsgY29sb3IgPSAnIzAwYjQ2Mic7IH1cclxuICAgIGNvbnN0IGhvdXJnbGFzcyA9IGA8c3ZnIGlkPVwiTlhMb2FkaW5nSG91cmdsYXNzXCIgZmlsbD1cIiR7Y29sb3J9XCIgd2lkdGg9XCIke3dpZHRofVwiIGhlaWdodD1cIiR7d2lkdGh9XCIgeG1sbnM9XCJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2Z1wiIHhtbG5zOnhsaW5rPVwiaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGlua1wiIHhtbDpzcGFjZT1cInByZXNlcnZlXCIgdmVyc2lvbj1cIjEuMVwiIHN0eWxlPVwic2hhcGUtcmVuZGVyaW5nOmdlb21ldHJpY1ByZWNpc2lvbjsgdGV4dC1yZW5kZXJpbmc6Z2VvbWV0cmljUHJlY2lzaW9uOyBpbWFnZS1yZW5kZXJpbmc6b3B0aW1pemVRdWFsaXR5OyBmaWxsLXJ1bGU6ZXZlbm9kZDsgY2xpcC1ydWxlOmV2ZW5vZGRcIiB2aWV3Qm94PVwiMCAwIDIwMCAyMDBcIj48c3R5bGU+QC13ZWJraXQta2V5ZnJhbWVzIE5YaG91cmdsYXNzNS1hbmltYXRpb257MCV7LXdlYmtpdC10cmFuc2Zvcm06IHNjYWxlKDEsIDEpO3RyYW5zZm9ybTogc2NhbGUoMSwgMSk7fTE2LjY3JXstd2Via2l0LXRyYW5zZm9ybTogc2NhbGUoMSwgMC44KTt0cmFuc2Zvcm06IHNjYWxlKDEsIDAuOCk7fTMzLjMzJXstd2Via2l0LXRyYW5zZm9ybTogc2NhbGUoMC44OCwgMC42KTt0cmFuc2Zvcm06IHNjYWxlKDAuODgsIDAuNik7fTM3LjUwJXstd2Via2l0LXRyYW5zZm9ybTogc2NhbGUoMC44NSwgMC41NSk7dHJhbnNmb3JtOiBzY2FsZSgwLjg1LCAwLjU1KTt9NDEuNjcley13ZWJraXQtdHJhbnNmb3JtOiBzY2FsZSgwLjgsIDAuNSk7dHJhbnNmb3JtOiBzY2FsZSgwLjgsIDAuNSk7fTQ1LjgzJXstd2Via2l0LXRyYW5zZm9ybTogc2NhbGUoMC43NSwgMC40NSk7dHJhbnNmb3JtOiBzY2FsZSgwLjc1LCAwLjQ1KTt9NTAley13ZWJraXQtdHJhbnNmb3JtOiBzY2FsZSgwLjcsIDAuNCk7dHJhbnNmb3JtOiBzY2FsZSgwLjcsIDAuNCk7fTU0LjE3JXstd2Via2l0LXRyYW5zZm9ybTogc2NhbGUoMC42LCAwLjM1KTt0cmFuc2Zvcm06IHNjYWxlKDAuNiwgMC4zNSk7fTU4LjMzJXstd2Via2l0LXRyYW5zZm9ybTogc2NhbGUoMC41LCAwLjMpO3RyYW5zZm9ybTogc2NhbGUoMC41LCAwLjMpO304My4zMyV7LXdlYmtpdC10cmFuc2Zvcm06IHNjYWxlKDAuMiwgMCk7dHJhbnNmb3JtOiBzY2FsZSgwLjIsIDApO30xMDAley13ZWJraXQtdHJhbnNmb3JtOiBzY2FsZSgwLjIsIDApO3RyYW5zZm9ybTogc2NhbGUoMC4yLCAwKTt9fUBrZXlmcmFtZXMgTlhob3VyZ2xhc3M1LWFuaW1hdGlvbnswJXstd2Via2l0LXRyYW5zZm9ybTogc2NhbGUoMSwgMSk7dHJhbnNmb3JtOiBzY2FsZSgxLCAxKTt9MTYuNjcley13ZWJraXQtdHJhbnNmb3JtOiBzY2FsZSgxLCAwLjgpO3RyYW5zZm9ybTogc2NhbGUoMSwgMC44KTt9MzMuMzMley13ZWJraXQtdHJhbnNmb3JtOiBzY2FsZSgwLjg4LCAwLjYpO3RyYW5zZm9ybTogc2NhbGUoMC44OCwgMC42KTt9MzcuNTAley13ZWJraXQtdHJhbnNmb3JtOiBzY2FsZSgwLjg1LCAwLjU1KTt0cmFuc2Zvcm06IHNjYWxlKDAuODUsIDAuNTUpO300MS42NyV7LXdlYmtpdC10cmFuc2Zvcm06IHNjYWxlKDAuOCwgMC41KTt0cmFuc2Zvcm06IHNjYWxlKDAuOCwgMC41KTt9NDUuODMley13ZWJraXQtdHJhbnNmb3JtOiBzY2FsZSgwLjc1LCAwLjQ1KTt0cmFuc2Zvcm06IHNjYWxlKDAuNzUsIDAuNDUpO301MCV7LXdlYmtpdC10cmFuc2Zvcm06IHNjYWxlKDAuNywgMC40KTt0cmFuc2Zvcm06IHNjYWxlKDAuNywgMC40KTt9NTQuMTcley13ZWJraXQtdHJhbnNmb3JtOiBzY2FsZSgwLjYsIDAuMzUpO3RyYW5zZm9ybTogc2NhbGUoMC42LCAwLjM1KTt9NTguMzMley13ZWJraXQtdHJhbnNmb3JtOiBzY2FsZSgwLjUsIDAuMyk7dHJhbnNmb3JtOiBzY2FsZSgwLjUsIDAuMyk7fTgzLjMzJXstd2Via2l0LXRyYW5zZm9ybTogc2NhbGUoMC4yLCAwKTt0cmFuc2Zvcm06IHNjYWxlKDAuMiwgMCk7fTEwMCV7LXdlYmtpdC10cmFuc2Zvcm06IHNjYWxlKDAuMiwgMCk7dHJhbnNmb3JtOiBzY2FsZSgwLjIsIDApO319QC13ZWJraXQta2V5ZnJhbWVzIE5YaG91cmdsYXNzMy1hbmltYXRpb257MCV7LXdlYmtpdC10cmFuc2Zvcm06IHNjYWxlKDEsIDAuMDIpO3RyYW5zZm9ybTogc2NhbGUoMSwgMC4wMik7fTc5LjE3JXstd2Via2l0LXRyYW5zZm9ybTogc2NhbGUoMSwgMSk7dHJhbnNmb3JtOiBzY2FsZSgxLCAxKTt9MTAwJXstd2Via2l0LXRyYW5zZm9ybTogc2NhbGUoMSwgMSk7dHJhbnNmb3JtOiBzY2FsZSgxLCAxKTt9fUBrZXlmcmFtZXMgTlhob3VyZ2xhc3MzLWFuaW1hdGlvbnswJXstd2Via2l0LXRyYW5zZm9ybTogc2NhbGUoMSwgMC4wMik7dHJhbnNmb3JtOiBzY2FsZSgxLCAwLjAyKTt9NzkuMTcley13ZWJraXQtdHJhbnNmb3JtOiBzY2FsZSgxLCAxKTt0cmFuc2Zvcm06IHNjYWxlKDEsIDEpO30xMDAley13ZWJraXQtdHJhbnNmb3JtOiBzY2FsZSgxLCAxKTt0cmFuc2Zvcm06IHNjYWxlKDEsIDEpO319QC13ZWJraXQta2V5ZnJhbWVzIE5YaG91cmdsYXNzMS1hbmltYXRpb257MCV7LXdlYmtpdC10cmFuc2Zvcm06IHJvdGF0ZSgwZGVnKTt0cmFuc2Zvcm06IHJvdGF0ZSgwZGVnKTt9ODMuMzMley13ZWJraXQtdHJhbnNmb3JtOiByb3RhdGUoMGRlZyk7dHJhbnNmb3JtOiByb3RhdGUoMGRlZyk7fTEwMCV7LXdlYmtpdC10cmFuc2Zvcm06IHJvdGF0ZSgxODBkZWcpO3RyYW5zZm9ybTogcm90YXRlKDE4MGRlZyk7fX1Aa2V5ZnJhbWVzIE5YaG91cmdsYXNzMS1hbmltYXRpb257MCV7LXdlYmtpdC10cmFuc2Zvcm06IHJvdGF0ZSgwZGVnKTt0cmFuc2Zvcm06IHJvdGF0ZSgwZGVnKTt9ODMuMzMley13ZWJraXQtdHJhbnNmb3JtOiByb3RhdGUoMGRlZyk7dHJhbnNmb3JtOiByb3RhdGUoMGRlZyk7fTEwMCV7LXdlYmtpdC10cmFuc2Zvcm06IHJvdGF0ZSgxODBkZWcpO3RyYW5zZm9ybTogcm90YXRlKDE4MGRlZyk7fX0jTlhMb2FkaW5nSG91cmdsYXNzICp7LXdlYmtpdC1hbmltYXRpb24tZHVyYXRpb246IDEuMnM7YW5pbWF0aW9uLWR1cmF0aW9uOiAxLjJzOy13ZWJraXQtYW5pbWF0aW9uLWl0ZXJhdGlvbi1jb3VudDogaW5maW5pdGU7YW5pbWF0aW9uLWl0ZXJhdGlvbi1jb3VudDogaW5maW5pdGU7LXdlYmtpdC1hbmltYXRpb24tdGltaW5nLWZ1bmN0aW9uOiBjdWJpYy1iZXppZXIoMCwgMCwgMSwgMSk7YW5pbWF0aW9uLXRpbWluZy1mdW5jdGlvbjogY3ViaWMtYmV6aWVyKDAsIDAsIDEsIDEpO30jTlhob3VyZ2xhc3M3e2ZpbGw6IGluaGVyaXQ7fSNOWGhvdXJnbGFzczF7LXdlYmtpdC1hbmltYXRpb24tbmFtZTogTlhob3VyZ2xhc3MxLWFuaW1hdGlvbjthbmltYXRpb24tbmFtZTogTlhob3VyZ2xhc3MxLWFuaW1hdGlvbjstd2Via2l0LXRyYW5zZm9ybS1vcmlnaW46IDUwJSA1MCU7dHJhbnNmb3JtLW9yaWdpbjogNTAlIDUwJTt0cmFuc2Zvcm0tYm94OiBmaWxsLWJveDt9I05YaG91cmdsYXNzM3std2Via2l0LWFuaW1hdGlvbi1uYW1lOiBOWGhvdXJnbGFzczMtYW5pbWF0aW9uO2FuaW1hdGlvbi1uYW1lOiBOWGhvdXJnbGFzczMtYW5pbWF0aW9uOy13ZWJraXQtYW5pbWF0aW9uLXRpbWluZy1mdW5jdGlvbjogY3ViaWMtYmV6aWVyKDAuNDIsIDAsIDAuNTgsIDEpO2FuaW1hdGlvbi10aW1pbmctZnVuY3Rpb246IGN1YmljLWJlemllcigwLjQyLCAwLCAwLjU4LCAxKTstd2Via2l0LXRyYW5zZm9ybS1vcmlnaW46IDUwJSAxMDAlO3RyYW5zZm9ybS1vcmlnaW46IDUwJSAxMDAlO3RyYW5zZm9ybS1ib3g6IGZpbGwtYm94O30jTlhob3VyZ2xhc3M1ey13ZWJraXQtYW5pbWF0aW9uLW5hbWU6IE5YaG91cmdsYXNzNS1hbmltYXRpb247YW5pbWF0aW9uLW5hbWU6IE5YaG91cmdsYXNzNS1hbmltYXRpb247LXdlYmtpdC10cmFuc2Zvcm0tb3JpZ2luOiA1MCUgMTAwJTt0cmFuc2Zvcm0tb3JpZ2luOiA1MCUgMTAwJTt0cmFuc2Zvcm0tYm94OiBmaWxsLWJveDt9ZyNOWGhvdXJnbGFzczUsI05YaG91cmdsYXNzM3tmaWxsOiBpbmhlcml0O29wYWNpdHk6IC40O308L3N0eWxlPjxnIGlkPVwiTlhob3VyZ2xhc3MxXCIgZGF0YS1hbmltYXRvci1ncm91cD1cInRydWVcIiBkYXRhLWFuaW1hdG9yLXR5cGU9XCIxXCI+PGcgaWQ9XCJOWGhvdXJnbGFzczJcIj48ZyBpZD1cIk5YaG91cmdsYXNzM1wiIGRhdGEtYW5pbWF0b3ItZ3JvdXA9XCJ0cnVlXCIgZGF0YS1hbmltYXRvci10eXBlPVwiMlwiPjxwb2x5Z29uIHBvaW50cz1cIjEwMCwxMDAgNjUuNjIsMTMyLjA4IDY1LjYyLDE2My4yMiAxMzQuMzgsMTYzLjIyIDEzNC4zOCwxMzIuMDggXCIgaWQ9XCJOWGhvdXJnbGFzczRcIi8+PC9nPjxnIGlkPVwiTlhob3VyZ2xhc3M1XCIgZGF0YS1hbmltYXRvci1ncm91cD1cInRydWVcIiBkYXRhLWFuaW1hdG9yLXR5cGU9XCIyXCI+PHBvbHlnb24gcG9pbnRzPVwiMTAwLDEwMCA2NS42Miw2Ny45MiA2NS42MiwzNi43OCAxMzQuMzgsMzYuNzggMTM0LjM4LDY3LjkyXCIgaWQ9XCJOWGhvdXJnbGFzczZcIi8+PC9nPiA8cGF0aCBkPVwiTTUxLjE0IDM4Ljg5bDguMzMgMCAwIDE0LjkzYzAsMTUuMSA4LjI5LDI4Ljk5IDIzLjM0LDM5LjEgMS44OCwxLjI1IDMuMDQsMy45NyAzLjA0LDcuMDggMCwzLjExIC0xLjE2LDUuODMgLTMuMDQsNy4wOSAtMTUuMDUsMTAuMSAtMjMuMzQsMjMuOTkgLTIzLjM0LDM5LjA5bDAgMTQuOTMgLTguMzMgMGMtMi42OCwwIC00Ljg2LDIuMTggLTQuODYsNC44NiAwLDIuNjkgMi4xOCw0Ljg2IDQuODYsNC44Nmw5Ny43MiAwYzIuNjgsMCA0Ljg2LC0yLjE3IDQuODYsLTQuODYgMCwtMi42OCAtMi4xOCwtNC44NiAtNC44NiwtNC44NmwtOC4zMyAwIDAgLTE0LjkzYzAsLTE1LjEgLTguMjksLTI4Ljk5IC0yMy4zNCwtMzkuMDkgLTEuODgsLTEuMjYgLTMuMDQsLTMuOTggLTMuMDQsLTcuMDkgMCwtMy4xMSAxLjE2LC01LjgzIDMuMDQsLTcuMDggMTUuMDUsLTEwLjExIDIzLjM0LC0yNCAyMy4zNCwtMzkuMWwwIC0xNC45MyA4LjMzIDBjMi42OCwwIDQuODYsLTIuMTggNC44NiwtNC44NiAwLC0yLjY5IC0yLjE4LC00Ljg2IC00Ljg2LC00Ljg2bC05Ny43MiAwYy0yLjY4LDAgLTQuODYsMi4xNyAtNC44Niw0Ljg2IDAsMi42OCAyLjE4LDQuODYgNC44Niw0Ljg2em03OS42NyAxNC45M2MwLDE1Ljg3IC0xMS45MywyNi4yNSAtMTkuMDQsMzEuMDMgLTQuNiwzLjA4IC03LjM0LDguNzUgLTcuMzQsMTUuMTUgMCw2LjQxIDIuNzQsMTIuMDcgNy4zNCwxNS4xNSA3LjExLDQuNzggMTkuMDQsMTUuMTYgMTkuMDQsMzEuMDNsMCAxNC45MyAtNjEuNjIgMCAwIC0xNC45M2MwLC0xNS44NyAxMS45MywtMjYuMjUgMTkuMDQsLTMxLjAyIDQuNiwtMy4wOSA3LjM0LC04Ljc1IDcuMzQsLTE1LjE2IDAsLTYuNCAtMi43NCwtMTIuMDcgLTcuMzQsLTE1LjE1IC03LjExLC00Ljc4IC0xOS4wNCwtMTUuMTYgLTE5LjA0LC0zMS4wM2wwIC0xNC45MyA2MS42MiAwIDAgMTQuOTN6XCIgaWQ9XCJOWGhvdXJnbGFzczdcIi8+PC9nPjwvZz48L3N2Zz5gO1xyXG4gICAgcmV0dXJuIGhvdXJnbGFzcztcclxufVxyXG4vLyBOb3RpZmxpeDogTG9hZGluZyBTVkcgaG91cmdsYXNzIG9mZlxyXG5cclxuLy8gTm90aWZsaXg6IExvYWRpbmcgU1ZHIGNpcmNsZSBvblxyXG5jb25zdCBub3RpZmxpeExvYWRpbmdTdmdDaXJjbGUgPSAod2lkdGgsIGNvbG9yKSA9PiB7XHJcbiAgICBpZiAoIXdpZHRoKSB7IHdpZHRoID0gJzYwcHgnOyB9XHJcbiAgICBpZiAoIWNvbG9yKSB7IGNvbG9yID0gJyMwMGI0NjInOyB9XHJcbiAgICBjb25zdCBjaXJjbGUgPSBgPHN2ZyBpZD1cIk5YTG9hZGluZ0NpcmNsZVwiIHdpZHRoPVwiJHt3aWR0aH1cIiBoZWlnaHQ9XCIke3dpZHRofVwiIHhtbG5zPVwiaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmdcIiB4bWxuczp4bGluaz1cImh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmtcIiB2aWV3Qm94PVwiMjUgMjUgNTAgNTBcIiB4bWw6c3BhY2U9XCJwcmVzZXJ2ZVwiIHZlcnNpb249XCIxLjFcIj48c3R5bGU+I05YTG9hZGluZ0NpcmNsZXstd2Via2l0LWFuaW1hdGlvbjogcm90YXRlIDJzIGxpbmVhciBpbmZpbml0ZTsgYW5pbWF0aW9uOiByb3RhdGUgMnMgbGluZWFyIGluZmluaXRlOyBoZWlnaHQ6ICR7d2lkdGh9OyAtd2Via2l0LXRyYW5zZm9ybS1vcmlnaW46IGNlbnRlciBjZW50ZXI7IC1tcy10cmFuc2Zvcm0tb3JpZ2luOiBjZW50ZXIgY2VudGVyOyB0cmFuc2Zvcm0tb3JpZ2luOiBjZW50ZXIgY2VudGVyOyB3aWR0aDogJHt3aWR0aH07IHBvc2l0aW9uOiBhYnNvbHV0ZTsgdG9wOiAwOyBsZWZ0OiAwOyBtYXJnaW46IGF1dG87fS5ub3RpZmxpeC1sb2FkZXItY2lyY2xlLXBhdGh7c3Ryb2tlLWRhc2hhcnJheTogMTUwLDIwMDsgc3Ryb2tlLWRhc2hvZmZzZXQ6IC0xMDsgLXdlYmtpdC1hbmltYXRpb246IGRhc2ggMS41cyBlYXNlLWluLW91dCBpbmZpbml0ZSwgY29sb3IgMS41cyBlYXNlLWluLW91dCBpbmZpbml0ZTsgYW5pbWF0aW9uOiBkYXNoIDEuNXMgZWFzZS1pbi1vdXQgaW5maW5pdGUsIGNvbG9yIDEuNXMgZWFzZS1pbi1vdXQgaW5maW5pdGU7IHN0cm9rZS1saW5lY2FwOiByb3VuZDt9QC13ZWJraXQta2V5ZnJhbWVzIHJvdGF0ZXsxMDAley13ZWJraXQtdHJhbnNmb3JtOiByb3RhdGUoMzYwZGVnKTsgdHJhbnNmb3JtOiByb3RhdGUoMzYwZGVnKTt9fUBrZXlmcmFtZXMgcm90YXRlezEwMCV7LXdlYmtpdC10cmFuc2Zvcm06IHJvdGF0ZSgzNjBkZWcpOyB0cmFuc2Zvcm06IHJvdGF0ZSgzNjBkZWcpO319QC13ZWJraXQta2V5ZnJhbWVzIGRhc2h7MCV7c3Ryb2tlLWRhc2hhcnJheTogMSwyMDA7IHN0cm9rZS1kYXNob2Zmc2V0OiAwO301MCV7c3Ryb2tlLWRhc2hhcnJheTogODksMjAwOyBzdHJva2UtZGFzaG9mZnNldDogLTM1O30xMDAle3N0cm9rZS1kYXNoYXJyYXk6IDg5LDIwMDsgc3Ryb2tlLWRhc2hvZmZzZXQ6IC0xMjQ7fX1Aa2V5ZnJhbWVzIGRhc2h7MCV7c3Ryb2tlLWRhc2hhcnJheTogMSwyMDA7IHN0cm9rZS1kYXNob2Zmc2V0OiAwO301MCV7c3Ryb2tlLWRhc2hhcnJheTogODksMjAwOyBzdHJva2UtZGFzaG9mZnNldDogLTM1O30xMDAle3N0cm9rZS1kYXNoYXJyYXk6IDg5LDIwMDsgc3Ryb2tlLWRhc2hvZmZzZXQ6IC0xMjQ7fX08L3N0eWxlPjxjaXJjbGUgY2xhc3M9XCJub3RpZmxpeC1sb2FkZXItY2lyY2xlLXBhdGhcIiBjeD1cIjUwXCIgY3k9XCI1MFwiIHI9XCIyMFwiIGZpbGw9XCJub25lXCIgc3Ryb2tlPVwiJHtjb2xvcn1cIiBzdHJva2Utd2lkdGg9XCIyXCIvPjwvc3ZnPmA7XHJcbiAgICByZXR1cm4gY2lyY2xlO1xyXG59XHJcbi8vIE5vdGlmbGl4OiBMb2FkaW5nIFNWRyBjaXJjbGUgb2ZmXHJcblxyXG4vLyBOb3RpZmxpeDogTG9hZGluZyBTVkcgYXJyb3dzIG9uXHJcbmNvbnN0IG5vdGlmbGl4TG9hZGluZ1N2Z0Fycm93cyA9ICh3aWR0aCwgY29sb3IpID0+IHtcclxuICAgIGlmICghd2lkdGgpIHsgd2lkdGggPSAnNjBweCc7IH1cclxuICAgIGlmICghY29sb3IpIHsgY29sb3IgPSAnIzAwYjQ2Mic7IH1cclxuICAgIGNvbnN0IGFycm93cyA9IGA8c3ZnIGlkPVwiTlhMb2FkaW5nQXJyb3dzXCIgZmlsbD1cIiR7Y29sb3J9XCIgd2lkdGg9XCIke3dpZHRofVwiIGhlaWdodD1cIiR7d2lkdGh9XCIgeG1sbnM9XCJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2Z1wiIHhtbG5zOnhsaW5rPVwiaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGlua1wiIHZlcnNpb249XCIxLjFcIiB2aWV3Qm94PVwiMCAwIDEyOCAxMjhcIiB4bWw6c3BhY2U9XCJwcmVzZXJ2ZVwiPjxnPjxwYXRoIGZpbGw9XCJpbmhlcml0XCIgZmlsbC1vcGFjaXR5PVwiMVwiIGQ9XCJNMTA5LjI1IDU1LjVoLTM2bDEyLTEyYTI5LjU0IDI5LjU0IDAgMCAwLTQ5LjUzIDEySDE4Ljc1QTQ2LjA0IDQ2LjA0IDAgMCAxIDk2LjkgMzEuODRsMTIuMzUtMTIuMzR2MzZ6bS05MC41IDE3aDM2bC0xMiAxMmEyOS41NCAyOS41NCAwIDAgMCA0OS41My0xMmgxNi45N0E0Ni4wNCA0Ni4wNCAwIDAgMSAzMS4xIDk2LjE2TDE4Ljc0IDEwOC41di0zNnpcIiAvPjxhbmltYXRlVHJhbnNmb3JtIGF0dHJpYnV0ZU5hbWU9XCJ0cmFuc2Zvcm1cIiB0eXBlPVwicm90YXRlXCIgZnJvbT1cIjAgNjQgNjRcIiB0bz1cIjM2MCA2NCA2NFwiIGR1cj1cIjEuNXNcIiByZXBlYXRDb3VudD1cImluZGVmaW5pdGVcIj48L2FuaW1hdGVUcmFuc2Zvcm0+PC9nPjwvc3ZnPmA7XHJcbiAgICByZXR1cm4gYXJyb3dzO1xyXG59XHJcbi8vIE5vdGlmbGl4OiBMb2FkaW5nIFNWRyBhcnJvd3Mgb2ZmXHJcblxyXG4vLyBOb3RpZmxpeDogTG9hZGluZyBTVkcgZG90cyBvblxyXG5jb25zdCBub3RpZmxpeExvYWRpbmdTdmdEb3RzID0gKHdpZHRoLCBjb2xvcikgPT4ge1xyXG4gICAgaWYgKCF3aWR0aCkgeyB3aWR0aCA9ICc2MHB4JzsgfVxyXG4gICAgaWYgKCFjb2xvcikgeyBjb2xvciA9ICcjMDBiNDYyJzsgfVxyXG4gICAgY29uc3QgZG90cyA9IGA8c3ZnIGlkPVwiTlhMb2FkaW5nRG90c1wiIGZpbGw9XCIke2NvbG9yfVwiIHdpZHRoPVwiJHt3aWR0aH1cIiBoZWlnaHQ9XCIke3dpZHRofVwiIHhtbG5zPVwiaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmdcIiB4bWxuczp4bGluaz1cImh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmtcIiB2aWV3Qm94PVwiMCAwIDEwMCAxMDBcIiBwcmVzZXJ2ZUFzcGVjdFJhdGlvPVwieE1pZFlNaWRcIj48ZyB0cmFuc2Zvcm09XCJ0cmFuc2xhdGUoMjUgNTApXCI+PGNpcmNsZSBjeD1cIjBcIiBjeT1cIjBcIiByPVwiOVwiIGZpbGw9XCJpbmhlcml0XCIgdHJhbnNmb3JtPVwic2NhbGUoMC4yMzkgMC4yMzkpXCI+PGFuaW1hdGVUcmFuc2Zvcm0gYXR0cmlidXRlTmFtZT1cInRyYW5zZm9ybVwiIHR5cGU9XCJzY2FsZVwiIGJlZ2luPVwiLTAuMjY2c1wiIGNhbGNNb2RlPVwic3BsaW5lXCIga2V5U3BsaW5lcz1cIjAuMyAwIDAuNyAxOzAuMyAwIDAuNyAxXCIgdmFsdWVzPVwiMDsxOzBcIiBrZXlUaW1lcz1cIjA7MC41OzFcIiBkdXI9XCIwLjhzXCIgcmVwZWF0Q291bnQ9XCJpbmRlZmluaXRlXCIvPjwvY2lyY2xlPjwvZz48ZyB0cmFuc2Zvcm09XCJ0cmFuc2xhdGUoNTAgNTApXCI+IDxjaXJjbGUgY3g9XCIwXCIgY3k9XCIwXCIgcj1cIjlcIiBmaWxsPVwiaW5oZXJpdFwiIHRyYW5zZm9ybT1cInNjYWxlKDAuMDAxNTIgMC4wMDE1MilcIj48YW5pbWF0ZVRyYW5zZm9ybSBhdHRyaWJ1dGVOYW1lPVwidHJhbnNmb3JtXCIgdHlwZT1cInNjYWxlXCIgYmVnaW49XCItMC4xMzNzXCIgY2FsY01vZGU9XCJzcGxpbmVcIiBrZXlTcGxpbmVzPVwiMC4zIDAgMC43IDE7MC4zIDAgMC43IDFcIiB2YWx1ZXM9XCIwOzE7MFwiIGtleVRpbWVzPVwiMDswLjU7MVwiIGR1cj1cIjAuOHNcIiByZXBlYXRDb3VudD1cImluZGVmaW5pdGVcIi8+PC9jaXJjbGU+PC9nPjxnIHRyYW5zZm9ybT1cInRyYW5zbGF0ZSg3NSA1MClcIj48Y2lyY2xlIGN4PVwiMFwiIGN5PVwiMFwiIHI9XCI5XCIgZmlsbD1cImluaGVyaXRcIiB0cmFuc2Zvcm09XCJzY2FsZSgwLjI5OSAwLjI5OSlcIj48YW5pbWF0ZVRyYW5zZm9ybSBhdHRyaWJ1dGVOYW1lPVwidHJhbnNmb3JtXCIgdHlwZT1cInNjYWxlXCIgYmVnaW49XCIwc1wiIGNhbGNNb2RlPVwic3BsaW5lXCIga2V5U3BsaW5lcz1cIjAuMyAwIDAuNyAxOzAuMyAwIDAuNyAxXCIgdmFsdWVzPVwiMDsxOzBcIiBrZXlUaW1lcz1cIjA7MC41OzFcIiBkdXI9XCIwLjhzXCIgcmVwZWF0Q291bnQ9XCJpbmRlZmluaXRlXCIvPjwvY2lyY2xlPjwvZz48L3N2Zz5gO1xyXG4gICAgcmV0dXJuIGRvdHM7XHJcbn1cclxuLy8gTm90aWZsaXg6IExvYWRpbmcgU1ZHIGRvdHMgb2ZmXHJcblxyXG4vLyBOb3RpZmxpeDogTG9hZGluZyBTVkcgcHVsc2Ugb25cclxuY29uc3Qgbm90aWZsaXhMb2FkaW5nU3ZnUHVsc2UgPSAod2lkdGgsIGNvbG9yKSA9PiB7XHJcbiAgICBpZiAoIXdpZHRoKSB7IHdpZHRoID0gJzYwcHgnOyB9XHJcbiAgICBpZiAoIWNvbG9yKSB7IGNvbG9yID0gJyMwMGI0NjInOyB9XHJcbiAgICBjb25zdCBwdWxzZSA9IGA8c3ZnIHN0cm9rZT1cIiR7Y29sb3J9XCIgd2lkdGg9XCIke3dpZHRofVwiIGhlaWdodD1cIiR7d2lkdGh9XCIgdmlld0JveD1cIjAgMCA0NCA0NFwiIHhtbG5zPVwiaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmdcIj48ZyBmaWxsPVwibm9uZVwiIGZpbGwtcnVsZT1cImV2ZW5vZGRcIiBzdHJva2Utd2lkdGg9XCIyXCI+PGNpcmNsZSBjeD1cIjIyXCIgY3k9XCIyMlwiIHI9XCIxXCI+PGFuaW1hdGUgYXR0cmlidXRlTmFtZT1cInJcIiBiZWdpbj1cIjBzXCIgZHVyPVwiMS44c1wiIHZhbHVlcz1cIjE7IDIwXCIgY2FsY01vZGU9XCJzcGxpbmVcIiBrZXlUaW1lcz1cIjA7IDFcIiBrZXlTcGxpbmVzPVwiMC4xNjUsIDAuODQsIDAuNDQsIDFcIiByZXBlYXRDb3VudD1cImluZGVmaW5pdGVcIi8+PGFuaW1hdGUgYXR0cmlidXRlTmFtZT1cInN0cm9rZS1vcGFjaXR5XCIgYmVnaW49XCIwc1wiIGR1cj1cIjEuOHNcIiB2YWx1ZXM9XCIxOyAwXCIgY2FsY01vZGU9XCJzcGxpbmVcIiBrZXlUaW1lcz1cIjA7IDFcIiBrZXlTcGxpbmVzPVwiMC4zLCAwLjYxLCAwLjM1NSwgMVwiIHJlcGVhdENvdW50PVwiaW5kZWZpbml0ZVwiLz48L2NpcmNsZT48Y2lyY2xlIGN4PVwiMjJcIiBjeT1cIjIyXCIgcj1cIjFcIj48YW5pbWF0ZSBhdHRyaWJ1dGVOYW1lPVwiclwiIGJlZ2luPVwiLTAuOXNcIiBkdXI9XCIxLjhzXCIgdmFsdWVzPVwiMTsgMjBcIiBjYWxjTW9kZT1cInNwbGluZVwiIGtleVRpbWVzPVwiMDsgMVwiIGtleVNwbGluZXM9XCIwLjE2NSwgMC44NCwgMC40NCwgMVwiIHJlcGVhdENvdW50PVwiaW5kZWZpbml0ZVwiLz48YW5pbWF0ZSBhdHRyaWJ1dGVOYW1lPVwic3Ryb2tlLW9wYWNpdHlcIiBiZWdpbj1cIi0wLjlzXCIgZHVyPVwiMS44c1wiIHZhbHVlcz1cIjE7IDBcIiBjYWxjTW9kZT1cInNwbGluZVwiIGtleVRpbWVzPVwiMDsgMVwiIGtleVNwbGluZXM9XCIwLjMsIDAuNjEsIDAuMzU1LCAxXCIgcmVwZWF0Q291bnQ9XCJpbmRlZmluaXRlXCIvPjwvY2lyY2xlPjwvZz48L3N2Zz5gO1xyXG4gICAgcmV0dXJuIHB1bHNlO1xyXG59XHJcbi8vIE5vdGlmbGl4OiBMb2FkaW5nIFNWRyBwdWxzZSBvZmZcclxuXHJcbi8vIE5vdGlmbGl4OiBMb2FkaW5nIFNWRyBub3RpZmxpeCBvblxyXG5jb25zdCBub3RpZmxpeExvYWRpbmdTdmdOb3RpZmxpeCA9ICh3aWR0aCwgd2hpdGUsIGdyZWVuKSA9PiB7XHJcbiAgICBpZiAoIXdpZHRoKSB7IHdpZHRoID0gJzYwcHgnOyB9XHJcbiAgICBpZiAoIXdoaXRlKSB7IHdoaXRlID0gJyNmOGY4ZjgnOyB9XHJcbiAgICBpZiAoIWdyZWVuKSB7IGdyZWVuID0gJyMwMGI0NjInOyB9XHJcbiAgICBjb25zdCBub3RpZmxpeEljb24gPSBgPHN2ZyBpZD1cIk5YTG9hZGluZ05vdGlmbGl4TGliXCIgeG1sbnM9XCJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2Z1wiIHhtbDpzcGFjZT1cInByZXNlcnZlXCIgd2lkdGg9XCIke3dpZHRofVwiIGhlaWdodD1cIiR7d2lkdGh9XCIgdmVyc2lvbj1cIjEuMVwiIHN0eWxlPVwic2hhcGUtcmVuZGVyaW5nOmdlb21ldHJpY1ByZWNpc2lvbjsgdGV4dC1yZW5kZXJpbmc6Z2VvbWV0cmljUHJlY2lzaW9uOyBpbWFnZS1yZW5kZXJpbmc6b3B0aW1pemVRdWFsaXR5OyBmaWxsLXJ1bGU6ZXZlbm9kZDsgY2xpcC1ydWxlOmV2ZW5vZGRcIiB2aWV3Qm94PVwiMCAwIDIwMCAyMDBcIiB4bWxuczp4bGluaz1cImh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmtcIj48ZGVmcz48c3R5bGUgdHlwZT1cInRleHQvY3NzXCI+LmxpbmV7c3Ryb2tlOiR7d2hpdGV9O3N0cm9rZS13aWR0aDoxMjtzdHJva2UtbGluZWNhcDpyb3VuZDtzdHJva2UtbGluZWpvaW46cm91bmQ7c3Ryb2tlLW1pdGVybGltaXQ6MjI7fS5saW5le2ZpbGw6bm9uZTt9LmRvdHtmaWxsOiR7Z3JlZW59O3N0cm9rZToke2dyZWVufTtzdHJva2Utd2lkdGg6MTI7c3Ryb2tlLWxpbmVjYXA6cm91bmQ7c3Ryb2tlLWxpbmVqb2luOnJvdW5kO3N0cm9rZS1taXRlcmxpbWl0OjIyO30ubntzdHJva2UtZGFzaGFycmF5OiA1MDA7c3Ryb2tlLWRhc2hvZmZzZXQ6IDA7YW5pbWF0aW9uLW5hbWU6IG5vdGlmbGl4LW47YW5pbWF0aW9uLXRpbWluZy1mdW5jdGlvbjogbGluZWFyO2FuaW1hdGlvbi1kdXJhdGlvbjogMi41czthbmltYXRpb24tZGVsYXk6MHM7YW5pbWF0aW9uLWl0ZXJhdGlvbi1jb3VudDogaW5maW5pdGU7YW5pbWF0aW9uLWRpcmVjdGlvbjogbm9ybWFsO31Aa2V5ZnJhbWVzIG5vdGlmbGl4LW57MCV7c3Ryb2tlLWRhc2hvZmZzZXQ6IDEwMDA7fTEwMCV7c3Ryb2tlLWRhc2hvZmZzZXQ6IDA7fX0ueDIsLngxe3N0cm9rZS1kYXNoYXJyYXk6IDUwMDtzdHJva2UtZGFzaG9mZnNldDogMDthbmltYXRpb24tbmFtZTogbm90aWZsaXgteDthbmltYXRpb24tdGltaW5nLWZ1bmN0aW9uOiBsaW5lYXI7YW5pbWF0aW9uLWR1cmF0aW9uOiAyLjVzO2FuaW1hdGlvbi1kZWxheTouMnM7YW5pbWF0aW9uLWl0ZXJhdGlvbi1jb3VudDogaW5maW5pdGU7YW5pbWF0aW9uLWRpcmVjdGlvbjogbm9ybWFsO31Aa2V5ZnJhbWVzIG5vdGlmbGl4LXh7MCV7c3Ryb2tlLWRhc2hvZmZzZXQ6IDEwMDA7fTEwMCV7c3Ryb2tlLWRhc2hvZmZzZXQ6IDA7fX0uZG90e2FuaW1hdGlvbi1uYW1lOiBub3RpZmxpeC1kb3Q7YW5pbWF0aW9uLXRpbWluZy1mdW5jdGlvbjogZWFzZS1pbi1vdXQ7YW5pbWF0aW9uLWR1cmF0aW9uOiAxLjI1czthbmltYXRpb24taXRlcmF0aW9uLWNvdW50OiBpbmZpbml0ZTthbmltYXRpb24tZGlyZWN0aW9uOiBub3JtYWw7fUBrZXlmcmFtZXMgbm90aWZsaXgtZG90ezAle3N0cm9rZS13aWR0aDogMDt9NTAle3N0cm9rZS13aWR0aDogMTI7fTEwMCV7c3Ryb2tlLXdpZHRoOiAwO319PC9zdHlsZT48L2RlZnM+PGc+PHBhdGggY2xhc3M9XCJkb3RcIiBkPVwiTTQ3Ljk3IDEzNS4wNWMzLjU5LDAgNi41LDIuOTEgNi41LDYuNSAwLDMuNTkgLTIuOTEsNi41IC02LjUsNi41IC0zLjU5LDAgLTYuNSwtMi45MSAtNi41LC02LjUgMCwtMy41OSAyLjkxLC02LjUgNi41LC02LjV6XCIvPjxwYXRoIGNsYXNzPVwibGluZSBuXCIgZD1cIk0xMC4xNCAxNDQuNzZsMCAtMC4yMiAwIC0wLjk2IDAgLTU2LjAzYzAsLTUuNjggLTQuNTQsLTQxLjM2IDM3LjgzLC00MS4zNiA0Mi4zNiwwIDM3LjgyLDM1LjY4IDM3LjgyLDQxLjM2bDAgNTcuMjFcIi8+PHBhdGggY2xhc3M9XCJsaW5lIHgxXCIgZD1cIk0xMTUuMDYgMTQ0LjQ5YzI0Ljk4LC0zMi42OCA0OS45NiwtNjUuMzUgNzQuOTQsLTk4LjAzXCIvPjxwYXRoIGNsYXNzPVwibGluZSB4MlwiIGQ9XCJNMTE0Ljg5IDQ2LjZjMjUuMDksMzIuNTggNTAuMTksNjUuMTcgNzUuMjksOTcuNzVcIi8+PC9nPjwvc3ZnPmA7XHJcbiAgICByZXR1cm4gbm90aWZsaXhJY29uO1xyXG59XHJcbi8vIE5vdGlmbGl4OiBMb2FkaW5nIFNWRyBub3RpZmxpeCBvZmYiLCIndXNlIHN0cmljdCc7XG5cbmltcG9ydCBOb3RpZmxpeCBmcm9tIFwibm90aWZsaXgtcmVhY3RcIjtcblxuTm90aWZsaXguTm90aWZ5LkluaXQoe1xuICB0aW1lb3V0OiA1MDAwXG59KTtcblxuJChmdW5jdGlvbigpIHtcblxuICBsZXQgYXV0aG9yID0gJzxkaXYgc3R5bGU9XCJwb3NpdGlvbjogZml4ZWQ7Ym90dG9tOiAwO3JpZ2h0OiAyMHB4O2JhY2tncm91bmQtY29sb3I6ICNmZmY7Ym94LXNoYWRvdzogMCA0cHggOHB4IHJnYmEoMCwwLDAsLjA1KTtib3JkZXItcmFkaXVzOiAzcHggM3B4IDAgMDtmb250LXNpemU6IDEycHg7cGFkZGluZzogNXB4IDEwcHg7XCI+QnkgPGEgaHJlZj1cImh0dHBzOi8vYXJ0aHVybW9ubmV5LmNvbVwiPkBtb25uZXlhcnRodXI8L2E+ICZuYnNwOyZidWxsOyZuYnNwOyA8YSBocmVmPVwiaHR0cHM6Ly9naXRodWIuY29tL2xhcmF2ZWwtc2hvcHBlci9mcmFtZXdvcmtcIiB0YXJnZXQ9XCJfYmxhbmtcIj5TZWUgZG9jdW1lbnRhdGlvbjwvYT48L2Rpdj4nXG4gICQoJ2JvZHknKS5hcHBlbmQoYXV0aG9yKTtcblxuICAkKGBpbnB1dFt0eXBlPSdwYXNzd29yZCddW2RhdGEtZXllXWApLmVhY2goZnVuY3Rpb24oaSkge1xuICAgIGxldCAkdGhpcyA9ICQodGhpcyksIGlkID0gJ2V5ZS1wYXNzd29yZC0nICsgaSwgZWwgPSAkKCcjJyArIGlkKTtcblxuICAgICR0aGlzLndyYXAoJChcIjxkaXYvPlwiLCB7IHN0eWxlOiAncG9zaXRpb246cmVsYXRpdmUnLCBpZDogaWQgfSkpO1xuICAgICR0aGlzLmNzcyh7IHBhZGRpbmdSaWdodDogNjAgfSk7XG5cbiAgICAkdGhpcy5hZnRlcigkKCc8ZGl2Lz4nLCB7XG4gICAgICBodG1sOiAnU2hvdycsXG4gICAgICBjbGFzczogJ2J0biBidG4tcHJpbWFyeSBidG4tc20nLFxuICAgICAgaWQ6ICdwYXNzZXllLXRvZ2dsZS0nK2ksXG4gICAgfSkuY3NzKHtcbiAgICAgIHBvc2l0aW9uOiAnYWJzb2x1dGUnLFxuICAgICAgcmlnaHQ6IDEwLFxuICAgICAgdG9wOiAoJHRoaXMub3V0ZXJIZWlnaHQoKSAvIDIpIC0gMTIsXG4gICAgICBwYWRkaW5nOiAnMnB4IDdweCcsXG4gICAgICBmb250U2l6ZTogMTIsXG4gICAgICBjdXJzb3I6ICdwb2ludGVyJyxcbiAgICB9KSk7XG5cbiAgICAkdGhpcy5hZnRlcigkKCc8aW5wdXQvPicsIHtcbiAgICAgIHR5cGU6ICdoaWRkZW4nLFxuICAgICAgaWQ6ICdwYXNzZXllLScgKyBpXG4gICAgfSkpO1xuXG4gICAgbGV0IGludmFsaWRfZmVlZGJhY2sgPSAkdGhpcy5wYXJlbnQoKS5wYXJlbnQoKS5maW5kKCcuaW52YWxpZC1mZWVkYmFjaycpO1xuXG4gICAgaWYoaW52YWxpZF9mZWVkYmFjay5sZW5ndGgpIHtcbiAgICAgICR0aGlzLmFmdGVyKGludmFsaWRfZmVlZGJhY2suY2xvbmUoKSk7XG4gICAgfVxuXG4gICAgJHRoaXMub24oJ2tleXVwIHBhc3RlJywgZnVuY3Rpb24oKSB7XG4gICAgICAkKCcjcGFzc2V5ZS0nK2kpLnZhbCgkKHRoaXMpLnZhbCgpKTtcbiAgICB9KVxuICAgICQoJyNwYXNzZXllLXRvZ2dsZS0nK2kpLm9uKCdjbGljaycsIGZ1bmN0aW9uKCkge1xuICAgICAgaWYoJHRoaXMuaGFzQ2xhc3MoJ3Nob3cnKSkge1xuICAgICAgICAkdGhpcy5hdHRyKCd0eXBlJywgJ3Bhc3N3b3JkJyk7XG4gICAgICAgICR0aGlzLnJlbW92ZUNsYXNzKCdzaG93Jyk7XG4gICAgICAgICQodGhpcykucmVtb3ZlQ2xhc3MoJ2J0bi1vdXRsaW5lLXByaW1hcnknKTtcbiAgICAgIH0gZWxzZSB7XG4gICAgICAgICR0aGlzLmF0dHIoJ3R5cGUnLCAndGV4dCcpO1xuICAgICAgICAkdGhpcy52YWwoJCgnI3Bhc3NleWUtJytpKS52YWwoKSk7XG4gICAgICAgICR0aGlzLmFkZENsYXNzKCdzaG93Jyk7XG4gICAgICAgICQodGhpcykuYWRkQ2xhc3MoJ2J0bi1vdXRsaW5lLXByaW1hcnknKTtcbiAgICAgIH1cbiAgICB9KVxuICB9KVxuXG4gICQoJy5teS1sb2dpbi12YWxpZGF0aW9uJykuc3VibWl0KGZ1bmN0aW9uKGV2ZW50KSB7XG4gICAgZXZlbnQucHJldmVudERlZmF1bHQoKTtcbiAgICBldmVudC5zdG9wUHJvcGFnYXRpb24oKTtcblxuICAgIGxldCBmb3JtID0gJCh0aGlzKSwgYnV0dG9uID0gJCgnI2J0bi1sb2dpbicpO1xuXG4gICAgYnV0dG9uLmF0dHIoJ2Rpc2FibGVkJywgdHJ1ZSlcbiAgICAgIC5odG1sKGA8c3BhbiBjbGFzcz0nbG9hZGVyMTInPjwvc3Bhbj5gKTtcblxuICAgIGlmIChmb3JtWzBdLmNoZWNrVmFsaWRpdHkoKSA9PT0gZmFsc2UpIHtcbiAgICAgIGJ1dHRvbi5yZW1vdmVBdHRyKCdkaXNhYmxlZCcpLmh0bWwoJ0xvZ2luJyk7XG4gICAgICBmb3JtLmFkZENsYXNzKCd3YXMtdmFsaWRhdGVkJylcbiAgICB9XG5cbiAgICBheGlvc1xuICAgICAgLnBvc3QoZm9ybS5hdHRyKCdhY3Rpb24nKSwgSlNPTi5zdHJpbmdpZnkoZm9ybS5zZXJpYWxpemUoKSkpXG4gICAgICAudGhlbigocmVzcG9uc2UpID0+IHtcbiAgICAgICAgYnV0dG9uLnJlbW92ZUF0dHIoJ2Rpc2FibGVkJykuaHRtbCgnTG9naW4nKTtcbiAgICAgICAgTm90aWZsaXguTm90aWZ5LlN1Y2Nlc3MoJ1lvdXIgYXJlIHN1Y2Nlc3NmdWxsIExvZ2dlZCBJbicpO1xuICAgICAgICBzZXRJbnRlcnZhbChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgd2luZG93LmxvY2F0aW9uLmhyZWYgPSAnaHR0cHM6Ly9hcnRodXJtb25uZXkuY29tJztcbiAgICAgICAgfSwgMjAwMClcbiAgICAgIH0pXG4gICAgICAuY2F0Y2goKGVycm9yKSA9PiB7XG4gICAgICAgIGNvbnN0IGVycm9ycyA9IGVycm9yLnJlc3BvbnNlLmRhdGEuZXJyb3JzO1xuXG4gICAgICAgIGlmIChlcnJvcnMpIHtcbiAgICAgICAgICBOb3RpZmxpeC5Ob3RpZnkuRmFpbHVyZShlcnJvci5yZXNwb25zZS5kYXRhLmVycm9ycy5lbWFpbFswXSk7XG4gICAgICAgICAgYnV0dG9uLnJlbW92ZUF0dHIoJ2Rpc2FibGVkJykuaHRtbCgnTG9naW4nKVxuICAgICAgICB9XG4gICAgICB9KTtcbiAgfSk7XG59KTtcbiJdLCJzb3VyY2VSb290IjoiIn0=