import React from "react";
// @ts-ignore
import Notiflix from "notiflix-react";

import ButtonLoader from "../ButtonLoader";

type Size = {
  id: number;
  name: string;
  max_products: number;
  description: string;
  created_at: string;
  updated_at: string;
};
interface StepOneProps {
  selectCategory: (item: string, id: number) => void;
  items: Array<Size>;
  shopId: number;
  selected: string;
  validateStep: () => void;
}

export default ({ selectCategory, items, selected, validateStep, shopId }: StepOneProps) => {
  const onSelected = (item: string, id: number) => {
    selectCategory(item, id);
  }
  const getIcon = (name: string) => {
    switch (name) {
      case "small":
        return require("../../../assets/svg/shop.svg");
      case "medium":
        return require("../../../assets/svg/building.svg");
      case "bigger":
        return require("../../../assets/svg/enterprise.svg");
      default :
        return null;
    }
  }
  const onSubmit = () => {
    if (shopId === 0) {
      Notiflix.Notify.Failure('You did not choose the size of your shop');
    } else {
      validateStep();
    }
  }

  return (
    <div className="step">
      <div className="step-header">
        <small>Step 1 - Shop Category</small>
        <h4 className="step-title mt-10">You must specify the size of your shop</h4>
        <p className="step-description">
          Don't Worry. You can change these setting at any time. Shopper allows
          you to start with the smallest level so that you can see the evolution of your shop.
        </p>
      </div>
      <div className="step-content step-one">
        <ul className="size-list">
          {
            items.map((item: Size) => (
              <li key={item.id}>
                <a onClick={() => onSelected(item.name.toLowerCase(), item.id)} className={`${selected === item.name.toLowerCase() ? "selected" : ""}`}>
                  <img src={getIcon(item.name.toLowerCase())} alt="shop size" />
                  <span>{item.name}</span>
                  <small>{item.description}</small>
                  {(selected === item.name.toLowerCase()) && <span className="selected"><span className="flaticon2-correct icon" /></span>}
                </a>
              </li>
            ))
          }
        </ul>
      </div>
      <div className="step-footer">
        <div className="buttons-step">
          <div className="actions-button">
            <ButtonLoader text="Next" onPress={onSubmit} />
          </div>
        </div>
      </div>
    </div>
  );
}
