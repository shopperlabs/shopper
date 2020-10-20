import React from "react";
import { Transition } from "@headlessui/react";

export default ({ children, show, classNames }) => (
  <Transition show={show}>
    <div className="fixed z-50 bottom-0 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center">
      <Transition.Child
        enter="ease-out duration-300"
        enterFrom="opacity-0"
        enterTo="opacity-100"
        leave="ease-in duration-200"
        leaveFrom="opacity-100"
        leaveTo="opacity-0"
      >
        <div className="fixed inset-0 transition-opacity">
          <div className="absolute z-100 inset-0 bg-gray-800 opacity-75" />
        </div>
      </Transition.Child>

      <Transition.Child
        enter="ease-out duration-300"
        enterFrom="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        enterTo="opacity-100 translate-y-0 sm:scale-100"
        leave="ease-in duration-200"
        leaveFrom="opacity-100 translate-y-0 sm:scale-100"
        leaveTo="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
      >
        <div className={`bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full ${classNames ?? ''}`}>
          {children}
        </div>
      </Transition.Child>
    </div>
  </Transition>
);
