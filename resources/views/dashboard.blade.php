@extends('layout.master')

@push('plugin-styles')

<link href="{{ asset('assets/plugins/@mdi/css/materialdesignicons.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/@mdi/css/materialdesignicons.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.css') }}" rel="stylesheet" />
<style>
  .timeLineCaja {
    max-width: 100%;
    /* Establece el ancho máximo deseado */
    max-height: 400px;
    /* Establece la altura máxima deseada */
    overflow: auto;
    /* Añade una barra de desplazamiento si es necesario */
    border: none;
    /* Añade un borde opcional */
  }

  .timeLineCaja .event {
    font-size: 12px;
  }

  .estadoNombreMin {
    font-size: 13px;
    font-style: italic;
  }
</style>
@endpush


@section('content')

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  @include('pages.mensajesflash.index')
  <div>
    <h3 class="mb-3 mb-md-0">¡Bienvenido! {{isset($parametro) ? $parametro[0]->nombre_empresa :''}} - <i>{{Auth::user()->nombre}}</i></h3>
  </div>

</div>
<div class="row">
  <div class="col-lg-12 col-xl-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-baseline mb-2">
          <h6 class="card-title mb-0"></h6>
        </div>
        <div class="container">
          <div class="row">
            <div class="col-md-1 form-group text-center">
            </div>
            <div class="col-md-2 form-group text-center">
               <a href="#" class="nav-link"
                onclick="setCookieAndOpenLink(event, '{{Config::get('app.cors_allow_origin')}}')">
                <img src="{{ url('assets/images/ventas_4.png') }}" alt="logo" width="35%" height="65%"
                  data-toggle="tooltip" data-placement="top" title="Nueva Operacion">
              </a>
              <div class="form-label" style="margin-top: 5%;">
                <h5>Facturacion</h5>
              </div>
            </div>
            <div class="col-md-2 form-group text-center">
              <a class="nav-link" href="{{ url('cuentacorriente/index')}}">
                <img src="{{ url('assets/images/personal-banking.png') }}" alt="logo" width="35%" height="65%"
                  data-toggle="tooltip" data-placement="top" title="Cuentas Corrientes">
              </a>
              <div class="form-label" style="margin-top: 5%;">
                <h5>Cuentas Corrientes</h5>
              </div>
            </div>

            <div class="col-md-2 form-group text-center">
              <a class="nav-link" href="{{ url('vendedor/index')}}">
                <img src="{{ url('assets/images/customer.png') }}" alt="logo" width="35%" height="65%"
                  data-toggle="tooltip" data-placement="top" title="Vendedores y Comisiones">
              </a>
              <div class="form-label" style="margin-top: 5%;">
                <h5>Comisiones</h5>
              </div>
            </div>
            <div class="col-md-2 form-group text-center">
              <a class="nav-link" href="{{ url('producto/indexByStockMinimo')}}">
                <img src="{{ url('assets/images/productos_1.png') }}" alt="logo" width="35%" height="65%"
                  data-toggle="tooltip" data-placement="top" title="Productos en Stock Minimo">
              </a>
              <div class="form-label" style="margin-top: 5%;">
                <h5>Stock</h5>
              </div>
            </div>
             <div class="col-md-2 form-group text-center">
              <a class="nav-link" href="{{ url('venta/index')}}">
                <img src="{{ url('assets/images/order.png') }}" alt="logo" width="35%" height="65%"
                  data-toggle="tooltip" data-placement="top" title="Lista de Ventas">
              </a>
              <div class="form-label" style="margin-top: 5%;">
                <h5>Ventas Ultimos 7 Dias</h5>
              </div>
            </div>
            <div class="col-md-1 form-group text-center">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<br>

