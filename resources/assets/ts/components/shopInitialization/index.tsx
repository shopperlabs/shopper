import React, { useState } from "react";
import ReactDom from "react-dom";

import route from "../../utils/route";

import Steps from "./Steps";
import Progress from "./Progress";
import ButtonLoader from "./ButtonLoader";

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
  const getPercent = (step: number): number => {
    let element = 0;

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
  }
  const validateStep1 = () => {
    setLoading(true);
    setStep1Done(true);
    setLoading(false);
    setCurrentStep(2);
  }
  const validateStep2 = () => {
    setLoading(true);
    setStep2Done(true);
    setLoading(false);
    setCurrentStep(3);
  }
  const validateStep3 = () => {
    setLoading(true);
    setStep3Done(true);
    setLoading(false);
    setCurrentStep(4);
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
                currentStep={currentStep}
              />
            </div>
          </div>
          <div className="col-md-9">
            {currentStep !== 4 &&
              <div className="form-container">
                <h2>Form Component</h2>

                <div className="buttons-step">
                  <div className="steps-indicator">
                    <span className="step">{getStep(currentStep)}</span>
                    <Progress percent={getPercent(currentStep)} />
                  </div>
                  <div className="actions-button">
                    {currentStep === 1 && <ButtonLoader loading={loading} text="Next" onPress={validateStep1} />}
                    {currentStep === 2 && <ButtonLoader loading={loading} text="Next" onPress={validateStep2} />}
                    {currentStep === 3 && <ButtonLoader loading={loading} text="Finish" onPress={validateStep3} />}
                  </div>
                </div>
              </div>}
            {currentStep === 4 &&
              <div className="step-complete">
                <img src={require("../../assets/svg/confetti.svg")} alt="Successfully" />
                <h1 className="step-complete__title">Create Shop successfully complete</h1>
                <h2 className="step-complete__subtitle">Thank to use Shopper</h2>
                <p className="step-complete__description">You can now access your shop by press the following button</p>
                <a href={route('shopper.dashboard').template} className="btn btn-primary btn-elevate">Go To Dashboard</a>
              </div>}
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
