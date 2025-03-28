$(document).ready(function() {
//Funcion para actualizar el precio de costo de un producto
$('#acronimo').on('focusout', function () {
    // Capturar el valor del campo de entrada
    var valor = $(this).val();
    var rubroId = ($('#id').val()!="")?$('#id').val():0;
    // Referencia al elemento
    var $elemento = $(this);

    $.ajax({
        url: '/rubro/getByAcronimoJSON/'+rubroId+'/'+valor,
        method: 'GET',
        success: function (response) {
            console.log(response);
            if(response.encontrado){
                //alert('repetido');
                var colorOriginal = $elemento.css('background-color');

// Cambiar el color del elemento
$elemento.css('background-color', '#e6a7a3').fadeIn(500, function () {
    // Después de la animación, mostrar el texto temporal
    $elemento.after('<small class="mensaje-temporal">El acrónimo ingresado ya existe, ingrese uno distinto.</small>');

    // Después de 1 segundo, revertir el color y eliminar el texto temporal
    setTimeout(function () {
        $elemento.fadeOut(300, function () {
            $elemento.css('background-color', colorOriginal).fadeIn(300);
            $('.mensaje-temporal').fadeOut('slow', function () {
                $(this).remove();
                $elemento.val('');
            });
        });
    }, 1000);
});
            }
        },
        error: function (xhr, status, error) {
            console.error(error);
            // Al hacer focusout, cambiar el color a verde y luego revertir en 1 segundo
            $elemento.css('background-color', '#e6a7a3').fadeIn(500, function () {
                // Después de la animación, esperar 1 segundo y revertir el color
                setTimeout(function () {
                    $elemento.fadeOut(300, function () {
                        $elemento.css('background-color', 'transparent').fadeIn(300);
                    });
                }, 1000);
            });
        }
    });
});

});