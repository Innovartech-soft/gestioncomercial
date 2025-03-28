//Muestra el modal con el detalle de una venta
  $(document).ready(function () {
    $('.btnVerComprobante').click(function () {
        var idVenta = $(this).data('id-venta');
        $.ajax({
            type: 'GET',
            url: '/venta/verpdfventa/'+idVenta,
            success: function (response) {
                // Actualiza el contenido del modal con la respuesta
                $('#detalleVenta .modal-body').html(response);
                $('input[name="id_venta"]').val(idVenta);
                // Muestra la modal
                $('#detalleVenta').modal('show');
            },
            error: function (xhr, status, error) {
                alert("Error al cargar la vista");
                console.error(error);
            }
        });
    });
});