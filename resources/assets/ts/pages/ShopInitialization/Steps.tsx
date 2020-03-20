import React from "react";

interface StepsProps {
  stepOneDone: boolean;
  stepTwoDone: boolean;
  stepTreeDone: boolean;
  currentStep: number;
}

export default ({
  stepOneDone,
  stepTwoDone,
  stepTreeDone,
  currentStep
}: StepsProps) => {
  return (
    <div className="flex flex-row md:flex-col justify-between space-x-2 md:space-x-0 space-y-0 md:space-y-1">
      <div className={`group flex items-center justify-between px-3 py-2 text-sm leading-5 font-medium rounded-md focus:outline-none transition ease-in-out duration-150 ${currentStep === 0 ? "text-gray-900 bg-gray-100 hover:text-gray-900 hover:bg-gray-100 focus:bg-gray-200" : "text-gray-600 hover:text-gray-900 hover:bg-gray-50 focus:bg-gray-100"}`}>
        <span className="truncate mr-2">Category</span>
        <span className="text-gray-400 text-sm">
          {stepOneDone && (
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" className="w-5 h-5 text-green-400">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          )}
          {!stepOneDone && "1 of 3"}
        </span>
      </div>
      <div className={`group flex items-center justify-between px-3 py-2 text-sm leading-5 font-medium rounded-md focus:outline-none transition ease-in-out duration-150 ${currentStep === 1 ? "text-gray-900 bg-gray-100 hover:text-gray-900 hover:bg-gray-100 focus:bg-gray-200" : "text-gray-600 hover:text-gray-900 hover:bg-gray-50 focus:bg-gray-100"}`}>
        <span className="truncate mr-2">About Shop</span>
        <span className="text-gray-400 text-sm">
          {stepTwoDone && (
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" className="w-5 h-5 text-green-400">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          )}
          {!stepTwoDone && "2 of 3"}
        </span>
      </div>
      <div className={`group flex items-center justify-between px-3 py-2 text-sm leading-5 font-medium rounded-md focus:outline-none transition ease-in-out duration-150 ${(currentStep === 2 && !stepTreeDone) ? "text-gray-900 bg-gray-100 hover:text-gray-900 hover:bg-gray-100 focus:bg-gray-200" : "text-gray-600 hover:text-gray-900 hover:bg-gray-50 focus:bg-gray-100"}`}>
        <span className="truncate mr-2">Social Links</span>
        <span className="text-gray-400 text-sm">
          {stepTreeDone && (
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" className="w-5 h-5 text-green-400">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          )}
          {!stepTreeDone && "3 of 3"}
        </span>
      </div>
    </div>
  );
};
