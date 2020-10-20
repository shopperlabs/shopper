import React, { useEffect, useRef } from "react";
import Trix from "trix";
import "trix/dist/trix.css";

export default ({ value, onChange }) => {
  const trixInput = useRef(null);

  useEffect(() => {
    trixInput.current.addEventListener('trix-change', (e) => {
      onChange(e.target.innerHTML);
    })
  }, []);

  return (
    <div className="rounded-md shadow-sm" id="description">
      <input id="trix" value={value} type="hidden" className="sr-only" />
      <trix-editor ref={trixInput} input="trix" class="form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" />
    </div>
  );
}
