"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
var react_1 = __importDefault(require("react"));
exports.default = (function (_a) {
    var stepOneDone = _a.stepOneDone, stepTwoDone = _a.stepTwoDone, stepTreeDone = _a.stepTreeDone, loading = _a.loading, currentStep = _a.currentStep;
    return (react_1.default.createElement("div", { className: "list" },
        react_1.default.createElement("div", { className: "list__item " + (currentStep === "step1" ? "active" : "") },
            react_1.default.createElement("span", { className: "list__text" }, "Category"),
            react_1.default.createElement("span", { className: "list__time" }, "1 of 3")),
        react_1.default.createElement("div", { className: "list__item " + (currentStep === "step2" ? "active" : "") },
            react_1.default.createElement("span", { className: "list__text" }, "Informations"),
            react_1.default.createElement("span", { className: "list__time" }, "2 of 3")),
        react_1.default.createElement("div", { className: "list__item " + (currentStep === "step3" ? "active" : "") },
            react_1.default.createElement("span", { className: "list__text" }, "Social Links"),
            react_1.default.createElement("span", { className: "list__time" }, "3 of 3"))));
});
