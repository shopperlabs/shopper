import React, { useState, useEffect } from "react";
import ReactDom from "react-dom";
import { useTransition, animated } from "react-spring";
import axios from "axios";

import route from "../../utils/route";

import Steps from "./Steps";
import Progress from "./Progress";
import ButtonLoader from "./ButtonLoader";
import StepOne from "./StepOne";
import StepTwo from "./StepTwo";
import StepTree from "./StepTree";
import Complete from "./Complete";

const ShopInitialization = () => {
  const formValues = {
    sizeId: 0,
    selected: "",
    name: "",
    email: ""
  };
  const [step1Done, setStep1Done] = useState(false);
  const [step2Done, setStep2Done] = useState(false);
  const [step3Done, setStep3Done] = useState(false);
  const [loading, setLoading] = useState(false);
  const [currentStep, setCurrentStep] = useState(0);
  const [form, setForm] = useState(formValues);
  const [sizes, setSizes] = useState([]);

  useEffect(() => {
    (async function loadSizes () {
      const {data: { data }} = await axios.get(route('shopper.api.shop.sizes'));
      setSizes(data);
    })();
  }, []);

  const transitions = useTransition(currentStep, item => item.toString(), {
    from: { opacity: 0, transform: 'translate3d(60%,0,0)' },
    enter: { opacity: 1, transform: 'translate3d(0%,0,0)' },
    leave: { opacity: 0, transform: 'translate3d(-5%,0,0)' }
  });

  const steps = [
    () => <StepOne selectCategory={selectCategory} items={sizes} selected={form.selected} />,
    () => <StepTwo />,
    () => <StepTree />,
    () => <Complete />
  ];

  const getStep = (step: number): string => {
    let element = "";

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
  }
  const getPercent = (step: number): number => {
    let element = 0;

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
  }
  const selectCategory = (item: string, id: number) => {
    setForm((prevState: any) => ({...prevState, sizeId: id, selected: item }));
  }
  const validateStep1 = () => {
    setLoading(true);
    setStep1Done(true);
    setLoading(false);
    setCurrentStep(1);
  }
  const validateStep2 = () => {
    setLoading(true);
    setStep2Done(true);
    setLoading(false);
    setCurrentStep(2);
  }
  const validateStep3 = () => {
    setLoading(true);
    setStep3Done(true);
    setLoading(false);
    setCurrentStep(3);
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
            <div className="form-container">

              {transitions.map(({ item, props, key }) => {
                const Component = steps[item];

                return (
                  <animated.div key={key} style={props}>
                    <div style={{ position: "absolute", left: 0, right: 0 }}>
                      <Component />
                    </div>
                  </animated.div>
                )
              })}

              <div className="buttons-step">
                { currentStep !== 3 &&
                  <div className="steps-indicator">
                    <span className="step">{getStep(currentStep)}</span>
                    <Progress percent={getPercent(currentStep)} />
                  </div>}
                <div className="actions-button">
                  {currentStep === 0 && <ButtonLoader loading={loading} text="Next" onPress={validateStep1} />}
                  {currentStep === 1 && <ButtonLoader loading={loading} text="Next" onPress={validateStep2} />}
                  {currentStep === 2 && <ButtonLoader loading={loading} text="Finish" onPress={validateStep3} />}
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
