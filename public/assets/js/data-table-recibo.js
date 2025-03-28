$(function () {
  $('#dataTableRecibos').DataTable({
      processing: true,
      serverSide: true,
      ajax: urlData,
      order: [[0, 'desc']],
      aLengthMenu: [
          [15, 30, 50, -1],
          [15, 30, 50, "Todos"]
      ],
      iDisplayLength: 15,
      language: {
          lengthMenu: "Mostrar _MENU_ registros por página",
          search: "",
          url: "/assets/js/Spanish.json",
      },
      columns: [
          { data: 'id', name: 'id' },
          { data: 'created_at', name: 'created_at', render: function(data) { return moment(data).format('DD/MM/YYYY'); } },
          { data: 'tipo', name: 'tipo' },
          { data: 'monto', name: 'monto', render: $.fn.dataTable.render.number(',', '.', 1, '$') },
          { data: 'metodo_pago', name: 'metodo_pago', orderable: false, searchable: false },
          { data: 'cliente', name: 'cliente' },
          { data: 'proveedor', name: 'proveedor' },
          { data: 'usuario', name: 'usuario' },
          { data: 'acciones', name: 'acciones', orderable: false, searchable: false }
      ],
      drawCallback: function () {
          var datatable = $('#dataTableRecibos');
          var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
          search_input.attr('placeholder', 'Buscar Recibo');
          search_input.removeClass('form-control-sm');
          var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
          length_sel.removeClass('form-control-sm');
      }
  });
});
