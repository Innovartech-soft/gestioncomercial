// npm package: datatables.net-bs5
// github link: https://github.com/DataTables/Dist-DataTables-Bootstrap5

$(function () {
  'use strict';

  $(function () {
    $('#dataTableVentas').DataTable({
      order: [[2, 'desc']],
      "aLengthMenu": [
        [10, 30, 50, -1],
        [10, 30, 50, "Todos"]
      ],
      "iDisplayLength": 10,
      "language": {
        "lengthMenu": "Mostrar _MENU_ registros por página",
        search: "",
        "url": "/assets/js/Spanish.json",
      },
      // Custom sorting function for dd/mm/yyyy dates
      columnDefs: [
        {
          targets: [2], // Apply to the second column (index 1)
          type: 'date',
          format: 'DD/MM/YYYY' // Specify date format for sorting
        }
      ],
    });
    $('#dataTableVentas').each(function () {
      var datatable = $(this);
      // SEARCH - Add the placeholder for Search and Turn this into in-line form control
      var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
      search_input.attr('placeholder', 'Buscar Operacion');
      search_input.removeClass('form-control-sm');
      // LENGTH - Inline-Form control
      var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
      length_sel.removeClass('form-control-sm');
    });
  });

});