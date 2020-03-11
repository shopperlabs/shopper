import * as React from "react";

const dashboardURL = (document.querySelector('meta[name="dashboard-url"]') as Element).getAttribute('content');

export default () => {
  return (
    <div className="w-140 md:h-screen flex items-center justify-center mx-auto pt-12 md:pt-0">
      <div className="mb-12 md:mb-36">
        <img className="h-28 w-auto mb-6" src={require("@/assets/svg/confetti.svg")} alt="Successfully" />
        <h1 className="text-2xl text-gray-900 font-semibold mb-2">Create Shop successfully complete</h1>
        <h2 className="text-lg text-gray-700 font-medium">Thank to use Shopper</h2>
        <p className="mb-5 text-lg">You can now access your shop by press the following button</p>
        <a href={`${dashboardURL}/dashboard`} className="btn btn-primary flex group items-center">
          Go To Dashboard
          <span className="ml-2 transform translate-x-0 group-hover:translate-x-2 transition duration-150 ease-in-out">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" className="w-6 h-6">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
          </span>
        </a>
      </div>
    </div>
  );
}
