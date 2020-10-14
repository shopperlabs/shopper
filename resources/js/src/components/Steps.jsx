import React from "react";

import { scrollToPosition } from "@utils/helpers";

export default ({ step }) => (
  <>
    <header className="hidden lg:block relative z-30 sticky top-0 bg-white shadow-md lg:border-t lg:border-b lg:border-gray-200 transition-all duration-200 ease-in-out">
      <nav className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <ul className="overflow-hidden flex">
          <li className="relative overflow-hidden lg:flex-1">
            <div className="border border-gray-200 overflow-hidden border-b-0 rounded-t-md lg:border-0">
              <button onClick={() => scrollToPosition('#step-one')} type="button" className="group text-left">
                <div className="absolute top-0 left-0 w-1 h-full bg-transparent group-hover:bg-gray-200 group-focus:bg-gray-200 lg:w-full lg:h-1 lg:bottom-0 lg:top-auto" />
                <div className="pr-6 py-5 flex items-start text-sm leading-5 font-medium space-x-4">
                  <div className="flex-shrink-0">
                    <div className="w-10 h-10 flex items-center justify-center border-2 border-gray-300 rounded-full">
                      <p className="text-gray-500">01</p>
                    </div>
                  </div>
                  <div className="mt-0.5 min-w-0">
                    <h3 className="text-xs leading-4 font-semibold uppercase tracking-wide text-gray-500">Store information</h3>
                    <p className="text-sm leading-5 font-medium text-gray-500">Provide useful information for your store.</p>
                  </div>
                </div>
              </button>
            </div>
          </li>
          <li className="relative overflow-hidden lg:flex-1">
            <div className="border border-gray-200 overflow-hidden lg:border-0">
              <button onClick={() => scrollToPosition('#step-two')} type="button" className="group text-left">
                <div className="absolute top-0 left-0 w-1 h-full bg-transparent group-hover:bg-gray-200 group-focus:bg-gray-200 lg:w-full lg:h-1 lg:bottom-0 lg:top-auto" />
                <div className="px-6 py-5 flex items-start text-sm leading-5 font-medium space-x-4 lg:pl-9">
                  <div className="flex-shrink-0">
                    <div className="w-10 h-10 flex items-center justify-center border-2 border-gray-300 rounded-full">
                      <p className="text-gray-500">02</p>
                    </div>
                  </div>
                  <div className="mt-0.5 min-w-0">
                    <h3 className="text-xs leading-4 font-semibold uppercase tracking-wide text-gray-500">Address Information</h3>
                    <p className="text-sm leading-5 font-medium text-gray-500">Provide store address information.</p>
                  </div>
                </div>
                <div className="hidden absolute top-0 left-0 w-3 inset-0 lg:block">
                  <svg className="h-full w-full text-gray-300" viewBox="0 0 12 82" fill="none" preserveAspectRatio="none">
                    <path d="M0.5 0V31L10.5 41L0.5 51V82" stroke="currentcolor" vectorEffect="non-scaling-stroke" />
                  </svg>
                </div>
              </button>
            </div>
          </li>
          <li className="relative overflow-hidden lg:flex-1">
            <div className="border border-gray-200 overflow-hidden border-b-0 rounded-t-md lg:border-0">
              <button onClick={() => scrollToPosition('#step-tree')} type="button" className="group text-left">
                <div className="absolute top-0 left-0 w-1 h-full bg-transparent group-hover:bg-gray-200 group-focus:bg-gray-200 lg:w-full lg:h-1 lg:bottom-0 lg:top-auto" />
                <div className="py-5 flex items-start text-sm leading-5 font-medium space-x-4 pl-9">
                  <div className="flex-shrink-0">
                    <div className="w-10 h-10 flex items-center justify-center border-2 border-gray-300 rounded-full">
                      <p className="text-gray-500">03</p>
                    </div>
                  </div>
                  <div className="mt-0.5 min-w-0">
                    <h3 className="text-xs leading-4 font-semibold uppercase tracking-wide text-gray-500">Social Links (Optional)</h3>
                    <p className="text-sm leading-5 font-medium text-gray-500">Links to your social media accounts.</p>
                  </div>
                </div>
              </button>
              <div className="hidden absolute top-0 left-0 w-3 inset-0 lg:block">
                <svg className="h-full w-full text-gray-300" viewBox="0 0 12 82" fill="none" preserveAspectRatio="none">
                  <path d="M0.5 0V31L10.5 41L0.5 51V82" stroke="currentcolor" vectorEffect="non-scaling-stroke" />
                </svg>
              </div>
            </div>
          </li>
        </ul>
      </nav>
    </header>
  </>
);