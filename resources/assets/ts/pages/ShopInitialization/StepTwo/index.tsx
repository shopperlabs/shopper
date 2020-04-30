import React from "react";
import useForm from "react-hook-form";

type FormData = {
  name: string;
  email: string;
  phone_number: string;
};

interface StepTwoProps {
  validateStep: () => void;
  registerValues: (name: string, email: string, phone_number: string) => void;
}

export default ({ validateStep, registerValues }: StepTwoProps) => {
  const { register, handleSubmit, errors } = useForm<FormData>({ mode: "onChange" });
  const onSubmit = handleSubmit(({ name, email, phone_number }) => {
    if (!errors.name && !errors.email && !errors.phone_number) {
      registerValues(name, email, phone_number);
      validateStep();
    }
  });

  return (
    <div className="relative w-full md:w-4/5 mx-auto pt-4 pb-10 md:pt-12">
      <form onSubmit={onSubmit}>
        <div className="mb-10">
          <small className="font-light text-xs">Step 2 - Shop Informations</small>
          <h3 className="mb-2 text-xl font-medium text-gray-800">Tell us about your Shop</h3>
          <p>
            This information will be useful if you want users of your site to directly
            contact you by email or by your phone number.
          </p>
        </div>
        <div className="step-content step-two md:flex h-full items-center">
          <div className="p-8 bg-white rounded-md shadow-md w-full flex items-center">
            <div className="w-full md:w-1/2">
              <div className="w-full mb-3">
                <label htmlFor="name" className="block text-sm font-medium leading-5 text-gray-700">Shop Name</label>
                <div className="mt-1 relative rounded-md shadow-sm">
                  <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg className="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                      <path
                        fillRule="evenodd"
                        d="M22 5H2a1 1 0 0 0-1 1v4a3 3 0 0 0 2 2.82V22a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-9.18A3 3 0 0 0 23 10V6a1 1 0 0 0-1-1zm-7 2h2v3a1 1 0 1 1-2 0zm-4 0h2v3a1 1 0 1 1-2 0zM7 7h2v3a1 1 0 1 1-2 0zm-3 4a1 1 0 0 1-1-1V7h2v3a1 1 0 0 1-1 1zm10 10h-4v-2a2 2 0 1 1 4 0zm5 0h-3v-2a4 4 0 1 0-8 0v2H5v-8.18a3.17 3.17 0 0 0 1-.6 3 3 0 0 0 4 0 3 3 0 0 0 4 0 3 3 0 0 0 4 0c.293.26.632.464 1 .6zm2-11a1 1 0 1 1-2 0V7h2zM4.3 3H20a1 1 0 1 0 0-2H4.3a1 1 0 1 0 0 2z"
                        clipRule="evenodd"
                      />
                    </svg>
                  </div>
                  <input
                    id="name"
                    type="text"
                    name="name"
                    autoComplete="off"
                    className={`form-input block w-full sm:text-sm sm:leading-5 pl-10 ${errors.name ? 'pr-10 focus:shadow-outline-red border-red-300 text-red-900 placeholder-red-300 focus:border-red-300' : ''}`}
                    ref={register({ required: true })}
                  />
                  {
                    errors.name && (
                      <div className="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg className="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                          <path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clipRule="evenodd" />
                        </svg>
                      </div>
                    )
                  }
                </div>
                {errors.name && <p className="mt-2 text-sm text-red-600">Name required.</p>}
              </div>
              <div className="w-full mb-3">
                <label htmlFor="email" className="block text-sm font-medium leading-5 text-gray-700">Shop Email Address</label>
                <div className="mt-1 relative rounded-md shadow-sm">
                  <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg className="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                      <path
                        fillRule="evenodd"
                        d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884zM18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"
                        clipRule="evenodd"
                      />
                    </svg>
                  </div>
                  <input
                    id="email"
                    type="email"
                    name="email"
                    autoComplete="off"
                    className={`form-input block w-full sm:text-sm sm:leading-5 pl-10 ${errors.email ? 'pr-10 focus:shadow-outline-red border-red-300 text-red-900 placeholder-red-300 focus:border-red-300' : ''}`}
                    ref={register({
                      required: "Email Address required",
                      pattern: {
                        value: /^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/,
                        message: "Invalid Email Address"
                      }
                    })}
                  />
                  {
                    errors.email && (
                      <div className="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg className="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                          <path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clipRule="evenodd" />
                        </svg>
                      </div>
                    )
                  }
                </div>
                {errors.email && <p className="mt-2 text-sm text-red-600">{errors.email.message}</p>}
              </div>
              <div className="w-full mb-3">
                <label htmlFor="phone_number" className="block text-sm font-medium leading-5 text-gray-700">Phone Number</label>
                <div className="mt-1 relative rounded-md shadow-sm">
                  <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg className="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                      <path
                        fillRule="evenodd"
                        d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"
                        clipRule="evenodd"
                      />
                    </svg>
                  </div>
                  <input
                    id="phone_number"
                    type="text"
                    name="phone_number"
                    autoComplete="off"
                    placeholder="+237 694 033 025"
                    className={`form-input block w-full sm:text-sm sm:leading-5 pl-10 ${errors.phone_number ? 'pr-10 focus:shadow-outline-red border-red-300 text-red-900 placeholder-red-300 focus:border-red-300' : ''}`}
                    ref={register({ required: true })}
                  />
                  {
                    errors.phone_number && (
                      <div className="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg className="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                          <path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clipRule="evenodd" />
                        </svg>
                      </div>
                    )
                  }
                </div>
                {errors.phone_number && <p className="mt-2 text-sm text-red-600">Phone number required.</p>}
              </div>
            </div>
            <div className="hidden md:flex md:w-1/2">
              <img className="mx-auto" src={require("@/assets/svg/business_shop.svg")} alt="shop" width="60%" />
            </div>
          </div>
        </div>
        <div className="actions-button flex justify-end mt-6">
          <button className="btn btn-primary w-full md:w-auto">Next</button>
        </div>
      </form>
    </div>
  );
}
