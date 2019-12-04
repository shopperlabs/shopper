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
    <div className="list">
      <div className={`list__item ${currentStep === 0 ? "active" : ""}`}>
        <span className="list__text">Category</span>
        <span className="list__time">
          {stepOneDone && <i className='flaticon2-checkmark font-success' />}
          {!stepOneDone && "1 of 3"}
        </span>
      </div>
      <div className={`list__item ${currentStep === 1 ? "active" : ""}`}>
        <span className="list__text">Informations</span>
        <span className="list__time">
          {stepTwoDone && <i className='flaticon2-checkmark font-success' />}
          {!stepTwoDone && "2 of 3"}
        </span>
      </div>
      <div className={`list__item ${(currentStep === 2 && !stepTreeDone) ? "active" : ""}`}>
        <span className="list__text">Social Links</span>
        <span className="list__time">
          {stepTreeDone && <i className='flaticon2-checkmark font-success' />}
          {!stepTreeDone && "3 of 3"}
        </span>
      </div>
    </div>
  );
};
