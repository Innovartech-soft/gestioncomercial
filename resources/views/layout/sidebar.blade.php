<nav class="sidebar">
  <div class="sidebar-header">
    {{-- <a href="#" class="sidebar-brand">
      Hereled<span></span>
    </a> --}}
    <a href="#" class="sidebar-brand">
      <img src="{{ url('assets/images/Logo_hereled.png') }}" alt="logo" width="80%" height="5%">
    </a>
    <div class="sidebar-toggler not-active">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </div>
  <div class="sidebar-body">
    <ul class="nav">
      <li class="nav-item nav-category">Principal</li>
      <li class="nav-item {{ active_class(['/']) }}">
        <a href="{{ url('/') }}" class="nav-link">
          <i class="link-icon" data-feather="home"></i>
          <span class="link-title">Dashboard</span>
        </a>
      </li>
      <li class="nav-item nav-category">Ventas</li>
      <li class="nav-item {{ active_class(['Venta/*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#Venta" role="button"
          aria-expanded="{{ is_active_route(['importar/*']) }}" aria-controls="auth">
          <i class="link-icon" data-feather="shopping-cart"></i>
          <span class="link-title"> Ventas</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['Venta/*']) }}" id="Venta">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="#" class="nav-link"
                onclick="setCookieAndOpenLink(event, '{{Config::get('app.cors_allow_origin')}}')">Gestión de Venta</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('venta/index') }}" class="nav-link {{ active_class(['venta/index']) }}">Listado de
                Ventas</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('venta/indexCerradas') }}" class="nav-link {{ active_class(['venta/indexCerradas']) }}">Listado de Cerradas</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('venta/indexAnuladas') }}" class="nav-link {{ active_class(['venta/indexAnuladas']) }}">Listado de Anuladas</a>
            </li>
          </ul>
        </div>
      </li>
        <li class="nav-item nav-category">Cuenta Corriente</li>
        <li class="nav-item {{ active_class(['cuentaCorriente/*']) }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#cuentaCorriente" role="button"
               aria-expanded="{{ is_active_route(['cuentacorriente/*']) }}" aria-controls="auth">
                <i class="link-icon" data-feather="trending-up"></i>
                <span class="link-title"> Cuenta Corriente</span>
                <i class="link-arrow" data-feather="chevron-down"></i>
            </a>
            <div class="collapse {{ show_class(['cuentaCorriente/*']) }}" id="cuentaCorriente">
                <ul class="nav sub-menu">
                    <li class="nav-item">
                        <a href="{{ url('cuentacorriente/index') }}"
                           class="nav-link {{ active_class(['cuentacorriente/index']) }}">Movimientos de C.Corriente</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('recibo/index') }}" class="nav-link {{ active_class(['recibo/index']) }}">Listado de
                            Recibos</a>
                    </li>
                </ul>
            </div>
        </li>
{{--        <li class="nav-item nav-category">Clientes</li>--}}
{{--        <li class="nav-item {{ active_class(['cliente/*']) }}">--}}
{{--            <a class="nav-link" data-bs-toggle="collapse" href="#cliente" role="button"--}}
{{--               aria-expanded="{{ is_active_route(['cliente/*']) }}" aria-controls="auth">--}}
{{--                <i class="link-icon" data-feather="user-plus"></i>--}}
{{--                <span class="link-title"> Clientes</span>--}}
{{--                <i class="link-arrow" data-feather="chevron-down"></i>--}}
{{--            </a>--}}
{{--            <div class="collapse {{ show_class(['cliente/*']) }}" id="cliente">--}}
{{--                <ul class="nav sub-menu">--}}
{{--                    <li class="nav-item">--}}
{{--                        <a href="{{ url('cliente/index') }}" class="nav-link {{ active_class(['cliente/login']) }}">Listado de--}}
{{--                            Clientes</a>--}}
{{--                    </li>--}}
{{--                    <li class="nav-item">--}}
{{--                        <a href="{{ url('cliente/create') }}" class="nav-link {{ active_class(['cliente/register']) }}">Alta de--}}
{{--                            Cliente</a>--}}
{{--                    </li>--}}

