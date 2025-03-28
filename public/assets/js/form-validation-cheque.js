// npm package: jquery-validation
// github link: https://github.com/jquery-validation/jquery-validation


$(function () {
  'use strict';

  $.validator.setDefaults({
    submitHandler: function () {
      //need to send the csrf_token with the ajax post request.
      // var data = $("#formularioMarca").serialize();
      // //get accion of the form
      // var action = $("#formularioMarca").attr('action');

      // $.ajax({
      //   url: action,
      //   type: "POST",
      //   csrf_token: $('meta[name="_token"]').attr('content'),
      //   data: data,
      //   success: function (response) {

      //     const modifiedURL = window.location.href.replace(/\/marca\/.*$/, "/marca/index");
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
    const modifiedURL = window.location.href.replace(/\/marca\/.*$/, "/marca/index");
    window.location.href = modifiedURL;
  });
  $(function () {
    // validamos el formulario de cliente
    $("#formularioCheque").validate({
      rules: {
        //hace referencia a la etiqueta name del input
        banco_emisor: {
          //seteamos si es requerido o si queremos evaluar otra condicion
          required: true,
          minlength: 3
        },
          id_cliente:{
            required: true,
          },
          numero:{
              required: true,
          },
        numero_cheque: {
          //seteamos si es requerido o si queremos evaluar otra condicion
          required: true,
          minlength: 5,
          type: "number"
        },
        fecha_emision: {
          //seteamos si es requerido o si queremos evaluar otra condicion
          required: true,
          type: "date"
        },
        fecha_pago: {
          //seteamos si es requerido o si queremos evaluar otra condicion
          required: true,
          type: "date"
        },
        importe: {
          //seteamos si es requerido o si queremos evaluar otra condicion
          required: true,
          //only numbers
          type: "number"
        },
        nombre_beneficiario: {
          //seteamos si es requerido o si queremos evaluar otra condicion
          required: true,
          minlength: 3
        },
        id_recibo: {
          //seteamos si es requerido o si queremos evaluar otra condicion
          required: false,
        },
        titular_librador: {
          //seteamos si es requerido o si queremos evaluar otra condicion
          required: true,
          minlength: 3
        },
        serie: {
          //seteamos si es requerido o si queremos evaluar otra condicion
          required: true,
          minlength: 3,
          //only numbers
          type: "number"
        },


      },
      //seteamos los mensajes para cada caso
      messages: {
        banco_emisor: {
          required: "Ingresa un banco emisor valido",
          minlength: "Ingrese al menos 3 caracteres"
        },
        numero_cheque: {
          required: "Ingresa un numero de cheque valido",
          minlength: "Ingrese al menos 3 caracteres",
          type: "Ingrese solo numeros"
        },
        fecha_emision: {
          required: "Ingresa una fecha de emision valida",
          type: "Ingrese una fecha valida"
        },
        fecha_vencimiento: {
          required: "Ingresa una fecha de vencimiento valida",
          type: "Ingrese una fecha valida"
        },
        importe: {
          required: "Ingresa un importe valido",
          type: "Ingrese solo numeros"
        },
        nombre_beneficiario: {
          required: "Ingresa un nombre de beneficiario valido",
          minlength: "Ingrese al menos 3 caracteres"
        },
        id_cliente: {
          required: "Ingresa un cliente valido",
        },
        id_recibo: {
          required: "Ingresa un recibo valido",
        },
        titular_liberador: {
          required: "Ingresa un titular liberador valido",
          minlength: "Ingrese al menos 3 caracteres"
        },
        serie: {
          required: "Ingresa una serie valida",
          minlength: "Ingrese al menos 3 caracteres",
          type: "Ingrese solo numeros"
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
