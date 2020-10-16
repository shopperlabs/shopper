import React, { useState, useEffect } from "react";
import ReactDom from "react-dom";
import { Switch } from "@headlessui/react";
import mapboxgl from 'mapbox-gl';

import Steps from "@components/Steps";
import TrixEditor from "@components/TrixEditor";
import PhoneNumber from "@components/Input/PhoneNumber";
import ButtonLoader from "@components/ButtonLoader";

const Configuration = () => {
  const [step, setStep] = useState(0);
  const [body, setBody] = useState('');
  const [phoneNumber, setPhoneNumber] = useState('');
  const [switchValue, setSwitchValue] = useState(false);
  const [loading, setLoading] = useState(false);
  var map = false;

  useEffect(() => {
    if (!map) {
      map = new mapboxgl.Map({
      container: 'mapboxgl',
        style: 'mapbox://styles/mapbox/streets-v11'
      });
    }
  }, [map])

  mapboxgl.accessToken = 'pk.eyJ1Ijoic2hvcHBlcmxhYnMiLCJhIjoiY2tnNzFsYWo4MDJ6aTJ1bDE0NmJ5d3k0dyJ9.5mAK55meVSZ3v5RwKYraqw';

  return (
    <>
      <Steps {...step} />
      <main className="py-5 sm:py-10">
        <div className="max-w-7xl mx-auto">
          <form>
            <div id="step-one" className="px-4 sm:px-6 lg:px-8">
              <span className="text-sm text-blue-600 uppercase font-medium lg:hidden">Step 1 of 3</span>
              <h1 className="text-gray-900 font-bold text-2xl leading-8 mt-2 lg:text-3xl lg:mt-0">Shop configuration</h1>
              <div className="mt-8">
                <span className="text-sm font-medium text-blue-600">Step 1 - Shop information</span>
                <h3 className="text-base mt-1.5 font-semibold text-gray-900 leading-5">Tell us about your Shop</h3>
                <p className="mt-3 text-gray-500 leading-5 text-sm lg:max-w-xl">
                  This information will be useful if you want users of your site to directly contact you by email or by your phone number.
                </p>
              </div>
            </div>

            <div className="mt-6 lg:mt-8 sm:px-6 lg:px-8">
              <div className="bg-white shadow-md sm:rounded-md p-4 lg:p-6">
                <div className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start">
                  <label htmlFor="name" className="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                    Store name <span className="text-red-500">*</span>
                  </label>
                  <div className="mt-1 sm:mt-0 sm:col-span-2">
                    <div className="relative rounded-md shadow-sm sm:max-w-xs lg:max-w-lg">
                      <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg className="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                          <path fillRule="evenodd" d="M22 5H2a1 1 0 0 0-1 1v4a3 3 0 0 0 2 2.82V22a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-9.18A3 3 0 0 0 23 10V6a1 1 0 0 0-1-1zm-7 2h2v3a1 1 0 1 1-2 0zm-4 0h2v3a1 1 0 1 1-2 0zM7 7h2v3a1 1 0 1 1-2 0zm-3 4a1 1 0 0 1-1-1V7h2v3a1 1 0 0 1-1 1zm10 10h-4v-2a2 2 0 1 1 4 0zm5 0h-3v-2a4 4 0 1 0-8 0v2H5v-8.18a3.17 3.17 0 0 0 1-.6 3 3 0 0 0 4 0 3 3 0 0 0 4 0 3 3 0 0 0 4 0c.293.26.632.464 1 .6zm2-11a1 1 0 1 1-2 0V7h2zM4.3 3H20a1 1 0 1 0 0-2H4.3a1 1 0 1 0 0 2z" clipRule="evenodd" />
                        </svg>
                      </div>
                      <input id="name" className="form-input pl-10 block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autoComplete="off" />
                    </div>
                  </div>
                </div>

                <div className="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                  <label htmlFor="email" className="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                    Email address <span className="text-red-500">*</span>
                  </label>
                  <div className="mt-1 sm:mt-0 sm:col-span-2">
                    <div className="relative rounded-md shadow-sm sm:max-w-xs lg:max-w-lg">
                      <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg className="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                          <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                          <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                      </div>
                      <input id="email" type="email" className="form-input block w-full pl-10 transition duration-150 ease-in-out sm:text-sm sm:leading-5" />
                    </div>
                  </div>
                </div>

                <div className="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                  <label htmlFor="country" className="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                    Country <span className="text-red-500">*</span>
                  </label>
                  <div className="mt-1 sm:mt-0 sm:col-span-2">
                    <div className="rounded-md shadow-sm sm:max-w-xs lg:max-w-lg">
                      <select id="country" className="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                        <option>United States</option>
                        <option>Canada</option>
                        <option>Mexico</option>
                      </select>
                    </div>
                  </div>
                </div>

                <div className="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-center sm:border-t sm:border-gray-200 sm:pt-5">
                  <label htmlFor="photo" className="flex flex-col text-sm leading-5 font-medium text-gray-700">
                    Logo
                  </label>
                  <div className="mt-1 sm:mt-0 sm:col-span-2">
                    <div className="flex items-center">
                      <span className="flex items-center justify-center h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" className="photograph w-8 h-8 text-gray-300">
                          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                      </span>
                      <div className="ml-5 inline-flex rounded-md shadow-sm">
                        <label className="inline-flex cursor-pointer py-2 px-3 border border-gray-300 rounded-md text-sm leading-4 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition duration-150 ease-in-out">
                          Change
                          <input type='file' className='sr-only' />
                        </label>
                      </div>
                    </div>
                    <p className="mt-2 text-sm text-gray-500">The logo of your store that will be visible on your site. This assets will appear on your invoices.</p>
                  </div>
                </div>

                <div className="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                  <label htmlFor="description" className="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                    About
                  </label>
                  <div className="mt-1 sm:mt-0 sm:col-span-2">
                    <div className="flex rounded-md shadow-sm sm:max-w-lg overflow-x-auto lg:w-full lg:overflow-visible">
                      <TrixEditor value={body} onChange={(e) => setBody(e)} />
                    </div>
                    <p className="mt-2 text-sm text-gray-500">You can view this information on the About page on your website.</p>
                  </div>
                </div>

                <div className="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                  <label htmlFor="currency" className="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
                    Store currency
                  </label>
                  <div className="mt-1 sm:mt-0 sm:col-span-2">
                    <div className="rounded-md shadow-sm sm:max-w-xs lg:max-w-lg">
                      <select id="currency" className="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                        <option>Central African CFA BEAC (XAF)</option>
                        <option>US Dollar (US)</option>
                        <option>Euro (EUR)</option>
                      </select>
                    </div>
                    <p className="mt-2 text-sm text-gray-500">This is the currency your products are sold in. After your first sale, currency is locked in and can’t be changed.</p>
                  </div>
                </div>
              </div>
            </div>

            <div id="step-two" className="px-4 sm:px-6 lg:px-8">
              <div className="mt-8 lg:mt-10 pt-8 lg:pt-10 border-t border-gray-200">
                <span className="text-sm font-medium text-blue-600">Step 2 - Address information</span>
                <h3 className="text-base mt-1.5 font-bold text-gray-900 leading-5">You must specify address and location of your shop</h3>
                <p className="mt-4 text-gray-500 text-sm lg:max-w-xl">
                  Don't Worry. You can change these setting at any time. Shopper allows you to start with the smallest level so that you can see the evolution of your shop.
                </p>
              </div>
            </div>

            <div className="mt-6 lg:mt-8 sm:px-6 lg:px-8">
              <div className="bg-white shadow-md sm:rounded-md p-4">
                <div className="grid gap-4 lg:grid-cols-3 lg:gap-6">
                  <div className="space-y-4 sm:col-span-2">
                    <div id="mapboxgl" />
                    <p className="text-sm text-gray-500 leading-5">
                      Shopper uses <span className="font-medium">Mapbox</span> to make it easier to locate your store.
                      To learn more about mapbox, consult the <a href="https://docs.mapbox.com/mapbox-gl-js/api" target="_blank" rel="noopener noreferrer" className="text-blue-600 hover:text-blue-500 transition-colors duration-150 ease-in-out mr-1">documentation</a>
                      and take a look at the <a href="#" target="_blank" rel="noopener noreferrer" className="text-blue-600 hover:text-blue-500 transition-colors duration-150 ease-in-out">configuration</a> with Shopper.
                    </p>
                  </div>
                  <div className="py-2 pr-2">
                    <div className="grid gap-4 lg:grid-cols-4 lg:gap-5">
                      <div className="sm:col-span-4">
                        <label htmlFor="street_address" className="block text-sm font-medium leading-5 text-gray-700">
                          Street address
                        </label>
                        <div className="mt-1 rounded-md shadow-sm">
                          <input id="street_address" className="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" />
                        </div>
                      </div>
                      <div className="sm:col-span-1">
                        <label htmlFor="zip_code" className="block text-sm font-medium leading-5 text-gray-700">
                          Zip code
                        </label>
                        <div className="mt-1 rounded-md shadow-sm">
                          <input id="zip_code" className="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" />
                        </div>
                      </div>
                      <div className="sm:col-span-3">
                        <label htmlFor="city" className="block text-sm font-medium leading-5 text-gray-700">
                          City
                        </label>
                        <div className="mt-1 rounded-md shadow-sm">
                          <input id="city" className="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" />
                        </div>
                      </div>
                      <div className="sm:col-span-4">
                        <label htmlFor="phone_number" className="block text-sm font-medium leading-5 text-gray-700">
                          Phone number
                        </label>
                        <PhoneNumber
                          value={phoneNumber}
                          onChange={(status, number) => {
                            if (status) {
                              setPhoneNumber(number);
                            }
                          }}
                        />
                      </div>
                      <div className="sm:col-span-2">
                        <label htmlFor="longitude" className="block text-sm font-medium leading-5 text-gray-700">
                          Longitude
                        </label>
                        <div className="mt-1 relative rounded-md shadow-sm">
                          <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg className="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                              <path d="M10 7.083A2.92 2.92 0 0 0 7.083 10 2.92 2.92 0 0 0 10 12.917 2.92 2.92 0 0 0 12.917 10 2.92 2.92 0 0 0 10 7.083zm0 4.167c-.69 0-1.25-.56-1.25-1.25S9.31 8.75 10 8.75s1.25.56 1.25 1.25-.56 1.25-1.25 1.25z" />
                              <path d="M19.167 9.167H17.45a7.51 7.51 0 0 0-6.618-6.618V.833a.834.834 0 0 0-1.666 0V2.55a7.51 7.51 0 0 0-6.618 6.618H.833a.834.834 0 0 0 0 1.666H2.55a7.51 7.51 0 0 0 6.618 6.618v1.716a.834.834 0 0 0 1.666 0V17.45a7.51 7.51 0 0 0 6.618-6.618h1.716a.834.834 0 0 0 0-1.666zM10 15.833A5.84 5.84 0 0 1 4.167 10 5.84 5.84 0 0 1 10 4.167 5.84 5.84 0 0 1 15.833 10 5.84 5.84 0 0 1 10 15.833z" />
                            </svg>
                          </div>
                          <input id="longitude" type="text" className="form-input pl-10 block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" />
                        </div>
                      </div>
                      <div className="sm:col-span-2">
                        <label htmlFor="latitude" className="block text-sm font-medium leading-5 text-gray-700">
                          Latitude
                        </label>
                        <div className="mt-1 relative rounded-md shadow-sm">
                          <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg className="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                              <path d="M10 7.083A2.92 2.92 0 0 0 7.083 10 2.92 2.92 0 0 0 10 12.917 2.92 2.92 0 0 0 12.917 10 2.92 2.92 0 0 0 10 7.083zm0 4.167c-.69 0-1.25-.56-1.25-1.25S9.31 8.75 10 8.75s1.25.56 1.25 1.25-.56 1.25-1.25 1.25z" />
                              <path d="M19.167 9.167H17.45a7.51 7.51 0 0 0-6.618-6.618V.833a.834.834 0 0 0-1.666 0V2.55a7.51 7.51 0 0 0-6.618 6.618H.833a.834.834 0 0 0 0 1.666H2.55a7.51 7.51 0 0 0 6.618 6.618v1.716a.834.834 0 0 0 1.666 0V17.45a7.51 7.51 0 0 0 6.618-6.618h1.716a.834.834 0 0 0 0-1.666zM10 15.833A5.84 5.84 0 0 1 4.167 10 5.84 5.84 0 0 1 10 4.167 5.84 5.84 0 0 1 15.833 10 5.84 5.84 0 0 1 10 15.833z" />
                            </svg>
                          </div>
                          <input id="latitude" type="text" className="form-input pl-10 block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" />
                        </div>
                      </div>
                    </div>
                    <div className="mt-6">
                      <Switch.Group as="div" className="flex items-center space-x-4">
                        <Switch
                          as="button"
                          checked={switchValue}
                          onChange={setSwitchValue}
                          className={`${
                            switchValue ? "bg-blue-600" : "bg-gray-200"
                          } relative inline-flex flex-shrink-0 h-6 transition-colors duration-200 ease-in-out border-2 border-transparent rounded-full cursor-pointer w-11 focus:outline-none focus:shadow-outline`}
                        >
                          {({ checked }) => (
                            <span
                              className={`${
                                checked ? "translate-x-5" : "translate-x-0"
                              } inline-block w-5 h-5 transition duration-200 ease-in-out transform bg-white rounded-full`}
                            />
                          )}
                        </Switch>
                        <div className="flex flex-col">
                          <Switch.Label className="text-sm text-gray-700 leading-5">Used as default inventory</Switch.Label>
                          <span className="text-sm text-gray-400 leading-5">Create an inventory with this information.</span>
                        </div>
                      </Switch.Group>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div className="px-4 sm:px-6 lg:px-8">
              <div className="mt-8 lg:mt-10 pt-8 lg:pt-10 border-t border-gray-200">
                <span className="text-sm font-medium text-blue-600">Step 3 - Shop Social Links</span>
                <h3 className="text-base mt-1.5 font-bold text-gray-900 leading-5">
                  Your shop on social networks <span className="text-gray-500 font-normal">(Optional)</span>
                </h3>
                <p className="mt-4 text-gray-500 text-sm lg:max-w-xl">
                  Information about your different accounts on social networks. Users will be able to contact you directly on your official pages.
                </p>
              </div>

              <div id="step-tree" className="bg-white shadow-md rounded-md p-4 lg:p-6 mt-6">
                <div className="grid grid-cols-6 gap-6">
                  <div className="col-span-6 lg:col-span-2">
                    <label htmlFor="facebook" className="block text-sm font-medium leading-5 text-gray-700">Facebook</label>
                    <div className="mt-1 relative">
                      <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                          <path fillRule="evenodd" d="M20 10c0-5.523-4.477-10-10-10S0 4.477 0 10c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V10h2.54V7.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V10h2.773l-.443 2.89h-2.33v6.988C16.343 19.128 20 14.991 20 10z" clipRule="evenodd" />
                        </svg>
                      </div>
                      <input id="facebook" className="form-input pl-10 block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" placeholder="https://facebook.com/mckenziearts" autoComplete="off" />
                    </div>
                  </div>

                  <div className="col-span-6 lg:col-span-2">
                    <label htmlFor="instagram" className="block text-sm font-medium leading-5 text-gray-700">Instagram</label>
                    <div className="mt-1 relative">
                      <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                          <path d="M17.34 5.46a1.2 1.2 0 1 0 1.2 1.2 1.2 1.2 0 0 0-1.2-1.2zm4.6 2.42a7.59 7.59 0 0 0-.46-2.43 4.94 4.94 0 0 0-1.16-1.77 4.7 4.7 0 0 0-1.77-1.15 7.3 7.3 0 0 0-2.43-.47C15.06 2 14.72 2 12 2s-3.06 0-4.12.06a7.3 7.3 0 0 0-2.43.47 4.78 4.78 0 0 0-1.77 1.15 4.7 4.7 0 0 0-1.15 1.77 7.3 7.3 0 0 0-.47 2.43C2 8.94 2 9.28 2 12s0 3.06.06 4.12a7.3 7.3 0 0 0 .47 2.43 4.7 4.7 0 0 0 1.15 1.77 4.78 4.78 0 0 0 1.77 1.15 7.3 7.3 0 0 0 2.43.47C8.94 22 9.28 22 12 22s3.06 0 4.12-.06a7.3 7.3 0 0 0 2.43-.47 4.7 4.7 0 0 0 1.77-1.15 4.85 4.85 0 0 0 1.16-1.77 7.59 7.59 0 0 0 .46-2.43c0-1.06.06-1.4.06-4.12s0-3.06-.06-4.12zM20.14 16a5.61 5.61 0 0 1-.34 1.86 3.06 3.06 0 0 1-.75 1.15 3.19 3.19 0 0 1-1.15.75 5.61 5.61 0 0 1-1.86.34c-1 .05-1.37.06-4 .06s-3 0-4-.06a5.73 5.73 0 0 1-1.94-.3 3.27 3.27 0 0 1-1.1-.75 3 3 0 0 1-.74-1.15 5.54 5.54 0 0 1-.4-1.9c0-1-.06-1.37-.06-4s0-3 .06-4a5.54 5.54 0 0 1 .35-1.9A3 3 0 0 1 5 5a3.14 3.14 0 0 1 1.1-.8A5.73 5.73 0 0 1 8 3.86c1 0 1.37-.06 4-.06s3 0 4 .06a5.61 5.61 0 0 1 1.86.34 3.06 3.06 0 0 1 1.19.8 3.06 3.06 0 0 1 .75 1.1 5.61 5.61 0 0 1 .34 1.9c.05 1 .06 1.37.06 4s-.01 3-.06 4zM12 6.87A5.13 5.13 0 1 0 17.14 12 5.12 5.12 0 0 0 12 6.87zm0 8.46A3.33 3.33 0 1 1 15.33 12 3.33 3.33 0 0 1 12 15.33z" />
                        </svg>
                      </div>
                      <input id="instagram" className="form-input pl-10 block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" placeholder="@mckenziearts" autoComplete="off" />
                    </div>
                  </div>

                  <div className="col-span-6 lg:col-span-2">
                    <label htmlFor="twitter" className="block text-sm font-medium leading-5 text-gray-700">Twitter</label>
                    <div className="mt-1 relative">
                      <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                          <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84" />
                        </svg>
                      </div>
                      <input id="twitter" className="form-input pl-10 block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" placeholder="@mckenziearts" autoComplete="off" />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div className="px-4 sm:px-6 lg:px-8 mt-12">
              <div className="flex justify-end">
                <ButtonLoader loading={loading} title="Create my shop" />
              </div>
            </div>
          </form>
        </div>
      </main>
    </>
  );
}

if (document.getElementById('setting-configuration')) {
  ReactDom.render(
    <Configuration />,
    document.getElementById('setting-configuration')
  );
}
