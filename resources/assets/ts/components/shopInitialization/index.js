"use strict";
var __assign = (this && this.__assign) || function () {
    __assign = Object.assign || function(t) {
        for (var s, i = 1, n = arguments.length; i < n; i++) {
            s = arguments[i];
            for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p))
                t[p] = s[p];
        }
        return t;
    };
    return __assign.apply(this, arguments);
};
var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var __generator = (this && this.__generator) || function (thisArg, body) {
    var _ = { label: 0, sent: function() { if (t[0] & 1) throw t[1]; return t[1]; }, trys: [], ops: [] }, f, y, t, g;
    return g = { next: verb(0), "throw": verb(1), "return": verb(2) }, typeof Symbol === "function" && (g[Symbol.iterator] = function() { return this; }), g;
    function verb(n) { return function (v) { return step([n, v]); }; }
    function step(op) {
        if (f) throw new TypeError("Generator is already executing.");
        while (_) try {
            if (f = 1, y && (t = op[0] & 2 ? y["return"] : op[0] ? y["throw"] || ((t = y["return"]) && t.call(y), 0) : y.next) && !(t = t.call(y, op[1])).done) return t;
            if (y = 0, t) op = [op[0] & 2, t.value];
            switch (op[0]) {
                case 0: case 1: t = op; break;
                case 4: _.label++; return { value: op[1], done: false };
                case 5: _.label++; y = op[1]; op = [0]; continue;
                case 7: op = _.ops.pop(); _.trys.pop(); continue;
                default:
                    if (!(t = _.trys, t = t.length > 0 && t[t.length - 1]) && (op[0] === 6 || op[0] === 2)) { _ = 0; continue; }
                    if (op[0] === 3 && (!t || (op[1] > t[0] && op[1] < t[3]))) { _.label = op[1]; break; }
                    if (op[0] === 6 && _.label < t[1]) { _.label = t[1]; t = op; break; }
                    if (t && _.label < t[2]) { _.label = t[2]; _.ops.push(op); break; }
                    if (t[2]) _.ops.pop();
                    _.trys.pop(); continue;
            }
            op = body.call(thisArg, _);
        } catch (e) { op = [6, e]; y = 0; } finally { f = t = 0; }
        if (op[0] & 5) throw op[1]; return { value: op[0] ? op[1] : void 0, done: true };
    }
};
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
var react_spring_1 = require("react-spring");
var axios_1 = __importDefault(require("axios"));
var route_1 = __importDefault(require("../../utils/route"));
var Steps_1 = __importDefault(require("./Steps"));
var Progress_1 = __importDefault(require("./Progress"));
var ButtonLoader_1 = __importDefault(require("./ButtonLoader"));
var StepOne_1 = __importDefault(require("./StepOne"));
var StepTwo_1 = __importDefault(require("./StepTwo"));
var StepTree_1 = __importDefault(require("./StepTree"));
var Complete_1 = __importDefault(require("./Complete"));
var ShopInitialization = function () {
    var formValues = {
        sizeId: 0,
        selected: "",
        name: "",
        email: ""
    };
    var _a = react_1.useState(false), step1Done = _a[0], setStep1Done = _a[1];
    var _b = react_1.useState(false), step2Done = _b[0], setStep2Done = _b[1];
    var _c = react_1.useState(false), step3Done = _c[0], setStep3Done = _c[1];
    var _d = react_1.useState(false), loading = _d[0], setLoading = _d[1];
    var _e = react_1.useState(0), currentStep = _e[0], setCurrentStep = _e[1];
    var _f = react_1.useState(formValues), form = _f[0], setForm = _f[1];
    var _g = react_1.useState([]), sizes = _g[0], setSizes = _g[1];
    react_1.useEffect(function () {
        (function loadSizes() {
            return __awaiter(this, void 0, void 0, function () {
                var data;
                return __generator(this, function (_a) {
                    switch (_a.label) {
                        case 0: return [4 /*yield*/, axios_1.default.get(route_1.default('shopper.api.shop.sizes'))];
                        case 1:
                            data = (_a.sent()).data.data;
                            setSizes(data);
                            return [2 /*return*/];
                    }
                });
            });
        })();
    }, []);
    var transitions = react_spring_1.useTransition(currentStep, function (item) { return item.toString(); }, {
        from: { opacity: 0, transform: 'translate3d(60%,0,0)' },
        enter: { opacity: 1, transform: 'translate3d(0%,0,0)' },
        leave: { opacity: 0, transform: 'translate3d(-5%,0,0)' }
    });
    var steps = [
        function () { return react_1.default.createElement(StepOne_1.default, { selectCategory: selectCategory, items: sizes, selected: form.selected }); },
        function () { return react_1.default.createElement(StepTwo_1.default, null); },
        function () { return react_1.default.createElement(StepTree_1.default, null); },
        function () { return react_1.default.createElement(Complete_1.default, null); }
    ];
    var getStep = function (step) {
        var element = "";
        switch (step) {
            case 0:
                element = "Step 1 of 3";
                break;
            case 1:
                element = "Step 2 of 3";
                break;
            case 2:
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
            case 0:
                element = 0;
                break;
            case 1:
                element = 50;
                break;
            case 2:
                element = 100;
                break;
            default:
                element = 0;
                break;
        }
        return element;
    };
    var selectCategory = function (item, id) {
        setForm(function (prevState) { return (__assign(__assign({}, prevState), { sizeId: id, selected: item })); });
    };
    var validateStep1 = function () {
        setLoading(true);
        setStep1Done(true);
        setLoading(false);
        setCurrentStep(1);
    };
    var validateStep2 = function () {
        setLoading(true);
        setStep2Done(true);
        setLoading(false);
        setCurrentStep(2);
    };
    var validateStep3 = function () {
        setLoading(true);
        setStep3Done(true);
        setLoading(false);
        setCurrentStep(3);
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
                    react_1.default.createElement("div", { className: "form-container" },
                        transitions.map(function (_a) {
                            var item = _a.item, props = _a.props, key = _a.key;
                            var Component = steps[item];
                            return (react_1.default.createElement(react_spring_1.animated.div, { key: key, style: props },
                                react_1.default.createElement("div", { style: { position: "absolute", left: 0, right: 0 } },
                                    react_1.default.createElement(Component, null))));
                        }),
                        react_1.default.createElement("div", { className: "buttons-step" },
                            currentStep !== 3 &&
                                react_1.default.createElement("div", { className: "steps-indicator" },
                                    react_1.default.createElement("span", { className: "step" }, getStep(currentStep)),
                                    react_1.default.createElement(Progress_1.default, { percent: getPercent(currentStep) })),
                            react_1.default.createElement("div", { className: "actions-button" },
                                currentStep === 0 && react_1.default.createElement(ButtonLoader_1.default, { loading: loading, text: "Next", onPress: validateStep1 }),
                                currentStep === 1 && react_1.default.createElement(ButtonLoader_1.default, { loading: loading, text: "Next", onPress: validateStep2 }),
                                currentStep === 2 && react_1.default.createElement(ButtonLoader_1.default, { loading: loading, text: "Finish", onPress: validateStep3 })))))))));
};
if (document.getElementById("shop-initialization-app")) {
    react_dom_1.default.render(react_1.default.createElement(ShopInitialization, null), document.getElementById("shop-initialization-app"));
}
