import React, { useState, useEffect } from "react";
import ReactDom from "react-dom";
import { useTransition, animated } from "react-spring";
// @ts-ignore
import Notiflix from "notiflix-react";

import axios from "@/utils/axios";

import Steps from "@/pages/ShopInitialization/Steps";
import StepOne from "@/pages/ShopInitialization/StepOne";
import StepTwo from "@/pages/ShopInitialization/StepTwo";
import StepTree from "@/pages/ShopInitialization/StepTree";
import Complete from "@/pages/ShopInitialization/Complete";

declare let window: any;
const { user } = window;

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
    from: { opacity: 0, transform: "translate3d(60%,0,0)" },
    enter: { opacity: 1, transform: "translate3d(0%,0,0)" },
    leave: { opacity: 0, transform: "translate3d(-5%,0,0)" }
  });

  useEffect(() => {
    axios
      .get("/api/shop/sizes")
      .then(response => {
        const {
          data: { data }
        } = response;
        setSizes(data);
      })
      .catch(error => {
        console.log(error);
      });
  }, []);

  const selectCategory = (item: string, id: number) => {
    setForm((prevState: FormData) => ({
      ...prevState,
      size_id: id,
      selected: item
    }));
  };
  const inputsRequiredShop = (
    name: string,
    email: string,
    phone_number: string
  ) => {
    setForm((prevState: FormData) => ({
      ...prevState,
      name,
      email,
      phone_number
    }));
  };
  const inputsSocialShop = (
    facebook_url: string,
    instagram_url: string,
    twitter_url: string,
    linkedin_url: string
  ) => {
    setForm((prevState: FormData) => ({
      ...prevState,
      facebook_url,
      instagram_url,
      twitter_url,
      linkedin_url
    }));
  };
  const validateStep1 = () => {
    setStep1Done(true);
    setCurrentStep(1);
  };
  const validateStep2 = () => {
    setStep2Done(true);
    setCurrentStep(2);
  };
  const validateStep3 = () => {
    setStep3Done(true);
    setCurrentStep(3);
  };
  const onSubmit = () => {
    const submittedValues = form;
    delete submittedValues.selected;
    axios
      .post("/api/shop/initialization", submittedValues)
      .then(() => {
        setComplete(true);
        validateStep3();
      })
      .catch(error => {
        if (error.response.data) {
          Notiflix.Notify.Failure(error.response.data.message);
        }
      });
  };
  function logout(e: React.SyntheticEvent) {
    e.preventDefault();
    localStorage.removeItem("user");
    localStorage.removeItem("token");
    axios.post("/logout");

    setTimeout(() => {
      window.location.href = "/";
    }, 1000);
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
    <div className="h-screen flex flex-col md:flex-row overflow-hidden">
      <div className="w-full md:w-80 bg-white shadow-md flex flex-col justify-between pt-12 pb-6 md:pb-0">
        <div className="px-6">
          <div className="flex items-center justify-between">
            <img
              className="h-12 w-auto"
              src={require("@/assets/svg/shopper.svg")}
              alt="Laravel Shopper"
            />
            <a
              onClick={logout}
              href="#"
              className="leading-6 font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 focus:bg-gray-100 md:hidden flex items-center bg-gray-50 shadow-md rounded-md px-3 py-2 transition ease-in-out duration-150"
            >
              {user.full_name}
              <svg
                className="h-5 w-5 ml-2"
                fill="currentColor"
                viewBox="0 0 24 24"
              >
                <path d="M10.21,6.21l.79-.8V10a1,1,0,0,0,2,0V5.41l.79.8a1,1,0,0,0,1.42,0,1,1,0,0,0,0-1.42l-2.5-2.5a1,1,0,0,0-.33-.21,1,1,0,0,0-.76,0,1,1,0,0,0-.33.21l-2.5,2.5a1,1,0,0,0,1.42,1.42ZM18,7.56A1,1,0,1,0,16.56,9,6.45,6.45,0,1,1,7.44,9,1,1,0,1,0,6,7.56a8.46,8.46,0,1,0,12,0Z" />
              </svg>
            </a>
          </div>
          <div className="mt-6">
            <h4 className="font-medium text-lg text-gray-800 mb-3">
              Shop Setup
            </h4>
            <Steps
              stepOneDone={step1Done}
              stepTwoDone={step2Done}
              stepTreeDone={step3Done}
              currentStep={currentStep}
            />
          </div>
        </div>
        <div className="hidden md:flex flex-shrink-0 flex border-t border-gray-200 p-4 bg-gray-50">
          <div className="flex-shrink-0 group block focus:outline-none">
            <div className="flex items-center">
              <div>
                <img
                  className="inline-block h-10 w-10 rounded-full"
                  src={user.picture}
                  alt={user.full_name}
                />
              </div>
              <div className="ml-3">
                <p className="text-base leading-6 font-medium text-gray-700 group-hover:text-gray-900">
                  {user.full_name}
                </p>
                <a
                  onClick={logout}
                  href="#"
                  className="text-sm leading-5 font-medium text-gray-500 group-hover:text-gray-700 group-focus:underline transition ease-in-out duration-150"
                >
                  Logout
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div className="relative flex-1 overflow-y-auto pt-2 pb-6 md:py-6">
        <div className="max-w-7xl h-full mx-auto px-4 sm:px-6 md:px-8">
          {transitions.map(({ item, props, key }) => {
            const Component = steps[item];
            return (
              <animated.div key={key} style={props}>
                <div style={{ position: "absolute", left: 0, right: 0 }}>
                  <Component />
                </div>
              </animated.div>
            );
          })}
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
