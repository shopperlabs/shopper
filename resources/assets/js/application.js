/**
 * Shopper.js
 *
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 *
 * @author Arthur Monney<contact@arthurmonney.me>
 * @version 1.0.0
 * @since November 2019
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
