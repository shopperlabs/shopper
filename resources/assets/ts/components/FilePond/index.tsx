import React, { useEffect, useState } from "react";
import ReactDOM from "react-dom";
import { FilePond, registerPlugin, File } from "react-filepond";
import axios from "axios";

// @ts-ignore
import FilePondPluginImageExifOrientation from "filepond-plugin-image-exif-orientation";
// @ts-ignore
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css";
import "filepond/dist/filepond.min.css";

import Transition from "@/components/Transition";
import BlogLoader from "@/components/Loaders/BlogLoader";

registerPlugin(FilePondPluginImageExifOrientation);

const filePond = document.getElementById('filepond');
const fetchURL: any = filePond?.getAttribute('data-fetch-images');

type ImageType = {
  id: number;
  disk_name: string;
  file_size: string;
  content_type: string;
  image_full_path: string;
}

const FilePondUpload = () => {
  const [isOpen, setIsOpen] = useState(false);
  const [loading, setLoading] = useState(true);
  const [url, setUrl] = useState('/api');
  const [files, setFiles] = useState<File[]>([]);
  const [images, setImages] = useState<ImageType[]>([]);
  const token: any = (document.querySelector('meta[name="csrf-token"]') as Element).getAttribute('content');

  useEffect(() => {
    if (filePond) {
      const uploadURL: any = filePond.getAttribute('data-url');
      setUrl(uploadURL);

      axios.get(`${fetchURL}`)
        .then((response) => {
          setLoading(false);
          setImages(response.data.images);
        });
    }
  }, []);

  function getAllImage() {
    axios.get(`${fetchURL}`)
      .then((response) => {
        setImages(response.data.images);
      });
  }

  return (
    <>
      <Transition
        show={isOpen}
        enter="transform ease-out duration-300 transition"
        enterFrom="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        enterTo="translate-y-0 opacity-100 sm:translate-x-0"
        leave="transition ease-in duration-100"
        leaveFrom="opacity-100"
        leaveTo="opacity-0"
      >
        <div className="fixed z-100 bottom-0 right-0 mt-2 p-8 max-w-sm w-full pointer-events-auto">
          <div className="bg-white rounded-lg shadow-md overflow-hidden">
            <div className="p-4">
              <div className="flex items-center">
                <div className="w-0 flex-1 flex justify-between">
                  <p className="w-0 flex-1 text-sm leading-5 font-medium text-gray-900">Image Upload</p>
                </div>
                <div className="ml-4 flex-shrink-0 flex">
                  <button type="button" onClick={() => setIsOpen(false)} className="inline-flex text-gray-400 focus:outline-none focus:text-gray-500 transition ease-in-out duration-150">
                    <svg className="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                      <path fillRule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clipRule="evenodd"/>
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </Transition>
      <div className="w-full">
        <FilePond
          files={files}
          allowMultiple
          maxFiles={5}
          name='image'
          server={{
            process: {
              url,
              method: "POST",
              headers: {
                "X-CSRF-TOKEN": token
              }
            }
          }}
          onupdatefiles={(fileItems) => {
            // Set currently active file objects to this.state
            setFiles(fileItems);
          }}
          onprocessfile={() => {
            setIsOpen(true);
            setTimeout(() => { setFiles([]); }, 1000);
            getAllImage();
          }}
          onremovefile={(file) => {
            console.log(file);
          }}
        />
        {loading && (
          <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 overflow-hidden my-8">
            <div className="bg-white flex items-center justify-center p-6 rounded-md shadow-md">
              <BlogLoader />
            </div>
            <div className="bg-white flex items-center justify-center p-6 rounded-md shadow-md">
              <BlogLoader />
            </div>
            <div className="bg-white flex items-center justify-center p-6 rounded-md shadow-md">
              <BlogLoader />
            </div>
            <div className="bg-white flex items-center justify-center p-6 rounded-md shadow-md">
              <BlogLoader />
            </div>
          </div>
        )}
        <div className="py-4">
          {
            !loading && (
              <>
                {images.length === 0 && (
                  <div className="py-10 w-full">
                    <div className="text-center">
                      <div className="block h-16 h-16">
                        <svg className="h-full w-full text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path
                            strokeLinecap="round"
                            strokeLinejoin="round"
                            strokeWidth="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                          />
                        </svg>
                      </div>
                      <h2 className="text-base mt-4 leading-5 font-medium text-gray-700">No images.</h2>
                    </div>
                  </div>
                )}
                {
                  images.length > 0 && (
                    <div className="bg-white px-4 py-4 sm:px-6 shadow-sm rounded-md overflow-hidden">
                      <div className="flex items-center justify-between">
                        <h4 className="text-gray-800 font-medium text-base sm:text-lg">Media</h4>
                      </div>
                      <div className="grid grid-cols-2 gap-6 lg:grid-cols-4 xl:grid-cols-6 py-4">
                        {
                          images.map((image: ImageType) => (
                            <div key={image.id} className="relative group border border-gray-200 flex flex-col bg-white rounded-md overflow-hidden hover:border-gray-300">
                              <div className="h-32 flex flex-shrink-0">
                                <img
                                  className="h-full w-full object-cover"
                                  src={image.image_full_path}
                                  alt="Product"
                                />
                              </div>
                            </div>
                          ))
                        }
                      </div>
                    </div>
                  )
                }
              </>
            )
          }
        </div>
      </div>
    </>
  );
};

if (filePond) {
  ReactDOM.render(<FilePondUpload />, filePond);
}