{{--                </ul>--}}
{{--            </div>--}}
{{--        </li>--}}

      <li class="nav-item nav-category">Cheques</li>
      <li class="nav-item {{ active_class(['cheques/*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#cheques" role="button"
          aria-expanded="{{ is_active_route(['cheques/*']) }}" aria-controls="auth">
          <i class="link-icon" data-feather="credit-card"></i>
          <span class="link-title"> Cheques</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['cheques/*']) }}" id="cheques">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ url('cheque/index') }}" class="nav-link {{ active_class(['cheques/index']) }}">Gestión de
                Cheques</a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item nav-category">Productos</li>
      <li class="nav-item {{ active_class(['producto/*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#producto" role="button"
          aria-expanded="{{ is_active_route(['producto/*']) }}" aria-controls="auth">
          <i class="link-icon" data-feather="package"></i>
          <span class="link-title"> Productos</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['producto/*']) }}" id="producto">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ url('producto/index') }}" class="nav-link {{ active_class(['producto/login']) }}">Listado de
                Productos</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('producto/create') }}" class="nav-link {{ active_class(['producto/register']) }}">Alta de
                Producto</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('producto/indexByStockMinimo') }}"
                class="nav-link {{ active_class(['producto/register']) }}">Informe de Stock</a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item nav-category">Rubros</li>
      <li class="nav-item {{ active_class(['rubro/*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#rubro" role="button"
          aria-expanded="{{ is_active_route(['rubro/*']) }}" aria-controls="auth">
          <i class="link-icon" data-feather="layers"></i>
          <span class="link-title"> Rubros</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['rubro/*']) }}" id="rubro">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ url('rubro/index') }}" class="nav-link {{ active_class(['rubro/login']) }}">Listado de
                Rubros</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('rubro/create') }}" class="nav-link {{ active_class(['rubro/register']) }}">Alta de
                Rubro</a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item nav-category">Listas</li>
      <li class="nav-item {{ active_class(['lista/*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#lista" role="button"
          aria-expanded="{{ is_active_route(['lista/*']) }}" aria-controls="auth">
          <i class="link-icon" data-feather="layers"></i>
          <span class="link-title"> Listas</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['lista/*']) }}" id="lista">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ url('lista/index') }}" class="nav-link {{ active_class(['lista/login']) }}">Listado de
                Listas</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('lista/create') }}" class="nav-link {{ active_class(['lista/register']) }}">Alta de
                Lista</a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item nav-category">Marcas</li>
      <li class="nav-item {{ active_class(['marca/*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#marca" role="button"
          aria-expanded="{{ is_active_route(['marca/*']) }}" aria-controls="auth">
          <i class="link-icon" data-feather="layers"></i>
          <span class="link-title"> Marcas</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['marca/*']) }}" id="marca">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ url('marca/index') }}" class="nav-link {{ active_class(['marca/login']) }}">Listado de
                Marcas</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('marca/create') }}" class="nav-link {{ active_class(['marca/register']) }}">Alta de
                Marca</a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item nav-category">Proveedores</li>
      <li class="nav-item {{ active_class(['proveedor/*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#proveedor" role="button"
          aria-expanded="{{ is_active_route(['proveedor/*']) }}" aria-controls="auth">
          <i class="link-icon" data-feather="truck"></i>
          <span class="link-title"> Proveedores</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['proveedor/*']) }}" id="proveedor">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ url('proveedor/index') }}" class="nav-link {{ active_class(['proveedor/login']) }}">Listado de
                Proveedores</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('proveedor/create') }}" class="nav-link {{ active_class(['proveedor/register']) }}">Alta
                De Proveedor</a>
            </li>
          </ul>
        </div>
      </li>


      <li class="nav-item nav-category">Clientes</li>
      <li class="nav-item {{ active_class(['cliente/*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#cliente" role="button"
          aria-expanded="{{ is_active_route(['cliente/*']) }}" aria-controls="auth">
          <i class="link-icon" data-feather="user-plus"></i>
          <span class="link-title"> Clientes</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['cliente/*']) }}" id="cliente">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ url('cliente/index') }}" class="nav-link {{ active_class(['cliente/login']) }}">Listado de
                Clientes</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('cliente/create') }}" class="nav-link {{ active_class(['cliente/register']) }}">Alta de
                Cliente</a>
            </li>

          </ul>
        </div>
      </li>
      <li class="nav-item nav-category">Vendedores</li>
      <li class="nav-item {{ active_class(['vendedor/*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#vendedor" role="button"
          aria-expanded="{{ is_active_route(['vendedor/*']) }}" aria-controls="auth">
          <i class="link-icon" data-feather="users"></i>
          <span class="link-title"> Vendedores</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['vendedor/*']) }}" id="vendedor">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ url('vendedor/index') }}" class="nav-link {{ active_class(['vendedor/login']) }}">Listado de
                Vendedores</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('vendedor/create') }}" class="nav-link {{ active_class(['vendedor/register']) }}">Alta de
                Vendedor</a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item nav-category">Usuarios</li>
      <li class="nav-item {{ active_class(['auth/*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#auth" role="button"
          aria-expanded="{{ is_active_route(['auth/*']) }}" aria-controls="auth">
          <i class="link-icon" data-feather="user"></i>
          <span class="link-title"> Usuarios</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['auth/*']) }}" id="auth">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ url('auth/index') }}" class="nav-link ">Listado de Usuarios</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('auth/register') }}" class="nav-link {{ active_class(['auth/register']) }}">Registrar
                Usuario</a>
            </li>
          </ul>
        </div>
      </li>
        <li class="nav-item nav-category">Caja Diaria</li>
        <li class="nav-item {{ active_class(['/*']) }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#cajadiaria" role="button"
               aria-expanded="{{ is_active_route(['cajadiaria/*']) }}" aria-controls="auth">
                <i class="link-icon" data-feather="dollar-sign"></i>
                <span class="link-title"> Caja Diaria</span>
                <i class="link-arrow" data-feather="chevron-down"></i>
            </a>
            <div class="collapse {{ show_class(['cajadiaria/*']) }}" id="cajadiaria">
                <ul class="nav sub-menu">
                    <li class="nav-item">
                        <a href="{{ url('cajadiaria/index') }}"
                           class="nav-link {{ active_class(['cajadiaria/index']) }}">Detalles Historico Caja</a>
                    </li>
                </ul>
            </div>
        </li>
      <li class="nav-item nav-category">Parametros del Sistema</li>
      <li class="nav-item {{ active_class(['parametros/*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#parametros" role="button"
          aria-expanded="{{ is_active_route(['parametros/*']) }}" aria-controls="auth">
          <i class="link-icon" data-feather="settings"></i>
          <span class="link-title"> Parametros</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['parametros/*']) }}" id="parametros">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{url('parametros/edit')}}"
                class="nav-link {{ active_class(['parametros/login']) }}">Parametros</a>
            </li>
          </ul>
        </div>
      </li>

    </ul>
  </div>
</nav>
<script>
  var token = "{{ session('token') }}";
    // console.log('Valor del token:', token);
        function setCookieAndOpenLink(event, link) {
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
        var urlWithToken = link + '?token=' + encodeURIComponent(token);

        // Agregar el token al encabezado de la solicitud
        var headers = {
            'Authorization': 'Bearer ' + token
        };

        // Abrir el enlace en una nueva pestaña con el token en la URL y en el encabezado de la solicitud
        window.open(urlWithToken, "_blank");
    }
</script>
