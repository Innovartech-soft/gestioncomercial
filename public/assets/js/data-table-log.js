$(function () {
  $('#dataTableLog').DataTable({
      processing: true,
      serverSide: true,
      ajax: urlData, 
      order: [[2, 'desc']],
      "aLengthMenu": [
          [10, 30, 50, -1],
          [10, 30, 50, "Todos"]
      ],
      "iDisplayLength": 30,
      "language": {
          "lengthMenu": "Mostrar _MENU_ registros por página",
          search: "",
          "url": "/assets/js/Spanish.json",
      },
      columns: [
          { data: 'id_usuario', name: 'id_usuario' },
          { data: 'mensaje', name: 'mensaje' },
          { data: 'created_at', name: 'created_at' }
      ]
  });

  $('#dataTableLog').each(function () {
      var datatable = $(this);
      var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
      search_input.attr('placeholder', 'Buscar logs');
      search_input.removeClass('form-control-sm');

      var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
      length_sel.removeClass('form-control-sm');
  });
});
