import React, { useState } from "react";
import useForm from "react-hook-form";

import ButtonLoader from "@/components/ShopInitialization/ButtonLoader";

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
    <div className="relative w-full md:w-4/5 mx-auto pt-4 pb-10 md:pt-12">
      <form onSubmit={onSubmit}>
        <div className="mb-10">
          <small className="font-light text-xs">Step 3 - Shop Social Links</small>
          <h3 className="mb-2 text-xl font-medium text-gray-800">
            Your shop on social networks
            <small className="ml-1 text-gray-500">(Optional)</small>
          </h3>
          <p>
            Information about your different accounts on social networks.
            Users will be able to contact you directly on your official pages.
          </p>
        </div>
        <div className="step-content step-tree md:flex h-full items-center">
          <div className="p-8 bg-white rounded-md shadow-md w-full flex items-center">
            <div className="w-full md:w-1/2">
              <div className="w-full mb-3">
                <label htmlFor="facebook_url" className="block text-sm font-medium leading-5 text-gray-700">Facebook Link</label>
                <div className="mt-1 relative rounded-md shadow-sm">
                  <input type="text" id="facebook_url" name="facebook_url" className="form-input block w-full sm:text-sm sm:leading-5" ref={register} />
                </div>
              </div>
              <div className="w-full mb-3">
                <label htmlFor="instagram_url" className="block text-sm font-medium leading-5 text-gray-700">Instagram Link</label>
                <div className="mt-1 relative rounded-md shadow-sm">
                  <input type="text" id="instagram_url" name="instagram_url" className="form-input block w-full sm:text-sm sm:leading-5" ref={register} />
                </div>
              </div>
              <div className="w-full mb-3">
                <label htmlFor="twitter_url" className="block text-sm font-medium leading-5 text-gray-700">Twitter Link</label>
                <div className="mt-1 relative rounded-md shadow-sm">
                  <input type="text" id="twitter_url" name="twitter_url" className="form-input block w-full sm:text-sm sm:leading-5" ref={register} />
                </div>
              </div>
              <div className="w-full mb-3">
                <label htmlFor="linkedin_url" className="block text-sm font-medium leading-5 text-gray-700">LinkedIn Link</label>
                <div className="mt-1 relative rounded-md shadow-sm">
                  <input type="text" id="linkedin_url" name="linkedin_url" className="form-input block w-full sm:text-sm sm:leading-5" ref={register} />
                </div>
              </div>
            </div>
            <div className="hidden md:flex md:w-1/2">
              <img className="mx-auto" src={require("@/assets/svg/social_sharing.svg")} alt="shop" width="60%" />
            </div>
          </div>
        </div>
        <div className="actions-button flex justify-end mt-6">
          <ButtonLoader loading={loading} submit text="Create My Shop" />
        </div>
      </form>
    </div>
  );
}
