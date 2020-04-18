import React, { useState, useEffect } from "react";
import ReactDOM from 'react-dom';
// @ts-ignore
import InputNumber from "rc-input-number";

const element = document.getElementById('input-number');

const CustomInputNumber = () => {
  const [defaultValue, setDefaultValue] = useState(0);
  const [value, setValue] = useState(0);
  const [isUpdate, setUpdate] = useState(false);

  useEffect(() => {
    if (element) {
      const updateAction = element.getAttribute('data-update');
      const inputValue = element.getAttribute('data-value');

      if (inputValue) {
        setDefaultValue(parseInt(inputValue, 10));
      }

      if (updateAction) {
        setUpdate(true);
      }
    }
  }, []);

  function onChange(current: number) {
    setValue(current);
  }

  return (
    <div className="flex items-center">
      {isUpdate && <p className="text-sm font-medium text-gray-600 mr-4">{defaultValue + value}</p>}
      <InputNumber
        aria-label="Quantity"
        value={value}
        onChange={onChange}
        name="quantity"
      />
    </div>
  );
};

if (element) {
  ReactDOM.render(<CustomInputNumber />, element);
}
