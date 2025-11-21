<nav class="sidebar">
  <div class="sidebar-header">
    <a href="#" class="sidebar-brand">
      <img src="{{ url('assets/images/logoheader.png') }}" alt="logo" width="80%" height="5%">
    </a>
    <div class="sidebar-toggler not-active">
      <span></span><span></span><span></span>
    </div>
  </div>

  <div class="sidebar-body">
    <ul class="nav">

      <!-- PRINCIPAL -->
      <li class="nav-item nav-category">Principal</li>
      <li class="nav-item {{ active_class(['/']) }}">
        <a href="{{ url('/') }}" class="nav-link">
          <i class="link-icon" data-feather="home"></i>
          <span class="link-title">Dashboard</span>
        </a>
      </li>

      <!-- VENTAS -->
      <li class="nav-item nav-category">Ventas</li>
      <li class="nav-item {{ active_class(['venta/*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#venta"
           aria-expanded="{{ is_active_route(['venta/*']) }}" aria-controls="venta">
          <i class="link-icon" data-feather="shopping-cart"></i>
          <span class="link-title">Ventas</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>

        <div class="collapse {{ show_class(['venta/*']) }}" id="venta">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ url('venta/gestionVentas') }}"
                class="nav-link {{ active_class(['venta/gestionVentas']) }}">Gestion de
                ventas</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('venta/index') }}" class="nav-link {{ active_class(['venta/index']) }}">Listado de Ventas</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('venta/indexcerradas') }}" class="nav-link {{ active_class(['venta/indexcerradas']) }}">Listado de Cerradas</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('venta/indexanuladas') }}" class="nav-link {{ active_class(['venta/indexanuladas']) }}">Listado de Anuladas</a>
            </li>
          </ul>
        </div>
      </li>

      <!-- CUENTA CORRIENTE -->
      <li class="nav-item nav-category">Cuenta Corriente</li>
      <li class="nav-item {{ active_class(['cuentacorriente/*', 'recibo/*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#cuentacorriente"
           aria-expanded="{{ is_active_route(['cuentacorriente/*']) }}" aria-controls="cuentacorriente">
          <i class="link-icon" data-feather="trending-up"></i>
          <span class="link-title">Cuenta Corriente</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>

        <div class="collapse {{ show_class(['cuentacorriente/*', 'recibo/*']) }}" id="cuentacorriente">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ url('cuentacorriente/index') }}" class="nav-link {{ active_class(['cuentacorriente/index']) }}">
                Movimientos de C.Corriente
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('recibo/index') }}" class="nav-link {{ active_class(['recibo/index']) }}">
                Listado de Recibos
              </a>
            </li>
          </ul>
        </div>
      </li>

      <!-- CHEQUES -->
      <li class="nav-item nav-category">Cheques</li>
      <li class="nav-item {{ active_class(['cheque/*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#cheque"
           aria-expanded="{{ is_active_route(['cheque/*']) }}" aria-controls="cheque">
          <i class="link-icon" data-feather="credit-card"></i>
          <span class="link-title">Cheques</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>

        <div class="collapse {{ show_class(['cheque/*']) }}" id="cheque">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ url('cheque/index') }}" class="nav-link {{ active_class(['cheque/index']) }}">
                Gestión de Cheques
              </a>
            </li>
          </ul>
        </div>
      </li>

      <!-- PRODUCTOS -->
      <li class="nav-item nav-category">Productos</li>
      <li class="nav-item {{ active_class(['producto/*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#producto"
           aria-expanded="{{ is_active_route(['producto/*']) }}" aria-controls="producto">
          <i class="link-icon" data-feather="package"></i>
          <span class="link-title">Productos</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>

        <div class="collapse {{ show_class(['producto/*']) }}" id="producto">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ url('producto/index') }}" class="nav-link {{ active_class(['producto/index']) }}">
                Listado de Productos
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('producto/create') }}" class="nav-link {{ active_class(['producto/create']) }}">
                Alta de Producto
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('producto/indexByStockMinimo') }}" class="nav-link {{ active_class(['producto/indexByStockMinimo']) }}">
                Informe de Stock
              </a>
            </li>
          </ul>
        </div>
      </li>

      <!-- RUBROS -->
      <li class="nav-item nav-category">Rubros</li>
      <li class="nav-item {{ active_class(['rubro/*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#rubro"
           aria-expanded="{{ is_active_route(['rubro/*']) }}" aria-controls="rubro">
          <i class="link-icon" data-feather="layers"></i>
          <span class="link-title">Rubros</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>

        <div class="collapse {{ show_class(['rubro/*']) }}" id="rubro">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ url('rubro/index') }}" class="nav-link {{ active_class(['rubro/index']) }}">Listado de Rubros</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('rubro/create') }}" class="nav-link {{ active_class(['rubro/create']) }}">Alta de Rubro</a>
            </li>
          </ul>
        </div>
      </li>

      <!-- LISTA DESCUENTO -->
      <li class="nav-item nav-category">Listas de Descuento</li>
      <li class="nav-item {{ active_class(['lista/*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#lista"
           aria-expanded="{{ is_active_route(['lista/*']) }}" aria-controls="lista">
          <i class="link-icon" data-feather="layers"></i>
          <span class="link-title">Listas Descuento</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>

        <div class="collapse {{ show_class(['lista/*']) }}" id="lista">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ url('lista/index') }}" class="nav-link {{ active_class(['lista/index']) }}">Listado de Listas</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('lista/create') }}" class="nav-link {{ active_class(['lista/create']) }}">Alta de Lista</a>
            </li>
          </ul>
        </div>
      </li>

      <!-- LISTA GANANCIA -->
      <li class="nav-item nav-category">Listas de Ganancia</li>
      <li class="nav-item {{ active_class(['listaganancia/*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#listaganancia"
           aria-expanded="{{ is_active_route(['listaganancia/*']) }}" aria-controls="listaganancia">
          <i class="link-icon" data-feather="layers"></i>
          <span class="link-title">Listas Ganancia</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>

        <div class="collapse {{ show_class(['listaganancia/*']) }}" id="listaganancia">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ url('listaganancia/index') }}" class="nav-link {{ active_class(['listaganancia/index']) }}">Listado de Listas</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('listaganancia/create') }}" class="nav-link {{ active_class(['listaganancia/create']) }}">Alta de Lista</a>
            </li>
          </ul>
        </div>
      </li>

      <!-- MARCAS -->
      <li class="nav-item nav-category">Marcas</li>
      <li class="nav-item {{ active_class(['marca/*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#marca"
           aria-expanded="{{ is_active_route(['marca/*']) }}" aria-controls="marca">
          <i class="link-icon" data-feather="layers"></i>
          <span class="link-title">Marcas</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>

        <div class="collapse {{ show_class(['marca/*']) }}" id="marca">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ url('marca/index') }}" class="nav-link {{ active_class(['marca/index']) }}">Listado de Marcas</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('marca/create') }}" class="nav-link {{ active_class(['marca/create']) }}">Alta de Marca</a>
            </li>
          </ul>
        </div>
      </li>

      <!-- PROVEEDORES -->
      <li class="nav-item nav-category">Proveedores</li>
      <li class="nav-item {{ active_class(['proveedor/*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#proveedor"
           aria-expanded="{{ is_active_route(['proveedor/*']) }}" aria-controls="proveedor">
          <i class="link-icon" data-feather="truck"></i>
          <span class="link-title">Proveedores</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>

        <div class="collapse {{ show_class(['proveedor/*']) }}" id="proveedor">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ url('proveedor/index') }}" class="nav-link {{ active_class(['proveedor/index']) }}">Listado de Proveedores</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('proveedor/create') }}" class="nav-link {{ active_class(['proveedor/create']) }}">Alta de Proveedor</a>
            </li>
          </ul>
        </div>
      </li>

      <!-- CLIENTES -->
      <li class="nav-item nav-category">Clientes</li>
      <li class="nav-item {{ active_class(['cliente/*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#cliente"
           aria-expanded="{{ is_active_route(['cliente/*']) }}" aria-controls="cliente">
          <i class="link-icon" data-feather="user-plus"></i>
          <span class="link-title">Clientes</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>

        <div class="collapse {{ show_class(['cliente/*']) }}" id="cliente">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ url('cliente/index') }}" class="nav-link {{ active_class(['cliente/index']) }}">Listado de Clientes</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('cliente/create') }}" class="nav-link {{ active_class(['cliente/create']) }}">Alta de Cliente</a>
            </li>
          </ul>
        </div>
      </li>

      <!-- VENDEDORES -->
      <li class="nav-item nav-category">Vendedores</li>
      <li class="nav-item {{ active_class(['vendedor/*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#vendedor"
           aria-expanded="{{ is_active_route(['vendedor/*']) }}" aria-controls="vendedor">
          <i class="link-icon" data-feather="users"></i>
          <span class="link-title">Vendedores</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>

        <div class="collapse {{ show_class(['vendedor/*']) }}" id="vendedor">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ url('vendedor/index') }}" class="nav-link {{ active_class(['vendedor/index']) }}">Listado de Vendedores</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('vendedor/create') }}" class="nav-link {{ active_class(['vendedor/create']) }}">Alta de Vendedor</a>
            </li>
          </ul>
        </div>
      </li>

      <!-- USUARIOS -->
      <li class="nav-item nav-category">Usuarios</li>
      <li class="nav-item {{ active_class(['auth/*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#auth"
           aria-expanded="{{ is_active_route(['auth/*']) }}" aria-controls="auth">
          <i class="link-icon" data-feather="user"></i>
          <span class="link-title">Usuarios</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>

        <div class="collapse {{ show_class(['auth/*']) }}" id="auth">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ url('auth/index') }}" class="nav-link {{ active_class(['auth/index']) }}">Listado de Usuarios</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('auth/register') }}" class="nav-link {{ active_class(['auth/register']) }}">Registrar Usuario</a>
            </li>
          </ul>
        </div>
      </li>

      <!-- CAJA DIARIA -->
      <li class="nav-item nav-category">Caja Diaria</li>
      <li class="nav-item {{ active_class(['cajadiaria/*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#cajadiaria"
           aria-expanded="{{ is_active_route(['cajadiaria/*']) }}" aria-controls="cajadiaria">
          <i class="link-icon" data-feather="dollar-sign"></i>
          <span class="link-title">Caja Diaria</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>

        <div class="collapse {{ show_class(['cajadiaria/*']) }}" id="cajadiaria">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ url('cajadiaria/index') }}" class="nav-link {{ active_class(['cajadiaria/index']) }}">
                Detalles Historico Caja
              </a>
            </li>
          </ul>
        </div>
      </li>

      <!-- PARÁMETROS -->
      <li class="nav-item nav-category">Parametros del Sistema</li>
      <li class="nav-item {{ active_class(['parametros/*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#parametros"
           aria-expanded="{{ is_active_route(['parametros/*']) }}" aria-controls="parametros">
          <i class="link-icon" data-feather="settings"></i>
          <span class="link-title">Parametros</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>

        <div class="collapse {{ show_class(['parametros/*']) }}" id="parametros">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{url('parametros/edit')}}" class="nav-link {{ active_class(['parametros/edit']) }}">
                Parametros
              </a>
            </li>
          </ul>
        </div>
      </li>

    </ul>
  </div>
</nav>

<script>
  var token = "{{ session('token') }}";

  function setCookieAndOpenLink(event, link) {
    event.preventDefault();
    const csrfToken = document.querySelector('meta[name="_token"]').getAttribute('content');
    document.cookie = `csrf_token=${csrfToken}`;
    var urlWithToken = link + '?token=' + encodeURIComponent(token);
    window.open(urlWithToken, "_blank");
  }

 
</script>
