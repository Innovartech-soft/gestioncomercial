// npm package: datatables.net-bs5
// github link: https://github.com/DataTables/Dist-DataTables-Bootstrap5

$(function () {
  'use strict';

  $(function () {
    $('#dataTableVentas').DataTable({
      processing: true,
      serverSide: true,
      ajax: urlData,
      order: [[0, 'desc']],
      aLengthMenu: [
        [10, 30, 50, -1],
        [10, 30, 50, "Todos"]
      ],
      iDisplayLength: 10,
      language: {
        lengthMenu: "Mostrar _MENU_ registros por página",
        search: "",
        url: "/assets/js/Spanish.json",
      },
      columns: [
        { data: 'numero_venta', name: 'ventas.id' },
        { data: 'fecha', name: 'ventas.fecha' },
        { data: 'total', name: 'ventas.total', render: $.fn.dataTable.render.number(',', '.', 1, '$ ') },
        { data: 'cliente', name: 'ventas.nombre_cliente' },
        { data: 'vendedor', name: 'vendedores.nombre' },
        { data: 'usuario', name: 'usuarios.nombre' }
      ],
      drawCallback: function () {
        var datatable = $('#dataTableVentas');
        var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
        search_input.attr('placeholder', 'Buscar Operacion');
        search_input.removeClass('form-control-sm');
        var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
        length_sel.removeClass('form-control-sm');
      }
    });
  });

});
