import React, { useState } from "react";
import ReactDOM from "react-dom";
import useForm from "react-hook-form";
import axios from "axios";

import BannerAlert from "@/components/BannerAlert";

const dashboardURL: any = (document.querySelector(
  'meta[name="dashboard-url"]'
) as Element).getAttribute("content");
const loginURL: any = document.getElementById("login-form");
const url: string = loginURL ? loginURL.getAttribute("data-url") : "";

type FormData = {
  email: string;
  password: string;
  remember: string;
};

const LoginForm = () => {
  const [state, setState] = useState(false);
  const [status, setStatus] = useState("");
  const [message, setMessage] = useState("");
  const [sending, setSending] = useState(false);

  const { register, handleSubmit, errors } = useForm<FormData>({
    mode: "onChange"
  });
  const onSubmit = handleSubmit(values => {
    setSending(true);
    axios
      .post(url, values)
      .then(response => {
        // eslint-disable-next-line no-shadow
        const { token, user, redirect_url, message } = response.data;
        localStorage.setItem("token", token);
        localStorage.setItem("user", JSON.stringify(user));

        setMessage(message);
        setStatus("success");
        setState(true);

        setInterval(() => {
          window.location.href = redirect_url;
        }, 2000);
      })
      .catch(error => {
        setSending(false);
        if (error.response.data) {
          const serverErrors = error.response.data.errors;
          setMessage(serverErrors.email[0]);
          setStatus("error");
          setState(true);
        }
      });
  });

  // @ts-ignore
  return (
    <>
      <form className="mt-8" onSubmit={onSubmit}>
        <div className="rounded-md shadow-sm">
          <div className="relative">
            <input
              aria-label="Email address"
              id="email"
              name="email"
              type="email"
              className={`appearance-none rounded-none relative block w-full px-3 py-2 border bg-input-bg placeholder-primary-text text-input-text rounded-t-md focus:outline-none focus:shadow-outline-blue focus:border-brand-100 focus:z-10 sm:text-sm sm:leading-5 ${
                errors.email ? "border-red-500 " : "border-input-border"
              }`}
              placeholder="Email address"
              ref={register({
                required: "Email Address required",
                pattern: {
                  value: /^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/,
                  message: "Invalid email address"
                }
              })}
            />
            {errors.email && (
              <div className="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <svg
                  className="h-5 w-5 text-red-500"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path
                    fillRule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                    clipRule="evenodd"
                  />
                </svg>
              </div>
            )}
          </div>
          <div className="-mt-px">
            <input
              aria-label="Password"
              id="password"
              name="password"
              type="password"
              className="appearance-none rounded-none relative block w-full px-3 py-2 border border-input-border bg-input-bg placeholder-primary-text text-input-text rounded-b-md focus:outline-none focus:shadow-outline-blue focus:border-brand-100 focus:z-10 sm:text-sm sm:leading-5"
              placeholder="Password"
              ref={register({ required: true })}
            />
          </div>
        </div>

        <div className="mt-6 flex items-center justify-between">
          <div className="flex items-center">
            <input
              id="remember_me"
              type="checkbox"
              name="remember"
              className="form-checkbox h-4 w-4 text-brand transition duration-150 ease-in-out"
              ref={register}
            />
            <label
              htmlFor="remember_me"
              className="ml-2 block text-sm leading-5 text-gray-500 cursor-pointer"
            >
              Remember me
            </label>
          </div>

          <div className="text-sm leading-5">
            <a
              href={`${dashboardURL}/password/reset`}
              className="font-medium text-gray-500 hover:text-brand-400 focus:outline-none focus:underline transition ease-in-out duration-150"
            >
              Forgot your password?
            </a>
          </div>
        </div>

        <div className="mt-6">
          <button
            type="submit"
            className="group relative w-full flex justify-center items-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-on-primary bg-brand-400 hover:bg-brand-100 focus:outline-none focus:border-brand-400 active:bg-brand-400 transition duration-75 ease-in-out"
          >
            {!sending && (
              <span className="absolute left-0 inset-y pl-3">
                <svg
                  className="h-5 w-5 text-brand-100 group-hover:text-gray-50 transition ease-in-out duration-150"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path
                    fillRule="evenodd"
                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                    clipRule="evenodd"
                  />
                </svg>
              </span>
            )}
            {sending && (
              <span className="absolute left-0 inset-y pl-3">
                <span className="btn-spinner" />
              </span>
            )}
            Login
          </button>
        </div>
      </form>
      <BannerAlert
        state={state}
        status={status}
        message={message}
        onClose={() => setState(!state)}
      />
    </>
  );
};

if (document.getElementById("login-form")) {
  ReactDOM.render(<LoginForm />, document.getElementById("login-form"));
}
