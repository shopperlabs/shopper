import intlTelInput from 'intl-tel-input'

export default (element) => ({
  input: element,

  init() {
    const phoneNumber = document.querySelector(this.input);
    let iti = intlTelInput(phoneNumber, {
      nationalMode: true,
      geoIpLookup: function(success, failure) {
        fetch('https://ipinfo.io').then(response => {
          let countryCode = (response && response.country) ? response.country : 'CM'
          success(countryCode)
        })
      },
      utilsScript: 'https://unpkg.com/intl-tel-input@17.0.3/build/js/utils.js'
    })
    let handleChange = () => {
      if (iti.isValidNumber()) {
        phoneNumber.value = iti.getNumber()
      }
    };
    phoneNumber.addEventListener('change', handleChange)
    phoneNumber.addEventListener('keyup', handleChange)
  }
})
