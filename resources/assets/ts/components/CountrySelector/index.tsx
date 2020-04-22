import React, { useState, useEffect } from "react";
import ReactDOM from "react-dom";
import { CountryDropdown } from "react-country-region-selector";

const countryElement = document.getElementById('country-selector');

const CountrySelector = () => {
  const [country, setCountry] = useState('');

  useEffect(() => {
    if (countryElement) {
      const value: any = countryElement.getAttribute('data-value');
      setCountry(value);
    }
  }, []);

  return (
    <CountryDropdown
      value={country}
      onChange={(val) => setCountry(val)}
      name="country"
      classes="mt-1 block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
    />
  );
};

if (countryElement) {
  ReactDOM.render(<CountrySelector />, countryElement);
}
