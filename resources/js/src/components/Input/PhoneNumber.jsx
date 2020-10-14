import React from "react";
import IntlTelInput from 'react-intl-tel-input';
import "./custom-intel-style.css";

export default ({ value, onChange, className }) => (
  <div className="mt-1 relative rounded-md shadow-sm">
    <IntlTelInput
      nationalMode
      fieldName="phone_number"
      containerClassName="intl-tel-input w-full"
      inputClassName={`form-input relative block w-full sm:text-sm sm:leading-5 transition duration-150 ease-in-out pr-10 ${className ?? ''}`}
      defaultValue={value}
      onPhoneNumberChange={onChange}
    />
  </div>
);
