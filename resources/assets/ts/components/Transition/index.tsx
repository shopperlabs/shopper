import React from "react";
import { CSSTransition } from "react-transition-group";

interface TransitionProps {
  show: boolean;
  enter: string;
  enterFrom: string;
  enterTo: string;
  leave: string;
  leaveFrom: string;
  leaveTo: string;
  children: React.ReactNode;
}

export default ({
  show,
  enter,
  enterFrom,
  enterTo,
  leave,
  leaveFrom,
  leaveTo,
  children
}: TransitionProps) => {
  const enterClasses = enter.split(' ');
  const enterFromClasses = enterFrom.split(' ');
  const enterToClasses = enterTo.split(' ');
  const leaveClasses = leave.split(' ');
  const leaveFromClasses = leaveFrom.split(' ');
  const leaveToClasses = leaveTo.split(' ');

  return (
    <CSSTransition
      unmountOnExit
      in={show}
      addEndListener={(node, done) => {
        node.addEventListener('transitionend', done, false)
      }}
      onEnter={(node) => {
        node.classList.add(...enterClasses, ...enterFromClasses)
      }}
      onEntering={(node) => {
        node.classList.remove(...enterFromClasses);
        node.classList.add(...enterToClasses)
      }}
      onEntered={(node) => {
        node.classList.remove(...enterToClasses, ...enterClasses)
      }}
      onExit={(node) => {
        node.classList.add(...leaveClasses, ...leaveFromClasses)
      }}
      onExiting={(node) => {
        node.classList.remove(...leaveFromClasses);
        node.classList.add(...leaveToClasses)
      }}
      onExited={(node) => {
        node.classList.remove(...leaveToClasses, ...leaveClasses)
      }}
    >
      {children}
    </CSSTransition>
  );
}
