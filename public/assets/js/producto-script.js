$(document).ready(function() {

  let currentStock = 0;
  //Metodo para sumar el campo de ingreso al stock total
  $("#addStockButton").click(function(){
      const newStock = parseInt($("#stockInput").val()) || 0;
      $('#cantidad').val(newStock);

      currentStock += newStock;

      $('#totalStock').fadeOut('slow', function() {
            // Cambia el valor del campo input después de que se complete el fadeOut
            $(this).val(currentStock);
            // Realiza un fadeIn del campo input
            $(this).fadeIn('slow');
        });
      $('#stockInput').fadeOut('slow', function() {
            // Cambia el valor del campo input después de que se complete el fadeOut
            $(this).val('');
            // Realiza un fadeIn del campo input
            $(this).fadeIn('slow');
        });

  });

  //Acciones al hacer click y abrir la ventana modal
  $('.btnStockModal').on('click', function() {
  var productoId = $(this).data('id-producto');
  $('#cantidad').val(0);

  $.ajax({
    url: 'findProductoById/' + productoId, // Cambiamos la URL
    type: 'GET',
    success: function(response) {
      // console.log(response);

      //vaciar campos
      $('#stockInput').val('');
      $('#totalStock').val('');
      
      //TODO: incorporar el codigo para asignar la "route" correspondiente al producto seleccionado para acceder al historialStock
      let historicoStockUrl = "/producto/exportarHistoricoStockExcel"+'/'+productoId;

      // Asignar la URL dinámica al enlace de historial de stock
      $('.historico-stock').attr('href', historicoStockUrl);

      $('#formStock').attr("action", "/producto/updateStock/"+productoId); //Setea la ruta en el action para el form
      $("#totalStock").val(response.stock);
      currentStock = response.stock;

    },
    error: function(xhr, status, error) {
      alert(error);
      console.error(error);
    }
    });
  });

  //Acciones al hacer click y abrir la ventana modal
  $('.btnHistorialModal').on('click', function() {
    var productoId = $(this).data('id-producto');
    var precioActual = $(this).data('precio');
    
    $.ajax({
      url: 'getHistorialByProductoJSON/' + productoId, // Cambiamos la URL
      type: 'GET',
      success: function(response) {
        console.log(response);

        var montoTotal = 0;
        // Limpiar la lista antes de agregar nuevos elementos
        $('#precioFila').empty();
        $('#precioFila').hide();

        // Mostrar los resultados en la lista
        $.each(response, function (index, precio) {
            var listItem = '<li class="event" data-date=' + formatDate(precio.created_at) +'>'+
                '<h5 class="monto">$' + precio.precio + '</h5>' +
                '<small>'+precio.nombre+
                '</small></li>';
            $('#precioFila').append(listItem);
        });
        $('#precioActual').val(precioActual);
        if(response.length>0)
            $('#precioFila').show();

      },
      error: function(xhr, status, error) {
        alert(error);
        console.error(error);
      }
      });
    });

    //Funcion para actualizar el precio de costo de un producto
    $('.precioCosto').on('focusout', function () {
            // Capturar el valor del campo de entrada
            var valor = $(this).val();
            var productoId = $(this).data('id-producto');
            var token = $('meta[name="_token"]').attr('content');
            
            // Referencia al elemento para animar
            var $elemento = $(this);

            $.ajax({
                url: '/producto/updatePrecio/'+productoId,
                method: 'POST',
                data: {
                    precio: valor,
                    _token: token
                },
                success: function (response) {
                    console.log(response);

                    // Al hacer focusout, cambiar el color a verde y luego revertir en 1 segundo
                    if(response.precioActualizado){ //Si el precio se actualizo
                      $elemento.css('background-color', '#b0e6a3').fadeIn(500, function () {
                          // Después de la animación, esperar 1 segundo y revertir el color
                          setTimeout(function () {
                              $elemento.fadeOut(300, function () {
                                  $elemento.css('background-color', 'transparent').fadeIn(300);
                              });
                          }, 1000);
                      });
                      //Setea el nuevo precio como atributo data-precio del boton historico para ese producto especifico
                      var prodClass = '.prod'+productoId;
                      $(prodClass).data('precio',response.precio_costo);
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

  // Función para formatear la fecha
function formatDate(dateString) {
    var date = new Date(dateString);
    var day = ("0" + date.getUTCDate()).slice(-2);
    var month = ("0" + (date.getUTCMonth() + 1)).slice(-2);
    var year = date.getUTCFullYear();
    var hours = ("0" + date.getUTCHours()).slice(-2);
    var minutes = ("0" + date.getUTCMinutes()).slice(-2);
    var seconds = ("0" + date.getUTCSeconds()).slice(-2);

    var formattedDate = `${day}/${month}/${year} ${hours}:${minutes}:${seconds}`;
    return formattedDate;
}


});
