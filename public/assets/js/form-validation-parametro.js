// npm package: jquery-validation
// github link: https://github.com/jquery-validation/jquery-validation

$(function () {
  'use strict';

  $.validator.setDefaults({
    submitHandler: function () {
      //need to send the csrf_token with the ajax post request.
      // var data = $("#formularioParametros").serialize();
      // //get accion of the form
      // var action = $("#formularioParametros").attr('action');

      // $.ajax({
      //   url: action,
      //   type: "POST",
      //   csrf_token: $('meta[name="_token"]').attr('content'),
      //   data: data,
      //   success: function (response) {
      //     const modifiedURL = window.location.href.replace(/\/parametros\/.*$/, "/parametros/edit");
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
    const modifiedURL = window.location.href.replace(/\/parametros\/.*$/, "/");
    window.location.href = modifiedURL;
  });
  $(function () {
    // validamos el formulario de cliente
    $("#formularioParametros").validate({
      rules: {
        //hace referencia a la etiqueta name del input
        nombre_empresa: {
          //seteamos si es requerido o si queremos evaluar otra condicion
          required: true,
          minlength: 3
        },
        tel_1: {
          required: true
        },
        tel_2: {
          required: false
        },
        dir_1: {
          required: true,
          minlength: 3
        },
        dir_2: {
          required: false,
          minlength: 3
        },
        cuit: {
          required: true,
          minlength: 8
        },
        
        multimoneda: {
          required: true
        },
        dolar: {
          required: true
        },
      },
      //seteamos los mensajes para cada caso
      messages: {
        razon_social: {
          required: "Ingresa una razon social valida",
          minlength: "Ingrese al menos 3 caracteres"
        },
        email: {
          required: "Ingresa un email valido",
        },
        tel_1: {
          required: "Ingresa un numero de telefono valido"
        },
        direccion: {
          required: "Ingrese una direccion valida",
          minlength: "La direccion debe tener al menos 3 caracteres"
        },
        dni_cuit: {
          required: "Ingrese una DNI/CUIT valido",
          minlength: "Ingrese al menos 8 caracteres"
        },
        
        multimoneda: {
          required: "Seleccione una moneda",
        },
        dolar: {
          required: "Ingrese una moneda",
        },
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