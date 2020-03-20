import React, { useState } from "react";
import ReactQuill from "react-quill";
import ReactDOM from "react-dom";

const QuillEditor = () => {
  const [body, setBody] = useState('');
  const formats = [
    'header', 'bold', 'italic', 'underline', 'blockquote',
    'list', 'bullet', 'indent', 'link', 'image',
  ];
  const modules = {
    toolbar: [
      [{ header: '1' }, { header: '2' }, { header: '3' }],
      ['bold', 'italic', 'underline', 'blockquote'],
      [{ list: 'ordered' }, { list: 'bullet' }, { indent: '-1' }, { indent: '+1' }],
      ['link', 'image', 'video'],
      ['clean'],
    ],
    clipboard: {
      // toggle to add extra line breaks when pasting HTML:
      matchVisual: false,
    },
  };

  return (
    <>
      <input type="hidden" value={body} name="body" />
      <ReactQuill
        className="quill-editor"
        formats={formats}
        modules={modules}
        value={body}
        onChange={(text) => setBody(text)}
      />
    </>
  );
};

const editor = document.getElementById('editor');
if (editor) {
  ReactDOM.render(<QuillEditor />, editor);
}
