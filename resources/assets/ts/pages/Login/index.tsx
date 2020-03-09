import React, { useState } from "react";
import ReactDOM from "react-dom";
import useForm from "react-hook-form";
import axios from "axios";
import { useTransition, animated } from "react-spring";

const dashboardURL: any = (document.querySelector('meta[name="dashboard-url"]') as Element).getAttribute('content');
const loginURL: any = document.getElementById('login-form');
const url: string = loginURL ? loginURL.getAttribute('data-url') : '';

const LoginForm = () => {
  const [danger, setDanger] = useState(false);
  const [errorMessage, setErrorMessage] = useState('');
  const [sending, setSending] = useState(false);
  const transitions = useTransition(danger, null, {
    from: { opacity: 0, transform: "translateY(40px)" },
    enter: { opacity: 1, transform: "translateY(0)" },
    leave: { opacity: 0, transform: "translateY(20px)" },
  });
  const { register, handleSubmit, errors } = useForm<FormData>({ mode: "onChange" });
  const onSubmit = handleSubmit((values) => {
    setSending(true);
    axios.post(url, values)
      .then((response) => {
        const { token, user, redirect_url } = response.data;
        localStorage.setItem('token', token);
        localStorage.setItem('user', JSON.stringify(user));

        setInterval(function () {
          window.location.href = redirect_url;
        }, 1000);
      })
      .catch((error) => {
        setSending(false);
        if (error.response.data) {
          const errors = error.response.data.errors;
          setErrorMessage(errors.email[0])
          setDanger(true);
        }
      });
  });

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
              className={`appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5 ${errors.email ? 'border-red-500 ' : ''}`}
              placeholder="Email address"
              ref={register({
                required: "Email Address is required",
                pattern: {
                  value: /^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/,
                  message: "Invalid email address"
                }
              })}
            />
            {errors.email && (
              <div className="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <svg className="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clipRule="evenodd" />
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
              className="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5"
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
            <label htmlFor="remember_me" className="ml-2 block text-sm leading-5 text-gray-900 cursor-pointer">
              Remember me
            </label>
          </div>

          <div className="text-sm leading-5">
            <a href={`${dashboardURL}/password/reset`} className="font-medium text-brand hover:text-brand-hover focus:outline-none focus:underline transition ease-in-out duration-150">
              Forgot your password?
            </a>
          </div>
        </div>

        <div className="mt-6">
          <button
            type="submit"
            className="group relative w-full flex justify-center items-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-brand hover:bg-brand-hover focus:outline-none focus:border-brand-hover active:bg-brand-hover transition duration-75 ease-in-out"
          >
            {!sending && (
              <span className="absolute left-0 inset-y pl-3">
                <svg className="h-5 w-5 text-brand-hover group-hover:text-gray-50 transition ease-in-out duration-150" fill="currentColor" viewBox="0 0 20 20">
                  <path fillRule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clipRule="evenodd" />
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
      {
        transitions.map(({ item, key, props }) =>
          item && (
            <animated.div className="absolute bottom-0 inset-x-0 pb-2 sm:pb-5" key={key} style={props}>
              <div className="max-w-screen-xl mx-auto px-2 sm:px-6 lg:px-8">
                <div className="py-1 px-2 rounded-lg bg-red-600 shadow-lg sm:py-2 sm:px-3">
                  <div className="flex items-center justify-between flex-wrap">
                    <div className="w-0 flex-1 flex items-center">
                      <span className="flex p-1 rounded-lg bg-red-800">
                        <svg className="h-5 w-5 text-white" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                      </span>
                      <p className="ml-3 font-medium text-sm text-white truncate">
                        <span className="md:hidden">Got an error!</span>
                        <span className="hidden md:inline">{errorMessage}</span>
                      </p>
                    </div>
                    <div className="order-2 flex-shrink-0 sm:order-3 sm:ml-2">
                      <button type="button" onClick={() => setDanger(false)} className="-mr-1 flex p-1 rounded-md hover:bg-red-500 focus:outline-none focus:bg-red-500 transition ease-in-out duration-150">
                        <svg className="h-5 w-5 text-white" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </animated.div>
          )
        )
      }
    </>
  );
}

if (document.getElementById('login-form')) {
  ReactDOM.render(
    <LoginForm />,
    document.getElementById('login-form')
  );
}
