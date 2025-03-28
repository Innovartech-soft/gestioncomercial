// npm package: flatpickr
// github link: https://github.com/flatpickr/flatpickr

$(function() {
  'use strict';

  // date picker
  if($('#flatpickr-date').length) {
    flatpickr("#flatpickr-date", {
      locale: "es", // Establece el idioma a español
      wrap: true,
      dateFormat: "d-m-Y",
    });
      $('#flatpickr-date').attr('required', 'required');
  }

    if($('#flatpickr-date-oferta').length) {
        flatpickr("#flatpickr-date", {
            locale: "es", // Establece el idioma a español
            wrap: true,
            dateFormat: "d-m-Y",
            // required: true,
        });
        $('#flatpickr-date-oferta').attr('required', 'required');

    }

    // if($('#flatpickr-date').length) {
    //     flatpickr("#flatpickr-date", {
    //         wrap: true,
    //         dateFormat: "Y-m-d",
    //     });
    // }


  // time picker
  if($('#flatpickr-time').length) {
    flatpickr("#flatpickr-time", {
      locale: "es", // Establece el idioma a español
      wrap: true,
      enableTime: true,
      noCalendar: true,
      dateFormat: "H:i",
    });
  }

});
