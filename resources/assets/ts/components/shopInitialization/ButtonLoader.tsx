import * as React from "react";

export default ({ loading, text, onPress }: { loading: boolean, text: string, onPress: () => void }) => {
  return (
    <button className={`btn btn-primary btn-elevate ${loading ? 'd-flex' : ''}`} type="button" onClick={onPress}>
      {loading &&
        <span className="spinner-border spinner-border-sm" role="status" aria-hidden="true" style={{ marginRight: "5px" }} />}
      {text}
    </button>
  );
}
