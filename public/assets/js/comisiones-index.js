$(document).ready(() => {
    $('.btnBuscarComisiones').on('click', async () => {
        try {
            const idVendedor = $('#id_vendedor_comision').val();
            const fechaDesde = $('#fecha_desde').val();
            const fechaHasta = $('#fecha_hasta').val();
            const administrador =$('#administrador').val();
            // Convertir fecha al formato yyyy-mm-dd
            const fechaDesdeFormatted = formatDateYMD(fechaDesde);
            const fechaHastaFormatted = formatDateYMD(fechaHasta);

            const response = await $.ajax({
                url: `/comision/getComisionesJSON/${idVendedor}/${fechaDesdeFormatted}/${fechaHastaFormatted}`, //Espera formato YYYY-MM-DD
                type: 'GET',
            });

            console.log(response);

            // Verificar si hay resultados en la respuesta
            if (response.comisiones.length > 0) {
                $('#rowListado').fadeIn('slow');
                $('#rowNotas').fadeIn('slow');
                $('#resultadoMensaje').fadeOut('slow');
            } else {
                $('#rowListado').fadeOut('slow');
                $('#rowNotas').fadeOut('slow');
                $('#resultadoMensaje').html('No se encontraron comisiones pendientes para el período seleccionado.');
                $('#resultadoMensaje').fadeIn('slow');
            }

            // Limpiar el cuerpo de la tabla
            $('#comisionesTBody').empty();
            var totalComisiones = 0;
            const idsComisiones = [];

            if(administrador==0){
                $('#thGanancia').hide();
            }else{
                $('#thGanancia').show();
            }
            response.comisiones.forEach(comision => {
                if(comision.venta !== null){
                    idsComisiones.push(comision.id);
                    totalComisiones += comision.monto;
                    $('#comisionesTBody').append(`
                        <tr data-id="${comision.id}">
                            <td>${comision.numero_venta}</td>
                            <td>${formatDateDMY(comision.fecha)}</td>
                            ${administrador == 1 ? `<td>$${comision.ganancia_venta}</td>` : ''} 
                            <td>$${comision.monto.toFixed(1)}</td>
                            <td><button type="button" class="btn btn-danger btn-sm eliminarComision" data-id="${comision.id}">X</button></td>
                        </tr>
                    `);
                }
            });
            
            $('#idsComisiones').val(idsComisiones.join(','));
            $('#totalComisiones').html('<b>Total Comisiones: $'+totalComisiones.toFixed(1)+'</b>');

        } catch (error) {
            console.error('Error al cargar los datos:', error);
            alert('Error al cargar los datos');
        }
    });

    $(document).on('click', '.eliminarComision', function() {
        const idComision = $(this).data('id');
        // Eliminar la fila de la tabla
        $(this).closest('tr').remove();
        
        // Actualizar el array de idsComisiones eliminando el id correspondiente
        let idsComisiones = $('#idsComisiones').val().split(',');
        idsComisiones = idsComisiones.filter(id => id != idComision);
        $('#idsComisiones').val(idsComisiones.join(','));
    
        // Recalcular el total de comisiones
        let totalComisiones = 0;
        $('#comisionesTBody tr').each(function() {
            const monto = parseFloat($(this).find('td').eq(3).text().replace('$', ''));
            totalComisiones += monto;
        });
        $('#totalComisiones').html('<b>Total Comisiones: $' + totalComisiones.toFixed(1) + '</b>');
    });
    
});

// Función para formatear la fecha al formato yyyy-mm-dd
function formatDateYMD(dateString) {
    const [day, month, year] = dateString.split('-');
    return `${year}-${month}-${day}`;
}

// Función para formatear la fecha al formato yyyy-mm-dd
function formatDateDMY(dateString) {
    const [year, month, day] = dateString.split('-');
    return `${day}/${month}/${year}`;
}
