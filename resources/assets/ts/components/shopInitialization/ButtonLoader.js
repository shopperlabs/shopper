"use strict";
var __importStar = (this && this.__importStar) || function (mod) {
    if (mod && mod.__esModule) return mod;
    var result = {};
    if (mod != null) for (var k in mod) if (Object.hasOwnProperty.call(mod, k)) result[k] = mod[k];
    result["default"] = mod;
    return result;
};
Object.defineProperty(exports, "__esModule", { value: true });
var React = __importStar(require("react"));
exports.default = (function (_a) {
    var loading = _a.loading, text = _a.text, onPress = _a.onPress;
    return (React.createElement("button", { className: "btn btn-primary btn-elevate " + (loading ? 'd-flex' : ''), type: "button", onClick: onPress },
        loading &&
            React.createElement("span", { className: "spinner-border spinner-border-sm", role: "status", "aria-hidden": "true", style: { marginRight: "5px" } }),
        text));
});
