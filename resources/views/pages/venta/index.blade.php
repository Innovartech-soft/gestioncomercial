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
    <h4 class="mb-3 mb-md-0">Ventas y Presupuestos</h4>
  </div>
  <div class="d-flex align-items-center flex-wrap text-nowrap">

    <a href="#" onclick="setCookieAndOpenLink(event, '{{Config::get('app.cors_allow_origin')}}')"
      class="btn btn-primary mr-3 mr-md-2"><i class="mdi mdi-plus-circle mr-1"></i> Nuevo</a>
  </div>
</div>

<div class="row">
  @include('pages.mensajesflash.index')
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title"></h6>
        <div class="table table-responsive">
          <table id="dataTableVentas" class="table table-hover">
            <thead>
              <tr>
                <th>Nº</th>
                <th>Fecha</th>
                <th>Operacion</th>
                <th>Monto</th>
                <th>Cliente</th>
                <th>Vendedor</th>
                <th>Usuario</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($ventas as $venta)
              <tr @if($venta->tipoVenta->id == 3/*VENTA */){{ ($venta->pagada==0?'class=fila-color-verde':'') }}@endif>
                <td>{{ $venta->numero_venta }}</td>
                <td>{{ date('d/m/Y', strtotime($venta->fecha)) }}</td>
                <td>{{ $venta->tipoVenta->nombre }}</td>
                <td>$ {{ round($venta->total,1)}}</td>
                <td>{{ $venta->nombre_cliente }}</br>
                  <small>
                   <form action="{{route('cuentacorriente.indexByCliente')}}" method="POST" style="display:inline;">
                      @csrf
                      <input type="hidden" name="id_cliente" value="{{$venta->id_cliente}}">
                      <button type="submit" class="link-button">Ver Cuenta Corriente</button>
                    </form>
                  </small>
                </td>
                <td>{{optional($venta->vendedor)->nombre }}</td>
                <td>{{ optional($venta->usuario)->nombre }}</td>
                     
                <td class="text-end">
                  @if ($venta->pagada!=1&&$venta->tipoVenta->id == 3/*VENTA */)
                  <a href="#" title="Cerrar Venta" type="button" class="btn btn-success btn-sm btnCerrarVenta" data-bs-toggle="modal"
                    data-bs-target="#formCerrarVenta" data-id-venta="{{$venta->id}}"
                    data-fecha="{{ date('d/m/Y', strtotime($venta->fecha)) }}"
                    data-id-vendedor="{{ $venta->vendedor->id }}" data-vendedor="{{ $venta->vendedor->nombre }}"
                    data-monto="{{ round($venta->total,1)}}"><i class="mdi mdi-currency-usd"></i></a>
                  @endif
                  @if ($venta->tipoVenta->id == 3/*VENTA */&&$venta->tiene_pago==1)
                    <a href="#" title="Ver Pagos" type="button" class="btn btn-info btn-sm btnPagos" data-bs-toggle="modal"
                      data-bs-target="#formPagos" data-id-venta="{{$venta->id}}"
                      data-total="{{ round($venta->total,1)}}"><i class="mdi mdi-cash-clock"></i></a>
                  @endif
                  <form action="{{ route('venta.imprimirventa',$venta->id) }}" method="POST"
                    style="display: inline-block;">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-sm"><i class="mdi mdi-printer" title="Imprimir"></i></button>
                  </form>              

                  <a href="#" title="Ver Comprobante" type="button" class="btn btn-data btn-sm btnVerComprobante" data-bs-toggle="modal"
                    data-bs-target="#detalleVenta" data-id-venta="{{$venta->id}}"><i class="mdi mdi-receipt-text-outline"></i></a>

                  @if($venta->id_tipo_venta != 4 && $venta->id_tipo_venta!=5)
                  <a href="#" title="Editar" id="{{$venta->id}}"
                    onclick="setCookieAndVentaId(event, '{{Config::get('app.cors_allow_origin')}}','{{$venta->id}}')"
                    class="btn btn-primary btn-sm"><i class="mdi mdi-pencil"></i></a>
                  @endif
                  <form onsubmit="eliminarAlert(event)" action="{{ route('venta.destroy',$venta->id) }}" method="POST"
                    style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button title="Eliminar" type="submit" class="btn btn-danger btn-sm" {{($venta->pagada==1?'disabled':'')}}><i class="mdi mdi-delete" ></i></button>
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

@include('pages/venta/show-modal-venta')
@include('pages/comision/form-modal-venta')
@include('pages/recibo/index-pagos-modal')
@endsection

@push('plugin-scripts')
<!-- Plugin js import here -->
<script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>

@endpush

@push('custom-scripts')
<!-- Custom js here -->
<script src="{{ asset('assets/js/comision-venta-script.js') }}"></script>
<script src="{{ asset('assets/js/pagos-venta-script.js') }}"></script>
<script src="{{ asset('assets/js/ver-venta-script.js') }}"></script>
<script src="{{ asset('assets/js/register-delete.js') }}"></script>
<script src="{{ asset('assets/js/register-save.js') }}"></script>
<script src="{{ asset('assets/js/data-table-ventas.js') }}"></script>
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