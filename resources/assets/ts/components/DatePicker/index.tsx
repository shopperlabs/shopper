import React, { useEffect } from "react";
import ReactDOM from "react-dom";
import Pikaday from "pikaday";

const datepicker = document.getElementById('date-picker');

const ShopperTimePicker = () => {

  useEffect(() => {
    // if (datepicker) {}
    const picker = new Pikaday({
      field: document.getElementById('datepicker'),
    });
    picker.toString('YYYY-MM-DD');
  }, []);

  return (
    <input
      placeholder="Date"
      name="date"
      id="datepicker"
      className="form-input form-input-shopper block w-full sm:text-sm sm:leading-5"
      autoComplete="off"
    />
  );
};

if (datepicker) {
  ReactDOM.render(<ShopperTimePicker />, datepicker );
}
