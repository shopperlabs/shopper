import React, { useState } from "react";
import useForm from "react-hook-form";

import ButtonLoader from "../ButtonLoader";

type FormData = {
  facebook_url: string;
  instagram_url: string;
  twitter_url: string;
  linkedin_url: string;
};
interface StepTreeProps {
  onSave: () => void;
  registerValues: (facebook_url: string, instagram_url: string, twitter_url: string, linkedin_url: string) => void;
  complete: boolean;
}

export default ({ registerValues, onSave, complete }: StepTreeProps) => {
  const [loading, setLoading] = useState(false);
  const { register, handleSubmit } = useForm<FormData>({ mode: "onChange" });
  const onSubmit = handleSubmit(({ facebook_url, instagram_url, twitter_url, linkedin_url }) => {
    setLoading(true);
    registerValues(facebook_url, instagram_url, twitter_url, linkedin_url);
    onSave();
    if (complete) {
      setLoading(false);
    }
  });

  return (
    <div className="step">
      <form onSubmit={onSubmit}>
        <div className="step-header">
          <small>Step 3 - Shop Social Links</small>
          <h4 className="step-title mt-10">
            Your shop on social networks
            <small>(Optional)</small>
          </h4>
        </div>
        <div className="step-content step-tree">
          <div className="portlet">
            <div className="portlet__body">
              <div className="row">
                <div className="col-md-6">
                  <div className="form-group">
                    <label>Facebook Link</label>
                    <input type="text" name="facebook_url" className="form-control" ref={register} />
                  </div>
                  <div className="form-group">
                    <label>Instagram Link</label>
                    <input type="text" name="instagram_url" className="form-control" ref={register} />
                  </div>
                  <div className="form-group">
                    <label>Twitter Link</label>
                    <input type="text" name="twitter_url" className="form-control" ref={register} />
                  </div>
                  <div className="form-group">
                    <label>LinkedIn Link</label>
                    <input type="text" name="linkedin_url" className="form-control" ref={register} />
                  </div>
                </div>
                <div className="col-md-6 text-center">
                  <div className="mt-50">
                    <img src={require("../../../assets/svg/social_sharing.svg")} alt="shop" width="60%" />
                    <p className="mt-10">
                      Information about your different accounts on social networks.
                      Users will be able to contact you directly on your official pages.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div className="step-footer">
          <div className="buttons-step">
            <div className="actions-button">
              <ButtonLoader loading={loading} submit text="Create My Shop"  />
            </div>
          </div>
        </div>
      </form>
    </div>
  );
}
