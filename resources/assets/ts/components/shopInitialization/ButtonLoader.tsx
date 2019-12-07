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
      className={`btn btn-primary btn-elevate ${loading ? 'd-flex' : ''}`}
      type={submit ? "submit" : "button"}
      onClick={onPress || undefined}
    >
      {loading &&
        <span className="spinner-border spinner-border-sm" role="status" aria-hidden="true" style={{ marginRight: "5px" }} />}
      {text}
    </button>
  );
}
