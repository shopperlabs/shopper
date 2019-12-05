"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
var react_1 = __importDefault(require("react"));
exports.default = (function (_a) {
    var selectCategory = _a.selectCategory, items = _a.items, selected = _a.selected;
    var onSelected = function (item, id) {
        selectCategory(item, id);
    };
    var getIcon = function (name) {
        switch (name) {
            case "small":
                return require("../../../assets/svg/shop.svg");
            case "medium":
                return require("../../../assets/svg/building.svg");
            case "bigger":
                return require("../../../assets/svg/enterprise.svg");
            default:
                return null;
        }
    };
    return (react_1.default.createElement("div", { className: "step" },
        react_1.default.createElement("div", { className: "step-header" },
            react_1.default.createElement("small", null, "Step 1 - Shop Category"),
            react_1.default.createElement("h4", { className: "step-title mt-10" }, "You must specify the size of your shop"),
            react_1.default.createElement("p", { className: "step-description" }, "Don't Worry. You can change these setting at any time. Shopper allows you to start with the smallest level so that you can see the evolution of your shop.")),
        react_1.default.createElement("div", { className: "step-content step-one" },
            react_1.default.createElement("ul", { className: "size-list" }, items.map(function (item) { return (react_1.default.createElement("li", { key: item.id },
                react_1.default.createElement("a", { onClick: function () { return onSelected(item.name.toLowerCase(), item.id); }, className: "" + (selected === item.name.toLowerCase() ? "selected" : "") },
                    react_1.default.createElement("img", { src: getIcon(item.name.toLowerCase()), alt: "shop size" }),
                    react_1.default.createElement("span", null, item.name),
                    react_1.default.createElement("small", null, item.description),
                    (selected === item.name.toLowerCase()) && react_1.default.createElement("span", { className: "selected" },
                        react_1.default.createElement("span", { className: "flaticon2-correct icon" }))))); })))));
});
