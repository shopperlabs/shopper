import { useEffect, useState, useCallback, useRef } from 'preact/hooks'

/**
 * Value with the possibility to push an additional value
 *
 * @param initialValue
 * @returns {(*[]|(function(*): void)|*)[]}
 */
export function usePrepend (initialValue = []) {
  const [value, setValue] = useState(initialValue)
  return [
    value,
    useCallback(item => {
      setValue(v => [item, ...v])
    }, [])
  ]
}

/**
 * Effect hook to detect the click outside an element
 * @param ref
 * @param cb
 */
export function useClickOutside (ref, cb) {
  useEffect(() => {
    if (cb === null) {
      return
    }
    const escCb = e => {
      if (e.key === 'Escape' && ref.current) {
        cb()
      }
    }
    const clickCb = e => {
      if (ref.current && !ref.current.contains(e.target)) {
        cb()
      }
    }
    document.addEventListener('click', clickCb)
    document.addEventListener('keyup', escCb)
    return function cleanup () {
      document.removeEventListener('click', clickCb)
      document.removeEventListener('keyup', escCb)
    }
  }, [ref, cb])
}

/**
 * useEffect for an asynchronous function
 *
 * @param fn
 * @param deps
 */
export function useAsyncEffect (fn, deps = []) {
  /* eslint-disable */
  useEffect(() => {
    fn()
  }, deps)
  /* eslint-enable */
}

export const PROMISE_PENDING = 0
export const PROMISE_DONE = 1
export const PROMISE_ERROR = -1

/**
 * Decorates a promise and returns its status
 *
 * @param fn
 * @returns {unknown[]}
 */
export function usePromiseFn (fn) {
  const [state, setState] = useState(null)
  const resetState = useCallback(() => {
    setState(null)
  }, [])

  const wrappedFn = useCallback(
    async (...args) => {
      setState(PROMISE_PENDING)
      try {
        await fn(...args)
        setState(PROMISE_DONE)
      } catch (e) {
        setState(PROMISE_ERROR)
        throw e
      }
    },
    [fn]
  )

  return [state, wrappedFn, resetState]
}

/**
 * Hook to detect when an element becomes visible on the screen
 *
 * @export
 * @param {DOMNode reference} node
 * @param {Boolean} once
 * @param {Object} [options={}]
 * @returns {object} visibility
 */
export function useVisibility (node, once = true, options = {}) {
  const [visible, setVisible] = useState(false)
  const isIntersecting = useRef()

  const handleObserverUpdate = entries => {
    const ent = entries[0]

    if (isIntersecting.current !== ent.isIntersecting) {
      setVisible(ent.isIntersecting)
      isIntersecting.current = ent.isIntersecting
    }
  }

  const observer = once && visible ? null : new IntersectionObserver(handleObserverUpdate, options)

  useEffect(() => {
    const element = node instanceof HTMLElement ? node : node.current

    if (!element || observer === null) {
      return
    }

    observer.observe(element)

    return function cleanup () {
      observer.unobserve(element)
    }
  })

  return visible
}

