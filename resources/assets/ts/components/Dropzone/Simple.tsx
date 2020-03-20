import React, { useEffect, useState, useCallback } from "react";
import ReactDOM from "react-dom";
import classNames from "classnames";
import { useDropzone } from "react-dropzone";
import { CSSTransition } from "react-transition-group";

import axios from "@/utils/axios";

interface T extends File {
  preview: string;
}

const dropzone = document.getElementById('dropzone-simple');

const DropzoneSimple = () => {
  const [showFile, setShowFile] = useState(false);
  const [preview, setPreview] = useState('');
  const [value, setValue] = useState(0);
  const [showUpload, setShowUpload] = useState(true);
  const [file, setFile] = useState<T | null>(null);
  const onDrop = useCallback(acceptedFiles => {
    const selectFile = acceptedFiles[0];
    setFile(Object.assign(selectFile, {preview: URL.createObjectURL(selectFile)}));

    // Upload file to the server.
    const formData = new FormData();
    formData.append('file', selectFile);
    axios.post(`api/upload`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      },
    })
      .then((response) => {
        setValue(response.data.id);
      })
      .catch((error) => {
        console.error(error.response.data);
      });

    setPreview(URL.createObjectURL(selectFile));
    setShowFile(true);
  }, []);
  const { getRootProps, getInputProps, isDragActive, isDragAccept, isDragReject } = useDropzone({
    onDrop,
    accept: 'image/jpeg, image/png, image/jpg',
    maxSize: 1024 * 1000,
    multiple: false
  });
  const dragClass = classNames('flex justify-center pt-5 pb-6 px-6 border-2 border-gray-300 border-dashed rounded-md cursor-pointer hover:border-brand-400 hover:bg-gray-50', {
    'hover:border-brand-400 hover:bg-gray-50': isDragActive || isDragAccept,
    'border-red-300 hover:border-red-400 hover:bg-red-50': isDragReject,
  });

  useEffect(() => {
    if (file !== null) {
      URL.revokeObjectURL(file.preview)
    }
  }, [file]);

  function removeFile () {
    axios.delete(`api/remove-file/${value}`)
      .then(() => {
        setFile(null);
        setShowFile(false);
      })
      .catch((error) => {
        console.error(error.response.data);
      });
  }

  return (
    <div className="mt-2 sm:mt-0">
      <input type="hidden" value={value} name="media_id" />
      {showUpload && (
        <div {...getRootProps({ className: dragClass })}>
          <input {...getInputProps()} multiple={false} />
          <div className="text-center">
            <svg className="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
              <path
                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                strokeWidth="2"
                strokeLinecap="round"
                strokeLinejoin="round"
              />
            </svg>
            <p className="mt-1 text-sm text-gray-600">
              <button
                type="button"
                className="font-medium text-brand-400 hover:text-brand-100 focus:outline-none focus:underline transition duration-150 ease-in-out"
              >
                Upload a file
              </button>
              {" "}
              or drag and drop
            </p>
            <p className="mt-1 text-xs text-gray-500">PNG, JPG up to 2MB</p>
          </div>
        </div>
      )}
      <CSSTransition
        in={showFile}
        timeout={300}
        classNames="preview"
        unmountOnExit
        onEnter={() => setShowUpload(false)}
        onExited={() => setShowUpload(true)}
      >
        <div className="relative rounded-md bg-cover">
          <img alt="" src={preview} className="w-full block object-cover rounded-md" />
          <button
            type="button"
            className="absolute bg-gray-500 text-gray-50 flex items-center justify-center text-sm z-10 rounded-full h-8 w-8 transition duration-150 easy-out opacity-50 hover:opacity-75"
            onClick={removeFile}
            style={{ right: "10px", top: "10px" }}
          >
            <svg viewBox="0 0 20 20" fill="currentColor" className="w-5 h-5">
              <path
                fillRule="evenodd"
                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                clipRule="evenodd"
              />
            </svg>
          </button>
        </div>
      </CSSTransition>
    </div>
  );
};

if (dropzone) {
  ReactDOM.render(<DropzoneSimple />, dropzone);
}
