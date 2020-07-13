import React, { useEffect, useState, useCallback } from "react";
import ReactDOM from "react-dom";
import { useDropzone } from "react-dropzone";
import classNames from "classnames";

interface T extends File {
  preview: string;
}

const dropzone = document.getElementById("dropzone");

const Dropzone = () => {
  const [isMultiple, setMultiple] = useState(false);
  const [files, setFiles] = useState<T[]>([]);
  const onDrop = useCallback(acceptedFiles => {
    if (isMultiple) {
      setFiles(
        acceptedFiles.map((file: T) =>
          Object.assign(file, {
            preview: URL.createObjectURL(file)
          })
        )
      );
    } else {
      setFiles(
        acceptedFiles.map((file: T) =>
          Object.assign(file, {
            preview: URL.createObjectURL(file)
          })
        )
      );
    }
  }, []);
  const { getRootProps, getInputProps, isDragActive } = useDropzone({
    onDrop,
    accept: "image/jpeg, image/png, image/jpg",
    maxSize: 1024 * 1000,
    multiple: false
  });
  const dragClass = classNames(
    "flex justify-center pt-5 pb-6 px-6 border-2 border-gray-300 border-dashed rounded-md cursor-pointer hover:border-brand-400 hover:bg-gray-50",
    {
      "hover:border-brand-400 hover:bg-gray-50": isDragActive
    }
  );

  useEffect(() => {
    if (dropzone) {
      const type: string | null = dropzone.getAttribute("data-type");
      setMultiple(type === "multiple");
    }
    files.forEach((file: T) => URL.revokeObjectURL(file.preview));
  }, [files]);

  const thumbs = files.map(file => (
    <div style={{ display: "inline-flex" }} key={file.name}>
      <div style={{ display: "flex", overflow: "hidden", minWidth: 0 }}>
        <img
          alt=""
          src={file.preview}
          style={{ display: "block", width: "auto" }}
        />
      </div>
    </div>
  ));

  return (
    <div className="mt-2 sm:mt-0">
      <div className={dragClass} {...getRootProps()}>
        <input {...getInputProps()} multiple={isMultiple} />
        <div className="text-center">
          <svg
            className="mx-auto h-12 w-12 text-gray-400"
            stroke="currentColor"
            fill="none"
            viewBox="0 0 48 48"
          >
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
            </button>{" "}
            or drag and drop
          </p>
          <p className="mt-1 text-xs text-gray-500">PNG, JPG up to 2MB</p>
        </div>
      </div>
      <h4>Preview</h4>
      {thumbs}
    </div>
  );
};

if (dropzone) {
  ReactDOM.render(<Dropzone />, dropzone);
}
