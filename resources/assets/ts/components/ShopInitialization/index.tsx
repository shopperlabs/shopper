import React, { useState, useEffect } from "react";
import ReactDom from "react-dom";
import { useTransition, animated } from "react-spring";
// @ts-ignore
import Notiflix from "notiflix-react";

import axios from "@/utils/axios";

import Steps from "@/components/ShopInitialization/Steps";
import StepOne from "@/components/ShopInitialization/StepOne";
import StepTwo from "@/components/ShopInitialization/StepTwo";
import StepTree from "@/components/ShopInitialization/StepTree";
import Complete from "@/components/ShopInitialization/Complete";

export type FormData = {
  size_id: number;
  selected: string;
  name: string;
  email: string;
  phone_number: string;
  facebook_url: string;
  twitter_url: string;
  instagram_url: string;
  linkedin_url: string;
};

const ShopInitialization = () => {
  const formValues: FormData = {
    size_id: 0,
    selected: "",
    name: "",
    email: "",
    phone_number: "",
    facebook_url: "",
    twitter_url: "",
    instagram_url: "",
    linkedin_url: ""
  };
  const [step1Done, setStep1Done] = useState(false);
  const [step2Done, setStep2Done] = useState(false);
  const [step3Done, setStep3Done] = useState(false);
  const [complete, setComplete] = useState(false);
  const [currentStep, setCurrentStep] = useState(0);
  const [form, setForm] = useState(formValues);
  const [sizes, setSizes] = useState([]);
  const transitions = useTransition(currentStep, item => item.toString(), {
    from: { opacity: 0, transform: 'translate3d(60%,0,0)' },
    enter: { opacity: 1, transform: 'translate3d(0%,0,0)' },
    leave: { opacity: 0, transform: 'translate3d(-5%,0,0)' }
  });

  useEffect(() => {
    axios.get('/shop/sizes')
      .then(response => {
        const {data: { data }} = response;
        setSizes(data);
      });
  }, []);

  const selectCategory = (item: string, id: number) => {
    setForm((prevState: FormData) => ({...prevState, size_id: id, selected: item }));
  }
  const inputsRequiredShop = (name: string, email: string, phone_number: string) => {
    setForm((prevState: FormData) => ({...prevState, name, email, phone_number }));
  }
  const inputsSocialShop = (facebook_url: string, instagram_url: string, twitter_url: string, linkedin_url: string) => {
    setForm((prevState: FormData) => ({...prevState, facebook_url, instagram_url, twitter_url, linkedin_url }));
  }
  const validateStep1 = () => {
    setStep1Done(true);
    setCurrentStep(1);
  }
  const validateStep2 = () => {
    setStep2Done(true);
    setCurrentStep(2);
  }
  const validateStep3 = () => {
    setStep3Done(true);
    setCurrentStep(3);
  }
  const onSubmit = () => {
    const submittedValues = form;
    delete submittedValues.selected;
    axios.post('/shop/initialization', submittedValues)
      .then(() => {
        setComplete(true);
        validateStep3();
      })
      .catch(error => {
        if (error.response.data) {
          Notiflix.Notify.Failure(error.response.data.message);
        }
      });
  }

  const steps = [
    () => (
      <StepOne
        selectCategory={selectCategory}
        items={sizes}
        selected={form.selected}
        shopId={form.size_id}
        validateStep={validateStep1}
      />
    ),
    () => (
      <StepTwo
        registerValues={inputsRequiredShop}
        validateStep={validateStep2}
      />
    ),
    () => (
      <StepTree
        registerValues={inputsSocialShop}
        onSave={onSubmit}
        complete={complete}
      />
    ),
    () => <Complete />
  ];

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
