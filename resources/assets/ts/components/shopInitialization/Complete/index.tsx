import * as React from "react";

const dashboardURL = (document.querySelector('meta[name="dashboard-url"]') as Element).getAttribute('content');

export default () => {
  return (
    <div className="step-complete">
      <img src={require("../../../assets/svg/confetti.svg")} alt="Successfully" />
      <h1 className="step-complete__title">Create Shop successfully complete</h1>
      <h2 className="step-complete__subtitle">Thank to use Shopper</h2>
      <p className="step-complete__description">You can now access your shop by press the following button</p>
      <a href={`${dashboardURL}/dashboard`} className="btn btn-primary btn-elevate">Go To Dashboard</a>
    </div>
  );
}
