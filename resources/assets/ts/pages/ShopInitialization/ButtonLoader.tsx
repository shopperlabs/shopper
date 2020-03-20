import * as React from "react";

export default ({
  loading,
  text,
  onPress,
  submit
}: {
  loading?: boolean,
  submit?: boolean,
  text: string,
  onPress?: () => void | undefined
}) => {
  return (
    <button
      className="btn btn-primary w-full md:w-auto px-6"
      type={submit ? "submit" : "button"}
      onClick={onPress || undefined}
    >
      {loading &&
        <span className="btn-spinner" role="status" aria-hidden="true" style={{ marginRight: "5px" }} />}
      {text}
    </button>
  );
}
