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
var route_1 = __importDefault(require("../../utils/route"));
var Steps_1 = __importDefault(require("./Steps"));
var Progress_1 = __importDefault(require("./Progress"));
var ButtonLoader_1 = __importDefault(require("./ButtonLoader"));
var ShopInitialization = function () {
    var _a = react_1.useState(false), step1Done = _a[0], setStep1Done = _a[1];
    var _b = react_1.useState(false), step2Done = _b[0], setStep2Done = _b[1];
    var _c = react_1.useState(false), step3Done = _c[0], setStep3Done = _c[1];
    var _d = react_1.useState(false), loading = _d[0], setLoading = _d[1];
    var _e = react_1.useState(1), currentStep = _e[0], setCurrentStep = _e[1];
    var getStep = function (step) {
        var element = "";
        switch (step) {
            case 1:
                element = "Step 1 of 3";
                break;
            case 2:
                element = "Step 2 of 3";
                break;
            case 3:
                element = "Step 3 of 3";
                break;
            default:
                element = "";
                break;
        }
        return element;
    };
    var getPercent = function (step) {
        var element = 0;
        switch (step) {
            case 1:
                element = 0;
                break;
            case 2:
                element = 50;
                break;
            case 3:
                element = 100;
                break;
            default:
                element = 0;
                break;
        }
        return element;
    };
    var validateStep1 = function () {
        setLoading(true);
        setStep1Done(true);
        setLoading(false);
        setCurrentStep(2);
    };
    var validateStep2 = function () {
        setLoading(true);
        setStep2Done(true);
        setLoading(false);
        setCurrentStep(3);
    };
    var validateStep3 = function () {
        setLoading(true);
        setStep3Done(true);
        setLoading(false);
        setCurrentStep(4);
    };
    return (react_1.default.createElement("div", { className: "s-wrapper" },
        react_1.default.createElement("div", { className: "container-fluid" },
            react_1.default.createElement("div", { className: "row h-100" },
                react_1.default.createElement("div", { className: "col-md-3 steps" },
                    react_1.default.createElement("div", { className: "brand" },
                        react_1.default.createElement("img", { src: require("../../assets/svg/shopper.svg"), alt: "Shopper" })),
                    react_1.default.createElement("div", { className: "steps-content" },
                        react_1.default.createElement("h4", { className: "steps-title" }, "Shop Setup"),
                        react_1.default.createElement(Steps_1.default, { stepOneDone: step1Done, stepTwoDone: step2Done, stepTreeDone: step3Done, currentStep: currentStep }))),
                react_1.default.createElement("div", { className: "col-md-9" },
                    currentStep !== 4 &&
                        react_1.default.createElement("div", { className: "form-container" },
                            react_1.default.createElement("h2", null, "Form Component"),
                            react_1.default.createElement("div", { className: "buttons-step" },
                                react_1.default.createElement("div", { className: "steps-indicator" },
                                    react_1.default.createElement("span", { className: "step" }, getStep(currentStep)),
                                    react_1.default.createElement(Progress_1.default, { percent: getPercent(currentStep) })),
                                react_1.default.createElement("div", { className: "actions-button" },
                                    currentStep === 1 && react_1.default.createElement(ButtonLoader_1.default, { loading: loading, text: "Next", onPress: validateStep1 }),
                                    currentStep === 2 && react_1.default.createElement(ButtonLoader_1.default, { loading: loading, text: "Next", onPress: validateStep2 }),
                                    currentStep === 3 && react_1.default.createElement(ButtonLoader_1.default, { loading: loading, text: "Finish", onPress: validateStep3 })))),
                    currentStep === 4 &&
                        react_1.default.createElement("div", { className: "step-complete" },
                            react_1.default.createElement("img", { src: require("../../assets/svg/confetti.svg"), alt: "Successfully" }),
                            react_1.default.createElement("h1", { className: "step-complete__title" }, "Create Shop successfully complete"),
                            react_1.default.createElement("h2", { className: "step-complete__subtitle" }, "Thank to use Shopper"),
                            react_1.default.createElement("p", { className: "step-complete__description" }, "You can now access your shop by press the following button"),
                            react_1.default.createElement("a", { href: route_1.default('shopper.dashboard').template, className: "btn btn-primary btn-elevate" }, "Go To Dashboard")))))));
};
if (document.getElementById("shop-initialization-app")) {
    react_dom_1.default.render(react_1.default.createElement(ShopInitialization, null), document.getElementById("shop-initialization-app"));
}
