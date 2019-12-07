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
    <div className="step">
      <form onSubmit={onSubmit}>
        <div className="step-header">
          <small>Step 2 - Shop Informations</small>
          <h4 className="step-title mt-10">Tell us about your Shop</h4>
        </div>
        <div className="step-content step-two mt-0">
          <div className="portlet mt-30">
            <div className="portlet__body p-5">
              <div className="row">
                <div className="col-md-6">
                  <div className="form-group">
                    <label>Shop name</label>
                    <input
                      type="text"
                      name="name"
                      className={`form-control ${errors.name ? 'is-invalid' : '' }`}
                      ref={register({ required: true })}
                    />
                    {errors.name && <div className="invalid-feedback">Name is required.</div>}
                  </div>
                  <div className="form-group">
                    <label>Shop Email address</label>
                    <input
                      type="email"
                      name="email"
                      className={`form-control ${errors.email ? 'is-invalid' : '' }`}
                      ref={register({
                        required: "Email Address is required",
                        pattern: {
                          value: /^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/,
                          message: "Invalid email address"
                        }
                      })}
                    />
                    {errors.email &&
                    <div className="invalid-feedback">
                      {errors.email.message}
                    </div>}
                  </div>
                  <div className="form-group">
                    <label>Phone Number</label>
                    <input
                      type="tel"
                      name="phone_number"
                      className={`form-control ${errors.phone_number ? 'is-invalid' : '' }`}
                      ref={register({ required: true })}
                    />
                    {errors.phone_number && <div className="invalid-feedback">Phone number is required.</div>}
                  </div>
                </div>
                <div className="col-md-6 text-center">
                  <img src={require("../../../assets/svg/business_shop.svg")} alt="shop" width="60%" />
                  <p className="mt-10">
                    This information will be useful if you want users of your site to directly
                    contact you by email or by your phone number.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div className="step-footer">
          <div className="buttons-step">
            <div className="actions-button">
              <button className="btn btn-primary btn-elevate">Next</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  );
}
