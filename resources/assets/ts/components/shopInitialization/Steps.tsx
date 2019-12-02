import React, { useState } from "react";

interface StepsProps {
  stepOneDone: boolean;
  stepTwoDone: boolean;
  stepTreeDone: boolean;
  loading: boolean;
  currentStep: number;
}

export default ({
  stepOneDone,
  stepTwoDone,
  stepTreeDone,
  loading,
  currentStep
}: StepsProps) => {
  return (
    <div className="list">
      <div className={`list__item ${currentStep === 1 ? "active" : ""}`}>
        <span className="list__text">Category</span>
        <span className="list__time">1 of 3</span>
      </div>
      <div className={`list__item ${currentStep === 2 ? "active" : ""}`}>
        <span className="list__text">Informations</span>
        <span className="list__time">2 of 3</span>
      </div>
      <div className={`list__item ${currentStep === 3 ? "active" : ""}`}>
        <span className="list__text">Social Links</span>
        <span className="list__time">3 of 3</span>
      </div>
    </div>
  );
};
