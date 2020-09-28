/**
 * Shopper.js
 *
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using Laravel.
 *
 * @author Arthur Monney<arthur@shopperlabs.io>
 * @version 2.0.0
 * @since July 2019
 */

import "alpinejs";
import axios from "axios";

// Remove items on CRUD
const element = document.getElementById("remove-item");
if (element) {
  const span = element.firstElementChild;
  const url = element.getAttribute("data-url");

  element.addEventListener("click", e => {
    e.preventDefault();
    span.classList.remove("hidden");
    axios
      .delete(url, {
        headers: {
          "X-Requested-With": "XMLHttpRequest"
        }
      })
      .then(response => {
        setTimeout(() => {
          window.location.href = response.data.redirect_url;
        }, 1000);
      });
  });
}
