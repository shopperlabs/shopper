import React, { useState, useEffect } from "react";
import ReactDOM from "react-dom";
import TimePicker from "rc-time-picker";

const timepicker = document.getElementById('time-picker');

const ShopperTimePicker = () => {
  const [label, setLabel] = useState('Hour');

  useEffect(() => {
    if (timepicker) {
      const placeholder: any = timepicker.getAttribute('data-label');
      setLabel(placeholder);
    }
  }, []);

  return (
    <TimePicker
      showSecond={false}
      placeholder={label}
      name="time"
      className="relative"
      minuteStep={5}
      clearIcon={
        <span className="absolute cursor-pointer" style={{ top: "10px", right: "5px" }}>
          <svg className="text-gray-400 w-5 h-5" fill="none" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" viewBox="0 0 24 24">
            <path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </span>
      }
    />
  );
};

if (timepicker) {
  ReactDOM.render(<ShopperTimePicker />, timepicker );
}
