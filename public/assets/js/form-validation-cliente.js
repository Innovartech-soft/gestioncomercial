// npm package: jquery-validation
// github link: https://github.com/jquery-validation/jquery-validation

$(function () {
  'use strict';

  $.validator.setDefaults({
    submitHandler: function () {
      return true;

    }
  });
  $("#cancelButton").click(function () {
    const modifiedURL = window.location.href.replace(/\/cliente\/.*$/, "/cliente/index");
    window.location.href = modifiedURL;
  });
  $(function () {
    // validamos el formulario de cliente
    $("#formularioCliente").validate({
      rules: {
        //hace referencia a la etiqueta name del input
        razon_social: {
          //seteamos si es requerido o si queremos evaluar otra condicion
          required: true,
          minlength: 3
        },
          id_lista: {
          required: true,
        },
          id_vendedor: {
              required: true,
          }
      },
      //seteamos los mensajes para cada caso
      messages: {
        razon_social: {
          required: "Ingresa una razon social valida",
          minlength: "Ingrese al menos 3 caracteres"
        },
          id_lista: {
          required: "Seleccione una lista de precios",
        },
          id_vendedor: {
              required: "Este campo es requerido",
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
