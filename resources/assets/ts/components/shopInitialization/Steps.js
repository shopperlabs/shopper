"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
var react_1 = __importDefault(require("react"));
exports.default = (function (_a) {
    var stepOneDone = _a.stepOneDone, stepTwoDone = _a.stepTwoDone, stepTreeDone = _a.stepTreeDone, currentStep = _a.currentStep;
    return (react_1.default.createElement("div", { className: "list" },
        react_1.default.createElement("div", { className: "list__item " + (currentStep === 1 ? "active" : "") },
            react_1.default.createElement("span", { className: "list__text" }, "Category"),
            react_1.default.createElement("span", { className: "list__time" },
                stepOneDone && react_1.default.createElement("i", { className: 'flaticon2-checkmark font-success' }),
                !stepOneDone && "1 of 3")),
        react_1.default.createElement("div", { className: "list__item " + (currentStep === 2 ? "active" : "") },
            react_1.default.createElement("span", { className: "list__text" }, "Informations"),
            react_1.default.createElement("span", { className: "list__time" },
                stepTwoDone && react_1.default.createElement("i", { className: 'flaticon2-checkmark font-success' }),
                !stepTwoDone && "2 of 3")),
        react_1.default.createElement("div", { className: "list__item " + ((currentStep === 3 && !stepTreeDone) ? "active" : "") },
            react_1.default.createElement("span", { className: "list__text" }, "Social Links"),
            react_1.default.createElement("span", { className: "list__time" },
                stepTreeDone && react_1.default.createElement("i", { className: 'flaticon2-checkmark font-success' }),
                !stepTreeDone && "3 of 3"))));
});
