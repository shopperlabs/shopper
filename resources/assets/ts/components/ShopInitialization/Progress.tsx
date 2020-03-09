import React from "react";
// @ts-ignore
import { ProgressBar, Step } from "react-step-progress-bar";

export default ({ percent }: { percent: number }) => {
  return (
    <ProgressBar
      percent={percent}
      filledBackground="linear-gradient(to right, #12CC79, #129779)"
    >
      <Step>
        {({ accomplished, index }: { accomplished: boolean, index: number }) => (
          <div
            className={`indexedStep ${accomplished ? "accomplished" : null}`}
          >
            {index + 1}
          </div>
        )}
      </Step>
      <Step>
        {({ accomplished, index }: { accomplished: boolean, index: number }) => (
          <div
            className={`indexedStep ${accomplished ? "accomplished" : null}`}
          >
            {index + 1}
          </div>
        )}
      </Step>
      <Step>
        {({ accomplished, index }: { accomplished: boolean, index: number }) => (
          <div
            className={`indexedStep ${accomplished ? "accomplished" : null}`}
          >
            {index + 1}
          </div>
        )}
      </Step>
    </ProgressBar>
  );
}