<div class="row">
  <div class="col-xl-3 col-lg-6">
    <div class="card card-stats mb-4 mb-xl-0">
      <div class="card-body">
        <div class="row">
          <div class="col">
            <h5 class="card-title text-uppercase text-muted mb-0">Cheques Disponibles</h5>
            <span class="h2 font-weight-bold mb-0">{{$totalChequesDisponibles}}</span>
          </div>
          <div class="col-auto">
            <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
              <i class="fas fa-chart-bar"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-lg-6">
    <div class="card card-stats mb-4 mb-xl-0">
      <div class="card-body">
        <div class="row">
          <div class="col">
            <h5 class="card-title text-uppercase text-muted mb-0">Productos En Stock Minimo</h5>
            <span class="h2 font-weight-bold mb-0">{{$totalProductosStockMinimo}}</span>
          </div>
          <div class="col-auto">
            <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
              <i class="fas fa-chart-pie"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-lg-6">
    <div class="card card-stats mb-4 mb-xl-0">
      <div class="card-body">
        <div class="row">
          <div class="col">
            <h5 class="card-title text-uppercase text-muted mb-0">Clientes Deudores</h5>
            <span class="h2 font-weight-bold mb-0">{{$totalClientesDeudores}}</span>
          </div>
          <div class="col-auto">
            <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
              <i class="fas fa-users"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-lg-6">
    <div class="card card-stats mb-4 mb-xl-0">
      <div class="card-body">
        <div class="row">
          <div class="col">
            <h5 class="card-title text-uppercase text-muted mb-0">Ventas Ultimos 7 Dias</h5>
            <span class="h2 font-weight-bold mb-0">{{$totalVentasUltimosSieteDias}}</span>
          </div>
          <div class="col-auto">
            <div class="icon icon-shape bg-info text-white rounded-circle shadow">
              <i class="fas fa-percent"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<br>
<div class="row">
  <div class="col-xl-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-baseline mb-2">
          <h6 class="card-title mb-0">Caja Diaria</h6>
        </div>
        <div class="container">
          <div class="row">
            <div class="col-md-6 mb-3 mr-3 text-center">
              <button type="button" class="btn btn-primary btn-60-percent" data-bs-toggle="modal"
                data-bs-target="#formCrearRecibo">
                <i class="mdi mdi-cash-multiple mr-1 icon-lg"></i> Registrar
              </button>
            </div>
            <div class="col-md-6 form-group text-center">
              <div class="row">
                <div class="col-md-6 form-group text-center">
                  <div class="row">
                    <div class="col-md-12">
                      <img src="{{ url('assets/images/cajero-automatico.png') }}" alt="logo" width="45%" height="85%">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12 text-center">
                      <button type="button" class="btn btn-primary btn-50-percent " data-bs-toggle="modal"
                        data-bs-target="#formCajaDiaria" {{auth()->User()->administrador==0?'disabled':''}}>
                        Ver Caja
                        <span id="estadoCajaLabel" class="estadoNombreMin"></span>
                      </button>

                    </div>
                  </div>
                  @if(Auth::user()->administrador==1)
                  <div class="row">
                    <div class="col-md-12 input-group">
                      <div class="input-group mb-3">
                        <label for="total_caja" class="form-label mt-2 md-4" style="margin-right: 5%">
                          <h5>Total</h5>
                        </label>
                        <div class="input-group-prepend">
                          <span class="input-group-text">$</span>
                        </div>
                        <input type="number" id="total_caja" name="total_caja" disabled placeholder="$"
                          class="form-control" value="{{$totalCajaHoy}}">
                      </div>
                    </div>
                  </div>
                  @endif
                </div>
              </div>

            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- row -->

@include('pages/recibo/form-modal')
@include('pages/cajadiaria/form-modal')
@endsection

@push('plugin-scripts')
<script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>

<script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
<script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.js') }}"></script>

@endpush

@push('custom-scripts')

<script src="{{ asset('assets/js/cheque-script.js') }}"></script>
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
<script src="{{ asset('assets/js/recibo-script.js') }}"></script>
<script src="{{ asset('assets/js/diariocaja-script.js') }}"></script>

<script>
  var token = "{{ session('token') }}";
    // console.log('Valor del token:', token);
        function setCookieAndOpenLink(event, link) {
        event.preventDefault();
        // Get the CSRF token from the meta tag
          const csrfToken = document.querySelector('meta[name="_token"]').getAttribute('content');

          // Log the CSRF token to the console
          // console.log('CSRF Token:', csrfToken);
          // Almacenar el CSRF token en una cookie con nombre 'csrf_token'
          document.cookie = `csrf_token=${csrfToken}`;

          // Verificar si la cookie se ha establecido correctamente
          const storedCSRFToken = document.cookie.replace(/(?:(?:^|.*;\s*)csrf_token\s*=\s*([^;]*).*$)|^.*$/, "$1");
          // console.log('CSRF Token almacenado en la cookie:', storedCSRFToken);
        // Obtener el usuario autenticado
        //var user = @json(auth()->user());

        // Concatenar el token a la URL
        var urlWithToken = link + '?token=' + encodeURIComponent(token);

        // Agregar el token al encabezado de la solicitud
        var headers = {
            'Authorization': 'Bearer ' + token
        };

        // Abrir el enlace en una nueva pestaña con el token en la URL y en el encabezado de la solicitud
        window.open(urlWithToken, "_blank");
    }
</script>
<script>
  var token = "{{ session('token') }}";

  $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>

@endpush
