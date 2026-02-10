$(document).ready(function () {
    $(document).on('click', '.btnPagos', function () {
        var idVenta = $(this).data('id-venta');
    	var total = $(this).data('total');
        $.ajax({
            url: '/venta/getPagosByVentaJSON/' + idVenta,
            type: 'GET',
            success: function (response) {
                // console.log(response);
                var montoTotal = 0;
                // Limpiar la lista antes de agregar nuevos elementos
                $('#pagoFila').empty();
                $('#pagoFila').hide();

                // Mostrar los resultados en la lista
                $.each(response, function (index, pago) {
                    var listItem = '<li class="event" data-date=' + formatDate(pago.created_at) +'>'+
                        '<h5 class="monto">$' + pago.monto.toFixed(1) + '</h5>' +
                        '<small><a href="/recibo/'+pago.id_recibo+'">Recibo Nº '+pago.id_recibo+
                        '</a></small></li>';
                    $('#pagoFila').append(listItem);
                    montoTotal = montoTotal + pago.monto;
                });
                $('#totalVenta').val(total.toFixed(1));
                $('#totalPagos').val(montoTotal.toFixed(1));
                if(response.length>0)
                    $('#pagoFila').show();
            },
            error: function (xhr, status, error) {
                alert(error);
                console.error(error);
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
