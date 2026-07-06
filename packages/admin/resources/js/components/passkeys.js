import { Passkeys, UserCancelledError } from '@laravel/passkeys'

export const passkeyLogin = ({ optionsUrl, loginUrl }) => ({
  supported: false,
  processing: false,
  error: null,

  async init() {
    this.supported = Passkeys.isSupported()

    if (!this.supported) {
      return
    }

    const response = await Passkeys.autofill({
      routes: { options: optionsUrl, submit: loginUrl },
    }).catch(() => undefined)

    if (response?.redirect) {
      window.location.assign(response.redirect)
    }
  },

  async login() {
    this.error = null
    this.processing = true

    try {
      const response = await Passkeys.verify({
        routes: { options: optionsUrl, submit: loginUrl },
      })

      if (response?.redirect) {
        window.location.assign(response.redirect)

        return
      }
    } catch (error) {
      if (!(error instanceof UserCancelledError)) {
        this.error = error.message
      }
    }

    this.processing = false
  },
})

export const passkeyManager = ({ optionsUrl, storeUrl }) => ({
  supported: false,

  init() {
    this.supported = Passkeys.isSupported()
  },

  async register(name) {
    try {
      await Passkeys.register({
        name,
        routes: { options: optionsUrl, submit: storeUrl },
      })

      window.Livewire.dispatch('passkeyRegistered')
    } catch (error) {
      if (error instanceof UserCancelledError) {
        return
      }

      window.Livewire.dispatch('passkeyRegistrationFailed', { message: error.message })
    }
  },
})
