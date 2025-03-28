$(document).ready(function() {
  //Acciones al hacer click y abrir la ventana modal
  $('.btnCerrarVenta').on('click', function() {
  var ventaId = $(this).data('id-venta');
  var fecha = $(this).data('fecha');
  var vendedorId = $(this).data('id-vendedor');
  var vendedor = $(this).data('vendedor');
  var monto = $(this).data('monto');

  //vaciar campos
  $('#ventaNumero').val('');
  $('#ventaFecha').val('');
  $('#ventaVendedor').val('');
  $('#id_vendedor').val('');
  $('#id_venta').val('');
  $('#ventaTotal').val('');

  $('#ventaNumero').val(ventaId);
  $('#ventaFecha').val(fecha);
  $('#ventaVendedor').val(vendedor);
  $('#id_vendedor').val(vendedorId);
  $('#ventaTotal').val(monto);
  $('#id_venta').val(ventaId);

  });

  $('.btnPagarComision').on('click', function() {
    var comisionId = $(this).data('id-comision');
    var monto = $(this).data('monto');
    //vaciar campos
    //$('#montoComision').val('');
    var route = '/comision/'+comisionId+'/update';
    $('#formComision').attr('action', route);
    $('#montoComision').val('$'+monto);
  
    });
});
