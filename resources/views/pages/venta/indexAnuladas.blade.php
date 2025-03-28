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
    <h4 class="mb-3 mb-md-0">Ventas Anuladas</h4>
  </div>  
</div>

<div class="row">
  @include('pages.mensajesflash.index')
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Ventas Anuladas</h6>
        <div class="table table-responsive">
          <table id="dataTableVentas" class="table table-hover">
            <thead>
              <tr>
                <th>Nº</th>
                <th>Fecha</th>
                <th>Monto</th>
                <th>Cliente</th>
                <th>Vendedor</th>
                <th>Usuario</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($ventas as $venta)
              <tr>
                <td>{{ $venta->numero_venta }}</td>
                <td>{{ date('d/m/Y', strtotime($venta->fecha)) }}</td>
                <td>$ {{ round($venta->total,1)}}</td>
                <td>{{ $venta->nombre_cliente }}</td>
                <td><a href="{{ route('vendedor.edit',$venta->vendedor->id )}}">{{ $venta->vendedor->nombre }}</a></td>
                <td>{{ $venta->usuario->nombre }}</td>                
              </tr>
              @endforeach

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

@include('pages.venta.cerrada-export')
@include('pages/comision/form-modal-venta')
@endsection

@push('plugin-scripts')
<!-- Plugin js import here -->
<script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>

@endpush

@push('custom-scripts')
<!-- Custom js here -->
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