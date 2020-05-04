/**
 * Template script file.
 *
 * Every javascript single action must write here.
 */
import axios from "axios";
import jquery from "jquery";
import flatpickr from "flatpickr";
import "select2";
import intlTelInput from "intl-tel-input";

const $: JQueryStatic = jquery;

// Remove items on CRUD
const element = document.getElementById('remove-item');
if (element) {
  const span: any = element.firstElementChild;
  const url: any = element.getAttribute('data-url');

  element.addEventListener('click', (e) => {
    e.preventDefault();
    span.classList.remove('hidden');
    axios.delete(url, {
      headers: {
        "X-Requested-With": "XMLHttpRequest"
      }
    }).then((response) => {
      setTimeout(() => {
        window.location.href = response.data.redirect_url;
      }, 1000);
    });
  });
}

// Datepicker
flatpickr(".timepicker", {
  enableTime: true,
  noCalendar: true,
  dateFormat: "H:i",
  time_24hr: true
});

flatpickr(".datepicker", {
  minDate: "today"
});

// Phone input
const phoneInput = document.getElementById('phone_number');
if (phoneInput) {
    const iti = intlTelInput(phoneInput, {
        nationalMode: true,
        initialCountry: "auto",
        geoIpLookup(callback) {
            $.get('https://ipinfo.io', () => { return ''; }, "jsonp").always((resp) => {
                const countryCode = (resp && resp.country) ? resp.country : "";
                callback(countryCode);
            });
        },
        utilsScript: require("intl-tel-input/build/js/utils.js"),
    });

    const handleChange = () => {
        if (iti.isValidNumber()) {
            // @ts-ignore
            phoneInput.value = iti.getNumber();
        }
    };

    phoneInput.addEventListener('change', handleChange);
    phoneInput.addEventListener('keyup', handleChange);
}

// jQuery Script
$(() => {
  $('.select-2').select2({
    placeholder: "Search...",
  });
});
