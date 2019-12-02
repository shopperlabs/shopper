import React, { useState } from "react";
import ReactDom from "react-dom";

import Steps from "./Steps";
import Progress from "./Progress";

const ShopInitialization = () => {
  const [step1Done, setStep1Done] = useState(false);
  const [step2Done, setStep2Done] = useState(false);
  const [step3Done, setStep3Done] = useState(false);
  const [loading, setLoading] = useState(false);
  const [currentStep, setCurrentStep] = useState(1);

  const getStep = (step: number): string => {
    let element = "";

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
  }

  return (
    <div className="s-wrapper">
      <div className="container-fluid">
        <div className="row h-100">
          <div className="col-md-3 steps">
            <div className="brand">
              <img
                src={require("../../assets/svg/shopper.svg")}
                alt="Shopper"
              />
            </div>
            <div className="steps-content">
              <h4 className="steps-title">Shop Setup</h4>
              <Steps
                stepOneDone={step1Done}
                stepTwoDone={step2Done}
                stepTreeDone={step3Done}
                loading={loading}
                currentStep={currentStep}
              />
            </div>
          </div>
          <div className="col-md-9">
            <div className="form-container">
              <h2>Form Component</h2>

              <div className="buttons-step">
                <div className="steps-indicator">
                  <span className="step">{getStep(currentStep)}</span>
                  <Progress percent={0} />
                </div>
                <div className="actions-button">
                  <button className="btn btn-primary" type="button">Next</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

if (document.getElementById("shop-initialization-app")) {
  ReactDom.render(
    <ShopInitialization />,
    document.getElementById("shop-initialization-app")
  );
}
