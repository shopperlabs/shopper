import React from "react";
import { useTransition, animated } from "react-spring";
import classNames from "classnames";

import { toCapitalize } from "@/utils/helpers";

interface BannerAlertProps {
  state: boolean;
  status: string;
  message: string;
  onClose: () => void;
}

export default ({ state, status, message, onClose }: BannerAlertProps) => {
  const transitions = useTransition(state, null, {
    from: { opacity: 0, transform: "translateY(40px)" },
    enter: { opacity: 1, transform: "translateY(0)" },
    leave: { opacity: 0, transform: "translateY(20px)" },
  });
  const divClassName = classNames('py-1 px-2 rounded-lg shadow-lg sm:py-2 sm:px-3', {
    'bg-red-600': status === "error",
    'bg-green-600': status === "success",
    'bg-blue-600': status === "info",
  });
  const svgClassName = classNames('flex p-1 rounded-lg', {
    'bg-red-800': status === "error",
    'bg-green-800': status === "success",
    'bg-blue-800': status === "info",
  });
  const buttonClassName = classNames('-mr-1 flex p-1 rounded-md focus:outline-none transition ease-in-out duration-150', {
    'hover:bg-red-500 focus:bg-red-500': status === "error",
    'hover:bg-green-500 focus:bg-green-500': status === "success",
    'hover:bg-blue-500 focus:bg-blue-500': status === "info",
  });

  function getStatusPath(receiveStatus: string) {
    switch (receiveStatus) {
      case "success":
        return "M5 13l4 4L19 7";
      case "error":
        return "M6 18L18 6M6 6l12 12";
      default:
        return "M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z";
    }
  }

  return (
    <>
      {
        transitions.map(({ item, key, props }) =>
          item && (
            <animated.div className="absolute bottom-0 inset-x-0 pb-2 sm:pb-5" key={key} style={props}>
              <div className="max-w-screen-xl mx-auto px-2 sm:px-6 lg:px-8">
                <div className={divClassName}>
                  <div className="flex items-center justify-between flex-wrap">
                    <div className="w-0 flex-1 flex items-center">
                      <span className={svgClassName}>
                        <svg className="h-5 w-5 text-white" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d={getStatusPath(status)} />
                        </svg>
                      </span>
                      <p className="ml-3 font-medium text-sm text-white truncate">
                        <span className="sm:hidden">{toCapitalize(status)}</span>
                        <span className="hidden sm:inline">{message}</span>
                      </p>
                    </div>
                    <div className="order-2 flex-shrink-0 sm:order-3 sm:ml-2">
                      <button type="button" onClick={onClose} className={buttonClassName}>
                        <svg className="h-5 w-5 text-white" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </animated.div>
          )
        )
      }
    </>
  );
}
