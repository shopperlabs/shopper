"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
var react_1 = __importDefault(require("react"));
// @ts-ignore
var react_step_progress_bar_1 = require("react-step-progress-bar");
exports.default = (function (_a) {
    var percent = _a.percent;
    return (react_1.default.createElement(react_step_progress_bar_1.ProgressBar, { percent: percent, filledBackground: "linear-gradient(to right, #12CC79, #129779)" },
        react_1.default.createElement(react_step_progress_bar_1.Step, null, function (_a) {
            var accomplished = _a.accomplished, index = _a.index;
            return (react_1.default.createElement("div", { className: "indexedStep " + (accomplished ? "accomplished" : null) }, index + 1));
        }),
        react_1.default.createElement(react_step_progress_bar_1.Step, null, function (_a) {
            var accomplished = _a.accomplished, index = _a.index;
            return (react_1.default.createElement("div", { className: "indexedStep " + (accomplished ? "accomplished" : null) }, index + 1));
        }),
        react_1.default.createElement(react_step_progress_bar_1.Step, null, function (_a) {
            var accomplished = _a.accomplished, index = _a.index;
            return (react_1.default.createElement("div", { className: "indexedStep " + (accomplished ? "accomplished" : null) }, index + 1));
        })));
});
