@extends('layout.master')

@push('plugin-styles')
<!-- Plugin css import here -->
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />

<link href="{{ asset('assets/plugins/@mdi/css/materialdesignicons.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<!-- Page content here -->
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0">Ventas Cerradas</h4>
  </div>
  <div class="d-flex align-items-center flex-wrap text-nowrap">
    <button type="button" class="btn btn-success mr-3 mr-md-2" data-bs-toggle="modal" data-bs-target="#formExportar"
      style="margin-right: 10px;">
      <i class="mdi mdi-file-excel mr-1"></i> Exportar
    </button>
  </div>
</div>

<div class="row">
  @include('pages.mensajesflash.index')
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Ventas Cerradas</h6>
        <div class="table table-responsive">
          <table id="dataTableVentas" class="table table-hover">
            <thead>
              <tr>
                <th>Nº</th>
                <th>Fecha</th>
                <th>Fecha Pago</th>
                <th>Monto</th>
                <th>Cliente</th>
                <th>Vendedor</th>
                <th>Usuario</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($ventas as $venta)
              <tr>
                <td>{{ $venta->numero_venta }}</td>
                <td>{{ date('d/m/Y', strtotime($venta->fecha)) }}</td>
                <td>{{ $venta->fecha_pago?date('d/m/Y', strtotime($venta->fecha_pago)):'N/A' }}</td>
                <td>$ {{ round($venta->total,1)}}</td>
                <td>{{ $venta->nombre_cliente }}</td>
                <td><a href="{{ route('vendedor.edit',$venta->vendedor->id )}}">{{ $venta->vendedor->nombre }}</a></td>
                <td>{{ $venta->usuario->nombre }}</td>
                <td class="text-end">
                  @if ($venta->tipoVenta->id == 3/*VENTA */&&$venta->tiene_pago==1)
                    <a href="#" title="Ver Pagos" type="button" class="btn btn-info btn-sm btnPagos" data-bs-toggle="modal"
                      data-bs-target="#formPagos" data-id-venta="{{$venta->id}}" data-total="{{$venta->total}}"
                      data-monto="{{ round($venta->total,1)}}"><i class="mdi mdi-cash-clock"></i></a>
                  @endif
                  <form action="{{ route('venta.imprimirventa',$venta->id) }}" method="POST"
                    style="display: inline-block;">
                    @csrf
                    <button type="submit" title="Imprimir" class="btn btn-secondary btn-sm"><i class="mdi mdi-printer"></i></button>
                  </form>
                  <a href="#" title="Ver Comprobante" type="button" class="btn btn-data btn-sm btnVerComprobante" data-bs-toggle="modal"
                    data-bs-target="#detalleVenta" data-id-venta="{{$venta->id}}"><i class="mdi mdi-receipt-text-outline"></i></a>
                  <a href="#" title="Ver" id="{{$venta->id}}"
                    onclick="setCookieAndVentaId(event, '{{Config::get('app.cors_allow_origin')}}','{{$venta->id}}')"
                    class="btn btn-primary btn-sm"><i class="mdi mdi-pencil"></i></a>
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
@include('pages/venta/show-modal-venta')
@include('pages/recibo/index-pagos-modal')
@include('pages.venta.cerrada-export')

@endsection

@push('plugin-scripts')
<!-- Plugin js import here -->
<script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>

@endpush

@push('custom-scripts')
<!-- Custom js here -->
<script src="{{ asset('assets/js/ver-venta-script.js') }}"></script>
<script src="{{ asset('assets/js/pagos-venta-script.js') }}"></script>
<script src="{{ asset('assets/js/register-delete.js') }}"></script>
<script src="{{ asset('assets/js/register-save.js') }}"></script>
<script src="{{ asset('assets/js/data-table-ventas-cerradas.js') }}"></script>
<script>
  var token = "{{ session('token') }}";
  // var venta_id = "{{}}"
      //console.log('Valor del token:', token);
      function setCookieAndVentaId(event, link, venta_id) {
      event.preventDefault();
      // Get the CSRF token from the meta tag
      const csrfToken = document.querySelector('meta[name="_token"]').getAttribute('content');
      
      // Log the CSRF token to the console
      console.log('CSRF Token:', csrfToken);
      // Almacenar el CSRF token en una cookie con nombre 'csrf_token'
      document.cookie = `csrf_token=${csrfToken}`;
      
      // Verificar si la cookie se ha establecido correctamente
      const storedCSRFToken = document.cookie.replace(/(?:(?:^|.*;\s*)csrf_token\s*=\s*([^;]*).*$)|^.*$/, "$1");
      console.log('CSRF Token almacenado en la cookie:', storedCSRFToken);
      // Obtener el usuario autenticado
      //var user = @json(auth()->user());
      
      // Concatenar el token a la URL
      var urlWithToken = link + '?token=' + encodeURIComponent(token) + '&regId='+ venta_id;
      
      // Agregar el token al encabezado de la solicitud
      var headers = {
      'Authorization': 'Bearer ' + token
      };
      
      // Abrir el enlace en una nueva pestaña con el token en la URL y en el encabezado de la solicitud
      window.open(urlWithToken, "_blank");
      }
</script>
@endpush