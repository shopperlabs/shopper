'use strict';

import Notiflix from "notiflix-react";

Notiflix.Notify.Init({
  timeout: 5000
});

$(function() {

  let author = '<div style="position: fixed;bottom: 0;right: 20px;background-color: #fff;box-shadow: 0 4px 8px rgba(0,0,0,.05);border-radius: 3px 3px 0 0;font-size: 12px;padding: 5px 10px;">By <a href="https://arthurmonney.com">@monneyarthur</a> &nbsp;&bull;&nbsp; <a href="https://github.com/laravel-shopper/framework" target="_blank">See documentation</a></div>'
  $('body').append(author);

  $(`input[type='password'][data-eye]`).each(function(i) {
    let $this = $(this), id = 'eye-password-' + i, el = $('#' + id);

    $this.wrap($("<div/>", { style: 'position:relative', id: id }));
    $this.css({ paddingRight: 60 });

    $this.after($('<div/>', {
      html: 'Show',
      class: 'btn btn-primary btn-sm',
      id: 'passeye-toggle-'+i,
    }).css({
      position: 'absolute',
      right: 10,
      top: ($this.outerHeight() / 2) - 12,
      padding: '2px 7px',
      fontSize: 12,
      cursor: 'pointer',
    }));

    $this.after($('<input/>', {
      type: 'hidden',
      id: 'passeye-' + i
    }));

    let invalid_feedback = $this.parent().parent().find('.invalid-feedback');

    if(invalid_feedback.length) {
      $this.after(invalid_feedback.clone());
    }

    $this.on('keyup paste', function() {
      $('#passeye-'+i).val($(this).val());
    })
    $('#passeye-toggle-'+i).on('click', function() {
      if($this.hasClass('show')) {
        $this.attr('type', 'password');
        $this.removeClass('show');
        $(this).removeClass('btn-outline-primary');
      } else {
        $this.attr('type', 'text');
        $this.val($('#passeye-'+i).val());
        $this.addClass('show');
        $(this).addClass('btn-outline-primary');
      }
    });
  });

  $('.my-login-validation').submit(function(event) {
    event.preventDefault();
    event.stopPropagation();

    let form = $(this), button = $('#btn-login');

    button.attr('disabled', true)
      .html(`<span class='loader12'></span>`);

    if (form[0].checkValidity() === false) {
      button.removeAttr('disabled').html('Login');
      form.addClass('was-validated');
    }

    axios
      .post(form.attr('action'), JSON.stringify(form.serialize()))
      .then((response) => {
        button.removeAttr('disabled').html('Login');
        if (response.data.status === 'success') {
          Notiflix.Notify.Success(response.data.message);
          setInterval(function () {
            window.location.href = response.data.redirect_url;
          }, 2000);
        }
      })
      .catch((error) => {
        const errors = error.response.data.errors;

        if (errors) {
          Notiflix.Notify.Failure(error.response.data.errors.email[0]);
          button.removeAttr('disabled').html('Login')
        }
      });
  });
});
