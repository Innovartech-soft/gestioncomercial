// npm package: jquery-validation
// github link: https://github.com/jquery-validation/jquery-validation


$(function () {
  'use strict';

  $.validator.setDefaults({
    submitHandler: function () {
      // //need to send the csrf_token with the ajax post request.
      // var data = $("#formularioLista").serialize();
      // //get accion of the form
      // var action = $("#formularioLista").attr('action');

      // $.ajax({
      //   url: action,
      //   type: "POST",
      //   csrf_token: $('meta[name="_token"]').attr('content'),
      //   data: data,
      //   success: function (response) {

      //     const modifiedURL = window.location.href.replace(/\/lista\/.*$/, "/lista/index");
      //     window.location.href = modifiedURL;


      //   },
      //   error: function (response) {
      //     console.log(response);

      //   }
      // });
      // //
      return true;

    }
  });
  $("#cancelButton").click(function () {
    const modifiedURL = window.location.href.replace(/\/lista\/.*$/, "/lista/index");
    window.location.href = modifiedURL;
  });
  $(function () {
    // validamos el formulario de cliente
    $("#formularioLista").validate({
      rules: {
        //hace referencia a la etiqueta name del input
        nombre: {
          //seteamos si es requerido o si queremos evaluar otra condicion
          required: true,
          minlength: 3
        },
        valor: {
          //seteamos si es requerido o si queremos evaluar otra condicion
          required: true,
        }
      },
      //seteamos los mensajes para cada caso
      messages: {
        nombre: {
          required: "Ingresa un nombre valido",
          minlength: "Ingrese al menos 3 caracteres"
        },
        valor: {
          required: "Ingresa un valor valido",
        }

      },
      errorPlacement: function (error, element) {
        error.addClass("invalid-feedback");

        if (element.parent('.input-group').length) {
          error.insertAfter(element.parent());
        }
        else if (element.prop('type') === 'radio' && element.parent('.radio-inline').length) {
          error.insertAfter(element.parent().parent());
        }
        else if (element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
          error.appendTo(element.parent().parent());
        }
        else {
          error.insertAfter(element);
        }
      },
      highlight: function (element, errorClass) {
        if ($(element).prop('type') != 'checkbox' && $(element).prop('type') != 'radio') {
          $(element).addClass("is-invalid").removeClass("is-valid");
        }
      },
      unhighlight: function (element, errorClass) {
        if ($(element).prop('type') != 'checkbox' && $(element).prop('type') != 'radio') {
          $(element).addClass("is-valid").removeClass("is-invalid");
        }
      }
    });
  });
});