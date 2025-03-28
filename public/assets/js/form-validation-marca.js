// npm package: jquery-validation
// github link: https://github.com/jquery-validation/jquery-validation


$(function () {

 
  $("#cancelButton").click(function () {
    const modifiedURL = window.location.href.replace(/\/marca\/.*$/, "/marca/index");
    window.location.href = modifiedURL;
  });
  $(function () {
    // validamos el formulario de cliente
    $("#formularioMarca").validate({
      rules: {
        //hace referencia a la etiqueta name del input
        nombre: {
          //seteamos si es requerido o si queremos evaluar otra condicion
          required: true,
          minlength: 3
        }
      },
      //seteamos los mensajes para cada caso
      messages: {
        nombre: {
          required: "Ingresa un nombre valido",
          minlength: "Ingrese al menos 3 caracteres"
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
      },
      
    });
  });
});