import React from "react";
// @ts-ignore
import Notiflix from "notiflix-react";

import ButtonLoader from "@/pages/ShopInitialization/ButtonLoader";

type Size = {
  id: number;
  name: string;
  max_products: number;
  size: string;
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
  function onSelected(item: string, id: number) {
    selectCategory(item, id);
  }

  function getIcon(name: string) {
    switch (name) {
      case "small":
        return require("@/assets/svg/shop.svg");
      case "medium":
        return require("@/assets/svg/building.svg");
      case "bigger":
        return require("@/assets/svg/enterprise.svg");
      default :
        return null;
    }
  }

  function onSubmit() {
    if (shopId === 0) {
      Notiflix.Notify.Failure('You did not choose the size of your shop');
    } else {
      validateStep();
    }
  }

  return (
    <div className="relative w-full md:w-4/5 mx-auto pt-4 pb-10 md:pt-12">
      <div className="mb-10">
        <small className="font-light text-xs">Step 1 - Shop Category</small>
        <h3 className="mb-2 text-xl font-medium text-gray-800">You must specify the size of your shop</h3>
        <p>
          Don't Worry. You can change these setting at any time. Shopper allows
          you to start with the smallest level so that you can see the evolution of your shop.
        </p>
      </div>
      <div className="step-content step-one md:flex h-full items-center">
        <ul className="space-y-4 md:space-y-0 md:grid md:grid-cols-1 md:col-gap-4 md:row-gap-4 lg:grid-cols-2">
          {
            items.map((item: Size) => (
              <li key={item.id}>
                <a
                  onClick={() => onSelected(item.name.toLowerCase(), item.id)}
                  className="relative flex bg-white shadow-md py-6 px-4 rounded-md cursor-pointer hover:shadow-lg"
                >
                  <div className="flex-shrink-0">
                    <div className="flex items-center justify-center h-12 w-12 rounded-md">
                      <img src={getIcon(item.name.toLowerCase())} alt="shop size" />
                    </div>
                  </div>
                  <span className="ml-4 flex flex-col">
                    <span className="text-lg leading-6 font-medium text-gray-800">
                      {item.name}
                      <span className="text-xs font-light text-gray-400 ml-1">{`(${item.size})`}</span>
                    </span>
                    <span className="mt-1 text-sm">{item.description}</span>
                    {
                      (selected === item.name.toLowerCase()) &&
                      (
                        <span className="absolute" style={{ top: "16px", right: "16px" }}>
                          <svg viewBox="0 0 20 20" fill="currentColor" className="w-5 h-5 text-green-500">
                            <path
                              fillRule="evenodd"
                              clipRule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            />
                          </svg>
                        </span>
                      )
                    }
                  </span>
                </a>
              </li>
            ))
          }
        </ul>
      </div>
      <div className="actions-button flex justify-end mt-6">
        <ButtonLoader text="Next" onPress={onSubmit} />
      </div>
    </div>
  );
}
