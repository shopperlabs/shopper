"use strict";
var __importStar = (this && this.__importStar) || function (mod) {
    if (mod && mod.__esModule) return mod;
    var result = {};
    if (mod != null) for (var k in mod) if (Object.hasOwnProperty.call(mod, k)) result[k] = mod[k];
    result["default"] = mod;
    return result;
};
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
var react_1 = __importStar(require("react"));
var react_dom_1 = __importDefault(require("react-dom"));
var Steps_1 = __importDefault(require("./Steps"));
var ShopInitialization = function () {
    var _a = react_1.useState(false), step1Done = _a[0], setStep1Done = _a[1];
    var _b = react_1.useState(false), step2Done = _b[0], setStep2Done = _b[1];
    var _c = react_1.useState(false), step3Done = _c[0], setStep3Done = _c[1];
    var _d = react_1.useState(false), loading = _d[0], setLoading = _d[1];
    var _e = react_1.useState("step1"), currentStep = _e[0], setCurrentStep = _e[1];
    return (react_1.default.createElement("div", { className: "s-wrapper" },
        react_1.default.createElement("div", { className: "container-fluid" },
            react_1.default.createElement("div", { className: "row h-100" },
                react_1.default.createElement("div", { className: "col-md-3 steps" },
                    react_1.default.createElement("div", { className: "brand" },
                        react_1.default.createElement("img", { src: require("../../assets/svg/shopper.svg"), alt: "Shopper" })),
                    react_1.default.createElement("div", { className: "steps-content" },
                        react_1.default.createElement("h4", { className: "steps-title" }, "Shop Setup"),
                        react_1.default.createElement(Steps_1.default, { stepOneDone: step1Done, stepTwoDone: step2Done, stepTreeDone: step3Done, loading: loading, currentStep: currentStep }))),
                react_1.default.createElement("div", { className: "col-md-9" },
                    react_1.default.createElement("div", { className: "form-container" },
                        react_1.default.createElement("h2", null, "Form Component")))))));
};
if (document.getElementById("shop-initialization-app")) {
    react_dom_1.default.render(react_1.default.createElement(ShopInitialization, null), document.getElementById("shop-initialization-app"));
}
