@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/@mdi/css/materialdesignicons.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0">Viajes</h4>
  </div>
  <div class="d-flex align-items-center flex-wrap text-nowrap">
    <a href="{{ route('viaje.create') }}" class="btn btn-primary mr-3 mr-md-2">
      <i class="mdi mdi-plus-circle mr-1"></i> Nuevo
    </a>
  </div>
</div>

<div class="row">
  @include('pages.mensajesflash.index')

  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Listado de Viajes</h6>
        <div class="table-responsive">
          <table id="dataTablePlanillas" class="table">
            <thead>
              <tr>
                <th>#N</th>
                {{-- <th>Numero venta_id</th> --}}
                <th>Estado</th>
                <th>Repartidor</th>
                <th>Cant. Boletas</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach($Viajes as $Viaje)
              <tr>
                <td>{{ $Viaje->id }}</td>


                {{-- <td>{{ $planilla->numero_venta_id }}</td> --}}

                <td>{{ $Viaje->estadoViaje->nombre }}</td>
                <td>{{ $Viaje->repartidor->nombre ?? 'No asignado' }}</td>
                <td>{{ count($Viaje->detalleViajes) }}</td>
                <td class="text-end">
                  <a href="#" class="btn btn-info btn-sm me-1 btnVer" data-id-viaje="{{ $Viaje->id}}"><i class="mdi mdi-eye-outline"></i></a>
                  <a href="{{ route('viaje.edit', $Viaje->id) }}" class="btn btn-primary btn-sm">
                    <i class="mdi mdi-pencil"></i>
                  </a>
                  <a href="#" class="btn btn-data sbtn-sm btnCambiarEstado" data-viaje-id="{{ $Viaje->id }}">
                    <i class="mdi mdi-swap-horizontal"></i>
                  </a>
                  <a href="{{ route('viaje.export', $Viaje->id) }}" class="btn btn-success btn-sm">
                    <i class="mdi mdi-file-excel"></i>
                  </a>
                  <form onsubmit="eliminarAlert(event,this)" action="{{ route('viaje.destroy',$Viaje->id) }}"
                    method="POST" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm"><i class="mdi mdi-delete"></i></button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal para cambiar estado -->
<div class="modal fade" id="modalCambiarEstado" tabindex="-1" role="dialog" aria-labelledby="modalCambiarEstadoLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="formCambiarEstado" method="POST" action="{{ route('viaje.cambiarEstado') }}">
      @csrf
      <input type="hidden" name="viaje_id" id="modalViajeId">

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Cambiar estado del viaje</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar">
          </button>
        </div>

        <div class="modal-body">
          <div class="form-group">
            <label for="estado_id">Nuevo estado</label>
            <select name="estado_id" id="estado_id" class="form-control" required>
              @foreach($estados as $estado)
                <option value="{{ $estado->id }}">{{ $estado->nombre }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Cambiar estado</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </form>
  </div>
</div>
@include('pages.viajes.view-modal')

@endsection

@push('plugin-scripts')
<script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
<script src="{{ asset('assets/js/register-delete.js') }}"></script>
@endpush

@push('custom-scripts')
<script>
  $(document).ready(function() {
   
    // Custom search input
    $('#dataTablePlanillas').DataTable({
      language: {
        search: "Buscar:",
        lengthMenu: "Mostrar _MENU_ registros por página",
        zeroRecords: "No se encontraron resultados",
        info: "Mostrando página _PAGE_ de _PAGES_",
        infoEmpty: "No hay registros disponibles",
        infoFiltered: "(filtrado de _MAX_ registros totales)",
        paginate: {
          first: "Primero",
          last: "Último",
          next: "Siguiente",
          previous: "Anterior"
        }
      }
    });
  });
</script>
<script>
  $(document).on('click', '.btnCambiarEstado', function(e) {
    e.preventDefault();
    let viajeId = $(this).data('viaje-id');
    $('#modalViajeId').val(viajeId);
    $('#modalCambiarEstado').modal('show');
  });
  $(document).on('click', '.btnVer', function (e) {
  e.preventDefault();
  let viajeId = $(this).data('id-viaje');
  $.ajax({
    url: `/viaje/view/${viajeId}`,
    method: 'GET',
    success: function (response) {
      console.log(response);
      $('#viajeNumero').text(response.viaje.id);
      $('#viajeRepartidor').text(response.viaje.repartidor || 'No asignado');
      let fecha = new Date(response.viaje.creacion);

      let dia = String(fecha.getDate()).padStart(2, '0');
      let mes = String(fecha.getMonth() + 1).padStart(2, '0'); // ¡Recordá que enero es 0!
      let anio = fecha.getFullYear();
      let fechaFormateada = `${dia}/${mes}/${anio}`;
      $('#viajeFecha').text(fechaFormateada);

      let tbody = $('#tablaDetalleViaje tbody');
      tbody.empty();

      response.detalles.forEach(detalle => {
      let montoHTML = parseFloat(detalle.monto) > 0 ? `$${parseFloat(detalle.monto).toFixed(1)}` : '';
      let saldoHTML = parseFloat(detalle.saldo) > 0 ? `$${parseFloat(detalle.saldo).toFixed(1)}` : '';

      tbody.append(`
        <tr>
          <td>${detalle.cliente_id + '-' + detalle.vendedor}</td>
          <td>${detalle.cliente}</td>
          <td>${detalle.venta_id}</td>
          <td>$${parseFloat(detalle.importe).toFixed(1)}</td>
          <td>${montoHTML}</td>
          <td>${saldoHTML}</td>
        </tr>
      `);
    });


      $('#modalDetallesViaje').modal('show');
    },
    error: function () {
      alert('Hubo un error al cargar los detalles del viaje.');
    }
  });
});
//impresion
function imprimirDiv(idDiv) {
  var contenido = document.getElementById(idDiv).innerHTML;

  var ventanaImpresion = window.open('', '', 'height=600,width=800');

  ventanaImpresion.document.write('<html><head><title>Detalle Viaje</title>');
  
  // Podés incluir estilos si usás Bootstrap o personalizados
  //ventanaImpresion.document.write('<link rel="stylesheet" href="/assets/css/bootstrap.min.css">');
  ventanaImpresion.document.write('<style>table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #000; padding: 6px; text-align: left; }</style>');

  ventanaImpresion.document.write('</head><body>');
  ventanaImpresion.document.write(contenido);
  ventanaImpresion.document.write('</body></html>');

  ventanaImpresion.document.close();
  ventanaImpresion.focus();

  setTimeout(() => {
    ventanaImpresion.print();
    ventanaImpresion.close();
  }, 500); // Espera medio segundo para asegurar que se cargue todo
}

</script>
@endpush